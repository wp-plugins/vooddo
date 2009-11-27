<?php

/****************************************************************************************
 * 
 *    GLOBAL GENERIC CONSTANTS
 * 
 ****************************************************************************************/


// Plugin name
define("VOODDO__PLUGIN_NAME", "Vooddo");
define("VOODDO__PLUGIN_VERSION", "1.0.0");

// Plugin location
define("VOODDO__PLUGIN_DIR", strtolower(VOODDO__PLUGIN_NAME));
define("VOODDO__PLUGIN_REMOTE_DIR", plugins_url(VOODDO__PLUGIN_DIR));


// Plugin lang domain
define("VOODDO__PLUGIN_LANG_DOMAIN", VOODDO__PLUGIN_NAME);
define("VOODDO__PLUGIN_LANG_DIR", VOODDO__PLUGIN_DIR."/lang");


// Editor default URL
define("VOODDO__EDITOR_URL", "http://www.vooddo.com/editor");

// Options
define("VOODDO__OPTION_NAME__PLAYER_URL", "vooddo_player_url");
define("VOODDO__OPTION_NAME__PLAYER_WIDTH", "vooddo_player_width");
define("VOODDO__OPTION_NAME__PLAYER_HEIGHT", "vooddo_player_height");
define("VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR", "vooddo_player_background_color");
define("VOODDO__OPTION_NAME__EDITOR_URL", "vooddo_editor_url");

// Player default parameters values
define("VOODDO_PLAYER__DEFAULT_URL", "http://www.vooddo.com/vooddo.swf");
define("VOODDO_PLAYER__DEFAULT_LOOP", false);
define("VOODDO_PLAYER__DEFAULT_WIDTH", 400);
define("VOODDO_PLAYER__DEFAULT_HEIGHT", 300);
define("VOODDO_PLAYER__DEFAULT_BACKGROUND_COLOR", "FFFFFF");

define("VOODDO_PLAYER__MIN_WIDTH", 100);
define("VOODDO_PLAYER__MIN_HEIGHT", 100);


// Metabox
define("VOODDO__METABOX_FIELD_NAME__DESCRIPTOR_URL", "vooddo_new_descriptor");
define("VOODDO__METABOX_FIELD_NAME__WIDTH", "vooddo_player_width");
define("VOODDO__METABOX_FIELD_NAME__HEIGHT", "vooddo_player_height");
define("VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR", "vooddo_player_background_color");

define("VOODDO__METABOX_BUTTON_NAME__ADD", "vooddo_btn_add");
define("VOODDO__METABOX_BUTTON_NAME__UPDATE", "vooddo_btn_update");
define("VOODDO__METABOX_BUTTON_NAME__DELETE", "vooddo_btn_delete");


// Custom field name
define("VOODDO__CUSTOM_FIELD__METAKEY", "vooddo");

// Vooddo parameter names inside custom field
define("VOODDO__PARAM_NAME__WIDTH", "w");
define("VOODDO__PARAM_NAME__HEIGHT", "h");
define("VOODDO__PARAM_NAME__BACKGROUND_COLOR", "bg");


?>