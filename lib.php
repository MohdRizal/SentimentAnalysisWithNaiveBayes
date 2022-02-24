<?php

function cleaning($data) {
    //ganti repeated dot dengan space
    $result = preg_replace("/\.(?=\.)|\G(?!^)\./", " ", $data);
    //hapus hashtag dan mention
    $result = preg_replace("/(^|[^\w])[@#]([\w\_\.]+)/", "", $result);
    //hanya terima huruf => kalo cuma ini, hanya hapus  @ tanpa hapus username yg di mention
    $result = preg_replace("/[^a-zA-Z\s]+/", "", $result);
    
    $result = preg_replace("/[ ]{2,}+/", " ", $result);

    return $result;
}

function case_folding($data) {
    $trimmed = trim($data);
    $case_folded = strtolower($trimmed);

    return $case_folded;
}

function tokenizing($data) {
    $tokenize = [];
    $exp2 = explode(" ", $data);
    foreach($exp2 as $token)
    {
        //insert data after cleaning per row
        if($data != " " || $data != "  "):
            $tokenize[] = $token;
        endif;
    }

    return $tokenize;
}

function normalisasi($tokenize) {
    global $con;

    //load kamus
    $kamus = [];
    $sql_kamus = "SELECT * FROM kamus_normalisasi";
    $query_kamus = mysqli_query($con, $sql_kamus);

    $index = 0;
    while($data = mysqli_fetch_array($query_kamus, MYSQLI_ASSOC)){
        $kamus[$index]['index'] = $index;
        $kamus[$index]['nama'] = $data['nama'];
        $kamus[$index]['normal'] = $data['normal'];
        $index++;
    }

    $token = [];
    foreach($tokenize as $d) {
        $val = array_search($d, array_column($kamus, 'nama'));
        if($val !== FALSE)
        {
             $token[] = $kamus[$val]['normal'];
             $ket = 1;
        }else{
             $token[] = $d;
             $ket = 0;
        }
    }

    return $token;
}

function filtering($data) {
    require_once 'vendor/autoload.php';
    $stopwordFactory = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
    $stopword = $stopwordFactory->createStopWordRemover();
    $token = [];
    foreach($data as $d) {
        $output = $stopword->remove($d);
        if(!empty($output))
        {   
            $token[] = $d;
        }
    }

    return $token;
}

function stemming($data) {
    require_once 'vendor/autoload.php';

    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    $stemmer = $stemmerFactory->createStemmer();
    $token = [];

    foreach($data as $d) {
        $output = $stemmer->stem($d);

        $token[] = $output;
    }

    return $token;
}