<?php
/*
 * Page Name: Employee
 * Author: Jushua FF
 * Date: 09.11.2022
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
				<h3 class="p-0 m-0">My DTR</h3>
				<input type="text" class="search-bar" placeholder="&#xF002;" id="my-dtr-list-search">
			</div>
			<div id="body-row">
				<?php $dtr_list = $this->dtr_model->get_all_my_dtr_desc($id); ?>
				<table id="my-dtr-list" class="table table-striped w-100">
				    <thead>
				        <tr>
				        	<th title="Sort by date">Date</th>
				            <th class="text-center tablet-show" title="Sort by time">Time</th>
				            <th class="text-center tablet-hide" title="Sort by time-in">Time-in</th>
				            <th class="text-center tablet-hide" title="Sort by time-out">Time-out</th>
				            <th title="Sort by work-base" class="w500-hide">Work Base</th>
				            <th title="Sort by shift" class="tablet-hide">Shift</th>
				            <th></th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				            foreach($dtr_list as $dtr_list):
			                    echo '
			                        <tr tr-id="'.$dtr_list['id'].'" class="'; if(!$dtr_list['time_out']){echo "active";}  echo'">
			                            <td class="date">'.date( "M d, Y (D)",strtotime( $dtr_list['date'])).'</td>
			                            <td class="time text-center tablet-show">'.date( "h:i a",strtotime( $dtr_list['time_in'])).' - ';
			                            	if($dtr_list['time_out']): echo date( "h:i a",strtotime( $dtr_list['time_out'])); else: echo '---'; endif;
			                            echo'</td>
			                            <td class="time-in text-center tablet-hide">'.date( "h:i a",strtotime( $dtr_list['time_in'])).'</td>
			                            <td class="time-out text-center tablet-hide">';
			                            	if($dtr_list['time_out']): echo date( "h:i a",strtotime( $dtr_list['time_out'])); else: echo '---'; endif;
			                            echo '</td>
			                            <td class="work-base w500-hide">'.$dtr_list['work_base'].'</td>
			                            <td class="shift-reason tablet-hide">';
			                            	if($dtr_list['shift_reason']): echo $dtr_list['shift_reason']; else: echo 'No'; endif;
			                            echo '</td>
			                            <td>
			                            	<button class="btn edit edit-dtr" data-toggle="modal" data-target="#dtr-cru-modal" title="'; if($dtr_list['time_out']){echo "Edit";}else{echo "Update";}  echo' DTR" dtr-id="'.$dtr_list['id'].'" user-id="'.$dtr_list['user_id'].'">'; 
			                            		if($dtr_list['time_out']){
			                            			echo '<i class="fas fa-edit"></i> Edit';
			                            		}else{
			                            			echo '<i class="fas fa-rotate"></i> Update';
			                            		}  
			                            	echo'</button>
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

<?= $this->load->view('modal/request-ot-cru-modal') ?>

