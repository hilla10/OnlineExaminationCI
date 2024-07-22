<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class examResult extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		
		$this->load->library(['datatables']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('Exam_model', 'save_one');
		
		$this->user = $this->ion_auth->user()->row();
	}

	public function output_json($data, $encode = true)
	{
		if($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$nip_lecturer = null;
		
		if( $this->ion_auth->in_group('Lecturer') ) {
			$nip_lecturer = $this->user->username;
		}

		$this->output_json($this->save_one->getExamResults($nip_lecturer), false);
	}

	public function ScoreMhs($id)
	{
		$this->output_json($this->save_one->ExamResultsByID($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Exam results',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('save_one/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function detail($id)
	{
		$save_one = $this->save_one->getExamById($id);
		$score = $this->save_one->bandingScore($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Detail Exam results',
			'save_one'	=> $save_one,
			'score'	=> $score
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('save_one/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->save_one->getIdStudent($this->user->username);
		$hasil 	= $this->save_one->examResults($id, $mhs->student_id)->row();
		$save_one 	= $this->save_one->getExamById($id);
		
		$data = [
			'save_one' => $save_one,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];
		
		$this->load->view('save_one/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$save_one = $this->save_one->getExamById($id);
		$score = $this->save_one->bandingScore($id);
		$hasil = $this->save_one->ExamResultsByID($id)->result();

		$data = [
			'save_one'	=> $save_one,
			'score'	=> $score,
			'hasil'	=> $hasil
		];

		$this->load->view('save_one/cetak_detail', $data);
	}
	
}