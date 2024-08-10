<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function getDatausers($id = null) {
        $this->datatables->select('users.id, username, first_name, last_name, email, FROM_UNIXTIME(created_on) as created_on, last_login, active, groups.name as level');
        $this->datatables->from('users_groups');
        $this->datatables->join('users', 'users_groups.user_id=users.id');
        $this->datatables->join('groups', 'users_groups.group_id=groups.id');
        if ($id !== null) {
            $this->datatables->where('users.id !=', $id);
        }
        return $this->datatables->generate();
    }

        public function get_google_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('google_login');
        return $query->row();
    }

    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('google_login');
        return $query->row();
    }
   public function get_lecturer_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('lecturer'); // Assuming 'lecturers' is your table name
        return $query->row();
    }

public function register_user($email, $name, $profile_picture) {
    $data = [
        'email' => $email,
        'username' => $name,
        'profile_picture' => $profile_picture,
        'created_on' => time()
    ];
    $this->db->insert('google_login', $data);
    return $this->db->insert_id();
}



}
?>
