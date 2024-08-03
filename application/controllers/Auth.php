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

        $user_in_db = $this->Users_model->get_user_by_email($email);
        if ($user_in_db) {
            $this->set_session($user_in_db);
        } else {
            $new_user_id = $this->Users_model->register_user($email, $username, $profile_picture);
            $new_user = $this->Users_model->get_user_by_id($new_user_id);
            $this->set_session($new_user);
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
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }
}
