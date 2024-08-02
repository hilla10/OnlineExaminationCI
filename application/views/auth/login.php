<div class="login-box pt-5">
    <div class="login-box-body">
        <h3 class="text-center mt-0 mb-4">
            <b>O</b>nline <b>E</b>xamination <b>S</b>ystem
        </h3> 
        <p class="login-box-msg">Login to start your session</p>

        <div id="infoMessage" class="text-center"><?php echo $message;?></div>

        <?= form_open("auth/cek_login", array('id'=>'login'));?>
            <!-- Your existing login form fields -->
            <?= form_input($identity);?>
            <?= form_input($password);?>
            <?= form_submit('submit', lang('login_submit_btn'), array('id'=>'submit','class'=>'btn btn-success btn-block btn-flat'));?>
        <?= form_close(); ?>

        <!-- New OAuth Login Buttons -->
        <a href="<?= base_url('auth/google_login') ?>" class="btn btn-danger btn-block">Login with Google</a>
        <a href="<?= base_url('auth/instagram_login') ?>" class="btn btn-info btn-block">Login with Instagram</a>

        <a href="<?=base_url()?>auth/forgot_password" class="text-center"><?= lang('login_forgot_password');?></a>
    </div>
</div>


<script type="text/javascript">
	let base_url = '<?=base_url();?>';

</script>
<script src="<?=base_url()?>assets/dist/js/app/auth/login.js"></script>