<?php 
$arrUser=array();
$source_employer=array();
$source_user=array();
$user_fullname='';
$ssNameUser='vmuser';
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
	$source_employer=\App\EmployerModel::find((int)@$arrUser['id']);
	if($source_employer != null){
		$data_employer=$source_employer->toArray();
		$source_user=\App\User::find((int)@$data_employer['user_id']);	
		if($source_user != null){
			$source_user=$source_user->toArray();
			$user_fullname=$source_user['fullname'];
		}
	}
} 
?>
<div class="box-category">
	<div class="menu-right-title">DANH MỤC</div>
	<div>
		<ul class="categoryarticle">
			<li><a href="<?php echo route('frontend.index.viewEmployerAccount'); ?>"><div class="category-icon"><i class="far fa-user"></i></div><div>Tài khoản</div></a></li>
			<li><a href="<?php echo route('frontend.index.getFormSearchProfile'); ?>"><div class="category-icon"><i class="fas fa-search"></i></div><div>Tìm hồ sơ ứng viên</div></a></li>
			<li><a href="<?php echo route('frontend.index.getFormRecruitment',['add',0]); ?>"><div class="category-icon"><i class="fab fa-spotify"></i></div><div>Đăng tin tuyển dụng</div></a></li>
			<li><a href="<?php echo route('frontend.index.manageRecruitment'); ?>"><div class="category-icon"><i class="fas fa-columns"></i></div><div>Quản lý tin tuyển dụng</div></a></li>
			<li><a href="<?php echo route('frontend.index.viewSavedProfile'); ?>"><div class="category-icon"><i class="fas fa-users"></i></div><div>Hồ sơ đã lưu</div></a></li>
			<li><a href="<?php echo route('frontend.index.viewAppliedProfile'); ?>"><div class="category-icon"><i class="far fa-file-alt"></i></div><div>Hồ sơ ứng viên đã nộp vào</div></a></li>		
			<li><a href="javascript:void(0);"><div class="category-icon"><i class="far fa-file-alt"></i></div><div>CSKH : <?php echo $user_fullname; ?></div></a></li>		
			<li class="category-article-last"><a href="<?php echo route('frontend.index.logoutEmployer'); ?>"><div class="category-icon"><i class="fas fa-sign-out-alt"></i></div><div>Đăng xuất</div></a></li>
		</ul>
	</div>
</div>