<div class="login-box pt-5 mt-5">
    <!-- /.login-logo -->
    <div class="login-logo link-color">
        <a href="<?=base_url('login')?>"><b>CBT</b>APP</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">
            <?php echo lang('reset_password_heading');?>
        </p>

        <!-- Display the message -->
        <div id="infoMessage" class="text-red text-center"><?php echo $message;?></div>

        <?php echo form_open('auth/reset_password/' . $code);?>

          <div class="mb-2">
              <p>
                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
                <?php echo form_input($new_password, 'class="w-100"');?>
                
            </p>
            <div class="d-flex align-items-center">
            <i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2 " id="togglePasswordNew"></i>
                    <span class="show-text pt-2" id="toggleTextNew">Show Password</span>
</div>
          </div>
          <div class="mb-2">
              <p>
                <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                <?php echo form_input($new_password_confirm);?>

            </p>
              <div class="d-flex align-items-center">
                  <i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2 " id="togglePasswordConfirm"></i>
                    <span class="show-text   pt-2" id="toggleTextConfirm">Show Password</span>
              </div>

          </div>
            <?php echo form_input($user_id);?>
            <?php echo form_hidden($csrf); ?>

            <p><?php echo  form_submit('submit', lang('reset_password_submit_btn'),array('class'=>'btn btn-success btn-block btn-flat'));?></p>

        <?php echo form_close();?>
    </div>
</div>

<script>
    $('#togglePasswordOld').on('click', function () {
        togglePasswordVisibility('#old', '#togglePasswordOld', '#toggleTextOld');
    });
    $('#togglePasswordNew').on('click', function () {
        togglePasswordVisibility('#new', '#togglePasswordNew', '#toggleTextNew');
    });
    $('#togglePasswordConfirm').on('click', function () {
        togglePasswordVisibility('#new_confirm', '#togglePasswordConfirm', '#toggleTextConfirm');
    });

    function togglePasswordVisibility(passwordFieldSelector, toggleButtonSelector, toggleTextSelector) {
        const passwordField = $(passwordFieldSelector);
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(toggleButtonSelector).toggleClass('bi-eye bi-eye-slash-fill');
        const toggleText = $(toggleTextSelector);
        toggleText.text(toggleText.text() === 'Show Password' ? 'Hide Password' : 'Show Password');
    }
</script>
