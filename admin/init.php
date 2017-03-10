<?php
defined('ABSPATH') or die("Cannot access pages directly.");

/*
 * Some custom overrides if you want
 */

//Label in menu
define('ADMIN_OPTIONS_MENU_LABEL', 'Theme Options');
//Meta title of options page
define('ADMIN_OPTIONS_TITLE_LABEL', 'Customize Your Theme');
//Options page slug
define('ADMIN_OPTIONS_PAGE_SLUG', 'theme-options');
//Option name that is saved in database (do not change this in a production site or you will lose settings)
define('ADMIN_OPTIONS_OPTION_NAME', 'theme_options');
//You can disable the theme options part and just use metaboxes.
define('ADMIN_OPTIONS_DISABLE', false);

include(__DIR__ . "/admin.class.php");
include(__DIR__ . "/meta.class.php");
include(__DIR__ . "/fields.php"); //<<<==define your own fields in this file
include(__DIR__ . "/metaboxes.php"); //<<<==define your own fields in this file







