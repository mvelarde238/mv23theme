<?php
namespace Theme_Custom_Fields\Template_Engine;

class Box_Shadow{
    /**
     * Return array of css box shadow properties
     */
    public static function get_styles($args){
        $styles = array();

	    if ( isset($args['settings']['box_shadow']) ) {
	    	$box_shadows = $args['settings']['box_shadow']['box_shadow'];

	    	if (is_array($box_shadows) && count($box_shadows) > 0) {
	    		$properties = array();
	    		foreach ($box_shadows as $s) {
	    			$h_offset = (!empty($s['h-offset'])) ? $s['h-offset'] : "0";
	    			$v_offset = (!empty($s['v-offset'])) ? $s['v-offset'] : "0";
	    			$blur = (!empty($s['blur'])) ? $s['blur'] : "0";
	    			$color = ($s['color']) ? $s['color'] : '#000000';
	    			$color_rgba = hexToRgb($color,$s['alpha']);

	    			$property = $h_offset.'px '.$v_offset.'px '.$blur.'px ';
	    			if($s['spread']) $property .= $s['spread'].'px ';  
	    			$property .= 'rgba('.$color_rgba.')';
	    			if($s['position'] == 'inset') $property .= ' inset'; 

	    			$properties[] = $property;
	    		}
	    		$styles[] = 'box-shadow:' . implode(',', $properties);
	    	}
        
	    }

        return $styles;
    }
}
