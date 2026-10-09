<?php
namespace Core\Builder\Component\Settings;

use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

abstract class Repeater_Table
{
    protected static $options_index = [];

    /**
     * Builds a repeater field used to handle grouped settings. 
     *
     * @param string $key   Datastore key where the settings are stored.
     * @param string $label Field label shown in the UI.
     * @return \Ultimate_Fields\Field
     */
    /* abstract */ public static function getRepeater($key, $label)
    {
        $repeater = Field::create('repeater', $key, $label)
            ->set_add_text(' ')
            ->set_layout('table')
            ->set_chooser_type('dropdown')
            ->set_default_value( static::get_default_groups() );

        foreach ( static::get_groups() as $group ) {
            $repeater->add_group($group);
        }

        return $repeater;
    }

    /**
     * Returns the option catalog used by the repeater.
     *
     * @return array
     */
    abstract public static function getOptions();

    /**
     * Creates one repeater group per supported option.
     *
     * @return array
     */
    private static function get_groups()
    {
        $groups = [];

        foreach (static::getOptions() as $option) {
            $field = static::create_field_from_array($option);
            
            if (!$field) {
                continue;
            }

            $key = $option['key'];
            $label = $option['label'];

            $group = Repeater_Group::create($key)
                ->set_title(__($label, '_mv23theme'))
                ->set_maximum(1)
                ->add_fields(array($field));

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
    public static function create_field_from_array($option)
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

    protected static function get_default_groups(){
        return array();
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
        $normalized = $include_defaults ? static::get_defaults() : [];

        if (!is_array($settings)) {
            return $normalized;
        }

        foreach ($settings as $row) {
            if (!is_array($row)) {
                continue;
            }

            $key = $row['__type'] ?? null;
            if (!$key || !static::has_option($key)) {
                continue;
            }

            if (!array_key_exists('value', $row)) {
                continue;
            }

            $value = static::coerce_value($key, $row['value']);
            if (isset(static::get_options_index()[$key]['list_type']) && empty($value)) {
                unset($normalized[$key]);
                continue;
            }

            $normalized[$key] = $value;
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

        foreach (static::getOptions() as $option) {
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
        $options = static::get_options_index();
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
        $options = static::get_options_index();
        if (!isset($options[$key])) {
            return $value;
        }

        if (isset($options[$key]['list_type'])) {
            return static::coerce_list_value($key, $value, $options[$key]['list_type']);
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
     * Converts comma-separated text or an array into a typed list.
     *
     * @param string $key
     * @param mixed $value
     * @param string $list_type
     * @return array
     */
    private static function coerce_list_value($key, $value, $list_type)
    {
        if (is_string($value)) {
            $values = explode(',', $value);
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            error_log(sprintf('Invalid value for query parameter list "%s".', $key));
            return [];
        }

        $normalized = [];
        $has_invalid_items = false;
        foreach ($values as $item) {
            if (!is_scalar($item)) {
                $has_invalid_items = true;
                continue;
            }

            $item = trim((string) $item);
            if ($item === '') {
                continue;
            }

            if ($list_type === 'integer') {
                $integer = filter_var($item, FILTER_VALIDATE_INT);
                if ($integer === false) {
                    $has_invalid_items = true;
                    continue;
                }

                $normalized[] = $integer;
            } else {
                $normalized[] = $item;
            }
        }

        if ($has_invalid_items) {
            error_log(sprintf('Invalid item omitted from query parameter list "%s".', $key));
        }

        return $normalized;
    }

    /**
     * Indexes the option catalog by key and memoizes the result.
     *
     * @return array
     */

    private static function get_options_index()
    {
        $class = static::class;
        if (isset(static::$options_index[$class])) {
            return static::$options_index[$class];
        }

        static::$options_index[$class] = [];
        foreach (static::getOptions() as $option) {
            static::$options_index[$class][$option['key']] = $option;
        }

        return static::$options_index[$class];
    }
}