@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$setting = getSettingSystem();

$arrUser=array();
$ssNameUser='vmuser';
$candidate=array();
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
	$candidate=App\CandidateModel::find((int)@$arrUser['id'])->toArray();
	$candidate['birthday']=datetimeConverterVn($candidate['birthday']);
} 
$picture                =   "";
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$candidate)>0){
	if(!empty(@$candidate["avatar"])){
		$picture        =   '<div class="margin-top-15"><img width="150" alt="'.@$candidate['fullname'].'" title="'.@$candidate['fullname'].'" height="150" src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$candidate["avatar"]).'"  /></div>';                        
	}else{
		$picture='<img src="'.asset("/upload/avatar-default-icon.png").'" width="64" alt="'.@$candidate['fullname'].'" title="'.@$candidate['fullname'].'" />';
	}         
}
$disabled_status='';
$register_status='onclick="document.forms[\'frm-profile\'].submit();"';
$dang_tin='';
switch ($task) {
	case 'add':
	$dang_tin='Tạo hồ sơ';
	break;
	case 'edit':
	$dang_tin='Cập nhật hồ sơ';
	break;
}  
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
		<div class="row">			
			<div class="col-lg-9">
				<form name="frm-profile" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<h1 class="dn-dk-h">Tạo Hồ Sơ</h1>
					
					<div class="row">
						<div class="col-lg-3"><?php echo $picture; ?></div>
						<div class="col-lg-9">
							<div class="row margin-top-10">
								<div class="col-lg-3"><div class="xika">Họ tên</div> </div>
								<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['fullname']; ?></b></div></div>
							</div>	
							<div class="row margin-top-10">
								<div class="col-lg-3"><div class="xika">Ngày sinh</div></div>
								<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['birthday']; ?></b></div></div>
							</div>	
							<div class="row margin-top-10">
								<div class="col-lg-3"><div class="xika">Số điện thoại</div></div>
								<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['phone']; ?></b></div></div>
							</div>	
							<div class="row margin-top-10">
								<div class="col-lg-3"><div class="xika">Email</div></div>
								<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['email']; ?></b></div></div>
							</div>	
							<div class="row margin-top-10">
								<div class="col-lg-3"></div>
								<div class="col-lg-9">
									<div class="fatanasa"><a href="<?php echo route('frontend.index.viewCandidateAccount'); ?>">Chỉnh sửa</a></div>
								</div>
							</div>			
						</div>
					</div>	
					<hr  />				
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
					$source_literacy=App\LiteracyModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
					$source_experience=App\ExperienceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
					$source_rank=App\RankModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
					$source_job=App\JobModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
					$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();

					$ddlLiteracy=cmsSelectboxCategory("literacy_id","vacca",$source_literacy,@$data['literacy_id'],$disabled_status,'Chọn trình độ học vấn');
					$ddlExperience=cmsSelectboxCategory("experience_id","vacca",$source_experience,@$data['experience_id'],$disabled_status,'Chọn số năm kinh nghiệm');
					$ddlRankPresent=cmsSelectboxCategory("rank_present_id","vacca",$source_rank,@$data['rank_present_id'],$disabled_status,'Chọn cấp bậc hiện tại');
					$ddlRankOffered=cmsSelectboxCategory("rank_offered_id","vacca",$source_rank,@$data['rank_offered_id'],$disabled_status,'Chọn cấp bậc mong muốn');
					$ddlJob        =cmsSelectboxMultiple("job_id", 'vacca selected2', @$source_job, @$data['job_id'],$disabled_status,'Chọn ngành nghề');
					$ddlProvince        =cmsSelectboxMultiple("province_id", 'vacca selected2', @$source_province, @$data['province_id'],$disabled_status,'Chọn nơi làm việc');								
					$status_search                 =  (isset($data['status_search'])) ? (int)@$data['status_search'] : 1 ;
					$arrStatusSearch              =   array(-1 => '- Chọn trạng thái -', 1 => 'Cho phép Nhà tuyển dụng tìm kiếm thông tin của bạn và chủ động liên hệ mời phỏng vấn', 0 => 'Không cho phép nhà tuyển dụng tìm kiếm. Hồ sơ này chỉ dùng để ứng tuyển');  
					$ddlStatusSearch              =   cmsSelectbox("status_search","vacca",$arrStatusSearch,$status_search,$disabled_status);
					?>		
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin tổng quan</h2></div>
						<div class="col-lg-8"></div>
					</div>							
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tiêu đề hồ sơ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="fullname" class="vacca" placeholder="Tiêu đề hồ sơ" value="<?php echo @$data['fullname']; ?>" ></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Trình độ học vấn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlLiteracy; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Số năm kinh nghiệm</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlExperience; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Cấp bậc hiện tại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlRankPresent; ?>
						</div>
					</div>			
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Cấp bậc mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlRankOffered; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Ngành nghề mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlJob; ?>
						</div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mức lương mong muốn (VNĐ/tháng)</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" <?php echo $disabled_status; ?> name="salary" class="vacca" placeholder="Ghi rõ mức lương bằng số" value="<?php echo @$data['salary']; ?>" onkeyup="PhanCachSoTien(this);"   ></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Nơi làm việc mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlProvince; ?>
						</div>
					</div>		
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8">						
							<?php echo $ddlStatusSearch; ?>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8"> 
							<?php 
							$data_profile_applied=App\ProfileAppliedModel::whereRaw('profile_id = ?',[(int)@$data['id']])->select()->get()->toArray();	
							if(count(@$data_profile_applied) > 0){
								?>
								<b><font color="#E30000">Hồ sơ đã được nộp không được phép chỉnh sửa</font></b> 
								<?php
							}else{
								?>
								<div class="rianmus">
									<a href="javascript:void(0);" <?php echo $register_status; ?> >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5"><?php echo $dang_tin; ?></div>
										</div>								
									</a>
								</div>
								<?php
							}
							?>						
						</div>
					</div>					
				</form>
			</div>
			<div class="col-lg-3">
				@include("frontend.candidate-sidebar")				
			</div>
		</div>
	</div>
</div>
@endsection()