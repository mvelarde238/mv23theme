<?php
class MV23_Form {
	public function __construct() {
		add_shortcode( 'mv23_form', array($this, 'print_form') );
		add_action( 'wp_ajax_form_process', array($this, 'form_process') );
		// add_action( 'wp_ajax_nopriv_form_process', array($this, 'form_process') );
	}

	function print_form( $atts ){
    	ob_start();
    	$a = shortcode_atts( array(
    	    'template' => 'fullwidth',
    	), $atts );

        $col_width = ( $a['template'] == 'condensed' ) ? 'm12' : 'm6';
    	?>
    	<section id="mv23-form" class="mv23-form" data-status="initial" data-template="<?php echo $a['template']; ?>">
            <ul class="mv23-form__error-msgs" data-status="initial"></ul>
            <form id="mv23-form__form">
                <div class="row">
                    <div class="col s12 <?=$col_width?> envio-field">
                        <div class="input-field inline">
                            <input id="envio" name="envio" type="number" class="validate">
                            <label for="envio">Monto de envío *</label>
                        </div>
                        <span>
                            <img src="<?php echo home_url('wp-content/uploads/2020/09/bandera-chile.jpg'); ?>">
                            <strong>PESOS CHILENOS</strong>
                        </span>
                    </div>
                    <div class="col s12 <?=$col_width?> recibe-field">
                        <div class="input-field inline">
                            <input id="recibe" name="recibe" readonly type="number">
                            <label for="recibe">Tu beneficiario recibe </label>
                        </div>
                        <div class="input-field inline">
                            <?php $currencies = get_option( 'form_currencies' ); ?>
                            <?php if ( is_array($currencies) && count($currencies) > 0 ): 
                                $first_flag = ( $currencies[0]['flag'] ) ? wp_get_attachment_image_src( $currencies[0]['flag'], 'full') : '';
                                ?>
                                <img id="currency-flag" src="<?php echo $first_flag[0] ?>">
                                <select name="currency" id="currency">
                                    <?php foreach ($currencies as $currency): 
                                        $name = $currency['name'];
                                        $cambio = $currency['cambio'];
                                        $flag = $currency['flag'];
                                        $flag_url = ( $flag ) ? wp_get_attachment_image_src( $flag, 'full') : '';
                                        ?>
                                        <option value="<?=$name?>"
                                            data-cambio="<?=$cambio?>"
                                            data-flag="<?=$flag_url[0]?>"><?php echo $name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <h4>Datos personales de quién envía:</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name">Nombres</label>
                    </div>
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="lastname" name="lastname" type="text" class="validate">
                        <label for="lastname">Apellidos</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">contact_mail</i>
                        <input id="email" name="email" type="email" class="validate">
                        <label for="email">Email *</label>
                    </div>
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">phone_android</i>
                        <input id="phone" name="phone" type="text" class="validate">
                        <label for="phone">Número de Celular *</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">flag</i>
                        <input id="country" name="country" type="text" class="validate">
                        <label for="country">País</label>
                    </div>
                    <div class="input-field col s12 <?=$col_width?>">
                        <i class="material-icons prefix">credit_card</i>
                        <input id="card" name="card" type="text" class="validate">
                        <label for="card">Cuenta Bancaria *</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 right-align">
                        <?php wp_nonce_field( 'mv23formnonce', 'nonce', true, true ); ?>
                        <p class="right-align">
                            <b>* Campos requeridos &nbsp&nbsp</b>
                            <input type="submit" id="mv23-form__form__btn" value="Guardar Transferencia">
                        </p>
                    </div>
                </div>
            </form>
            <div class="mv23-form__success-msg" data-status="initial"></div>
    	</section>
    	<?php
    	return ob_get_clean();
	}

	function form_process() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $errors = array();

            // if ( !wp_verify_nonce($_REQUEST['nonce'], 'mv23formnonce' ) ){
                // $result['type'] = "error";
                // $result['msg'] = "Nonce Error!.";
                // array_push($errors, array('input'=>'nonce','msg'=>'Nonce Error! Error de verificación.'));
                // $result['errors'] = $errors;
            // }else{
                $envio = sanitize_text_field($_REQUEST["envio"]);
                $recibe = sanitize_text_field($_REQUEST["recibe"]);
                $currency = sanitize_text_field($_REQUEST["currency"]);
                $name = sanitize_text_field($_REQUEST["name"]);
                $lastname = sanitize_text_field($_REQUEST["lastname"]);
                $email = sanitize_email($_REQUEST["email"]);
                $phone = sanitize_text_field($_REQUEST["phone"]);
                $country = sanitize_text_field($_REQUEST["country"]);
                $card = sanitize_text_field($_REQUEST["card"]);

                /*

















                VALIDAR QUE LO QUE SE RECIBA SEA COHERENTE CON EL CAMBIO DEL ADMIN NO VAYA SER QUE UN USUARIO PENDEJO CAMBIE EL VALOR DESDE LA CONSOLA













                */
    
                if($envio==='') array_push($errors, array('input'=>'envio','msg'=>'El monto de envío es requerido'));
                if($email==='') array_push($errors, array('input'=>'email','msg'=>'El campo Email está vacío'));
                if($phone==='') array_push($errors, array('input'=>'phone','msg'=>'El campo Teléfono está vacío'));
                if( !is_email($email) ) array_push($errors, array('input'=>'email','msg'=>'El formato del campo Email no es correcto'));
                if($card==='') array_push($errors, array('input'=>'name','msg'=>'El campo Cuenta Bancaria está vacío'));

                if ( count($errors) > 0 ) {
                    $result['type'] = "error";
                    $result['msg'] = "Por favor, corrige los errores del formulario";
                    $result['errors'] = $errors;
                } else {
                    global $user_ID;

                    $post_id = wp_insert_post(array(
                        'post_title'=>'Transferencia', 
                        'post_type'=>'transferencia',
                        'post_author' => $user_ID,
                        'meta_input' => array(
                            'cantidad_de_envio' => $envio,
                            'cantidad_recibida' => $recibe,
                            'moneda' => $currency,
                            'nombre' => $name,  
                            'apellido' => $lastname,  
                            'email' => $email,  
                            'celular' => $phone,  
                            'pais' => $country,  
                            'cuenta' => $card,  
                        )
                    ));

                    if(!is_wp_error($post_id)){
                        $my_post = array(
                            'ID'           => $post_id,
                            'post_title'   => 'Transferencia #'.$post_id,
                            'guid' => 'transferencia-'.$post_id
                        );
                        wp_update_post( $my_post );

                        $result['type'] = "success";
                        $result['msg'] = 'Transferencia guardada correctamente.';
                    } else {
                        $result['type'] = "error";
                        $result['msg'] = 'No se pudo guardar la transferencia.';
                    }

                    // $mail = WP_CONTENT_DIR .'/themes/mv23theme/assets/emails/cotizacion.html';
                    // if (file_exists($mail)) : 
                    //     $mail_template = file_get_contents( $mail ); 
                    //     $message = $this->replace_mail_template_placemarkers($mail_template, $name, $email, $phone, $company, $message, $productos );

                    //     $to = 'contacto@mv23.com'; 
                    //     $subject = "Cotización Web";
                    //     $headers = "MIME-Version: 1.0" . "\r\n";
                    //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    //     $headers .= "From: ".$full_name." <" . $email . ">" . "\r\n";
                    //     $headers .= "Bcc: velarde23soluciones@gmail.com" . "\r\n";

                    //     if( wp_mail($to, $subject, $message, $headers) ) {
                    //         $result['type'] = "success";
                    //         $result['msg'] = 'Formulario enviado correctamente, nos pondremos en contacto contigo respondiendo tu solicitud.';
                	   // } else {
                    //         $result['type'] = "error";
                    //         $result['msg'] = 'No se pudo enviar el formulario.';
                    //     }
                    // else :
                    //     $result['type'] = "error";
                    //     $result['msg'] = 'No se pudo enviar el formulario. Template Error.';
                    // endif;
                }
            // }
            $result = json_encode($result);
            echo $result;
            wp_die();

        } else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            wp_die();
        }
	}

    // function replace_mail_template_placemarkers($mail_template, $name, $email, $phone, $company, $message, $productos){
    //     $placemarkers = ['{$nombre}','{$email}','{$telefono}','{$empresa}','{$comentarios}','{$productos}'];
    //     $values = [$name, $email, $phone, $company, $message, $productos];
    //     $message = str_replace($placemarkers, $values, $mail_template);
    //     return $message;
    // }
}

new MV23_Form();