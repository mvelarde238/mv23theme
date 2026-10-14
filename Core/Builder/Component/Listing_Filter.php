<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core;

class Listing_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'listing-filter',
			__( 'Listing Filter', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'bi-filter';
    }

	public static function get_fields() {
		$fields = array();

        $posttypes = Core::get_post_types();

        $fields[] = Field::create( 'radio', 'posttype', __('Select a post type','mv23theme') )
            ->set_default_value('post')
            ->set_orientation( 'horizontal' )
            ->add_options( $posttypes );

        $fields[] = Field::create('text', 'listing_uid')
            ->set_prefix(__('Listing UID','mv23theme'))
            ->set_description(__('Enter the Listing UID to interact with.','mv23theme'))
            ->hide_label()
            ->required();

        $actions_class = 'uf-button button-primary';
        $action_cmd = 'add-listing-filter-item';
        $fields[] = Field::create( 'actions_control', 'common_actions', __('Add filters','mv23theme') )
			->set_actions( array(
                array( 'label' => __('Search', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'search-filter' ) ),
				array( 'label' => __('Month', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'month-filter' ) ),
				array( 'label' => __('Year', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'year-filter' ) ),
                array( 'label' => __('Taxonomy', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'taxonomy-filter' ) ),
                array( 'label' => __('Post Meta', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'postmeta-filter' ) ),
                array( 'label' => __('Submit', 'mv23theme'), 'command' => $action_cmd, 'class' => $actions_class, 'args' => array( 'cmpType' => 'submit-filter' ) ),
			));

		return $fields;
	}

    /**
     * Get the form action URL based on the post type.
     * 
     * If there is not a listing connected, route the form submission to the appropriate archive page for the selected post type.
     * Core\Frontend\Archive_Search::customize_main_query() will handle the query modifications based on the filter settings.
     *
     * @param string $posttype The post type slug.
     * @return string The form action URL.
     */
    private static function get_form_action_url( $posttype ){
        $action_url = home_url();

        $post_type_obj = get_post_type_object($posttype);
        if($post_type_obj && $post_type_obj->has_archive){
            $action_url .= '/'.$posttype.'/';
        }

        if(WOOCOMMERCE_IS_ACTIVE && $posttype === 'product'){
            $action_url = get_permalink( wc_get_page_id( 'shop' ) );
        }

        if( $posttype === 'post' ){
            $posts_page_id = get_option('page_for_posts');
            if($posts_page_id){
                $action_url = get_permalink($posts_page_id);
            }
        }

        return $action_url;
    }

    public static function get_reset_field_attributes( $value = '', $checked = null ) {
        $attributes = ' data-reset-value="' . esc_attr( (string) $value ) . '"';

        if ( null !== $checked ) {
            $attributes .= ' data-reset-checked="' . ( $checked ? '1' : '0' ) . '"';
        }

        return $attributes;
    }

    public static function display( $args ) {
        if( Template_Engine::is_restricted( $args ) ) return;

        $args['html_tag'] = 'form';
        
        $posttype = $args['posttype'] ?? 'post';

        $form_action_url = self::get_form_action_url( $posttype );
        $args['additional_attributes']['action'] = esc_url($form_action_url);
        $args['additional_attributes']['method'] = 'GET';

        $listing_uid = $args['listing_uid'] ?? 'listing-'.uniqid();
        $args['additional_attributes']['data-listing-uid'] = esc_attr( $listing_uid );

        ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
    }
}

new Listing_Filter();