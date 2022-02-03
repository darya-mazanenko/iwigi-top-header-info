<?php
/**
 * Plugin Name: IWIGI Top Header Information
 * Plugin URI: 
 * Description: Displays information as a carousel at the very top of the website
 * Version: 1.0.0
 * Author: D Mazanenka
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 
if( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Core plugin class
 */
     
    class IWIGI_Top_Header_Info
    {
         
        public function __construct() {
            
        }     
        
        public function init() {
            add_action( 'admin_menu', array( $this, 'admin_banners_page' ) );   
            add_action( 'admin_init', array( $this, 'admin_banners_settings' ) );
            add_action( 'wp_body_open', array( $this, 'top_header_banners_output' ) );
            
            add_action( 'wp_enqueue_scripts', array( $this, 'public_scripts' ) );
        }
        
        public function public_scripts() {
            
            //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iwigi-ajax-search-scroll-public.js', array( 'jquery' ), $this->version, true );
            
            wp_enqueue_script( 'iwigi-slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.1', true);
            wp_enqueue_script( 'iwigi-top', plugin_dir_url( __FILE__ ) . 'assets/js/iwigi-top-header-public.js', array( 'jquery' ), '1.0', true );
        }
        
        public function admin_banners_page() {
            add_options_page( 'IWIGI Top Banners Settings', 'IWIGI Top Banners', 'manage_options', 'iwigi_top_banners_settings', array( $this, 'top_banner_settings' ) );
        }
        
        public function top_banner_settings() {
            ?>
            <div class="wrap">
                <h1>IWIGI Top Banners Settings</h1>
              
                <form action='options.php' method ='POST'>
                    <?php 
                        settings_fields( 'iwigibanners' ); // group name registered in 
                        do_settings_sections( 'iwigi_top_banners_settings' ); 
                        submit_button();
                    ?>
                </form>
                
            </div>
            
            <?php
        }
        
        public function admin_banners_settings() {
            
            // I decided to register one section only
            add_settings_section( 
                'iwigi_banner_first_section', //section slug
                null, // section title or null
                null, // section additional info, or null
                'iwigi_top_banners_settings' // page slug registered in add_options_page
            );
            
            // First Banner Text Field
            add_settings_field(
                'iwigi_first_text', // option name stored in wp_options
                'First Banner Text',
                array( $this, 'banner_first_output' ),
                'iwigi_top_banners_settings', // page slug registered in add_options_page
                'iwigi_banner_first_section' // section slug
            );
            
            register_setting( 
                'iwigibanners',  // group name
                'iwigi_first_text', // option name stored in wp_options
                array( 
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => ''
                ) 
            );
            
            
            // Second Banner Text Field
            add_settings_field(
                'iwigi_second_text', // option name stored in wp_options
                'Second Banner Text',
                array( $this, 'banner_second_output' ),
                'iwigi_top_banners_settings', // page slug registered in add_options_page
                'iwigi_banner_first_section' // section slug
            );
            
            register_setting( 
                'iwigibanners',  // group name
                'iwigi_second_text', // option name stored in wp_options
                array( 
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => ''
                ) 
            );
        }
        
        // html output for the first field
        public function banner_first_output() {
            ?>
            <input name='iwigi_first_text' type='text' value="<?php echo esc_attr( get_option( 'iwigi_first_text' ) ); ?>">
            <?php
        }
        
        // html output for the second field
        public function banner_second_output() {
            ?>
            <input name='iwigi_second_text' type='text' value="<?php echo esc_attr( get_option( 'iwigi_second_text' ) ); ?>">
            <?php
        }
        
        /**
         * HTML banners output right after the <body>
         * 
         * Needs some checks in case theme's template doesn't have do_action( 'wp_body_open' )
         */
        public function top_header_banners_output() {
            ?>
            <div class="col-full">
        		<div class="top-info">
        			<p><?php echo esc_html( get_option( 'iwigi_first_text' ) ); ?></p>
        			<p><?php echo esc_html( get_option( 'iwigi_second_text' ) );?></p>
        		</div>
        	</div>
            <?php
        }
         
    }
    
    $iwigi_top_header_info = new IWIGI_Top_Header_Info();
    $iwigi_top_header_info->init();




