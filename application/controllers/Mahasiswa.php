<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
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
			'judul'	=> 'Student',
			'subjudul' => 'Data Student'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataMahasiswa(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Student',
			'subjudul' => 'Add Student Data'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$mhs = $this->master->getMahasiswaById($id);
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Student',
			'subjudul'	=> 'Edit Student Data',
			'department'	=> $this->master->getJurusan(),
			'class'		=> $this->master->getKelasByJurusan($mhs->department_id),
			'student' => $mhs
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/student/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function validasi_mahasiswa($method)
	{
		$student_id 	= $this->input->post('student_id', true);
		$student_number 			= $this->input->post('student_number', true);
		$email 			= $this->input->post('email', true);
		if ($method == 'add') {
			$u_nim = '|is_unique[student.student_number]';
			$u_email = '|is_unique[student.email]';
		} else {
			$dbdata 	= $this->master->getMahasiswaById($student_id);
			$u_nim		= $dbdata->student_number === $student_number ? "" : "|is_unique[student.student_number]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[student.email]";
		}
		$this->form_validation->set_rules('student_number', 'NIM', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_nim);
		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		$this->form_validation->set_rules('department', 'Dept.', 'required');
		$this->form_validation->set_rules('class', 'Class', 'required');

		$this->form_validation->set_message('required', 'Kolom {field} wajib diisi');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->validasi_mahasiswa($method);

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'student_number' => form_error('student_number'),
					'name' => form_error('name'),
					'email' => form_error('email'),
					'gender' => form_error('gender'),
					'department' => form_error('department'),
					'class' => form_error('class'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'student_number' 			=> $this->input->post('student_number', true),
				'email' 		=> $this->input->post('email', true),
				'name' 			=> $this->input->post('name', true),
				'gender' => $this->input->post('gender', true),
				'kelas_id' 		=> $this->input->post('class', true),
			];
			if ($method === 'add') {
				$action = $this->master->create('student', $input);
			} else if ($method === 'edit') {
				$id = $this->input->post('student_id', true);
				$action = $this->master->update('student', $input, 'student_id', $id);
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
			if ($this->master->delete('student', $chk, 'student_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getMahasiswaById($id);
		$name = explode(' ', $data->name);
		$first_name = $name[0];
		$last_name = end($name);

		$username = $data->student_number;
		$password = $data->student_number;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('3'); // Sets user to lecturer.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Username not available (already used).'
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
			'judul'	=> 'Student',
			'subjudul' => 'Import Student Data',
			'class' => $this->master->getAllKelas()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/student/import');
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
					'student_number' => $sheetData[$i][0],
					'name' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'gender' => $sheetData[$i][3],
					'kelas_id' => $sheetData[$i][4]
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
				'student_number' => $d->student_number,
				'name' => $d->name,
				'email' => $d->email,
				'gender' => $d->gender,
				'kelas_id' => $d->kelas_id
			];
		}

		$save = $this->master->create('student', $data, true);
		if ($save) {
			redirect('student');
		} else {
			redirect('student/import');
		}
	}
}
