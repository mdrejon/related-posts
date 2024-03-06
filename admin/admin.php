<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WTDRP_ADMIN {
    public function __construct() { 

        // Add related posts menu
        add_action( 'admin_menu', array( $this, 'wtdrp_related_posts_menu' ) );

        // Call register settings function
        add_action('admin_init', array($this, 'wtdrp_register_related_posts_settings') );
    }
 
    
 
    /**
     *  Add related posts menu
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function wtdrp_related_posts_menu() {
        add_menu_page(
            __('WTDRP Related Posts Settings', 'related-posts'),
            __('WTDRP Related Posts', 'related-posts'),
            'manage_options',
            'related_posts_settings',
            array($this, 'wtdrp_related_posts_settings_page'),
            'dashicons-admin-post',
            10
        );
    }

    /**
     * Register related posts settings
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function wtdrp_register_related_posts_settings() {
        register_setting(
            'related_posts_settings_group', 
            '_wtdrp_option', 
            array($this, 'related_posts_settings_group_sanitization')
        );
        add_settings_section(
            'related_posts_settings_section', 
            __('Related Posts Settings Options', 'related-posts'), 
            '__return_empty_string', 
            'related_posts_settings'
        );
        
        add_settings_field(
            'enable_related_posts', 
            __('Related Posts Feature', 'related-posts'), 
            array($this, 'enable_related_posts_callback'),
            'related_posts_settings', 
            'related_posts_settings_section'
        );

        add_settings_field(
            'enable_thumbnail', 
            __('Display Thumbnail', 'related-posts'), 
            array($this, 'enable_thumbnail_callback'),
            'related_posts_settings', 
            'related_posts_settings_section'
        );
        

        add_settings_field(
            'posts_order_by', 
            __('Display Post Order By', 'related-posts'), 
            array($this, 'posts_order_by_callback'),
            'related_posts_settings', 
            'related_posts_settings_section'
        );
        add_settings_field(
            'max_posts_display', 
            __('Maximum Related Posts to Display', 'related-posts'),
            array($this, 'max_posts_display_callback'),
            'related_posts_settings', 
            'related_posts_settings_section'
        );

        
    
    }

    /**
     * Sanitization callback function
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */ 
    function related_posts_settings_group_sanitization($input) {
        $sanitize = array();

        // Enable thumbnail
        if(isset($input['enable_related_posts'])) {
            $sanitize['enable_related_posts'] = sanitize_text_field($input['enable_related_posts']);
        }else{
            $sanitize['enable_related_posts'] = 0;
        }

        // Max posts display
        if(isset($input['max_posts_display'])) {
            $sanitize['max_posts_display'] = sanitize_text_field($input['max_posts_display']);
        }
        // Max posts display
        if(isset($input['posts_order_by'])) {
            $sanitize['posts_order_by'] = sanitize_text_field($input['posts_order_by']);
        }

        // Enable thumbnail
        if(isset($input['enable_thumbnail'])) {
            $sanitize['enable_thumbnail'] = sanitize_text_field($input['enable_thumbnail']);
        }else{
            $sanitize['enable_thumbnail'] = 0;
        } 

        return $sanitize;
    }

     /**
     * Field callback function
     * enable_related_posts
     * 
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    function enable_related_posts_callback() {
         
        $_wtdrp_option = get_option('_wtdrp_option'); 
        $enable_related_posts = isset($_wtdrp_option['enable_related_posts']) ? $_wtdrp_option['enable_related_posts'] : 1;
        echo '<input type="checkbox" name="_wtdrp_option[enable_related_posts]" value="1" ' . checked(1, $enable_related_posts, false) . ' />';
    } 

    /**
     * Field callback function
     * posts_order_by
     * 
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    function posts_order_by_callback() {
         
        $_wtdrp_option = get_option('_wtdrp_option'); 
        $posts_order_by = isset($_wtdrp_option['posts_order_by']) ? $_wtdrp_option['posts_order_by'] : 'rand'; 
        echo '<select name="_wtdrp_option[posts_order_by]">';
        echo '<option value="date" ' . selected('date', $posts_order_by, false) . '>Date</option>';
        echo '<option value="title" ' . selected('title', $posts_order_by, false) . '>Title</option>';
        echo '<option value="rand" ' . selected('rand', $posts_order_by, false) . '>Random</option>';
        echo '</select>';
    } 

    /**
     * Field callback function
     * max_posts_display
     * 
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    function max_posts_display_callback() {
         
        $_wtdrp_option = get_option('_wtdrp_option'); 
        $max_posts_display = isset($_wtdrp_option['max_posts_display']) ? $_wtdrp_option['max_posts_display'] : 5;
        echo '<input type="number" name="_wtdrp_option[max_posts_display]" value="' . esc_attr($max_posts_display) . '"  />';
    } 

    /**
     * Field callback function
     * enable_thumbnail
     * 
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    function enable_thumbnail_callback() {
         
        $_wtdrp_option = get_option('_wtdrp_option'); 
        $enable_thumbnail = isset($_wtdrp_option['enable_thumbnail']) ? $_wtdrp_option['enable_thumbnail'] : 1;
        echo '<input type="checkbox" name="_wtdrp_option[enable_thumbnail]" value="1" ' . checked(1, $enable_thumbnail, false) . ' />';
    }

     /**
     * Related Posts Settings Page
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function wtdrp_related_posts_settings_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html(__('Related Posts Settings', 'related-posts')) ?></h2>
            <p><?php echo esc_html(__('Configure settings for the Related Posts plugin here.', 'related-posts')) ?></p>
            <!-- Add your settings form or content here -->
            <form method="post" action="options.php">  
                <?php  

                    settings_fields('related_posts_settings_group');
                    do_settings_sections('related_posts_settings');
                    submit_button();
                     
                ?>
            </form>
        </div>
        <?php
    }
 
}