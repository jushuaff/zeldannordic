<div class="toggled m-hide" id="sidebar">
	<div class="logo-con">
		<button class="sidebar-toggle" title="Hide sidebar">
			<i class="fas fa-angle-left"></i>
		</button>
		<img class="logo" src="<?= base_url("assets/img/nlrc-logo-white.png") ?>">
		<div class="profile-con">
			<img class="w-100" src="
				<?php
					if($profile_pic):
						echo base_url("assets_module/user_profile/{$profile_pic}");
					else:
						echo base_url("assets/img/".($gender=='Male' ? "male": "female")."-default.jpg");
					endif;
				?>
			">
		</div>
		<h5 class="name" title="<?= $name ?>"><?= $name ?></h5>
		<b class="user-type">(<?= $user_type ?>)</b>
	</div>

	<button class="drop-button" target="#dtr-collapse">
		<div class="w-100">
			<i class="fa-solid fa-clock left"></i>Daily Time Record
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="dtr-collapse">
		<?php 
			$read_grade = $this->salary_grade_model->get_row($salary_grade);
			$salary = $read_grade['hourly_rate'];

			$active = $this->dtr_model->count_by_where(['user_id'=>$user_id,'time_out'=>NULL]);
		?>
		<button id="dtr-today" <?php if($active>0){echo 'class="disabled" title="You still have an active dtr"';}else{echo 'data-toggle="modal" data-target="#dtr-cru-modal"';} ?> user-id="<?= $user_id ?>" per_hour="<?= $salary ?>" >
			<i class="fas fa-calendar"></i>Create <?php if($active>0){echo '<span class="ongoing-dtr-message">You still have an active dtr</span>';} ?>
		</button>
		<button id="my-dtr"><a href="<?= base_url("employee"); ?>"><i class="fas fa-list"></i>My DTR</a></button>
	</div>

	<button class="drop-button" target="#ot-collapse">
		<div class="w-100">
			<i class="fa-solid fa-calendar left"></i>Overtime
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="ot-collapse">
		<button id="request-ot" data-toggle="modal" data-target="#request-ot-cru-modal" user-id="<?= $user_id ?>"><i class="request-overtime"></i>Request Overtime</button>
		<button id="ot-lists"><a href="<?= base_url("employee/overtime_lists"); ?>"><i class="fas fa-list"></i>Overtime Lists</a></button>
	</div>
</div>