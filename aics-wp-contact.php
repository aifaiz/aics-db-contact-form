<?php defined( 'ABSPATH' ) or die( 'The hell? nope.. just nope' );
/*
Plugin Name: AiCS Contact Form Database Submit
Plugin URI: http://www.aics.my/
Description: Custom contact form, with function to record each submission to db.
Author: FAiZ
Version: 1.0
*/

$aics_contact_path = plugin_dir_path( __FILE__ );
$aics_contact_db_version = '1.0';


include_once($aics_contact_path.'/admin/admin.php');

function initiate_install(){
    global $wpdb;
    
    $table_name = $wpdb->prefix.'aics_contact_form';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    name text NULL,
    phone varchar(25) NULL,
    email varchar(255) NULL,
    subject varchar(255) NULL,
    enquiry text NULL,
    created_at datetime DEFAULT '0000-00-00 00:00:00' NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
  add_option( 'aics_contact_db_version', $aics_contact_db_version );
}
register_activation_hook( __FILE__, 'initiate_install' );

// include bootstrap css
function include_bootsrap(){
    $bootstrap = get_option('aics_contact_form_bootstrap');
    if($bootstrap == 1):
    wp_enqueue_style('aics-contact-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', [], '3.3.7');
    endif;
}
add_action('plugins_loaded', 'include_bootsrap');

// dont include css on admin
function disable_aics_style_include(){
    $bootstrap = get_option('aics_contact_form_bootstrap');
    if($bootstrap == 1):
    wp_deregister_style('aics-contact-bootstrap');
    endif;
}
add_action('admin_init', 'disable_aics_style_include');

function generate_contact_form_content(){
    global $aics_contact_path, $post;
    $permalink = get_permalink($post->ID);
    $form_content = file_get_contents($aics_contact_path.'contact-form.html', true);
    $form_start = '<form method="POST" action="'.$permalink.'">';
    if(isset($_GET['success']) && $_GET['success'] == 1):
        $form_start .= '<div class="alert alert-success">Thank you for contacting us. We will get back to you shortly.</div>';
    endif;
    $form_start .= '<input type="hidden" name="process_aics_contact" value="1">';
    $form_start .= '<input type="hidden" name="contact_page" value="'.$post->ID.'">';
    $form_end = '</form>';
    return $form_start.$form_content.$form_end;
}

function contact_shortcode(){
    return generate_contact_form_content();
}
add_shortcode('aics_contact_form', 'contact_shortcode');

function generate_email_form_content($name, $phone, $email,$subject, $enquiry){
    $content .= '<div>Name: '.$name.'</div>';
    $content .= '<div>Phone: '.$phone.'</div>';
    $content .= '<div>Email: '.$email.'</div>';
    $content .= '<div>Subject: '.$subject.'</div>';
    $content .= '<div><br></div>';
    $content .= '<div>Subject: '.$subject.'</div>';
    $content .= '<div>Enquiry: </div>';
    $content .= '<div>'.$enquiry.'</div>';
    return $content;
}

function process_contact_form(){
    if(isset($_POST['process_aics_contact']) && $_POST['process_aics_contact'] == 1):
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_phone = sanitize_text_field($_POST['contact_phone']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_subject = sanitize_text_field($_POST['contact_subject']);
        $contact_enquiry = sanitize_text_field($_POST['contact_enquiry']);
        
        $email_content = generate_email_form_content($contact_name,$contact_phone,$contact_email,$contact_subject,$contact_enquiry);
        $admin_email = get_option('admin_email');
        //$admin_name = get_option('blogname');
        record_contact_db();
        wp_mail($admin_email, $contact_subject, $email_content);
        $page_id = $_POST['contact_page'];
        $page_url = get_permalink($page_id);
        wp_redirect($page_url.'?success=1');
    endif;
}
add_action('init', 'process_contact_form');

function record_contact_db(){
    global $wpdb;
    $name = sanitize_text_field($_POST['contact_name']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $enquiry = sanitize_text_field($_POST['contact_enquiry']);
    $created_at = date('YmdHis');
    $params = compact('name', 'phone', 'email', 'subject', 'enquiry','created_at');
    $wpdb->insert($wpdb->prefix.'aics_contact_form', $params);
}

function set_aics_mail_content_type( $content_type ) {
	return 'text/html';
}
add_filter( 'wp_mail_content_type', 'set_aics_mail_content_type' );
