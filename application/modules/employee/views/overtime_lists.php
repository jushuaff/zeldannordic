<?php
/*
 * Page Name: Overtime Lists
 * Author: Jushua FF
 * Date: 02.01.2023
 */
$user_type_name = $this->users_type_model->get_one_by_where(['id'=>$user_type]);
$user_type_name['user_id'] = $id;
?>
<div class="w-100 d-flex">
	<?= $this->load->view("partials/sidebar", $user_type_name) ?>
	<div class="w-100 toggled" id="main">
		<?= $this->load->view("partials/header") ?>
		<div id="main-div">
			<div class="table-search-row">
				<h3 class="p-0 m-0">Overtime List</h3>
				<input type="text" class="search-bar" placeholder="&#xF002;" id="overtime-list-search">
			</div>
			<div id="body-row">
				<?php $ot_list = $this->request_ot_model->get_all_my_request_desc($id); ?>
				<table id="overtime-list" class="table table-striped w-100">
				    <thead>
				        <tr>
				        	<th title="Sort by date">Date</th>
				            <th title="Sort by task" class="w500-hide">Task</th>
				            <th title="Sort by time" class="w767-hide">Time</th>
				            <th title="Sort by status" class="text-center">Status</th>
				            <th></th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				            foreach($ot_list as $ot_list):
			                    echo '
			                        <tr tr-id="'.$ot_list['id'].'">
			                            <td class="date">'.date( "M d, Y (D)",strtotime( $ot_list['date'])).'</td>
			                            <td class="task w500-hide">';
			                            	$task = explode(PHP_EOL, $ot_list['task']);
			                            	for($a=0;$a<count($task);$a++){
			                            		echo "&#x2022; ".$task[$a]."<br>";
			                            	}
			                            echo '</td>
			                            <td class="time w767-hide">';
			                            	$times = explode(" ",$ot_list['time']);
			                            	for($a=0;$a<count($times);$a++){
			                            		if($times[$a]){
				                            		$in_out = explode("-",$times[$a]);
				                            		echo date( "H:i a",strtotime( $in_out[0])).' - '.date( "h:i a",strtotime( $in_out[1])).'<br>';
				                            	}
			                            	}
			                            echo'</td>
			                            <td class="status text-center">';
			                            	if($ot_list['status'] == "pending"):
			                            		echo "<span class='t-green'>Pending</span>";
			                            	elseif($ot_list['status'] == "approved"):
			                            		echo "<span class='t-blue'>Approved</span>";
			                            	elseif($ot_list['status'] == "denied"):
			                            		echo "<span class='t-red'>Denied</span>";
			                            	endif;
			                            echo'</td>
			                            <td class="text-center">
			                            	<button class="btn edit edit-ot-request" data-toggle="modal" data-target="#request-ot-cru-modal" title="Edit Overtime Request" otr-id="'.$ot_list['id'].'"><i class="fas fa-edit"></i> Edit
			                            	</button>';
			                            	if($ot_list['status'] == "pending"){
			                            		echo '<button class="btn delete delete-request" data-toggle="modal" data-target="#delete-otr-modal" title="Delete Overtime Request" otr-id="'.$ot_list['id'].'"><i class="fas fa-trash"></i> Delete
			                            		</button>';
			                            	}
			                            	
			                            echo '</td>
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

<?= $this->load->view('modal/request-ot-cru-modal') ?>
<?= $this->load->view('modal/delete-otr-modal') ?>

