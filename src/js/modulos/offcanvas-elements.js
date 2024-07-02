(function($,c){

    // TODO: to use dismisible settings i need to update materialize

    function format_color( color, alpha ){
        let formated_color = color;
        if( alpha != 100 ) formated_color = hexToRgba(color, alpha);
        return formated_color;
    }

    document.addEventListener('DOMContentLoaded', function() {

        var offcanvas_elements = document.querySelectorAll('.offcanvas-element');

        for (var i = 0; i < offcanvas_elements.length; i++) {

            var offcanvas_element = offcanvas_elements[i];
            var type = offcanvas_element.dataset.type;
            var trigger_events = offcanvas_element.dataset.triggerEvents;
            var _settings = offcanvas_element.dataset.settings;
            var offcanvas_element_id = offcanvas_element.id;

            // clear attributes
            offcanvas_element.removeAttribute('data-trigger-events')
            offcanvas_element.removeAttribute('data-settings')
            offcanvas_element.removeAttribute('data-type')
            
            if(type === 'sidenav'){
                if( trigger_events && Array.isArray(JSON.parse(trigger_events)) && JSON.parse(trigger_events).length ){
                    // parse settings
                    var settings = JSON.parse(_settings);
                    console.log(settings);

                    // add styles
                    if( settings.background_color.use ) offcanvas_element.style.backgroundColor = format_color(settings.background_color.color, settings.background_color.alpha);

                    // add trigger events
                    JSON.parse(trigger_events).forEach(triggerData => {
                        switch ( triggerData.__type ) {
                            case 'click':
                                var triggers = document.querySelectorAll(triggerData.selector);
                                if( triggers.length ){

                                    triggers.forEach( trigger => {
                                        trigger.dataset.activates = offcanvas_element_id;
                                        
                                        $(trigger).sideNav({ 
                                            edge: settings.position, 
                                            menuWidth: settings.max_width,
                                            // closeOnClick: true,
                                            // dismisible: settings.dismisible, 
                                            draggable: false
                                        });
                                    });
                                };
                                break;
                        
                            default:
                                console.log('No trigger events');
                                break;
                        }
                    });
                }

            }
        }
    });
})(jQuery,console.log); 