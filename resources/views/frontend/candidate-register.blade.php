@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<!-- jp register wrapper start -->
	<div class="register_section">
		<!-- register_form_wrapper -->
		<div class="register_tab_wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<form name="frm-register-candidate" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div role="tabpanel">							
								<ul id="tabOne" class="nav register-tabs">
									<li class="active"><a href="javascript:void(0);" data-toggle="tab">Tài khoản ứng viên</a>
									</li>								
								</ul>
								<div class="tab-content">
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
									<div class="tab-pane fade in active register_left_form" id="contentOne-1">										
										<div class="row">
											<!--Form Group-->
											<div class="form-group col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="email" value="<?php echo @$data['email']; ?>" placeholder="Email">
											</div>
											<!--Form Group-->
											<div class="form-group col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="fullname" value="<?php echo @$data['fullname']; ?>" placeholder="Tên người tìm việc">
											</div>
											<!--Form Group-->
											<div class="form-group col-md-6 col-sm-6 col-xs-12">
												<input type="password" name="password" value="<?php echo @$data['password']; ?>" placeholder="Mật khẩu">
											</div>
											<!--Form Group-->
											<div class="form-group col-md-6 col-sm-6 col-xs-12">
												<input type="password" name="password_confirmed" value="<?php echo @$data['password_confirmed']; ?>" placeholder="Xác nhận mật khẩu">
											</div>
											
											<!--Form Group-->
											<div class="form-group col-md-6 col-sm-6 col-xs-12 custom_input">
												<input type="text" name="phone" value="<?php echo @$data['phone']; ?>" placeholder="Điện thoại">
											</div>
											
																											
									</div>
									<div class="login_btn_wrapper register_btn_wrapper login_wrapper ">
										<a href="javascript:void(0);" onclick="document.forms['frm-register-candidate'].submit();" class="btn btn-primary login_btn">Đăng ký</a>
									</div>
									<div class="login_message">
										<p>Bạn đã là thành viên chưa? <a href="<?php echo route('frontend.index.candidateLogin'); ?>"> Đăng nhập tại đây </a> </p>
									</div>
								</div>							
							</div>								
						</div>
					</form>						
				</div>
			</div>
		</div>
	</div>
</div>
<!-- jp register wrapper end -->
<div class="clr"></div>
</div>	
@endsection()