<?php


/************************ LICENCE ***************************
*     This file is part of <ViKM Vital-IT Knowledge Management web application>
*     Copyright (C) <2016> SIB Swiss Institute of Bioinformatics
*
*     This program is free software: you can redistribute it and/or modify
*     it under the terms of the GNU Affero General Public License as
*     published by the Free Software Foundation, either version 3 of the
*     License, or (at your option) any later version.
*
*     This program is distributed in the hope that it will be useful,
*     but WITHOUT ANY WARRANTY; without even the implied warranty of
*     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*     GNU Affero General Public License for more details.
*
*     You should have received a copy of the GNU Affero General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>
*
*****************************************************************/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require '../../tools/include.php';


// this should activate the debug mode
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);


$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = ($exception->getCode() !== 501) ? 500 : 501;
        return $c['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($exception->getMessage()));
    };
};


$app->post('/peptides', function ($request, $response, $nr_motifs){
	require 'peptides.php';
    $dataset = $request->getParsedBody();
    $data_info = upload_fasta($dataset, $_POST['data']);
	$result = compute_clusters($data_info, $_POST['nr_of_motifs'], $_POST['core_length'], $_POST['use_trash']);
    return $response->withJson($result);
});


$app->get('/results/{result_id}', function ($request,$response,$result_id) {
	require 'results.php';
	$results = get_results($result_id['result_id']);
	return $response->withJson($results);
});

$app->get('/results/{result_id}/zip', function ($request,$response,$result_id) {
    require 'results_zip.php';
    $zip_file = get_results_zip($result_id['result_id']);
    header('Content-Type: application/zip, application/octet-stream');
    header('Content-Length: ' . filesize($zip_file));
    readfile($zip_file);
});

$app->get('/test-fasta', function ($request,$response,$result_id) {
    $test_file = DATA_PATH."/test.fa";
    header('Content-Type: text/plain');
    header('Content-Length: ' . filesize($test_file));
    readfile($test_file);
});

$app->get('/test-peptides', function ($request,$response,$result_id) {
    $test_file = DATA_PATH."/test_peptides";
    header('Content-Type: text/plain');
    header('Content-Length: ' . filesize($test_file));
    readfile($test_file);
});


$app->run();

?>