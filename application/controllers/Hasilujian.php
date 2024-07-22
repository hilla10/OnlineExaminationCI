<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HasilUjian extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		
		$this->load->library(['datatables']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('Ujian_model', 'ujian');
		
		$this->user = $this->ion_auth->user()->row();
	}

	public function output_json($data, $encode = true)
	{
		if($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$nip_dosen = null;
		
		if( $this->ion_auth->in_group('Lecturer') ) {
			$nip_dosen = $this->user->username;
		}

		$this->output_json($this->ujian->getHasilUjian($nip_dosen), false);
	}

	public function ScoreMhs($id)
	{
		$this->output_json($this->ujian->HslUjianById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Exam results',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function detail($id)
	{
		$ujian = $this->ujian->getUjianById($id);
		$score = $this->ujian->bandingScore($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Exam',
			'subjudul'=> 'Detail Exam results',
			'ujian'	=> $ujian,
			'score'	=> $score
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->ujian->getIdMahasiswa($this->user->username);
		$hasil 	= $this->ujian->HslUjian($id, $mhs->student_id)->row();
		$ujian 	= $this->ujian->getUjianById($id);
		
		$data = [
			'ujian' => $ujian,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];
		
		$this->load->view('ujian/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$ujian = $this->ujian->getUjianById($id);
		$score = $this->ujian->bandingScore($id);
		$hasil = $this->ujian->HslUjianById($id)->result();

		$data = [
			'ujian'	=> $ujian,
			'score'	=> $score,
			'hasil'	=> $hasil
		];

		$this->load->view('ujian/cetak_detail', $data);
	}
	
}