<div class="row">
    <?php if($this->ion_auth->is_admin()) : ?>
    <div class="col-sm-12 mb-4">
        <a href="<?= base_url('users') ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Cancel
        </a>
    </div>
    <div class="col-sm-4">
        <?= form_open('users/edit_info', ['id' => 'user_info'], ['id' => $users->id]) ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Data User</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $users->username ?>">
                    <small class="help-block"></small>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?= $users->first_name ?>">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?= $users->last_name ?>">
                        <small class="help-block"></small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $users->email ?>">
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btn-info" class="btn btn-success">Save</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
    <?php endif; ?>

    <?php if($user->id !== $users->id) : ?>
    <div class="col-sm-4">
        <?= form_open('users/edit_level', ['id' => 'user_level'], ['id' => $users->id]) ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Level</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="level">Level User</label>
                    <select id="level" name="level" class="form-control select2" style="width: 100%!important">
                        <option value="">Choose Level</option>
                        <?php foreach ($groups as $row) : ?>
                            <option <?= $level->id === $row->id ? "selected" : "" ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btn-level" class="btn btn-success">Save</button>
            </div>
        </div>
        <?= form_close() ?>

        <?= form_open('users/edit_status', ['id' => 'user_status'], ['id' => $users->id]) ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Status</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label>
                        <input <?= $users->active === '1' ? "checked" : "" ?> type="radio" name="status" value="1"> Active
                    </label>
                    <label>
                        <input <?= $users->active === '0' ? "checked" : "" ?> type="radio" name="status" value="0"> Not Active
                    </label>
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btn-status" class="btn btn-success">Save</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
    <?php endif;?>

    <?php if($user->id === $users->id) : ?>
    <div class="col-sm-4">
        <?= form_open('users/change_password', ['id' => 'change_password'], ['id' => $users->id]) ?>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Change Password</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="old">Current Password</label>
                    <input type="password" placeholder="Current Password" name="old" class="form-control" id="old">
                    <small class="help-block"></small>
                    <i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2" id="togglePasswordOld"></i>
                    <span class="show-text pt-2" id="toggleTextOld">Show Password</span>
                </div>
                <div class="form-group">
                    <label for="new">New Password</label>
                    <input type="password" placeholder="New Password" name="new" class="form-control" id="new">
                    <small class="help-block"></small>
                    <i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2" id="togglePasswordNew"></i>
                    <span class="show-text pt-2" id="toggleTextNew">Show Password</span>
                </div>
                <div class="form-group">
                    <label for="new_confirm">Confirmation Password</label>
                    <input type="password" placeholder="Confirmation Password" name="new_confirm" class="form-control" id="new_confirm">
                    <small class="help-block"></small>
                    <i class="bi bi-eye-slash-fill custom-icon-size show-btn mr-2" id="togglePasswordConfirm"></i>
                    <span class="show-text pt-2" id="toggleTextConfirm">Show Password</span>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-flat btn-danger">
                    <i class="fa fa-rotate-left"></i> Reset
                </button>
                <button type="submit" id="btn-pass" class="btn btn-flat btn-warning">Change Password</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
    <?php endif; ?>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/users/edit.js"></script>

<?php if($user->id === $users->id) : ?>
<script type="text/javascript">
$(document).ready(function(){
    // Handling form submission for password change
    $('form#change_password').on('submit', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        
        let btn = $('#btn-pass');
        btn.attr('disabled', 'disabled').text('Process..');

        let url = $(this).attr('action');
        let data = $(this).serialize();
        let msg = "Your password has been changed successfully!";
        submitajax(url, data, msg, btn);
    });

  
});
</script>

<?php endif; ?>
