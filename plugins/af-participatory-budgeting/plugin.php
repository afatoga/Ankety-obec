<?php

/**
 * Plugin Name: Participatory Budgeting
 * Description: Plugin to extend Wordpress CMS with polls for voting
 * Author: Afatoga
 * Version: 1.0
 * Author URI: https://leoweb.cz
 * Text Domain: participatory-budgeting
 */

class ParticipatoryBudgeting
{
   function __construct()
   {
      // add filter hooks, wp_enqueue_script, etc.

      // To assign a method from your class to a WP 
      // function do something like this
      add_action('admin_menu', [$this, "admin"]);
   }

   public function admin()
   {
      $usertable_page = add_menu_page(
         __('Participatory Budgeting', 'participatory-budgeting'), //titulek
         __('Participatory Budgeting', 'participatory-budgeting'), //titulek v menu
         'edit_users', // capabilities
         'participatory-budgeting', //wp admin page slug
         [$this, "load_plugin_front_page"], //callback function
         'dashicons-image-filter', // https://developer.wordpress.org/resource/dashicons/#image-filter
         99
      );

      add_action('load-' . $usertable_page, [$this, "load_scripts"]);
   }

   public function load_scripts()
   {
      // methods you only use inside this class
      wp_enqueue_style("bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");

      //wp_register_script( 'vue2','https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js' );
      //wp_enqueue_script( 'vue2' );
      //wp_enqueue_script( 'unfetch', 'https://cdn.jsdelivr.net/npm/unfetch@4.2.0/dist/unfetch.js' );
      //wp_enqueue_script( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.4.5/dist/vuetify.js' );

      // wp_localize_script( 'vue2', 'wpRestApi', [
      //       'root'  => esc_url_raw( rest_url().'aa_restserver/v1' ), // /wp-json/aa_restserver/v1
      //       'nonce' => wp_create_nonce( 'wp_rest' ),
      // ] );

      // wp_enqueue_style( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.4.5/dist/vuetify.min.css', false );
      // wp_enqueue_style( 'material-design-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.9.95/css/materialdesignicons.min.css', false );
   }

   public function load_plugin_front_page()
   {

      if (isset($_POST["submit"])) {
         //action
      }

      load_template(dirname(__FILE__) . '/admin-frontpage.php');
   }
}

new ParticipatoryBudgeting();
