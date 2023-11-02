<style>
.navbar-right{
    padding-right: 5px;
}

.navbar-nav > li > a, .navbar-brand {
    padding-top:5px !important; padding-bottom:0 !important;
    height: 30px;
}
.navbar {min-height:30px !important;}

.navbar-inverse .navbar-nav > li > a {
  color: #131212;
}
</style>

<header>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid bg-primary">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">Accurate Group</a>
    </div>
    <ul class="nav navbar-nav">
        
        <?php /*<li><a href="<?php echo base_url('welcome'); ?>/login/<?=$username;?>">Home</a></li>*/ ?>
        <li><a href="<?php echo base_url('products'); ?>">Ապրանքներ</a></li>
        <li><a href="<?php echo base_url('clients'); ?>">Կլիենտներ</a></li>
        <li><a href="<?php echo base_url('orders'); ?>">Գործարքներ</a></li>
        <li><a href="<?php echo base_url('payment'); ?>">Վճարում</a></li>
        <li><a href="<?php echo base_url('giveback'); ?>">Ապրանքի վերադարձ</a></li>
        <li><a href="<?php echo base_url('dashboard'); ?>">Աղյուսակ</a></li>
        <li><a href="<?php echo base_url('debts'); ?>">Պարտքերի աղյուսակ</a></li>
        <li><a href="<?php echo base_url('getproduct'); ?>">Ներմուծում</a></li>
        <li><a href="<?php echo base_url('dashboardnew'); ?>">Աղյուսակ (NEW)</a></li>
        
        
      <!--
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ապրանքներ
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#" id="new_product">Նոր ապրանք</a></li>
          <li><a href="#" id="edit_product">Փոփոխել</a></li>
        </ul>
      </li>
      
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ապրանքներ
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ապրանքներ
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ապրանքներ
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ապրանքներ
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>      
      
      
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>-->
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li><div style="margin-top: 7px; padding-right: 10px;"><?php echo "database:" . $this->db->database;?></div></li>
      <li><button id="logoutBtn" class="btn btn-sm btn-danger">Logout</button></li>
    </ul>
  </div>
</nav> 
</header>