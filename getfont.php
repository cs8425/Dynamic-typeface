<?php
/*error_reporting( E_ALL );
ini_set( 'display_errors', 1);*/
//資料庫位置
//$db_server = "localhost";
$db_server = "127.0.0.1";
//資料庫名稱
$db_name = "webfont";
//資料庫管理者帳號
$db_user = "webfont";
//資料庫管理者密碼
$db_passwd = "ReHqqPb42X65KjRx";

function select($info, $table) {
	global $mysqli;
	if (!is_array($info)) { die("select member failed, info must be an array"); }
	$sql = "SELECT * FROM ".$table." WHERE ";
	$sql .= "`".key($info)."`";
	$sql .= " = ";
	$sql .= "'".$mysqli->real_escape_string(current($info))."'";
	$query = $mysqli->query($sql);
	$row = $query->fetch_array();
	return $row;
}
function select2($info, $table) {
	global $mysqli;
	if (!is_array($info)) { die("select member failed, info must be an array"); }
	$sql = "SELECT * FROM `".$table."` WHERE ";
	for ($i=0; $i<count($info); $i++) {
		$sql .= "`".key($info)."` = '".$mysqli->real_escape_string(current($info))."'";
		if ($i < (count($info)-1)) {
			$sql .= " AND ";
		} else {
			$sql .= " ";
		}
		next($info);
	}
	$query = $mysqli->query($sql);
	$row = $query->fetch_array();
	return $row;
}
function update2($info, $data, $table) {
	global $mysqli;
	if (!is_array($info)) { die("insert member failed, info must be an array"); }
	$sql = "UPDATE ".$table." SET ";
	for ($i=0; $i<count($data); $i++) {
		$sql .= "`".key($data)."` = '".$mysqli->real_escape_string(current($data))."'";
		if ($i < (count($data)-1)) {
			$sql .= ", ";
		} else {
			$sql .= " ";
		}
		next($data);
	}

	$sql .= " WHERE ";
	for ($i=0; $i<count($info); $i++) {
		$sql .= "`".key($info)."` = '".$mysqli->real_escape_string(current($info))."'";
		if ($i < (count($info)-1)) {
			$sql .= " AND ";
		} else {
			$sql .= " ";
		}
		next($info);
	}
	$mysqli->query($sql);
	return $mysqli->insert_id;
}
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

//對資料庫連線
$mysqli = new mysqli($db_server, $db_user, $db_passwd, $db_name);

//資料庫連線採UTF8
$mysqli->set_charset("utf8");

$datalist = array( 'c', 'id');
$data = array();
foreach ($_GET as $key => $value){
	if(in_array($key, $datalist)){
		$data[$key] = $mysqli->real_escape_string($value);
	}
}
foreach ($_POST as $key => $value){
	if(in_array($key, $datalist)){
		$data[$key] = $mysqli->real_escape_string($value);
	}
}
header('Content-Type: application/javascript; charset=utf-8');
if((isset($data['c']))&&(isset($data['id']))){
	$font = select2(array('font_id' => $data['id']),'font_data');
	foreach ($font as $key => $value){
		if(is_numeric($key)){
			unset($font[$key]);
		}
	}
	$font['boundingBox'] = array(
		'yMin' => $font['boundingBox_yMin'],
		'xMin' => $font['boundingBox_xMin'],
		'yMax' => $font['boundingBox_yMax'],
		'xMax' => $font['boundingBox_xMax']
		);
	unset($font['font_id']);
	unset($font['boundingBox_yMin']);
	unset($font['boundingBox_xMin']);
	unset($font['boundingBox_yMax']);
	unset($font['boundingBox_xMax']);

	$c_len = mb_strlen($data['c'], 'UTF-8');
	for($i=0; $i < $c_len; $i++){
		$c = mb_substr($data['c'], $i, 1, 'UTF-8');
		$font['glyphs'][$c] = select2(array('char' => mb_ord($c), 'font_id' => $data['id']),'glyphs');
		foreach ($font['glyphs'][$c] as $key => $value){
			if(is_numeric($key)){
				unset($font['glyphs'][$c][$key]);
			}
		}
		unset($font['glyphs'][$c]['font_id']);
		unset($font['glyphs'][$c]['char']);
	}
	//var_dump($font['glyphs']);
	echo 'if (_typeface_js && _typeface_js.loadFace) _typeface_js.loadFace(';
	echo json_encode($font);
	echo ');';
}else{
	//echo 'nothing select\n';
}


