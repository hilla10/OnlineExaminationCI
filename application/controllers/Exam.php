<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {

	public $mhs, $user;

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->helper('my');
		$this->load->model('Master_model', 'master');
		$this->load->model('Question_model', 'question');
		$this->load->model('Exam_model', 'exam');
		$this->form_validation->set_error_delimiters('','');

		$this->user = $this->ion_auth->user()->row();
		$this->mhs 	= $this->exam->getIdStudent($this->user->username);
    }

    public function access_lecturer()
    {
        if ( !$this->ion_auth->in_group('Lecturer') ){
			show_error('This page is specifically for lecturers to make an Online Test, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');
		}
    }

    public function akses_student_access()
    {
        if ( !$this->ion_auth->in_group('Student') ){
			show_error('This page is specifically for students taking the exam_history, <a href="'.base_url('dashboard').'">Back to main menu</a>', 403, 'Forbidden Access');
		}
    }

    public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
	}
	
	public function json($id=null)
	{
        $this->access_lecturer();

		$this->output_json($this->exam->getDataExam($id), false);
	}

    public function master()
	{
        $this->access_lecturer();
        $user = $this->ion_auth->user()->row();
        $data = [
			'user' => $user,
			'title'	=> 'Exam',
			'subtitle'=> 'Exam Data',
			'lecturer' => $this->exam->getIdLecturer($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function add()
	{
		$this->access_lecturer();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'title'		=> 'Exam',
			'subtitle'	=> 'Add Exam',
			'course'	=> $this->question->getCourseLecturer($user->username),
			'lecturer'		=> $this->exam->getIdLecturer($user->username),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function edit($id)
	{
		$this->access_lecturer();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'title'		=> 'Exam',
			'subtitle'	=> 'Edit Exam',
			'course'	=> $this->question->getCourseLecturer($user->username),
			'lecturer'		=> $this->exam->getIdLecturer($user->username),
			'exam'		=> $this->exam->getExamById($id),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function convert_tgl($tgl)
	{
		$this->access_lecturer();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function validasi()
	{
		$this->access_lecturer();
		
		$user 	= $this->ion_auth->user()->row();
		$lecturer 	= $this->exam->getIdLecturer($user->username);
		$jml 	= $this->exam->getTotalQuestions($lecturer->lecturer_id)->total_questions;
		$jml_a 	= $jml + 1; // If you don't understand, please read the user_guide codeigniter about form_validation in the less_than section

		$this->form_validation->set_rules('exam_name', 'Exam Name', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('total_questions', 'Number of Questions', "required|integer|less_than[{$jml_a}]|greater_than[0]", ['less_than' => "Question tidak cukup, anda hanya punya {$jml} question"]);
		$this->form_validation->set_rules('start_time', 'Start Date', 'required');
		$this->form_validation->set_rules('end_time', 'Completion Date', 'required');
		$this->form_validation->set_rules('duration', 'Time', 'required|integer|max_length[4]|greater_than[0]');
		$this->form_validation->set_rules('type', 'Random Question', 'required|in_list[Random,Sort]');
	}

	public function save()
	{
		$this->validasi();
		$this->load->helper('string');

		$method 		= $this->input->post('method', true);
		$lecturer_id 		= $this->input->post('lecturer_id', true);
		$course_id 		= $this->input->post('course_id', true);
		$exam_name 	= $this->input->post('exam_name', true);
		$total_questions 	= $this->input->post('total_questions', true);
		$start_time 		= $this->convert_tgl($this->input->post('start_time', 	true));
		$end_time	= $this->convert_tgl($this->input->post('end_time', true));
		$duration			= $this->input->post('duration', true);
		$type			= $this->input->post('type', true);
		$token 			= strtoupper(random_string('alpha', 5));

		if( $this->form_validation->run() === FALSE ){
			$data['status'] = false;
			$data['errors'] = [
				'exam_name' 	=> form_error('exam_name'),
				'total_questions' 	=> form_error('total_questions'),
				'start_time' 	=> form_error('start_time'),
				'end_time' 	=> form_error('end_time'),
				'duration' 		=> form_error('duration'),
				'type' 		=> form_error('type'),
			];
		}else{
			$input = [
				'exam_name' 	=> $exam_name,
				'total_questions' 	=> $total_questions,
				'start_time' 	=> $start_time,
				'late_time' 	=> $end_time,
				'duration' 		=> $duration,
				'type' 		=> $type,
			];
			if($method === 'add'){
				$input['lecturer_id']	= $lecturer_id;
				$input['course_id'] = $course_id;
				$input['token']		= $token;
				$action = $this->master->create('exam', $input);
			}else if($method === 'edit'){
				$exam_id = $this->input->post('exam_id', true);
				$action = $this->master->update('exam', $input, 'exam_id', $exam_id);
			}
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function delete()
	{
		$this->access_lecturer();
		$chk = $this->input->post('checked', true);
        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('exam', $chk, 'exam_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}

	public function refresh_token($id)
	{
		$this->load->helper('string');
		$data['token'] = strtoupper(random_string('alpha', 5));
		$refresh = $this->master->update('exam', $data, 'exam_id', $id);
		$data['status'] = $refresh ? TRUE : FALSE;
		$this->output_json($data);
	}

	/**
	 * BAGIAN Student
	 */

	public function list_json()
	{
		$this->akses_student_access();
		
		$list = $this->exam->getListExam($this->mhs->student_id, $this->mhs->class_id);
		$this->output_json($list, false);
	}
	
	public function list()
	{
		$this->akses_student_access();

		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'title'		=> 'Exam',
			'subtitle'	=> 'List Exam',
			'mhs' 		=> $this->exam->getIdStudent($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('exam/list');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function token($id)
	{
		$this->akses_student_access();
		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'title'		=> 'Exam',
			'subtitle'	=> 'Token Exam',
			'mhs' 		=> $this->exam->getIdStudent($user->username),
			'exam'		=> $this->exam->getExamById($id),
			'encrypted_id' => urlencode($this->encryption->encrypt($id))
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('exam/token');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function cektoken()
	{
		$id = $this->input->post('exam_id', true);
		$token = $this->input->post('token', true);
		$cek = $this->exam->getExamById($id);
		
		$data['status'] = $token === $cek->token ? TRUE : FALSE;
		$this->output_json($data);
	}

	public function encrypt()
	{
		$id = $this->input->post('id', true);
		$key = urlencode($this->encryption->encrypt($id));
		// $decrypted = $this->encryption->decrypt(rawurldecode($key));
		$this->output_json(['key'=>$key]);
	}

	public function index()
	{
		$this->akses_student_access();
		$key = $this->input->get('key', true);
		$id  = $this->encryption->decrypt(rawurldecode($key));
		
		$exam 		= $this->exam->getExamById($id);
		$question 		= $this->exam->getQuestion($id);
		
		$mhs		= $this->mhs;
		$exam_history 	= $this->exam->examResults($id, $mhs->student_id);
	
		$cek_sudah_ikut = $exam_history->num_rows();

		if ($cek_sudah_ikut < 1) {
			$questions_ordered_correctly 	= array();
			$i = 0;
			foreach ($question as $s) {
				$question_per = new stdClass();
				$question_per->question_id 		= $s->question_id;
				$question_per->question 		= $s->question;
				$question_per->file 		= $s->file;
				$question_per->file_type 	= $s->file_type;
				$question_per->option_a 		= $s->option_a;
				$question_per->option_b 		= $s->option_b;
				$question_per->option_c 		= $s->option_c;
				$question_per->option_d 		= $s->option_d;
				$question_per->option_e 		= $s->option_e;
				$question_per->answer 		= $s->answer;
				$questions_ordered_correctly[$i] 		= $question_per;
				$i++;
			}
			$questions_ordered_correctly 	= $questions_ordered_correctly;
			$list_question_id	= "";
			$question_answer_list 	= "";
			if (!empty($question)) {
				foreach ($question as $d) {
					$list_question_id .= $d->question_id.",";
					$question_answer_list .= $d->question_id."::N,";
				}
			}
			$list_question_id 	= substr($list_question_id, 0, -1);
			$question_answer_list 	= substr($question_answer_list, 0, -1);
			$end_time 	= date('Y-m-d H:i:s', strtotime("+{$exam->duration} minute"));
			$time_mulai		= date('Y-m-d H:i:s');

			$input = [
				'exam_id' 		=> $id,
				'student_id'	=> $mhs->student_id,
				'question_list'		=> $list_question_id,
				'answer_list' 	=> $question_answer_list,
				'correct_count'		=> 0,
				'score'			=> 0,
				'weighted_score'	=> 0,
				'start_time'		=> $time_mulai,
				'end_time'	=> $end_time,
				'status'		=> 'Y'
			];
			$this->master->create('exam_history', $input);

			// Setelah insert wajib refresh dulu
			redirect('exam/?key='.urlencode($key), 'location', 301);
		}
		
		$q_question = $exam_history->row();
		
		$question_order 		= explode(",", $q_question->answer_list);
		$questions_ordered_correctly	= array();
		for ($i = 0; $i < sizeof($question_order); $i++) {
			$pc_question_order	= explode(":",$question_order[$i]);
			$pc_question_order1 	= empty($pc_question_order[1]) ? "''" : "'{$pc_question_order[1]}'";
			$fetch_questions 	= $this->exam->ambilQuestion($pc_question_order1, $pc_question_order[0]);
			$questions_ordered_correctly[] = $fetch_questions; 
		}

		$detail_tes = $q_question;
		$questions_ordered_correctly = $questions_ordered_correctly;

		$pc_answer_list = explode(",", $detail_tes->answer_list);
		$arr_jawab = array();
		foreach ($pc_answer_list as $v) {
			$pc_v 	= explode(":", $v);
			$idx 	= $pc_v[0];
			$val 	= $pc_v[1];
			$rg 	= $pc_v[2];

			$arr_jawab[$idx] = array("j"=>$val,"r"=>$rg);
		}

		$arr_option = array("a","b","c","d","e");
		$html = '';
		$no = 1;
		if (!empty($questions_ordered_correctly)) {
			foreach ($questions_ordered_correctly as $s) {
				$path = 'uploads/bank_question/';
				$vrg = $arr_jawab[$s->question_id]["r"] == "" ? "N" : $arr_jawab[$s->question_id]["r"];
				$html .= '<input type="hidden" name="question_id_'.$no.'" value="'.$s->question_id.'">';
				$html .= '<input type="hidden" name="rg_'.$no.'" id="rg_'.$no.'" value="'.$vrg.'">';
				$html .= '<div class="step" id="widget_'.$no.'">';

				$html .= '<div class="text-center"><div class="w-25">'.display_media($path.$s->file).'</div></div>'.$s->question.'<div class="funkyradio">';
				for ($j = 0; $j < $this->config->item('jml_option'); $j++) {
					$option 			= "option_".$arr_option[$j];
					$file 			= "file_".$arr_option[$j];
					$checked 		= $arr_jawab[$s->question_id]["j"] == strtoupper($arr_option[$j]) ? "checked" : "";
					$option_label 	= !empty($s->$option) ? $s->$option : "";
					$display_media_option = (is_file(base_url().$path.$s->$file) || $s->$file != "") ? display_media($path.$s->$file) : "";
					$html .= '<div class="funkyradio-success" onclick="return simpan_sementara();">
						<input type="radio" id="option_'.strtolower($arr_option[$j]).'_'.$s->question_id.'" name="option_'.$no.'" value="'.strtoupper($arr_option[$j]).'" '.$checked.'> <label for="option_'.strtolower($arr_option[$j]).'_'.$s->question_id.'"><div class="option_label">'.$arr_option[$j].'</div> <p>'.$option_label.'</p><div class="w-25">'.$display_media_option.'</div></label></div>';
				}
				$html .= '</div></div>';
				$no++;
			}
		}

		// Enkripsi Id Tes
		$id_tes = $this->encryption->encrypt($detail_tes->id);

		$data = [
			'user' 		=> $this->user,
			'mhs'		=> $this->mhs,
			'title'		=> 'Exam',
			'subtitle'	=> 'Exam Sheet',
			'question'		=> $detail_tes,
			'no' 		=> $no,
			'html' 		=> $html,
			'id_tes'	=> $id_tes
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('exam/sheet');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function exam()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		$input 	= $this->input->post(null, true);
		$answer_list 	= "";
		for ($i = 1; $i < $input['total_questions']; $i++) {
			$_tjawab 	= "option_".$i;
			$_question_id 	= "question_id_".$i;
			$_ragu 		= "rg_".$i;
			$answer_ 	= empty($input[$_tjawab]) ? "" : $input[$_tjawab];
			$answer_list	.= "".$input[$_question_id].":".$answer_.":".$input[$_ragu].",";
		}
		$answer_list	= substr($answer_list, 0, -1);
		$d_simpan = [
			'answer_list' => $answer_list
		];
		
		// Simpan answer
		$this->master->update('exam_history', $d_simpan, 'id', $id_tes);
		$this->output_json(['status'=>true]);
	}

	public function save_final()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		// Get Answer
		$answer_list = $this->exam->getAnswer($id_tes);

		// Pecah Answer
		$pc_answer = explode(",", $answer_list);
		
		$jumlah_benar 	= 0;
		$jumlah_salah 	= 0;
		$jumlah_ragu  	= 0;
		$weighted_score 	= 0;
		$total_weight	= 0;
		$total_questions	= sizeof($pc_answer);

		foreach ($pc_answer as $jwb) {
			$pc_dt 		= explode(":", $jwb);
			$question_id 	= $pc_dt[0];
			$answer 	= $pc_dt[1];
			$ragu 		= $pc_dt[2];

			$cek_jwb 	= $this->question->getQuestionById($question_id);
			$total_weight = $total_weight + $cek_jwb->weight;

			$answer == $cek_jwb->answer ? $jumlah_benar++ : $jumlah_salah++;
		}

		$score = ($jumlah_benar / $total_questions)  * 100;
		$weighted_score = ($total_weight / $total_questions)  * 100;

		$d_update = [
			'correct_count'		=> $jumlah_benar,
			'score'			=> number_format(floor($score), 0),
			'weighted_score'	=> number_format(floor($weighted_score), 0),
			'status'		=> 'N'
		];

		$this->master->update('exam_history', $d_update, 'id', $id_tes);
		$this->output_json(['status'=>TRUE, 'data'=>$d_update, 'id'=>$id_tes]);
	}
}