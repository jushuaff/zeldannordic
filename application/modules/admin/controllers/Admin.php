<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Admin extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model([
            'users_model',
            'users_type_model',
            'salary_grade_model',
            'request_ot_model',
            'dtr_model'
        ]);

        if($this->session->has_userdata('is_logged_in')){
            $type = $this->session->userdata('is_logged_in');
            if($type["type"] !== "admin"){
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
            ->title('Admin')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('index_admin', $user_info);
    }

    public function archived_employee() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Archived Employees')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('archived_employees', $user_info);
    }

    public function salary_grade() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Salary Grade')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('salary_grade', $user_info);
    }

    public function overtime_requests() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Overtime Requests')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('overtime_requests', $user_info);
    }

    public function active_dtr() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('Active DTR')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('active_dtr', $user_info);
    }

    public function outstanding_dtr() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('outstanding DTR')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('outstanding_dtr', $user_info);
    }

    public function dtr_list() {
        $account_session = $this->session->userdata('account_session');
        $user_info = $this->users_model->get_one_by_where(['id'=>$account_session['id']]);

        $this->template
            ->title('outstanding DTR')
            ->set_layout('user_main')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/main.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/main_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/desktop_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/tablet_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/admin/css/mobile_admin.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('assets_module/universal/css/modal.css') . '" rel="stylesheet" type="text/css">')
            ->prepend_metadata('<link href="' . versionAsset('plugins/datatables/datatables.min.css') . '" rel="stylesheet" type="text/css">')
            ->append_metadata('<script src="' . versionAsset('assets_module/universal/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('assets_module/admin/js/main.js') . '"></script>')
            ->append_metadata('<script src="' . versionAsset('plugins/datatables/datatables.min.js') . '"></script>')
            ->build('dtr_list', $user_info);
    }


}