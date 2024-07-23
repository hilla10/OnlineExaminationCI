<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?=base_url()?>assets/dist/img/usersys-min.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?=$user->username?></p>
				<small><?=$user->email?></small>
			</div>
		</div>
		
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MAIN MENU</li>
			<!-- Optionally, you can add icons to the links -->
			<?php 
			$page = $this->uri->segment(1);
			$master = ["department", "class", "course", "lecturer", "student"];
			$relation = ["classLecturer", "departmentCourse"];
			$users = ["users"];
			?>
			<li class="<?= $page === 'dashboard' ? "active" : "" ?>"><a href="<?=base_url('dashboard')?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<?php if($this->ion_auth->is_admin()) : ?>
			<li class="treeview <?= in_array($page, $master)  ? "active menu-open" : ""  ?>">
				<a href="#"><i class="fa fa-folder-open"></i> <span>Master Data</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?=$page==='department'?"active":""?>">
						<a href="<?=base_url('department')?>">
							<i class="fa fa-bars"></i> 
							 Department
						</a>
					</li>
					<li class="<?=$page==='class'?"active":""?>">
						<a href="<?=base_url('class')?>">
							<i class="fa fa-bars"></i>
							 Class
						</a>
					</li>
					<li class="<?=$page==='course'?"active":""?>">
						<a href="<?=base_url('course')?>">
							<i class="fa fa-bars"></i>
							 Course
						</a>
					</li>
					<li class="<?=$page==='lecturer'?"active":""?>">
						<a href="<?=base_url('lecturer')?>">
							<i class="fa fa-bars"></i>
							 Lecturer
						</a>
					</li>
					<li class="<?=$page==='student'?"active":""?>">
						<a href="<?=base_url('student')?>">
							<i class="fa fa-bars"></i>
							 Student
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?= in_array($page, $relation)  ? "active menu-open" : ""  ?>">
				<a href="#"><i class="fa fa-link"></i> <span>Relation</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?=$page==='classLecturer'?"active":""?>">
						<a href="<?=base_url('classLecturer')?>">
							<i class="fa fa-bars"></i>
							Class - Lecturer
						</a>
					</li>
					<li class="<?=$page==='departmentCourse'?"active":""?>">
						<a href="<?=base_url('departmentCourse')?>">
							<i class="fa fa-bars"></i>
							Department - Course
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->is_admin() || $this->ion_auth->in_group('Lecturer') ) : ?>
			<li class="<?=$page==='question'?"active":""?>">
				<a href="<?=base_url('question')?>" rel="noopener noreferrer">
					<i class="fa fa-file-text"></i> <span>Question Bank</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->in_group('Lecturer') ) : ?>
			<li class="<?=$page==='exam'?"active":""?>">
				<a href="<?=base_url('exam/master')?>" rel="noopener noreferrer">
					<i class="fa fa-pencil"></i> <span>Exam</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->in_group('Student') ) : ?>
			<li class="<?=$page==='exam'?"active":""?>">
				<a href="<?=base_url('exam/list')?>" rel="noopener noreferrer">
					<i class="fa fa-pencil"></i> <span>Exam</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( !$this->ion_auth->in_group('Student') ) : ?>
			<li class="header">REPORTS</li>
			<li class="<?=$page==='examResult'?"active":""?>">
				<a href="<?=base_url('examResult')?>" rel="noopener noreferrer">
					<i class="fa fa-file"></i> <span>Exam Results</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if($this->ion_auth->is_admin()) : ?>
			<li class="header">ADMINISTRATOR</li>
			<li class="<?=$page==='users'?"active":""?>">
				<a href="<?=base_url('users')?>" rel="noopener noreferrer">
					<i class="fa fa-users"></i> <span>User Management</span>
				</a>
			</li>
			<li class="<?=$page==='settings'?"active":""?>">
				<a href="<?=base_url('settings')?>" rel="noopener noreferrer">
					<i class="fa fa-cogs"></i> <span>Settings</span>
				</a>
			</li>
			<?php endif; ?>
		</ul>

	</section>
	<!-- /.sidebar -->
</aside>