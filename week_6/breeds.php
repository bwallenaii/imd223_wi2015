<?php

require_once("database.php");

$speciesId = empty($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ? 0:(int) $_REQUEST['id'];

$breedQuery = $pdo->query("SELECT * FROM `breeds` WHERE `species_id` = $speciesId");

$returnData = new StdClass();

$returnData->success = true;
$returnData->breeds = array();

foreach($breedQuery as $breed)
{
    $returnData->breeds[] = (Object) $breed;
}
header('Content-Type: application/json');
echo json_encode($returnData);