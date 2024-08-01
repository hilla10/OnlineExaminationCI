<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email {

    public function __construct()
    {
        parent::__construct();
        // Optionally set default configurations or debug mode here
        $this->smtp_debug = 1; // Enable SMTP debug
    }
}
