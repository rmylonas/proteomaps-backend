<?php
require '../../tools/include.php';

$result_id = (isset($_GET['result_id'])) ? filter_input(INPUT_GET,'result_id',FILTER_SANITIZE_SPECIAL_CHARS) : '';
$filename = (isset($_GET['filename'])) ? filter_input(INPUT_GET,'filename',FILTER_SANITIZE_SPECIAL_CHARS) : '';

// check result_id
if(! preg_match('/^\w{13}$/', $result_id)) throw new Exception("ERROR: invalid ResultId", 501);

$img_path = "";
// check filenames and set the path
if(preg_match('/^LoLa_L[\d|\-|_|Trash]+\.png$/', $filename)){
    $img_path = $result_id."/results/logos/".$filename;
}elseif (preg_match('/^lg_[\d|_]+\.png$/', $filename)){
    $img_path = $result_id."/results/weights/plots/".$filename;
}elseif (preg_match('/empty.png/', $filename)){
    $img_path = $filename;
}else{
    throw new Exception("ERROR: invalid filename", 501);
}

$path = DATA_PATH."/".$img_path;

$type = 'image/png';

header('Content-Type:'.$type);
header('Content-Length: ' . filesize($path));
// echo json_encode($image);
readfile($path);

?>