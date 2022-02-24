<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    require_once 'vendor/autoload.php';

    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    $stemmer = $stemmerFactory->createStemmer();
    // $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    // $stemmer  = $stemmerFactory->createStemmer();
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
        //echo $data['data']." - ";
        //proses stemming
        $sentence = $data['data'];
        $output = $stemmer->stem($sentence);
        //echo $output."<br>";
        
        $filterId = $data['id'];
        $sqlStemming = "INSERT INTO stemming_sastrawi (data, filter_id) VALUES ('$output', $filterId)"; 
        //echo $sqlStemming."<br>";
        mysqli_query($con, $sqlStemming);
    }
    //echo "Data: ".mysqli_num_rows($query);

    // stem
    // $sentence = $_POST['text'];
    // $output = $stemmer->stem($sentence);

    // $stemmerFactory = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
    // $stemmer = $stemmerFactory->createStopWordRemover();

    //proses remove stopword
    // $sentence = $_POST['text'];
    // $output = $stemmer->remove($sentence);
    echo "<script>window.location.href='".$url."?page=stemming'</script>";
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
          <h3 class="box-title">Stemming</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=stemming&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Stemming?')">Proses Stemming</a>
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>Hasil Stemming</th>
                    <th>ID Filtering</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>Hasil Stemming</th>
                    <th>ID Filtering</th>
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



