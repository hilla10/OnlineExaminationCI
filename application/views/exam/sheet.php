<?php
if(time() >= $question->times_up)
{
    redirect('exam/list', 'location', 301);
}
?>
<div class="row">
    <div class="col-sm-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Question Navigation</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body text-center" id="display_answer">
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <?=form_open('', array('id'=>'exam'), array('id'=> $id_tes));?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><span class="badge bg-blue">Question #<span id="question_number"></span> </span></h3>
                <div class="box-tools pull-right">
                    <span class="badge bg-red" onload="return Save()">Remaining time <span class="remainingTime" data-time="<?=$question->end_time?>"></span></span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <?=$html?>
            </div>
            <div class="box-footer text-center">
                <a class="action back btn btn-info" rel="0" onclick="return back();"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                <a class="ragu_ragu btn btn-warning" rel="1" onclick="return no_answer();">Doubtful</a>
                <a class="action next btn btn-info" rel="2" onclick="return next();"><i class="glyphicon glyphicon-chevron-right"></i> Next</a>
                <a class="completed action submit btn btn-danger" onclick="return save_final();"><i class="glyphicon glyphicon-stop"></i> Finished</a>
                <input type="hidden" name="total_questions" id="total_questions" value="<?=$no; ?>">
            </div>
        </div>
        <?=form_close();?>
    </div>
</div>

<script type="text/javascript">
    base_url        = "<?=base_url(); ?>";
    let id_tes          = "<?=$id_tes; ?>";
    let widget          = $(".step");
    let total_widget    = widget.length;
</script>

<script src="<?=base_url()?>assets/dist/js/app/exam/sheet.js"></script>