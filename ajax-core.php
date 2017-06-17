<?php
/**
 * Plugin Name: Ajax Email
 * Plugin URI: https://knightglen.com
 * Description: This is a plugin that allows us to send email via ajax in WordPress
 * Version: 1.0.0
 * Author: Glen Knight
 * Author URI: https://knightglen.com
 * License: GPL2
 */

 function enqueue_dependencies(){
     wp_enqueue_style( 'core-style', plugins_url('/css/core-style.css', __FILE__), false, '1.0.0', 'all');
 }
 add_action('wp_enqueue_scripts', "enqueue_dependencies");

function html_form_code(){
    echo '<form class="contactForm" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
  	echo '<p>';
  	echo 'Your Name (required) <br/>';
  	echo '<input type="text" name="cf-name" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" required />';
  	echo '</p>';
  	echo '<p>';
  	echo 'Your Email (required) <br/>';
  	echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" required />';
  	echo '</p>';
  	echo '<p>';
  	echo 'Subject (required) <br/>';
  	echo '<input type="text" name="cf-subject" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" required />';
  	echo '</p>';
  	echo '<p>';
  	echo 'Your Message (required) <br/>';
  	echo '<textarea rows="10" cols="35" name="cf-message" required>' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
  	echo '</p>';
  	echo '<input class="btn btn-plugin" type="submit" name="cf-submitted" value="Send" /><button class="btn btn-plugin" name="cf-reset" type="reset">Clear</button>';
  	echo '</form>';
}



function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div class="confirmationBox">';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
          echo '<div class="confirmationBox_Error">';
          echo '<p>An Unexpected Error Occurred</p>';
          echo '</div>';
        }
    }
}

function cf_shortcode(){
    ob_start();
    deliver_mail();
    html_form_code();

    return ob_get_clean();
}

add_shortcode('ajax_contact_form','cf_shortcode');
?>
