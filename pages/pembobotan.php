<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    // tentukan rasio data
    $ratio = $_GET['ratio'];
    switch($ratio)
    {
        case "1":
            $limit = 1400;
            break;
        case "2":
            $limit = 1600;
            break;
        default:
            $limit = 1800;
            break;
    }

    //empty pembobotan table (reset)
    $sql0 = "DELETE FROM pembobotan WHERE rasio = '$ratio'";
    mysqli_query($con, $sql0);

    //setting stemmer dan stopword
    $stemmer = 'sastrawi';
    $filtering = 'on';

    if($stemmer == 'sastrawi')
    {
        if($filtering == 'on')
        {
            //select data untuk pembobotan
            $sql = "SELECT stemming_sastrawi.data FROM stemming_sastrawi 
            JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
            JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
            JOIN tokenizing ON tokenizing.id = normalisasi.token_id
            JOIN raw_data ON raw_data.id = tokenizing.raw_id
            WHERE raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab)
            GROUP BY stemming_sastrawi.data";
            $query = mysqli_query($con, $sql);

            while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                $token = $data['data'];
                
                $queryTermPositif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
                JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
                JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
                JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
                JOIN raw_data ON tokenizing.raw_id = raw_data.id 
                WHERE stemming_sastrawi.data = '$token' AND kelas = '1' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab)";
                $queryTFPositif = mysqli_query($con, $queryTermPositif);
                $tfPositif = mysqli_fetch_array($queryTFPositif, MYSQLI_ASSOC);
                $tfPositif = $tfPositif['term_frequency'];
                
                $queryTermNegatif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
                JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
                JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
                JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
                JOIN raw_data ON tokenizing.raw_id = raw_data.id 
                WHERE stemming_sastrawi.data = '$token' AND kelas = '0' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab)";
                $queryTFNegatif = mysqli_query($con, $queryTermNegatif);
                $tfNegatif = mysqli_fetch_array($queryTFNegatif, MYSQLI_ASSOC);
                $tfNegatif = $tfNegatif['term_frequency'];
                
                $queryDocumentFrequency = "SELECT raw_id as document_frequency FROM stemming_sastrawi 
                JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
                JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
                JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
                JOIN raw_data ON tokenizing.raw_id = raw_data.id 
                WHERE stemming_sastrawi.data = '$token' AND raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data ORDER BY length(data) DESC LIMIT $limit) temp_tab)
                GROUP BY tokenizing.raw_id";
                $queryDF = mysqli_query($con, $queryDocumentFrequency);
                $df = mysqli_num_rows($queryDF);

                $queryTotalData = mysqli_num_rows(mysqli_query($con, "SELECT * FROM raw_data"));
                
                $IDF = log10($queryTotalData/$df);
                
                $bobotPositif = $tfPositif * $IDF;
                $bobotNegatif = $tfNegatif * $IDF;

                $queryPembobotan = "INSERT INTO pembobotan(data, tf_positif, tf_negatif, df, idf, bobot_positif, bobot_negatif, rasio) VALUES ('$token', $tfPositif, $tfNegatif, $df, '$IDF', '$bobotPositif', '$bobotNegatif', '$ratio')";
                mysqli_query($con, $queryPembobotan);
            }

            //select data untuk pembobotan -> kelas negatif
            // $sql = "SELECT stemming_sastrawi.data FROM stemming_sastrawi 
            // JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
            // JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
            // JOIN tokenizing ON tokenizing.id = normalisasi.token_id
            // JOIN raw_data ON raw_data.id = tokenizing.raw_id
            // WHERE raw_data.id IN (SELECT * FROM (SELECT id FROM raw_data WHERE kelas = '0' ORDER BY length(data) LIMIT $limit) temp_tab)
            // GROUP BY stemming_sastrawi.data";
            // $query = mysqli_query($con, $sql);

            // while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            //     $token = $data['data'];
                
            //     $queryTermPositif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
            //     JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
            //     JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' AND kelas = '1'";
            //     $queryTFPositif = mysqli_query($con, $queryTermPositif);
            //     $tfPositif = mysqli_fetch_array($queryTFPositif, MYSQLI_ASSOC);
            //     $tfPositif = $tfPositif['term_frequency'];
                
            //     $queryTermNegatif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
            //     JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
            //     JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' AND kelas = '0'";
            //     $queryTFNegatif = mysqli_query($con, $queryTermNegatif);
            //     $tfNegatif = mysqli_fetch_array($queryTFNegatif, MYSQLI_ASSOC);
            //     $tfNegatif = $tfNegatif['term_frequency'];
                
            //     $queryDocumentFrequency = "SELECT raw_id as document_frequency FROM stemming_sastrawi 
            //     JOIN filtering ON filtering.id = stemming_sastrawi.filter_id
            //     JOIN normalisasi ON normalisasi.id = filtering.normalisasi_id
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' GROUP BY tokenizing.raw_id";
            //     $queryDF = mysqli_query($con, $queryDocumentFrequency);
            //     $df = mysqli_num_rows($queryDF);

            //     $queryTotalData = mysqli_num_rows(mysqli_query($con, "SELECT * FROM raw_data"));
                
            //     $IDF = log10($queryTotalData/$df);
                
            //     $bobotPositif = $tfPositif * $IDF;
            //     $bobotNegatif = $tfNegatif * $IDF;

            //     $queryPembobotan = "INSERT INTO pembobotan(data, tf_positif, tf_negatif, df, idf, bobot_positif, bobot_negatif, rasio) VALUES ('$token', $tfPositif, $tfNegatif, $df, '$IDF', '$bobotPositif', '$bobotNegatif', '$ratio')";
            //     mysqli_query($con, $queryPembobotan);
            // }

            echo "Proses Selesai";
        }else{
            //select data after stemming
            // $sql = "SELECT stemming_sastrawi.data FROM stemming_sastrawi 
            // JOIN normalisasi ON normalisasi.id = stemming_sastrawi.filter_id
            // JOIN tokenizing ON tokenizing.id = normalisasi.token_id
            // JOIN raw_data ON raw_data.id = tokenizing.raw_id
            // WHERE raw_data.id BETWEEN 1 and $limit
            // GROUP BY stemming_sastrawi.data";
            // $query = mysqli_query($con, $sql);

            // while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            //     $token = $data['data'];
                
            //     $queryTermPositif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
            //     JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' AND kelas = '1' AND raw_data.id BETWEEN 1 and $limit";
            //     $queryTFPositif = mysqli_query($con, $queryTermPositif);
            //     $tfPositif = mysqli_fetch_array($queryTFPositif, MYSQLI_ASSOC);
            //     $tfPositif = $tfPositif['term_frequency'];
                
            //     $queryTermNegatif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
            //     JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' AND kelas = '0' AND raw_data.id BETWEEN 1 and $limit";
            //     $queryTFNegatif = mysqli_query($con, $queryTermNegatif);
            //     $tfNegatif = mysqli_fetch_array($queryTFNegatif, MYSQLI_ASSOC);
            //     $tfNegatif = $tfNegatif['term_frequency'];
                
            //     $queryDocumentFrequency = "SELECT raw_id as document_frequency FROM stemming_sastrawi 
            //     JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
            //     JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
            //     JOIN raw_data ON tokenizing.raw_id = raw_data.id 
            //     WHERE stemming_sastrawi.data = '$token' GROUP BY tokenizing.raw_id";
            //     $queryDF = mysqli_query($con, $queryDocumentFrequency);
            //     $df = mysqli_num_rows($queryDF);
                
            //     $queryTotalData = mysqli_num_rows(mysqli_query($con, "SELECT * FROM raw_data"));
                
            //     $IDF = log10($queryTotalData/$df);
                
            //     $bobotPositif = $tfPositif * $IDF;
            //     $bobotNegatif = $tfNegatif * $IDF;

            //     $queryPembobotan = "INSERT INTO pembobotan(data, tf_positif, tf_negatif, df, idf, bobot_positif, bobot_negatif) VALUES ('$token', $tfPositif, $tfNegatif, $df, '$IDF', '$bobotPositif', '$bobotNegatif')";
            //     mysqli_query($con, $queryPembobotan);
            // }

            // echo "Proses Selesai";
        }
    }else{
        if($filtering == 'on')
        {

        }else{

        }
    }

    # query tanpa filtering

    /*
    //select data after stemming
    $sql = "SELECT stemming_sastrawi.data FROM stemming_sastrawi 
    JOIN normalisasi ON normalisasi.id = stemming_sastrawi.filter_id
    JOIN tokenizing ON tokenizing.id = normalisasi.token_id
    JOIN raw_data ON raw_data.id = tokenizing.raw_id
    WHERE raw_data.id BETWEEN 1 and $limit
    GROUP BY stemming_sastrawi.data";
    $query = mysqli_query($con, $sql);

    $queryTermPositif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
        JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
        JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
        JOIN raw_data ON tokenizing.raw_id = raw_data.id 
        WHERE stemming_sastrawi.data = '$token' AND kelas = '1'";

    $queryTermNegatif = "SELECT COUNT(raw_id) as term_frequency FROM stemming_sastrawi 
        JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
        JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
        JOIN raw_data ON tokenizing.raw_id = raw_data.id 
        WHERE stemming_sastrawi.data = '$token' AND kelas = '0'";

    $queryDocumentFrequency = "SELECT raw_id as document_frequency FROM stemming_sastrawi 
        JOIN normalisasi ON stemming_sastrawi.filter_id = normalisasi.id 
        JOIN tokenizing ON normalisasi.token_id = tokenizing.id 
        JOIN raw_data ON tokenizing.raw_id = raw_data.id 
        WHERE stemming_sastrawi.data = '$token' GROUP BY tokenizing.raw_id";
        */
        echo "<script>window.location.href='".$url."?page=pembobotan&ratio=$ratio'</script>";
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
        <form action="<?= $url ?>?page=pembobotan">
        <input type="hidden" name="page" value="pembobotan">
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
            <h3 class="box-title">Pembobotan</h3>

            <div class="box-tools pull-right">
                <a href="<?= $url ?>?page=pembobotan&task=proses&ratio=<?= isset($_GET['ratio']) ? $_GET['ratio'] : 1 ?>" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Pembobotan?')">Proses Pembobotan</a>
            </div>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>TF Positif</th>
                        <th>TF Negatif</th>
                        <th>DF</th>
                        <th>IDF</th>
                        <th>Bobot Positif</th>
                        <th>Bobot Negatif</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>TF Positif</th>
                        <th>TF Negatif</th>
                        <th>DF</th>
                        <th>IDF</th>
                        <th>Bobot Positif</th>
                        <th>Bobot Negatif</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->