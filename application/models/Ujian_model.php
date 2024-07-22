<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
    
    public function getDataUjian($id)
    {
        $this->datatables->select('a.id_ujian, a.token, a.nama_ujian, b.course_name, a.jumlah_soal, CONCAT(a.start_time, " <br/> (", a.waktu, " Minute)") as waktu, a.jenis');
        $this->datatables->from('m_ujian a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        if($id!==null){
            $this->datatables->where('lecturer_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListUjian($id, $class)
    {
        $this->datatables->select("a.id_ujian, e.lecturer_name, d.class_name, a.nama_ujian, b.course_name, a.jumlah_soal, CONCAT(a.start_time, ' <br/> (', a.waktu, ' Minute)') as waktu,  (SELECT COUNT(id) FROM exam h WHERE h.student_id = {$id} AND h.exam_id = a.id_ujian) AS ada");
        $this->datatables->from('m_ujian a');
        $this->datatables->join('course b', 'a.course_id = b.course_id');
        $this->datatables->join('kelas_dosen c', "a.lecturer_id = c.lecturer_id");
        $this->datatables->join('class d', 'c.kelas_id = d.class_id');
        $this->datatables->join('lecturer e', 'e.lecturer_id = c.lecturer_id');
        $this->datatables->where('d.class_id', $class);
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('m_ujian a');
        $this->db->join('lecturer b', 'a.lecturer_id=b.lecturer_id');
        $this->db->join('course c', 'a.course_id=c.course_id');
        $this->db->where('id_ujian', $id);
        return $this->db->get()->row();
    }

    public function getIdDosen($teacher_id)
    {
        $this->db->select('lecturer_id, lecturer_name')->from('lecturer')->where('teacher_id', $teacher_id);
        return $this->db->get()->row();
    }

    public function getJumlahSoal($lecturer)
    {
        $this->db->select('COUNT(id_soal) as jml_soal');
        $this->db->from('tb_soal');
        $this->db->where('lecturer_id', $lecturer);
        return $this->db->get()->row();
    }

    public function getIdMahasiswa($student_number)
    {
        $this->db->select('*');
        $this->db->from('student a');
        $this->db->join('class b', 'a.kelas_id=b.class_id');
        $this->db->join('department c', 'b.department_id=c.department_id');
        $this->db->where('student_number', $student_number);
        return $this->db->get()->row();
    }

    public function HslUjian($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(end_time) as waktu_habis');
        $this->db->from('exam');
        $this->db->where('exam_id', $id);
        $this->db->where('student_id', $mhs);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $ujian = $this->getUjianById($id);
        $order = $ujian->jenis==="Random" ? 'rand()' : 'id_soal';

        $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
        $this->db->from('tb_soal');
        $this->db->where('lecturer_id', $ujian->lecturer_id);
        $this->db->where('course_id', $ujian->course_id);
        $this->db->order_by($order);
        $this->db->limit($ujian->jumlah_soal);
        return $this->db->get()->result();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('tb_soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('answer_list');
        $this->db->from('exam');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->answer_list;
    }

    public function getHasilUjian($teacher_id = null)
    {
        $this->datatables->select('b.id_ujian, b.nama_ujian, b.jumlah_soal, CONCAT(b.waktu, " Minute") as waktu, b.start_time');
        $this->datatables->select('c.course_name, d.lecturer_name');
        $this->datatables->from('exam a');
        $this->datatables->join('m_ujian b', 'a.exam_id = b.id_ujian');
        $this->datatables->join('course c', 'b.course_id = c.course_id');
        $this->datatables->join('lecturer d', 'b.lecturer_id = d.lecturer_id');
        $this->datatables->group_by('b.id_ujian');
        if($teacher_id !== null){
            $this->datatables->where('d.teacher_id', $teacher_id);
        }
        return $this->datatables->generate();
    }

    public function HslUjianById($id, $dt=false)
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
        $this->$db->join('class b', 'a.kelas_id=b.class_id');
        $this->$db->join('department c', 'b.department_id=c.department_id');
        $this->$db->join('exam d', 'a.student_id=d.student_id');
        $this->$db->where(['d.exam_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingScore($id)
    {
        $this->db->select_min('score', 'min_score');
        $this->db->select_max('score', 'max_score');
        $this->db->select_avg('FORMAT(FLOOR(score),0)', 'avg_score');
        $this->db->where('exam_id', $id);
        return $this->db->get('exam')->row();
    }

}