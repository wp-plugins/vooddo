<?php

/****************************************************************************************
 * 
 *    GLOBAL GENERIC METHODS
 * 
 ****************************************************************************************/


/**
  Check the specified URL and extract the contenttype from the response headers.
  @param url URL to check
  @param timeout HTTP request timeout
  @return the extracted contenttype from the HTTP request, empty string if any problem occured.
*/
function getHttpContenttype($url, $timeout)
{
  $contenttype = "";
    
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_NOBODY, true);
  curl_exec($ch);
    
  if(!curl_errno($ch))
  {
    $contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  }
  curl_close($ch);

  return $contenttype;
}


/**
  Clean the parameter (generally width or height) so as to get value between 10 and 9999.
  @param length for a width or height.
  @return cleaned and valid length between 10 and 9999.
*/
function cleanLength($length)
{
  $expectedLength = 4;
  
  $s = preg_replace("/[^0-9]/", "", $length);
  $s = substr($s, 0, $expectedLength);
  
  if($s < 10)
  {
    $s = 10;
  }
  return $s;
}

/**
  Clean the parameter so as to have a valid CSS color string.
  @param cssRgbValue CSS color hexadecimal RGB string.
  @return cleaned and valid CSS color string including. 
*/
function cleanCssColor($cssRgbValue)
{
  $expectedLength = 6;
  
  $s = strtoupper($cssRgbValue);
  $s = preg_replace("/[^0-9A-F]/", "", $s);
  $s = substr($s, 0, $expectedLength);
  
  while(strlen($s) < $expectedLength)
  {
    $s = "0".$s;
  }
  
  $s = "#".$s;
  return $s;
}

?>