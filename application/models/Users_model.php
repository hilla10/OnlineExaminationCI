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
    // Get the latest google_id and increment it
    $this->db->select_max('id', 'max_id');
    $query = $this->db->get('google_login');
    $row = $query->row();
    
    if ($row && $row->max_id) {
        // Extract the numeric part of the ID and increment it
        $max_id = intval(substr($row->max_id, 2)) + 1;
    } else {
        // If no records exist, start with 1
        $max_id = 1;
    }

    // Generate the new google_id, e.g., G-001
    $new_google_id = 'G-' . str_pad($max_id, 3, '0', STR_PAD_LEFT);

    // Insert the new user into the google_login table with the new google_id
    $data = [
        'id' => $new_google_id,
        'email' => $email,
        'username' => $name,
        'profile_picture' => $profile_picture,
        'created_on' => time(),
        'company' => 'Gust',
    ];
    $this->db->insert('google_login', $data);

    return $new_google_id;
}

}
?>
