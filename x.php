<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1);
header ("Content-Type: text/html; charset=UTF-8");


function mb_html_entity_decode($string)
{
    if (extension_loaded('mbstring') === true)
    {
    	mb_language('Neutral');
    	mb_internal_encoding('UTF-32');
    	mb_detect_order(array('UTF-8', 'UTF-16', 'UTF-32', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));

    	return mb_convert_encoding($string, 'UTF-8', 'HTML-ENTITIES');
    }

    return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
}
function mb_ord($string)
{
    if (extension_loaded('mbstring') === true)
    {
    	mb_language('Neutral');
    	mb_internal_encoding('UTF-32');
    	mb_detect_order(array('UTF-8', 'UTF-16', 'UTF-32', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));

    	$result = unpack('N', mb_convert_encoding($string, 'UCS-4BE', 'UTF-8'));

    	if (is_array($result) === true)
    	{
    		return $result[1];
    	}
    }

    return ord($string);
}

function mb_chr($string)
{
    return mb_html_entity_decode('&#' . intval($string) . ';');
}

$font_data = '￰';

//$font_data = json_decode($font_data, true);//preg_replace('~if \(_typeface_js && _typeface_js\.loadFace\) _typeface_js\.loadFace\((.*)\);~i', "$1", $font_data));
//$font_data = JsonHandler::decode($font_data);
//echo json_last_error();
/*var_dump(sprintf("%d",$font_data));
var_dump(sprintf("%c",sprintf("%d",$font_data)));*/

var_dump(mb_ord($font_data)); 
var_dump(mb_chr(mb_ord($font_data)));

