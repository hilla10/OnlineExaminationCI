<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function __construct()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    }

    public function create($table, $data, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->insert($table, $data);
        } else {
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
        } else {
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data Kelas
     */

    public function getDataKelas()
    {
        $this->datatables->select('id_kelas, nama_kelas, department_id, department_name');
        $this->datatables->from('kelas');
        $this->datatables->join('department', 'department_id=department_id');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_kelas, nama_kelas, department_id, department_name');
        return $this->datatables->generate();
    }

    public function getKelasById($id)
    {
        $this->db->where_in('id_kelas', $id);
        $this->db->order_by('nama_kelas');
        $query = $this->db->get('kelas')->result();
        return $query;
    }

    /**
     * Data Jurusan
     */

    public function getDataJurusan()
    {
        $this->datatables->select('department_id, department_name');
        $this->datatables->from('department');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'department_id, department_name');
        return $this->datatables->generate();
    }

    public function getJurusanById($id)
    {
        $this->db->where_in('department_id', $id);
        $this->db->order_by('department_name');
        $query = $this->db->get('department')->result();
        return $query;
    }

    /**
     * Data Mahasiswa
     */

    public function getDataMahasiswa()
    {
        $this->datatables->select('a.id_mahasiswa, a.nama, a.nim, a.email, b.nama_kelas, c.department_name');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nim) AS ada');
        $this->datatables->from('mahasiswa a');
        $this->datatables->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->datatables->join('department c', 'b.department_id=c.department_id');
        return $this->datatables->generate();
    }

    public function getMahasiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa');
        $this->db->join('kelas', 'kelas_id=id_kelas');
        $this->db->join('department', 'department_id=department_id');
        $this->db->where(['id_mahasiswa' => $id]);
        return $this->db->get()->row();
    }

    public function getJurusan()
    {
        $this->db->select('department_id, department_name');
        $this->db->from('kelas');
        $this->db->join('department', 'department_id=department_id');
        $this->db->order_by('department_name', 'ASC');
        $this->db->group_by('department_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllJurusan($id = null)
    {
        if ($id === null) {
            $this->db->order_by('department_name', 'ASC');
            return $this->db->get('department')->result();
        } else {
            $this->db->select('department_id');
            $this->db->from('jurusan_matkul');
            $this->db->where('course_id', $id);
            $department = $this->db->get()->result();
            $department_id = [];
            foreach ($department as $j) {
                $department_id[] = $j->department_id;
            }
            if ($department_id === []) {
                $department_id = null;
            }
            
            $this->db->select('*');
            $this->db->from('department');
            $this->db->where_not_in('department_id', $department_id);
            $matkul = $this->db->get()->result();
            return $matkul;
        }
    }

    public function getKelasByJurusan($id)
    {
        $query = $this->db->get_where('kelas', array('department_id'=>$id));
        return $query->result();
    }

    /**
     * Data Lecturer
     */

    public function getDataDosen()
    {
        $this->datatables->select('a.lecturer_id,a.teacher_id, a.lecturer_name, a.email, a.course_id, b.nama_matkul, (SELECT COUNT(id) FROM users WHERE username = a.teacher_id OR email = a.email) AS ada');
        $this->datatables->from('lecturer a');
        $this->datatables->join('matkul b', 'a.course_id=b.id_matkul');
        return $this->datatables->generate();
    }

    public function getDosenById($id)
    {
        $query = $this->db->get_where('lecturer', array('lecturer_id'=>$id));
        return $query->row();
    }

    /**
     * Data Matkul
     */

    public function getDataMatkul()
    {
        $this->datatables->select('id_matkul, nama_matkul');
        $this->datatables->from('matkul');
        return $this->datatables->generate();
    }

    public function getAllMatkul()
    {
        return $this->db->get('matkul')->result();
    }

    public function getMatkulById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('id_matkul', $id);
            $this->db->order_by('nama_matkul');
            $query = $this->db->get('matkul')->result();
        } else {
            $query = $this->db->get_where('matkul', array('id_matkul'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data Kelas Lecturer
     */

    public function getKelasDosen()
    {
        $this->datatables->select('kelas_dosen.id, lecturer.lecturer_id, lecturer.teacher_id, lecturer.lecturer_name, GROUP_CONCAT(kelas.nama_kelas) as kelas');
        $this->datatables->from('kelas_dosen');
        $this->datatables->join('kelas', 'kelas_id=id_kelas');
        $this->datatables->join('lecturer', 'lecturer_id=lecturer_id');
        $this->datatables->group_by('lecturer.lecturer_name');
        return $this->datatables->generate();
    }

    public function getAllDosen($id = null)
    {
        $this->db->select('lecturer_id');
        $this->db->from('kelas_dosen');
        if ($id !== null) {
            $this->db->where_not_in('lecturer_id', [$id]);
        }
        $lecturer = $this->db->get()->result();
        $lecturer_id = [];
        foreach ($lecturer as $d) {
            $lecturer_id[] = $d->lecturer_id;
        }
        if ($lecturer_id === []) {
            $lecturer_id = null;
        }

        $this->db->select('lecturer_id, teacher_id, lecturer_name');
        $this->db->from('lecturer');
        $this->db->where_not_in('lecturer_id', $lecturer_id);
        return $this->db->get()->result();
    }

    
    public function getAllKelas()
    {
        $this->db->select('id_kelas, nama_kelas, department_name');
        $this->db->from('kelas');
        $this->db->join('department', 'department_id=department_id');
        $this->db->order_by('nama_kelas');
        return $this->db->get()->result();
    }
    
    public function getKelasByDosen($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_dosen');
        $this->db->join('kelas', 'kelas_dosen.kelas_id=kelas.id_kelas');
        $this->db->where('lecturer_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Jurusan Matkul
     */

    public function getJurusanMatkul()
    {
        $this->datatables->select('jurusan_matkul.id, matkul.id_matkul, matkul.nama_matkul, department.department_id, GROUP_CONCAT(department.department_name) as department_name');
        $this->datatables->from('jurusan_matkul');
        $this->datatables->join('matkul', 'course_id=id_matkul');
        $this->datatables->join('department', 'department_id=department_id');
        $this->datatables->group_by('matkul.nama_matkul');
        return $this->datatables->generate();
    }

    public function getMatkul($id = null)
    {
        $this->db->select('course_id');
        $this->db->from('jurusan_matkul');
        if ($id !== null) {
            $this->db->where_not_in('course_id', [$id]);
        }
        $matkul = $this->db->get()->result();
        $id_matkul = [];
        foreach ($matkul as $d) {
            $id_matkul[] = $d->course_id;
        }
        if ($id_matkul === []) {
            $id_matkul = null;
        }

        $this->db->select('id_matkul, nama_matkul');
        $this->db->from('matkul');
        $this->db->where_not_in('id_matkul', $id_matkul);
        return $this->db->get()->result();
    }

    public function getJurusanByIdMatkul($id)
    {
        $this->db->select('department.department_id');
        $this->db->from('jurusan_matkul');
        $this->db->join('department', 'jurusan_matkul.department_id=department.department_id');
        $this->db->where('course_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}
