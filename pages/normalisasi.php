<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
     //empty normalisasi table (reset)
     $sql0 = "TRUNCATE TABLE normalisasi";
     mysqli_query($con, $sql0);

     //select data after tokenizing
     $sql = "SELECT * FROM tokenizing WHERE data != ''";
     $query = mysqli_query($con, $sql);

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

     //echo "total token = ".$index;

     //process tokenizing
     //$index_ = 1;
     while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
          $val = array_search($data['data'], array_column($kamus, 'nama'));
          if($val !== FALSE)
          {
               $normal = $kamus[$val]['normal'];
               $ket = 1;
          }else{
               $normal = $data['data'];
               $ket = 0;
          }
          

          $insertNormalisasi = "INSERT INTO normalisasi (data, token_id, ket) VALUES ('$normal', ".$data['id'].", '$ket')";

          if(mysqli_query($con, $insertNormalisasi))
          {
               //echo "data $index_ masuk";
          }else{
               echo "data $index_ gamasuk";
          }
     }
     echo "<script>window.location.href='".$url."?page=normalisasi'</script>";
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
          <h3 class="box-title">Normalisasi</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=normalisasi&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Normalisasi?')">Proses Normalisasi</a>
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>ID Tokenizing</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Token</th>
                    <th>ID Tokenizing</th>
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

