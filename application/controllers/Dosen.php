<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecturer extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Only Administrators are authorized to access this page, <a href="' . base_url('dashboard') . '">Back to main menu</a>', 403, 'Forbidden Access');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Lecturer',
			'subjudul' => 'Lecturer Data'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/lecturer/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataDosen(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Add Lecturer',
			'subjudul' => 'Add Lecturer Data',
			'course'	=> $this->master->getAllMatkul()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/lecturer/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Edit Lecturer',
			'subjudul'	=> 'Edit Lecturer Data',
			'course'	=> $this->master->getAllMatkul(),
			'data' 		=> $this->master->getDosenById($id)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/lecturer/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$method 	= $this->input->post('method', true);
		$lecturer_id 	= $this->input->post('lecturer_id', true);
		$teacher_id 		= $this->input->post('teacher_id', true);
		$lecturer_name = $this->input->post('lecturer_name', true);
		$email 		= $this->input->post('email', true);
		$course 	= $this->input->post('course', true);
		if ($method == 'add') {
			$u_nip = '|is_unique[lecturer.teacher_id]';
			$u_email = '|is_unique[lecturer.email]';
		} else {
			$dbdata 	= $this->master->getDosenById($lecturer_id);
			$u_nip		= $dbdata->teacher_id === $teacher_id ? "" : "|is_unique[lecturer.teacher_id]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[lecturer.email]";
		}
		$this->form_validation->set_rules('teacher_id', 'NIP', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_nip);
		$this->form_validation->set_rules('lecturer_name', 'Nama Lecturer', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('course', 'Mata Kuliah', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'teacher_id' => form_error('teacher_id'),
					'lecturer_name' => form_error('lecturer_name'),
					'email' => form_error('email'),
					'course' => form_error('course'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'teacher_id'			=> $teacher_id,
				'lecturer_name' 	=> $lecturer_name,
				'email' 		=> $email,
				'course_id' 	=> $course
			];
			if ($method === 'add') {
				$action = $this->master->create('lecturer', $input);
			} else if ($method === 'edit') {
				$action = $this->master->update('lecturer', $input, 'lecturer_id', $lecturer_id);
			}

			if ($action) {
				$this->output_json(['status' => true]);
			} else {
				$this->output_json(['status' => false]);
			}
		}
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('lecturer', $chk, 'lecturer_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getDosenById($id);
		$name = explode(' ', $data->lecturer_name);
		$first_name = $name[0];
		$last_name = end($name);

		$username = $data->teacher_id;
		$password = $data->teacher_id;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('2'); // Sets user to lecturer.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Username is not available (already used).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'Email is not available (already in use).'
			];
		} else {
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$data = [
				'status'	=> true,
				'msg'	 => 'User created successfully. NIP is used as a password at login.'
			];
		}
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Lecturer',
			'subjudul' => 'Import Lecturer Data',
			'course' => $this->master->getAllMatkul()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/lecturer/import');
		$this->load->view('_templates/dashboard/_footer');
	}
	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload_file')) {
			$error = $this->upload->display_errors();
			echo $error;
			die;
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');

			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					echo "unknown file ext";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'teacher_id' => $sheetData[$i][0],
					'lecturer_name' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'course_id' => $sheetData[$i][3]
				];
			}

			unlink($file);

			$this->import($data);
		}
	}

	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = [
				'teacher_id' => $d->teacher_id,
				'lecturer_name' => $d->lecturer_name,
				'email' => $d->email,
				'course_id' => $d->course_id
			];
		}

		$save = $this->master->create('lecturer', $data, true);
		if ($save) {
			redirect('lecturer');
		} else {
			redirect('lecturer/import');
		}
	}
}
