<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam_model extends CI_Model {
    
    public function getDataExam($id)
    {
        $this->datatables->select('a.exam_id, a.token, a.exam_name, b.course_name, a.total_questions, CONCAT(a.start_time, " <br/> (", a.duration, " Minute)") as duration, a.type');
        $this->datatables->from('exam a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        if($id!==null){
            $this->datatables->where('lecturer_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListExam($id, $class)
    {
        $this->datatables->select("a.exam_id, e.lecturer_name, d.class_name, a.exam_name, b.course_name, a.total_questions, CONCAT(a.start_time, ' <br/> (', a.duration, ' Minute)') as duration,  (SELECT COUNT(id) FROM exam_history h WHERE h.student_id = {$id} AND h.exam_id = a.exam_id) AS ada");
        $this->datatables->from('exam a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        $this->datatables->join('lecturer_class c', "a.lecturer_id = c.lecturer_id");
        $this->datatables->join('class d', 'c.class_id = d.class_id');
        $this->datatables->join('lecturer e', 'e.lecturer_id = c.lecturer_id');
        $this->datatables->where('d.class_id', $class);
        return $this->datatables->generate();
    }

    public function getExamById($id)
    {
        $this->db->select('*');
        $this->db->from('exam a');
        $this->db->join('lecturer b', 'a.lecturer_id=b.lecturer_id');
        $this->db->join('course c', 'a.course_id=c.course_id');
        $this->db->where('exam_id', $id);
        return $this->db->get()->row();
    }

    public function getIdLecturer($teacher_id)
    {
        $this->db->select('lecturer_id, lecturer_name')->from('lecturer')->where('teacher_id', $teacher_id);
        return $this->db->get()->row();
    }

    public function getTotalQuestions($lecturer)
    {
        $this->db->select('COUNT(question_id) as total_questions');
        $this->db->from('tb_question');
        $this->db->where('lecturer_id', $lecturer);
        return $this->db->get()->row();
    }

    public function getIdStudent($student_number)
    {
        $this->db->select('*');
        $this->db->from('student a');
        $this->db->join('class b', 'a.class_id=b.class_id');
        $this->db->join('department c', 'b.department_id=c.department_id');
        $this->db->where('student_number', $student_number);
        return $this->db->get()->row();
    }

    public function examResults($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(end_time) as times_up');
        $this->db->from('exam_history');
        $this->db->where('exam_id', $id);
        $this->db->where('student_id', $mhs);
        return $this->db->get();
    }

    public function getQuestion($id)
    {
        $save_one = $this->getExamById($id);
        $order = $save_one->type==="Random" ? 'rand()' : 'question_id';

        $this->db->select('question_id, question, file, file_type, option_a, option_b, option_c, option_d, option_e, answer');
        $this->db->from('tb_question');
        $this->db->where('lecturer_id', $save_one->lecturer_id);
        $this->db->where('course_id', $save_one->course_id);
        $this->db->order_by($order);
        $this->db->limit($save_one->total_questions);
        return $this->db->get()->result();
    }

    public function ambilQuestion($pc_question_order1, $pc_question_order_arr)
    {
        $this->db->select("*, {$pc_question_order1} AS answer");
        $this->db->from('tb_question');
        $this->db->where('question_id', $pc_question_order_arr);
        return $this->db->get()->row();
    }

    public function getAnswer($id_tes)
    {
        $this->db->select('answer_list');
        $this->db->from('exam_history');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->answer_list;
    }

    public function getExamResults($teacher_id = null)
    {
        $this->datatables->select('b.exam_id, b.exam_name, b.total_questions, CONCAT(b.duration, " Minute") as duration, b.start_time');
        $this->datatables->select('c.course_name, d.lecturer_name');
        $this->datatables->from('exam_history a');
        $this->datatables->join('exam b', 'a.exam_id = b.exam_id');
        $this->datatables->join('course c', 'b.course_id = c.course_id');
        $this->datatables->join('lecturer d', 'b.lecturer_id = d.lecturer_id');
        $this->datatables->group_by('b.exam_id');
        if($teacher_id !== null){
            $this->datatables->where('d.teacher_id', $teacher_id);
        }
        return $this->datatables->generate();
    }

    public function ExamResultsByID($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.name, b.class_name, c.department_name, d.correct_count, d.score');
        $this->$db->from('student a');
        $this->$db->join('class b', 'a.class_id=b.class_id');
        $this->$db->join('department c', 'b.department_id=c.department_id');
        $this->$db->join('exam_history d', 'a.student_id=d.student_id');
        $this->$db->where(['d.exam_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingScore($id)
    {
        $this->db->select_min('score', 'min_score');
        $this->db->select_max('score', 'max_score');
        $this->db->select_avg('FORMAT(FLOOR(score),0)', 'avg_score');
        $this->db->where('exam_id', $id);
        return $this->db->get('exam_history')->row();
    }

}