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

        add_action( 'admin_enqueue_scripts', [$this, 'admin_hooks_page'] );
        add_action( 'wp_enqueue_scripts', [$this, 'frontend_scripts'] );
        add_action( 'wp_ajax_get_post_with_ajax', [$this, 'get_post_with_ajax'] );

        add_shortcode( 'auth', [$this, 'user_authentication_shortcode'] );
    }

    public function frontend_scripts(){
        wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) . 'assets/CSS/style.css', );

        wp_enqueue_script( 'ajax-user-form',  plugin_dir_url( __FILE__ ) . 'assets/JS/ajax-form.js', array(), time(), true );
    }

    public function user_authentication_shortcode(){

        ob_start();

        if(is_user_logged_in(  )){
            
            if(is_page( 'auth' )){
                echo '<h1>Yes</h1>';
               
            }

            $user = wp_get_current_user()->data;
            $display_name = $user -> display_name;
            $user_email = $user -> user_email;
            ?>
                <h3>Update User Profile:</h3>
                <form action="" method="POST" name="user_profile_update" id="user_profile_update">
                    <input type="text" name="display_name " id="display_name" value="<?php echo $display_name ? $display_name : '' ?>" placeholder="Your Display Name ">
                    <input type="email" name="display_name " id="display_name" value="<?php echo $user_email ? $user_email : '' ?>" placeholder="Your Display Name ">
                    <button type="submit" id="update_profile_btn">Update Button</button>
                </form>
            <?php
        }else{
            ?>
                <h3>Update User Profile:</h3>
                <form action="" method="post" name="user_login">
                    <input type="text" name="display_name " id="display_name" value="" placeholder="Your Display Name ">
                    <input type="email" name="display_name " id="display_name" value="" placeholder="Your Display Name ">
                    <!-- <button type="submit" id="update_profile_btn">Update Button</button> -->
                </form>
            <?php
            return;
        }
        return ob_get_clean();
    }

    public function wp_ajax_menu_page() {
        add_menu_page(
            "Ajax Page", "Ajax Menu", 'manage_options',
            'ajax-plug-page', [$this, 'wp_ajax_add_menu_page'],
            'dashicons-admin-plugins'
        );
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
        echo "<button type='submit' id='submit-btn'>Show Post</button>";
        echo "<div id='show-post'></div>";
    }

    public function get_post_with_ajax() {
        check_ajax_referer( 'ajax-nonce' );

        $post_per_page = isset($_POST['post_per_page']) ? intval($_POST['post_per_page']) : 5;

        
        $posts = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => $post_per_page
        ]);

    
        wp_send_json(  $posts );
    }
}

new WP_Ajax_Plug();
