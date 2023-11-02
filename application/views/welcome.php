<?php $this->load->view('header'); ?>
<div id="container">
<br />
    <h1>Պահեստի շարժ</h1>
    <div id="body">
        <?php if($username == 'admin123457'){ ?><a href="<?php echo base_url('products'); ?>"><button class="btn btn-default">Ապրանքներ</button></a><?php } ?>
        <?php if($username == 'admin123457'){ ?><a href="<?php echo base_url('clients'); ?>"><button class="btn btn-default">Կլիենտներ</button></a><?php } ?>
        <?php if($username == 'admin123457'){ ?><a href="<?php echo base_url('orders'); ?>"><button class="btn btn-default">Գործարքներ</button></a><?php } ?>
        <?php if($username == 'admin123457'){ ?><a href="<?php echo base_url('payment'); ?>"><button class="btn btn-default">Վճարում</button></a><?php } ?>
        <?php if($username == 'admin123457'){ ?><a href="<?php echo base_url('giveback'); ?>"><button class="btn btn-default">Ապրանքի վերադարձ</button></a><?php } ?>
        <a href="<?php echo base_url('dashboard'); ?>"><button class="btn btn-info">Աղյուսակ</button></a>
    </div><br />
    <div>
        <button id="logoutBtn" class="btn btn-danger">Logout</button>
    </div>
</div>
<?php $this->load->view('footer'); ?>
<script>
    $(document).ready(function(){
        localStorage.setItem('username', '<?php echo $username; ?>');
    })
</script>
