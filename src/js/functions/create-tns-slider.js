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

function create_tns_slider(slider){   
    var show_controls = toBool(slider.dataset['showControls'] ?? false),
        nav_position = slider.dataset['navPosition'] ?? 'bottom',
        show_nav = toBool(slider.dataset['showNav'] ?? false),
        autoplay = toBool(slider.dataset['autoplay'] ?? false),
        autoplay_timeout = slider.dataset['autoplayTimeout'] ?? 5000,
        // autoplay_hover_pause = toBool(slider.dataset['autoplayHoverPause'] ?? false),
        // prevent_action = toBool(slider.dataset['preventAction'] ?? false),
        // rewind = toBool(slider.dataset['rewind'] ?? false),
        speed = slider.dataset['speed'] ?? 450,
        autoHeight = toBool(slider.dataset['autoHeight'] ?? false),
        mobile = slider.dataset['mobile'] ?? 1,
        tablet = slider.dataset['tablet'] ?? 2,
        laptop = slider.dataset['laptop'] ?? 3,
        desktop = slider.dataset['desktop'] ?? 4,
        axis = slider.dataset['axis'] ?? 'horizontal',
        mode = slider.dataset['mode'] ?? 'carousel',
        touch = toBool(slider.dataset['touch'] ?? false),
        mobile_gutter = slider.dataset['mobileGutter'] ?? 0,
        tablet_gutter = slider.dataset['tabletGutter'] ?? 0,
        laptop_gutter = slider.dataset['laptopGutter'] ?? 0,
        desktop_gutter = slider.dataset['desktopGutter'] ?? 0;

    var slider_options = {  
        mode: mode, 
        touch: touch, 
        container: slider, 
        autoplay: autoplay,
        autoplayButton: false,
        autoplayButtonOutput: false,
        // autoplayHoverPause: autoplay_hover_pause,
        autoplayTimeout: parseInt(autoplay_timeout),
        // preventActionWhenRunning: prevent_action,
        loop: true, 
        speed: parseInt(speed),
        axis:axis, 
        controlsText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'], 
        rewind: true,
        autoHeight: autoHeight,
        mouseDrag: true, 
        swipeAngle: false,
        controls: show_controls, 
        nav: show_nav, 
        navPosition: nav_position,
        responsive : {
            1401 : {items:parseInt(desktop), slideBy:parseInt(desktop), gutter: parseInt(desktop_gutter)},
            1025 : {items:parseInt(laptop), slideBy:parseInt(laptop), gutter: parseInt(laptop_gutter)},
            601 : {items:parseInt(tablet), slideBy:parseInt(tablet), gutter: parseInt(tablet_gutter)},
            100 : {items:parseInt(mobile), slideBy:parseInt(mobile), gutter: parseInt(mobile_gutter)}
        }
    };
        
    return tns(slider_options);
}