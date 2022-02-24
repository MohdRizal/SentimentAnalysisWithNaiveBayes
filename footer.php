
  

  <footer class="main-footer navbar-fixed-bottom">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?= $url ?>assets/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $url ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?= $url ?>assets/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $url ?>assets/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?= $url ?>assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= $url ?>assets/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $url ?>assets/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $url ?>assets/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
  $(function () {
    // $('#example1').DataTable()
    <?php
    if(isset($_GET['page']))
    {
      $page = $_GET['page'];
      $ajaxUrl = $url.'?api='.$page;
      $multipleRatiosPage = ['pembobotan', 'pelatihan', 'pengujian', 'data'];
      if(in_array($page, $multipleRatiosPage))
      {
        $ratio = isset($_GET['ratio']) ? $_GET['ratio'] : '1';
        $ajaxUrl .= "&ratio=".$ratio;
      }
      ?>
      $('#example1').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax": "<?= $ajaxUrl ?>"
      });
    <?php
      if($page == 'data')
      {
        ?>
        $('#example2').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "<?= $url ?>?api=data_uji&ratio="+<?= $ratio ?>
        });
    <?php
      }
    }
    ?>
    
  })
</script>
</body>
</html>
