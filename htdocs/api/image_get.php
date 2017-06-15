<?php
require '../../tools/include.php';

$result_id = (isset($_GET['result_id'])) ? filter_input(INPUT_GET,'result_id',FILTER_SANITIZE_SPECIAL_CHARS) : '';
$filename = (isset($_GET['filename'])) ? filter_input(INPUT_GET,'filename',FILTER_SANITIZE_SPECIAL_CHARS) : '';

$path = DATA_PATH."/".$result_id."/results/logos/".$filename;

$type = 'image/png';
//$path_parts = pathinfo($path);
//$extension= $path_parts['extension'];
//$type = "image/$extension";


header('Content-Type:'.$type);
header('Content-Length: ' . filesize($path));
// echo json_encode($image);
readfile($path);

?>