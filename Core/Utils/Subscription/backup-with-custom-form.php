<?php
namespace Core\Utils\Subscription;

class Subscribe_To_Continue {
    private $version = '1.0.0';
    private static $instance = null;

    private static $form_fields = array(
        array( 'name'=>'username', 'type'=>'text', 'required'=>true, 'icon'=>'bi bi-person-circle', 'label'=>'Nombre *', 'wrapper_classes'=>array() ),
        array( 'name'=>'email', 'type'=>'email', 'required'=>true, 'icon'=>'bi bi-envelope', 'label'=>'Email *', 'wrapper_classes'=>array() ),
        // array( 'name'=>'accept_terms', 'type'=>'checkbox', 'required'=>true, 'label'=>'He leído y entiendo los <a href="#">Términos de Uso y Políticas de Privacidad de Datos</a>','wrapper_classes'=>array() ),
        array( 'type'=>'submit', 'value'=>'SUSCRIBIRME', 'wrapper_classes'=>array() )
    );

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct(){
        add_action( 'wp_footer', array($this, 'display_form') );
        add_action( 'wp_footer', array($this, 'scripts') );

        add_action( 'wp_ajax_check_subscription_to_continue', array($this, 'ajaxify_button') );
        add_action( 'wp_ajax_nopriv_check_subscription_to_continue', array($this, 'ajaxify_button') );

        add_action( 'wp_ajax_process_subscription', array($this, 'handle_subscription') );
        add_action( 'wp_ajax_nopriv_process_subscription', array($this, 'handle_subscription') );

        // Evitar que se envíe el correo de notificación de creación de usuarios
        remove_action('register_new_user', 'wp_send_new_user_notifications');
        remove_action('edit_user_created_user', 'wp_send_new_user_notifications');

        // add_action( 'init', array($this, 'serve_file') );
    }

    public static function can_user_continue() {
        if ( isset($_COOKIE['user_email']) ) {
            $email = urldecode($_COOKIE['user_email']);
            return email_exists($email);
        }
        return false;
    }

    public function ajaxify_button() {
        $response = array();
        
        // Verificar si el usuario puede CONTNUAR
        if (!self::can_user_continue()) {
            $response['errorType'] = 'subjectCantContinue';
            wp_send_json_error($response);

        } else {
            // Obtener el ID del post
            // $post_ID = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
            // $file_path = get_attached_file($post_ID);
    
            // if (!$file_path || !file_exists($file_path)) {
            //     wp_send_json_error([
            //         'errorType' => 'fileNotFound', 
            //         'message' => 'Archivo no encontrado.'
            //     ]);
            // }
    
            // Generar una URL temporal para la descarga
            // $file_url = add_query_arg(
            //     ['download_file' => $post_ID, 'nonce' => wp_create_nonce('secure_download')],
            //     site_url()
            // );
            // $response = array('file_url' => $file_url);
            wp_send_json_success($response);
        }
    }

    public function handle_subscription(){
        $response = array();
        $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : null;
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : null;

        // Verificar que todos los datos del form estén correctos
        if( !$username || !$email ){
            if(!$username) $response['errorType'] = 'usernameMissing';
            if(!$email) $response['errorType'] = 'emailMissing';
            wp_send_json_error($response);
        }

        if( !is_email($email) ){
            $response['errorType'] = 'invalidEmail';
            wp_send_json_error($response);
        }

        if( !email_exists($email) ){
            // Crear el usuario
            $user_id = wp_insert_user([
                'user_login' => $email,
                'user_email' => $email,
                'first_name' => $username,
                'user_pass'  => wp_generate_password(),
                'role'       => 'subscriber'
            ]);
        
            // Si hay error, devolverlo
            if (is_wp_error($user_id)) {
                $response['errorType'] = 'insertFailed';
                wp_send_json_error($response);
            }
        }
        
        setcookie("user_email", $email, time() + (86400 * 30), "/");
        wp_send_json_success($response);
    }

    public function display_form(){ 
        $form_fields = apply_filters('subscribe_to_continue_form_fields', self::$form_fields);

        echo '<div id="stc-modal" class="modal stc-modal" style="max-width:500px;">';
        echo '<div class="modal-content">';
        do_action('before_subscribe_to_continue_form');
        echo '<form id="stc-form" action="">';
        echo '<div class="row">';
        foreach ($form_fields as $field) {
            $field_wrapper_classes = array('col', 's12');
            if( is_array($field['wrapper_classes']) && count($field['wrapper_classes']) ){
                $field_wrapper_classes = array_merge($field_wrapper_classes, $field['wrapper_classes']);
            } 
            echo '<div class="'.implode(' ',$field_wrapper_classes).'">';
            if( $field['type'] == 'text' || $field['type'] == 'email' ){
                if($field['name']){
                    if($field['label']){
                        echo '<label>';
                        if($field['icon']) echo '<i class="'.$field['icon'].'"></i>';
                        echo $field['label'];
                        echo '</label>';
                    }
                    echo '<input type="'.$field['type'].'"'; 
                    if($field['required']) echo ' required '; 
                    echo 'name="'.$field['name'].'">';
                }
            }
            if( $field['type'] == 'checkbox' && $field['name'] ){
                if($field['label']) echo '<label>';
                echo '<input type="'.$field['type'].'"'; 
                if($field['required']) echo ' required '; 
                echo 'name="'.$field['name'].'">';
                if($field['label']) echo '<span>'.$field['label'].'</span></label>';
            }
            if( $field['type'] == 'content' && $field['content'] ){
                echo $field['content'];
            }
            if( $field['type'] == 'submit' && $field['value'] ){
                echo '<input type="submit" value="'.$field['value'].'">';
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</form>';
        do_action('after_subscribe_to_continue_form');
        echo '</div>';
        echo '<a href="#!" class="modal-close"></a>';
        echo '</div>';
    }

    public function scripts(){ ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // ****************************************************************************************************
                // FUNCTION TO CREATE FORM DATA TO SEND FILES 
                // ****************************************************************************************************
                function create_form_data(form){
                    var formData = new FormData(),
                        formInputs = form.serializeObject(),
                        fileInputs = form.find('input[type=file]');

                    for (var key in formInputs){
                        formData.append(key, formInputs[key]);
                    }

                    for (let i = 0; i < fileInputs.length; i++) {
                        formData.append(fileInputs[i].name+'_qty', fileInputs[i].files.length);
                        for (let count = 0; count < fileInputs[i].files.length; count++) {
                            let file = fileInputs[i].files[count];
                            formData.append(fileInputs[i].name+'_'+count, file);
                        }
                    }

                    return formData;
                }

                function openPopup(response) {
                    const subscriptionPopUp = M.Modal.getInstance(document.getElementById('stc-modal'));
                    subscriptionPopUp.open();
                }

                $('body').on('click', '.subscribe-to-continue-js',function (e) {
                    e.preventDefault();
                    const button = $(this),
                        postId = button.data('id');

                    if(!postId) return;

                    $.ajax({
                        url: MV23_GLOBALS.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'check_subscription_to_continue',
                            post_id: postId
                        },
                        beforeSend: function () {
                            button.addClass('processing');
                        },
                        success: function (response) {
                            button.removeClass('processing');
                            if (response.success) {
                                // Redirigir a la URL de descarga
                                //window.location.href = response.data.file_url;
                                alert('continue');
                            } else {
                                switch (response.data.errorType) {
                                    case 'subjectCantContinue':     
                                        openPopup(response);
                                        break;

                                    default:
                                        const errorType = (response.data.errorType) ? response.data.errorType : 'unknown';
                                        alert('There was an error. Please try again. ERROR CODE: '+errorType);
                                        break;
                                }
                            }
                        },
                        error: function () {
                            alert('There was an error. Please try again. ERROR CODE: networkError');
                        },
                    });
                });

                $('#stc-form').on('submit',function (e) {
                    e.preventDefault();

                    var form = $(this),
                        formData = create_form_data(form);
                        formData.append('action', "process_subscription");

                    $.ajax({
                        url: MV23_GLOBALS.ajaxUrl,
                        type: 'POST',
                        dataType : "json",
                        processData: false,
                        contentType: false,
                        data : formData,
                        beforeSend: function () {
                            form.addClass('processing');
                        },
                        success: function (response) {
                            form.removeClass('processing');
                            if (response.success) {
                                // Ejecutar the continue action
                                alert('continue');
                            } else {
                                switch (response.data.errorType) {
                                    default:
                                        const errorType = (response.data.errorType) ? response.data.errorType : 'unknown';
                                        alert('There was an error. Please try again. ERROR CODE: '+errorType);
                                        break;
                                }
                            }
                        },
                        error: function () {
                            alert('There was an error. Please try again. ERROR CODE: networkError');
                        },
                    });
                });
            });
        </script>
        <?php
    }

    // public static function serve_file() {
    //     if (isset($_GET['download_file']) && isset($_GET['nonce'])) {
    //         $file_ID = intval($_GET['download_file']);

    //         // Validar nonce
    //         if (!wp_verify_nonce($_GET['nonce'], 'secure_download')) {
    //             wp_die('No autorizado');
    //         }

    //         $file_path = get_attached_file($file_ID);
    //         if (!$file_path || !file_exists($file_path)) {
    //             wp_die('Archivo no encontrado');
    //         }

    //         // Forzar la descarga
    //         header('Content-Description: File Transfer');
    //         header('Content-Type: application/octet-stream');
    //         header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    //         header('Expires: 0');
    //         header('Cache-Control: must-revalidate');
    //         header('Pragma: public');
    //         header('Content-Length: ' . filesize($file_path));

    //         readfile($file_path);
    //         exit;
    //     }
    // }
}