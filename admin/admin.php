<?php

function aics_contact_admin_page(){
    add_menu_page('Contact DB', 'Contact DB', 'manage_options', 'aics-contact-db', 'ai_page_contact', 'dashicons-megaphone');
    add_submenu_page('aics-contact-db', 'Contact Form Settings', 'Settings', 'manage_options', 'ai-contact-setting', 'aics_form_setting_page');
}
add_action('admin_menu', 'aics_contact_admin_page');

function ai_page_contact(){
    global $aics_contact_path;
    include_once($aics_contact_path.'/admin/templates/main-contact.php');
}

function aics_form_setting_page(){
    global $aics_contact_path;
    include_once($aics_contact_path.'admin/templates/settings.php');
}

function getAllContactEnquiry(){
    global $wpdb;
    return $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'aics_contact_form ORDER BY created_at DESC');
}


function process_setting_aics_contact_form(){
    if(isset($_POST['save_aics_contact_form_setting']) && $_POST['save_aics_contact_form_setting'] == 1):
        $enable_bootstrap = $_POST['enable_bootstrap'];
        
        $previous_bootstrap = get_option('aics_contact_form_bootstrap');
        if(!isset($previous_bootstrap) && empty($previous_bootstrap)):
            add_option('aics_contact_form_bootstrap', $enable_bootstrap);
        else:
            update_option('aics_contact_form_bootstrap', $enable_bootstrap);
        endif;
    endif;
    
    wp_redirect(admin_url('admin.php?page=ai-contact-setting&success=1'));
}
add_action('admin_post_process_save_setting_form_contact', 'process_setting_aics_contact_form');