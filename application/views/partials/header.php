<?php
/*
 * Page Name: Universal
 * Author: Jushua FF
 * Date: 01.17.2023
 */
$account_session = $this->session->userdata('account_session');
?>
<div class="w-100 head-row">
	<button class="sidebar-toggle" title="Hide sidebar">
		<i class="fas fa-angle-left"></i>
	</button>
	<div class="ml-auto d-flex">
		<div class="dropdown">
		  <button class="dropdown-toggle" id="account-setting" title="account setting" data-bs-toggle="dropdown"><i class="fas fa-cog"></i></button>
		  <ul class="dropdown-menu" aria-labelledby="account-setting">
		    <li><button class="edit-profile dropdown-item own-profile" user-id="<?= $account_session['id'] ?>" data-toggle="modal" data-target="#employee-cru-modal" user-type="n/a">Profile</button></li>
		    <li><button class="update-password dropdown-item own-password" user-id="<?= $account_session['id'] ?>" data-toggle="modal" data-target="#update-password-modal">Password</button></li>
		  </ul>
		</div>
		<a class="logout" href="<?= base_url('logout') ?>" title="log-out"><i class="fas fa-power-off"></i></a>
	</div>
</div>