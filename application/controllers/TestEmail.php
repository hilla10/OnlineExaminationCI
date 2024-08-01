<?php 

// class TestEmail extends CI_controller {

//     public function index() {

//         $this->load->library("email");

//         $config = array(
//     'protocol'  => 'smtp',
//     'smtp_host' => 'ssl://smtp.googlemail.com',
//     'smtp_user' => 'negussehaylemikael2022@gmail.com',
//     'smtp_pass' => 'fhqp hrbz qcnk udbp ',
//     'smtp_timeout' => 30,
//     'smtp_port' => 465,
//     'mailtype'  => 'html',
//     'charset'   => 'utf-8',
//     'newline'   => "\r\n",
//     'wordwrap'  => TRUE
// );

//   $this->email->initialize($config);
//         $this->email->set_newline("\r\n");
//         $this->email->set_crlf("\r\n");
//         $this->email->to("negussehaylemikael2022@gmail.com");
//         $this->email->from("hillaman592@gmail.com");
//         $this->email->subject("Test Email Library");
//         $this->email->message("Hello this is test mail function");
//        if( $this->email->send()) {
//             echo "Mail send success fully";
//        } else {
//             echo "Sorry! Unable to sent";
//             print_r($this->email->print_debugger());
//        }

//     }

// }



class TestEmail extends CI_Controller {

    public function test_email()
    {
        $this->load->library('email');
        $this->config->load('email', TRUE);
        $email_config = $this->config->item('email');

        $this->email->initialize($email_config);
        $this->email->set_newline("\r\n");

        $this->email->to('negussehaylemikael2022@gmail.com');
        $this->email->from('hillaman592@gmail.com', 'Your Name');
        $this->email->subject('Test Email');
        $this->email->message('This is a test email.');

        if ($this->email->send()) {
            echo 'Email sent successfully.';
        } else {
            echo 'Failed to send email:<br>';
            print_r($this->email->print_debugger());
        }
    }
}

