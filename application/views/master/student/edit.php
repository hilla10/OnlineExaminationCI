<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$title?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url('student')?>" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('student/save', array('id'=>'student'), array('method'=>'edit', 'student_id'=>$student->student_id))?>
                    <div class="form-group">
                        <label for="student_number">student Number</label>
                        <input value="<?=$student->student_number?>" autofocus="autofocus" onfocus="this.select()" placeholder="student Number" type="text" name="student_number" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input value="<?=$student->name?>" placeholder="Nama" type="text" name="name" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input value="<?=$student->email?>" placeholder="Email" type="email" name="email" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control select2">
                            <option value="">-- Choose --</option>
                            <option <?=$student->gender === "M" ? "selected" : "" ?> value="M">Male</option>
                            <option <?=$student->gender === "F" ? "selected" : "" ?> value="F">Female</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control select2">
                            <option value="" disabled selected>-- Choose --</option>
                            <?php foreach ($department as $j) : ?>
                            <option <?=$student->department_id === $j->department_id ? "selected" : "" ?> value="<?=$j->department_id?>">
                                <?=$j->department_name?>
                            </option>
                            <?php endforeach ?>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select id="class" name="class" class="form-control select2">
                            <option value="" disabled selected>-- Choose --</option>
                            <?php foreach ($class as $k) : ?>
                            <option <?=$student->class_id === $k->class_id ? "selected" : "" ?> value="<?=$k->class_id?>">
                                <?=$k->class_name?>
                            </option>
                            <?php endforeach ?>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group pull-right">
                        <button type="reset" class="btn btn-flat btn-danger"><i class="fa fa-rotate-left"></i> Reset</button>
                        <button type="submit" id="submit" class="btn btn-flat bg-green"><i class="fa fa-save"></i> Save</button>
                    </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/master/student/edit.js"></script>