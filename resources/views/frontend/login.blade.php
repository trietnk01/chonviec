@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="container">
	<div class="row">
		<div class="col-lg-6">
			<div class="register-bl">
				<div class="row">
					<div class="col-lg-4">
						<div class="profile-candidate"><i class="far fa-address-book"></i></div>
					</div>
					<div class="col-lg-8">
						<div class="register-candidate">ĐĂNG NHẬP ỨNG VIÊN</div>
						<ul class="gara">
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">+ 1,500,000 công việc được cập nhật thường xuyên</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Ứng tuyển công việc yêu thích HOÀN TOÀN MIỄN PHÍ</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Hiển thị thông tin hồ sơ với nhà tuyển dụng hàng đầu</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Nhận bản tin công việc phù hợp định kỳ</div>
								</div>								
							</li>
						</ul>
						<div class="register-pipi"><a href="<?php echo route('frontend.index.candidateLogin'); ?>">ĐĂNG NHẬP ỨNG VIÊN</a></div>
					</div>
				</div>				
			</div>
		</div>
		<div class="col-lg-6">
			<div class="register-bl">
				<div class="row">
					<div class="col-lg-4">
						<div class="profile-employer"><i class="far fa-address-book"></i></div>
					</div>
					<div class="col-lg-8">
						<div class="register-employer">ĐĂNG NHẬP NHÀ TUYỂN DỤNG</div>
						<ul class="gara">
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">+ 3,500,000 ứng viên tiếp cận thông tin tuyển dụng</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Không giới hạn tương tác với ứng viên qua hệ thống nhắn tin nội bộ miễn phí</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Quảng cáo thông minh giúp tin tuyển dụng được phủ rộng trên toàn bộ hệ thống</div>
								</div>								
							</li>
							<li>
								<div class="register-li">
									<div class="nicotin"><i class="fas fa-arrow-circle-right"></i></div>
									<div class="margin-left-5">Quảng cáo công ty trên Fanpage số 1 về việc làm - tuyển dụng</div>
								</div>								
							</li>
						</ul>
						<div class="register-lata"><a href="<?php echo route('frontend.index.employerLogin'); ?>">ĐĂNG NHẬP NHÀ TUYỂN DỤNG</a></div>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>
@endsection()               