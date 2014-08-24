<?php 
wp_enqueue_script('plupload-all');
wp_enqueue_script('jquery');

add_theme_support( 'woocommerce' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<section id="main" class="woocommerce">';
}

function my_theme_wrapper_end() {
  echo '</section>';
}

function register_my_menu() {
  register_nav_menu('header_menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );
?>