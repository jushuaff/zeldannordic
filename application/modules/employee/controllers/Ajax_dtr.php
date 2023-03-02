    <?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_dtr extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model(['users_model','dtr_model']);
        if(!$this->input->is_ajax_request()){ show_404(); }
    }

     public function read_dtr(){
        $dtr = $this->dtr_model->get_row($_POST['dtr_id']);
        if($dtr){
            $response = [
                "status"       => "success",
                "user_id"      => $dtr['user_id'],
                "date"         => $dtr['date'],
                "time_in"      => $dtr['time_in'],
                "time_out"     => $dtr['time_out'],
                "work_base"    => $dtr['work_base'],
                "shift_reason" => $dtr['shift_reason'],
                "per_hour"     => $dtr['per_hour'],
                "paid"         => $dtr['paid'],
                "date_created" => $dtr['date_created'],
                "date_updated" => $dtr['date_updated']
            ];
        }else{
            $response = [
                "status"  => "error",
                "message" => "An error has occurred: Unable to read dtr"
            ];
        }

        echo json_encode($response); 
    }

    public function add_dtr(){
        $this->form_validation
             ->set_rules('date', 'date', 'required|min_length[10]')
             ->set_rules('time-in', 'time-in', 'required|callback_validate_time_in')
             ->set_rules('work-base', 'work-base', 'required')
             ->set_message('min_length','Invalid date')
             ->set_message('required','required');

        $shift_reason = NULL;
        if(isset($_POST['cb-moved-shift'])){
            $this->form_validation->set_rules('shift-reason', 'shift-reason', 'required');
            $shift_reason = $_POST['shift-reason'];
            if($_POST['shift-reason'] == "Others"){
                $this->form_validation->set_rules('others', 'others', 'required');
                $shift_reason = "Others : ".$_POST['others'];
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
                    'user_id'      => $_POST['user_id'],
                    'date'         => $_POST['date'],
                    'time_in'      => $_POST['time-in'],
                    'work_base'    => $_POST['work-base'],
                    'shift_reason' => $shift_reason,
                    'per_hour'     => $_POST['per_hour'],
                    'date_updated' => date('Y-m-d h:i:s')
                ];

                $this->dtr_model->add($data); 
                
                if($this->db->trans_status()){
                    $this->db->trans_commit();
                    $response['status'] = 'success';
                    $response['message'] = "DTR created Successfully";
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

    public function update_dtr(){
        $this->form_validation
             ->set_rules('date', 'date', 'required|min_length[10]')
             ->set_rules('time-in', 'time-in', 'required')
             ->set_rules('time-out', 'time-out', 'required|callback_validate_time_out')
             ->set_rules('work-base', 'work-base', 'required')
             ->set_message('min_length','Invalid date')
             ->set_message('required','required');

        $shift_reason = NULL;
        if(isset($_POST['cb-moved-shift'])){
            $this->form_validation->set_rules('shift-reason', 'shift-reason', 'required');
            $shift_reason = $_POST['shift-reason'];
            if($_POST['shift-reason'] == "Others"){
                $this->form_validation->set_rules('others', 'others', 'required');
                $shift_reason = "Others : ".$_POST['others'];
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
                    'date'         => $_POST['date'],
                    'time_in'      => $_POST['time-in'],
                    'time_out'     => $_POST['time-out'],
                    'work_base'    => $_POST['work-base'],
                    'shift_reason' => $shift_reason,
                    'date_updated' => date('Y-m-d h:i:s')
                ];

                $this->dtr_model->update($_POST['dtr_id'], $data); 

                if($this->db->trans_status()){
                    $this->db->trans_commit();
                    $response['status']       = 'success';
                    $response['message']      = "DTR updated Successfully";
                    $response['date']         = date( "M d, Y (D)",strtotime( $_POST['date']));
                    $response['time']         = date( "h:i a",strtotime( $_POST['time-in']))." - ".date( "h:i a",strtotime( $_POST['time-out']));
                    $response['time_in']      = date( "h:i a",strtotime( $_POST['time-in']));
                    $response['time_out']     = date( "h:i a",strtotime( $_POST['time-out']));
                    $response['work_base']    = $_POST['work-base'];
                    $response['shift_reason'] = ($shift_reason) ? $shift_reason : "no";
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

    public function validate_time_in(){
        $same_date = $this->dtr_model->get_same_date($_POST['user_id'],$_POST['date']);
        if($same_date):
            /*$count = 0;
            foreach($same_date as $sm):
                if(strtotime($sm['time-out']) < strtotime($_POST['time-in'])):
                    $count++;
                endif;
            endforeach;

            if($count > 0){
                $this->form_validation->set_message('validate_time_in','Should not be less than your previous time-out on the given date');
                return false;
            }else{
                return true;
            }*/

            $num = count($same_date)-1;
            if(strtotime($same_date[$num]['time_out']) >= strtotime($_POST['time-in'])):
                $this->form_validation->set_message('validate_time_in','Should not be less than your previous time-out on the given date');
                return false;
            endif;
            return true;
        endif;
        return true;
    }

    public function validate_time_out(){
        if(strtotime($_POST['time-out']) <= strtotime($_POST['time-in'])){
            $this->form_validation->set_message('validate_time_out','Should not be less than time-in');
            return false;
        }
        return true;
    }
}