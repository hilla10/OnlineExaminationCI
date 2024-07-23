<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

// require_once dirname(__FILE__) . '/../third_party/PHPExcel/PHPExcel/Writer/PDF/tcPDF.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}