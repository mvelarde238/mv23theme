// ****************************************************************************************************
// CREATE A TNS SLIDER
// ****************************************************************************************************
// Helper function to convert string to boolean
function toBool(value) {
    if (typeof value === 'string') {
        return value === 'true' || value === '1';
    }
    return Boolean(value);
}

function toNumber(value) {
    if (value === '' || value === undefined || value === null) {
        return undefined;
    }

    var parsed = Number(value);
    return Number.isNaN(parsed) ? undefined : parsed;
}

function splitTextValue(value, fallback) {
    if (!value || typeof value !== 'string') {
        return fallback;
    }

    var parts = value.split('|');
    return parts.length === 2 ? parts : fallback;
}

function resolveStartIndex(value, slider) {
    if (typeof value === 'number' && !Number.isNaN(value)) {
        return value;
    }

    if (typeof value !== 'string') {
        return 0;
    }

    if (/^\d+$/.test(value)) {
        return parseInt(value, 10);
    }

    var totalItems = slider ? slider.children.length : 0;
    switch (value) {
        case 'in_the_middle':
            return Math.max(0, Math.floor(totalItems / 2));

        case 'at_the_end':
            return Math.max(0, totalItems - 1);

        default:
            return 0;
    }
}

function hasData(slider, key) {
    return Object.prototype.hasOwnProperty.call(slider.dataset, key);
}

function create_tns_slider(slider){   
    var show_controls = toBool(slider.dataset['showControls'] ?? false),
        controls_position = slider.dataset['controlsPosition'] ?? 'center',
        nav_position = slider.dataset['navPosition'] ?? 'bottom',
        show_nav = toBool(slider.dataset['showNav'] ?? false),
        autoplay = toBool(slider.dataset['autoplay'] ?? false),
        autoplay_button_output = toBool(slider.dataset['autoplayButtonOutput'] ?? true),
        autoplay_position = slider.dataset['autoplayPosition'] ?? 'top',
        autoplay_timeout = slider.dataset['autoplayTimeout'] ?? 5000,
        autoplay_direction = slider.dataset['autoplayDirection'] ?? 'forward',
        autoplay_text = splitTextValue(slider.dataset['autoplayText'], ['start', 'stop']),
        autoplay_hover_pause = toBool(slider.dataset['autoplayHoverPause'] ?? false),
        autoplay_reset_on_visibility = toBool(slider.dataset['autoplayResetOnVisibility'] ?? true),
        prevent_action = toBool(slider.dataset['preventActionWhenRunning'] ?? false),
        loop = toBool(slider.dataset['loop'] ?? true),
        rewind = toBool(slider.dataset['rewind'] ?? true),
        speed = slider.dataset['speed'] ?? 300,
        autoHeight = toBool(slider.dataset['autoHeight'] ?? false),
        mobile = slider.dataset['mobile'] ?? 1,
        tablet = slider.dataset['tablet'] ?? 2,
        laptop = slider.dataset['laptop'] ?? 3,
        desktop = slider.dataset['desktop'] ?? 4,
        axis = slider.dataset['axis'] ?? 'horizontal',
        mode = slider.dataset['mode'] ?? 'carousel',
        edge_padding = toNumber(slider.dataset['edgePadding']),
        fixed_width = toNumber(slider.dataset['fixedWidth']),
        auto_width = toBool(slider.dataset['autoWidth'] ?? false),
        slide_by = slider.dataset['slideBy'] ?? 'page',
        center = toBool(slider.dataset['center'] ?? false),
        touch = toBool(slider.dataset['touch'] ?? true),
        mouse_drag = toBool(slider.dataset['mouseDrag'] ?? true),
        swipe_angle = slider.dataset['swipeAngle'] ?? false,
        prevent_scroll_on_touch = slider.dataset['preventScrollOnTouch'] ?? 'false',
        freezable = toBool(slider.dataset['freezable'] ?? true),
        start_index = slider.dataset['startIndex'] ?? '0',
        animate_in = slider.dataset['animateIn'] ?? 'tns-fadeIn',
        animate_out = slider.dataset['animateOut'] ?? 'tns-fadeOut',
        animate_normal = slider.dataset['animateNormal'] ?? 'tns-normal',
        animate_delay = toNumber(slider.dataset['animateDelay']),
        mobile_gutter = slider.dataset['mobileGutter'] ?? 0,
        tablet_gutter = slider.dataset['tabletGutter'] ?? 0,
        laptop_gutter = slider.dataset['laptopGutter'] ?? 0,
        desktop_gutter = slider.dataset['desktopGutter'] ?? 0,
        prev_icon_name = slider.dataset['prevIcon'] ?? 'fa-angle-left',
        next_icon_name = slider.dataset['nextIcon'] ?? 'fa-angle-right';

    const prev_icon_prefix = prev_icon_name.split('-')[0]; // Assuming the prefix is the first part of the class
    const next_icon_prefix = next_icon_name.split('-')[0]; // Assuming the prefix is the first part of the class
    const prev_icon = `<i class="${prev_icon_prefix} ${prev_icon_name}"></i>`;
    const next_icon = `<i class="${next_icon_prefix} ${next_icon_name}"></i>`;

    var slider_options = {
        container: slider,
        responsive : {
            1401 : {items:parseInt(desktop), gutter: parseInt(desktop_gutter)},
            1025 : {items:parseInt(laptop), gutter: parseInt(laptop_gutter)},
            601 : {items:parseInt(tablet), gutter: parseInt(tablet_gutter)},
            100 : {items:parseInt(mobile), gutter: parseInt(mobile_gutter)}
        }
    };

    if (hasData(slider, 'mode')) slider_options.mode = mode;
    if (hasData(slider, 'showControls')) slider_options.controls = show_controls;
    if (hasData(slider, 'autoplayButtonOutput')) slider_options.autoplayButtonOutput = autoplay_button_output;
    if (hasData(slider, 'touch')) slider_options.touch = 1;
    if (hasData(slider, 'autoplay')) slider_options.autoplay = autoplay;
    if (hasData(slider, 'autoplayPosition')) slider_options.autoplayPosition = autoplay_position;
    if (hasData(slider, 'autoplayDirection')) slider_options.autoplayDirection = autoplay_direction;
    if (hasData(slider, 'autoplayText')) slider_options.autoplayText = autoplay_text;
    if (hasData(slider, 'autoplayHoverPause')) slider_options.autoplayHoverPause = autoplay_hover_pause;
    if (hasData(slider, 'autoplayTimeout')) slider_options.autoplayTimeout = parseInt(autoplay_timeout);
    if (hasData(slider, 'autoplayResetOnVisibility')) slider_options.autoplayResetOnVisibility = autoplay_reset_on_visibility;
    if (hasData(slider, 'preventActionWhenRunning')) slider_options.preventActionWhenRunning = prevent_action;
    if (hasData(slider, 'loop')) slider_options.loop = loop;
    if (hasData(slider, 'speed')) slider_options.speed = parseInt(speed);
    if (hasData(slider, 'axis')) slider_options.axis = axis;
    if (hasData(slider, 'edgePadding') && edge_padding !== undefined) slider_options.edgePadding = edge_padding;
    if (hasData(slider, 'fixedWidth') && fixed_width !== undefined) slider_options.fixedWidth = fixed_width;
    if (hasData(slider, 'autoWidth')) slider_options.autoWidth = auto_width;
    if (hasData(slider, 'slideBy')) slider_options.slideBy = slide_by === 'page' ? 'page' : parseInt(slide_by);
    if (hasData(slider, 'center')) slider_options.center = center;
    if (hasData(slider, 'rewind')) slider_options.rewind = rewind;
    if (hasData(slider, 'autoHeight')) slider_options.autoHeight = autoHeight;
    if (hasData(slider, 'mouseDrag')) slider_options.mouseDrag = mouse_drag;
    if (hasData(slider, 'swipeAngle')) slider_options.swipeAngle = swipe_angle === false ? false : parseInt(swipe_angle);
    if (hasData(slider, 'controlsPosition')) slider_options.controlsPosition = controls_position;
    if (hasData(slider, 'showNav')) slider_options.nav = show_nav;
    if (hasData(slider, 'navPosition')) slider_options.navPosition = nav_position;
    if (hasData(slider, 'freezable')) slider_options.freezable = freezable;
    if (hasData(slider, 'preventScrollOnTouch')) slider_options.preventScrollOnTouch = prevent_scroll_on_touch;
    if (hasData(slider, 'startIndex')) slider_options.startIndex = resolveStartIndex(start_index, slider);
    if (hasData(slider, 'animateIn')) slider_options.animateIn = animate_in;
    if (hasData(slider, 'animateOut')) slider_options.animateOut = animate_out;
    if (hasData(slider, 'animateNormal')) slider_options.animateNormal = animate_normal;
    if (hasData(slider, 'animateDelay') && animate_delay !== undefined) slider_options.animateDelay = animate_delay;

    if (hasData(slider, 'showControls') && show_controls) {
        slider_options.controlsText = [prev_icon, next_icon];
    }

    if (hasData(slider, 'slideBy')) {
        slider_options.responsive[1401].slideBy = slider_options.slideBy;
        slider_options.responsive[1025].slideBy = slider_options.slideBy;
        slider_options.responsive[601].slideBy = slider_options.slideBy;
        slider_options.responsive[100].slideBy = slider_options.slideBy;

        if (mode !== 'gallery' && slide_by === 'page') {
            slider_options.responsive[1401].slideBy = 'page';
            slider_options.responsive[1025].slideBy = 'page';
            slider_options.responsive[601].slideBy = 'page';
            slider_options.responsive[100].slideBy = 'page';
        }
    } else {
        slider_options.responsive[1401].slideBy = parseInt(desktop);
        slider_options.responsive[1025].slideBy = parseInt(laptop);
        slider_options.responsive[601].slideBy = parseInt(tablet);
        slider_options.responsive[100].slideBy = parseInt(mobile);
    }
        
    return tns(slider_options);
}