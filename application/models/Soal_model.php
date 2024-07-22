<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soal_model extends CI_Model {
    
    public function getDataSoal($id, $lecturer)
    {
        $this->datatables->select('a.id_soal, a.soal, FROM_UNIXTIME(a.created_on) as created_on, FROM_UNIXTIME(a.updated_on) as updated_on, b.nama_matkul, c.lecturer_name');
        $this->datatables->from('tb_soal a');
        $this->datatables->join('matkul b', 'b.id_matkul=a.course_id');
        $this->datatables->join('lecturer c', 'c.lecturer_id=a.lecturer_id');
        if ($id!==null && $lecturer===null) {
            $this->datatables->where('a.course_id', $id);            
        }else if($id!==null && $lecturer!==null){
            $this->datatables->where('a.lecturer_id', $lecturer);
        }
        return $this->datatables->generate();
    }

    public function getSoalById($id)
    {
        return $this->db->get_where('tb_soal', ['id_soal' => $id])->row();
    }

    public function getMatkulDosen($teacher_id)
    {
        $this->db->select('course_id, nama_matkul, lecturer_id, lecturer_name');
        $this->db->join('matkul', 'course_id=id_matkul');
        $this->db->from('lecturer')->where('teacher_id', $teacher_id);
        return $this->db->get()->row();
    }

    public function getAllDosen()
    {
        $this->db->select('*');
        $this->db->from('lecturer a');
        $this->db->join('matkul b', 'a.course_id=b.id_matkul');
        return $this->db->get()->result();
    }
}