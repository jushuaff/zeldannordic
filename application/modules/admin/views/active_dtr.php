<?php
/*
 * Page Name: Active DTR
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
				<h3 class="p-0 m-0">Active DTR</h3>
				<div>
					<input type="text" class="search-bar" placeholder="&#xF002;" id="adtr-list-search">
				</div>
			</div>
			<div id="body-row">
				<?php $dtr = $this->dtr_model->get_all_active_dtr_desc(); ?>
				<table id="adtr-list" class="table table-striped">
				    <thead>
				        <tr>
				            <th title="Sort by name">Name</th>
				            <th title="Sort by date">Date</th>
				            <th title="Sort by time-in">Time-in</th>
				            <th title="Sort by work-base">Work Base</th>
				            <th title="Sort by shift">Shift</th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				            foreach($dtr as $dtr):
				            	$shift = ($dtr['work_base'])?$dtr['work_base']:"no";
			                    echo '
			                        <tr tr-id="'.$dtr['id'].'">
			                            <td class="date">'.$dtr['name'].'</td>
			                            <td class="date">'.date( "M d, Y (D)",strtotime( $dtr['date'])).'</td>
			                            <td class="time-in">'.date( "H:i a",strtotime( $dtr['time_in'])).'</td>
			                            <td class="work-base">'.$dtr['work_base'].'</td>
			                            <td class="shift">'.$shift.'</td>
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
<?= $this->load->view('modal/ot-status-modal'); ?>
