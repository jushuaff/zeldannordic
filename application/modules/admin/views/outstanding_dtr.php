<?php
/*
 * Page Name: Outstanding DTR
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
				<h3 class="p-0 m-0">Outstanding DTR</h3>
				<div class="d-flex align-items-center">
					<button class="btn btn-maroon view-group" id="odtr-view-by" title="Group view"><i class="fas fa-users"></i></button>
					<button class="btn btn-maroon download-outstanding-dtr" title="Download Outstanding DTR Report"><i class="fa fa-file-pdf"></i></button>
					<input type="text" class="search-bar" placeholder="&#xF002;" id="odtr-list-search">
				</div>
			</div>
			<div id="body-row">
				<?php $dtr = $this->dtr_model->outstanding_dtr_desc(); ?>
				<table id="odtr-table-list-view" class="table table-striped">
				    <thead>
				        <tr class="bg-lblue align-middle text-white">
				            <th rowspan="2" title="Sort by name">Name</th>
				            <th rowspan="2" title="Sort by date">Date</th>
				            <th rowspan="2" class="text-center" title="Sort by hours">Hours</th>
				            <th colspan="2" class="text-center">Work</th>
				            <th rowspan="2"></th>
				        </tr>
				        <tr class="text-center">
				        	<th title="Sort by Shift">Shift</th>
				        	<th title="Sort by Overtime">Overtime</th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				        	$total_hours = 0;
				            foreach($dtr as $dtr):
				            	$shift = ($dtr['shift_reason'] !== NULL)?"<i class='fas fa-check t-green'></i>":"";
				            	
				            	$th = $this->dtr_model->get_hours($dtr['user_id'],$dtr['date']);
				            	foreach($th as $th):
				            		$hour_diff = strtotime($th['time_out'])-strtotime($th['time_in']);
					            	$hours = date('H:i:s', $hour_diff);
					            	$hms = explode(":", $hours);
					            	$total_hours += $hms[0] + ($hms[1]/60) + ($hms[2]/3600) - 1;
				            	endforeach;

				            	$ot = $this->request_ot_model->get_one_by_where(['user_id'=>$dtr['user_id'],'date'=>$dtr['date']]);
				            	if($ot): $otr = '<i class="fas fa-check t-green"></i><i class="t-12px">('.$ot['status'].')</i>';
				            	else: $otr = '';
					            endif;

			                    echo '
			                        <tr tr-id="'.$dtr['id'].'">
			                            <td class="date">'.$dtr['name'].'</td>
			                            <td class="date">'.date( "M d, Y (D)",strtotime( $dtr['date'])).'</td>
			                            <td class="hours text-center">'.$total_hours.' Hours</td>
			                            <td class="shift text-center">'.$shift.'</td>
			                            <td class="overtime text-center">'.$otr.'</td>
			                            <!--td class="text-center">
			                            	<button class="btn btn-blue view-odtr" user-id="'.$dtr['user_id'].'" date="'.$dtr['date'].'" data-toggle="modal" data-target="#outstanding-dtr-modal"><i class="fas fa-eye"></i> Details</button>
			                            </td-->
			                        </tr>
			                    ';
			                    $total_hours = 0;
				            endforeach;
				        ?>
				    </tbody>
				</table>

				<?php $dtr_group = $this->dtr_model->outstanding_dtr_group_desc(); ?>
				<table id="odtr-group-list-view" class="table table-striped d-none">
					<thead>
				        <tr class="bg-lblue align-middle text-white">
				            <th title="Sort by name">Name</th>
				            <th class="text-center" title="Sort by user type">User Type</th>
				            <th></th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				        	foreach($dtr_group as $dtr):
				        		$user_type = $this->users_type_model->get_one_by_where(['id'=>$dtr["user_type"]]);
			                    echo '
			                        <tr tr-id="'.$dtr['id'].'">
			                            <td class="date">'.$dtr['name'].'</td>
			                            <td class="user-type text-center">'.$user_type['user_type'].'</td>
			                            <td class="text-center">
			                            	<!--button class="btn btn-blue view-odtr" user-id="'.$dtr['user_id'].'" date="'.$dtr['date'].'" data-toggle="modal" data-target="#outstanding-dtr-modal"><i class="fas fa-eye"></i> Details</button-->
			                            	<button class="btn btn-blue view-dtr-list"><i class="fas fa-list"></i> DTR List</button>
			                            	<button class="btn btn-blue view-overtime-list"><i class="fas fa-calendar-alt"></i> Overtime</button>
			                            	<button class="btn btn-blue view-summary"><i class="fas fa-list-alt"></i> Summary</button>
			                            </td>
			                        </tr>
			                    ';
			                endforeach;
				        ?>
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?= $this->load->view('modal/ot-status-modal') ?>
<?= $this->load->view('modal/outstanding-dtr-modal') ?>
