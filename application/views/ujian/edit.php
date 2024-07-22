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
                    <p><?=$matkul->nama_matkul?></p>
                </div>
                <div class="alert bg-purple">
                    <h4>Lecturer <i class="fa fa-address-book-o pull-right"></i></h4>
                    <p><?=$lecturer->lecturer_name?></p>
                </div>
            </div>
            <div class="col-sm-4">
                <?=form_open('ujian/save', array('id'=>'formujian'), array('method'=>'edit','lecturer_id'=>$lecturer->lecturer_id, 'course_id'=>$matkul->course_id, 'id_ujian'=>$ujian->id_ujian))?>
                <div class="form-group">
                    <label for="nama_ujian">Exam Name</label>
                    <input value="<?=$ujian->nama_ujian?>" autofocus="autofocus" onfocus="this.select()" placeholder="Nama Ujian" type="text" class="form-control" name="nama_ujian">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="jumlah_soal">Number of Questions</label>
                    <input value="<?=$ujian->jumlah_soal?>" placeholder="Number of Questions" type="number" class="form-control" name="jumlah_soal">
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
                    <label for="waktu">Time</label>
                    <input value="<?=$ujian->waktu?>" placeholder="In Minute" type="number" class="form-control" name="waktu">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="jenis">Question Pattern</label>
                    <select name="jenis" class="form-control">
                        <option value="" disabled selected>--- Choose ---</option>
                        <option <?=$ujian->jenis==="Random"?"selected":"";?> value="Random">Random Question</option>
                        <option <?=$ujian->jenis==="Sort"?"selected":"";?> value="Sort">Sort Question</option>
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
    var terlambat = '<?=$ujian->terlambat?>';
</script>

<script src="<?=base_url()?>assets/dist/js/app/ujian/edit.js"></script>