<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url('student')?>" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('student/save', array('id'=>'student'), array('method'=>'add'))?>
                    <div class="form-group">
                        <label for="student_number">NIM</label>
                        <input autofocus="autofocus" onfocus="this.select()" placeholder="Std ID" type="text" name="student_number" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input placeholder="Student's Name" type="text" name="name" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input placeholder="Email" type="email" name="email" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control select2">
                            <option value="">-- Choose --</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control select2">
                            <option value="" disabled selected>-- Choose --</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select id="class" name="class" class="form-control select2">
                            <option value="">-- Choose --</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group pull-right">
                        <button type="reset" class="btn btn-flat btn-default"><i class="fa fa-rotate-left"></i> Reset</button>
                        <button type="submit" id="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Save</button>
                    </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/master/student/add.js"></script>