<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Komentar</h3>
        </div>
        <form action="" method="post">
        <input type="hidden" name="page" value="pengujian">
        <div class="box-body">
            <div class="form-group">
               <input type="text" name="komentar" class="form-control" required>
            </div>   
                    
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button class="btn btn-primary" type="submit">Lakukan Prediksi</button>
        </div>
        </form>
        <!-- /.box-footer-->
    </div>
      <!-- /.box -->

      <?php
        if($_POST){
            require_once "lib.php";

            $komentar = $_POST['komentar'];

            //proses cleaning
            $cleaning = cleaning($komentar);

            //proses case folding
            $case_folding = case_folding($cleaning);

            //tokenizing
            $tokenizing = tokenizing($case_folding);

            //normalisasi
            $normalisasi = normalisasi($tokenizing);

            //filtering
            $filtering = filtering($normalisasi);

            //stemming
            $stemming = stemming($filtering);

            //get total words each class
            $queryTotalKata = "SELECT SUM(tf_positif) as tf_positif, SUM(tf_negatif) as tf_negatif, COUNT(data) as total_data FROM pembobotan WHERE rasio = '3'";
            $totalWord = mysqli_fetch_array(mysqli_query($con, $queryTotalKata), MYSQLI_ASSOC);

            //probabilitas kelas terhadap dokumen
            $queryProbKelasDok = "SELECT COUNT(id) as total_data, (SELECT count(kelas) FROM raw_data WHERE kelas = '1' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT 1800) temp_tab) ) as data_pos, (SELECT count(kelas) FROM raw_data WHERE kelas = '0' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT 1800) temp_tab) ) as data_neg FROM `raw_data`";
            $execKelasDoc = mysqli_fetch_array(mysqli_query($con, $queryProbKelasDok), MYSQLI_ASSOC);
            $probKelasDokPositif = $execKelasDoc['data_pos'] / 1800;
            $probKelasDokNegatif = $execKelasDoc['data_neg'] / 1800;

            echo "prob positif :".$probKelasDokPositif." - ".$probKelasDokNegatif."<br>";


            foreach($stemming as $data) {
                $token = $data;
                $positif = 0;
                $negatif = 0;
    
                $probabilitas = mysqli_query($con,"SELECT positif, negatif FROM pelatihan WHERE data = '$token' AND rasio = '3'");
                $numRows = mysqli_num_rows($probabilitas);
    
                //jika token ada di pelatihan
                if($numRows > 0){
                    $hasilProbabilitas = mysqli_fetch_array($probabilitas);
                    $positif = $hasilProbabilitas['positif'];
                    $negatif = $hasilProbabilitas['negatif'];
                }

                echo $token.': '.$positif."-".$negatif."<br>";
    
                //perhitungan probabilitas
                $pos[] = $prob_positif = ($positif + 1) / ($totalWord['tf_positif'] + $totalWord['total_data']);
                $neg[] = $prob_negatif = ($negatif + 1) / ($totalWord['tf_negatif'] + $totalWord['total_data']);    
            }

            $pos = $probKelasDokPositif * array_product($pos);
            echo $pos."<br>";
            $neg = $probKelasDokNegatif * array_product($neg);
            echo $neg."<br>";

            $result = max($pos, $neg);

            if($result == $pos){
                $result = "Positif";
            }else{
                $result = "Negatif";
            }

        ?>

      <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Hasil Prediksi</h3>
        </div>
        <div class="box-body">
           
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?php
                echo $cleaning."<br>";
                echo $case_folding."<br>";
                print_r($tokenizing); echo "<br>";
                print_r($normalisasi); echo "<br>";
                print_r($filtering); echo "<br>";
                print_r($stemming); echo "<br>";
                echo $result;
            ?>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
    <?php } ?>
  </div>
  <!-- /.content-wrapper -->