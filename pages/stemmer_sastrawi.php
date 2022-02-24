<?php

require_once 'vendor/autoload.php';

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer = $stemmerFactory->createStemmer();
// $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
// $stemmer  = $stemmerFactory->createStemmer();

$con = mysqli_connect("localhost", "root", "root", "sentimen_prakerja");

//empty stemming table (reset)
$sql0 = "TRUNCATE TABLE stemming_sastrawi";
mysqli_query($con, $sql0);

//select data after filtering
$filtering = "on";

if($filtering == "on"){
    $sql = "SELECT * FROM filtering ORDER BY id";
}else{
    $sql = "SELECT * FROM normalisasi ORDER BY id";
}

$query = mysqli_query($con, $sql);

while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
    echo $data['data']." - ";
    

    //proses remove stopword
    $sentence = $data['data'];
    $output = $stemmer->stem($sentence);
    echo $output."<br>";
    
    $filterId = $data['id'];
    $sqlStemming = "INSERT INTO stemming_sastrawi (data, filter_id) VALUES ('$output', $filterId)"; 
    echo $sqlStemming."<br>";
    mysqli_query($con, $sqlStemming);
}
echo "Data: ".mysqli_num_rows($query);

// stem
// $sentence = $_POST['text'];
// $output = $stemmer->stem($sentence);

// $stemmerFactory = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
// $stemmer = $stemmerFactory->createStopWordRemover();

//proses remove stopword
// $sentence = $_POST['text'];
// $output = $stemmer->remove($sentence);

