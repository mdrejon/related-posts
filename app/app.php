<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WTDRP_APP {
    public function __construct() {  

        // Enqueue admin scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'wtdrp_enqueue_scripts' ) );
        
        // Display related posts
        add_filter('the_content', array($this, 'wtdrp_display_related_posts'), 1, 999);
 
    }
 
    /**
     * Enqueue admin scripts
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function wtdrp_enqueue_scripts(){
 
        // Enqueue style
        wp_enqueue_style( 'wtdrp-admin-style', WTDRP_FILE . 'assets/css/wtdrp-style.css', array(), WTDRP_VERSION, 'all' ); 
    }

   /**
     * Display related posts
     *
     * @since 1.0.0
     * @author Sydur Rahman <sydurrahmant1@gmail.com>
     * @return void
     */
    public function wtdrp_display_related_posts($content ) {

        // Check if it's a single post page
        if (is_single()) {

            // Related posts Option
            $_wtdrp_option = get_option('_wtdrp_option');
            $enable_related_posts = isset($_wtdrp_option['enable_related_posts']) ? $_wtdrp_option['enable_related_posts'] : 1;
            $enable_thumbnail = isset($_wtdrp_option['enable_thumbnail']) ? $_wtdrp_option['enable_thumbnail'] : 1;
            $posts_order_by = isset($_wtdrp_option['posts_order_by']) ? $_wtdrp_option['posts_order_by'] : 'rand';
            $max_posts_display = isset($_wtdrp_option['max_posts_display']) ? $_wtdrp_option['max_posts_display'] : 5; 

            // If related posts feature is disabled
            if ($enable_related_posts != true) {
                return $content;
            }

            // Get current post ID
            $current_post_id = get_the_ID();

            // Get post categories
            $categories = get_the_category($current_post_id);

            if ($categories) {

                // Get category IDs
                $category_ids = array();
                foreach ($categories as $category) {
                    $category_ids[] = $category->term_id;
                } 

                // Query related posts
                $args = array(
                    'category__in' => $category_ids,
                    'post__not_in' => array($current_post_id),
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                    'no_found_rows' => true,
                    'posts_per_page' => $max_posts_display,
                    'orderby' => $posts_order_by, 
                );

                $related_posts = new WP_Query($args);

                // Display related posts if found
                if ($related_posts->have_posts()) {
                    $content .= '<div class="wtdrp-related-posts">';
                    $content .= '<h3>'.esc_html(__('Related Posts', 'related-posts' )).'</h3>';
                    $content .= '<ul>';
                    while ($related_posts->have_posts()) {
                        $related_posts->the_post();
                        $content .= '<li><a href="' . esc_url(get_permalink()) . '">';
                        if ($enable_thumbnail == true && has_post_thumbnail()) {
                            $content .= '' . get_the_post_thumbnail() . '';
                        }
                        $content .= esc_html(get_the_title());
                        $content .= '</a></li>'; 
                    }
                    $content .= '</ul>';
                    $content .= '</div>';
                }

                // Reset post data
                wp_reset_postdata();
            }
        }

        return $content;
    }
}