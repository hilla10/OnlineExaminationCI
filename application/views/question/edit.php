<div class="row">
    <div class="col-sm-12">    
        <?=form_open_multipart('question/save', array('id'=>'formquestion'), array('method'=>'edit', 'question_id'=>$question->question_id));?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$subtitle?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="lecturer_id" class="control-label">Lecturer (Course)</label>
                                <?php if ($this->ion_auth->is_admin()) : ?>
                                <select required="required" name="lecturer_id" id="lecturer_id" class="select2 form-group" style="width:100% !important">
                                    <option value="" disabled selected>Choose Lecturer</option>
                                    <?php
                                    $sdm = $question->lecturer_id.':'.$question->course_id;
                                    foreach ($lecturer as $d) :
                                        $dm = $d->lecturer_id.':'.$d->course_id;?>
                                        <option <?=$sdm===$dm?"selected":"";?> value="<?=$dm?>"><?=$d->lecturer_name?> (<?=$d->course_name?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="help-block" style="color: #dc3545"><?=form_error('lecturer_id')?></small>
                                <?php else : ?>
                                <input type="hidden" name="lecturer_id" value="<?=$lecturer->lecturer_id;?>">
                                <input type="hidden" name="course_id" value="<?=$lecturer->course_id;?>">
                                <input type="text" readonly="readonly" class="form-control" value="<?=$lecturer->lecturer_name; ?> (<?=$lecturer->course_name; ?>)">
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-sm-12">
                                <label for="question" class="control-label text-center">Question</label>
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <input type="file" name="file_question" class="form-control">
                                        <small class="help-block" style="color: #dc3545"><?=form_error('file_question')?></small>
                                        <?php if (!empty($question->file)) : ?>
                                            <?=display_media('uploads/bank_question/'.$question->file);?>
                                        <?php endif;?>
                                    </div>
                                    <div class="form-group col-sm-9">
                                        <textarea name="question" id="question" class="form-control summernote"><?=$question->question?></textarea>
                                        <small class="help-block" style="color: #dc3545"><?=form_error('question')?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 
                                Membuat perulangan A-E 
                            -->
                            <?php
                            $abjad = ['a', 'b', 'c', 'd', 'e'];
                            foreach ($abjad as $abj) :
                                $ABJ = strtoupper($abj); // Abjad Kapital
                                $file = 'file_'.$abj;
                                $option = 'option_'.$abj;
                            ?>
                            
                            <div class="col-sm-12">
                                <label for="answer_<?= $abj; ?>" class="control-label text-center">Answer <?= $ABJ; ?></label>
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <input type="file" name="<?= $file; ?>" class="form-control">
                                        <small class="help-block" style="color: #dc3545"><?=form_error($file)?></small>
                                        <?php if (!empty($question->$file)) : ?>
                                            <?=display_media('uploads/bank_question/'.$question->$file);?>
                                        <?php endif;?>
                                    </div>
                                    <div class="form-group col-sm-9">
                                        <textarea name="answer_<?= $abj; ?>" id="answer_<?= $abj; ?>" class="form-control summernote"><?=$question->$option?></textarea>
                                        <small class="help-block" style="color: #dc3545"><?=form_error('answer_'.$abj)?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php endforeach; ?>
                            
                            <div class="form-group col-sm-12">
                                <label for="answer" class="control-label">Answer key</label>
                                <select required="required" name="answer" id="answer" class="form-control select2" style="width:100%!important">
                                    <option value="" disabled selected>Choose Answer key</option>
                                    <option <?=$question->answer==="A"?"selected":""?> value="A">A</option>
                                    <option <?=$question->answer==="B"?"selected":""?> value="B">B</option>
                                    <option <?=$question->answer==="C"?"selected":""?> value="C">C</option>
                                    <option <?=$question->answer==="D"?"selected":""?> value="D">D</option>
                                    <option <?=$question->answer==="E"?"selected":""?> value="E">E</option>
                                </select>                
                                <small class="help-block" style="color: #dc3545"><?=form_error('answer')?></small>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="weight" class="control-label">Value Weight</label>
                                <input required="required" value="<?=$question->weight?>" type="number" name="weight" placeholder="Weight Question" id="weight" class="form-control">
                                <small class="help-block" style="color: #dc3545"><?=form_error('weight')?></small>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group pull-right">
                                    <a href="<?=base_url('question')?>" class="btn btn-flat btn-default"><i class="fa fa-arrow-left"></i> Cancel</a>
                                    <button type="submit" id="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Save</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?=form_close();?>
    </div>
</div>