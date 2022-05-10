<?php
if (!function_exists('mv23_cf7_email_template')) {
    function mv23_cf7_email_template($contact_form){
        // get mail property
        $mail = $contact_form->prop('mail'); // returns array
        $mail_2 = $contact_form->prop('mail_2'); // returns array

        $use_html = $mail['use_html'];
        $use_html_2 = $mail_2['use_html'];

        if( $use_html || $use_html_2 ){
            // get header temlate from themes root (one above)
            ob_start();
            get_template_part('inc/partials/mail-header');
            $header = ob_get_clean();

            // get footer temlate from themes root (one above)
            ob_start();
            get_template_part('inc/partials/mail-footer');
            $footer = ob_get_clean();
        }

        if($use_html){
            $body = $mail['body'];
            $mail['body'] = $header . $body . $footer;
            $contact_form->set_properties(array('mail' => $mail));
        }

        if($mail_2['active'] && $use_html_2){
            $body_2 = $mail_2['body'];
            $mail_2['body'] = $header . $body_2 . $footer;
            $contact_form->set_properties(array('mail_2' => $mail_2));
        }
        print_r( $mail['body'] );
        die();
    }
}

add_action('wpcf7_before_send_mail', 'mv23_cf7_email_template');