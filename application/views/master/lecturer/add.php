<?=form_open('lecturer/save', array('id'=>'formlecturer'), array('method'=>'add'));?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$subtitle?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>lecturer" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="form-group">
                    <label for="teacher_id">Teacher ID</label>
                    <input autofocus="autofocus" onfocus="this.select()" type="number" id="teacher_id" class="form-control" name="teacher_id" placeholder="Teacher ID">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="lecturer_name">Lecturer Name</label>
                    <input type="text" class="form-control" name="lecturer_name" placeholder="Lecturer Name">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="email">Lecturer Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Lecturer Email">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="course">Course</label>
                    <select name="course" id="course" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected>Choose Course</option>
                        <?php foreach ($course as $row) : ?>
                            <option value="<?=$row->course_id?>"><?=$row->course_name?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button type="submit" id="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?=form_close();?>

<script src="<?=base_url()?>assets/dist/js/app/master/lecturer/add.js"></script>