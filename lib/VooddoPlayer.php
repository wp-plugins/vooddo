<?php


require_once("config.php");
require_once("functions.php");


/**
  Class VooddoPlayer : generate the HTML to insert the Vooddo player inside a webpage.
*/
class VooddoPlayer
{
  private $idDescriptor;
  
  // Public fields
  var $loop;
  var $width;
  var $height;
  var $backgroundColor;


  /**
    Constructor.
    @param idDescriptor
  */
  function __construct(&$vooddoVideo)
  {
    $this->idDescriptor = $vooddoVideo->idDescriptor;
    $this->loop = VOODDO_PLAYER__DEFAULT_LOOP;
    $this->width = $vooddoVideo->width;
    $this->height = $vooddoVideo->height;
    $this->backgroundColor = $vooddoVideo->backgroundColor;
  }


  function toHtml()
  {
    $html =  '<script src="' .VOODDO__EDITOR_PLAYER_JS. '"></script>
			<script>
				var attributes = new Array();
				attributes["play"] = "' .($this->loop? "true": "false"). '";
				attributes["wmode"] = "opaque";
				attributes["bgcolor"] = "' .$this->backgroundColor. '";
				document.write(generateVooddoPlayer(' .$this->width. ',	' .$this->height. ', ' .$this->idDescriptor. ',
				"", null, attributes));
			</script>';
		
    return $html;
  }
  
  function toExportableHtml()
  {
    $exportableHtml = $this->toHtml();
    
    //Replace CR LF and tabs by spaces and get rid of useless spaces
    $exportableHtml = preg_replace("/[\\n\\r\\t ]+/", " ", $exportableHtml);
    
    // Convert the special chars
    $exportableHtml = htmlspecialchars($exportableHtml, ENT_QUOTES);
    
    return $exportableHtml;
  }
  
};

?>