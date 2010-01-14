<?php
/*
Plugin Name: Vooddo
Description: Mark your videos with interactive hyperlinks using the powerful Vooddo system.
Version: 1.5.1
Author: VodDnet
Author URI: http://www.voddnet.com

 
Vooddo Video Plugin for Wordpress Copyright 2009  Voddnet  (email : support@voddnet.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
 any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


require_once("lib/config.php");
require_once("lib/VooddoVideoDTO.php");
require_once("lib/VooddoPlayer.php");
require_once("lib/VooddoMetaBox.php");
require_once("lib/VooddoAdminPage.php");


// Load plugin translations
load_plugin_textdomain( VOODDO__PLUGIN_LANG_DOMAIN, "wp-content/plugins/" .VOODDO__PLUGIN_LANG_DIR, VOODDO__PLUGIN_LANG_DIR );

// Initialize the options values
add_action("init", "vooddo_install");


// Include the CSS and Javascript
add_action('wp_head', 'vooddo_include');
add_action('admin_head', 'vooddo_include');

// Add requested Vooddo player in the posts
add_filter("the_content", "vooddo_addPlayers");

// Add the Vooddo player metabox
add_action('admin_menu', 'vooddo_addMetaboxPlayer');


if(is_admin())
{
  // Hook for adding admin menus
  add_action('admin_menu', 'vooddo_addAdminMenu');
}



/**
  Set up the options with default values.
*/
function vooddo_install()
{
  add_option(VOODDO__OPTION_NAME__PLAYER_URL, VOODDO_PLAYER__DEFAULT_URL);
  add_option(VOODDO__OPTION_NAME__PLAYER_WIDTH, VOODDO_PLAYER__DEFAULT_WIDTH);
  add_option(VOODDO__OPTION_NAME__PLAYER_HEIGHT, VOODDO_PLAYER__DEFAULT_HEIGHT);
  add_option(VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR, VOODDO_PLAYER__DEFAULT_BACKGROUND_COLOR);
}



/**
  Include the CSS and Javascript.
*/
function vooddo_include()
{
  echo '<link rel="stylesheet" href="' .VOODDO__PLUGIN_REMOTE_DIR. '/css/vooddo.css" type="text/css" />';
  echo '<link rel="stylesheet" href="' .VOODDO__PLUGIN_REMOTE_DIR. '/css/colorPicker.css" type="text/css" />';
  echo '<script type="text/javascript" src="' . VOODDO__PLUGIN_REMOTE_DIR . '/js/utils.js" ></script>';
  echo '<script type="text/javascript" src="' . VOODDO__PLUGIN_REMOTE_DIR . '/js/colorPickerLib.js" ></script>';
}


/**
  Add the specified players in posts.
*/
function vooddo_addPlayers($content)
{
  // Read descriptor from custom fields
  global $post;
  $vooddoStrings = get_post_meta($post->ID, VOODDO__CUSTOM_FIELD__METAKEY, false);

  // debug
  //$content .= var_export($descriptors, true);
  
  // Generate the players HTML code
  if(!empty($vooddoStrings))
  {
    foreach($vooddoStrings as $vooddoString)
    {
      $vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($vooddoString);

      $player = new VooddoPlayer($vooddoVideo, get_option("vooddo_player_url"));

      // Draw player
      $content .= '<div class="vooddo-player">' .$player->toHtml(). '</div>';
      
      // Draw player exportable code
      $content .= '<p id="vooddo_exportable">
          [ ' .__("This video in your website", VOODDO__PLUGIN_LANG_DOMAIN). '] 
            <input type="text" value="' .$player->toExportableHtml(). '" readonly="true" size="30" onclick="select()" />
          </p>';
    }
  }
  return $content;
}


/**
  Add Vooddo metabox
*/
function vooddo_addMetaboxPlayer()
{
  if( function_exists( 'add_meta_box' ))
  {
    $metaboxPlayer = new VooddoMetabox();
  }
}


/**
  Add Vooddo administration menu
*/
function vooddo_addAdminMenu()
{
  // Add a new submenu under Options:
  add_options_page(VOODDO__PLUGIN_NAME, VOODDO__PLUGIN_NAME, 'administrator', 'vooddo-options', 'vooddo_createAdminPage');
}


/**
  Draw Vooddo administration page
*/
function vooddo_createAdminPage()
{
  $adminPage = new VooddoAdminPage();
  echo $adminPage->toHtml();
}


?>