<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['users_model', 'users_type_model']);
        if(!$this->input->is_ajax_request()){ show_404(); }
    }
    
    public function index() {
        $this->form_validation
            ->set_rules('username', 'Username', 'required')
            ->set_rules('password', 'Password', 'required');

        $this->form_validation->set_message('required', 'required');

        if( $this->form_validation->run() == FALSE ){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            $user_info = $this->users_model->get_one_by_where(['username'=>$_POST['username']]);
            $user_type = $this->users_type_model->get_one_by_where(['id'=>$user_info["user_type"]]);
            $pass = $user_info["password"];
            //$type = $user_type["user_type"];
            $type = ($user_type["user_type"] == "admin") ? "admin" : "employee";
            
            if($user_info){
                if($user_info['archive'] == '0' && password_verify($_POST['password'], $user_info['password'])){
                    $response = [
                        'status' => 'success',
                        'message' => 'Log in successful. ',
                        'redirect' => base_url($type)
                    ];

                    $this->session->set_userdata("account_session", $user_info);
                    $this->session->set_userdata('is_logged_in', ["type" => $type]);
                }else{
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid username or password'
                    ];
                }
            }else {
                $response = [
                    'status' => 'error',
                    'message' => 'User do not exist'
                ];
            }
        }
        
        echo json_encode($response); 
    }
}