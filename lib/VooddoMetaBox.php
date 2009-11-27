<?php


require_once("config.php");
require_once("functions.php");
require_once("VooddoVideoDTO.php");


class VooddoMetaBox
{
  private $pluginNoncename;
  
  
  function __construct()
  {
    $this->pluginNoncename = preg_replace("/[^0-9a-z]/i", "-", VOODDO__PLUGIN_NAME) . "-noncename";

    add_action('save_post', array(&$this, 'saveData'));
    add_meta_box('vooddo', 'Lecteur Vooddo', array(&$this,'formEditor'), 'post', 'normal', 'high');
  }
  
  function formEditor()
  {
    global $post;
    
    // Compose the editor
    $html = '<div id="vooddo-metabox">
        <input type="hidden" name="' .$this->pluginNoncename. '" id="' .$this->pluginNoncename. '" value="' . 
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />
        <input type="hidden" name="editor_post_id" value="' .$post->ID. '" />
        <label for="myplugin_new_field">' .__("Descriptor URL", VOODDO__PLUGIN_LANG_DOMAIN). ' : </label>
        <input type="text" class="regular-text code" name="' .VOODDO__METABOX_FIELD_NAME__DESCRIPTOR_URL. '" value="http://" size="45" />
        <input type="submit" class="button-primary" name="' .VOODDO__METABOX_BUTTON_NAME__ADD. '" value="' .__("Add", VOODDO__PLUGIN_LANG_DOMAIN). '" />';
    
     
   
    $descriptors = $this->getDescriptors($post->ID);
    for($i = 0; $i < count($descriptors); ++$i)
    {
      $vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($descriptors[ $i ]);
      
      $backgroundColorPickerName = VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR . '_color_picker_' .$i;
      
      $html .= '<table cellpadding="0px" cellspacing="0px">
        <tr>
          <td><strong>' .$vooddoVideo->urlDescriptor. '</strong></td>
          <td rowspan="2" style="text-align: right">
            <input type="submit" class="button-primary" name="' .VOODDO__METABOX_BUTTON_NAME__UPDATE. '_' .$i. '" value="' .__("Update", VOODDO__PLUGIN_LANG_DOMAIN). '" size="40" />
            <input type="submit" class="button" name="' .VOODDO__METABOX_BUTTON_NAME__DELETE. '_' .$i. '" value="X" />
          </td>
        </tr>
        <tr>
          <td>

            ' .__("Width", VOODDO__PLUGIN_LANG_DOMAIN). ' : 
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__WIDTH. '_' .$i. '" value="' .$vooddoVideo->width. '" size="4" maxlength="4" onclick="this.select();" />
            ' .__("Height", VOODDO__PLUGIN_LANG_DOMAIN). ' : 
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__HEIGHT. '_' .$i. '" value="' .$vooddoVideo->height. '" size="4"     maxlength="4" onclick="this.select();" />
            ' .__("Background color", VOODDO__PLUGIN_LANG_DOMAIN). ' : 
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR. '_' .$i. '" value="' .$vooddoVideo->backgroundColor. '" size="8" maxlength="7" id="colorPicker:' .$backgroundColorPickerName. '" />
            <div style="background-color: ' .$vooddoVideo->backgroundColor. '" class="colorPickerElement" id="' .$backgroundColorPickerName. '">
              <input value="" id="colorPicker:' .$backgroundColorPickerName. '" name="' .$backgroundColorPickerName. '" type="hidden">
            </div>
            <script type="text/javascript">colorPickerLib.attachColorPickerBehavior();</script>

          </td>
        </tr>
      </table>';
    }
    
    $html .= '</div>';
    
    echo $html;
  }
  
  function saveData( $post_id )
  {
    // Ignore the first useless saveData call (why the hell this first call ?) with unrelated post_id
    if( $post_id != $_POST["editor_post_id"] )  return $postID;

    // verify
    if ( !wp_verify_nonce( $_POST[$this->pluginNoncename], plugin_basename(__FILE__) ) )   return $post_id;

    // Security prevention
    if ( !current_user_can('edit_post', $postID) )  return $postID;

    // Ignore save_post action for revisions and autosave
    if ( wp_is_post_revision($postID) || wp_is_post_autosave($postID) ) return $postID;



    // Add new descriptor
    if(isset($_POST[VOODDO__METABOX_BUTTON_NAME__ADD]))
    {
      $vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($_POST[VOODDO__METABOX_FIELD_NAME__DESCRIPTOR_URL]);
      
      $this->addNewDescriptor($post_id, $vooddoVideo);
    }
    else
    {
      // Check if any descriptor to delete or update
      foreach(array_keys($_POST) as $key)
      {
        $descriptors = $this->getDescriptors($post_id);

        // Check for update
        if(preg_match("/^" .VOODDO__METABOX_BUTTON_NAME__UPDATE. "_/", $key))
        {
           // Update descriptor
           $index = str_replace(VOODDO__METABOX_BUTTON_NAME__UPDATE."_", "", $key);
           $vooddoString = $descriptors[$index];
           $vooddoVideo = new VooddoVideoDTO();
           $vooddoVideo->initializeFromVooddoString($vooddoString);

           $vooddoVideo->width = $_POST[VOODDO__METABOX_FIELD_NAME__WIDTH. "_" .$index];
           $vooddoVideo->height = $_POST[VOODDO__METABOX_FIELD_NAME__HEIGHT. "_" .$index];
           $vooddoVideo->backgroundColor = $_POST[VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR. "_" .$index];

           update_post_meta($post_id, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoVideo->toVooddoString(), $vooddoString);
           break;
        }


        // Check for delete
        if(preg_match("/^" .VOODDO__METABOX_BUTTON_NAME__DELETE. "_/", $key))
        {
          // Delete descriptor
          $index = str_replace(VOODDO__METABOX_BUTTON_NAME__DELETE."_", "", $key);
          $vooddoString = $descriptors[$index];
          
          delete_post_meta($post_id, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoString);
          break;
        }
      }
    }
  }

  
  // PRIVATE METHODS

  private function getDescriptors($postId)
  {
    $descriptors = get_post_meta($postId, VOODDO__CUSTOM_FIELD__METAKEY, false);
    if(empty($descriptors))
    {
      $descriptors = array();
    }
    return $descriptors;
  }
  
  
  private function addNewDescriptor($postId, &$vooddoVideo)
  {
    $vooddoVideoString = $vooddoVideo->toVooddoString();
    
    // prevent form inserting twice and check HTTP content type
    if(!in_array($vooddoVideoString, $this->getDescriptors($postId)) &&
          getHttpContenttype($vooddoVideo->urlDescriptor, $timeout) == "application/xml")
    {
      //echo "<br>Add " .$urlDescriptor;
      add_post_meta($postId, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoVideoString, false);
    }
  }

};

?>