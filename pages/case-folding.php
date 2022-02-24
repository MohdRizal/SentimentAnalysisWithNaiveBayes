<?php
if(isset($_GET['task']) && $_GET['task'] == 'proses'){
    //reset case-folding process
    $sql0 = "TRUNCATE TABLE case_folding";
    mysqli_query($con, $sql0);

    //select data after cleaning
    $sql = "SELECT * FROM cleaning";
    $query = mysqli_query($con, $sql);

    //process case folding
    while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $trimmed = trim($data['data']);
        $case_folded = strtolower($trimmed);

        $sql2 = "INSERT INTO case_folding (data) VALUES ('".$case_folded."')";
        mysqli_query($con, $sql2);
    }
    echo "<script>window.location.href='".$url."?page=case-folding'</script>";
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
          <h3 class="box-title">Case Folding</h3>

          <div class="box-tools pull-right">
              <a href="<?= $url ?>?page=case-folding&task=proses" class="btn btn-primary" onclick="return confirm('Yakin lakukan proses Case Folding?')">Proses Case Folding</a>
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