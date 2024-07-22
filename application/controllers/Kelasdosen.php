<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KelasDosen extends CI_Controller {

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
			'judul'	=> 'Lecturer Class',
			'subjudul'=> 'Data Lecturer Class'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/kelasdosen/data');
		$this->load->view('_templates/dashboard/_footer.php');
    }

    public function data()
    {
        $this->output_json($this->master->getKelasDosen(), false);
	}
	
	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Add Lecturer Class',
			'subjudul'	=> 'Add Lecturer Class Data',
			'lecturer'		=> $this->master->getAllDosen(),
			'kelas'	    => $this->master->getAllKelas()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/kelasdosen/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 			=> $this->ion_auth->user()->row(),
			'judul'			=> 'Edit Class Lecturer',
			'subjudul'		=> 'Edit Lecturer Class Data',
			'lecturer'			=> $this->master->getDosenById($id),
			'lecturer_id'		=> $id,
			'all_kelas'	    => $this->master->getAllKelas(),
			'kelas'		    => $this->master->getKelasByDosen($id)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/kelasdosen/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->form_validation->set_rules('lecturer_id', 'Lecturer', 'required');
		$this->form_validation->set_rules('kelas_id[]', 'Class', 'required');
	
		if($this->form_validation->run() == FALSE){
			$data = [
				'status'	=> false,
				'errors'	=> [
					'lecturer_id' => form_error('lecturer_id'),
					'kelas_id[]' => form_error('kelas_id[]'),
				]
			];
			$this->output_json($data);
		}else{
			$lecturer_id = $this->input->post('lecturer_id', true);
			$kelas_id = $this->input->post('kelas_id', true);
			$input = [];
			foreach ($kelas_id as $key => $val) {
				$input[] = [
					'lecturer_id'  => $lecturer_id,
					'kelas_id' => $val
				];
			}
			if($method==='add'){
				$action = $this->master->create('kelas_dosen', $input, true);
			}else if($method==='edit'){
				$id = $this->input->post('lecturer_id', true);
				$this->master->delete('kelas_dosen', $id, 'lecturer_id');
				$action = $this->master->create('kelas_dosen', $input, true);
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
            if($this->master->delete('kelas_dosen', $chk, 'lecturer_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}
}