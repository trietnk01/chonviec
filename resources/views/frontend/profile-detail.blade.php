@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$setting = getSettingSystem();

$arrUser=array();
$ssNameUser='vmuser';
$candidate=array();
$sex=array();
$marriage=array();
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
	$candidate=App\CandidateModel::find((int)@$arrUser['id'])->toArray();
	$sex=App\SexModel::find((int)@$candidate['sex_id'])->toArray();    
	$marriage=App\MarriageModel::find((int)@$candidate['marriage_id'])->toArray();
	$candidate['birthday']=datetimeConverterVn($candidate['birthday']);    
} 
$picture                =   "";
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$candidate)>0){
	if(!empty(@$candidate["avatar"])){
		$picture        =   '<img width="150" alt="'.@$candidate['fullname'].'" title="'.@$candidate['fullname'].'" height="150" src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$candidate["avatar"]).'"  />';                        
	}else{
		$picture='<img src="'.asset("/upload/avatar-default-icon.png").'" width="64" alt="'.@$candidate['fullname'].'" title="'.@$candidate['fullname'].'" />';
	}           
}
$query=DB::table('profile')
->leftJoin('literacy','profile.literacy_id','=','literacy.id')
->leftJoin('experience','profile.experience_id','=','experience.id')
->leftJoin('rank as rank_present','profile.rank_present_id','=','rank_present.id')
->leftJoin('rank as rank_offered','profile.rank_offered_id','=','rank_offered.id')
->leftJoin('classification as w','profile.ms_word','=','w.id')
->leftJoin('classification as e','profile.ms_excel','=','e.id')
->leftJoin('classification as p','profile.ms_power_point','=','p.id')
->leftJoin('classification as o','profile.ms_outlook','=','o.id');
$query->where('profile.id',@$id);
$source_info=$query->select('profile.fullname'
	,'literacy.fullname as literacy_fullname'
	,'experience.fullname as experience_fullname'
	,'rank_present.fullname as rank_present_fullname'
	,'rank_offered.fullname as rank_offered_fullname'
	,'profile.salary'
	,'profile.career_goal'
	,'profile.ms_word'
	,'profile.ms_excel'
	,'profile.ms_power_point'
	,'profile.ms_outlook'
	,'w.fullname as ms_word_level'
	,'e.fullname as ms_excel_level'
	,'p.fullname as ms_power_point_level'
	,'o.fullname as ms_outlook_level'
	,'profile.other_software'
	,'profile.medal'
	,'profile.hobby'
	,'profile.talent'
	,'profile.status_search')
->groupBy('profile.fullname'
	,'literacy.fullname'
	,'experience.fullname'
	,'rank_present.fullname'
	,'rank_offered.fullname'
	,'profile.salary'
	,'profile.career_goal'
	,'profile.ms_word'
	,'profile.ms_excel'
	,'profile.ms_power_point'
	,'profile.ms_outlook'
	,'w.fullname'
	,'e.fullname'
	,'p.fullname'
	,'o.fullname'
	,'profile.other_software'
	,'profile.medal'
	,'profile.hobby'
	,'profile.talent'
	,'profile.status_search')	
->get()->toArray();	
$profile_detail=array();
$job_id=array();
$province_id=array();
$source_job=array();
$source_province=array();
$source_job_fullname=array();
$source_province_fullname=array();
$job_fullname='';
$province_fullname='';
$status_search='';
$enabled_do=1;
$data_profile_applied=App\ProfileAppliedModel::whereRaw('profile_id = ?',[(int)@$id])->select()->get()->toArray();
if(count(@$data_profile_applied) > 0){
	$enabled_do=0;
}	
if(count($source_info) > 0){
	$source_info2=convertToArray($source_info);	
	$profile_detail=$source_info2[0];	
	$profile_detail['salary']=convertToTextPrice($profile_detail['salary']) . ' VNĐ/tháng';	
	if((int)@$profile_detail['status_search'] == 1){
		$status_search='Cho phép Nhà tuyển dụng tìm kiếm thông tin của bạn và chủ động liên hệ mời phỏng vấn';
	}else{
		$status_search='Không cho phép nhà tuyển dụng tìm kiếm. Hồ sơ này chỉ dùng để ứng tuyển';
	}		
	$source_profile_job=App\ProfileJobModel::whereRaw('profile_id = ?',[@$id])->select('id','profile_id','job_id')->get()->toArray();
	$source_profile_place=App\ProfilePlaceModel::whereRaw('profile_id = ?',[@$id])->select('id','profile_id','province_id')->get()->toArray();
	if(count($source_profile_job) > 0){
		foreach ($source_profile_job as $key => $value) {
			$job_id[]=$value['job_id'];
		}
		$source_job=App\JobModel::whereIn('id',@$job_id)->select('fullname')->get()->toArray();			
		if(count($source_job) > 0){
			foreach ($source_job as $key => $value) {
				$source_job_fullname[]=$value['fullname'];
			}
			$job_fullname=implode(' , ', $source_job_fullname);
		}
	}
	if(count($source_profile_place) > 0){
		foreach ($source_profile_place as $key => $value) {
			$province_id[]=$value['province_id'];
		}
		$source_province=App\ProvinceModel::whereIn('id',@$province_id)->select('fullname')->get()->toArray();	
		if(count($source_province) > 0){
			foreach ($source_province as $key => $value) {
				$source_province_fullname[]=$value['fullname'];
			}
			$province_fullname=implode(' , ', $source_province_fullname);
		}		
	}		
}

$disabled_status='';
$register_status='onclick="document.forms[\'frm\'].submit();"';
$inputID     =   '<input type="hidden" name="id"  value="'.@$id.'" />';
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>



<div class="container">
	<div class="row">			
		<div class="col-lg-9">
			<form name="frm" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<?php echo $inputID; ?>
				<h1 class="dn-dk-h">Tạo Hồ Sơ Từng Bước</h1>				
				<div class="row">
					<div class="col-lg-3"><div class="picture-box"><?php echo $picture; ?></div></div>
					<div class="col-lg-9">
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Họ tên</div> </div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['fullname']; ?></b></div></div>
						</div>	
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Giới tính</div></div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$sex['fullname']; ?></b></div></div>
						</div>	
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Ngày sinh</div></div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['birthday']; ?></b></div></div>
						</div>	
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Địa chỉ</div></div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['address']; ?></b></div></div>
						</div>	
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Số điện thoại</div></div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['phone']; ?></b></div></div>
						</div>	
						<div class="row mia">
							<div class="col-lg-3"><div class="xika">Email</div></div>
							<div class="col-lg-9"><div class="xika2"><b><?php echo @$candidate['email']; ?></b></div></div>
						</div>	
						<div class="row mia">
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
				?>		
				<div class="row mia">
					<div class="col-lg-12"><h2 class="login-information">Thông tin chung</h2></div>					
				</div>	
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Tiêu đề hồ sơ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['fullname']; ?></div> </div>
				</div>
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Trình độ cao nhất</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['literacy_fullname']; ?></div></div>
				</div>					
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Số năm kinh nghiệm</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['experience_fullname']; ?></div></div>
				</div>					
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Cấp bậc hiện tại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['rank_present_fullname']; ?></div></div>
				</div>	
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Cấp bậc mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['rank_offered_fullname']; ?></div></div>
				</div>	
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Ngành nghề mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$job_fullname; ?></div></div>
				</div>	
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Mức lương mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['salary']; ?></div></div>
				</div>
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Nơi làm việc mong muốn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$province_fullname; ?></div></div>
				</div>	
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Hôn nhân</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$marriage['fullname']; ?></div></div>
				</div>
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Hiện tại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><div class="xika2"><?php echo @$status_search; ?></div></div>
				</div>	
				<div class="row margin-top-10">
					<div class="col-lg-4"></div>
					<div class="col-lg-8">
						<?php 
						if((int)@$enabled_do == 1){
							?>
							<div class="fatanasa"><a href="<?php echo route('frontend.index.getFormProfile',['edit',@$id]); ?>">Chỉnh sửa</a></div>
							<?php
						}else{
							?>
							<b><font color="#E30000">Hồ sơ đã được nộp không được phép chỉnh sửa</font></b> 
							<?php
						}
						?>						
					</div>
				</div>				
				<hr  />		
				<div class="row mia">
					<div class="col-lg-12"><div class="rarakata"><h2 class="login-information">Mục tiêu nghề nghiệp</h2><div class="miakasaki margin-left-15">(Bắt buộc)</div></div></div>					
				</div>
				<div class="note note_career_goal margin-top-15"  style="display: none;"></div>
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Mục tiêu</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8">
						<?php 
						$status_career_goal_edit='';
						$status_career_goal_save='';
						if(empty(@$profile_detail['career_goal'])){
							$status_career_goal_edit='display:none';
							$status_career_goal_save='display:block';
						}else{
							$status_career_goal_edit='display:block';
							$status_career_goal_save='display:none';
						}
						?>
						<div class="career_goal_edit" style="<?php echo $status_career_goal_edit; ?>">
							<div class="career_goal_txt"><?php echo @$profile_detail['career_goal']; ?></div>
							<?php 
							if((int)@$enabled_do == 1){
								?>
								<div class="vihamus-3 margin-top-5">
									<a href="javascript:void(0);" onclick="showCareerGoalSave();"  >
										<div class="narit">
											<div><i class="far fa-edit"></i></div>
											<div class="margin-left-5">Chỉnh sửa</div>
										</div>
									</a>
								</div>
								<?php
							}else{
								?>
								<b><font color="#E30000">Hồ sơ đã được nộp không được phép chỉnh sửa</font></b> 
								<?php
							}
							?>									
						</div>
						<div class="career_goal_save" style="<?php echo $status_career_goal_save; ?>">
							<div><textarea name="career_goal" placeholder="Nhập mục tiêu nghề nghiệp..." class="vacca summer-editor" rows="10" ><?php echo @$profile_detail['career_goal']; ?></textarea></div>
							<div>
								<div class="titanius">
									<div class="vihamus">
										<a href="javascript:void(0);" onclick="saveCareerGoal('<?php echo route("frontend.index.updateCareerGoal"); ?>');" >
											<div class="narit">
												<div><i class="far fa-save"></i></div>
												<div class="margin-left-5">Lưu</div>
											</div>								
										</a>
									</div>							
									<div class="vihamus-2 margin-left-5">
										<a href="javascript:void(0);" onclick="noSaveCareerGoal();" >
											<div class="narit">
												<div><i class="far fa-times-circle"></i></div>
												<div class="margin-left-5">Không lưu</div>
											</div>								
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
				<hr  />		
				<div class="row mia">
					<div class="col-lg-12"><div class="rarakata"><h2 class="login-information">Kinh nghiệm làm việc</h2><div class="miakasaki margin-left-15">(Bắt buộc)</div></div></div>					
				</div>
				<div class="row mia">					
					<div class="col-lg-12">
						Hãy liệt kê những công việc, nhiệm vụ mà bạn đã từng đảm nhận và thực hiện. Chú ý liệt kê kinh nghiệm làm việc từ thời gian gần đây nhất trở về trước. 
					</div>
				</div>
				<div class="note note_experience margin-top-15"  style="display: none;"></div>
				<?php 
				$status_experience_job_edit='';
				$status_experience_job_save='';
				$data_profile_experience=App\ProfileExperienceModel::whereRaw('profile_id = ?',[@$id])->select()->get()->toArray();		
				if(count(@$data_profile_experience) == 0){
					$status_experience_job_edit='display:none';
					$status_experience_job_save='display:block';
				}else{
					$status_experience_job_edit='display:block';
					$status_experience_job_save='display:none';
				}
				?>
				<div class="experience_job_edit" style="<?php echo $status_experience_job_edit; ?>">
					<div class="experience_job_txt">
						<?php 
						if(count(@$data_profile_experience) > 0){
							foreach ($data_profile_experience as $key => $value) {
								$profile_experience_id=$value['id'];
								$profile_experience_company_name=$value['company_name'];
								$profile_experience_person_title=$value['person_title'];
								$profile_experience_time_from=$value['month_from'] . '/' . $value['year_from'];
								$profile_experience_time_to=$value['month_to'] . '/' .$value['year_to'];		
								$profile_experience_salary=convertToTextPrice($value['salary']);
								$currency='';
								switch ($value['currency']) {
									case 'vnd':			
									$currency='VNĐ';	
									break;
									case 'usd':
									$currency='USD';							
									break;
								}		
								$profile_experience_salary=@$profile_experience_salary.' '.@$currency.'/tháng';
								$profile_experience_job_description=@$value['job_description'];
								$profile_experience_achievement=@$value['achievement'];
								$profile_experience_profile_id=(int)@$value['profile_id'];
								?>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Công ty</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_experience_company_name; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Chức danh</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_experience_person_title; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Thời gian làm việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8">
										<div class="lunarnewyear">
											<div>Từ</div>
											<div class="margin-left-10"><?php echo @$profile_experience_time_from; ?></div>
											<div class="margin-left-10">Đến</div>
											<div class="margin-left-10"><?php echo @$profile_experience_time_to; ?></div>
										</div> 
									</div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Mức lương</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_experience_salary; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Mô tả công việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_experience_job_description; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Thành tích nổi bật</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_experience_achievement; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4"></div>
									<div class="col-lg-8">
										<?php 
										if((int)@$enabled_do == 1){
											?>
											<div class="vihamus-3">
												<a href="javascript:void(0);" onclick="deleteProfileExperience(<?php echo $profile_experience_id ?>,'<?php echo route("frontend.index.deleteExperienceJob"); ?>');" >
													<div class="narit">
														<div><i class="far fa-times-circle"></i></div>
														<div class="margin-left-5">Xóa</div>
													</div>								
												</a>
											</div>
											<?php
										}
										?>												
									</div>
								</div>
								<hr>
								<?php
							}
						}						
						?>
					</div>
					<?php 
					if((int)@$enabled_do == 1){
						?>
						<div class="row mia">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="vihamus-4">
									<a href="javascript:void(0);" onclick="addExperienceJob();" >
										<div class="narit">
											<div><i class="far fa-plus-square"></i></div>
											<div class="margin-left-5">Thêm kinh nghiệm làm việc</div>
										</div>								
									</a>
								</div>
							</div>
						</div>			
						<?php
					}
					?>						
				</div>
				<div class="experience_job_save" style="<?php echo $status_experience_job_save; ?>">
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Công ty</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="company_name" class="vacca" placeholder="Tên công ty" value="" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Chức danh</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="person_title" class="vacca" placeholder="Chức danh" value="" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Thời gian làm việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<?php 
							$source_month=array();
							for($i=0;$i<=12;$i++){
								if($i==0){
									$source_month[]='Tháng';
								}else{
									$source_month[]=$i;
								}	
							}
							$arrDate    = date_parse_from_format('Y-m-d H:i:s', date("Y-m-d")) ;
							for ($i=1953; $i <= (int)@$arrDate['year']; $i++) { 
								$source_year[$i]=$i;
							}
							$source_year[0]='Năm';
							krsort($source_year);
							$ddlMonthFrom=cmsSelectbox(	"month_from"	,	"vacca"	,	$source_month	,	0	,	''	);
							$ddlYearFrom=cmsSelectbox(	"year_from"	,	"vacca"	,	$source_year	,	0	,	''	);
							$ddlMonthTo=cmsSelectbox(	"month_to"	,	"vacca"	,	$source_month	,	0	,	''	);
							$ddlYearTo=cmsSelectbox(	"year_to"	,	"vacca"	,	$source_year	,	0	,	''	);
							?>
							<div class="lunarnewyear">
								<div>Từ</div>
								<div class="margin-left-10"><?php echo $ddlMonthFrom; ?></div>
								<div class="margin-left-10"><?php echo $ddlYearFrom; ?></div>
								<div class="margin-left-10">Đến</div>
								<div class="margin-left-10"><?php echo $ddlMonthTo; ?></div>
								<div class="margin-left-10"><?php echo $ddlYearTo; ?></div>
							</div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mức lương</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<?php
							$source_currency=array(''=>'Vui lòng chọn đơn vị tiền tệ' , 'vnd'=>'VND','usd'=>'USD'); 
							$ddlCurrency=cmsSelectbox(	"currency"	,	"vacca"	,	$source_currency	,	''	,	''	);
							?>
							<div class="lunarnewyear">							
								<div ><?php echo $ddlCurrency; ?></div>				
								<div class="margin-left-10">
									<input type="text"  name="salary" class="vacca" onkeyup="PhanCachSoTien(this);"  placeholder="Nhập mức lương..." value="" >
								</div>			
							</div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Mô tả công việc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<textarea name="job_description" placeholder="Nhập mô tả công việc..."  class="vacca summer-editor" rows="10" ></textarea>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Thành tích đạt được</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<textarea name="achievement" placeholder="Nhập thành tích đạt được..."  class="vacca summer-editor" rows="10" ></textarea>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div class="titanius">
								<div class="vihamus">
									<a href="javascript:void(0);" onclick="saveExperienceJob('<?php echo route("frontend.index.saveExperienceJob"); ?>');" >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5">Lưu</div>
										</div>								
									</a>
								</div>							
								<div class="vihamus-2 margin-left-5">
									<a href="javascript:void(0);" onclick="noSaveExperienceJob();" >
										<div class="narit">
											<div><i class="far fa-times-circle"></i></div>
											<div class="margin-left-5">Không lưu</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
					</div>							
				</div>			
				<hr  />
				<div class="row mia">
					<div class="col-lg-12"><div class="rarakata"><h2 class="login-information">Trình độ bằng cấp</h2><div class="miakasaki margin-left-15">(Bắt buộc)</div></div></div>					
				</div>		
				<div class="row mia">					
					<div class="col-lg-12">
						Hãy liệt kê đầy đủ thông tin các bằng cấp/chứng chỉ mà bạn có (kể cả bằng cấp/ chứng chỉ đào tạo ngắn hạn) 
					</div>
				</div>	
				<div class="note note_graduation margin-top-15"  style="display: none;"></div>
				<?php 
				$status_graduation_edit='';
				$status_graduation_save='';
				$source_profile_graduation=DB::table('profile_graduation')
				->join('literacy','profile_graduation.literacy_id','=','literacy.id')
				->join('graduation','profile_graduation.graduation_id','=','graduation.id')
				->where('profile_graduation.profile_id',(int)@$id)
				->select(
					'profile_graduation.id',
					'literacy.fullname as literacy_name',
					'profile_graduation.training_unit',
					'profile_graduation.year_from',
					'profile_graduation.year_to',
					'profile_graduation.department',
					'graduation.fullname as graduation_name',
					'profile_graduation.degree'
				)
				->groupBy(
					'profile_graduation.id',
					'literacy.fullname',
					'profile_graduation.training_unit',
					'profile_graduation.year_from',
					'profile_graduation.year_to',
					'profile_graduation.department',
					'graduation.fullname',
					'profile_graduation.degree'
				)
				->orderBy('profile_graduation.id', 'asc')
				->get()->toArray();		
				$data_profile_graduation=convertToArray($source_profile_graduation);	
				if(count(@$data_profile_graduation) == 0){
					$status_graduation_edit='display:none';
					$status_graduation_save='display:block';
				}else{
					$status_graduation_edit='display:block';
					$status_graduation_save='display:none';
				}
				$source_literacy=App\LiteracyModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
				$source_graduation=App\GraduationModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
				$ddlLiteracy=cmsSelectboxCategory("literacy_id","vacca",$source_literacy,0,'','Chọn trình độ học vấn');
				$ddlGraduation=cmsSelectboxCategory("graduation_id","vacca",$source_graduation,0,'','Chọn Tốt nghiệp loại');
				$ddlGraduationYearFrom=cmsSelectbox(	"graduation_year_from"	,	"vacca"	,	$source_year	,	0	,	''	);						
				$ddlGraduationYearTo=cmsSelectbox(	"graduation_year_to"	,	"vacca"	,	$source_year	,	0	,	''	);
				?>
				<div class="graduation_edit" style="<?php echo $status_graduation_edit; ?>">
					<div class="graduation_txt">
						<?php 
						if(count(@$data_profile_graduation) > 0){
							foreach ($data_profile_graduation as $key => $value) {
								$profile_graduation_id=$value['id'];
								$profile_graduation_literacy_name=$value['literacy_name'];
								$profile_graduation_training_unit=$value['training_unit'];
								$profile_graduation_year_from= $value['year_from'];
								$profile_graduation_year_to=$value['year_to'];									
								$profile_graduation_department=@$value['department'];
								$profile_graduation_graduation_name=@$value['graduation_name'];			
								$profile_graduation_degree=@$value['degree'];				
								?>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Trình độ học vấn</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_graduation_literacy_name; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Đơn vị đào tạo</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_graduation_training_unit; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Thời gian</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8">
										<div class="lunarnewyear">
											<div>Từ</div>
											<div class="margin-left-10"><?php echo @$profile_graduation_year_from; ?></div>
											<div class="margin-left-10">Đến</div>
											<div class="margin-left-10"><?php echo @$profile_graduation_year_to; ?></div>
										</div> 
									</div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Chuyên ngành</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_graduation_department; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Tốt nghiệp loại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_graduation_graduation_name; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Ảnh bằng cấp</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8">
										<div class="xika2"><a target="_blank" href="<?php echo asset('upload/'.@$profile_graduation_degree); ?>">Tải về</a>			</div>																	
									</div>
								</div>		
								<?php 
								if((int)@$enabled_do == 1){
									?>
									<div class="row mia">
										<div class="col-lg-4"></div>
										<div class="col-lg-8">
											<div class="vihamus-3">
												<a href="javascript:void(0);" onclick="deleteProfileGraduation(<?php echo $profile_graduation_id ?>);" >
													<div class="narit">
														<div><i class="far fa-times-circle"></i></div>
														<div class="margin-left-5">Xóa</div>
													</div>								
												</a>
											</div>
										</div>
									</div>
									<?php
								}
								?>													
								<hr>
								<?php
							}
						}						
						?>
					</div>
					<?php 
					if((int)@$enabled_do == 1){
						?>
						<div class="row mia">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="vihamus-4">
									<a href="javascript:void(0);" onclick="addGraduation();" >
										<div class="narit">
											<div><i class="far fa-plus-square"></i></div>
											<div class="margin-left-5">Thêm trình độ bằng cấp</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
				<div class="graduation_save" style="<?php echo $status_graduation_save; ?>">
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Trình độ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlLiteracy; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Đơn vị đào tạo</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="training_unit" class="vacca" placeholder="Đơn vị đào tạo" value="" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Thời gian</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<div class="lunarnewyear">															
								<div>Từ</div>								
								<div class="margin-left-10"><?php echo $ddlGraduationYearFrom; ?></div>
								<div class="margin-left-10">Đến</div>								
								<div class="margin-left-10"><?php echo $ddlGraduationYearTo; ?></div>
							</div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Chuyên ngành</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="department" class="vacca" placeholder="Chuyên ngành" value="" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tốt nghiệp loại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlGraduation; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tải ảnh bằng cấp</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="file" name="degree" /></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div class="titanius">
								<div class="vihamus">
									<a href="javascript:void(0);" onclick="saveGraduation('<?php echo route("frontend.index.saveGraduation"); ?>','<?php echo route("frontend.index.deleteGraduation"); ?>');" >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5">Lưu</div>
										</div>								
									</a>
								</div>							
								<div class="vihamus-2 margin-left-5">
									<a href="javascript:void(0);" onclick="noSaveGraduation();" >
										<div class="narit">
											<div><i class="far fa-times-circle"></i></div>
											<div class="margin-left-5">Không lưu</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
					</div>				
				</div>
				<hr  />		
				<div class="row mia">
					<div class="col-lg-12"><div class="rarakata"><h2 class="login-information">Trình độ ngoại ngữ</h2><div class="miakasaki margin-left-15">(Bắt buộc)</div></div></div>					
				</div>
				<div class="row mia">					
					<div class="col-lg-12">
						Hãy liệt kê các bằng cấp ngoại ngữ mà bạn đã có
					</div>
				</div>
				<div class="note note_language margin-top-15"  style="display: none;"></div>
				<?php 
				$status_language_edit='';
				$status_language_save='';
				
				$source_profile_language=DB::table('profile_language')
				->join('language','profile_language.language_id','=','language.id')
				->join('language_level','profile_language.language_level_id','=','language_level.id')
				->join('classification as l','profile_language.listening','=','l.id')
				->join('classification as s','profile_language.speaking','=','s.id')
				->join('classification as r','profile_language.reading','=','r.id')
				->join('classification as w','profile_language.writing','=','w.id')
				->where('profile_language.profile_id',(int)@$id)
				->select(
					'profile_language.id',
					'language.fullname as language_name',					
					'language_level.fullname as language_level_name',
					'l.fullname as listening',
					's.fullname as speaking',
					'r.fullname as reading',
					'w.fullname as writing'				
				)
				->groupBy(
					'profile_language.id',
					'language.fullname',					
					'language_level.fullname',
					'l.fullname',
					's.fullname',
					'r.fullname',
					'w.fullname'		
				)
				->orderBy('profile_language.id', 'asc')
				->get()->toArray();		
				$data_profile_language=convertToArray($source_profile_language);

				if(count(@$data_profile_language) == 0){
					$status_language_edit='display:none';
					$status_language_save='display:block';
				}else{
					$status_language_edit='display:block';
					$status_language_save='display:none';
				}				
				$source_language=App\languageModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
				$source_language_level=App\languageLevelModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
				$source_classification=App\ClassificationModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();				
				$ddlLanguage=cmsSelectboxCategory("language_id","vacca",$source_language,0,'','Chọn ngoại ngữ');
				$ddlLanguageLevel=cmsSelectboxCategory("language_level_id","vacca",$source_language_level,0,'','Chọn trình độ');				
				?>
				<div class="language_edit" style="<?php echo $status_language_edit; ?>">
					<div class="language_txt">
						<?php 
						if(count(@$data_profile_language) > 0){
							foreach ($data_profile_language as $key => $value) {
								$profile_language_id=$value['id'];
								$profile_language_name=$value['language_name'];
								$profile_language_level_name=$value['language_level_name'];							
								$profile_listening=$value['listening'];
								$profile_speaking=$value['speaking'];
								$profile_reading=$value['reading'];
								$profile_writing=$value['writing'];
								?>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Ngoại ngữ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_language_name; ?></div> </div>
								</div>
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Trình độ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_language_level_name; ?></div> </div>
								</div>		
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Nghe</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_listening; ?></div> </div>
								</div>		
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Nói</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_speaking; ?></div> </div>
								</div>		
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Đọc</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_reading; ?></div> </div>
								</div>		
								<div class="row mia">
									<div class="col-lg-4" ><div class="xika"><div>Viết</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
									<div class="col-lg-8"><div class="xika2"><?php echo @$profile_writing; ?></div> </div>
								</div>			
								<?php 
								if((int)@$enabled_do == 1){
									?>
									<div class="row mia">
										<div class="col-lg-4"></div>
										<div class="col-lg-8">
											<div class="vihamus-3">
												<a href="javascript:void(0);" onclick="deleteProfileLanguage(<?php echo $profile_language_id ?>);" >
													<div class="narit">
														<div><i class="far fa-times-circle"></i></div>
														<div class="margin-left-5">Xóa</div>
													</div>								
												</a>
											</div>
										</div>
									</div>
									<?php
								}
								?>															
								<hr>
								<?php
							}
						}						
						?>
					</div>
					<?php 
					if((int)@$enabled_do == 1){
						?>
						<div class="row mia">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="vihamus-4">
									<a href="javascript:void(0);" onclick="addLanguage();" >
										<div class="narit">
											<div><i class="far fa-plus-square"></i></div>
											<div class="margin-left-5">Thêm trình độ ngoại ngữ</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
				<div class="language_save" style="<?php echo $status_language_save; ?>">
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Ngoại ngữ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlLanguage; ?></div>
					</div>					
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Trình độ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlLanguageLevel; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8">
							<table class="language-tb">
								<tr>
									<th class="suzuritake"></th>
									<?php 
									if(count($source_classification) > 0){
										foreach ($source_classification as $key => $value) {										
											$classification_name=$value['fullname'];
											?>
											<th class="suzuritake"><?php echo $classification_name; ?></th>
											<?php
										}
									}									
									?>									
								</tr>
								<tr>
									<td>Nghe</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											?>
											<td><center><input type="radio" name="listening" value="<?php echo $classification_id; ?>"></center></td>
											<?php
										}
									}									
									?>									
								</tr>
								<tr>
									<td>Nói</td>
									<?php 
									if(count(@$source_classification) > 0 ){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											?>
											<td><center><input type="radio" name="speaking" value="<?php echo $classification_id; ?>"></center></td>
											<?php
										}
									}									
									?>		
								</tr>
								<tr>
									<td>Đọc</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											?>
											<td><center><input type="radio" name="reading" value="<?php echo $classification_id; ?>"></center></td>
											<?php
										}
									}									
									?>		
								</tr>
								<tr>
									<td>Viết</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											?>
											<td><center><input type="radio" name="writing" value="<?php echo $classification_id; ?>"></center></td>
											<?php
										}
									}									
									?>		
								</tr>
							</table>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div class="titanius">
								<div class="vihamus">
									<a href="javascript:void(0);" onclick="saveLanguage('<?php echo route("frontend.index.saveLanguage"); ?>','<?php echo route("frontend.index.deleteLanguage"); ?>');" >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5">Lưu</div>
										</div>								
									</a>
								</div>							
								<div class="vihamus-2 margin-left-5">
									<a href="javascript:void(0);" onclick="noSaveLanguage();" >
										<div class="narit">
											<div><i class="far fa-times-circle"></i></div>
											<div class="margin-left-5">Không lưu</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
					</div>				
				</div>
				<hr  />		
				<div class="row mia">
					<div class="col-lg-12"><div class="rarakata"><h2 class="login-information">Tin học văn phòng</h2><div class="miakasaki margin-left-15">(Bắt buộc)</div></div></div>					
				</div>
				<div class="row mia">					
					<div class="col-lg-12">
						Hãy liệt kê các kỹ năng tin học văn phòng mà bạn có thể đảm nhiệm
					</div>
				</div>
				<?php 
				$status_office_edit='';
				$status_office_save='';
				if((int)@$profile_detail['ms_word'] == 0 || 
					(int)@$profile_detail['ms_excel'] == 0 ||
					(int)@$profile_detail['ms_power_point'] == 0 ||
					(int)@$profile_detail['ms_outlook'] == 0) 
				{
					$status_office_edit='display:none';
					$status_office_save='display:block';
				}else{
					$status_office_edit='display:block';
					$status_office_save='display:none';
				}
				?>
				<div class="note note_office margin-top-15"  style="display: none;"></div>
				<div class="office_edit" style="<?php echo @$status_office_edit; ?>">
					<div class="office_txt">
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>MS Word</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['ms_word_level']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>MS Excel</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['ms_excel_level']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>MS Power Point</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['ms_power_point_level']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>MS Outlook</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['ms_outlook_level']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Phần mềm khác</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['other_software']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Thành tích nổi bật</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['medal']; ?></div> </div>
						</div>						
					</div>
					<?php 
					if((int)@$enabled_do == 1){
						?>
						<div class="row mia">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="vihamus-3 margin-top-5">
									<a href="javascript:void(0);" onclick="showOfficeSave();"  >
										<div class="narit">
											<div><i class="far fa-edit"></i></div>
											<div class="margin-left-5">Chỉnh sửa</div>
										</div>
									</a>
								</div>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
				<div class="office_save" style="<?php echo $status_office_save; ?>">					
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tin học văn phòng</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<table class="language-tb">
								<tr>
									<th class="suzuritake"></th>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {										
											$classification_name=$value['fullname'];
											?>
											<th class="suzuritake"><?php echo $classification_name; ?></th>
											<?php
										}
									}									
									?>									
								</tr>
								<tr>
									<td>MS Word</td>
									<?php 
									if (count(@$source_classification) > 0) {
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											if((int)@$profile_detail['ms_word'] == (int)@$classification_id){
												?>
												<td><center><input type="radio" name="ms_word" value="<?php echo $classification_id; ?>" checked ></center></td>
												<?php
											}else{
												?>
												<td><center><input type="radio" name="ms_word" value="<?php echo $classification_id; ?>"></center></td>
												<?php
											}										
										}
									}									
									?>									
								</tr>
								<tr>
									<td>MS Excel</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											if((int)@$profile_detail['ms_excel'] == (int)@$classification_id){
												?>
												<td><center><input type="radio" name="ms_excel" value="<?php echo $classification_id; ?>" checked ></center></td>
												<?php
											}else{
												?>
												<td><center><input type="radio" name="ms_excel" value="<?php echo $classification_id; ?>"></center></td>
												<?php
											}										
										}
									}									
									?>		
								</tr>
								<tr>
									<td>MS Power Point</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											if((int)@$profile_detail['ms_power_point'] == (int)@$classification_id){
												?>
												<td><center><input type="radio" name="ms_power_point" value="<?php echo $classification_id; ?>" checked ></center></td>
												<?php
											}else{
												?>
												<td><center><input type="radio" name="ms_power_point" value="<?php echo $classification_id; ?>"></center></td>
												<?php
											}										
										}
									}									
									?>		
								</tr>
								<tr>
									<td>MS Outlook</td>
									<?php 
									if(count(@$source_classification) > 0){
										foreach ($source_classification as $key => $value) {
											$classification_id=$value['id'];
											$classification_name=$value['fullname'];
											if((int)@$profile_detail['ms_outlook'] == (int)@$classification_id){
												?>
												<td><center><input type="radio" name="ms_outlook" value="<?php echo $classification_id; ?>" checked ></center></td>
												<?php
											}else{
												?>
												<td><center><input type="radio" name="ms_outlook" value="<?php echo $classification_id; ?>"></center></td>
												<?php
											}										
										}
									}									
									?>		
								</tr>
							</table>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Phần mềm khác</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><input type="text"  name="other_software"  class="vacca" placeholder="Nhập tên phần mềm khác" value="<?php echo @$profile_detail['other_software']; ?>" ></div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Các thành tích nổi bật</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8">
								<textarea name="medal" placeholder="Nhập thành tích..." class="vacca summer-editor" rows="10" ><?php echo @$profile_detail['medal']; ?></textarea>
							</div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div class="titanius">
								<div class="vihamus">
									<a href="javascript:void(0);" onclick="saveOffice('<?php echo route("frontend.index.saveOffice"); ?>');" >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5">Lưu</div>
										</div>								
									</a>
								</div>							
								<div class="vihamus-2 margin-left-5">
									<a href="javascript:void(0);" onclick="noSaveOffice();" >
										<div class="narit">
											<div><i class="far fa-times-circle"></i></div>
											<div class="margin-left-5">Không lưu</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
					</div>				
				</div>
				<hr  />		
				<div class="row mia">
					<div class="col-lg-4"><div class="rarakata"><h2 class="login-information">Kỹ năng / Sở trường</h2><div class="miakasaki margin-left-15"></div></div></div>
					<div class="col-lg-8"></div>
				</div>
				<?php 
				$status_skill_edit='';
				$status_skill_save='';
				if(empty(@$profile_detail['hobby']) || empty(@$profile_detail['talent'])){
					$status_skill_edit='display:none';
					$status_skill_save='display:block';
				}else{
					$status_skill_edit='display:block';
					$status_skill_save='display:none';
				}
				$source_skill=App\SkillModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
				$data_profile_skill= DB::table('skill')
				->join('profile_skill','skill.id','=','profile_skill.skill_id')
				->where('profile_skill.profile_id',@$id)
				->select('skill.id','skill.fullname')
				->groupBy('skill.id','skill.fullname')
				->orderBy('skill.id','asc')
				->get()
				->toArray();
				$data_profile_skill=convertToArray($data_profile_skill);				
				?>
				<div class="note note_skill margin-top-15"  style="display: none;"></div>
				<div class="skill_edit" style="<?php echo $status_skill_edit; ?>">
					<div class="skill_txt">		
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Kỹ năng - Sở trường</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8">
								<?php 
								if(count(@$data_profile_skill) > 0){
									foreach (@$data_profile_skill as $key => $value) {
										$skill_id=$value['id'];
										$skill_name=$value['fullname'];
										?>
										<div><?php echo $skill_name; ?></div>
										<?php
									}							
								}							
								?>							
							</div>
						</div>							
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Sở thích</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['hobby']; ?></div> </div>
						</div>
						<div class="row mia">
							<div class="col-lg-4" ><div class="xika"><div>Kỹ năng đặc biệt</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
							<div class="col-lg-8"><div class="xika2"><?php echo @$profile_detail['talent']; ?></div> </div>
						</div>						
					</div>
					<?php 
					if((int)@$enabled_do == 1){
						?>
						<div class="row mia">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="vihamus-3 margin-top-5">
									<a href="javascript:void(0);" onclick="showSkillSave();"  >
										<div class="narit">
											<div><i class="far fa-edit"></i></div>
											<div class="margin-left-5">Chỉnh sửa</div>
										</div>
									</a>
								</div>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
				<div class="skill_save" style="<?php echo $status_skill_save; ?>">	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Kỹ năng - Sở trường</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<?php 
							if(count(@$source_skill) > 0){
								?>
								<div class="row mia">
									<?php 
									$k=1;
									foreach (@$source_skill as $key => $value) {
										$skill_id=$value['id'];
										$skill_name=$value['fullname'];
										$checked_status='';		
										foreach ($data_profile_skill as $key2 => $value2) {
											$skill_id2=$value2['id'];																	
											if((int)@$skill_id == (int)@$skill_id2){
												$checked_status='checked';						
											}										
										}									
										?>
										<div class="col-lg-4">
											<input type="checkbox" name="skill_id[]" <?php echo $checked_status; ?> value="<?php echo $skill_id; ?>"><?php echo $skill_name; ?>
										</div>
										<?php
										if($k%3 == 0){
											?><div class="clr"></div><?php
										}
										$k++;
									}
									?>
								</div>
								<?php								
							}							
							?>							
						</div>
					</div>									
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Sở thích</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="hobby"  class="vacca" placeholder="Nhập sở thích" value="<?php echo @$profile_detail['hobby']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Kỹ năng đặc biệt</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<textarea name="talent" placeholder="Nhập kỹ năng đặc biệt" class="vacca summer-editor" rows="10" ><?php echo @$profile_detail['talent']; ?></textarea>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div class="titanius">
								<div class="vihamus">
									<a href="javascript:void(0);" onclick="saveSkill('<?php echo route("frontend.index.saveSkill"); ?>');" >
										<div class="narit">
											<div><i class="far fa-save"></i></div>
											<div class="margin-left-5">Lưu</div>
										</div>								
									</a>
								</div>							
								<div class="vihamus-2 margin-left-5">
									<a href="javascript:void(0);" onclick="noSaveSkill();" >
										<div class="narit">
											<div><i class="far fa-times-circle"></i></div>
											<div class="margin-left-5">Không lưu</div>
										</div>								
									</a>
								</div>
							</div>
						</div>
					</div>			
				</div>	
			</form>			
		</div>							
		<div class="col-lg-3">
			@include("frontend.candidate-sidebar")				
		</div>
	</div>
</div>
@endsection()