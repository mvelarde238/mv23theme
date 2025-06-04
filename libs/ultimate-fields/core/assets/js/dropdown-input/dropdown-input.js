class DropdownInput {
    constructor(wrapper, opciones = [], onSelect = null) {
        this.wrapper = wrapper;
        this.input = wrapper.querySelector('input');
        this.button = wrapper.querySelector('button');
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'dropdown';
        wrapper.appendChild(this.dropdown);

        this.opciones = opciones;
        this.filtradas = [...opciones];
        this.selectedIndex = -1;
        this.onSelect = onSelect;

        this.setupEvents();
    }

    setupEvents() {
        this.input.addEventListener('input', () => this.filtrarYMostrar());
        this.input.addEventListener('keydown', e => this.navegarPorTeclado(e));
        this.button.addEventListener('click', () => this.toggleDropdown());

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-wrapper')) {
                this.ocultarDropdown();
            }
        });
    }

    toggleDropdown() {
        if (this.dropdown.style.display === 'block') {
            this.ocultarDropdown();
        } else {
            this.filtradas = [...this.opciones];
            this.renderDropdown();
            this.mostrarDropdown();
        }
    }

    mostrarDropdown() {
        this.dropdown.style.display = 'block';
    }

    ocultarDropdown() {
        this.dropdown.style.display = 'none';
        this.selectedIndex = -1;
    }

    filtrarYMostrar() {
        const valor = this.input.value.toLowerCase();
        this.filtradas = this.opciones.filter(op => op.toLowerCase().includes(valor));
        this.renderDropdown();
        this.mostrarDropdown();
    }

    renderDropdown() {
        this.dropdown.innerHTML = '';
        this.selectedIndex = -1;

        if (this.filtradas.length === 0) {
            const empty = document.createElement('div');
            empty.textContent = 'Sin resultados';
            empty.classList.add('empty');
            this.dropdown.appendChild(empty);
            return;
        }

        this.filtradas.forEach((opcion, index) => {
            const div = document.createElement('div');
            div.textContent = opcion;
            div.addEventListener('click', () => {
                this.input.value = opcion;
                this.ocultarDropdown();
                if (typeof this.onSelect === 'function') {
                    this.onSelect(opcion);
                }
            });
            this.dropdown.appendChild(div);
        });
    }

    navegarPorTeclado(e) {
        if (this.dropdown.style.display !== 'block') return;

        const items = this.dropdown.querySelectorAll('div:not(.empty)');
        if (!items.length) return;

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex + 1) % items.length;
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex - 1 + items.length) % items.length;
                break;
            case 'Enter':
                e.preventDefault();
                if (this.selectedIndex >= 0) {
                    const valor = items[this.selectedIndex].textContent;
                    this.input.value = valor;
                    this.ocultarDropdown();
                    if (typeof this.onSelect === 'function') {
                        this.onSelect(valor);
                    }
                }
                return;
            case 'Escape':
                this.ocultarDropdown();
                return;
        }

        items.forEach(el => el.classList.remove('active'));
        if (this.selectedIndex >= 0) {
            items[this.selectedIndex].classList.add('active');
            items[this.selectedIndex].scrollIntoView({ block: 'nearest' });
        }
    }
}