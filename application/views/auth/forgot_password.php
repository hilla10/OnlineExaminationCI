<div class="login-box width-50 pt-5">
    <!-- /.login-logo -->
    <div class="login-logo">
        <a href="<?= base_url('login') ?>" data-toggle="tooltip" data-placement="top" title="Entoto Polytechnic College Online Examination System APP">
            <b>EPTC</b> <b>OES</b> APP
        </a>
    </div>

    <div class="login-box-body">
        <h3 class="text-center mt-0 mb-4">
            <?php echo lang('forgot_password_heading'); ?>
        </h3>

        <?php if ($this->session->flashdata('message')): ?>
            <!-- Display success message -->
            <div class="flash-message" id="infoMessage">
                <?= $this->session->flashdata('message') ?>
            </div>
            <?php $this->session->set_flashdata('message', null); // Clear the flash message ?>
        <?php elseif ($this->session->flashdata('error')): ?>
            <!-- Display error message -->
			<p class="text-red text-center"><?= $this->session->flashdata('error'); ?></p>
			  <?php echo form_open("auth/forgot_password"); ?>
            <p>
                <label for="identity">
                    <?php echo (($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label)); ?>
                </label> <br />
                <?php echo form_input($identity); ?>
            </p>
            <p><?php echo form_submit('submit', 'Forgot Password', ['class' => 'btn btn-danger btn-flat btn-block']); ?></p>
            <?php echo form_close(); ?>
        <?php else: ?>
            <!-- Display the input form if there's no message or error -->
            <p class="login-box-msg">
                <?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?>
            </p>
           <?php echo form_open("auth/forgot_password"); ?>
            <p>
                <label for="identity">
                    <?php echo (($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label)); ?>
                </label> <br />
                <?php echo form_input($identity); ?>
            </p>
            <p><?php echo form_submit('submit', 'Forgot Password', ['class' => 'btn btn-danger btn-flat btn-block']); ?></p>
            <?php echo form_close(); ?>
        <?php endif; ?>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

