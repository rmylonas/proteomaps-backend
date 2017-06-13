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


$app->post('/peptides/{nr_motifs}', function ($request, $response, $nr_motifs){
	require 'peptides.php';
    $dataset = $request->getParsedBody();
    $data_info = upload_fasta($dataset);
	$result_id = compute_clusters($data_info, $nr_motifs['nr_motifs']);
	echo($result_id);
});


$app->get('/results/{result_id}', function ($request,$response,$result_id) {
	require 'results.php';
	$results = get_results($result_id['result_id']);
	return $response->withJson($results);
});

$app->run();

?>