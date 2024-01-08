<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accurate group</title>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/style.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css'); ?>">
    
    
    <!--<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
   	
    DataTables CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/jquery.dataTables.min.css'); ?>">
    <link href="<?php echo base_url('/assets/datatables/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('/assets/datatables/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('/assets/datatables/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('/assets/datatables/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
    <!--<link href="assets/datatables/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/bootstrap-select/css/bootstrap-select.min.css'); ?>">
    <!-- Custom CSS--> 
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/jquery-ui.min.css'); ?>">
    <!--<link href="assets/css/blog-home.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="<?php echo base_url('/assets/fontawesome/css/font-awesome.min.css'); ?>">


</head>
<body>
<?php 
if($_SERVER["REQUEST_URI"]!=='/'){
    include 'menu.php';
}
?>
<div class="clearfix"></div>
<div class="container-fluid">






    <?php //echo "database:" . $this->db->database;?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button>
            </div>
        </div>

    </div>
</div>
