<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    // tentukan rasio data
    $ratio = $_GET['ratio'];
   
    //empty pelatihan table (reset)
    $sql0 = "DELETE FROM pelatihan WHERE rasio = '$ratio'";
    mysqli_query($con, $sql0);

    //select data after pembobotan
    $sql = "SELECT * FROM pembobotan WHERE rasio = '$ratio'";
    $query = mysqli_query($con, $sql);

    //jumlah kemunculan kata pada masing2 kelas
    $queryTotalKata = "SELECT SUM(tf_positif) as tf_positif, SUM(tf_negatif) as tf_negatif, COUNT(data) as total_data FROM pembobotan WHERE rasio = '$ratio'";
    $totalWord = mysqli_fetch_array(mysqli_query($con, $queryTotalKata), MYSQLI_ASSOC);
    $i = 1;
    while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $id = $data['id'];
        $token = $data['data'];
        $bobotPositif = $data['bobot_positif'];
        $bobotNegatif = $data['bobot_negatif'];

        $probPositif = ($bobotPositif + 1) / ($totalWord['tf_positif'] + $totalWord['total_data']);
        $probNegatif = ($bobotNegatif + 1) / ($totalWord['tf_negatif'] + $totalWord['total_data']);

        //echo $i.'-';
        $queryTraining = "INSERT INTO pelatihan (data, positif, negatif, rasio) VALUES ('$token', '$probPositif', '$probNegatif', '$ratio')";
        var_dump(mysqli_query($con, $queryTraining));
        //echo '<br>';
        $i++;
    }

    echo "<script>window.location.href='".$url."?page=pelatihan&ratio=$ratio'</script>";
    exit;
}
//echo 'selesai';
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
        <input type="hidden" name="page" value="pelatihan">
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
            <h3 class="box-title">Pelatihan</h3>

            <div class="box-tools pull-right">
                <a href="<?= $url ?>?page=pelatihan&task=proses&ratio=<?= isset($_GET['ratio']) ? $_GET['ratio'] : 1 ?>" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Pelatihan?')">Proses Pelatihan</a>
            </div>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>Probabilitas Positif</th>
                        <th>Probabilitas Negatif</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>Probabilitas Positif</th>
                        <th>Probabilitas Negatif</th>
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