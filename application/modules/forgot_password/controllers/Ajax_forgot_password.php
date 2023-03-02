<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_forgot_password extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['users_model', 'users_type_model']);
        if(!$this->input->is_ajax_request()){ show_404(); }
    }

    public function index(){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation
            ->set_message('required', 'required')
            ->set_message('valid_email', 'invalid email');

        if( $this->form_validation->run() == FALSE ){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            $user = $this->users_model->get_one_by_where(['email'=>$_POST['email']]);
            
            if($user){
                $template = 'email_templates/forgot_password';
                $email_data = array(
                    'name' => $user['name'],
                    'date_updated' => $user['date_updated'],
                    'user_id' => $user['id']
                );

                $this->load->library('email');

                $this->email->from('nordicnlrc@gmail.com', 'NLRC');
                $this->email->to($_POST['email']);
                $this->email->subject('Reset Password');
                $this->email->message(
                    $this->template
                        ->title('Reset Password')
                        ->set_layout('email_main')
                        ->set_partial('header', 'email_templates/header')
                        ->set_partial('footer', 'email_templates/footer')
                        ->build($template, $email_data, TRUE)
                );
                $this->email->set_mailtype('html');

                if($this->email->send()){
                    $response = [
                        'status' => 'success',
                        'message' => "A reset password link is been sent to your email"
                    ];
                }else{
                    $response = [
                        'status' => 'error',
                        'message' => "Email sending failed. ".$this->email->print_debugger()
                    ];
                }
            }else {
                $response = [
                    'status' => 'error',
                    'message' => 'Email do not exist'
                ];
            }
        }
        
        echo json_encode($response); 
    }
}