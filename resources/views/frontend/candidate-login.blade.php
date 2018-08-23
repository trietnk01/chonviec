@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<!-- jp login wrapper start -->
	<div class="login_section">
		<!-- login_form_wrapper -->
		<form name="frm-candidate-login" method="POST" enctype="multipart/form-data" class="login_form_wrapper">
			{{ csrf_field() }}
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<!-- login_wrapper -->
						<h1>ĐĂNG NHẬP TÀI KHOẢN ỨNG VIÊN</h1>
						<div class="login_wrapper">
							<?php 
							if(count(@$msg) > 0){
								$type_msg='';					
								if((int)@$checked == 1){
									$type_msg='note-success';
								}else{
									$type_msg='note-danger';
								}
								?>
								<div class="note margin-top-15 margin-bottom-15 <?php echo $type_msg; ?>" >
									<ul>
										<?php 
										foreach (@$msg as $key => $value) {
											?>
											<li><?php echo $value; ?></li>
											<?php
										}
										?>                              
									</ul>	
								</div>      
								<?php
							}			
							?>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
									<a href="javascript:void(0);" class="btn btn-primary"> <span>Đăng nhập bằng Facebook</span><i class="fab fa-facebook-f"></i></a>
								</div>
								<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
									<a href="javascript:void(0);" class="btn btn-primary google-plus">Đăng nhập bằng Google<i class="fab fa-google-plus-g"></i></a>
								</div>
							</div>
							<h2>or</h2>
							<div class="formsix-pos">
								<div class="form-group i-email">
									<input type="email" name="email" class="form-control" value="<?php echo @$data['email']; ?>" placeholder="Email">									
								</div>
							</div>
							<div class="formsix-e">
								<div class="form-group i-password">									
									<input type="password" class="form-control" name="password" value="<?php echo @$data['password']; ?>" placeholder="Mật khẩu">
								</div>
							</div>
							<div class="login_remember_box">
								<label class="control control--checkbox">Remember me
									<input type="checkbox">
									<span class="control__indicator"></span>
								</label>
								<a href="<?php echo route('frontend.index.resetPassWrdCandidate'); ?>" class="forget_password">
									Quên mật khẩu
								</a>
							</div>
							<div class="login_btn_wrapper">
								<a href="javascript:void(0);" onclick="document.forms['frm-candidate-login'].submit();" class="btn btn-primary login_btn">ĐĂNG NHẬP</a>
							</div>
							<div class="login_message">
								<p>Bạn không có tài khoản ? <a href="<?php echo route('frontend.index.candidateRegister'); ?>">Đăng ký tài khoản ứng viên</a> </p>
							</div>
						</div>						
						<!-- /.login_wrapper-->
					</div>
				</div>
			</div>
		</form>
		<!-- /.login_form_wrapper-->
	</div>
	<!-- jp login wrapper end -->
	<div class="clr"></div>
</div>
@endsection()