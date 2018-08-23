@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$source_sex=App\SexModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_work=App\WorkModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_literacy=App\LiteracyModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_experience=App\ExperienceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_salary=App\SalaryModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_working_form=App\WorkingFormModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_probationary=App\ProbationaryModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_job=App\JobModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();

$arrUser=array();
$ssNameUser='vmuser';
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
} 
$disabled_status='';
$register_status='onclick="document.forms[\'frm\'].submit();"';
$dang_tin='';
switch ($task) {
	case 'add':
	$dang_tin='Đăng tin';
	break;
	case 'edit':
	$dang_tin='Cập nhật';
	break;
}
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
		<div class="row">			
			<div class="col-lg-9">
				<form name="frm" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<h1 class="dn-dk-h">Đăng Tin Tuyển Dụng</h1>
					<?php 
					if(count(@$msg) > 0){
						$type_msg='';					
						if((int)@$checked == 1){
							if($task == 'add'){
								$disabled_status='disabled';
								$register_status='';
							}												
							$type_msg='note-success';
						}else{
							$type_msg='note-danger';
						}
						?>
						<div class="note margin-top-15 <?php echo $type_msg; ?>" >
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
					$ddlSex=cmsSelectboxCategory("sex_id","vacca",$source_sex,@$data['sex_id'],$disabled_status,'Chọn giới tính');				
					$ddlWork=cmsSelectboxCategory("work_id","vacca",$source_work,@$data['work_id'],$disabled_status,'Chọn tính chất công việc');
					$ddlLiteracy=cmsSelectboxCategory("literacy_id","vacca",$source_literacy,@$data['literacy_id'],$disabled_status,'Chọn trình độ học vấn');
					$ddlExperience=cmsSelectboxCategory("experience_id","vacca",$source_experience,@$data['experience_id'],$disabled_status,'Chọn kinh nghiệm');
					$ddlSalary=cmsSelectboxCategory("salary_id","vacca",$source_salary,@$data['salary_id'],$disabled_status,'Chọn mức lương');
					$ddlWorkingForm=cmsSelectboxCategory("working_form_id","vacca",$source_working_form,@$data['working_form_id'],$disabled_status,'Chọn hình thức làm việc');
					$ddlProbationary=cmsSelectboxCategory("probationary_id","vacca",$source_probationary,@$data['probationary_id'],$disabled_status,'Chọn thời gian thử việc');
					$ddlJob        =cmsSelectboxMultiple("job_id", 'vacca selected2', @$source_job, @$data['job_id'],$disabled_status,'Chọn ngành nghề');
					$ddlProvince        =cmsSelectboxMultiple("province_id", 'vacca selected2', @$source_province, @$data['province_id'],$disabled_status,'Chọn nơi làm việc');
					$status_employer                 =   (count(@$data["status_employer"]) > 0) ? @$data["status_employer"] : 1 ;
					$arrStatus              =   array(-1 => '- Chọn trạng thái -', 1 => 'Hiển thị tin', 0 => 'Ẩn tin');  
					$ddlStatusEmployer              =   cmsSelectbox("status_employer","vacca",$arrStatus,$status_employer,$disabled_status);
					?>				
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin tuyển dụng</h2></div>
						<div class="col-lg-8"></div>
					</div>			
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tiêu đề</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="fullname" class="vacca" placeholder="Nhập tiêu đề" value="<?php echo @$data['fullname']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Số lượng cần tuyển</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> onkeypress='return isNumberKey(event);' name="quantity" class="vacca" placeholder="Nhập số lượng" value="<?php echo @$data['quantity']; ?>" ></div>
					</div>		
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Giới tính</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlSex; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mô tả công việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><textarea name="description" placeholder="Nhập mô tả công việc..." <?php echo $disabled_status; ?> class="vacca summer-editor" rows="10" ><?php echo @$data['description']; ?></textarea></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Yêu cầu công việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><textarea name="requirement" placeholder="Nhập yêu cầu công việc..." <?php echo $disabled_status; ?> class="vacca summer-editor" rows="10" ><?php echo @$data['requirement']; ?></textarea></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tính chất công việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlWork; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Trình độ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlLiteracy; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Kinh nghiệm</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlExperience; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mức lương</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlSalary; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mức hoa hồng (nếu có)</div></div></div>
						<div class="col-lg-8">						
							<div class="flamentco">
								
								<div><input type="text" <?php echo $disabled_status; ?> onkeypress='return isNumberKey(event);' name="commission_from" class="vacca" placeholder="Từ" value="<?php echo @$data['commission_from']; ?>" ></div>
								
								<div class="margin-left-5"><input type="text" <?php echo $disabled_status; ?> onkeypress='return isNumberKey(event);' name="commission_to" class="vacca" placeholder="Đến" value="<?php echo @$data['commission_to']; ?>" ></div>
								<div class="margin-left-5">%</div>
							</div>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Hình thức làm việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlWorkingForm; ?>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Thời gian thử việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlProbationary; ?>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Quyền lợi</div></div></div>
						<div class="col-lg-8"><textarea name="benefit" placeholder="Nhập quyền lợi..." <?php echo $disabled_status; ?> class="vacca summer-editor" rows="10" ><?php echo @$data['benefit']; ?></textarea></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Ngành nghề</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlJob; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Nơi làm việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlProvince; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Yêu cầu / hình thức nộp hồ sơ</div></div></div>
						<div class="col-lg-8"><textarea name="requirement_profile" placeholder="Nhập yêu cầu / hình thức nộp hồ sơ..." <?php echo $disabled_status; ?> class="vacca summer-editor" rows="10" ><?php echo @$data['requirement_profile']; ?></textarea></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Hết hạn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" readonly="readonly" <?php echo $disabled_status; ?> name="duration" class="vacca"  value="<?php echo @$data['duration']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Hiển thị tin</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlStatusEmployer; ?>
						</div>
					</div>
					<hr  />
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin liên hệ</h2></div>
						<div class="col-lg-8"></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tên người liên hệ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="contacted_name" class="vacca" placeholder="Tên người liên hệ" value="<?php echo @$data['contacted_name']; ?>" ></div>
					</div>				
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Địa chỉ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="address" class="vacca" placeholder="Địa chỉ công ty" value="<?php echo @$data['address']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Điện thoại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="contacted_phone" class="vacca" placeholder="Điện thoại người liên hệ" value="<?php echo @$data['contacted_phone']; ?>" ></div>
					</div>							
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8"><div class="btn-dang-ky"><a href="javascript:void(0);" <?php echo $register_status; ?> ><?php echo $dang_tin; ?></a></div></div>
					</div>
				</form>
			</div>
			<div class="col-lg-3">
				@include("frontend.employer-sidebar")				
			</div>
		</div>
	</div>
</div>
@endsection()