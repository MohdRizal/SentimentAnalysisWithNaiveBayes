<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    //empty tokenizing table (reset)
    $sql0 = "TRUNCATE TABLE tokenizing";
    mysqli_query($con, $sql0);

    //select data after case folding
    $sql = "SELECT * FROM case_folding";

    $query = mysqli_query($con, $sql);

    //process tokenizing
    while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        //$rm_mltpl_spaces = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $data['data']);

        $exp2 = explode(" ", $data['data']);
        foreach($exp2 as $token)
        {
            //insert data after cleaning per row
            if($data != " " || $data != "  "):
                $sql2 = "INSERT INTO tokenizing (data, raw_id) VALUES ('".$token."', ".$data['id'].")";
                $query2 = mysqli_query($con, $sql2);
            endif;
        }
    }
    echo "<script>window.location.href='".$url."?page=tokenizing'</script>";
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
          <h3 class="box-title">Tokenizing</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=tokenizing&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Tokenizing?')">Proses Tokenizing</a>
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Token ID</th>
                    <th>Token</th>
                    <th>ID Data</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Token ID</th>
                    <th>Token</th>
                    <th>ID Data</th>
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

