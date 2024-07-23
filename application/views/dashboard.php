<?php if( $this->ion_auth->is_admin() ) : ?>
<div class="row">
    <?php foreach($info_box as $info) : ?>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-<?=$info->box?>">
        <div class="inner">
            <h3><?=$info->total;?></h3>
            <p><?=$info->text;?></p>
        </div>
        <div class="icon">
            <i class="fa fa-<?=$info->icon?>"></i>
        </div>
        <a href="<?=base_url().strtolower($info->title);?>" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
        </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php elseif( $this->ion_auth->in_group('Lecturer') ) : ?>

<div class="row">
    <div class="col-sm-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Account Information</h3>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>Name</th>
                    <td><?=$lecturer->lecturer_name?></td>
                </tr>
                <tr>
                    <th>TeacherID</th>
                    <td><?=$lecturer->teacher_id?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$lecturer->email?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?=$lecturer->course_name?></td>
                </tr>
                <tr>
                    <th>Class List</th>
                    <td>
                        <ol class="pl-4">
                        <?php foreach ($class as $k) : ?>
                            <li><?=$k->class_name?></li>
                        <?php endforeach;?>
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="box box-solid">
            <div class="box-header bg-yellow">
                <h3 class="box-title">Before You Kick Off:</h3>
            </div>
            <div class="box-body">
            <p>Welcome to Online Examination System. Here are some tips in order to get you through the system with an ease.</p>
                <ul class="pl-4">
                    <li>First things first, all the Questions are listed on the "Question Bank section (left hand sidebar menu)"</li>
                    <li>Secondly, you can manage questionaires in order to setup examinations from Question Bank section.</li>
                    <li>Every examination should have its own name, question sets, date and timing details set by LECTURER.</li>
                    <li>You need to copy and share (to students) the TOKEN code, once after creating the Examination record.</li>
                    <li>Once student completes their examination, you'll be able to view their detailed results from "Exam Results" section.</li>
                    <li>Also, the result portion can be downloaded on PDF format.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php else : ?>

<div class="row">
    <div class="col-sm-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Account Information</h3>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>studentNumber</th>
                    <td><?=$student->student_number?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?=$student->name?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?=$student->gender === 'M' ? "Male" : "Female" ;?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$student->email?></td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td><?=$student->department_name?></td>
                </tr>
                <tr>
                    <th>Class</th>
                    <td><?=$student->class_name?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="box box-solid">
            <div class="box-header bg-yellow">
                <h3 class="box-title">Before You Kick Off:</h3>
            </div>
            <div class="box-body">
            <p>Welcome to Online Examination System. Here are some tips in order to get you through the system with an ease.</p>
                <ul class="pl-4">
                    <li>First things first, all the examinations are listed on the "Exam Section (left hand sidebar menu)"</li>
                    <li>Secondly, you'll only be able to see the examinations according to your course-department.</li>
                    <li>Every examination has its own time limit, which is set by the lecturer.</li>
                    <li>You're required to enter the TOKEN number in order to start the online examination.</li>
                    <li>You need to enter/start examination within the given time frame (date and time) ELSE you can't join the examination.</li>
                    <li>Once you complete your exams, you'll ONLY be able to view your results.</li>
                    <li>For re-exam_history, please consult with your respective lecturer!</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>