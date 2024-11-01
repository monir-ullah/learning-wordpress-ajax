<?php 


if ( ! defined( 'ABSPATH' ) ) {
   echo 'Fuck';
   exit;
}

Class Admin_Panel {
    public function __construct(){
        add_action( 'admin_menu', [$this, 'build_admin_panel'] );


        add_action( 'admin_enqueue_scripts', [$this, 'admin_panel_scripts'] );

        add_action( 'wp_ajax_wp_admin_panel_form_data', [$this, 'wp_admin_panel_form_data_func'] );
        add_action( 'wp_ajax_get_admin_panel_data', [$this, 'get_admin_panel_form_data'] );
    }

    public function get_admin_panel_form_data (){
        check_ajax_referer( 'admin_panel_form');

        $admin_panel_data = get_option( 'admin_panel_form' );

        if(!$admin_panel_data){
            wp_send_json_error( [
                'data' => 'Data Not Foundt'
            ] );
        }

        wp_send_json_success( $admin_panel_data );
    }
    public function wp_admin_panel_form_data_func(){

        check_ajax_referer( 'admin_panel_form');
       
       

        $formData = array(
            'user_name' => sanitize_text_field(isset($_POST['user_name']) ? $_POST['user_name'] : ''),
            'user_email' => sanitize_email(isset($_POST['user_email']) ? $_POST['user_email'] : ''),
            'like_or_dislike' => isset($_POST['like_or_dislike']) ? $_POST['like_or_dislike'] === 'on' ? true : false : false,
        );

        $update_option = update_option( 'admin_panel_form', $formData , false );
      

        wp_send_json(  $update_option );
    }

    public function build_admin_panel(){
        add_menu_page( 'Admin Panel', 'Admin Panel', 'manage_options', 'admin_panel', [$this, 'custom_admin_panel_page'], 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48IS0tIUZvbnQgQXdlc29tZSBGcmVlIDYuNi4wIGJ5IEBmb250YXdlc29tZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tIExpY2Vuc2UgLSBodHRwczovL2ZvbnRhd2Vzb21lLmNvbS9saWNlbnNlL2ZyZWUgQ29weXJpZ2h0IDIwMjQgRm9udGljb25zLCBJbmMuLS0+PHBhdGggZD0iTTM4NCAzMTIuN2MtNTUuMSAxMzYuNy0xODcuMSA1NC0xODcuMSA1NC00MC41IDgxLjgtMTA3LjQgMTM0LjQtMTg0LjYgMTM0LjctMTYuMSAwLTE2LjYtMjQuNCAwLTI0LjQgNjQuNC0uMyAxMjAuNS00Mi43IDE1Ny4yLTExMC4xLTQxLjEgMTUuOS0xMTguNiAyNy45LTE2MS42LTgyLjIgMTA5LTQ0LjkgMTU5LjEgMTEuMiAxNzguMyA0NS41IDkuOS0yNC40IDE3LTUwLjkgMjEuNi03OS43IDAgMC0xMzkuNyAyMS45LTE0OS41LTk4LjEgMTE5LjEtNDcuOSAxNTIuNiA3Ni43IDE1Mi42IDc2LjcgMS42LTE2LjcgMy4zLTUyLjYgMy4zLTUzLjQgMCAwLTEwNi4zLTczLjctMzguMS0xNjUuMiAxMjQuNiA0MyA2MS40IDE2Mi40IDYxLjQgMTYyLjQgLjUgMS42IC41IDIzLjggMCAzMy40IDAgMCA0NS4yLTg5IDEzNi40LTU3LjUtNC4yIDEzNC0xNDEuOSAxMDYuNC0xNDEuOSAxMDYuNC00LjQgMjcuNC0xMS4yIDUzLjQtMjAgNzcuNSAwIDAgODMtOTEuOCAxNzItMjB6Ii8+PC9zdmc+' );
    }

    public function admin_panel_scripts($hook){
        if('toplevel_page_admin_panel' !== $hook){
            return ;
        }

        $main_assets = require plugin_dir_path(__DIR__) . 'JS/output/index.asset.php';

       

        wp_enqueue_style( 'admin-panel-css', plugin_dir_url( __DIR__ ) . 'CSS/admin-panel.css', array(), time(), 'all' );
        wp_enqueue_script( 'admin-panel-react', plugin_dir_url( __DIR__ ) . 'JS/output/index.js', $main_assets['dependencies'], $main_assets['version'], true );

        wp_localize_script( 'admin-panel-react', 'js_variable', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            '_ajax_nonce' => wp_create_nonce( 'admin_panel_form' )
        ) );
    }

    public function custom_admin_panel_page(){
        
        echo '<div id="react-app"></div>';
    }
}
