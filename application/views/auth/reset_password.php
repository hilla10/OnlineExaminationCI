<div class="login-box pt-5">
    <!-- /.login-logo -->
    <div class="login-logo">
        <a href="<?=base_url('login')?>"><b>CBT</b>APP</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">
            <?php echo lang('reset_password_heading');?>
        </p>

        <!-- Display the message -->
        <div id="infoMessage" class="text-red text-center"><?php echo $message;?></div>

        <?php echo form_open('auth/reset_password/' . $code);?>

            <p>
                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
                <?php echo form_input($new_password);?>
            </p>

            <p>
                <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                <?php echo form_input($new_password_confirm);?>
            </p>

            <?php echo form_input($user_id);?>
            <?php echo form_hidden($csrf); ?>

            <p><?php echo form_submit('submit', lang('reset_password_submit_btn'));?></p>

        <?php echo form_close();?>
    </div>
</div>
