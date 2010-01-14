<?php


require_once("config.php");
require_once("functions.php");
require_once("VooddoVideoDTO.php");


class VooddoMetaBox
{
  private $pluginNoncename;
  private $lastErrorMessage;
  
  
  function __construct()
  {
    $this->pluginNoncename = preg_replace("/[^0-9a-z]/i", "-", VOODDO__PLUGIN_NAME) . "-noncename";
    $this->lastErrorMessage = get_option(VOODDO__OPTION_NAME__LAST_ERROR_MSG);

    add_action('save_post', array(&$this, 'saveData'));
    add_meta_box('vooddo', 'Lecteur Vooddo', array(&$this,'formEditor'), 'post', 'normal', 'high');
  }
  
  
  function formEditor()
  {
    global $post;
    
    
    // Compose the editor
    $html = "";
    
    if(!empty($this->lastErrorMessage))	// Any error message ?
    {
    	$html .= '<div class="vooddo-error-box">' .$this->lastErrorMessage. '</div>';
    	
    	delete_option( VOODDO__OPTION_NAME__LAST_ERROR_MSG );
    }
    
    $html .= '<div id="vooddo-metabox">
        <input type="hidden" name="' .$this->pluginNoncename. '" id="' .$this->pluginNoncename. '" value="' . 
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />
        <input type="hidden" name="editor_post_id" value="' .$post->ID. '" />
        
        <table>
        <tr>
        	<th scope="row">' .__("URL video", VOODDO__PLUGIN_LANG_DOMAIN). ' : </th>
        	<td>
		        <input type="text" class="regular-text code" name="' .VOODDO__METABOX_FIELD_NAME__NEW_VOODDO_VIDEO_URL. '" 
		        	value="http://" size="60" maxlength="100" />
		        <input type="submit" class="button-primary" name="' .VOODDO__METABOX_BUTTON_NAME__ADD_NEW_VOODDO. '"
		        	value="' .__("Add", VOODDO__PLUGIN_LANG_DOMAIN). '" 
		        	title="' .__("Add new Vooddo into the post using a public video URL", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
		      </td>
		    </tr>
		    <tr>
		    	<td></td>
		      <td class="description">
	        	&gt; '.__("Add new Vooddo into the post using a public video URL", VOODDO__PLUGIN_LANG_DOMAIN). '
	        </td>
        </tr>
        
        <tr><td>&nbsp;</td></tr>
        
        <tr>
	        <th scope="row">' .__("Vooddo ID", VOODDO__PLUGIN_LANG_DOMAIN). ' : </th>
	        <td>
		        <input type="text" class="regular-text code" name="' .VOODDO__METABOX_FIELD_NAME__EXISTING_DESCRIPTOR_ID. '" 
		        	value="" size="20" maxlength="12" />
		        <input type="submit" class="button-primary" name="' .VOODDO__METABOX_BUTTON_NAME__ADD_EXISTING_VOODDO. '" 
		        	value="' .__("Add", VOODDO__PLUGIN_LANG_DOMAIN). '" 
		        	title="' .__("Add exisiting Vooddo into the post by passing its ID", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
        	</td>
        </tr>
        <tr>
        	<td></td>
        	<td class="description">
        		&gt; '.__("Add exisiting Vooddo into the post by passing its ID", VOODDO__PLUGIN_LANG_DOMAIN). '
	        </td>
    		</tr>
    		</table>';
     
   
    $descriptors = $this->getDescriptors($post->ID);
    for($i = 0; $i < count($descriptors); ++$i)
    {
      $vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($descriptors[ $i ]);
      
      $backgroundColorPickerName = VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR . '_color_picker_' .$i;

      $html .= '<table class="vooddo" cellpadding="0px" cellspacing="0px">
        <tr>
          <td>
          	' .__("Vooddo ID", VOODDO__PLUGIN_LANG_DOMAIN) . ' <strong>' . $vooddoVideo->idDescriptor. '</strong>
          </td>
          <td rowspan="2" style="text-align: right">
           	<a href="' .VOODDO__WATCH_URL. '?id='. $vooddoVideo->idDescriptor .'&fmt=video-only" target="vooddo-watch">
	          	<input type="image" class="button" 
	          		src="' .VOODDO__PLUGIN_REMOTE_DIR. '/img/play.png" 
	          		title="' .__("Watch the Vooddo video", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
          
          	<a href="' .VOODDO__EDITOR_URL. '?id='. $vooddoVideo->idDescriptor .'" target="vooddo-editor">
	          	<input type="image" class="button" 
	          		src="' .VOODDO__PLUGIN_REMOTE_DIR. '/img/edit-vooddo.png" 
	          		title="' .__("Modify in Vooddo Editor", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
          	</a>
            <input type="image" class="button" name="' .VOODDO__METABOX_BUTTON_NAME__DELETE. '_' .$i. '" 
            	src="' .VOODDO__PLUGIN_REMOTE_DIR. '/img/delete.png" 
            	title="' .__("Remove from post", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
          </td>
        </tr>';
      
      
      // hide the parameters input fields  
      /*
      $html .= '<tr>
          <td>

            <label for="myplugin_new_field">' .__("Width", VOODDO__PLUGIN_LANG_DOMAIN). ' : </label>
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__WIDTH. '_' .$i. '" value="' .$vooddoVideo->width. '" size="4" maxlength="4" onclick="this.select();" />
            <label for="myplugin_new_field">' .__("Height", VOODDO__PLUGIN_LANG_DOMAIN). ' : </label>
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__HEIGHT. '_' .$i. '" value="' .$vooddoVideo->height. '" size="4"     maxlength="4" onclick="this.select();" />
            <label for="myplugin_new_field">' .__("Background color", VOODDO__PLUGIN_LANG_DOMAIN). ' : </label>
            <input type="text" name="' .VOODDO__METABOX_FIELD_NAME__BACKGROUND_COLOR. '_' .$i. '" value="' .$vooddoVideo->backgroundColor. '" size="8" maxlength="7" id="colorPicker:' .$backgroundColorPickerName. '" />
            <div style="background-color: ' .$vooddoVideo->backgroundColor. '" class="colorPickerElement" id="' .$backgroundColorPickerName. '">
              <input value="" id="colorPicker:' .$backgroundColorPickerName. '" name="' .$backgroundColorPickerName. '" type="hidden">
            </div>
            <script type="text/javascript">colorPickerLib.attachColorPickerBehavior();</script>
            
            &nbsp;&nbsp;&gt;
            <input type="submit" class="button-primary" 
            	name="' .VOODDO__METABOX_BUTTON_NAME__UPDATE. '_' .$i. '" 
            	value="' .__("Submit", VOODDO__PLUGIN_LANG_DOMAIN). '" size="40"
            	title="' .__("Submit the new parameters values", VOODDO__PLUGIN_LANG_DOMAIN). '"/>
          </td>
        </tr>';
        */
        
      $html .= '</table>';
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



		// Add new Vooddo using a public video URL
		if(isset($_POST[VOODDO__METABOX_BUTTON_NAME__ADD_NEW_VOODDO]))
    {
    	$urlVideo = $_POST[VOODDO__METABOX_FIELD_NAME__NEW_VOODDO_VIDEO_URL];
    	
    	$this->addNewVooddo($post_id, $urlVideo);
    }
    // Add existing descriptor
    else if(isset($_POST[VOODDO__METABOX_BUTTON_NAME__ADD_EXISTING_VOODDO]))
    {
      $vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($_POST[VOODDO__METABOX_FIELD_NAME__EXISTING_DESCRIPTOR_ID]);
      
      $this->addExistingVooddo($post_id, $vooddoVideo);
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
          preg_match("/_([0-9]+)/", $key, $matches);
          $index = $matches[1];
          $vooddoString = $descriptors[$index];
          
          delete_post_meta($post_id, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoString);
          break;
        }
      }
    }
    
    update_option( VOODDO__OPTION_NAME__LAST_ERROR_MSG, $this->lastErrorMessage );
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


  /**

  */
  private function addNewVooddo($postId, $urlVideo)
  {
  	$postParameters = array("video" => $urlVideo);
  	
  	$result = $this->callVooddoAPI(VOODDO__API_URL__NEW_VIDEO, $postParameters);
  	if($result["code"] != "0")
  	{
  		$this->lastErrorMessage = sprintf(__("Can not put video at URL : %s into a Vooddo video.", VOODDO__PLUGIN_LANG_DOMAIN),
    		 $urlVideo);
  	}
  	else
  	{
  		list($idDescriptor, $xmlDescriptor) = explode("|", $result["content"]);

  		$vooddoVideo = new VooddoVideoDTO();
      $vooddoVideo->initializeFromVooddoString($idDescriptor);
      
      if(!$this->parseDescriptor($xmlDescriptor, $vooddoVideo))
      {
      	$this->lastErrorMessage = sprintf(__("Invalid descriptor format for the new Vooddo ID %s.", VOODDO__PLUGIN_LANG_DOMAIN),
    			$vooddoVideo->idDescriptor);
      }
      else
      {
      	add_post_meta($postId, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoVideo->toVooddoString(), false);
      }
  	}
  }
  
  
  /**
  	@remark parameter vooddoVideo may be modified.
  */
  private function addExistingVooddo($postId, &$vooddoVideo)
  {
    // prevent form inserting twice and check HTTP content type
    if($this->checkIdDescriptorExists($postId, $vooddoVideo->idDescriptor))
    {
    	$this->lastErrorMessage = sprintf(__("You already added this Vooddo (ID : %s) in the post.", VOODDO__PLUGIN_LANG_DOMAIN), 
    		$vooddoVideo->idDescriptor);
    }
    else
    {
    	$xmlDescriptor = $this->downloadDescriptor($vooddoVideo->idDescriptor);
    	
    	if($xmlDescriptor === NULL)
    	{
    		$this->lastErrorMessage = sprintf(__("No descriptor available for the Vooddo ID %s.", VOODDO__PLUGIN_LANG_DOMAIN),
    		 $vooddoVideo->idDescriptor);
    	}
    	else if(!$this->parseDescriptor($xmlDescriptor, $vooddoVideo))
    	{
    		$this->lastErrorMessage = sprintf(__("Invalid descriptor format for the Vooddo ID %s.", VOODDO__PLUGIN_LANG_DOMAIN),
    		 $vooddoVideo->idDescriptor);
    	}
		  else
		  {
			  add_post_meta($postId, VOODDO__CUSTOM_FIELD__METAKEY, $vooddoVideo->toVooddoString(), false);
		  }
    }
  }  
  
  
  private function checkIdDescriptorExists($postId, $idDescriptor)
  {
  	$result = false;
  
  	$descriptors = $this->getDescriptors($postId);
  	foreach($descriptors as $vooddoString)
  	{
  		$vooddoVideo = new VooddoVideoDTO();
  		$vooddoVideo->initializeFromVooddoString($vooddoString);
  		
  		if($vooddoVideo->idDescriptor == $idDescriptor)
  		{
  			$result = true;
  			break;
  		}
  	}
  	return $result;
  }
  
  
  /**
  	Download specified Vooddo descriptor.
  	@return the XML descriptor, NULL otherwise.
  */
  private function downloadDescriptor($idDescriptor)
  {
  	$xmlDescriptor = NULL;
  	
  	$postParameters = array("id" => $idDescriptor);
  	
  	$result = $this->callVooddoAPI(VOODDO__API_URL__GET_DESCRIPTOR, $postParameters);
  	if($result["code"] == "0")
  	{
  		$xmlDescriptor = $result["content"];
  	}
	  
  	return $xmlDescriptor;
  }
  
  
  /**
  	Call Vooddo API.
  	@param urlApi is the API URL to call.
  	@param postParameters is an array that contains the parameters to send in HTTP POST.
  	@return an array with key "code" and "content". 
  		Code value 0 means success in which case content contains the body result.
  		Code value -1 means URL unreachable.
  		Any other code value means error and depends on the API.
  */
  private function callVooddoAPI($urlApi, $postParameters)
  {
  	$result = array("code" => -1);	// -1 stans for URL unreachable
  	
  	// Call the URL
  	$ch = curl_init($urlApi);
	  curl_setopt($ch, CURLOPT_TIMEOUT, VOODDO__CONNECTION_TIMEOUT_S__DOWNLOAD_SMALL_FILE);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	  curl_setopt($ch, CURLOPT_HEADER, false);
	  curl_setopt($ch, CURLOPT_NOBODY, false);
	  
	  if(is_array($postParameters) && count($postParameters) > 0)
	  {
	  	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParameters);
	  }
	  
	  $response = curl_exec($ch);
	  
	  if(!curl_errno($ch))
	  {
	  	$result["code"] = strtok($response, "\n");
	  	$result["content"] = strtok("\n");
	  }
	  return $result;
  }
  
  /**
  	Parse the specified descriptor and apply the extracted values to the 
  	vooddoVideo reference parameter.
  */
  private function parseDescriptor(&$xmlDescriptor, &$vooddoVideo)
  {
  	$success = false;
  
	  // Extract and parse the Vooddo XML 
	  $parser = xml_parser_create();
	  xml_parse_into_struct($parser, $xmlDescriptor, $vals);
	  xml_parser_free($parser);
	  
	  // Look for CONTAINER node
	  $containerNode = NULL;
	  foreach($vals as $node)
	  {
	  	if($node["tag"] == "CONTAINER")
	  	{
	      $containerNode = $node;
	      break;
	  	}
	  }
	  
	  // Set container width and height if available
	  if($containerNode !== NULL)
	  {
	  	$width = $containerNode["attributes"]["WIDTH"];
	  	$height = $containerNode["attributes"]["HEIGHT"];
		
			if(!empty($width))
				$vooddoVideo->width = $width;
	
			if(!empty($height))
				$vooddoVideo->height = $height;

			$success = true;
  	}
  	return $success;
  }
  

};

?>