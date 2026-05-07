<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Animations\Animated_Properties_Repeater;

$scroll_animation_fields = array();
$read_only_styles = 'pointer-events:none;opacity:.45;user-select:none;cursor:not-allowed;';

if( !SCROLL_ANIMATIONS ){
    array_push($scroll_animation_fields, 
        Field::create( 'message', 'Hint_1' )->set_description( __('Activate scroll animations on Theme Options -> Global Animations','mv23theme') )->hide_label()
    );
}

$scroll_animation_settings_fields = array(
    Field::create( 'text', 'animation_name', __('Name', 'mv23theme') )->set_placeholder( 'Animation name' ),

    Field::create( 'complex', 'trigger_element', __('Trigger','mv23theme') )->add_fields(array(
        Field::create( 'select', 'el' )->add_options( array(
            'this' => __('Component','mv23theme'),
            'selector' => __('Inner Element','mv23theme')
        ))->hide_label()->set_width( 50 ),
        Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_width( 50 )
    )),

    Field::create( 'complex', 'start', __('Point where the animation starts','mv23theme') )->add_fields(array(
        Field::create( 'select', 'hook', 'Trigger Point' )->add_options( array(
            'top bottom' => __('Bottom of viewport','mv23theme'),
            'top center' => __('Middle of viewport','mv23theme'),
            'top top' => __('Top of viewport','mv23theme'),
            'custom' => __('Custom','mv23theme')
        ))->hide_label()->set_width( 50 ),
        Field::create( 'text', 'Hint_1' )->add_dependency('hook','top bottom')->set_default_value( 'top bottom' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'Hint_2' )->add_dependency('hook','top center')->set_default_value( 'top center' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'Hint_3' )->add_dependency('hook','top top')->set_default_value( 'top top' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'custom_hook' )->add_dependency('hook','custom')->hide_label()->set_width( 50 )
    )),

    // complex for scrub
    Field::create( 'complex', 'scrub', __('Scrub animation','mv23theme') )->add_fields(array(
        Field::create( 'select', 'scrub_value' )->add_options( array(
            '' => __('No scrub','mv23theme'),
            'true' => __('Scrub linked to scroll progress','mv23theme'),
            'custom' => __('Custom','mv23theme')
        ))->hide_label()->set_width( 50 ),
        Field::create( 'text', 'custom_value' )->add_dependency('scrub_value','custom')->hide_label()->set_width( 50 )
    )),

    // end
    Field::create( 'checkbox', 'set_end' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Set End','mv23theme') ),
    Field::create( 'complex', 'end', __('Point where the animation ends','mv23theme') )->add_fields(array(
        Field::create( 'select', 'hook', 'Trigger Point' )->add_options( array(
            'bottom top' => __('When the trigger element leaves the viewport','mv23theme'),
            '+=300' => __('300px after the start point','mv23theme'),
            '+=100%' => __('100% of the viewport after the start point','mv23theme'),
            'custom' => __('Custom','mv23theme')
        ))->hide_label(),
        Field::create( 'text', 'Hint_1' )->add_dependency('hook','bottom top')->set_default_value( 'bottom top' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'Hint_2' )->add_dependency('hook','+=300')->set_default_value( '+=300' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'Hint_3' )->add_dependency('hook','+=100%')->set_default_value( '+=100%' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
        Field::create( 'text', 'custom_hook' )->add_dependency('hook','custom')->hide_label()
    ))->add_dependency('set_end'),

    // toggle actions
    Field::create( 'checkbox', 'set_toggle_actions' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Set Toggle Actions','mv23theme') ),
    Field::create( 'text', 'toggle_actions', 'toggleActions' )
        ->set_placeholder( 'play none none reverse' )
        ->set_description( 'onEnter, onLeave, onEnterBack, onLeaveBack' )
        ->add_dependency('set_toggle_actions'),

    // toggle class
    Field::create( 'checkbox', 'set_toggle_class' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Toggle a Class','mv23theme') ),
    Field::create( 'complex', 'toggle_class', __('Toggle Class','mv23theme') )->add_fields(array(
        Field::create( 'select', 'el' )->add_options( array(
            'this' => __('On the Trigger Element','mv23theme'),
            'selector' => __('On a specific element','mv23theme')
        ))->hide_label()->set_width( 30 ),
        Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_description('Targets')->set_width( 30 ),
        Field::create( 'text', 'classname' )->hide_label()->set_description('Class Name')->set_width( 30 ),
    ))->hide_label()->add_dependency('set_toggle_class'),

    // pin settings
    Field::create( 'checkbox', 'set_pin' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Pin element while scrolling','mv23theme') ),
    Field::create( 'complex', 'pin_settings' )->add_fields(array(
        Field::create( 'select', 'pinned_el', __('Pinned element','mv23theme') )->add_options( array(
            'trigger_el' => __('Trigger Element','mv23theme'),
            'selector' => __('Inner Element','mv23theme')
        ))->set_width( 25 ),
        Field::create( 'text', 'selector' )->add_dependency('pinned_el','selector','=')->set_width( 25 ),
        Field::create( 'checkbox', 'push_followers', __('Push followers','mv23theme') )->fancy()->set_default_value('1')->set_width( 25 )
    ))
    ->hide_label()->add_dependency('set_pin'),
        
    Field::create( 'checkbox', 'trigger_carrusel' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Trigger Carrusel','mv23theme') ),

    // disable settings
    Field::create( 'checkbox', 'disable_settings' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Disable animation on certain devices','mv23theme') ),
    Field::create( 'complex', 'disable_on' )->add_fields(array(
        Field::create( 'checkbox', 'mobile', __('Disable on mobile', 'mv23theme') )
            ->set_text( __('Mobile','mv23theme') )
            ->hide_label()
            ->set_width( 50 ),
        Field::create( 'checkbox', 'desktop', __('Disable on desktop', 'mv23theme') )
            ->set_text( __('Desktop','mv23theme') )
            ->hide_label()
            ->set_width( 50 )
    ))->add_dependency('disable_settings'),

    // markers
    Field::create( 'checkbox', 'show_markers' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Show markers','mv23theme') ),

    // initial rules settings
    Field::create( 'checkbox', 'set_initial_rules' )
        ->hide_label()
        ->set_attr( 'class', 'uf-separator-top' )
        ->set_text( __('Set initial rules','mv23theme') ),
    Field::create( 'repeater', 'initial_rules' )
        ->set_add_text( __('Add rule','mv23theme') )
        ->hide_label()
        ->add_group('rule', array(
            'edit_mode' => 'popup',
            'layout' => 'rows',
            'title_template' => '<% if(element["el"] == "this"){ %>Trigger element<% } else { %><%= element["selector"] %><% } %>
                <% if(rules.length > 0){ %> - <%= rules.length %> rules (<%= rules[0] && rules[0].property ? rules[0].property : "" %>)<% } %>',
            'fields' => array(
                Field::create( 'complex', 'element', __('Affected Element','mv23theme') )->add_fields(array(
                    Field::create( 'select', 'el' )->add_options( array(
                        'this' => __('Trigger Element','mv23theme'),
                        'selector' => __('Inner Element','mv23theme'),
                        'outer_selector' => __('Outer Element','mv23theme')
                    ))->hide_label()->set_width( 50 ),
                    Field::create( 'text', 'selector' )->add_dependency('el','this','!=')->hide_label()->set_width( 50 )
                )),
                Animated_Properties_Repeater::getRepeater('rules', __('Rules', 'mv23theme'))
            )
        ))->add_dependency('set_initial_rules')
);

array_push($scroll_animation_fields, Field::create( 'repeater', 'groups' )
    ->set_add_text( __('Add animation','mv23theme') )
    ->hide_label()
    ->add_group('group1', array(
        'title' => 'Scroll Animation',
        'edit_mode' => 'popup',
        'title_template' => '<%= settings["animation_name"] %> <% if(settings["disable_settings"] == 1 && settings["disable_on"]["mobile"] && settings["disable_on"]["desktop"]){ %> 
            <span style="color:red;font-weight:bold;">(disabled)</span> <% } 
            %>',
        'fields' => array(
            Field::create( 'complex', 'settings' )->add_fields( $scroll_animation_settings_fields )
                ->hide_label()->rows_layout()
                ->set_width( 50 ),

            Field::create( 'repeater', 'timeline', __('Animation Timeline', 'mv23theme') )
                ->set_add_text(__('Add animation','mv23theme'))
                ->add_group('tween', array(
                    'edit_mode' => 'popup',
                    'layout' => 'rows',
                    'title_template' => '<% if(element["el"] == "this"){ %>Trigger element<% } else { %><%= element["selector"] %><% } %>',
                    'fields' => array(
                        Field::create( 'complex', 'element', __('Animated Element','mv23theme') )->add_fields(array(
                            Field::create( 'select', 'el' )->add_options( array(
                                'this' => __('Trigger Element','mv23theme'),
                                'selector' => __('Inner Element','mv23theme'),
                                'outer_selector' => __('Outer Element','mv23theme')
                            ))->hide_label()->set_width( 50 ),
                            Field::create( 'text', 'selector' )->add_dependency('el','this','!=')->hide_label()->set_width( 50 ),
                        )),

                        Field::create( 'complex', 'animated_properties', __('Animated properties','mv23theme') )->add_fields(array(
                            Animated_Properties_Repeater::getRepeater('from', __('Initial CSS values (from)', 'mv23theme'))->set_width(50),
                            Animated_Properties_Repeater::getRepeater('to', __('Final CSS values (to)', 'mv23theme'))->set_width(50)
                        )),

                        Field::create( 'complex', 'position', __('Position (Optional)','mv23theme') )->add_fields(array(
                            Field::create( 'select', 'key' )->add_options( array(
                                '' => __('Default','mv23theme'),
                                '2' => __('2 seconds from start','mv23theme'),
                                '+=1' => __('Create a 1 second gap','mv23theme'),
                                '-=1' => __('Overlap by 1 second','mv23theme'),    
                                '<' => __('At the START of the most recently added animation','mv23theme'),
                                '>' => __('At the END of the most recently added animation','mv23theme'),
                                '<1' => __('1 second after the START of the most recently added animation','mv23theme'),
                                '>1' => __('1 second after the END of the most recently added animation','mv23theme'),
                                'custom' => __('Custom','mv23theme')
                            ))->hide_label()->set_width( 50 ),
                            Field::create( 'text', 'Hint_1' )->add_dependency('key','2')->set_default_value( '2' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_2' )->add_dependency('key','+=1')->set_default_value( '+=1' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_3' )->add_dependency('key','-=1')->set_default_value( '-=1' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_4' )->add_dependency('key','<')->set_default_value( '<' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_5' )->add_dependency('key','>')->set_default_value( '>' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_6' )->add_dependency('key','<1')->set_default_value( '<1' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'Hint_7' )->add_dependency('key','>1')->set_default_value( '>1' )->hide_label()->set_width( 50 )->set_attr( 'style', $read_only_styles ),
                            Field::create( 'text', 'custom_key' )->add_dependency('key','custom')->hide_label()->set_width( 50 ),
                        )),
                    )
                ))->set_width( 50 )
        )
    ))
);

Container::create( 'scroll_animations_container' ) 
    ->set_layout( 'rows' )
    ->add_fields( $scroll_animation_fields );