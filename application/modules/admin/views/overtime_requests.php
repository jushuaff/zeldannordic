<?php
/*
 * Page Name: Overtime Requests
 * Author: Jushua FF
 * Date: 01.30.2023
 */
$user_type_name = $this->users_type_model->get_one_by_where(['id'=>$user_type]);
?>
<div class="w-100 d-flex">
	<?= $this->load->view("partials/sidebar", $user_type_name) ?>
	<div class="w-100 toggled" id="main">
		<?= $this->load->view("partials/header") ?>
		<div id="main-div">
			<div class="table-search-row">
				<h3 class="p-0 m-0">Overtime Request</h3>
				<input type="text" class="search-bar" placeholder="&#xF002;" id="aotr-list-search">
			</div>
			<div id="body-row">
				<?php $otr = $this->request_ot_model->get_all_ot(); ?>
				<table id="aotr-list" class="table table-striped w-100">
				    <thead>
				        <tr>
				            <th title="Sort by name">Name</th>
				            <th title="Sort by task" class="desktop-hide">Task/s</th>
				            <th title="Sort by date" class="w405-hide">Date</th>
				            <th title="Sort by time" class="desktop-hide">Time</th>
				            <th title="Sort by status">Status</th>
				            <th></th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				        	/*
				        		Notes: 
				        		-> Edit status button will be removed once salary is given
				        		-> An Approved ot will no longer be updated once the requester already logged a DTR on the given date
				        			-> Date is validated by online date and time
				        			-> User cannot log an overtime dtr until the date specified is reached
				        			-> If the user forgot to log DTR, the user can still log after the given date
				        				-> Drawbacks is if the admin forgot to approve the status
				        	*/
				            foreach($otr as $otr):
				            	$check_paid = $this->dtr_model->check_if_paid($otr['user_id'],$otr['date']);
			                    echo '
			                        <tr tr-id="'.$otr['id'].'">
			                            <td class="name">'.$otr['name'].'</td>
			                            <td class="task desktop-hide">';
			                            	$task = explode(PHP_EOL, $otr['task']);
			                            	for($a=0;$a<count($task);$a++){
			                            		echo "&#x2022; ".$task[$a]."<br>";
			                            	}
			                            echo '</td>
			                            <td class="date w405-hide">'.date( "M d, Y (D)",strtotime( $otr['date'])).'</td>
			                            <td class="time desktop-hide">';
			                            	$times = explode(" ",$otr['time']);
			                            	for($a=0;$a<count($times);$a++){
			                            		if($times[$a]){
				                            		$in_out = explode("-",$times[$a]);
				                            		echo date( "h:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
				                            	}
			                            	}
			                            echo '</td>
			                            <td class="status">';
			                            	if($otr['status'] == "pending"):
			                            		echo "<span class='t-green'>Pending</span>";
			                            	elseif($otr['status'] == "approved"):
			                            		echo "<span class='t-blue'>Approved</span>";
			                            	elseif($otr['status'] == "denied"):
			                            		echo "<span class='t-red'>Denied</span>";
			                            	endif;
			                            echo '</td>
			                            <td class="d-flex flex-wrap justify-content-center">
			                            	<button class="update-ot-status btn edit" data-toggle="modal" data-target="#ot-status-modal" title="Update Status" rot-id="'.$otr['id'].'" '; if($check_paid){echo"disabled";} echo'>
			                            		<i class="fas fa-edit"></i> Status
			                            	</button>
			                            	<button class="btn btn-blue otr-details desktop-show" data-toggle="modal" data-target="#view-otr-details-modal" title="View Details" otr-id="'.$otr['id'].'">
			                            		<i class="fas fa-eye"></i> Details
			                            	</button>
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

<?= $this->load->view('modal/salary-grade-cru-modal'); ?>
<?= $this->load->view('modal/ot-status-modal'); ?>
<?= $this->load->view('modal/view-otr-details-modal'); ?>
