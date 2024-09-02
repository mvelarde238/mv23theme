<?php
namespace Theme_Migrator\Migration;

use Theme_Migrator\Migration\Migrate_0_4_X_to_0_5_0;

class Migrate_Page_Header_0_4_X_to_0_5_0{
    public $page_id = null;
    public $new_data = array();

    public function __construct( $page_id ){
        $this->page_id = $page_id;
    }

    public function migrate(){
        if( $this->page_id != null){
            global $wpdb;

            // Select old page header post meta rows
            $old_page_header_rows = $wpdb->get_results($wpdb->prepare(
                "SELECT meta_key, meta_value, meta_id FROM {$wpdb->postmeta} WHERE (meta_key LIKE 'page_header_%' OR meta_key = 'slider_desktop' OR meta_key = 'slider_movil') AND post_id = %d",
                $this->page_id
            ));

            $page_header_slider = array();
            $page_header_content = array();
            $fake_settings_data = array( '__type' => 'page_header' );

            foreach ($old_page_header_rows as $row) {
                if( $row->meta_key == 'page_header_element' ){
                    $translate_element = array(
                        'default' => 'default',
                        'slider' => 'slider',
                        'contenido' => 'content',
                        'ninguno' => 'none'
                     );
                    $new_value = ( isset($translate_element[ $row->meta_value ]) ) ? $translate_element[ $row->meta_value ] : $row->meta_value;
                    update_post_meta($this->page_id, 'page_header_content_type', $new_value);
                    $this->new_data['page_header_content_type'] = $new_value;
                }
                if( $row->meta_key == 'slider_desktop' ){
                    if(!empty($row->meta_value)) $page_header_slider['desktop'] = $row->meta_value;
                    delete_post_meta( $this->page_id, $row->meta_key );
                }
                if( $row->meta_key == 'slider_movil' ){
                    if(!empty($row->meta_value)) $page_header_slider['mobile'] = $row->meta_value;
                    delete_post_meta( $this->page_id, $row->meta_key );
                }
                if( $row->meta_key == 'page_header_content2' ){
                    $old_data = maybe_unserialize($row->meta_value);
                    $page_header_content = Migrate_0_4_X_to_0_5_0::getInstance()->migrate_content_layout_data( $old_data );
                    delete_post_meta( $this->page_id, $row->meta_key );
                }
                if( $row->meta_key == 'page_header_content' && !empty($row->meta_value) ){
                    $page_header_content = array(
                        array(
                            array(
                                '__type' => 'text_editor',
                                '__width' => 12,
                                'content' => $row->meta_value
                            )
                        )
                    );  
                }

                $for_settings = array('page_header_bgi','page_header_video','page_header_bgcolor','page_header_bgc','page_header_text_color','page_header_id','page_header_class','page_header_padding','page_header_layout', 'page_header_bgi_parallax'
                );
                if( in_array( $row->meta_key, $for_settings ) ){
                    if( !empty($row->meta_value) ){
                        if( $row->meta_key == 'page_header_bgi' ) $fake_settings_data[ 'bgi' ] = $row->meta_value;
                        if( $row->meta_key == 'page_header_video' ){
                            $fake_settings_data[ 'add_video_bg' ] = true;
                            $page_header_video = maybe_unserialize($row->meta_value);
                            $fake_settings_data[ 'bgvideo' ] = $page_header_video['files'];
                            $fake_settings_data[ 'video_settings' ] = array(
                                'bgc' => "#000000",
                                'opacity' => $page_header_video['opacity'],
                                'autoplay' => 1,
                                'muted' => 1,
                                'loop' => 1
                            );
                        } 
                        if( $row->meta_key == 'page_header_bgcolor' || $row->meta_key == 'page_header_bgc' ){
                            $fake_settings_data[ 'color_de_fondo' ] = array(
                                'add_bgc' => true,
                                'color_de_fondo' => $row->meta_value
                            );
                        } 
                        if( $row->meta_key == 'page_header_text_color' && $row->meta_value != 'text-color-default' ){
                            $fake_settings_data[ 'color_scheme' ] = 'dark_scheme';  
                        }
                        if( $row->meta_key == 'page_header_id') $fake_settings_data[ 'module_id' ] = $row->meta_value;
                        if( $row->meta_key == 'page_header_class') $fake_settings_data[ 'class' ] = $row->meta_value;
                        if( $row->meta_key == 'page_header_padding' && $row->meta_value ){
                            $fake_settings_data['delete_margins'] = true;
                            $fake_settings_data['padding'] = array( 'top' => 1, 'bottom' => 1 );
                        }
                        if( $row->meta_key == 'page_header_layout' ) $fake_settings_data[ 'layout' ] = $row->meta_value;
                    }
                    delete_post_meta( $this->page_id, $row->meta_key );
                }

                $ancient_settings = array('page_header_content_bg', 'page_header_content_bgc', 'page_header_content_bgc_alpha', 'page_header_section');
                if( in_array( $row->meta_key, $ancient_settings ) ){
                    delete_post_meta( $this->page_id, $row->meta_key );
                }
            }

            update_post_meta($this->page_id, 'page_header_slider', $page_header_slider);
            $this->new_data['page_header_slider'] = $page_header_slider;

            update_post_meta($this->page_id, 'page_header_content', $page_header_content);
            $this->new_data['page_header_content'] = $page_header_content;

            $page_header_settings = Migrate_0_4_X_to_0_5_0::getInstance()->migrate_settings_data($fake_settings_data);
            update_post_meta($this->page_id, 'page_header_settings', $page_header_settings);
            $this->new_data['page_header_settings'] = $page_header_settings;
        }

        return $this->new_data;
    }
}