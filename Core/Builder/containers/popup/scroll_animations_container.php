<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Animations\Animated_Properties_Repeater;

$scroll_animation_fields = array();
$read_only_styles = 'pointer-events:none;opacity:.6;background-color:#eee;';

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

    Field::create( 'complex', 'start_at', __('Point where the animation begins','mv23theme') )->add_fields(array(
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

    Field::create( 'complex', 'end_at', __('Distance the animation lasts','mv23theme') )->add_fields(array(
        Field::create( 'text', 'basic' )->set_prefix('+=')->set_suffix('px / %')->hide_label()->add_dependency('customize',1,'!=')->set_width( 50 ),
        Field::create( 'checkbox', 'customize' )->set_text( __('Customize','mv23theme') )->fancy()->hide_label()->set_width( 50 ),
        Field::create( 'text', 'custom' )->add_dependency('customize')->hide_label()->set_width( 50 )
    )),

    // advanced settings
    Field::create( 'checkbox', 'set_advanced_settings' )
        ->hide_label()
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
        ->set_text( __('Advanced Settings','mv23theme') ),
    Field::create( 'text', 'toggle_actions', 'toggleActions' )
        ->set_placeholder( 'play none none reverse' )
        ->set_description( 'onEnter, onLeave, onEnterBack, onLeaveBack' )
        ->add_dependency('set_advanced_settings'),
    Field::create( 'complex', 'toggle_class', __('Toggle Class','mv23theme') )->add_fields(array(
        Field::create( 'select', 'el' )->add_options( array(
            'this' => __('Trigger Element','mv23theme'),
            'selector' => __('Selector','mv23theme')
        ))->hide_label()->set_width( 30 ),
        Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_description('Targets')->set_width( 30 ),
        Field::create( 'text', 'classname' )->hide_label()->set_description('Class Name')->set_width( 30 ),
    ))->add_dependency('set_advanced_settings'),

    // pin settings
    Field::create( 'checkbox', 'set_pin' )
        ->hide_label()
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
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
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
        ->set_text( __('Trigger Carrusel','mv23theme') ),
    Field::create( 'checkbox', 'disable_on_mobile', __('Disable on mobile', 'mv23theme') )
        ->hide_label()
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
        ->set_text( __('Disable on mobile', 'mv23theme') ),
    Field::create( 'checkbox', 'disable_everywhere', __('Disable everywhere', 'mv23theme') )
        ->hide_label()
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
        ->set_text( __('Disable everywhere', 'mv23theme') ),
    Field::create( 'checkbox', 'add_indicators' )
        ->hide_label()
        ->set_attr( 'style', 'background:#f3f3f3;border-bottom:1px solid #dedede' )
        ->set_text( __('Show indicators','mv23theme') )
);

array_push($scroll_animation_fields, Field::create( 'repeater', 'groups' )
    ->set_add_text( __('Add animation','mv23theme') )
    ->hide_label()
    ->add_group('group1', array(
        'title' => 'Scroll Animation',
        'edit_mode' => 'popup',
        'title_template' => '<%= settings["animation_name"] %> <% if(settings["disable_everywhere"] == 1){ %> <span style="color:red;font-weight:bold;">(disabled)</span> <% } %>',
        'fields' => array(
            Field::create( 'complex', 'settings' )->add_fields( $scroll_animation_settings_fields )
                ->hide_label()->rows_layout()
                ->set_width( 50 ),

            Field::create( 'repeater', 'timeline', __('Timeline', 'mv23theme') )
                ->set_add_text(__('Add animation','mv23theme'))
                ->add_group('tween', array(
                    'edit_mode' => 'popup',
                    'layout' => 'rows',
                    'title_template' => '<%= element["selector"] %>',
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