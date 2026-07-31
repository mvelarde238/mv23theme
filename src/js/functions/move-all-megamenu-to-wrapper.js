function move_all_megamenu_to_wrapper(){
    // move all .megamenu elements to the .megamenus-wrapper container
	var navs = document.getElementsByClassName('menu-comp');

    for (var i = 0; i < navs.length; i++) {
        const megamenusWrapper = navs[i].querySelector('.megamenus-wrapper');
        const megamenus = navs[i].querySelectorAll('.megamenu');
        Array.prototype.forEach.call(megamenus, megamenu => {
            if (megamenusWrapper) {
                megamenusWrapper.appendChild(megamenu);
            }
        });
	}
}