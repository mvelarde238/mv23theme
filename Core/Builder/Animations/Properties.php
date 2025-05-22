<?php

namespace Core\Builder\Animations;

class Properties
{
    public static function getProperties()
    {
        return [
            ['key' => 'opacity', 'label' => 'Opacity', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 1, 'step' => 0.01],
            ['key' => 'autoAlpha', 'label' => 'Auto Alpha', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 1, 'step' => 0.01],
            ['key' => 'scale', 'label' => 'Scale', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'scaleX', 'label' => 'Scale X', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'scaleY', 'label' => 'Scale Y', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 5, 'step' => 0.1],
            ['key' => 'x', 'label' => 'X Position', 'type' => 'select', 'options' => self::get_default_positions()],
            ['key' => 'y', 'label' => 'Y Position', 'type' => 'select', 'options' => self::get_default_positions()],
            ['key' => 'z', 'label' => 'Z Position', 'type' => 'select', 'options' => self::get_default_positions()],
            ['key' => 'xPercent', 'label' => 'X Percent', 'type' => 'select', 'options' => self::get_default_percentage_positions()],
            ['key' => 'yPercent', 'label' => 'Y Percent', 'type' => 'select', 'options' => self::get_default_percentage_positions()],
            ['key' => 'skew', 'label' => 'Skew', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'skewX', 'label' => 'Skew X', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'skewY', 'label' => 'Skew Y', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
            ['key' => 'color', 'label' => 'Text Color', 'type' => 'color'],
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
            ['key' => 'letterSpacing', 'label' => 'Letter Spacing', 'type' => 'text'],
            ['key' => 'rotation', 'label' => 'Rotation', 'type' => 'number', 'enable_slider' => true, 'min' => -360, 'max' => 360, 'step' => 1],
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
            ['key' => 'yoyo', 'label' => 'Yoyo Effect', 'type' => 'boolean'],
            ['key' => 'margin', 'label' => 'Margin', 'type' => 'select', 'options' => self::get_default_space_options()],
            ['key' => 'padding', 'label' => 'Padding', 'type' => 'select', 'options' => self::get_default_space_options()],
            ['key' => 'border', 'label' => 'Border', 'type' => 'select', 'options' => [
                '0' => '0',
                '1px solid #000' => '1px solid #000',
                '2px solid #000' => '2px solid #000',
                '3px solid #000' => '3px solid #000',
                'none' => 'None'
            ]],
            ['key' => 'zIndex', 'label' => 'Z Index', 'type' => 'number', 'enable_slider' => true, 'min' => -10, 'max' => 500, 'step' => 1],
            ['key' => 'boxShadow', 'label' => 'Box Shadow', 'type' => 'select', 'options' => [
                'none' => 'None',
                '0 0 10px rgba(0, 0, 0, 0.5)' => '0 0 10px rgba(0, 0, 0, 0.5)',
                '2px 2px 5px rgba(0, 0, 0, 0.5)' => '2px 2px 5px rgba(0, 0, 0, 0.5)',
                '4px 4px 10px rgba(0, 0, 0, 0.5)' => '4px 4px 10px rgba(0, 0, 0, 0.5)',
            ]],
            ['key' => 'textShadow', 'label' => 'Text Shadow', 'type' => 'select', 'options' => [
                'none' => 'None',
                '1px 1px #000' => '1px 1px #000',
                '2px 2px #000' => '2px 2px #000',
                '3px 3px #000' => '3px 3px #000'
            ]],
            ['key' => 'borderRadius', 'label' => 'Border Radius', 'type' => 'select', 'options' => [
                '0' => '0',
                '5px' => '5px',
                '10px' => '10px',
                '20px' => '20px',
                '50%' => '50%'
            ]],
            ['key' => 'filter', 'label' => 'CSS Filter', 'type' => 'selct', 'options' => [
                'none' => 'None',
                'blur(5px)' => 'Blur 5px',
                'brightness(0.5)' => 'Brightness 50%',
                'contrast(200%)' => 'Contrast 200%',
                'grayscale(100%)' => 'Grayscale 100%',
                'hue-rotate(90deg)' => 'Hue Rotate 90deg',
                'invert(100%)' => 'Invert 100%',
                'opacity(50%)' => 'Opacity 50%',
                'saturate(200%)' => 'Saturate 200%',
                'sepia(100%)' => 'Sepia 100%'
            ]],
            ['key' => 'duration', 'label' => 'Duration (seconds)', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 10, 'step' => 0.1],
            ['key' => 'delay', 'label' => 'Delay (seconds)', 'type' => 'number', 'enable_slider' => true, 'min' => 0, 'max' => 10, 'step' => 0.1],
            [
                'key' => 'ease',
                'label' => 'Ease',
                'type' => 'select',
                'options' => self::get_default_ease_options()
            ],
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
            ['key' => 'stagger', 'label' => 'Stagger', 'type' => 'select', 'options' => [
                'amount:1|from:start|grid:auto' => 'amount:1|from:start|grid:auto',
                'amount:1|from:center|grid:auto' => 'amount:1|from:center|grid:auto',
                'amount:1|from:edges|grid:auto' => 'amount:1|from:edges|grid:auto',
                'amount:1|from:random|grid:auto' => 'amount:1|from:random|grid:auto'
            ]],
            // ['key' => 'width', 'label' => 'Width', 'type' => 'number'],
            // ['key' => 'height', 'label' => 'Height', 'type' => 'number'],
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
            'power1.in' => 'power1.in',
            'power1.out' => 'power1.out',
            'power1.inOut' => 'power1.inOut',
            'power2.in' => 'power2.in',
            'power2.out' => 'power2.out',
            'power2.inOut' => 'power2.inOut',
            'power3.in' => 'power3.in',
            'power3.out' => 'power3.out',
            'power3.inOut' => 'power3.inOut',
            'power4.in' => 'power4.in',
            'power4.out' => 'power4.out',
            'power4.inOut' => 'power4.inOut',
            'back.in' => 'back.in',
            'back.out' => 'back.out',
            'back.inOut' => 'back.inOut',
            'elastic.in' => 'elastic.in',
            'elastic.out' => 'elastic.out',
            'elastic.inOut' => 'elastic.inOut',
            'bounce.in' => 'bounce.in',
            'bounce.out' => 'bounce.out',
            'bounce.inOut' => 'bounce.inOut',
            'expo.in' => 'expo.in',
            'expo.out' => 'expo.out',
            'expo.inOut' => 'expo.inOut',
            'sine.in' => 'sine.in',
            'sine.out' => 'sine.out',
            'sine.inOut' => 'sine.inOut',
            'circ.in' => 'circ.in',
            'circ.out' => 'circ.out',
            'circ.inOut' => 'circ.inOut',
            'linear' => 'linear'
        ];
    }
}
