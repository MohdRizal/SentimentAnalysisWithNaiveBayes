<?php
$con = mysqli_connect("localhost", "root", "root", "sentimen_prakerja");

require_once "ECSHelper.php";

//empty stemming table (reset)
$sql0 = "TRUNCATE TABLE stemming_ecs";
mysqli_query($con, $sql0);

//select data after filtering
$sql = "SELECT * FROM filtering";
$query = mysqli_query($con, $sql);

while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
    $output = ECS($data['data']);
    $filterId = $data['id'];
    $sqlStemming = "INSERT INTO stemming_ecs (data, filter_id) VALUES ('$output', $filterId)"; 
    mysqli_query($con, $sqlStemming);
}