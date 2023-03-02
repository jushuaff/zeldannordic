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
			<i class="fas fa-users left"></i>Employee Accounts
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
			<i class="fa-solid fa-clock left"></i>Employee DTR
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
				<i class="fa-solid fa-calendar left"></i>Overtime Request
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
</div>