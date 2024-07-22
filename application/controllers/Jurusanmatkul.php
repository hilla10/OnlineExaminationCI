<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JurusanMatkul extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}else if (!$this->ion_auth->is_admin()){
			show_error('Only Administrators are authorized to access this page, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');			
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('','');
	}

	public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Course Department',
			'subjudul'=> 'Data Course Department'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/departmentmatkul/data');
		$this->load->view('_templates/dashboard/_footer.php');
    }

    public function data()
    {
        $this->output_json($this->master->getJurusanMatkul(), false);
	}

	public function getJurusanId($id)
	{
		$this->output_json($this->master->getAllJurusan($id));		
	}
	
	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Add Course Department',
			'subjudul'	=> 'Add Course Dept. Data',
			'course'	=> $this->master->getMatkul()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/departmentmatkul/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 			=> $this->ion_auth->user()->row(),
			'judul'			=> 'Edit Course Dept.',
			'subjudul'		=> 'Edit Data Course Dept.',
			'course'		=> $this->master->getMatkulById($id, true),
			'course_id'		=> $id,
			'all_department'	=> $this->master->getAllJurusan(),
			'department'		=> $this->master->getJurusanByIdMatkul($id)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/departmentmatkul/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->form_validation->set_rules('course_id', 'Course', 'required');
		$this->form_validation->set_rules('department_id[]', 'Department', 'required');
	
		if($this->form_validation->run() == FALSE){
			$data = [
				'status'	=> false,
				'errors'	=> [
					'course_id' => form_error('course_id'),
					'department_id[]' => form_error('department_id[]'),
				]
			];
			$this->output_json($data);
		}else{
			$course_id 	= $this->input->post('course_id', true);
			$department_id = $this->input->post('department_id', true);
			$input = [];
			foreach ($department_id as $key => $val) {
				$input[] = [
					'course_id' 	=> $course_id,
					'department_id'  	=> $val
				];
			}
			if($method==='add'){
				$action = $this->master->create('department_matkul', $input, true);
			}else if($method==='edit'){
				$id = $this->input->post('course_id', true);
				$this->master->delete('department_matkul', $id, 'course_id');
				$action = $this->master->create('department_matkul', $input, true);
			}
			$data['status'] = $action ? TRUE : FALSE ;
		}
		$this->output_json($data);
	}

	public function delete()
    {
        $chk = $this->input->post('checked', true);
        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('department_matkul', $chk, 'course_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}
}