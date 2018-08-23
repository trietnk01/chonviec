<div class="box-category">
	<div class="menu-right-title">DANH MỤC</div>
	<div>
		<ul class="categoryarticle">
			<li><a href="<?php echo route('frontend.index.viewCandidateAccount'); ?>"><div class="category-icon"><i class="far fa-user"></i></div><div>Tài khoản</div></a></li>
			<li><a href="<?php echo route('frontend.index.viewProfileCabinet'); ?>"><div class="category-icon"><i class="fas fa-search"></i></div><div>Tủ hồ sơ</div></a></li>
			<li><a href="<?php echo route('frontend.index.getFormProfile',['add',0]); ?>"><div class="category-icon"><i class="fab fa-spotify"></i></div><div>Tạo mới hồ sơ</div></a></li>
			<li><a href="javascript:void(0);"><div class="category-icon"><i class="fas fa-sync"></i></div><div>Làm đẹp hồ sơ</div></a></li>
			<li><a href="<?php echo route('frontend.index.viewSavedRecruitment'); ?>"><div class="category-icon"><i class="fas fa-columns"></i></div><div>Việc làm đã lưu</div></a></li>
			<li><a href="<?php echo route('frontend.index.viewAppliedRecruitment'); ?>"><div class="category-icon"><i class="fas fa-users"></i></div><div>Việc làm đã ứng tuyển</div></a></li>				
			<li class="category-article-last"><a href="<?php echo route('frontend.index.logoutCandidate'); ?>"><div class="category-icon"><i class="fas fa-sign-out-alt"></i></div><div>Đăng xuất</div></a></li>
		</ul>
	</div>
</div>