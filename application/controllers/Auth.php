<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Client as Google_Client;
use Google\Service\Oauth2;

class Auth extends CI_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['form_validation', 'session', 'ion_auth']);
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->load->model('Users_model');
    }

    public function output_json($data)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function index()
    {
        if ($this->ion_auth->logged_in()){
            $user_id = $this->ion_auth->user()->row()->id; // Get User ID
            $group = $this->ion_auth->get_users_groups($user_id)->row()->name; // Get user group
            redirect('dashboard');
        }
        $this->data['identity'] = [
            'name' => 'identity',
            'id' => 'identity',
            'type' => 'text',
            'placeholder' => 'Email',
            'autofocus' => 'autofocus',
            'class' => 'form-control',
            'autocomplete' => 'off'
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'placeholder' => 'Password',
            'class' => 'form-control',
        ];
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $this->load->view('_templates/auth/_header.php');
        $this->load->view('auth/login', $this->data);
        $this->load->view('_templates/auth/_footer.php');
    }

    public function cek_login()
    {
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required|trim');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required|trim');

        if ($this->form_validation->run() === TRUE) {
            $remember = (bool)$this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                $this->cek_access();
            } else {
                $data = [
                    'status' => false,
                    'failed' => 'Incorrect Login',
                ];
                $this->output_json($data);
            }
        } else {
            $invalid = [
                'identity' => form_error('identity'),
                'password' => form_error('password')
            ];
            $data = [
                'status' => false,
                'invalid' => $invalid
            ];
            $this->output_json($data);
        }
    }

  
    public function google_login() {
        $client = new Google_Client();
        $client->setClientId('354860570014-ap1734thc81dcpu3i4n7ts870mk37l93.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri(base_url('auth/google_callback'));
        $client->addScope('email');
        $client->addScope('profile');

        $auth_url = $client->createAuthUrl();
        redirect($auth_url);
    }

public function google_callback() {
    $client = new Google_Client();
    $client->setClientId('354860570014-ap1734thc81dcpu3i4n7ts870mk37l93.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri(base_url('auth/google_callback'));

    if (!isset($_GET['code'])) {
        redirect(base_url());
    } else {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            redirect(base_url());
        }

        $client->setAccessToken($token);
        $oauth = new Google_Service_Oauth2($client);
        $user = $oauth->userinfo->get();

        $email = $user->email;
        $username = $user->name;
        $profile_picture = $user->picture;

        // First, check if the user exists in the users table
        $user_in_users_table = $this->Users_model->get_user_by_email($email);

        if ($user_in_users_table) {
            // User found in users table, set session based on users table data
            $this->set_session($user_in_users_table);
        } else {
            // User not found in users table, check if the user exists in google_login table
            $user_in_google_login = $this->Users_model->get_google_user_by_email($email);

            if ($user_in_google_login) {
                // User found in google_login, set session based on google_login data
                $this->set_session($user_in_google_login);
            } else {
                // User not found in either table, register in users table
                $new_user_id = $this->Users_model->register_user($email, $username, $profile_picture);
                $new_user = $this->Users_model->get_user_by_id($new_user_id);
                $this->set_session($new_user);
            }
        }

        redirect(base_url('dashboard'));
    }
}

    public function set_session($user) {
        $session_data = [
            'identity' => $user->email,
            'username' => $user->username,
            'email' => $user->email,
            'user_id' => $user->id,
            'old_last_login' => $user->last_login,
        ];

        $this->session->set_userdata($session_data);
    }

    public function cek_access()
    {
        if (!$this->ion_auth->logged_in()) {
            $status = false; // jika false, berarti login gagal
            $url = 'auth'; // url untuk redirect
        } else {
            $status = true; // jika true maka login berhasil
            $url = 'dashboard';
        }

        $data = [
            'status' => $status,
            'url' => $url
        ];
        $this->output_json($data);
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('login', 'refresh');
    }

  /**
	 * Forgot password
	 */
public function forgot_password()
{
    $this->data['title'] = $this->lang->line('forgot_password_heading');
    
    // setting validation rules by checking whether identity is username or email
    if ($this->config->item('identity', 'ion_auth') != 'email')
    {
        $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
    }
    else
    {
        $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
    }

    if ($this->form_validation->run() === FALSE)
    {
        $this->data['type'] = $this->config->item('identity', 'ion_auth');
        // setup the input
        $this->data['identity'] = [
            'name'  => 'identity',
            'id'    => 'identity',
            'class' => 'form-control',
            'autocomplete' => 'off',
            'autofocus' => 'autofocus'
        ];

        if ($this->config->item('identity', 'ion_auth') != 'email')
        {
            $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
        }
        else
        {
            $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
        }

        // set any errors and display the form
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->load->view('_templates/auth/_header', $this->data);
        $this->load->view('auth/forgot_password');
        $this->load->view('_templates/auth/_footer');
    }
    else
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

        if (empty($identity))
        {
            if ($this->config->item('identity', 'ion_auth') != 'email')
            {
                $this->ion_auth->set_error('forgot_password_identity_not_found');
            }
            else
            {
                $this->ion_auth->set_error('forgot_password_email_not_found');
            }

            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }

        // run the forgotten password method to email an activation code to the user
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

        if ($forgotten)
        {
            $config = [
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_user' => 'entotopolytechniccollege72@gmail.com',
                'smtp_pass' => 'pzxr heaa rbvw kkis',
                'smtp_port' => 465,
                'smtp_timeout' => 30,
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'newline'   => "\r\n",
                'wordwrap'  => TRUE,
                'smtp_debug' => 2
            ];

            $data = [
                'identity' => $identity->{$this->config->item('identity', 'ion_auth')},
                'forgotten_password_code' => $forgotten['forgotten_password_code'],
            ];

            $this->load->library('email');
            $this->email->initialize($config);
            $this->load->helper('url');
            $this->email->set_newline("\r\n");

            $this->email->from('entotopolytechniccollege72@gmail.com', 'Entoto Polytechnic College');
            $this->email->to($data['identity']);
            $this->email->subject("Forgot Password");

            $body = $this->load->view('auth/email/forgot_password.tpl.php', $data, TRUE);
            $this->email->message($body);

            if ($this->email->send())
            {
                  $this->session->set_flashdata('message', "We've received your password reset request. If an account with the provided email exists, you will receive an email to reset your password.");
                redirect("auth", 'refresh');
            }
            else
            {
                echo "Email not sent.";
                show_error($this->email->print_debugger());
            }
        }
        else
        {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }
}


	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	
	public function reset_password($code = NULL)
{
    if (!$code) {
        show_404();
    }

    $this->data['title'] = $this->lang->line('reset_password_heading');
    
    $user = $this->ion_auth->forgotten_password_check($code);

    if ($user) {
        // if the code is valid then display the password reset form
        $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

        if ($this->form_validation->run() === FALSE) {
            // display the form with custom message
            $this->data['message'] = 'Please enter and confirm your new password.';
            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['new_password'] = [
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['new_password_confirm'] = [
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['user_id'] = [
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            ];
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['code'] = $code;

            // render the view
            $this->load->view('_templates/auth/_header');
            $this->load->view('auth/reset_password', $this->data);
            $this->load->view('_templates/auth/_footer');
        } else {
            $identity = $user->{$this->config->item('identity', 'ion_auth')};

            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {
                // something fishy might be up
                $this->ion_auth->clear_forgotten_password_code($identity);
                show_error($this->lang->line('error_csrf'));
            } else {
                // finally change the password
                $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                if ($change) {
                    // if the password was successfully changed
                    $this->session->set_flashdata('message', 'Your password has been successfully reset. You can now log in with your new password.');
                    redirect("auth/", 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('auth/reset_password/' . $code, 'refresh');
                }
            }
        }
    } else {
        // if the code is invalid then send them back to the forgot password page
        $this->session->set_flashdata('message', $this->ion_auth->errors());
        redirect("auth/forgot_password", 'refresh');
    }
}


	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;

		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
	}

	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}
