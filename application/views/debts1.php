<style>
table.dataTable.no-footer {
  border: 1px solid #111;
}
</style>

<?php $this->load->view('header'); ?>
<div class="container">
<h4 id="client_debt">Պարտքերի ցուցակ</h4>
<div id="depts_table">
    <div class="input-group date" style="width: 200px;" data-provide="datepicker">
        <input type="text" id="debts_date" value="<?php echo $datepicker_value; ?>" class="form-control datepicker" name="debts_date">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>
    <hr />
    <div class="col-md-12">
    <table class="table" id="deptable">
        <thead>
            <th>Կլիենտ</th>
            <th>Պարտք</th>
        </thead>
        <tbody>
        <?php
            foreach($result_array as $name => $arr)
            {
                echo '<tr data-client_id="'.$arr['id'].'" data-toggle="modal" data-target="#myModal" class="clickable client_info"><td>'.$name.'</td><td>'.$arr['debt'].'</td></tr>';
            }
        ?>
        </tbody>
    </table>
    </div>
</div>
<div class="giveback">
    <a href="dashboard"><button class="btn btn-default">Հետ</button></a>
</div>
</div>
<?php $this->load->view('footer'); ?>
<script>
	$(document).ready(function() {       
       
    $('#deptable').DataTable({
          "ordering": true,
          //"paging": false,
          //"searching": false,
          //"info":     false,
          dom: "Bflrtip",   
          buttons: [
            {extend: "copy", className: "btn-sm"},
            {extend: "csv", className: "btn-sm"},
            {extend: "excel", className: "btn-sm"},
            {extend: "print", className: "btn-sm"},
          ],
        });    
    });
</script>