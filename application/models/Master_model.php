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
     * Data ClassRoom
     */

    public function getDataClass()
    {
        $this->datatables->select('class_id, class_name, department_id, department_name');
        $this->datatables->from('class');
        $this->datatables->join('department', 'department_id=department_id');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'class_id, class_name, department_id, department_name');
        return $this->datatables->generate();
    }

    public function getClassById($id)
    {
        $this->db->where_in('class_id', $id);
        $this->db->order_by('class_name');
        $query = $this->db->get('class')->result();
        return $query;
    }

    /**
     * Data Department
     */

    public function getDataDepartment()
    {
        $this->datatables->select('department_id, department_name');
        $this->datatables->from('department');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'department_id, department_name');
        return $this->datatables->generate();
    }

    public function getDepartmentById($id)
    {
        $this->db->where_in('department_id', $id);
        $this->db->order_by('department_name');
        $query = $this->db->get('department')->result();
        return $query;
    }

    /**
     * Data Student
     */

    public function getDataStudent()
    {
        $this->datatables->select('a.student_id, a.name, a.student_number, a.email, b.class_name, c.department_name');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.student_number) AS ada');
        $this->datatables->from('student a');
        $this->datatables->join('class b', 'a.class_id=b.class_id');
        $this->datatables->join('department c', 'b.department_id=c.department_id');
        return $this->datatables->generate();
    }

    public function getStudentById($id)
    {
        $this->db->select('*');
        $this->db->from('student');
        $this->db->join('class', 'class_id=class_id');
        $this->db->join('department', 'department_id=department_id');
        $this->db->where(['student_id' => $id]);
        return $this->db->get()->row();
    }

    public function getDepartment()
    {
        $this->db->select('department_id, department_name');
        $this->db->from('class');
        $this->db->join('department', 'department_id=department_id');
        $this->db->order_by('department_name', 'ASC');
        $this->db->group_by('department_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllDepartment($id = null)
    {
        if ($id === null) {
            $this->db->order_by('department_name', 'ASC');
            return $this->db->get('department')->result();
        } else {
            $this->db->select('department_id');
            $this->db->from('department_course');
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
            $course = $this->db->get()->result();
            return $course;
        }
    }

    public function getClassByDepartment($id)
    {
        $query = $this->db->get_where('class', array('department_id'=>$id));
        return $query->result();
    }

    /**
     * Data Lecturer
     */

    public function getDataLecturer()
    {
        $this->datatables->select('a.lecturer_id,a.teacher_id, a.lecturer_name, a.email, a.course_id, b.course_name, (SELECT COUNT(id) FROM users WHERE username = a.teacher_id OR email = a.email) AS ada');
        $this->datatables->from('lecturer a');
        $this->datatables->join('course b', 'a.course_id=b.course_id');
        return $this->datatables->generate();
    }

    public function getLecturerById($id)
    {
        $query = $this->db->get_where('lecturer', array('lecturer_id'=>$id));
        return $query->row();
    }

    /**
     * Data Course
     */

    public function getDataCourse()
    {
        $this->datatables->select('course_id, course_name');
        $this->datatables->from('course');
        return $this->datatables->generate();
    }

    public function getAllCourse()
    {
        return $this->db->get('course')->result();
    }

    public function getCourseById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('course_id', $id);
            $this->db->order_by('course_name');
            $query = $this->db->get('course')->result();
        } else {
            $query = $this->db->get_where('course', array('course_id'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data ClassRoom Lecturer
     */

    public function getLecturerClass()
    {
        $this->datatables->select('lecturer_class.id, lecturer.lecturer_id, lecturer.teacher_id, lecturer.lecturer_name, GROUP_CONCAT(class.class_name) as class');
        $this->datatables->from('lecturer_class');
        $this->datatables->join('class', 'class_id=class_id');
        $this->datatables->join('lecturer', 'lecturer_id=lecturer_id');
        $this->datatables->group_by('lecturer.lecturer_name');
        return $this->datatables->generate();
    }

    public function getAllLecturer($id = null)
    {
        $this->db->select('lecturer_id');
        $this->db->from('lecturer_class');
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

    
    public function getAllClass()
    {
        $this->db->select('class_id, class_name, department_name');
        $this->db->from('class');
        $this->db->join('department', 'department_id=department_id');
        $this->db->order_by('class_name');
        return $this->db->get()->result();
    }
    
    public function getClassByLecturer($id)
    {
        $this->db->select('class.class_id');
        $this->db->from('lecturer_class');
        $this->db->join('class', 'lecturer_class.class_id=class.class_id');
        $this->db->where('lecturer_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Department Course
     */

    public function getDepartmentCourse()
    {
        $this->datatables->select('department_course.id, course.course_id, course.course_name, department.department_id, GROUP_CONCAT(department.department_name) as department_name');
        $this->datatables->from('department_course');
        $this->datatables->join('course', 'course_id=course_id');
        $this->datatables->join('department', 'department_id=department_id');
        $this->datatables->group_by('course.course_name');
        return $this->datatables->generate();
    }

    public function getCourse($id = null)
    {
        $this->db->select('course_id');
        $this->db->from('department_course');
        if ($id !== null) {
            $this->db->where_not_in('course_id', [$id]);
        }
        $course = $this->db->get()->result();
        $course_id = [];
        foreach ($course as $d) {
            $course_id[] = $d->course_id;
        }
        if ($course_id === []) {
            $course_id = null;
        }

        $this->db->select('course_id, course_name');
        $this->db->from('course');
        $this->db->where_not_in('course_id', $course_id);
        return $this->db->get()->result();
    }

    public function getDepartmentByIdCourse($id)
    {
        $this->db->select('department.department_id');
        $this->db->from('department_course');
        $this->db->join('department', 'department_course.department_id=department.department_id');
        $this->db->where('course_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}
