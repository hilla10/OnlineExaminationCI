<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>ujian/master" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4>Course <i class="fa fa-book pull-right"></i></h4>
                    <p><?=$course->course_name?></p>
                </div>
                <div class="alert bg-purple">
                    <h4>Lecturer <i class="fa fa-address-book-o pull-right"></i></h4>
                    <p><?=$lecturer->lecturer_name?></p>
                </div>
            </div>
            <div class="col-sm-4">
                <?=form_open('ujian/save', array('id'=>'formujian'), array('method'=>'edit','lecturer_id'=>$lecturer->lecturer_id, 'course_id'=>$course->course_id, 'exam_id'=>$ujian->exam_id))?>
                <div class="form-group">
                    <label for="exam_name">Exam Name</label>
                    <input value="<?=$ujian->exam_name?>" autofocus="autofocus" onfocus="this.select()" placeholder="Nama Ujian" type="text" class="form-control" name="exam_name">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="number_of_questions">Number of Questions</label>
                    <input value="<?=$ujian->number_of_questions?>" placeholder="Number of Questions" type="number" class="form-control" name="number_of_questions">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Date</label>
                    <input id="start_time" name="start_time" type="text" class="datetimepicker form-control" placeholder="Start Date">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="end_time">Completion Date</label>
                    <input id="end_time" name="end_time" type="text" class="datetimepicker form-control" placeholder="Completion Date">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="duration">Time</label>
                    <input value="<?=$ujian->duration?>" placeholder="In Minute" type="number" class="form-control" name="duration">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="type">Question Pattern</label>
                    <select name="type" class="form-control">
                        <option value="" disabled selected>--- Choose ---</option>
                        <option <?=$ujian->type==="Random"?"selected":"";?> value="Random">Random Question</option>
                        <option <?=$ujian->type==="Sort"?"selected":"";?> value="Sort">Sort Question</option>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-default btn-flat">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Save</button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var start_time = '<?=$ujian->start_time?>';
    var late_time = '<?=$ujian->late_time?>';
</script>

<script src="<?=base_url()?>assets/dist/js/app/ujian/edit.js"></script>