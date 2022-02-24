
      <!-- /.box -->
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
  <form action="<?= $url ?>?page=data">
  <input type="hidden" name="page" value="data">
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

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Data Latih</h3>

          <div class="box-tools pull-right">
          </div>
        </div>
        <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data ID</th>
                        <th>Data</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Data ID</th>
                        <th>Data</th>
                        <th>Kelas</th>
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

       <!-- Default box -->
       <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Data Uji</h3>

          <div class="box-tools pull-right">
          </div>
        </div>
        <div class="box-body">
        <table id="example2" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data ID</th>
                        <th>Data</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Data ID</th>
                        <th>Data</th>
                        <th>Kelas</th>
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