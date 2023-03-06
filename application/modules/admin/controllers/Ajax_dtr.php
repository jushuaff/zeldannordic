    <?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ajax_dtr extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model(['users_model','dtr_model','request_ot_model','salary_grade_model','holidays_model']);
        $this->load->library('tcpdf/tcpdf');
    }

    public function read_odtr_list(){
        if(!$this->input->is_ajax_request()){ show_404(); }
        $odtr = $this->dtr_model->get_all_by_where(['user_id' => $_POST['user_id'], 'date' => $_POST['date']]);
        $shift_count = 0;
        $dtr_count = 0;
        echo '<h6 class="p-0 bg-lblue text-center text-white">'.date( "M d, Y (D)",strtotime( $odtr[0]['date'])).'</h6>
            <div class="row">';
            $total_hours = 0;
            foreach($odtr as $odtr):
                $hour_diff = strtotime($odtr['time_out'])-strtotime($odtr['time_in']);
                $hours = date('H:i:s', $hour_diff);
                $hms = explode(":", $hours);
                $num_hours = $hms[0] + ($hms[1]/60) + ($hms[2]/3600) - 1;
                $total_hours += $num_hours;
                echo '
                    <div class="col-md-5"><b>Time-in: </b>'.date( "h:i a",strtotime( $odtr['time_in'])).'</div>
                    <div class="col-md-5"><b>Time-out: </b>'.date( "h:i a",strtotime( $odtr['time_out'])).'</div>
                    <div class="col-md-2 text-right">'.$num_hours.' hrs</div>
                ';
                if($odtr['shift_reason']){ $shift_count++; }
                $dtr_count++;
            endforeach;
        echo '</div>';

        if($dtr_count>1):
            echo '<div class="row">
                <div class="col-md-2 offset-md-10 text-right bt-1px-black">'.$total_hours.' hrs</div>
            </div>';
        endif;

        $otr = $this->request_ot_model->get_ot($_POST['user_id'],$_POST['date']);
        $total_hours = 0;
        if($otr):
            echo '<h6 class="p-0 bg-lblue text-center text-white mt-15px">Overtime</h6>
                <div class="row pb-7px">
                <div class="col-md-6"><b>Tasks</b></div>
                <div class="col-md-6 text-right"><b>Aloted Timeline</b></div>';
                foreach($otr as $otr):
                    echo '<div class="col-md-6">';
                        $task = explode(PHP_EOL, $otr['task']);
                        for($a=0;$a<count($task);$a++){
                            echo "&#x2022; ".$task[$a]."<br>";
                        }
                    echo '</div>
                    <div class="col-md-6 text-right">';
                        $times = explode(" ",$otr['time']);
                        for($a=0;$a<count($times);$a++){
                            if($times[$a]){
                                $in_out = explode("-",$times[$a]);
                                echo date( "h:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
                                $hour_diff = strtotime($in_out[1])-strtotime($in_out[0]);
                                $hours = date('H:i:s', $hour_diff);
                                $hms = explode(":", $hours);
                                $num_hours = $hms[0] + ($hms[1]/60) - 1;
                                $total_hours += $num_hours;
                            }
                        }
                    echo '</div>
                    <div class="col-md-6 offset-md-6 bt-1px-black text-right">'.$total_hours.' hrs</div>';
                endforeach; 
            echo "</div>";
        endif;

        if($shift_count > 0):
            $shift = $this->dtr_model->get_all_by_where(['user_id' => $_POST['user_id'], 'date' => $_POST['date']]);
            echo '<h6 class="p-0 bg-lblue text-center text-white mt-15px">Shift</h6>
                <div class="row pb-7px">';
                foreach($shift as $shift):
                    echo '<div class="col-md-12">'.$shift['shift_reason'].'</div>';
                endforeach; 
            echo "</div>";
        endif;
    }

    public function download_dtr_validation(){
        if(!$this->input->is_ajax_request()){ show_404(); }
        $this->form_validation
             ->set_rules("start-date","start-date","required")
             ->set_rules("end-date","end-date","required|callback_validate_end_date")
             ->set_message("required","required");

        if($this->form_validation->run() == FALSE){
            $response = [
                'status' => 'form-incomplete',
                'errors' => $this->form_validation->error_array()
            ];
        }else{
            $this->session->set_userdata("start-date", $_POST['start-date']);
            $this->session->set_userdata("end-date", $_POST['end-date']);

            $response = [
                'status'  => 'success',
                'message' => 'Generating PDF'
            ];
        }
        echo json_encode($response); 
    }

    public function download_dtr(){
        if($this->session->has_userdata('start-date') && $this->session->has_userdata('end-date')):
            $start = date( "Ymd",strtotime($this->session->userdata('start-date')));
            $end   = date( "Ymd",strtotime($this->session->userdata('end-date')));

            /*$start = date( "Ymd",strtotime("01/01/2023"));
            $end   = date( "Ymd",strtotime("03/01/2023"));*/

            $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
            $pdf->SetCreator(PDF_CREATOR);  
            $pdf->SetTitle("Employee DTR List");  
            $pdf->SetHeaderData(base_url("assets/img/nlrc-logo-2.png"), '100px', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
            $pdf->SetDefaultMonospacedFont('helvetica'); 
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
            $pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
            $pdf->setPrintHeader(false);  
            $pdf->setPrintFooter(false);  
            $pdf->SetAutoPageBreak(TRUE, 10);  
            $pdf->SetFont('helvetica', '', 12);  
            $pdf->AddPage();  
            $content = '';  
            $content .= '
                <style>
                    .logo { width: 120px; }
                    .text-left { text-align: left; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .bg-blue { background-color: #0c72ba; color: white; }
                    .bg-gray { background-color: gray; color: white; }
                    .t-8 {font-size: 8px !important; }
                    .t-approved { color: #039487; }
                    .t-pending { color: green; }
                    .t-denied { color: red; }
                    table { font-size: 9px;}
                </style>
                <table>
                <thead>
                    <tr class="logo-con">
                        <td width="50%"><img class="logo" src="'.base_url("assets/img/nlrc-logo-2.png").'"></td>
                        <td width="50%" class="text-right">
                            <b>Employee DTR List</b><br>
                            <span class="t-8">(From '.date( "M d, Y",strtotime($start)).' to '.date( "M d, Y",strtotime($end)).')</span>
                        </td>
                    </tr>
                    <tr><td></td></tr>
                </thead>
                <tbody>
            ';
            $user = $this->users_model->get_all_by_where(['user_type !=' => "1", 'archive' => "0"]);
            foreach($user as $user):
                $total_hours = 0;
                $total_days = 0;

                $check_user = $this->dtr_model->get_all_by_where(['user_id' => $user['id']]);
                if($check_user):
                    $dtr_list = $this->dtr_model->get_all_by_group();
                    if($dtr_list):
                        $content .= '
                            <tr class="bg-blue">
                                <td width="40%"><b>'.$user['name'].'</b></td>
                                <td width="35%"><b>Schedule: '.$user['schedule'].'</b></td>
                                <td width="25%" class="text-right"><b>Hourly Rate: ';
                                    $sg = $this->salary_grade_model->get_one_by_where(['grade_number' => $user['salary_grade']]);
                                    $content .= $sg['hourly_rate'].'/hr</b></td>
                            </tr>
                            <tr>
                                <td width="20%"><strong>Date</strong></td>
                                <td width="20%" class="text-center"><strong>Time</strong></td>
                                <td width="25%" class="text-center"><strong>Day Type</strong></td>
                                <td width="20%" class="text-center"><strong>OT Request</strong></td>
                                <td width="15%" class="text-right"><strong>Hours</strong></td>
                            </tr>
                        ';
                        $total_hours_per_row = 0;
                        foreach($dtr_list as $dtr_list):
                            $date = date( "Ymd",strtotime($dtr_list['date']));
                            if($date >= $start && $date <= $end && $dtr_list['user_id'] == $user['id']):
                                $content .= '<tr>
                                    <td width="20%">'.date( "M d, Y (D)",strtotime( $dtr_list['date'])).'</td>
                                    <td width="20%" class="text-center">';
                                        $get_log = $this->dtr_model->get_all_by_where(['user_id' => $dtr_list['user_id'], 'date' => $dtr_list['date']]);
                                        foreach($get_log as $gl):
                                            $hour_diff = strtotime($gl['time_out'])-strtotime($gl['time_in']);
                                            $hours = date('H:i:s', $hour_diff);
                                            $hms = explode(":", $hours);
                                            $total = $hms[0] + ($hms[1]/60) + ($hms[2]/3600) - 1;
                                            $total_hours += $total;
                                            $total_hours_per_row += $total;
                                            $content .= date( "h:i a",strtotime($gl['time_in']))." - ".date( "h:i a",strtotime($gl['time_out']))."<br>";
                                        endforeach;
                                    $content .='</td>
                                    <td width="25%" class="text-center">';
                                        $h_list = $this->holidays_model->get_all();
                                        $date_compare = date( "Y-m-d",strtotime( $dtr_list['date']));
                                        $count_compare = 0;
                                        foreach($h_list as $h_list):
                                            if($h_list['date'] == $date_compare):
                                                $count_compare++;
                                                if($h_list['type'] == "regular"):
                                                    $content .= "Regular Holiday";
                                                elseif($h_list['type'] == "special"):
                                                    $content .= "Special Non-working Holiday";
                                                else:
                                                    $content .= "Special Working Holiday";
                                                endif;
                                            endif;
                                        endforeach;
                                    $content .='</td>
                                    <td width="20%" class="text-center">';
                                        $otr = $this->request_ot_model->get_ot($dtr_list['user_id'],$dtr_list['date']);
                                        if($otr):
                                            if($otr['status'] == "pending"):
                                                $content .='<i class="t-pending">'.$otr['status'].'</i>'; 
                                            elseif($otr['status'] == "approved"):
                                                $content .='<i class="t-approved">'.$otr['status'].'</i>';
                                            else:
                                                $content .='<i class="t-denied">'.$otr['status'].'</i>';
                                            endif;
                                        endif;
                                    $content .='</td>
                                    <td width="15%" class="text-right">'.$total_hours_per_row.' hrs</td>
                                </tr>';
                                $total_days++;
                                $total_hours_per_row = 0;
                            endif;
                        endforeach;
                        $content .= '<tr>
                            <td width="45%" class="bg-gray">Number of Days: '.$total_days.' day/s</td>
                            <td width="40%" class="bg-gray text-right">Total hours:</td>
                            <td width="15%" class="text-right bg-blue">'.$total_hours.' hrs</td>
                        </tr>
                        <tr><td width="100%"></td></tr>';
                    endif;
                    $total_days = 0;
                    $total_hours = 0;
                endif;
            endforeach;

            $content .= "
                <tr><td><b>Non-regular Payable Computation:</b></td></tr>
                <tr><td><b>Special Non-working Holiday:</b> No work no pay | Hours worked * Hourly Rate * 130%</td></tr>
                <tr><td><b>Special Non-working Holiday + Rest day:</b> Hours worked * Hourly Rate * 150%</td></tr>
                <tr><td><b>Special Working Holiday:</b> Hours worked * Hourly Rate</td></tr>
                <tr><td><b>Regular Holiday (With work):</b> Hours worked * Hourly Rate * 200%</td></tr>
                <tr><td><b>Regular Holiday (Without work):</b> Hourly rate * 8 hours</td></tr>
                <tr><td><b>Rest Day: </b> Hours worked * Hourly rate * 130%</td></tr>
                <tr><td><b>Night Differential (7PM onwards):</b> Hours worked * Hourly rate * 110%</td></tr>
                <tr><td><b>Overtime:</b> (Hours worked * Computed hourly rate) * 130%</td></tr>
                </tbody></table>
            ";  
            $pdf->writeHTML($content);
            ob_end_clean();
            $pdf->Output('Employee Dtr List.pdf', 'I');

            $this->session->unset_userdata('start-date');
            $this->session->unset_userdata('end-date');
        else:
            show_404();
        endif;
    }

    public function validate_end_date(){
        if(!$this->input->is_ajax_request()){ show_404(); }
        $start = new DateTime($_POST['start-date']);
        $end   = new DateTime($_POST['end-date']);
        if($start > $end){
          $this->form_validation->set_message("validate_end_date","End date must be greater than start date");
          return false;
        }
        return true;
    }

    public function dtr_details(){
        $total_hours_per_row = 0;
        $dtr  = $this->dtr_model->get_row($_POST['dtr_id']);
        $user = $this->users_model->get_row($dtr['user_id']);
        echo '
            <div class="row input-con">
                <div class="col-md-3 offset-md-1"><label>Name: </label></div>
                <div class="col-md-7"><div class="form-control">'.$user['name'].'</div></div>
              </div>

              <div class="row input-con">
                <div class="col-md-3 offset-md-1"><label>Date: </label></div>
                <div class="col-md-7"><div class="form-control">'.date( "M d, Y (D)",strtotime($dtr['date'])).'</div></div>
              </div>

              <div class="row input-con">
                <div class="col-md-3 offset-md-1"><label>Time: </label></div>
                <div class="col-md-7"><div class="form-control">';
                    $get_log = $this->dtr_model->get_all_by_where(['user_id' => $dtr['user_id'], 'date' => $dtr['date']]);
                    foreach($get_log as $gl):
                        $hour_diff = strtotime($gl['time_out'])-strtotime($gl['time_in']);
                        $hours = date('H:i:s', $hour_diff);
                        $hms = explode(":", $hours);
                        $total = $hms[0] + ($hms[1]/60) + ($hms[2]/3600) - 1;
                        $total_hours_per_row += $total;

                        echo date( "h:i a",strtotime($gl['time_in']))." - ".date( "h:i a",strtotime($gl['time_out']))."<br>";
                    endforeach;
                echo '</div></div>
                <div class="col-md-7 offset-md-4"><div class="form-control">Total: '.$total_hours_per_row.' hrs</div></div>
              </div>
              <div class="row">
                <div class="col-md-10 offset-md-1"><hr></div>
              </div>
              <div class="row input-con">
                <div class="col-md-3 offset-md-1"><label>Shift: </label></div>
                <div class="col-md-7"><div class="form-control">';
                    $get_shift = $this->dtr_model->get_all_by_where(['user_id' => $dtr['user_id'], 'date' => $dtr['date'], 'shift_reason !=' => NULL]);
                    if($get_shift):
                        foreach($get_shift as $gs):
                            echo $gs["shift_reason"]."<br>";
                        endforeach;
                    else:
                        echo "---";
                    endif;
                echo '</div></div>
              </div>

              <div class="row input-con">';
                echo '<div class="col-md-3 offset-md-1"><label class="w-100">Overtime: </label>';
                    $otr = $this->request_ot_model->get_ot($dtr['user_id'],$dtr['date']);
                    if($otr){
                        if($otr['status'] == "pending"):
                            echo "<i class='t-green status'>Pending</i>";
                        elseif($otr['status'] == "approved"):
                            echo "<i class='t-blue status'>Approved</i>";
                        elseif($otr['status'] == "denied"):
                            echo "<i class='t-red status'>Denied</i>";
                        endif;
                    }
                echo'</div>
                <div class="col-md-7"><div class="form-control">';
                    if($otr):
                        $times = explode(" ",$otr['time']);
                        for($a=0;$a<count($times);$a++):
                            if($times[$a]):
                                $in_out = explode("-",$times[$a]);
                                echo date( "h:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
                            endif;
                        endfor;
                    else:
                        echo "---";
                    endif;
                echo'</div></div>
              </div>
        ';
    }
}