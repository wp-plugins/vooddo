<?php


require_once("config.php");
require_once("functions.php");


class VooddoAdminPage
{
  private $title;
  
  function __construct()
  {
    $this->title =__("Administration", VOODDO__PLUGIN_LANG_DOMAIN). ' ' .VOODDO__PLUGIN_NAME;
    
    $this->update();
  }
  
  function toHtml()
  {
    $html = '';
    
    $backgroundColorPickerName = VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR."_color_picker";
    
    $html .= '<div class="wrap">
        <div id="icon-options-general" class="icon32"><br /></div>
          <h2>' .$this->title. '</h2>
          <h3>' .__("Player", VOODDO__PLUGIN_LANG_DOMAIN). '</h3>
          <form method="post">'
            .wp_nonce_field("update-options").
            '<table class="form-table">
            <tbody>
            <tr>
              <th scope="row">' .__("Vooddo player URL", VOODDO__PLUGIN_LANG_DOMAIN). '</th>
              <td>
                <input type="text" name="' .VOODDO__OPTION_NAME__PLAYER_URL. '" value="' .get_option(VOODDO__OPTION_NAME__PLAYER_URL). '" class="regular-text code" onclick="this.select();" />
                <span class="description">' .__("URL of the Vooddo player. Be sure the URL is valid so that the modification occurs.", VOODDO__PLUGIN_LANG_DOMAIN). '</span>
              </td>
            </tr>
            <tr>
              <th scope="row">' .__("Width", VOODDO__PLUGIN_LANG_DOMAIN). '</th>
              <td>
                <input type="text" name="' .VOODDO__OPTION_NAME__PLAYER_WIDTH. '" value="' .get_option(VOODDO__OPTION_NAME__PLAYER_WIDTH). '" maxlength="4" size="10" onclick="this.select();"/>
                <span class="description">' .__("Default player width in pixels.", VOODDO__PLUGIN_LANG_DOMAIN). '</span>
              </td>
            </tr>
            <tr>
        <th scope="row">' .__("Height", VOODDO__PLUGIN_LANG_DOMAIN). '</th>
              <td>
                <input type="text" name="' .VOODDO__OPTION_NAME__PLAYER_HEIGHT. '" value="' .get_option(VOODDO__OPTION_NAME__PLAYER_HEIGHT). '" maxlength="4" size="10" onclick="this.select();"/>
                <span class="description">' .__("Default player height in pixels.", VOODDO__PLUGIN_LANG_DOMAIN). '</span>
              </td>
            </tr>
            <tr>
        <th scope="row">' .__("Background color", VOODDO__PLUGIN_LANG_DOMAIN). '</th>
              <td>
                <input type="text" name="' .VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR. '" value="' .get_option(VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR). '" maxlength="7"  size="7"  id="colorPicker:' .$backgroundColorPickerName. '" />
                <div style="background-color: ' .get_option(VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR). '" class="colorPickerElement" id="' .$backgroundColorPickerName. '">
                  <input value="" id="colorPicker:' .$backgroundColorPickerName. '" name="' .$backgroundColorPickerName. '" type="hidden">
                </div>
                <script type="text/javascript">colorPickerLib.attachColorPickerBehavior();</script>
                <span class="description">' .__("Default player background color, use CSS color syntax.", VOODDO__PLUGIN_LANG_DOMAIN). '</span>
              </td>
            </tr>
            </tbody>
            </table>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="' .VOODDO__OPTION_NAME__PLAYER_URL. ',' 
              .VOODDO__OPTION_NAME__PLAYER_WIDTH. ',' 
              .VOODDO__OPTION_NAME__PLAYER_HEIGHT. ',' 
              .VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR. '" />
            <p class="submit">
              <input name="Submit" class="button-primary" value="' .__("Save changes", VOODDO__PLUGIN_LANG_DOMAIN). '" type="submit">
            </p>
          </form>

        </div>';
    
    return $html;
  }
  
  
  private function update()
  {
    $playerUrl = $_POST[VOODDO__OPTION_NAME__PLAYER_URL];
    $playerWidth = $_POST[VOODDO__OPTION_NAME__PLAYER_WIDTH];
    $playerHeight = $_POST[VOODDO__OPTION_NAME__PLAYER_HEIGHT];
    $playerBackgroundColor = $_POST[VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR];
    
    
    if(isset($playerUrl))
    {
      // Add missing http:
      if(!preg_match("/^https?:\/\//i", $playerUrl))
      {
        $playerUrl = "http://".$playerUrl;
      }
      
      // Check URL validity
      if(!$this->checkPlayerUrl($playerUrl, VOODDO__CONNECTION_TIMEOUT_S__READ_HTTP_HEADER))
      {
        $playerUrl = VOODDO_PLAYER__DEFAULT_URL;
      }
      
      update_option(VOODDO__OPTION_NAME__PLAYER_URL, $playerUrl);
    }
    
    if(isset($playerWidth))
    {
      $playerWidth = cleanLength($playerWidth);
      update_option(VOODDO__OPTION_NAME__PLAYER_WIDTH, $playerWidth);
    }
    
    if(isset($playerHeight))
    {
      $playerHeight = cleanLength($playerHeight);
      update_option(VOODDO__OPTION_NAME__PLAYER_HEIGHT, $playerHeight);
    }
    
    if(isset($playerBackgroundColor))
    {
      $playerBackgroundColor = cleanCssColor($playerBackgroundColor);
      update_option(VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR, $playerBackgroundColor);
    }
  }
  
  private function checkPlayerUrl($playerUrl, $timeout)
  {
    return getHttpContenttype($playerUrl, $timeout) == "application/x-shockwave-flash";
  }

};

?>