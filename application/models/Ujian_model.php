<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
    
    public function getDataUjian($id)
    {
        $this->datatables->select('a.id_ujian, a.token, a.nama_ujian, b.nama_matkul, a.jumlah_soal, CONCAT(a.start_time, " <br/> (", a.waktu, " Minute)") as waktu, a.jenis');
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matkul b', 'a.course_id = b.id_matkul');
        if($id!==null){
            $this->datatables->where('lecturer_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListUjian($id, $kelas)
    {
        $this->datatables->select("a.id_ujian, e.lecturer_name, d.nama_kelas, a.nama_ujian, b.nama_matkul, a.jumlah_soal, CONCAT(a.start_time, ' <br/> (', a.waktu, ' Minute)') as waktu,  (SELECT COUNT(id) FROM exam h WHERE h.student_id = {$id} AND h.exam_id = a.id_ujian) AS ada");
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matkul b', 'a.course_id = b.id_matkul');
        $this->datatables->join('kelas_dosen c', "a.lecturer_id = c.lecturer_id");
        $this->datatables->join('kelas d', 'c.kelas_id = d.id_kelas');
        $this->datatables->join('lecturer e', 'e.lecturer_id = c.lecturer_id');
        $this->datatables->where('d.id_kelas', $kelas);
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('m_ujian a');
        $this->db->join('lecturer b', 'a.lecturer_id=b.lecturer_id');
        $this->db->join('matkul c', 'a.course_id=c.id_matkul');
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

    public function getIdMahasiswa($nim)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa a');
        $this->db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->db->where('nim', $nim);
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
        $this->datatables->select('c.nama_matkul, d.lecturer_name');
        $this->datatables->from('exam a');
        $this->datatables->join('m_ujian b', 'a.exam_id = b.id_ujian');
        $this->datatables->join('matkul c', 'b.course_id = c.id_matkul');
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
        
        $this->$db->select('d.id, a.nama, b.nama_kelas, c.nama_jurusan, d.correct_count, d.score');
        $this->$db->from('mahasiswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->$db->join('exam d', 'a.id_mahasiswa=d.student_id');
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