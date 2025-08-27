<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Logos {
    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', 'logos' )
        );

        foreach ( Theme_Options::getInstance()->get_logos_field_names() as $key => $value) {
            if( $key != 'custom' ) {
                $fields[] = Field::create( 'image', $key, $value )->set_width(25);
            }
        }

        return $fields;
    }
}