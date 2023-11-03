  <script type="text/javascript" language="javascript" src="<?=base_url() ?>/assets/js/jquery-3.3.1.js"></script>
  <!--<script type="text/javascript" language="javascript" src="assets/js/jquery.js"></script>-->
  <script src="<?=base_url() ?>/assets/js/jquery-ui.min.js"></script>
  
  <!-- Highcharts
  <script src="assets/js/highcharts.js"></script>
  <script src="assets/js/exporting.js"></script>
  <script src="assets/js/accessibility.js"></script> -->
  
  <!-- Bootstrap Core JavaScript 
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>-->
  
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
-->
<script src="<?php echo base_url('/assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.js'); ?>"></script>
<script src="<?php echo base_url('/assets/scripts.js'); ?>"></script>
<!--<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>-->




      <!-- Datatables 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
    <script src="<?=base_url() ?>/assets/datatables/jszip/dist/jszip.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?=base_url() ?>/assets/datatables/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <!--<script src="assets/datatables/datatables.net-scroller/js/dataTables.scroller.min.js"></script>-->     


    <script src="<?=base_url() ?>/assets/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script>
    $(document).on('click', '#logoutBtn', function(event){
        localStorage.clear();
        window.location.href = '<?=base_url() ?>';
    });
</script>

</div>
</body>
</html>
