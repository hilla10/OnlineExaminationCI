<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClassRoom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        } else if (!$this->ion_auth->is_admin()) {
            show_error('Only Administrators are authorized to access this page, <a href="' . base_url('dashboard') . '">Back to main menu</a>', 403, 'Forbidden Access');
        }
        $this->load->library(['datatables', 'form_validation']);
        $this->load->model('Master_model', 'master');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
        $data = [
            'user' => $this->ion_auth->user()->row(),
            'title' => 'ClassRoom',
            'subtitle' => 'Data Class'
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('master/ClassRoom/data');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function data()
    {
        $this->output_json($this->master->getDataClass(), false);
    }

    public function add()
    {
        $data = [
            'user' => $this->ion_auth->user()->row(),
            'title' => 'Add Class',
            'subtitle' => 'Add Data Class',
            'rate' => $this->input->post('rate', true),
            'department' => $this->master->getAllDepartment()
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('master/ClassRoom/add');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function edit()
    {
        $chk = $this->input->post('checked', true);
        if (!$chk) {
            redirect('admin/class');
        } else {
            $class = $this->master->getClassById($chk);
            $data = [
                'user' => $this->ion_auth->user()->row(),
                'title' => 'Edit Class',
                'subtitle' => 'Edit Data Class',
                'department' => $this->master->getAllDepartment(),
                'class' => $class
            ];
            $this->load->view('_templates/dashboard/_header.php', $data);
            $this->load->view('master/ClassRoom/edit');
            $this->load->view('_templates/dashboard/_footer.php');
        }
    }

    public function save()
    {
        $rows = count($this->input->post('class_name', true));
        $mode = $this->input->post('mode', true);
        for ($i = 1; $i <= $rows; $i++) {
            $class_name = 'class_name[' . $i . ']';
            $department_id = 'department_id[' . $i . ']';
            $this->form_validation->set_rules($class_name, 'Class', 'required');
            $this->form_validation->set_rules($department_id, 'Dept.', 'required');
            $this->form_validation->set_message('required', '{field} Required');

            if ($this->form_validation->run() === FALSE) {
                $error[] = [
                    $class_name => form_error($class_name),
                    $department_id => form_error($department_id),
                ];
                $status = FALSE;
            } else {
                if ($mode == 'add') {
                    $insert[] = [
                        'class_name' => $this->input->post($class_name, true),
                        'department_id' => $this->input->post($department_id, true)
                    ];
                } else if ($mode == 'edit') {
                    $update[] = [
                        'class_id' => $this->input->post('class_id[' . $i . ']', true),
                        'class_name' => $this->input->post($class_name, true),
                        'department_id' => $this->input->post($department_id, true)
                    ];
                }
                $status = TRUE;
            }
        }
        if ($status) {
            if ($mode == 'add') {
                $this->master->create('class', $insert, true);
                $data['insert'] = $insert;
            } else if ($mode == 'edit') {
                $this->master->update('class', $update, 'class_id', null, true);
                $data['update'] = $update;
            }
        } else {
            if (isset($error)) {
                $data['errors'] = $error;
            }
        }
        $data['status'] = $status;
        $this->output_json($data);
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);
        if (!$chk) {
            $this->output_json(['status' => false]);
        } else {
            if ($this->master->delete('class', $chk, 'class_id')) {
                $this->output_json(['status' => true, 'total' => count($chk)]);
            }
        }
    }


 public function classByDepartment($id)
    {
        $this->load->model('Class_model'); // Load your model
        $data = $this->Class_model->getClassByDepartment($id);
        echo json_encode($data);
    }



 public function getClassByDepartment()
    {
        $department_id = $this->input->post('department_id');
        $this->load->model('Master_model'); // Load your model
        $classes = $this->Master_model->getClassByDepartment($department_id);
        echo json_encode($classes);
    }



    public function import($import_data = null)
    {
        $data = [
            'user' => $this->ion_auth->user()->row(),
            'title' => 'Class',
            'subtitle' => 'Import Class',
            'department' => $this->master->getAllDepartment()
        ];
        if ($import_data != null) $data['import'] = $import_data;

        $this->load->view('_templates/dashboard/_header', $data);
        $this->load->view('master/ClassRoom/import');
        $this->load->view('_templates/dashboard/_footer');
    }

    public function preview()
    {
        $config['upload_path'] = './uploads/import/';
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('upload_file')) {
            $error = $this->upload->display_errors();
            echo $error;
            die;
        } else {
            $file = $this->upload->data('full_path');
            $ext = $this->upload->data('file_ext');

            switch ($ext) {
                case '.xlsx':
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    break;
                case '.xls':
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    break;
                case '.csv':
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                    break;
                default:
                    echo "unknown file ext";
                    die;
            }

            $spreadsheet = $reader->load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $data = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $data[] = [
                    'class' => $sheetData[$i][0],
                    'department' => $sheetData[$i][1]
                ];
            }

            unlink($file);

            $this->import($data);
        }
    }

    public function do_import()
    {
        $input = json_decode($this->input->post('data', true));
        $data = [];
        foreach ($input as $d) {
            $data[] = ['class_name' => $d->class, 'department_id' => $d->department];
        }

        $save = $this->master->create('class', $data, true);
        if ($save) {
            redirect('class');
        } else {
            redirect('ClassRoom/import');
        }
    }
}
