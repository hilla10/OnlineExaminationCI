<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$title?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>departmentCourse" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4><i class="fa fa-info-circle"></i> Information</h4>
                    If the Course column is empty, the following are possible causes:
                    <br><br>
                    <ol class="pl-4">
                        <li>You have not added Master Course data (Master Course is empty/no data at all).</li>
                        <li>Courses have been added, so you don't need to add more. You only need to edit the data for the course department.</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-4">
                <?=form_open('departmentCourse/save', array('id'=>'departmentCourse'), array('method'=>'add'))?>
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected></option>
                        <?php foreach ($course as $m) : ?>
                            <option value="<?=$m->course_id?>"><?=$m->course_name?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select id="department" multiple="multiple" name="department_id[]" class="form-control select2" style="width: 100%!important">
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relation/departmentCourse/add.js"></script>