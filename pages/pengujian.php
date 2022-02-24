<?php
// tentukan rasio data
$ratio = $_GET['ratio'];
switch($ratio)
{
    case "1":
        $sisa = 600;
        $limit = 1400;
        break;
    case "2":
        $sisa = 400;
        $limit = 1600;
        break;
    default:
        $sisa = 200;
        $limit = 1800;
        break;
}

if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    //empty pengujian table (reset)
    $sql0 = "DELETE FROM pengujian WHERE rasio = '$ratio'";
    mysqli_query($con, $sql0);

    //get total words each class
    $queryTotalKata = "SELECT SUM(tf_positif) as tf_positif, SUM(tf_negatif) as tf_negatif, COUNT(data) as total_data FROM pembobotan WHERE rasio = '$ratio'";
    $totalWord = mysqli_fetch_array(mysqli_query($con, $queryTotalKata), MYSQLI_ASSOC);

    //probabilitas kelas terhadap dokumen
    $queryProbKelasDok = "SELECT COUNT(id) as total_data, (SELECT count(kelas) FROM raw_data WHERE kelas = '1' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab) ) as data_pos, (SELECT count(kelas) FROM raw_data WHERE kelas = '0' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab) ) as data_neg FROM `raw_data`";
    $execKelasDoc = mysqli_fetch_array(mysqli_query($con, $queryProbKelasDok), MYSQLI_ASSOC);
    $probKelasDokPositif = $execKelasDoc['data_pos'] / $execKelasDoc['total_data'];
    $probKelasDokNegatif = $execKelasDoc['data_neg'] / $execKelasDoc['total_data'];

    // $arr['positif'] = $probKelasDokPositif;
    // $arr['negatif'] = $probKelasDokNegatif;

    // print_r($arr);
    // exit;

    //get the id of the rest of the data (testing data) of positive class
    $queryTestDataPos = "SELECT id FROM raw_data ORDER BY length(data) LIMIT $sisa";
    $exec = mysqli_query($con, $queryTestDataPos);

    $true = $false = 0;
    while($data = mysqli_fetch_array($exec, MYSQLI_ASSOC)){
        //id data testing
        $testId = $data['id'];

        //initialize pos and neg array
        $pos = [];
        $neg = [];

        //select 
        $sql = "SELECT stemming_sastrawi.data as data, tokenizing.raw_id as id FROM stemming_sastrawi 
        JOIN filtering ON filtering.id = stemming_sastrawi.filter_id 
        JOIN normalisasi on normalisasi.id = filtering.normalisasi_id 
        JOIN tokenizing on tokenizing.id = normalisasi.token_id 
        WHERE tokenizing.raw_id = $testId";
        $query = mysqli_query($con, $sql);

        while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $token = $data['data'];
            $positif = 0;
            $negatif = 0;

            $probabilitas = mysqli_query($con,"SELECT positif, negatif FROM pelatihan WHERE data = '$token' AND rasio = '$ratio'");
            $numRows = mysqli_num_rows($probabilitas);

            //jika token ada di pelatihan
            if($numRows > 0){
                $hasilProbabilitas = mysqli_fetch_array($probabilitas);
                $positif = $hasilProbabilitas['positif'];
                $negatif = $hasilProbabilitas['negatif'];
            }

            //perhitungan probabilitas
            $pos[] = $prob_positif = ($positif + 1) / ($totalWord['tf_positif'] + $totalWord['total_data']);
            $neg[] = $prob_negatif = ($negatif + 1) / ($totalWord['tf_negatif'] + $totalWord['total_data']);    
        }

        $pos = $probKelasDokPositif * array_product($pos);
        $neg = $probKelasDokNegatif * array_product($neg);

        $result = max($pos, $neg);

        if($result == $pos){
            $result = 1;
        }else{
            $result = 0;
        }

        $insertPengujian = "INSERT INTO pengujian (raw_id, prob_positif, prob_negatif, hasil, rasio) VALUES ($testId, '$pos', '$neg', '$result', '$ratio')";
        mysqli_query($con, $insertPengujian);
    }

    //get the id of the rest of the data (testing data) of negative class
    // $queryTestDataNeg = "SELECT id FROM raw_data WHERE kelas = '0' ORDER BY length(data) DESC LIMIT $limitNegative";
    // $exec2 = mysqli_query($con, $queryTestDataNeg);

    // $true = $false = 0;
    // while($data = mysqli_fetch_array($exec2, MYSQLI_ASSOC)){
    //     //id data testing
    //     $testId = $data['id'];

    //     //initialize pos and neg array
    //     $pos = [];
    //     $neg = [];

    //     //select 
    //     $sql = "SELECT stemming_sastrawi.data as data, tokenizing.raw_id as id FROM stemming_sastrawi 
    //     JOIN filtering ON filtering.id = stemming_sastrawi.filter_id 
    //     JOIN normalisasi on normalisasi.id = filtering.normalisasi_id 
    //     JOIN tokenizing on tokenizing.id = normalisasi.token_id 
    //     WHERE tokenizing.raw_id = $testId";
    //     $query = mysqli_query($con, $sql);

    //     while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
    //         $token = $data['data'];
    //         $positif = 0;
    //         $negatif = 0;

    //         $probabilitas = mysqli_query($con,"SELECT positif, negatif FROM pelatihan WHERE data = '$token'");
    //         $numRows = mysqli_num_rows($probabilitas);

    //         //jika token ada di pelatihan
    //         if($numRows > 0){
    //             $hasilProbabilitas = mysqli_fetch_array($probabilitas);
    //             $positif = $hasilProbabilitas['positif'];
    //             $negatif = $hasilProbabilitas['negatif'];
    //         }

    //         //perhitungan probabilitas
    //         $pos[] = $prob_positif = ($positif + 1) / ($totalWord['tf_positif'] + $totalWord['total_data']);
    //         $neg[] = $prob_negatif = ($negatif + 1) / ($totalWord['tf_negatif'] + $totalWord['total_data']);    
    //     }

    //     $pos = array_product($pos);
    //     $neg = array_product($neg);

    //     $result = max($pos, $neg);

    //     if($result == $pos){
    //         $result = 1;
    //     }else{
    //         $result = 0;
    //     }

    //     $insertPengujian = "INSERT INTO pengujian (raw_id, prob_positif, prob_negatif, hasil, rasio) VALUES ($testId, '$pos', '$neg', '$result', '$ratio')";
    //     mysqli_query($con, $insertPengujian);
    // }

    echo "<script>window.location.href='".$url."?page=pengujian&ratio=$ratio'</script>";
    exit;
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <!-- Main content -->
    <section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Pilih Rasio Pembagian Data Uji - Latih</h3>
        </div>
        <form action="">
        <input type="hidden" name="page" value="pengujian">
        <div class="box-body">
            <div class="form-group">
                <select name="ratio" class="form-control">
                    <option value="1" <?php if(isset($_GET['ratio']) && $_GET['ratio'] == 1) echo 'selected'; ?>>70% - 30%</option>
                    <option value="2" <?php if(isset($_GET['ratio']) && $_GET['ratio'] == 2) echo 'selected'; ?>>80% - 20%</option>
                    <option value="3" <?php if(isset($_GET['ratio']) && $_GET['ratio'] == 3) echo 'selected'; ?>>90% - 10%</option>
                </select>
            </div>           
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Pilih</button>
        </div>
        </form>
        <!-- /.box-footer-->
    </div>
      <!-- /.box -->

      <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Pengujian</h3>

            <div class="box-tools pull-right">
                <a href="<?= $url ?>?page=pengujian&task=proses&ratio=<?= isset($_GET['ratio']) ? $_GET['ratio'] : 1 ?>" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Pengujian?')">Proses Pengujian</a>
            </div>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Probabilitas Positif</th>
                        <th>Probabilitas Negatif</th>
                        <th>Hasil Sistem</th>
                        <th>Hasil Manual</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Data</th>
                        <th>Probabilitas Positif</th>
                        <th>Probabilitas Negatif</th>
                        <th>Hasil Sistem</th>
                        <th>Hasil Manual</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?php
            //hitung akurasi
            $queryCek = "SELECT hasil, kelas FROM pengujian JOIN raw_data ON pengujian.raw_id = raw_data.id WHERE pengujian.rasio = '$ratio'";
            $dataCek = mysqli_query($con, $queryCek);

            $tp = $tn = $fp = $fn = 0;
            while($data = mysqli_fetch_array($dataCek, MYSQLI_ASSOC))
            {
                $kelas = $data['kelas'];
                $hasil = $data['hasil'];

                //true positive
                if($kelas == '1' && $hasil == '1')
                {
                    $tp++;
                }

                //true negative
                if($kelas == '0' && $hasil == '0')
                {
                    $tn++;
                }

                //false positive
                if($kelas == '0' && $hasil == '1')
                {
                    $fp++;
                }

                //false negative
                if($kelas == '1' && $hasil == '0')
                {
                    $fn++;
                }
            }
            //echo $fn; 
            echo "TP:".$tp.", TN:".$tn.", FP:".$fp.", FN:".$fn;
            $akurasi = (($tp + $tn) / ($tp + $tn + $fp + $fn)) * 100;
            $precision = ($tp / ($tp + $fp)) * 100;
            $recall =  @($tp / ($tp + $fn)) * 100;
            ?>
            <ul>
                <li><img src="<?= $url ?>assets/akurasi.png"> = <?= number_format($akurasi, 2, ',', '') ?>%</li>
                <li><img src="<?= $url ?>assets/precision.png"> = <?= number_format($precision, 2, ',', '') ?>%</li>
                <li><img src="<?= $url ?>assets/recall.png"> = <?= number_format($recall, 2, ',', '') ?>%</li>
            </ul>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->