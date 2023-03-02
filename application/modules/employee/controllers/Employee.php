<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Employee extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model(['users_model','users_type_model','salary_grade_model','request_ot_model','dtr_model']);

        if($this->session->has_userdata('is_logged_in')){
            $type = $this->session->userdata('is_logged_in');
            if($type["type"] !== "employee"){
                header("Location: ".base_url($type['type']));
            }
        }else {
            header("Location: ".base_url());
        }
    }

    public function index() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Main')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/main_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/desktop_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/tablet_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/mobile_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/employee/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('index_employee', $user_info);
    }

    public function overtime_lists() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Main')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/main_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/desktop_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/tablet_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/employee/css/mobile_employee.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/employee/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('overtime_lists', $user_info);
    }


}