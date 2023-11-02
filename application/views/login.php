<?php $this->load->view('header'); ?>

<div class="container">
    <form method="POST" action="<?php echo base_url('welcome/login'); ?>">
        
        <label for="dbselect">Բազա</label>
        <select id="dbselect" name="database">
	    <option value="default">Նորմալ</option>
            <option value="old6">Արխիվ 6</option>
            <option value="old5">Արխիվ 5</option>
            <option value="old4">Արխիվ 4</option>
            <option value="old3">Արխիվ 3</option>
            <option value="old2">Արխիվ 2</option>
            <option value="old">Արխիվ</option>
            <!-- <option value="test">Test</option> -->
        </select>
        Username:
        <input name="username" class="form-control" type="text" />
        Password:
        <input name="password" class="form-control" type="password" />
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <input type="submit" value="Login" />
    </form>
</div>
<?php $this->load->view('footer'); ?>
