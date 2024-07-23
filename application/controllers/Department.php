<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Department extends CI_Controller
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
			'title'	=> 'Department',
			'subtitle' => 'Data Department'
		];
		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/department/data');
		$this->load->view('_templates/dashboard/_footer');
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'title'		=> 'Add Department',
			'subtitle'	=> 'Add Department Data',
			'rate'	=> $this->input->post('rate', true)
		];
		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/department/add');
		$this->load->view('_templates/dashboard/_footer');
	}

	public function data()
	{
		$this->output_json($this->master->getDataDepartment(), false);
	}

	public function edit()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			redirect('department');
		} else {
			$department = $this->master->getDepartmentById($chk);
			$data = [
				'user' 		=> $this->ion_auth->user()->row(),
				'title'		=> 'Edit Department',
				'subtitle'	=> 'Edit Department Data',
				'department'	=> $department
			];
			$this->load->view('_templates/dashboard/_header', $data);
			$this->load->view('master/department/edit');
			$this->load->view('_templates/dashboard/_footer');
		}
	}

	public function save()
	{
		$rows = count($this->input->post('department_name', true));
		$mode = $this->input->post('mode', true);
		for ($i = 1; $i <= $rows; $i++) {
			$department_name = 'department_name[' . $i . ']';
			$this->form_validation->set_rules($department_name, 'Dept.', 'required');
			$this->form_validation->set_message('required', '{field} Required');

			if ($this->form_validation->run() === FALSE) {
				$error[] = [
					$department_name => form_error($department_name)
				];
				$status = FALSE;
			} else {
				switch ($mode) {
					case 'add':
						$insert[] = [
							'department_name' => $this->input->post($department_name, true)
						];
						break;
					case 'edit':
						$update[] = [
							'department_id' => $this->input->post("department_id[$i]", true),
							'department_name' => $this->input->post($department_name, true)
						];
						break;
				}
				$status = TRUE;
			}
		}
		if ($status) {
			switch ($mode) {
				case 'add':
					$this->master->create('department', $insert, true);
					$data['insert'] = $insert;
					break;
				case 'edit':
					$this->master->update('department', $update, 'department_id', null, true);
					$data['update'] = $update;
					break;
			}
		} else {
			if (isset($error)) {
				$data['errors'] = $error;
			}
		}
		$data['status'] = $status;
		$this->output_json($data);
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('department', $chk, 'department_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function loadDepartment()
	{
		$data = $this->master->getDepartment();
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'title'	=> 'Department',
			'subtitle' => 'Import Department'
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/department/import');
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
			$department = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				if ($sheetData[$i][0] != null) {
					$department[] = $sheetData[$i][0];
				}
			}

			unlink($file);

			$this->import($department);
		}
	}
	public function do_import()
	{
		$data = json_decode($this->input->post('department', true));
		$department = [];
		foreach ($data as $j) {
			$department[] = ['department_name' => $j];
		}

		$save = $this->master->create('department', $department, true);
		if ($save) {
			redirect('department');
		} else {
			redirect('department/import');
		}
	}
}
