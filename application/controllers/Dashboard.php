<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->model('Dashboard_model', 'dashboard');
		$this->user = $this->ion_auth->user()->row();
	}

	public function admin_box()
	{
		$box = [
			[
				'box' 		=> 'yellow',
				'total' 	=> $this->dashboard->total('department'),
				'title'		=> 'department',
				'text'      => 'Department',
				'icon'		=> 'th-large'
			],
			[
				'box' 		=> 'green',
				'total' 	=> $this->dashboard->total('kelas'),
				'title'		=> 'kelas',
				'text'      => 'Class',
				'icon'		=> 'building-o'
			],
			[
				'box' 		=> 'blue',
				'total' 	=> $this->dashboard->total('lecturer'),
				'title'		=> 'lecturer',
				'text'      => 'Lecturer',
				'icon'		=> 'users'
			],
			[
				'box' 		=> 'red',
				'total' 	=> $this->dashboard->total('mahasiswa'),
				'title'		=> 'mahasiswa',
				'text'      => 'Student',
				'icon'		=> 'graduation-cap'
			],
			[
				'box' 		=> 'maroon',
				'total' 	=> $this->dashboard->total('matkul'),
				'title'		=> 'matkul',
				'text'      => 'Course',
				'icon'		=> 'th'
			],
			[
				'box' 		=> 'aqua',
				'total' 	=> $this->dashboard->total('tb_soal'),
				'title'		=> 'soal',
				'text'      => 'Questions',
				'icon'		=> 'file-text'
			],
			[
				'box' 		=> 'purple',
				'total' 	=> $this->dashboard->total('exam'),
				'title'		=> 'hasilujian',
				'text'      => 'Results Generated',
				'icon'		=> 'file'
			],
			[
				'box' 		=> 'olive',
				'total' 	=> $this->dashboard->total('users'),
				'title'		=> 'users',
				'text'      => 'System Users',
				'icon'		=> 'key'
			],
		];
		$info_box = json_decode(json_encode($box), FALSE);
		return $info_box;
	}

	public function index()
	{
		$user = $this->user;
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Dashboard',
			'subjudul'	=> 'Application Data',
		];

		if ( $this->ion_auth->is_admin() ) {
			$data['info_box'] = $this->admin_box();
		} elseif ( $this->ion_auth->in_group('Lecturer') ) {
			$matkul = ['matkul' => 'lecturer.course_id=matkul.id_matkul'];
			$data['lecturer'] = $this->dashboard->get_where('lecturer', 'teacher_id', $user->username, $matkul)->row();

			$kelas = ['kelas' => 'kelas_dosen.kelas_id=kelas.id_kelas'];
			$data['kelas'] = $this->dashboard->get_where('kelas_dosen', 'lecturer_id' , $data['lecturer']->lecturer_id, $kelas, ['nama_kelas'=>'ASC'])->result();
		}else{
			$join = [
				'kelas b' 	=> 'a.kelas_id = b.id_kelas',
				'department c'	=> 'b.department_id = c.id_department'
			];
			$data['mahasiswa'] = $this->dashboard->get_where('mahasiswa a', 'nim', $user->username, $join)->row();
		}

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('dashboard');
		$this->load->view('_templates/dashboard/_footer.php');
	}
}