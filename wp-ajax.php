<?php
/*
 * Plugin Name: WP AJAX
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WP_Ajax_Plug {

    public function __construct() {
        add_action( 'admin_menu', [$this, 'wp_ajax_menu_page'] );
    }

    public function wp_ajax_menu_page() {
        add_menu_page(
            "Ajax Page", "Ajax Menu", 'manage_options',
            'ajax-plug-page', [$this, 'wp_ajax_add_menu_page'],
            'dashicons-admin-plugins'
        );

        add_action( 'admin_enqueue_scripts', [$this, 'admin_hooks_page'] );
        add_action( 'wp_ajax_get_post_with_ajax', [$this, 'get_post_with_ajax'] );
    }

    public function admin_hooks_page($hook) {
        if( 'toplevel_page_ajax-plug-page' === $hook ) {
            wp_enqueue_script(
                'ajax-script',
                plugin_dir_url( __FILE__ ) . 'assets/JS/ajax-testing.js',
                array( 'jquery' ),
                '1.0.0',
                true
            );

            wp_localize_script(
                'ajax-script',
                'variable',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'ajax-nonce' ),
                )
            );
        }
    }

    public function wp_ajax_add_menu_page() {
        echo "<h1>Ajax Plugin Admin Menu</h1>";
        echo "<button type='submit' id='submit-btn'>Click Now</button>";
        echo "<div id='show-post'></div>";
    }

    public function get_post_with_ajax() {
        // check_ajax_referer( 'ajax-nonce' );

        $post_per_page = isset($_POST['post_per_page']) ? intval($_POST['post_per_page']) : 5;

        $posts = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => $post_per_page
        ]);

        $response = [];

        foreach ( $posts as $post ) {
            $response[] = [
                'post_title' => $post->post_title,
                'post_link'  => get_permalink( $post->ID )
            ];
        }

        wp_send_json( $response );
    }
}

new WP_Ajax_Plug();
