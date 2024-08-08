<div class="login-box pt-5">
    <div class="login-box-body">
        <h3 class="text-center mt-0 mb-4">
            <b class="text-primary"  data-toggle="tooltip" data-placement="top" title="Entoto Polytechnic College">EPTC</b> Online Examination System
        </h3> 
        <p class="login-box-msg">Login to start your session</p>

        <div id="infoMessage" class="text-center"><?php echo $message;?></div>

        <?= form_open("auth/cek_login", array('id'=>'login', 'class'=>'mt-4'));?>
            <!-- Your existing login form fields -->
            <div class="form-group has-feedback">
			<?= form_input($identity);?>
			<span class="fa fa-envelope form-control-feedback"></span>
			<span class="help-block"></span>
		</div>
         <div class="form-group has-feedback">
			<?= form_input($password);?>
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			<span class="help-block"></span>
		</div>
        	<div class="form-group d-flex align-items-center">
		<i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2" id="togglePassword"></i>
		<span class="show-text pt-2" id="toggleText">Show Password</span>
		</div>

          <div class="mt-4">
            <div class="pb-3">
                  <?= form_submit('submit', lang('login_submit_btn'), array('id'=>'submit','class'=>'btn btn-success btn-block btn-flat'));?>
             <?= form_close(); ?>
            </div>

      <div class="pb-3">
          <!-- New OAuth Login Buttons -->
        <a href="<?= base_url('auth/google_login') ?>" class="d-flex align-items-center btn-block  justify-content-center login-google"> 
            <img src="<?= base_url('assets/dist/img/google-logo-NePEveMl.svg')?>" alt="">
            <span>
                Login with Google 
            </span>
        </a>
      </div>
          </div>

        <a href="<?=base_url()?>auth/forgot_password" class="text-center"><?= lang('login_forgot_password');?></a>
    </div>
</div>


<script type="text/javascript">
	let base_url = '<?=base_url();?>';
    
	 $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script src="<?=base_url()?>assets/dist/js/app/auth/login.js"></script>