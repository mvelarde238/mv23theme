<?php
namespace Core\Builder;

use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

class Slider_Settings
{
    private static $options_index = null;

    /**
     * Builds the repeater field used to edit slider settings in the builder.
     *
     * @param string $key   Datastore key where the settings are stored.
     * @param string $label Field label shown in the UI.
     * @return \Ultimate_Fields\Field
     */
    public static function getRepeater($key, $label)
    {
        $repeater = Field::create('repeater', $key, $label)
            ->set_add_text(' ')
            ->set_layout('table')
            ->set_chooser_type('dropdown')
            ->set_default_value( self::get_default_groups() );

        foreach ( self::get_groups() as $group ) {
            $repeater->add_group($group);
        }

        return $repeater;
    }

    /**
     * Returns the option catalog used by the repeater.
     *
     * @return array
     */
    public static function getOptions()
    {
        return [
            ['key' => 'auto_height', 'label' => __('Auto Height', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            ['key' => 'autoplay', 'label' => __('Autoplay', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            // ['key' => 'autoplayButton', 'label' => __('Autoplay Button Selector', 'mv23theme'), 'type' => 'text'],
            ['key' => 'autoplay_button_output', 'label' => __('Autoplay Button Output', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            ['key' => 'autoplay_direction', 'label' => __('Autoplay Direction', 'mv23theme'), 'type' => 'select', 'default' => 'forward', 'options' => [
                    'forward' => __('Forward', 'mv23theme'),
                    'backward' => __('Backward', 'mv23theme')
                ]
            ],
            ['key' => 'autoplay_hover_pause', 'label' => __('Pause Autoplay on Hover', 'mv23theme'), 'type' => 'boolean', 'default' => false, 'disable_prefix' => true],
            ['key' => 'autoplay_position', 'label' => __('Autoplay Position', 'mv23theme'), 'type' => 'select', 'default' => 'top', 'options' => [
                    'top' => __('Top', 'mv23theme'),
                    'bottom' => __('Bottom', 'mv23theme')
                ]
            ],
            ['key' => 'autoplay_text', 'label' => __('Autoplay Button Text', 'mv23theme'), 'type' => 'text', 'default' => 'start|stop'],
            ['key' => 'autoplay_timeout', 'label' => __('Autoplay Timeout (ms)', 'mv23theme'), 'type' => 'number', 'min' => 1, 'max' => 60000, 'default' => 5000],
            ['key' => 'autoplay_reset_on_visibility', 'label' => __('Reset Autoplay on Visibility Change', 'mv23theme'), 'type' => 'boolean', 'default' => true, 'disable_prefix' => true],
            ['key' => 'auto_width', 'label' => __('Auto Width', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            ['key' => 'animate_in', 'label' => __('Animate In Class', 'mv23theme'), 'type' => 'text', 'default' => 'tns-fadeIn'],
            ['key' => 'animate_out', 'label' => __('Animate Out Class', 'mv23theme'), 'type' => 'text', 'default' => 'tns-fadeOut'],
            ['key' => 'animate_normal', 'label' => __('Animate Normal Class', 'mv23theme'), 'type' => 'text', 'default' => 'tns-normal'],
            ['key' => 'animate_delay', 'label' => __('Animate Delay (ms)', 'mv23theme'), 'type' => 'number', 'min' => 1, 'max' => 10000, 'step' => 1],
            // ['key' => 'arrowKeys', 'label' => __('Arrow Keys', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            ['key' => 'axis', 'label' => __('Axis', 'mv23theme'), 'type' => 'select', 'default' => 'horizontal', 'options' => [
                    'horizontal' => __('Horizontal', 'mv23theme'),
                    'vertical' => __('Vertical', 'mv23theme')
                ]
                ],
            ['key' => 'center', 'label' => __('Center Active Slide', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            ['key' => 'controls', 'label' => __('Controls', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            ['key' => 'controls_position', 'label' => __('Controls Position', 'mv23theme'), 'type' => 'select', 'default' => 'center', 'options' => [
                    'top' => __('Top', 'mv23theme'),
                    'center' => __('Center', 'mv23theme'),
                    'bottom' => __('Bottom', 'mv23theme')
                ]
            ],
            // ['key' => 'controlsContainer', 'label' => __('Controls Container Selector', 'mv23theme'), 'type' => 'text'],
            // ['key' => 'controlsText', 'label' => __('Controls Text', 'mv23theme'), 'type' => 'text', 'default' => 'prev|next'],
            ['key' => 'edge_padding', 'label' => __('Edge Padding', 'mv23theme'), 'type' => 'number', 'min' => 0, 'max' => 1000, 'step' => 1, 'suffix' => 'px', 'default' => 0],
            ['key' => 'fixed_width', 'label' => __('Fixed Width', 'mv23theme'), 'type' => 'number', 'min' => 1, 'max' => 4000, 'step' => 1, 'suffix' => 'px'],
            ['key' => 'freezable', 'label' => __('Freezable', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            // ['key' => 'lazyload', 'label' => __('Lazyload', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            // ['key' => 'lazyloadSelector', 'label' => __('Lazyload Selector', 'mv23theme'), 'type' => 'text', 'default' => '.tns-lazy-img'],
            ['key' => 'loop', 'label' => __('Loop', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            ['key' => 'mode', 'label' => __('Mode', 'mv23theme'), 'type' => 'select', 'default' => 'carousel', 'options' => [
                    'carousel' => __('Carousel', 'mv23theme'),
                    'gallery' => __('Fade', 'mv23theme')
                ]
            ],
            ['key' => 'mouse_drag', 'label' => __('Mouse Drag', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            ['key' => 'nav', 'label' => __('Navigation', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            // ['key' => 'navAsThumbnails', 'label' => __('Nav as Thumbnails', 'mv23theme'), 'type' => 'boolean', 'default' => false],
            // ['key' => 'navContainer', 'label' => __('Nav Container Selector', 'mv23theme'), 'type' => 'text'],
            ['key' => 'nav_position', 'label' => __('Navigation Position', 'mv23theme'), 'type' => 'select', 'default' => 'bottom', 'options' => [
                    'top' => __('Top', 'mv23theme'),
                    'bottom' => __('Bottom', 'mv23theme')
                ]
            ],
            // ['key' => 'nested', 'label' => __('Nested Slider Mode', 'mv23theme'), 'type' => 'select', 'default' => 'false', 'options' => [
                    // 'false' => __('False', 'mv23theme'),
                    // 'inner' => __('Inner', 'mv23theme'),
                    // 'outer' => __('Outer', 'mv23theme')
                // ]
            // ],
            // ['key' => 'nextButton', 'label' => __('Next Button Selector', 'mv23theme'), 'type' => 'text'],
            ['key' => 'prevent_action_when_running', 'label' => __('Prevent Action While Running', 'mv23theme'), 'type' => 'boolean', 'default' => false, 'disable_prefix' => true],
            ['key' => 'prevent_scroll_on_touch', 'label' => __('Prevent Scroll on Touch', 'mv23theme'), 'type' => 'select', 'default' => 'false', 'options' => [
                    'false' => __('False', 'mv23theme'),
                    'auto' => __('Auto', 'mv23theme'),
                    'force' => __('Force', 'mv23theme')
                ]
            ],
            // ['key' => 'prevButton', 'label' => __('Previous Button Selector', 'mv23theme'), 'type' => 'text'],
            ['key' => 'rewind', 'label' => __('Rewind', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            // ['key' => 'responsive', 'label' => __('Responsive Options', 'mv23theme'), 'type' => 'text'],
            ['key' => 'slide_by', 'label' => __('Slide By', 'mv23theme'), 'type' => 'text', 'default' => 'page', 'suggestions' => ['1', 'page']],
            ['key' => 'slider_uid', 'label' => 'Slider UID', 'type' => 'text' ],
            ['key' => 'slider_theme', 'label' => 'Slider Theme', 'type' => 'text', 'suggestions' => ['theme1', 'none'], 'default' => 'theme1'],
            ['key' => 'speed', 'label' => __('Speed (ms)', 'mv23theme'), 'type' => 'number', 'min' => 1, 'max' => 10000, 'default' => 300],
            ['key' => 'start_index', 'label' => __('Start Index', 'mv23theme'), 'type' => 'text', 'default' => '0', 'suggestions' => ['0', '1', '2', 'in_the_middle', 'at_the_end']],
            ['key' => 'swipe_angle', 'label' => __('Swipe Angle', 'mv23theme'), 'type' => 'number', 'min' => 0, 'max' => 180, 'step' => 1, 'enable_slider' => true, 'default' => 15],
            ['key' => 'touch', 'label' => __('Touch', 'mv23theme'), 'type' => 'boolean', 'default' => true],
            // ['key' => 'viewportMax', 'label' => __('Viewport Max Width (px)', 'mv23theme'), 'type' => 'number', 'min' => 1, 'max' => 10000, 'step' => 1],
        ];
    }

    /**
     * Creates one repeater group per supported option.
     *
     * @return array
     */
    private static function get_groups()
    {
        $groups = [];

        foreach (self::getOptions() as $option) {
            $field = self::create_field($option);
            
            if (!$field) {
                continue;
            }

            $key = $option['key'];
            $label = $option['label'];

            $group = Repeater_Group::create($key)
                ->set_title(__($label, '_mv23theme'))
                ->set_maximum(1)
                ->add_fields(array($field));

            $group->add_field( Field::create('text', 'property')->set_default_value($key)->set_attr('style','display:none;') );

            $groups[$key] = $group;
        }

        return $groups;
    }

    /**
     * Creates the Ultimate Fields input for a single option definition.
     *
     * @param array $option
     * @return \Ultimate_Fields\Field|null
     */
    private static function create_field($option)
    {
        $label = $option['label'];
        $type = $option['type'];

        if ($type === 'number') {
            $field = Field::create('number', 'value')->set_prefix($label);

            if (isset($option['enable_slider']) && $option['enable_slider']) {
                $field->enable_slider($option['min'], $option['max'], $option['step']);
            }

            if (isset($option['suffix'])) {
                $field->set_suffix($option['suffix']);
            }
        } elseif ($type === 'text') {
            $field = Field::create('text', 'value')->set_prefix($label);

            if (isset($option['suggestions'])) {
                $field->add_suggestions($option['suggestions']);
            }

        } elseif ($type === 'boolean') {
            $field = Field::create('checkbox', 'value');

            if (isset($option['disable_prefix']) && $option['disable_prefix']) {
                // translators: %s: Enable property
                $field->set_text( sprintf( __('Enable %s', '_mv23theme'), $label ) );
            } else {
                $field->set_prefix($label)->set_text(__('Enable', '_mv23theme'));
            }

        } elseif ($type === 'select') {
            $field = Field::create('select', 'value')
                ->set_prefix($label)
                ->add_options($option['options']);
        } else {
            return null;
        }

        if (array_key_exists('default', $option)) {
            $field->set_default_value($option['default']);
        }

        return $field;
    }

    /**
     * Returns the default repeater groups.
     *
     * @return array
     */
    private static function get_default_groups()
    {
        return array(
            array(
                '__type' => 'controls',
                'property' => 'controls',
                'value' => true
            ),
            array(
                '__type' => 'nav',
                'property' => 'nav',
                'value' => false
            ),
            array(
                '__type' => 'rewind',
                'property' => 'rewind',
                'value' => true
            ),
            array(
                '__type' => 'mouse_drag',
                'property' => 'mouse_drag',
                'value' => true
            ),
            array(
                '__type' => 'slider_uid',
                'property' => 'slider_uid',
                'value' => ''
            )
        );
    }

    /**
     * Expands a repeater payload into a flat key/value settings map.
     *
     * @param mixed $settings Repeater rows or any arbitrary value.
     * @param bool $include_defaults Whether to seed the result with declared defaults.
     * @return array
     */
    public static function from_repeater($settings, $include_defaults = false)
    {
        $normalized = $include_defaults ? self::get_defaults() : [];

        if (!is_array($settings)) {
            return $normalized;
        }

        foreach ($settings as $row) {
            if (!is_array($row)) {
                continue;
            }

            $key = $row['property'] ?? ($row['__type'] ?? null);
            if (!$key || !self::has_option($key)) {
                continue;
            }

            if (!array_key_exists('value', $row)) {
                continue;
            }

            $normalized[$key] = self::coerce_value($key, $row['value']);
        }

        return $normalized;
    }

    /**
     * Collects declared defaults from the option catalog.
     *
     * @return array
     */
    private static function get_defaults()
    {
        $defaults = [];

        foreach (self::getOptions() as $option) {
            if (array_key_exists('default', $option)) {
                $defaults[$option['key']] = $option['default'];
            }
        }

        return $defaults;
    }

    /**
     * Checks whether a key belongs to the carousel option catalog.
     *
     * @param string $key
     * @return bool
     */
    private static function has_option($key)
    {
        $options = self::get_options_index();
        return isset($options[$key]);
    }

    /**
     * Normalizes a value according to the option type declared for the given key.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    private static function coerce_value($key, $value)
    {
        $options = self::get_options_index();
        if (!isset($options[$key])) {
            return $value;
        }

        $type = $options[$key]['type'];
        switch ($type) {
            case 'boolean':
                if (is_string($value)) {
                    $value = strtolower($value);
                    return in_array($value, ['1', 'true', 'yes', 'on'], true);
                }
                return (bool) $value;

            case 'number':
                if ($value === '' || $value === null) {
                    return '';
                }
                return is_numeric($value) ? 0 + $value : $value;

            default:
                return $value;
        }
    }

    /**
     * Indexes the option catalog by key and memoizes the result.
     *
     * @return array
     */

    private static function get_options_index()
    {
        if (self::$options_index !== null) {
            return self::$options_index;
        }

        self::$options_index = [];
        foreach (self::getOptions() as $option) {
            self::$options_index[$option['key']] = $option;
        }

        return self::$options_index;
    }

    /**
     * Converts repeater rows into HTML data attributes for the front-end slider runtime.
     *
     * @param mixed $settings Repeater rows or any arbitrary value.
     * @return array
     */
    public static function to_dataset_attributes($settings)
    {
        $normalized = self::from_repeater($settings);
        $map = [
            'mode' => 'data-mode',
            'axis' => 'data-axis',
            'edge_padding' => 'data-edge-padding',
            'fixed_width' => 'data-fixed-width',
            'auto_width' => 'data-auto-width',
            'slide_by' => 'data-slide-by',
            'center' => 'data-center',
            'controls' => 'data-show-controls',
            'controls_position' => 'data-controls-position',
            'nav' => 'data-show-nav',
            'nav_position' => 'data-nav-position',
            'speed' => 'data-speed',
            'autoplay' => 'data-autoplay',
            'autoplay_button_output' => 'data-autoplay-button-output',
            'autoplay_position' => 'data-autoplay-position',
            'autoplay_timeout' => 'data-autoplay-timeout',
            'autoplay_direction' => 'data-autoplay-direction',
            'autoplay_text' => 'data-autoplay-text',
            'autoplay_hover_pause' => 'data-autoplay-hover-pause',
            'autoplay_reset_on_visibility' => 'data-autoplay-reset-on-visibility',
            'animate_in' => 'data-animate-in',
            'animate_out' => 'data-animate-out',
            'animate_normal' => 'data-animate-normal',
            'animate_delay' => 'data-animate-delay',
            'loop' => 'data-loop',
            'rewind' => 'data-rewind',
            'auto_height' => 'data-auto-height',
            'touch' => 'data-touch',
            'mouse_drag' => 'data-mouse-drag',
            'swipe_angle' => 'data-swipe-angle',
            'prevent_action_when_running' => 'data-prevent-action-when-running',
            'prevent_scroll_on_touch' => 'data-prevent-scroll-on-touch',
            'freezable' => 'data-freezable',
            'start_index' => 'data-start-index',
            'slider_uid' => 'data-slider-uid',
            'slider_theme' => 'data-slider-theme'
        ];

        $attributes = [];
        foreach ($map as $key => $attribute) {
            if (!array_key_exists($key, $normalized)) {
                continue;
            }

            $value = $normalized[$key];
            if ($value === '' || $value === null) {
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            $attributes[$attribute] = $value;
        }

        return $attributes;
    }
}
