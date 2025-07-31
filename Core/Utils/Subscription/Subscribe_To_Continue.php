<?php
namespace Core\Utils\Subscription;

/* abstract */ class Subscribe_To_Continue {
    private $version = '1.1.0';
    private static $instance = null;
    private static $cf7_shortcode = '';

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct(){
        add_action( 'wp_footer', array($this, 'display_form') );
        add_action( 'wp_footer', array($this, 'scripts'), 100 );

        add_action( 'wp_ajax_check_subscription_to_continue', array($this, 'check_subscription_to_continue') );
        add_action( 'wp_ajax_nopriv_check_subscription_to_continue', array($this, 'check_subscription_to_continue') );

        add_action( 'wpcf7_mail_sent', array($this, 'on_cf7_mail_sent_handle_subscription') );

        // Evitar que se envíe el correo de notificación de creación de usuarios
        remove_action('register_new_user', 'wp_send_new_user_notifications');
        remove_action('edit_user_created_user', 'wp_send_new_user_notifications');
    }

    public function set_cf7_shortcode( $cf7_shortcode ){
        self::$cf7_shortcode = $cf7_shortcode;
    }

    public static function can_user_continue() {
        if ( isset($_COOKIE['user_email']) ) {
            $email = urldecode($_COOKIE['user_email']);
            return email_exists($email);
        }
        return false;
    }

    public function check_subscription_to_continue() {
        $data_id = isset($_POST['data_id']) ? intval($_POST['data_id']) : 0;
        $data_action = isset($_POST['data_action']) ? $_POST['data_action'] : '';
        $response = array( 
            'data_id' => $data_id,
            'data_action' => $data_action
        );
        
        if (!self::can_user_continue()) {
            $response['success'] = false;
            $response['errorType'] = 'subjectCantContinue';
        } else {
            $response['success'] = true;
            $response = apply_filters($data_action.'_filter_response', $response);
        }
        wp_send_json($response);
    }

    public function on_cf7_mail_sent_handle_subscription($contact_form) {
        $submission = \WPCF7_Submission::get_instance();
        $cf7_atts = shortcode_parse_atts( self::$cf7_shortcode );
    
        if ($submission && $contact_form->hash() == $cf7_atts['id']) {
            $posted_data = $submission->get_posted_data();
            $email = $posted_data['your-email'];
            $data_id = $posted_data['data-id'];
            $data_action = $posted_data['data-action'];

            if( !email_exists($email) ){
                $user_id = wp_insert_user([
                    'user_login' => $email,
                    'user_email' => $email,
                    'first_name' => $posted_data['your-name'],
                    'user_pass'  => wp_generate_password(),
                    'role'       => 'subscriber'
                ]);

                do_action('stc_after_insert_user', $user_id, $posted_data);
            }

            setcookie("user_email", $email, time() + (86400 * 30), "/");

            $response = array( 
                'success' => true,
                'subscriptionIsOk' => true,
                'data_id' => intval($data_id),
                'data_action' => $data_action
            );
            $response = apply_filters($data_action.'_filter_response', $response);
            $submission->add_result_props( $response );
        }
    }

    public function display_form(){ 
        $cf7_shortcode = apply_filters('cf7_shortcode_for_subscribe_to_continue', self::$cf7_shortcode);
        echo '<div id="stc-modal" class="theme-modal modal stc-modal" style="max-width:570px;">';
        echo '<div class="modal-content">';
        echo do_shortcode($cf7_shortcode);
        echo '</div>';
        echo '<a href="#!" class="modal-close"></a>';
        echo '</div>';
    }

    /* abstract */ public static function init(){}
    /* abstract */ public static function filter_response($response){}

    public function scripts(){ 
        ?>
        <style>.stc-modal+.modal-overlay{z-index: 950 !important;}</style>
        <?php
    }
}