<?php
/*
Plugin Name: Wordpress White Security Logger
Plugin URI: https://github.com/web4mybiz
Description: A plugin to log user log activities.
Author: Wordpress White Security Logger
Version: 1.0
Author URI: https://github.com/web4mybiz
Text Domain: wp-white-security
*/

defined('ABSPATH') or die('You are not authorised to view this page!');

require_once( dirname( __FILE__ ) .'/WWSL_Admin.php' );

class WordpressWhiteSecurityLogger
{
    public $plugin;
    public $options;

    public function __construct()
    {
        $this->plugin=plugin_basename(__FILE__);
        $this->options = get_option( 'wwsl_option_name' );
    }

    public function LoadEvents(){
        add_action('wp_login',array($this,'LoginAction'),10,2);
        add_action('clear_auth_cookie',array($this,'LogoutAction'));
        add_filter("plugin_action_links_$this->plugin", array($this,'SettingsLink' ));
    }


    function SettingsLink($links){
        $settings_link='<a href="options-general.php?page=wwsl_settings">Settings</a>';
        array_push($links,$settings_link);
        return $links;
    }

    // Function call when logged in
    function LoginAction($user_login, $user){
        $this->LogLoginEvent($user_login,$user);
    }

    // Function call when logged out
    function LogoutAction(){
        $user=wp_get_current_user();
        $user_login=$user->user_login;
        $this->LogLogoutEvent($user_login,$user);
    }


    // Create log for logged in user
    function LogLoginEvent($user_login,$user){
        //Something to write to txt log
        $log  = "User Details: Username - ".$user_login." | Role - ".implode($user->roles).PHP_EOL.
        "Logged IP : ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
        "Logged date & time: ".date("F j, Y, g:i a").PHP_EOL.
        "-------------------------".PHP_EOL;
        //Save string to log, use FILE_APPEND to append.
        $this->SetupLogFile($log);        
    }

    // Create log for logged out user
    function LogLogoutEvent($user_login,$user){
        //Something to write to txt log
        $log  = "User Details: Username - ".$user_login." | Role - ".implode($user->roles).PHP_EOL.
        "Logged in IP : ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
        "Logged out date & time: ".date("F j, Y, g:i a").PHP_EOL.
        "-------------------------".PHP_EOL;
        //Save string to log, use FILE_APPEND to append.
        $this->SetupLogFile($log);       
    }

    //Setup log file path, create folder if not exists
    function SetupLogFile($log){

        //assign folder path to a variable
        $LogFilePath = $this->options["wwsl_logfile_path"];

        //create folder if not exist
        if (!file_exists($LogFilePath)) {
            mkdir($LogFilePath, 0777, true);
        }

        file_put_contents('./'.$LogFilePath.'/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

    }

//end class
}

if(class_exists('WordpressWhiteSecurityLogger')){
   $WordpressWhiteSecurityLogger = new WordpressWhiteSecurityLogger();
   $WordpressWhiteSecurityLogger->LoadEvents();
}

if(class_exists('WordpressWhiteSecurityLogger_Admin')){
   new WordpressWhiteSecurityLogger_Admin();
}

