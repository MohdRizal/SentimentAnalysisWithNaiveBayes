<?php
$con = mysqli_connect("localhost", "root", "root", "sentimen_prakerja");

//kosongkan table cleaning
$sql0 = "TRUNCATE TABLE cleaning";
mysqli_query($con, $sql0);

//select raw data
$sql = "SELECT * FROM raw_data";
$query = mysqli_query($con, $sql);

//process cleaning
while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
    //ganti repeated dot dengan space
    $result = preg_replace("/\.(?=\.)|\G(?!^)\./", " ", $data['data']);
    //hapus hashtag dan mention
    $result = preg_replace("/(^|[^\w])[@#]([\w\_\.]+)/", "", $result);
    //hanya terima huruf => kalo cuma ini, hanya hapus  @ tanpa hapus username yg di mention
    $result = preg_replace("/[^a-zA-Z\s]+/", "", $result);
    
    $result = preg_replace("/[ ]{2,}+/", " ", $result);

    //insert data after cleaning per row
    $sql2 = "INSERT INTO cleaning (data) VALUES ('".$result."')";
    $query2 = mysqli_query($con, $sql2);
    var_dump($query2);
}

print_r($data);