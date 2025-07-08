<?php
namespace Core\Builder\Template_Engine;

class Helpers {
    /**
     * Simplify values into shorthand notation
     * util for padding and margin
     * Returns the simplified value or null if simplification is not possible
     */
    public static function simplify_values($property, $top, $right, $bottom, $left) {
        // Count non-null values
        $values = array_filter([$top, $right, $bottom, $left], function($v) { return $v !== null; });
        
        if (empty($values)) {
            return null;
        }
        
        // If all four values are set
        if ($top !== null && $right !== null && $bottom !== null && $left !== null) {
            // All same value: property: value
            if ($top === $right && $right === $bottom && $bottom === $left) {
                return $top;
            }

            // Vertical same, horizontal same: property: vertical horizontal
            if ($top === $bottom && $left === $right) {
                return $top . ' ' . $right;
            }

            // Top same as bottom: property: top horizontal bottom
            if ($top === $bottom) {
                return $top . ' ' . $right . ' ' . $bottom;
            }

            // All different: property: top right bottom left
            return $top . ' ' . $right . ' ' . $bottom . ' ' . $left;
        }
        
        // For partial values, we can't simplify safely
        return null;
    }

    /**
     * Format numeric values as pixels
     * If the value is numeric, append 'px'
     */
    public static function format_numeric_value_as_pixels($value) {
        $formatted_value = (self::isNumeric($value)) ? $value . 'px' : $value;
        return $formatted_value;
    }

    private static function isNumeric($str){
        return preg_match('/^[0-9]+$/', $str);
    }
}