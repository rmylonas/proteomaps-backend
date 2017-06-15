<?php
// A random string used to crypt user password //
if(!defined('SALT')) define('SALT','O06i44wKq5Kjt.');

// The email address of the ViKM administrator
if(!defined("CONTACT_EMAIL")) define("CONTACT_EMAIL","first.last@email.com");

// The name of the ViKM instance
if(!defined("SITE_TITLE")) define("SITE_TITLE","ViKM APP");

// Path to the data directory. All datasets will be stored in this directory.
// This directory must be writable by apache user.
if(!defined('DATA_PATH')) define('DATA_PATH',__DIR__."/../data");

// Path to the tools directory
if(!defined('INCLUDE_PATH')) define("INCLUDE_PATH",dirname(__FILE__)."/../tools/");

// Whether to server should accept Cross-Origin request or not. Should be set to false in production.
if(!defined('CORS')) define('CORS',true);

// Set the debug state of the application. Might be used to display some debugging messages
if(!defined('DEBUG')) define('DEBUG',false);

if(!defined('GRIP_PATH')) define('GRIP_PATH',"/usr/local/bin/grip");

if(!defined('MIXMHCP_PATH')) define('MIXMHCP_PATH',"../../tools/MixMHCp1.1/MixMHCp");

?>
