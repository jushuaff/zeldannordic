    <?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_users extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model([
            'users_model',
            'users_type_model'
        ]);
        if(!$this->input->is_ajax_request()){ show_404(); }
    }

    public function fetch_user(){
        $user_info = $this->users_model->get_row($_POST['user_id']);
        if($user_info){
            $response = [
                "status"        => "success",
                "profile_pic"   => $user_info['profile_pic'],
                "name"          => $user_info['name'],
                "email"         => $user_info['email'],
                "mobile_number" => $user_info['mobile_number'],
                "gender"        => $user_info['gender'],
                "date_of_birth" => $user_info['date_of_birth'],
                "username"      => $user_info['username'],
                "user_type"     => $user_info['user_type'],
                "schedule"      => $user_info['schedule'],
                "salary_grade"  => $user_info['salary_grade'],
                "archive"       => $user_info['archive'],
                "added_by"      => $user_info['added_by'],
                "date_created"  => $user_info['date_created'],
                "date_updated"  => $user_info['date_updated']
            ];
        }else{
            $response = [
                "status"  => "error",
                "message" => "An error has occurred: Unable to read user profile"
            ];
        }

        echo json_encode($response); 
    }

    public function add_user(){
        $this->form_validation
             ->set_rules('name', 'name', 'required|callback_validate_name')
             ->set_rules('email', 'email', 'required|valid_email|callback_validate_email')
             ->set_rules('mobile-number', 'mobile-number', 'callback_validate_mobile_number')
             ->set_rules('gender', 'gender', 'required')
             ->set_rules('date-of-birth', 'date-of-birth', 'required|callback_validate_date_of_birth')
             ->set_rules('role', 'role', 'required')
             ->set_rules('username', 'username', 'required|callback_validate_username')
             ->set_rules('password', 'password', 'required|min_length[8]')
             ->set_message('required', 'required')
             ->set_message('valid_email', 'Email Invalid')
             ->set_message('min_length', 'Atleast 8 characters in length');

        $salary_grade = NULL;
        $schedule     = NULL;

        if($_POST['role'] !== "1"){ 
            $this->form_validation
                 ->set_rules('salary-grade', 'salary-grade', 'required')
                 ->set_rules('schedule', 'schedule', 'required');
            $salary_grade = $_POST['salary-grade'];
            $schedule     = $_POST['schedule'];

            if($_POST['schedule'] == "fixed"){
                $this->form_validation
                     ->set_rules('time-in', 'time-in', 'required')
                     ->set_rules('time-out', 'time-out', 'required');

                $schedule = $_POST['schedule']."-".$_POST['time-in']."-".$_POST['time-out'];
            }
        }

        if($this->form_validation->run() == FALSE){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            try {
                $account_session = $this->session->userdata('account_session');
                $data = [
                    'profile_pic'   => $_POST['filename'],
                    'name'          => $_POST['name'],
                    'email'         => $_POST['email'],
                    'mobile_number' => $_POST['mobile-number'],
                    'gender'        => $_POST['gender'],
                    'date_of_birth' => $_POST['date-of-birth'],
                    'user_type'     => $_POST['role'],
                    'schedule'      => $schedule,
                    'salary_grade'  => $salary_grade,
                    'username'      => $_POST['username'],
                    'password'      => password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                    'added_by'      => $account_session['id'],
                    'date_updated'  => date('Y-m-d h:i:s')
                ];

                $url= $_SERVER['REQUEST_URI']; 
                $explode = explode("/",$url);
                $base = $explode[1];
                
                if($_POST['filename']){
                    $data['profile_pic'] = $this->rename_profile($base, $_POST['filename']);
                }

                $this->users_model->add($data);

                if($this->db->trans_status()){
                    $this->db->trans_commit();
                    $new = $this->users_model->get_one_by_where(['username' => $_POST['username']]);
                    $type = $this->users_type_model->get_row($_POST['role']);
                    $response = [
                        'status'        => 'success',
                        'message'       => 'User Added Successfully',
                        'user_id'       => $new['id'],
                        'name'          => $_POST['name'],
                        'email'         => $_POST['email'],
                        'profile_pic'   => $_POST['filename'],
                        'username'      => $_POST['username'],
                        'mobile_number' => $_POST['mobile-number'],
                        'gender'        => $_POST['gender'],
                        'user_type'     => $type['user_type']
                    ];
                }else{
                    $this->db->trans_rollback();
                    $response['status'] = 'error';
                    $response['message'] = $this->db->error();
                }
            }catch (Exception $e) {
                $this->db->trans_rollback();
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
        }
        echo json_encode($response); 
    }

    public function update_user(){
        $this->form_validation
             ->set_rules('name', 'name', 'required|callback_validate_name')
             ->set_rules('email', 'email', 'required|valid_email|callback_validate_email')
             ->set_rules('mobile-number', 'mobile-number', 'callback_validate_mobile_number')
             ->set_rules('gender', 'gender', 'required')
             ->set_rules('username', 'username', 'required|callback_validate_username')
             ->set_rules('date-of-birth', 'date-of-birth', 'required|callback_validate_date_of_birth')
             ->set_message('required', 'required')
             ->set_message('valid_email', 'Email Invalid');

        $salary_grade = NULL;
        $schedule     = NULL;

        if($_POST['user_type'] !== "n/a" && $_POST['user_type'] !== "1"){
            $this->form_validation
                 ->set_rules('salary-grade', 'salary-grade', 'required')
                 ->set_rules('schedule', 'schedule', 'required');
            $salary_grade = $_POST['salary-grade'];
            $schedule     = $_POST['schedule'];

            if($_POST['schedule'] == "fixed"){
                $this->form_validation
                     ->set_rules('time-in', 'time-in', 'required')
                     ->set_rules('time-out', 'time-out', 'required');  
                $schedule = $_POST['schedule']."-".$_POST['time-in']."-".$_POST['time-out'];
            }
        }

        if($this->form_validation->run() == FALSE){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            try {
                $data = [
                    'profile_pic'   => $_POST['filename'],
                    'name'          => $_POST['name'],
                    'email'         => $_POST['email'],
                    'mobile_number' => $_POST['mobile-number'],
                    'gender'        => $_POST['gender'],
                    'date_of_birth' => $_POST['date-of-birth'],
                    'username'      => $_POST['username'],
                    'schedule'      => $schedule,
                    'salary_grade'  => $salary_grade,
                    'date_updated'  => date('Y-m-d h:i:s')
                ];

                if($_POST['filename'] !== ""){
                    if($_POST['filename'] !== $_POST['old_profile_name']){
                        $split = explode("-",$_POST['filename']);
                        $url= $_SERVER['REQUEST_URI']; 
                        $explode = explode("/",$url);
                        $base = $explode[1];

                        if($_POST['old_profile_name'] !== ""){ //Erase old profile
                            $path = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".$_POST['old_profile_name'];
                            unlink($path);
                        }

                        $data['profile_pic'] = $this->rename_profile($base, $_POST['filename']);
                    }
                }
                
                $this->users_model->update($_POST['user_id'],$data);

                if($this->db->trans_status()){
                    $this->db->trans_commit();

                    $response = [
                        'status'        => 'success',
                        'message'       => 'Profile Updated Successfully',
                        'profile_pic'   => $data['profile_pic'],
                        'name'          => $_POST['name'],
                        'email'         => $_POST['email'],
                        'mobile_number' => $_POST['mobile-number'],
                        'gender'        => $_POST['gender'],
                        'date_of_birth' => $_POST['date-of-birth'],
                        'username'      => $_POST['username']
                    ];
                }else{
                    $this->db->trans_rollback();
                    $response['status'] = 'error';
                    $response['message'] = $this->db->error();
                }
            }catch (Exception $e) {
                $this->db->trans_rollback();
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
        }
        echo json_encode($response); 
    }

    public function update_password(){
        $account_session = $this->session->userdata('account_session');

        if($account_session['id'] == $_POST['user_id']){
            $this->form_validation
                ->set_rules('current-password', 'current-password', 'required|callback_validate_current_password')
                ->set_rules('new-password', 'new-password', 'required|min_length[8]|callback_validate_confirm_password')
                ->set_rules('confirm-password', 'confirm-password', 'required');
        }else{
            $this->form_validation->set_rules('new-password', 'new-password', 'required|min_length[8]');
        }

        $this->form_validation
            ->set_message('required', 'required')
            ->set_message('min_length', 'Atleast 8 characters in length');

        if($this->form_validation->run() == FALSE){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            try {
                $data = [
                    'password'      => password_hash($_POST['new-password'], PASSWORD_BCRYPT, ['cost' => 12]),
                    'date_updated'  => date('Y-m-d h:i:s')
                ];

                $this->users_model->update($_POST['user_id'],$data);

                if($this->db->trans_status()){
                    $this->db->trans_commit();
                    $response['status'] = 'success';
                    $response['message'] = 'Password Updated Successfully';
                }else{
                    $this->db->trans_rollback();
                    $response['status'] = 'error';
                    $response['message'] = $this->db->error();
                }
            }catch (Exception $e) {
                $this->db->trans_rollback();
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
        }
        echo json_encode($response); 
    }

    public function profile_image() {
        $url= $_SERVER['REQUEST_URI']; 
        $explode = explode("/",$url);
        $base = $explode[1];

        $temp = explode(".", $_FILES["file"]["name"]);
        $filename = 'Temporary-'.round(microtime(true)).'.'.end($temp);
        $location = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".$filename;
        if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
            echo $location;
        }else{
            echo "0";
        }
    }

    public function delete_temp_image() {
        $url= $_SERVER['REQUEST_URI']; 
        $explode = explode("/",$url);
        $base = $explode[1];
        $path = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".$_POST['filename'];
        unlink($path);

        //For dev validation only since above code always return true
        /*if (file_exists($path)){
            if (unlink($path)) { echo "success";
            }else{ echo "fail"; }   
        }else{
            echo "fail";
        }*/
    }

    public function validate_name(){
        if(isset($_POST['user_id'])){//means update
            $where = ["id !=" => $_POST['user_id'], "name" => $_POST['name']];
        }else{//add
            $where = ["name" => $_POST['name']];
        }

        $count = $this->users_model->count_by_where($where);
        if($count == 1){
            $this->form_validation->set_message('validate_name','Name already exist');
            return false;
        }
        return true;
    }

    public function validate_email(){
        if(isset($_POST['user_id'])){//means update
            $where = ["id !=" => $_POST['user_id'], "email" => $_POST['email']];
        }else{//add
            $where = ["email" => $_POST['email']];
        }

        $count = $this->users_model->count_by_where($where);
        if($count == 1){
            $this->form_validation->set_message('validate_email','Email already exist');
            return false;
        }
        return true;
    }

    public function validate_mobile_number(){
        if(!empty($_POST['mobile-number'])){
            if(isset($_POST['user_id'])){//means update
                $where = ["id !=" => $_POST['user_id'], "mobile_number" => $_POST['mobile-number']];
            }else{//add
                $where = ["mobile_number" => $_POST['mobile-number']];
            }

            $count = $this->users_model->count_by_where($where);
            if($count == 1){
                $this->form_validation->set_message('validate_email','Email already exist');
                return false;
            }
        }
        return true;
    }

    public function validate_date_of_birth(){
        if(strlen($_POST['date-of-birth']) < 10){
            $this->form_validation->set_message('validate_date_of_birth','Invalid date of birth');
            return false;
        }else{
            $date_diff = date_diff(date_create($_POST['date-of-birth']),date_create(date('m/d/Y')));
            if($date_diff->format('%y') < 18){
                $this->form_validation->set_message('validate_date_of_birth','A user cannot be a minor');
                return false;
            }
        }
        return true;
    }

    public function validate_username(){
        if(isset($_POST['user_id'])){//means update
            $where = ["id !=" => $_POST['user_id'], "username" => $_POST['username']];
        }else{//add
            $where = ["username" => $_POST['username']];
        }

        $count = $this->users_model->count_by_where($where);
        if($count == 1){
            $this->form_validation->set_message('validate_username','Username already exist');
            return false;
        }
        return true;
    }

    public function validate_current_password(){
        $user_info = $this->users_model->get_one_by_where(['id'=>$_POST['user_id']]);

        if(!password_verify($_POST['current-password'], $user_info['password'])){
            $this->form_validation->set_message('validate_current_password','Invalid password');
            return false;
        }
        return true;
    }

    public function validate_confirm_password(){
        if($_POST['new-password'] !== $_POST['confirm-password']){
            $this->form_validation->set_message('validate_confirm_password','Confirm password mismatch');
            return false;
        }
        return true;
    }

    public function archive_user(){
        try {
            $data = [
                'archive'      => $_POST['archive'],
                'date_updated' => date('Y-m-d h:i:s')
            ];
            $this->users_model->update($_POST['user_id'],$data);

            if($this->db->trans_status()){
                $this->db->trans_commit();
                $response['status'] = 'success';
                $response['message'] = 'User Archived Successfully';
            }else{
                $this->db->trans_rollback();
                $response['status'] = 'error';
                $response['message'] = $this->db->error();
            }
        }catch (Exception $e) {
            $this->db->trans_rollback();
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response); 
    }

    public function rename_profile($base,$file){
        $original = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".$file;
        $split = explode("-",$_POST['filename']);
        $new_file_name = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".$split[1];
        $renamed= rename($original, $new_file_name);
        return $split[1];
    }

    public function delete_temp_files(){
        $fileList = glob('assets_module/user_profile/*');
        foreach($fileList as $filename){
            if(is_file($filename)){
                $profile = $this->users_model->get_one_by_where(['profile_pic' => basename($filename)]);
                if(!$profile){
                    $url= $_SERVER['REQUEST_URI']; 
                    $explode = explode("/",$url);
                    $base = $explode[1];
                    $path = $_SERVER['DOCUMENT_ROOT']."/".$base."/assets_module/user_profile/".basename($filename);
                    unlink($path);
                }
            }   
        }

        $response['status'] = 'success';
        $response['message'] = 'Temp Files Removed Successfully';
        echo json_encode($response); 
    }
}