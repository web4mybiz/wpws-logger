<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


class WordpressWhiteSecurityLogger_Admin{
    function __construct(){
        add_action( 'admin_menu', array( $this, 'AddPluginPage' ) );
        add_action( 'admin_init', array( $this, 'WWSL_RegisterSettings' ) );
    }

    public $options;

    function AddPluginPage(){
        add_options_page( 'Wordpress White Security Logger', 'Wordpress White Security Logger', 'manage_options', 'wwsl_settings',array($this,'WWSL_SettingsPage'));
    }

    function WWSL_RegisterSettings(){
        register_setting('wwsl_option_group', 'wwsl_option_name');
        add_settings_section('wwsl_section', __( '', 'wp-white-security' ), array($this,'WWSL_Callback'), 'wwsl_settings');
        add_settings_field('wwsl_logfile_path', __( 'Log File Path ', 'wp-white-security' ), array($this,'WWSL_RenderForm'), 'wwsl_settings','wwsl_section',array('label_for' => 'wwsl_logfile_path'));

    }

    function WWSL_Callback(){

    }

    function WWSL_RenderForm() {
        $options = get_option('wwsl_option_name');
    
        // Sanitize the option value before using it in the output
        $logfile_path = isset($options['wwsl_logfile_path']) ? esc_attr($options['wwsl_logfile_path']) : '';
    
        // Use a more descriptive option name
        $home_path = esc_url(get_home_path());
    
        // Output the form field
        echo esc_html($home_path) . ' <input type="text" name="wwsl_option_name[wwsl_logfile_path]" value="' . esc_attr($logfile_path) . '" maxlength="100" size="50" placeholder="custom-folder/logfiles">';
    }

     function WWSL_SettingsPage(){?>
        <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "daac_settings"
        settings_fields( 'wwsl_option_group' );
        // output setting sections and their fields
        // (sections are registered for "daac_settings", each field is registered to a specific section)
        do_settings_sections( 'wwsl_settings' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
        </form>
        </div>
  <?php
     }

}
