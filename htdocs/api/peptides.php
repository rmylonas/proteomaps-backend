<?php


/**
 * Call the MixMHCp script
 *
 * @param $upload_info array result_id and filename
 * @return array result_id,
 * @author Roman
 */
function compute_clusters($upload_info, $nr_motifs){

    $result_id = $upload_info["result_id"];
    $filename = $upload_info["filename"];

    // check if nr_clusters > 0
    if(! intval($nr_motifs) > 0) throw new Exception("Invalid number of motifs", 501);

	// create the results folder
    $result_dir = DATA_PATH."/".$result_id."/results/";
    if(!file_exists($result_dir)) mkdir($result_dir,0755,true);

    // input and log file
    $input_file = DATA_PATH."/".$result_id."/input/".$filename;
    $log_file = $result_dir."php_cmd.log";

    // call MixMHCp
    $cmd = MIXMHCP_PATH." -i ".$input_file." -o ".$result_dir. " -m ".$nr_motifs;
    exec($cmd, $output);
    file_put_contents($log_file, $output);

    // the exit_code is always 0, so we look for the logos.html to see if everything went OK
    $has_error = ! file_exists($result_dir."/logos.html");

    $pipeline_log = file_get_contents($result_dir."/pipeline.log");
    if($has_error) throw new Exception($pipeline_log, 501);

	return array('result_id' => $result_id, 'has_error' => $has_error, 'pipeline_log' => $pipeline_log);
}

/**
 * Create a new directory and move the fasta file there
 *
 * @param $dataset
 * @return array result_id and filename
 * @author Roman
 */
function upload_fasta($dataset){
    if(!isset($_FILES['file'])) throw new Exception("No file received", 501);

    // create a new ResultID
	$result_id = uniqid();

	// create a new result directory
    $input_dir = DATA_PATH."/".$result_id."/input/";
    if(!file_exists($input_dir)) mkdir($input_dir,0755,true);

    // move upload file to the right place
    $filename = $_FILES['file']['name'];
    if(file_exists($input_dir."/".$filename)){
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        if(preg_match("/(.*)\.(\d+)$/",$basename,$regs)){
            $filename = $regs[1].".".(intval($regs[2])+1).".".pathinfo($filename, PATHINFO_EXTENSION);
        }
        else $filename = $basename.".1.".pathinfo($filename, PATHINFO_EXTENSION);
    }
    if(!move_uploaded_file($_FILES['file']['tmp_name'],$input_dir."/".$filename)) throw new Exception("Sorry, upload error !", 501);

    return array('result_id' => $result_id, 'filename' => $filename);
}

?>
