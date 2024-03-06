<?php
/**
 * Plugin Name: Related Posts
 * Plugin URI: https://github.com/mdrejon/related-posts
 * Description:  This plugin aims to improve navigation within the website by offering readers additional relevant content based on the current post’s category.
 * Version: 1.0.0
 * Author: Sydur Rahman
 * Author URI: https://sydurrahman.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: related-posts
 * Domain Path: /languages
 */


class  WTDRP_RELATED_POSTS {
    public function __construct() {

        // // Define constant
        $this->wtdrp_constant();
 
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'plugins_loaded', array( $this, 'run' ) );  
    }
 
    /**
     * Load plugin textdomain.  
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function load_textdomain() {

        load_plugin_textdomain( 'related-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

    }
    
    /**
     * Run the plugin
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function run() {
        
        // Check if it's admin
        if( is_admin() ){

            require_once WTDRP_PATH . 'admin/admin.php';
            new WTDRP_ADMIN();

            
        } else { 

            require_once WTDRP_PATH . 'app/app.php';
            new WTDRP_APP();

        }

    }

    /**
     * Define constant
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function wtdrp_constant(){

        define( 'WTDRP_VERSION', '1.0.0' ); 
        define( 'WTDRP_FILE', plugin_dir_url( __FILE__ ));
        define( 'WTDRP_PATH', plugin_dir_path( __FILE__ )); 
    }

}
new WTDRP_RELATED_POSTS(); 
