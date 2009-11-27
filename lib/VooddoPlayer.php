<?php


require_once("config.php");
require_once("functions.php");


/**
  Class VooddoPlayer : generate the HTML to insert the Vooddo player inside a webpage.
*/
class VooddoPlayer
{
  private $name;
  private $urlPlayer;
  private $urlDescriptor;
  
  // Public fields
  var $loop;
  var $width;
  var $height;
  var $backgroundColor;


  /**
    Constructor.
    @param urlDescriptor
    @param urlPlayer
  */
  function __construct(&$vooddoVideo, $urlPlayer = VOODDO_PLAYER__DEFAULT_URL)
  {
    $this->name = basename($urlPlayer, ".swf");
    $this->urlPlayer = $urlPlayer;
    $this->urlDescriptor = $vooddoVideo->urlDescriptor;
    $this->loop = VOODDO_PLAYER__DEFAULT_LOOP;
    $this->width = $vooddoVideo->width;
    $this->height = $vooddoVideo->height;
    $this->backgroundColor = $vooddoVideo->backgroundColor;
  }


  function toHtml()
  {
    $loop = ($this->loop === true? "true" : "false");
    
    $html =  '<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0"
        width="' .$this->width. '" height="' .$this->height. '" align="middle" 
        id="' .$this->name. '" name="' .$this->name. '" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" >
        <param name="menu" value="true" />
        <param name="quality" value="high" />
        <param name="play" value="true" />
        <param name="allowFullScreen" value="true" />
        <param name="devicefont" value="false" />
        <param name="bgcolor" value="' .$this->backgroundColor. '" />
        <param name="allowScriptAccess" value="always" />
        <param name="movie" value="' .$this->urlPlayer. '" />
        <param name="loop" value="' .$loop. '" />
        <param name="scale" value="noscale" />
        <param name="wmode" value="opaque" />
        <param name="salign" value="lt" />
        <param name="flashVars" value="xml=' .$this->urlDescriptor. '&edit=false&autoPlay=false" />
					
        <embed quality="high" pluginspage="http://www.adobe.com/go/getflashplayer_fr" align="middle" play="true" type="application/x-shockwave-flash"
        menu="true" allowFullScreen="true" devicefont="false" bgcolor="' .$this->backgroundColor. '" 
        name="' .$this->name. '" allowScriptAccess="always" width="' .$this->width. '" height="' .$this->height. '" 
        src="' .$this->urlPlayer. '" loop="' .$loop. '" 
        scale="noscale" wmode="opaque" salign="lt" 
        flashvars="xml=' .$this->urlDescriptor. '&edit=false&autoPlay=false" >
        </embed>
        </object>';
    
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