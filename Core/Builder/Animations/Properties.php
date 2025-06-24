<?php

namespace Core\Builder\Animations;

class Properties
{
    public static function getProperties()
    {
        return [
            ['key' => 'autoAlpha', 'label' => 'Auto Alpha', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 1, 'step' => 0.01],
            ['key' => 'backgroundColor', 'label' => 'Background Color', 'type' => 'color'],
            ['key' => 'backgroundPosition', 'label' => 'Background Position', 'type' => 'select', 'options' => [
                'top left' => 'Top Left',
                'top center' => 'Top Center',
                'top right' => 'Top Right',
                'center left' => 'Center Left',
                'center center' => 'Center Center',
                'center right' => 'Center Right',
                'bottom left' => 'Bottom Left',
                'bottom center' => 'Bottom Center',
                'bottom right' => 'Bottom Right',
                '50% 50%' => '50% 50%'
                ]],
            ['key' => 'border', 'label' => 'Border', 'type' => 'select', 'options' => [
                '0' => '0',
                '1px solid #000' => '1px solid #000',
                '2px solid #000' => '2px solid #000',
                '3px solid #000' => '3px solid #000',
                'none' => 'None'
                ]],
            ['key' => 'borderRadius', 'label' => 'Border Radius', 'type' => 'select', 'options' => [
                '0' => '0',
                '5px' => '5px',
                '10px' => '10px',
                '20px' => '20px',
                '50%' => '50%'
                ]],
            ['key' => 'boxShadow', 'label' => 'Box Shadow', 'type' => 'select', 'options' => [
                'none' => 'None',
                '0 0 10px rgba(0, 0, 0, 0.5)' => '0 0 10px rgba(0, 0, 0, 0.5)',
                '2px 2px 5px rgba(0, 0, 0, 0.5)' => '2px 2px 5px rgba(0, 0, 0, 0.5)',
                '4px 4px 10px rgba(0, 0, 0, 0.5)' => '4px 4px 10px rgba(0, 0, 0, 0.5)',
                ]],
            ['key' => 'clipPath', 'label' => 'Clip Path', 'type' => 'select', 'options' => [
                'circle(100% at 50% 50%)' => 'circle(100% at 50% 50%)',
                'ellipse(60% 40% at 50% 50%)' => 'ellipse(60% 40% at 50% 50%)',
                'inset(10% 20%)' => 'inset(10% 20%)',
                'path("M10 10 H 90 V 90 H 10 L 10 10")' => 'path("M10 10 H 90 V 90 H 10 L 10 10")',
                'polygon(50% 0%, 100% 100%, 0% 100%)' => 'polygon(50% 0%, 100% 100%, 0% 100%)'
            ]],
            ['key' => 'customProperty', 'label' => 'Custom Property', 'type' => 'complex'],
            ['key' => 'delay', 'label' => 'Delay (seconds)', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 10, 'step' => 0.1],
            ['key' => 'duration', 'label' => 'Duration (seconds)', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 10, 'step' => 0.1],
            ['key' => 'ease', 'label' => 'Ease', 'type' => 'select', 'options' => self::get_default_ease_options()],
            ['key' => 'filter', 'label' => 'Filter', 'type' => 'selct', 'options' => [
                'contrast(200%)' => 'Contrast 200%',
                'blur(5px)' => 'Blur 5px',
                'brightness(0.5)' => 'Brightness 50%',
                'grayscale(100%)' => 'Grayscale 100%',
                'hue-rotate(90deg)' => 'Hue Rotate 90deg',
                'invert(100%)' => 'Invert 100%',
                'opacity(50%)' => 'Opacity 50%',
                'saturate(200%)' => 'Saturate 200%',
                'sepia(100%)' => 'Sepia 100%',
                'none' => 'None'
                ]],
            ['key' => 'height', 'label' => 'Height', 'type' => 'text'],
            ['key' => 'letterSpacing', 'label' => 'Letter Spacing', 'type' => 'text'],
            ['key' => 'margin', 'label' => 'Margin', 'type' => 'select', 'options' => self::get_default_space_options()],
            ['key' => 'opacity', 'label' => 'Opacity', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 1, 'step' => 0.01],
            ['key' => 'overflow', 'label' => 'Overflow', 'type' => 'select', 'options' => [
                'auto' => 'Auto',
                'hidden' => 'Hidden',
                'scroll' => 'Scroll',
                'visible' => 'Visible'
            ]],
            ['key' => 'padding', 'label' => 'Padding', 'type' => 'select', 'options' => self::get_default_space_options()],
            ['key' => 'repeat', 'label' => 'Repeat Count', 'type' => 'select', 'options' => [
                'none' => 'None',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '-1' => '-1 (infinite)',
                ]],
            ['key' => 'repeatDelay', 'label' => 'Repeat Delay', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 10, 'step' => 0.1],
            ['key' => 'repeatRefresh', 'label' => 'Repeat Refresh', 'type' => 'boolean'],
            ['key' => 'rotation', 'label' => 'Rotation', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'scale', 'label' => 'Scale', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'scaleX', 'label' => 'Scale X', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'scaleY', 'label' => 'Scale Y', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'skew', 'label' => 'Skew', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'skewX', 'label' => 'Skew X', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'skewY', 'label' => 'Skew Y', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'stagger', 'label' => 'Stagger', 'type' => 'select', 'options' => [
                'amount:1|from:start|grid:auto' => 'amount:1|from:start|grid:auto',
                'amount:1|from:center|grid:auto' => 'amount:1|from:center|grid:auto',
                'amount:1|from:edges|grid:auto' => 'amount:1|from:edges|grid:auto',
                'amount:1|from:random|grid:auto' => 'amount:1|from:random|grid:auto'
                ]],
            ['key' => 'strokeDasharray', 'label' => 'Stroke dash array', 'type' => 'number'],
            ['key' => 'strokeDashoffset', 'label' => 'Stroke dash offset', 'type' => 'number'],
            ['key' => 'color', 'label' => 'Text Color', 'type' => 'color'],
            ['key' => 'textShadow', 'label' => 'Text Shadow', 'type' => 'select', 'options' => [
                'none' => 'None',
                '1px 1px #000' => '1px 1px #000',
                '2px 2px #000' => '2px 2px #000',
                '3px 3px #000' => '3px 3px #000'
                ]],
            ['key' => 'transformOrigin', 'label' => 'Transform Origin', 'type' => 'select', 'options' => [
                '0% 0%' => '0% 0%',
                '50% 50%' => '50% 50%',
                '100% 100%' => '100% 100%',
                'top left' => 'Top Left',
                'top center' => 'Top Center',
                'top right' => 'Top Right',
                'center left' => 'Center Left',
                'center center' => 'Center Center',
                'center right' => 'Center Right',
                'bottom left' => 'Bottom Left',
                'bottom center' => 'Bottom Center',
                'bottom right' => 'Bottom Right'
                ]],
            ['key' => 'width', 'label' => 'Width', 'type' => 'text'],
            ['key' => 'xPercent', 'label' => 'X Percent', 'type' => 'select', 'options' => self::get_default_percentage_positions()],
            ['key' => 'x', 'label' => 'X Position', 'type' => 'select', 'options' => self::get_default_positions()],
            ['key' => 'yPercent', 'label' => 'Y Percent', 'type' => 'select', 'options' => self::get_default_percentage_positions()],
            ['key' => 'y', 'label' => 'Y Position', 'type' => 'select', 'options' => self::get_default_positions()],
            ['key' => 'yoyo', 'label' => 'Yoyo Effect', 'type' => 'boolean'],
            ['key' => 'zIndex', 'label' => 'Z Index', 'type' => 'number', 'enable_slider' => true, 'min' => -10, 'max' => 500, 'step' => 1],
            ['key' => 'z', 'label' => 'Z Position', 'type' => 'select', 'options' => self::get_default_positions()]
            // ['key' => 'fontSize', 'label' => 'Font Size', 'type' => 'number'],
        ];
    }

    private static function get_default_positions(){
        return [
            '-100vw' => '-100vw',
            '-200' => '-200px',
            '-100' => '-100px',
            '-50' => '-50px',
            '-=50' => '-=50px',
            '0' => '0px',
            '+=50' => '+=50px',
            '50' => '50px',
            '100' => '100px',
            '200' => '200px',
            '100vw' => '100vw',
        ];
    }

    private static function get_default_percentage_positions(){
        return [
            '-200' => '-200',
            '-100' => '-100',
            '-50' => '-50',
            '-=50' => '-=50',
            '0' => '0',
            '+=50' => '+=50',
            '50' => '50',
            '100' => '100',
            '200' => '200',
        ];
    }

    private static function get_default_space_options(){
        return [
            '0' => '0',
            '10' => '10px',
            '0 20px' => '0 20px',
            '1em 2em 0 2em' => '1em 2em 0 2em',
            '10px 20px 30px 40px' => '10px 20px 30px 40px'
        ];
    }

    private static function get_default_ease_options(){
        return [
            'back.in' => 'back.in',
            'back.inOut' => 'back.inOut',
            'back.out' => 'back.out',
            'bounce.in' => 'bounce.in',
            'bounce.inOut' => 'bounce.inOut',
            'bounce.out' => 'bounce.out',
            'circ.in' => 'circ.in',
            'circ.inOut' => 'circ.inOut',
            'circ.out' => 'circ.out',
            'elastic.in' => 'elastic.in',
            'elastic.inOut' => 'elastic.inOut',
            'elastic.out' => 'elastic.out',
            'expo.in' => 'expo.in',
            'expo.inOut' => 'expo.inOut',
            'expo.out' => 'expo.out',
            'linear' => 'linear',
            'power1.in' => 'power1.in',
            'power1.inOut' => 'power1.inOut',
            'power1.out' => 'power1.out',
            'power2.in' => 'power2.in',
            'power2.inOut' => 'power2.inOut',
            'power2.out' => 'power2.out',
            'power3.in' => 'power3.in',
            'power3.inOut' => 'power3.inOut',
            'power3.out' => 'power3.out',
            'power4.in' => 'power4.in',
            'power4.inOut' => 'power4.inOut',
            'power4.out' => 'power4.out',
            'sine.in' => 'sine.in',
            'sine.inOut' => 'sine.inOut',
            'sine.out' => 'sine.out'
        ];
    }
}
