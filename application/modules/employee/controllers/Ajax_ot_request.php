    <?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_ot_request extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model(['users_model','request_ot_model']);
        if(!$this->input->is_ajax_request()){ show_404(); }
    }

    public function ot_au_request(){
        $this->form_validation
             ->set_rules('task', 'task', 'required')
             ->set_rules('date', 'date', 'required|callback_validate_date')
             ->set_message('required','required');

        if($this->form_validation->run() == FALSE){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            try {
                if(isset($_POST['user_id'])){
                    $data = [
                        'user_id'      => $_POST['user_id'],
                        'task'         => $_POST['task'],
                        'date'         => $_POST['date'],
                        'time'         => $_POST['time'],
                        'date_updated' => date('Y-m-d h:i:s')
                    ];
                    $this->request_ot_model->add($data);
                    $response['message'] = "Request Sent Successfully";
                }else{
                    $data = [
                        'task'         => $_POST['task'],
                        'date'         => $_POST['date'],
                        'time'         => $_POST['time'],
                        'date_updated' => date('Y-m-d h:i:s')
                    ];
                    $this->request_ot_model->update($_POST['otr_id'],$data);
                    $task_input = explode(PHP_EOL, $_POST['task']);
                    $task ="";
                    for($a=0;$a<count($task_input);$a++){
                        $task .= "&#x2022; ".$task_input[$a]."<br>";
                    }
                    $time = "";
                    $times = explode(" ",$_POST['time']);
                    for($a=0;$a<count($times);$a++){
                        if($times[$a]){
                            $in_out = explode("-",$times[$a]);
                            $time .= date( "H:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
                        }
                    }
                    
                    $response['message'] = "Request Updated Successfully";
                    $response['date']    = date( "M d, Y (D)",strtotime( $_POST['date']));
                    $response['time']    = $time;
                    $response['task']    = $task;
                }

                if($this->db->trans_status()){
                    $this->db->trans_commit();
                    $response['status'] = 'success';
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

    public function delete_request(){
        try {
            $this->request_ot_model->delete($_POST['otr_id']);
                
            if($this->db->trans_status()){
                $this->db->trans_commit();
                $response['status'] = 'success';
                $response['message'] = 'Request Deleted Successfully';
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

    public function validate_date(){
        if(strlen($_POST['date']) < 10){
            $this->form_validation->set_message('validate_date','Invalid date');
            return false;
        }
        return true;
    }
}