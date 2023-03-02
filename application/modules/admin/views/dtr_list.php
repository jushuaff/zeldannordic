<?php
/*
 * Page Name: DTR List
 * Author: Jushua FF
 * Date: 02.06.2023
 */
$user_type_name = $this->users_type_model->get_one_by_where(['id'=>$user_type]);
?>
<div class="w-100 d-flex">
	<?= $this->load->view("partials/sidebar", $user_type_name) ?>
	<div class="w-100 toggled" id="main">
		<?= $this->load->view("partials/header") ?>
		<div id="main-div">
			<div class="table-search-row">
				<h3 class="p-0 m-0">DTR List</h3>
				<div class="d-flex align-items-center w500-100">
					<button class="btn btn-maroon download-dtr-list" data-toggle="modal" data-target="#download-dtr-list-filter-modal" title="Download Outstanding DTR Report"><i class="fa fa-file-pdf"></i></button>
					<input type="text" class="search-bar" placeholder="&#xF002;" id="dtr-list-search">
				</div>
			</div>
			<div id="body-row">
				<table id="dtr-list" class="table table-striped w-100">
				    <thead>
				        <tr class="bg-lblue align-middle text-white">
				            <th title="Sort by name">Name</th>
				            <th title="Sort by date">Date</th>
				            <th title="Sort by time-in" class="w405-hide">Time</th>
				            <th title="Sort by shift" class="desktop-hide">Shift</th>
				            <th title="Sort by overtime" class="desktop-hide">Overtime</th>
				            <th title="Sort by hours worked" class="text-right tablet-hide">Total hours</th>
				            <th class="desktop-show"></th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				        	$dtr = $this->dtr_model->get_all_by_group_desc();
				        	foreach($dtr as $dtr):
				        		$total_hours_per_row = 0;
				        		$user = $this->users_model->get_one_by_where(['id'=>$dtr['user_id']]);
				        		echo '
				        			<tr tr-id="'.$dtr['id'].'">
				        				<td>'.$user['name'].'</td>
				        				<td>'.date( "M d, Y (D)",strtotime( $dtr['date'])).'</td>
				        				<td class="w405-hide">';
				        					$get_log = $this->dtr_model->get_all_by_where(['user_id' => $dtr['user_id'], 'date' => $dtr['date']]);
	                                        foreach($get_log as $gl):
	                                            $hour_diff = strtotime($gl['time_out'])-strtotime($gl['time_in']);
	                                            $hours = date('H:i:s', $hour_diff);
	                                            $hms = explode(":", $hours);
	                                            $total = $hms[0] + ($hms[1]/60) + ($hms[2]/3600) - 1;
	                                            $total_hours_per_row += $total;

	                                            echo date( "h:i a",strtotime($gl['time_in']))." - ".date( "h:i a",strtotime($gl['time_out']))."<br>";
	                                        endforeach;
				        				echo '</td>
				        				<td class="desktop-hide">';
				        					$get_shift = $this->dtr_model->get_all_by_where(['user_id' => $dtr['user_id'], 'date' => $dtr['date'], 'shift_reason !=' => NULL]);
	                                        foreach($get_shift as $gs):
	                                            echo $gs["shift_reason"]."<br>";
	                                        endforeach;
				        				echo '</td>
				        				<td class="desktop-hide">';
				        					$otr = $this->request_ot_model->get_ot($dtr['user_id'],$dtr['date']);
	                                        if($otr):
	                                            $times = explode(" ",$otr['time']);
	                                            for($a=0;$a<count($times);$a++):
	                                                if($times[$a]):
	                                                    $in_out = explode("-",$times[$a]);
	                                                    echo date( "h:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
	                                                endif;
	                                            endfor;
	                                        endif;
				        				echo'</td>
				        				<td class="text-right tablet-hide">'.$total_hours_per_row.' hrs</td>
				        				<td class="desktop-show text-center">
				        					<button class="btn btn-blue dtr-details" data-toggle="modal" data-target="#view-dtr-details-modal" dtr-id="'.$dtr['id'].'" title="View Details"><i class="fas fa-eye"></i> Details</button>
				        				</td>
				        			</tr>
				        		';
				        		$total_hours_per_row = 0;
				        	endforeach
				        ?>
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?= $this->load->view('modal/salary-grade-cru-modal') ?>
<?= $this->load->view('modal/ot-status-modal') ?>
<?= $this->load->view('modal/outstanding-dtr-modal') ?>
<?= $this->load->view('modal/download-dtr-list-filter-modal') ?>
<?= $this->load->view('modal/view-dtr-details-modal') ?>
