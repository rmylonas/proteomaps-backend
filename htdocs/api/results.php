<?php

/**
 * get_results
 *
 * give back a json containing the results generated by the MixMHCp script
 *
 * @param string $result_id
 * @return json
 * @author Roman
 */

function get_results($result_id){
    // the data directory
    $result_dir = DATA_PATH."/".$result_id."/results/";

    if(! file_exists($result_dir)) throw new Exception("There are no results available for Result ID <strong>".$result_id."</strong>", 501);

    // parse motifs nr from KLD.txt file
    $motifs = parse_KLD_motifs($result_dir."/KLD/KLD.txt");

    // construct results
    $motif_res = array();

    foreach($motifs as $motif){
       $img_files = get_img_filenames($result_dir."/logos/LoLa_".$motif."*");
       $nr_peps = get_nr_peps($img_files);
       $pmw_values = get_pmw_values($result_dir."/Multiple_PWMs/PWM_".$motif."*");

       if(count($img_files) != count($pmw_values)) throw new Exception("Number of Logos and PWMs differ", 501);

       $res_func = function($id, $img, $pmw, $nr_pep){
           return array('id' => (int)$id+1, 'logo_img' => $img, 'PMW' => (float)$pmw, 'nr_peptides' => (int)$nr_pep);
       };

        $motif_res[$motif] = array_map(
           $res_func,
           range(0, count($img_files)-1),
           $img_files,
           $pmw_values,
           $nr_peps
       );
    }

    // parse id of best cluster
    $best_cluster = get_best_nlc($result_dir.'KLD/best_ncl.txt');

    return array('result_id' => $result_id, 'motifs' => $motif_res, 'best_cluster' => $best_cluster);
}



/**
 * get_best_nlc
 *
 * parse best cluster nr from KLD/best_ncl.txt
 *
 * @param string path to best_ncl.txt
 * @return integer best cluster nr
 * @author Roman
 */
function get_best_nlc($best_ncl_file){
    $best_ncl = file_get_contents($best_ncl_file);
    preg_match('/.+\:\s*(\d+)/', $best_ncl, $matches);
    return intval($matches[1]);
}

/**
 * get_nr_peps
 *
 * parse number of peptides from logo file name
 *
 * @param string $img_names Logo file name
 * @return array of nr of peptides
 * @author Roman
 */
function get_nr_peps($img_names){
    return array_map(function($name){
        preg_match('/.+\-(\d+)\.png$/', $name, $matches);
        return $matches[1];
    }, $img_names);
}

/**
 * get_pmw_values
 *
 * parse PMW values from PMW files
 *
 * @param string $pattern the file search pattern
 * @return array of PMW values
 * @author Roman
 */
function get_pmw_values($pattern){
    $pmw_files = glob($pattern);

    $pmw_parsing = function($file){
        $filename = basename($file);
        $pmw_name = str_replace(".txt", "", $filename);
        $pmw_content = file_get_contents($file);
        preg_match('/'.$pmw_name.'\s+([\d|\.]+).*/', $pmw_content, $matches);
        return $matches[1];
    };

    return array_map($pmw_parsing, $pmw_files);
}


/**
 * get_img_filenames
 *
 * get all the Logos filenames for a given directory
 *
 * @param string $pattern the file search pattern
 * @return array
 * @author Roman
 */
function get_img_filenames($pattern){
    $img_files = glob($pattern);

    return array_map(function($p){
        return basename($p);
    }, $img_files);
}


/**
 * parse_KLD_motifs
 *
 * get an array of motifs
 *
 * @param KLD file path
 * @return array of motif numbers
 * @author Roman
 */
function parse_KLD_motifs($kld_file){
    $content = file_get_contents($kld_file);
    preg_match_all("/(\\d+)\\s+.*/", $content, $out);
    return $out[1];
}


?>