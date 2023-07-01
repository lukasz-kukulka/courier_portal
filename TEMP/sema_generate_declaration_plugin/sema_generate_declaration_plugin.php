<?php
/*
Plugin Name: Generate Declaration Sema Plugin
Plugin URI: https://www.mecztrenera.pl/
Description: Plugin rejestracyjny
Version: 1.0
Author: Łukasz Kukułka
*/

require_once 'sema_form_generator.php'; 
//require_once 'sema_pdf_generator.php'; 

function sema_register_activation_plugin() {
    
}

function sema_register_deactivation_plugin() {
    
}

function sema_add_style_to_plugin() {
    wp_enqueue_style( 'sema_my_style', plugins_url( 'style.css', __FILE__ ), array(), '1.0.1.158', 'all' );
    wp_enqueue_script( 'my-script', plugin_dir_url( __FILE__ ) . 'scripts.js', array( 'jquery' ), '1.0.1.87', true );
}
  
add_action( 'wp_enqueue_scripts', 'sema_add_style_to_plugin' );

add_shortcode( 'semagenerator', 'sema_plugin_run' );

$sema_form = new declarationFormGenerator();

function sema_plugin_run() {
    global $sema_form;
    return $sema_form->getDeclarationButtonsChoice();
}

register_activation_hook( __FILE__ , 'sema_register_activation_plugin' );
register_deactivation_hook( __FILE__, 'sema_register_deactivation_plugin' );



