<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_animation-settings' )->add_fields( array(
    Field::create( 'checkbox', 'add_animation' )->set_text( 'Agregar Animación' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
    Field::create( 'select', 'animation', 'Animación' )->add_options( array(
        '' => 'Ninguna',
        'callout.bounce' => 'bounce',
        'callout.shake' => 'shake',
        'callout.flash' => 'flash',
        'callout.pulse' => 'pulse',
        'callout.swing' => 'swing',
        'callout.tada' => 'tada',
        'transition.fadeIn' => 'fadeIn',
        'transition.fadeOut' => 'fadeOut',
        'transition.flipXIn' => 'flipXIn',
        'transition.flipXOut' => 'flipXOut',
        'transition.flipYIn' => 'flipYIn',
        'transition.flipYOut' => 'flipYOut',
        'transition.flipBounceXIn' => 'flipBounceXIn',
        'transition.flipBounceXOut' => 'flipBounceXOut',
        'transition.flipBounceYIn' => 'flipBounceYIn',
        'transition.flipBounceYOut' => 'flipBounceYOut',
        'transition.swoopIn' => 'swoopIn',
        'transition.swoopOut' => 'swoopOut',
        'transition.whirlIn' => 'whirlIn',
        'transition.whirlOut' => 'whirlOut',
        'transition.shrinkIn' => 'shrinkIn',
        'transition.shrinkOut' => 'shrinkOut',
        'transition.expandIn' => 'expandIn',
        'transition.expandOut' => 'expandOut',
        'transition.bounceIn' => 'bounceIn',
        'transition.bounceOut' => 'bounceOut',
        'transition.bounceUpIn' => 'bounceUpIn',
        'transition.bounceUpOut' => 'bounceUpOut',
        'transition.bounceDownIn' => 'bounceDownIn',
        'transition.bounceDownOut' => 'bounceDownOut',
        'transition.bounceLeftIn' => 'bounceLeftIn',
        'transition.bounceLeftOut' => 'bounceLeftOut',
        'transition.bounceRightIn' => 'bounceRightIn',
        'transition.bounceRightOut' => 'bounceRightOut',
        'transition.slideUpIn' => 'slideUpIn',
        'transition.slideUpOut' => 'slideUpOut',
        'transition.slideDownIn' => 'slideDownIn',
        'transition.slideDownOut' => 'slideDownOut',
        'transition.slideLeftIn' => 'slideLeftIn',
        'transition.slideLeftOut' => 'slideLeftOut',
        'transition.slideRightIn' => 'slideRightIn',
        'transition.slideRightOut' => 'slideRightOut',
        'transition.slideUpBigIn' => 'slideUpBigIn',
        'transition.slideUpBigOut' => 'slideUpBigOut',
        'transition.slideDownBigIn' => 'slideDownBigIn',
        'transition.slideDownBigOut' => 'slideDownBigOut',
        'transition.slideLeftBigIn' => 'slideLeftBigIn',
        'transition.slideLeftBigOut' => 'slideLeftBigOut',
        'transition.slideRightBigIn' => 'slideRightBigIn',
        'transition.slideRightBigOut' => 'slideRightBigOut',
        'transition.perspectiveUpIn' => 'perspectiveUpIn',
        'transition.perspectiveUpOut' => 'perspectiveUpOut',
        'transition.perspectiveDownIn' => 'perspectiveDownIn',
        'transition.perspectiveDownOut' => 'perspectiveDownOut',
        'transition.perspectiveLeftIn' => 'perspectiveLeftIn',
        'transition.perspectiveLeftOut' => 'perspectiveLeftOut',
        'transition.perspectiveRightIn' => 'perspectiveRightIn',
        'transition.perspectiveRightOut' => 'perspectiveRightOut',
    ))->add_dependency('add_animation'),
    // Field::create( 'number', 'animation_delay', 'Animation delay (milisegundos)' )->set_width( 75 )->enable_slider( 0, 20000 )->set_step( 100 ) 
));