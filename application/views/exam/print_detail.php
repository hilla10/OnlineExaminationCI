<?php 
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header() {
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 18);
        $this->SetY(13);
        $this->Cell(0, 15, 'Examination Results', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Examination Results');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$start_time= date('d F Y H:i:s', strtotime($exam->start_time));
$completed = date('d F Y H:i:s', strtotime($exam->end_time));

// create some HTML content
$html = <<<EOD
<p>
Online Examination System in PHP using CodeIgniter Framework. </br>
All the detailed information are provided below with Highest, Lowest and Average Score of the student!
</p>
<table>
    <tr>
        <th><b>Exam Name</b></th>
        <td>{$exam->exam_name}</td>
        <th><b>Course</b></th>
        <td>{$exam->course_name}</td> 
    </tr>
    <tr>
        <th><b>Total Ques.</b></th>
        <td>{$exam->total_questions}</td>
        <th><b>Lecturer</b></th>
        <td>{$exam->lecturer_name}</td>
    </tr>
    <tr>
        <th><b>Time</b></th>
        <td>{$exam->duration} Minute</td>
        <th><b>Lowest Score</b></th>
        <td>{$score->min_score}</td>
    </tr>
    <tr>
        <th><b>Start Date</b></th>
        <td>{$start_time}</td>
        <th><b>Highest Score</b></th>
        <td>{$score->max_score}</td>
    </tr>
    <tr>
        <th><b>Completion Date</b></th>
        <td>{$completed}</td>
        <th><b>Average Score</b></th>
        <td>{$score->avg_score}</td>
    </tr>
</table>
EOD;

$html .= <<<EOD
<br><br><br>
<table border="1" style="border-collapse:collapse">
    <thead>
        <tr align="center">
            <th width="5%">#</th>
            <th width="35%"><b>Name</b></th>
            <th width="15%"><b>Class</b></th>
            <th width="25%"><b>Dept.</b></th>
            <th width="10%"><b>Correct Amt</b></th>
            <th width="10%"><b>Score</b></th>
        </tr>        
    </thead>
    <tbody>
EOD;

$no = 1;
foreach($result as $row) {
$html .= <<<EOD
    <tr>
        <td align="center" width="5%">{$no}</td>
        <td width="35%">{$row->name}</td>
        <td width="15%">{$row->class_name}</td>
        <td width="25%">{$row->department_name}</td>
        <td width="10%">{$row->correct_count}</td>
        <td width="10%">{$row->score}</td>
    </tr>
EOD;
$no++;
}

$html .= <<<EOD
    </tbody>
</table>
EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('tes.pdf', 'I');
