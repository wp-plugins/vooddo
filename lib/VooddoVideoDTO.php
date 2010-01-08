<?php

require_once("functions.php");


/**
  Class VooddoVideoDTO : contains the video data
*/
class VooddoVideoDTO
{
  var $idDescriptor;
  var $width;
  var $height;
  var $backgroundColor;
  
  function __construct()
  {
    $this->idDescriptor = "";
    $this->width = get_option(VOODDO__OPTION_NAME__PLAYER_WIDTH);
    $this->height = get_option(VOODDO__OPTION_NAME__PLAYER_HEIGHT);
    $this->backgroundColor = get_option(VOODDO__OPTION_NAME__PLAYER_BACKGROUND_COLOR);
  }
  
  function initializeFromVooddoString($vooddoString)
  {
    list($idDescriptor, $parametersString) = split("[\{\}]", $vooddoString);
    $this->idDescriptor = trim($idDescriptor);
    $parametersString = trim($parametersString);

    if(!empty($parametersString))
    {
      $parametersArray = split(";", $parametersString);
      foreach($parametersArray as $parameterString)
      {
        $this->setParameter($parameterString);
      }
    }
  }
  
  function toVooddoString()
  {
    $vooddoString = $this->idDescriptor;
    $vooddoString .= ' { ';
    $vooddoString .= VOODDO__PARAM_NAME__WIDTH. ' = ' .$this->width. '; ';
    $vooddoString .= VOODDO__PARAM_NAME__HEIGHT. ' = ' .$this->height. '; ';
    $vooddoString .= VOODDO__PARAM_NAME__BACKGROUND_COLOR .' = '. $this->backgroundColor .' }';
    
    return $vooddoString;
  }
  
  private function setParameter($parameterString)
  {
    list($name, $value) = split("[:=]", $parameterString);
    $name = trim($name);
    $value = trim($value);
    
    if(!empty($value))
    {
      switch($name)
      {
        case VOODDO__PARAM_NAME__WIDTH:
          $this->width = cleanLength($value);
          break;
        case VOODDO__PARAM_NAME__HEIGHT:
          $this->height = cleanLength($value);
          break;
        case VOODDO__PARAM_NAME__BACKGROUND_COLOR:
          $this->backgroundColor = cleanCssColor($value);
          break;
        default:
          break;
      }
    }
  }
  
};

?>