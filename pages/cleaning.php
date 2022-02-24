<?php
//TODO: Put all of this page's process here!
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
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
    }

    echo "<script>window.location.href='".$url."?page=cleaning'</script>";
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
          <h3 class="box-title">Cleaning</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=cleaning&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses cleaning?')">Proses Cleaning</a>
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Data ID</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Data ID</th>
                    <th>Data</th>
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