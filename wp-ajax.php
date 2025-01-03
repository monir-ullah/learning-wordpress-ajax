<?php
/*
 * Plugin Name: WP AJAX
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}




require_once plugin_dir_path(__FILE__) . 'assets/php/admin-panel.php';


class WP_Ajax_Plug {

    public function __construct() {
        add_action( 'admin_menu', [$this, 'wp_ajax_menu_page'] );

        add_action( 'admin_enqueue_scripts', [$this, 'admin_hooks_page'] );
        add_action( 'wp_enqueue_scripts', [$this, 'frontend_scripts'] );
        add_action( 'wp_ajax_get_post_with_ajax', [$this, 'get_post_with_ajax'] );

        
        add_shortcode( 'auth', [$this, 'user_authentication_shortcode'] );
        add_action( 'wp_ajax_update_user_profile_form', [$this, 'update_user_profile_form'] );
        add_action( 'wp_ajax_nopriv_user_login_form_action', [$this, 'login_user_from_front_end'] );

        new Admin_Panel();
    }


    public function login_user_from_front_end(){
        check_ajax_referer( 'user_login_form_nonce' );

        $user_login = wp_unslash( $_POST['user_login'] );
        $user_password = wp_unslash( $_POST['user_password'] );

        $user_info = wp_signon( [
            'user_login' => $user_login,
            'user_password' => $user_password,
            'remember' => true
        ], true );

        if(is_wp_error( $user_info )){
            wp_send_json( [
                'success' => false,
                'error_message' => $user_info-> get_error_message(),
                'user_object' => $user_info
            ]);
        }

        wp_send_json( [
            wp_send_json( [
                'success' => true,
                'success_message' => 'User Login  Successfull',
                'user_object' => $user_info
            ])
        ] );
    }

    public function update_user_profile_form(){

        check_ajax_referer( 'update_user_profile_nonce' );

        $user_id = get_current_user_id();


        $display_name = wp_unslash( $_POST['display_name'] );
        $user_email = wp_unslash( $_POST['user_email'] );
        

        global $updated_info ;
        if($display_name && $user_email){
            $updated_info =   wp_update_user([
                'ID'=> $user_id,
                'display_name'=> $display_name,
                'user_email'=> $user_email,
            ]);
        }


        if(is_wp_error( $updated_info )){
           wp_send_json( ['error'=> "Error"] );
        }

        wp_send_json( [
           'success' => $updated_info,  
           'display_name' => $display_name,
           'user_email' => $user_email,
           'user_id' => $user_id,
           'update_message' => $updated_info
        ] );
    }

    public function frontend_scripts(){
        wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) . 'assets/CSS/style.css', );

        wp_enqueue_script( 'ajax-user-form',  plugin_dir_url( __FILE__ ) . 'assets/JS/ajax-form.js', array('jquery'), time(), true );

        wp_localize_script(
            'ajax-user-form',
            'ajax_form_variable',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
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
                    <input type="text" name="display_name " id="display_name" value="<?php echo $display_name ?>" placeholder="Your Display Name ">
                    <input type="email" name="user_email " id="user_email" value="<?php echo $user_email ?>" placeholder="Email">
                    <?php wp_nonce_field( 'update_user_profile_nonce' );?>
                    <button type="submit" id="update_profile_btn" class="btn button-primary">Update Button</button>
                </form>
            <?php
        }else{
            ?>
                <h1>Login Page</h1>
                <p id="user_login_message"></p>
                <form action="" method="post" name="user_login_form" id="user_login_form">
                    <input type="text" name="user_name " id="user_name" value="" placeholder="Your User Name">
                    <input type="password" name="user_password " id="user_password" value="" placeholder="Enter Your Password ">
                    <?php wp_nonce_field( 'user_login_form_nonce' );?>
                    <button type="submit" id="user_login_form_btn">Log In</button>
                </form>
            <?php
            
        }
        return ob_get_clean();
    }

    public function wp_ajax_menu_page() {
        add_menu_page(
            "Ajax Page", 
            "Ajax Menu", 
            'manage_options',
            'ajax-plug-page', 
            [$this, 'wp_ajax_add_menu_page'],
            'dashicons-admin-plugins'
        );
    }

    public function admin_hooks_page($hook) {
        if( 'toplevel_page_ajax-plug-page' !== $hook ) {
           return ;
        }

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
