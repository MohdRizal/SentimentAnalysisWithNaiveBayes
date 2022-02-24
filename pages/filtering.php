<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    require_once 'vendor/autoload.php';

    //empty filtering table (reset)
    $sql0 = "TRUNCATE TABLE filtering";
    mysqli_query($con, $sql0);

    //select data after normalisasi
    $sql = "SELECT * FROM normalisasi";
    $query = mysqli_query($con, $sql);

    while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $normalisasi_id = $data['id'];
        $normalisasi = $data['data'];

        $stopwordFactory = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
        $stopword = $stopwordFactory->createStopWordRemover();
        $output = $stopword->remove($normalisasi);

        //echo $normalisasi.'==>'.$output."<br>";

        if(!empty($output))
        {   
            //echo $normalisasi."====$normalisasi_id<br>";
            $sqlFiltering = "INSERT INTO filtering (data, normalisasi_id) VALUES ('$normalisasi', $normalisasi_id)";
            //echo $sqlFiltering."<br>";
            mysqli_query($con, $sqlFiltering);
        }
    }
    echo "<script>window.location.href='".$url."?page=filtering'</script>";
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
          <h3 class="box-title">Filtering</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=filtering&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Filtering?')">Proses Filtering</a>
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>ID Normalisasi</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>ID Normalisasi</th>
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



