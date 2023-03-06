<?php
/*
 * Page Name: Holidays
 * Author: Jushua FF
 * Date: 03.03.2023
 */
$user_type_name = $this->users_type_model->get_one_by_where(['id'=>$user_type]);
?>
<div class="w-100 d-flex">
	<?= $this->load->view("partials/sidebar", $user_type_name) ?>
	<div class="w-100 toggled" id="main">
		<?= $this->load->view("partials/header") ?>
		<div id="main-div">
			<div class="table-search-row">
				<h3 class="p-0 m-0">Holiday List</h3>
				<input type="text" class="search-bar" placeholder="&#xF002;" id="holiday-list-search">
			</div>
			<div id="body-row">
				<?php $h_list = $this->holidays_model->get_all_sort_date(); ?>
				<table id="holiday-list" class="table table-striped">
				    <thead>
				        <tr>
				            <th title="Sort by name">Name</th>
				            <th title="Sort by email">Date</th>
				            <th title="Sort by type">Type</th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php 
				            foreach($h_list as $h_list):
			                    echo '
			                        <tr tr-id="'.$h_list['id'].'">
			                            <td class="name">'.$h_list['name'].'</td>
			                            <td class="date">'.date( "M d, Y (D)",strtotime( $h_list['date'])).'</td>
			                            <td class="type">';
			                            	if($h_list['type'] == "regular"):
                                                echo "Regular Holiday";
                                            elseif($h_list['type'] == "special"):
                                                echo "Special Non-working Holiday";
                                            else:
                                                echo "Special Working Holiday";
                                            endif;
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
