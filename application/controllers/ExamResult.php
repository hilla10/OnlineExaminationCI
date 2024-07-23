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
		$this->load->model('Exam_model', 'exam');
		
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

		$this->output_json($this->exam->getExamResults($nip_lecturer), false);
	}

	public function ScoreMhs($id)
	{
		$this->output_json($this->exam->ExamResultsByID($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'title'	=> 'Exam',
			'subtitle'=> 'Exam results',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/result');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function detail($id)
	{
		$exam = $this->exam->getExamById($id);
		$score = $this->exam->bandingScore($id);

		$data = [
			'user' => $this->user,
			'title'	=> 'Exam',
			'subtitle'=> 'Detail Exam results',
			'exam'	=> $exam,
			'score'	=> $score
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/detail_result');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function print($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->exam->getIdStudent($this->user->username);
		$result 	= $this->exam->examResults($id, $mhs->student_id)->row();
		$exam 	= $this->exam->getExamById($id);
		
		$data = [
			'exam' => $exam,
			'result' => $result,
			'mhs'	=> $mhs
		];
		
		$this->load->view('exam/print', $data);
	}

	public function print_detail($id)
	{
		$this->load->library('Pdf');

		$exam = $this->exam->getExamById($id);
		$score = $this->exam->bandingScore($id);
		$result = $this->exam->ExamResultsByID($id)->result();

		$data = [
			'exam'	=> $exam,
			'score'	=> $score,
			'result'	=> $result
		];

		$this->load->view('exam/print_detail', $data);
	}
	
}