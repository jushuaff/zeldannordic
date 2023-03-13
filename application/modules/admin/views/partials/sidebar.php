<div class="toggled" id="sidebar">
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
	<!--  -->
	<button class="drop-button" target="#employee-collapse">
		<div class="w-100">
			<i class="fas fa-users left"></i>Accounts
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="employee-collapse">
		<button id="view-employees"><a href="<?= base_url("admin") ?>"><i class="fas fa-list"></i>Employee list</a></button>
		<button id="archived-employees"><a href="<?= base_url("admin/archived_employee") ?>"><i class="fas fa-archive"></i>Archived Accounts</a></button>
		<button id="add-employee" data-toggle="modal" data-target="#employee-cru-modal"><i class="fas fa-user-plus"></i>Add employee</button>
	</div>

	<button class="drop-button" target="#dtr-collapse">
		<div class="w-100">
			<i class="fas fa-calendar-check left"></i>Daily Time Record
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="dtr-collapse">
		<button id="active-dtr">
			<a href="<?= base_url("admin/active_dtr") ?>">
				<i class="fas fa-calendar-check"></i>Active DTR
			</a>
		</button>
		<!--button id="outstanding-dtr"><a href="<?= base_url("admin/outstanding_dtr") ?>"><i class="fas fa-coins"></i>Outstanding DTR</button-->
		<button id="dtr-record"><a href="<?= base_url("admin/dtr_list") ?>"><i class="fas fa-user-tie"></i>DTR List</a></button>
	</div>

	<button class="drop-button">
		<div class="w-100">
			<a class="otr-link" href="<?= base_url("admin/overtime_requests") ?>">
				<i class="fas fa-clock left"></i>Overtime Request
			</a>
		</div>
	</button>

	<button class="drop-button" target="#salary-grade">
		<div class="w-100">
			<i class="fas fa-credit-card left"></i>Salary Grade
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="salary-grade">
		<button><a href="<?= base_url("admin/salary_grade") ?>"><i class="fas fa-coins"></i>Salary Grade List</a></button>
		<button id="add-salary-grade" data-toggle="modal" data-target="#salary-grade-cru-modal"><i class="fas fa-plus"></i>Add Grade</button>
	</div>

	<button class="drop-button" target="#holidays">
		<div class="w-100">
			<i class="fa-solid fa-calendar left"></i>Holidays
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="holidays">
		<button><a href="<?= base_url("admin/holidays") ?>"><i class="fas fa-calendar-day"></i>Holiday List</a></button>
		<button><a class="d-flex" href="<?= base_url("admin/custom_holidays") ?>"><i class="fas fa-calendar-edit d-flex align-items-center"></i>Custom Holidays</a></button>
		<button id="add-salary-grade" data-toggle="modal" data-target="#holiday-cru-modal"><i class="fas fa-plus"></i>Add Custom</button>
	</div>

	<button class="drop-button" target="#dev-setting">
		<div class="w-100">
			<i class="fa-brands fa-dev left"></i>Developer Options
		</div>
		<i class="fas fa-angle-down right"></i>
	</button>
	<div class="collapsible" id="dev-setting">
		<?php
			$temps = 0;
			$fileList = glob('assets_module/user_profile/*');
		    foreach($fileList as $filename){
		        if(is_file($filename)){
		            $profile = $this->users_model->get_one_by_where(['profile_pic' => basename($filename)]);
		            if(!$profile){
		            	$temps++;
		                break;
		            }
		        }   
		    }
		?>
		<button id="temp-button" <?= ($temps == 0) ? 'class="disabled" title="There are no temporary files"':'data-toggle="modal" data-target="#delete-temp-modal"' ?>><i class="fas fa-trash"></i>Delete Temp <?php if($temps == 0){echo '<span class="ongoing-temp-message">There are no temporary files</span>';} ?></button>
		<?php
			//get all the dynamic holidays
			//
		?>
		<button><i class="fa fa-refresh"></i>Holidays</button>
	</div>
</div>

<?= $this->load->view('modal/salary-grade-cru-modal'); ?>
<?= $this->load->view('modal/holiday-cru-modal'); ?>
<?= $this->load->view('modal/delete-temp-modal'); ?>