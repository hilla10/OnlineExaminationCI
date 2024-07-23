<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$title?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>classLecturer" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('classLecturer/save', array('id'=>'classLecturer'), array('method'=>'edit', 'lecturer_id'=>$lecturer_id))?>
                <div class="form-group">
                    <label>Lecturer</label>
                    <input type="text" readonly="readonly" value="<?=$lecturer->lecturer_name?>" class="form-control">
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Class</label>
                    <select id="class" multiple="multiple" name="class_id[]" class="form-control select2" style="width: 100%!important">
                        <?php 
                        $sk = [];
                        foreach ($class as $key => $val) {
                            $sk[] = $val->class_id;
                        }
                        foreach ($all_class as $m) : ?>
                            <option <?=in_array($m->class_id, $sk) ? "selected" : "" ?> value="<?=$m->class_id?>"><?=$m->class_name?> - <?=$m->department_name?></option>
                        <?php endforeach; ?>
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

<script src="<?=base_url()?>assets/dist/js/app/relation/classLecturer/edit.js"></script>