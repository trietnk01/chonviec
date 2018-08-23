@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$setting= getSettingSystem();
$width=$setting['product_width']['field_value'];
$height=$setting['product_height']['field_value'];  
$id=0;
if(count(@$item) > 0){
	?>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="margin-top-15">
					<ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="mybreadcrumb" >
						<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
							<a itemscope="" itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo route('frontend.index.getHome'); ?>">
								<span itemprop="name">Trang chủ</span>
							</a>
							<i class="fa fa-angle-right"></i>
							<meta itemprop="position" content="1">
						</li>
						<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
							<a itemscope="" itemtype="http://schema.org/Thing" itemprop="item" href="javascript:void(0);">
								<span itemprop="name">Tuyển dụng</span>
							</a>
							<i class="fa fa-angle-right"></i>
							<meta itemprop="position" content="2">
						</li>
						<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
							<a itemscope="" itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo route('frontend.index.index',[@$alias]) ?>">
								<span itemprop="name"><?php echo @$title; ?></span>
							</a>							
							<meta itemprop="position" content="3">
						</li>      				
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<?php 
				$id= (int)@$item["id"];
				$fullname = @$item["fullname"];	
				$count_view=(int)@$item['count_view'];
				$count_view++;
				$row				= App\RecruitmentModel::find((int)@$id); 
				$row->count_view=(int)@$count_view;
				$row->save();
				$count_view_text=number_format((int)@$count_view,0,",",".");		
				$data_employer=App\EmployerModel::find((int)@$item['employer_id'])->toArray();
				$logo='';
				if(!empty($data_employer['logo'])){
					$logo=asset('upload/'.$width.'x'.$height.'-'.$data_employer['logo']);
				}else{
					$logo=asset('upload/no-logo.png');
				}
				?>
				<div class="source-recruitment-box margin-top-15 padding-bottom-15 padding-left-15 padding-right-15">
					<div class="row">
						<div class="col-lg-3">							
							<div class="box-employer-logo"><center><img src="<?php echo @$logo; ?>" alt="<?php echo @$item["fullname"]; ?>" title="<?php echo @$item["fullname"]; ?>"></center></div>
						</div>
						<div class="col-lg-9">
							<!--begin-->
							<h1 class="recruitment-title"><?php echo @$fullname; ?></h1>
							<h2 style="display: none;"><?php echo @$item['meta_description']; ?></h1>
								<div class="recruitment-detail-employer-title"><span><i class="far fa-building"></i></span><span class="margin-left-10"><?php echo @$data_employer['fullname']; ?></span></div>						

								<div class="margin-top-10 fiob">
									<span><b>Hạn nộp hồ sơ&nbsp;:</b></span>
									<span class="margin-left-10">							
										<?php 
										$duration=datetimeConverterVn($item['duration']);
										echo @$duration;
										?>
									</span>						
								</div>						
								<div class="margin-top-10">
									<div class="ex-website">
										<div class="vihamus-3">
											<?php 
											$arrUser=array();    
											if(Session::has("vmuser")){
												$arrUser=Session::get("vmuser");
											}   
											if(count(@$arrUser) == 0){
												?>
												<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-alert-apply" >
													<div class="narit">
														<div><i class="far fa-edit"></i></div>
														<div class="margin-left-5">Nộp Hồ Sơ</div>
													</div>
												</a>
												<?php
											}else{									
												$source_candidate=\App\CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$arrUser['email'],'UTF-8'))])->select('id','email')->get()->toArray();
												if(count($source_candidate)  == 0){
													?>
													<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-alert-apply" >
														<div class="narit">
															<div><i class="far fa-edit"></i></div>
															<div class="margin-left-5">Nộp Hồ Sơ</div>
														</div>
													</a>
													<?php
												}else{
													?>
													<a href="<?php echo route("frontend.index.getFormApplied",[@$id]); ?>"   >
														<div class="narit">
															<div><i class="far fa-edit"></i></div>
															<div class="margin-left-5">Nộp Hồ Sơ</div>
														</div>
													</a>
													<?php
												}									
											}
											?>								
										</div>
										<div class="vihamus-3 margin-left-5">
											<?php 
											$arrUser=array();    
											if(Session::has("vmuser")){
												$arrUser=Session::get("vmuser");
											}   
											if(count(@$arrUser) == 0){
												?>
												<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-alert-saved-recruitment" >
													<div class="narit">
														<div><i class="far fa-save"></i></div>
														<div class="margin-left-5">Lưu công việc</div>
													</div>
												</a>
												<?php
											}else{									
												$source_candidate=\App\CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$arrUser['email'],'UTF-8'))])->select('id','email')->get()->toArray();
												if(count($source_candidate)  == 0){
													?>
													<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-alert-saved-recruitment" >
														<div class="narit">
															<div><i class="far fa-save"></i></div>
															<div class="margin-left-5">Lưu công việc</div>
														</div>
													</a>
													<?php
												}else{
													?>
													<form enctype="multipart/form-data" name="frm-quicked-saved-recruitment" method="POST" action="<?php echo route('frontend.index.saveQuicklyRecruitment'); ?>">
														{{ csrf_field() }}			
														<input type="hidden" name="recruitment_id" value="<?php echo @$id; ?>">				
														<a href="javascript:void(0);" onclick="document.forms['frm-quicked-saved-recruitment'].submit();"   >
															<div class="narit">
																<div><i class="far fa-save"></i></div>
																<div class="margin-left-5">Lưu công việc</div>
															</div>
														</a>
													</form>											
													<?php
												}									
											}
											?>								
										</div>
									</div>														
								</div>
								<!--end-->
							</div>
						</div>
						<div class="recruitment-heading">
							<div>THÔNG TIN TUYỂN DỤNG</div>
						</div>
						<div class="row margin-top-15">
							<div class="col-lg-6">
								<div class="margin-top-10">
									<?php 				
									$experience_name='';
									$source_experience=App\ExperienceModel::find((int)@$item['experience_id']);
									if(count($source_experience) > 0){
										$data_experience=$source_experience->toArray();
										$experience_name=$data_experience['fullname'];
									}
									?>
									<span><b>Kinh nghiệm&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$experience_name; ?></span>
								</div>
								<div class="margin-top-10">
									<?php 				
									$literacy_name='';
									$source_literacy=App\LiteracyModel::find((int)@$item['literacy_id']);
									if(count($source_literacy) > 0){
										$data_literacy=$source_literacy->toArray();
										$literacy_name=$data_literacy['fullname'];
									}
									?>
									<span><b>Trình độ&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$literacy_name; ?></span>
								</div>
								<?php 
								$source_job=DB::table('job')
								->join('recruitment_job','job.id','=','recruitment_job.job_id')
								->where('recruitment_job.recruitment_id',(int)@$id)
								->select('job.fullname','job.alias')
								->groupBy('job.fullname','job.alias')
								->orderBy('job.id','asc')
								->get()
								->toArray();
								if(count($source_job) > 0){
									$data_job=convertToArray($source_job);
									$job_text='';
									foreach ($data_job as $key => $value) {
										$job_text.='<a href="'.route('frontend.index.index',[@$value['alias']]).'">'.@$value['fullname'].'</a>' .' ,';
									}
									$job_name=mb_substr($job_text, 0,mb_strlen($job_text)-1);
									?>
									<div class="margin-top-10">																
										<span><b>Ngành nghề&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$job_name; ?></span>
									</div>
									<?php
								}
								$source_province=DB::table('province')
								->join('recruitment_place','province.id','=','recruitment_place.province_id')							
								->where('recruitment_place.recruitment_id',(int)@$id)
								->select(								
									'province.fullname',
									'province.alias'								
								)
								->groupBy(								
									'province.fullname',
									'province.alias'								
								)
								->orderBy('province.id', 'desc')						
								->get()
								->toArray();
								if(count(@$source_province) > 0){
									$data_province=convertToArray($source_province);					
									$province_text='';
									foreach ($data_province as $key => $value) {
										$province_text.='<a href="'.route('frontend.index.index',[@$value['alias']]).'">'.@$value['fullname'].'</a>' .' ,';
									}
									$province_title=mb_substr($province_text, 0,mb_strlen($province_text)-1);
									?>
									<div class="margin-top-10">																
										<span><b>Tỉnh/thành phố&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$province_title; ?></span>
									</div>
									<?php
								}																				
								?>	
								<div class="margin-top-10">																
									<span><b>Số lượng tuyển&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$item['quantity']; ?></span>
								</div>						
							</div>
							<div class="col-lg-6">							
								<?php 				
								$salary_name='';
								$source_salary=App\SalaryModel::find((int)@$item['salary_id']);
								if(count($source_salary) > 0){
									$data_salary=$source_salary->toArray();
									$salary_name=$data_salary['fullname'];
									?>
									<div class="margin-top-10">																					
										<span class="lazasa"><b>Mức lương&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_salary['fullname']; ?></span>
									</div>
									<?php
								}
								if((int)@$item['commission_from'] > 0 && (int)@$item['commission_to']){
									?>
									<div class="margin-top-10">																					
										<span class="lazasa"><b>Mức hoa hồng&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$item['commission_from'] ?>%&nbsp;-&nbsp;<?php echo @$item['commission_to']; ?>%</span>
									</div>
									<?php
								}
								$sex_name='';
								$source_sex=App\SexModel::find((int)@$item['sex_id']);
								if(count($source_sex) > 0){
									$data_sex=$source_sex->toArray();
									$sex_name=$data_sex['fullname'];
									?>
									<div class="margin-top-10">																					
										<span class="lazasa"><b>Giới tính&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_sex['fullname']; ?></span>
									</div>
									<?php
								}

								$work_name='';
								$source_work=App\WorkModel::find((int)@$item['work_id']);
								if(count($source_work) > 0){
									$data_work=$source_work->toArray();
									$work_name=$data_work['fullname'];
									?>
									<div class="margin-top-10">																					
										<span class="lazasa"><b>Tính chất công việc&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_work['fullname']; ?></span>
									</div>
									<?php
								}
								$working_form_name='';
								$source_working_form=App\WorkingFormModel::find((int)@$item['working_form_id']);
								if(count($source_working_form) > 0){
									$data_working_form=$source_working_form->toArray();
									$working_form_name=$data_working_form['fullname'];
									?>
									<div class="margin-top-10">																					
										<span class="lazasa"><b>Hình thức làm việc&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_working_form['fullname']; ?></span>
									</div>
									<?php
								}
								?>									
							</div>
						</div>
						<div class="recruitment-heading">
							<div>MÔ TẢ CÔNG VIỆC</div>
						</div>
						<div class="margin-top-30">
							<?php echo @$item['description']; ?>
						</div>
						<div class="recruitment-heading">
							<div>YÊU CẦU</div>
						</div>
						<div class="margin-top-30">
							<?php echo @$item['requirement']; ?>
						</div>
						<div class="recruitment-heading">
							<div>QUYỀN LỢI</div>
						</div>
						<div class="margin-top-30">
							<?php echo @$item['benefit']; ?>
						</div>
						<div class="recruitment-heading">
							<div>YÊU CẦU / HÌNH THỨC NỘP HỒ SƠ</div>
						</div>
						<div class="margin-top-30">
							<?php echo @$item['requirement_profile']; ?>
						</div>
						<?php 
						$source_employer=App\EmployerModel::find((int)@$item['employer_id']);
						if(count(@$source_employer) > 0){
							$data_employer = @$source_employer->toArray();
							?>
							<div class="recruitment-heading">
								<div>THÔNG TIN LIÊN HỆ</div>
							</div>
							<div class="margin-top-30">																					
								<span class="lazasa"><b>Người liên hệ&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_employer['contacted_name']; ?></span>
							</div>
							<div class="margin-top-15">																					
								<span class="lazasa"><b>Địa chỉ công ty&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_employer['address']; ?></span>
							</div>
							<div class="margin-top-15">																					
								<span class="lazasa"><b>Điện thoại liên lạc&nbsp;:&nbsp;</b></span><span class="lazasa"><?php echo @$data_employer['contacted_phone']; ?></span>
							</div>
							<?php						
						}	
						?>	
					</div>
					<div class="jp_hiring_heading_wrapper jp_job_post_heading_wrapper job-general ">
				<h2>Công việc hiện tại</h2>
			</div>
			<div class="clr"></div>					
			@include("frontend.recruitment-attractive")								
			<div class="clr"></div>
			<div class="jp_hiring_heading_wrapper jp_job_post_heading_wrapper job-general ">
				<h2>Công việc lương cao</h2>
			</div>
			<div class="clr"></div>		
			@include("frontend.recruitment-high-salary")
			<div class="jp_hiring_heading_wrapper jp_job_post_heading_wrapper job-general ">
				<h2>Việc làm theo ngành nghề</h2>
			</div>
			<div class="clr"></div>			
			@include("frontend.recruitment-by-job-2")
			<div class="clr"></div>					
				</div>
				<div class="col-lg-4">
					<div class="source-recruitment-box margin-top-15 padding-left-15 padding-right-15 padding-bottom-15">
						<div class="padding-top-15"><center><img src="<?php echo @$logo; ?>" alt="<?php echo @$item["fullname"]; ?>" title="<?php echo @$item["fullname"]; ?>"></center></div>
						<div class="box-wrapper-employer-name"><center><?php echo @$data_employer['fullname']; ?></center></div>
						<div class="margin-top-15"><b>Địa chỉ&nbsp;:&nbsp;</b><?php echo @$data_employer['address']; ?></div>
						<?php 
						if(!empty(@$data_employer['website'])){
							?>
							<div class="margin-top-5"><b>Website&nbsp;:&nbsp;</b><?php echo @$data_employer['website']; ?></div>
							<?php
						}
						?>	
						<div class="margin-top-5"><b>Người liên hệ&nbsp;:&nbsp;</b><?php echo @$data_employer['contacted_name']; ?></div>
						<div class="margin-top-5"><b>Số điện thoại&nbsp;:&nbsp;</b><?php echo @$data_employer['contacted_phone']; ?></div>
						<div class="margin-top-15">
							<center>
								<?php 
								if((int)@$data_employer['status_authentication'] > 0){
									?>
									<img src="<?php echo asset('upload/ok.png'); ?>" width="150">
									<?php
								}else{
									?>
									<img src="<?php echo asset('upload/no-ok.png'); ?>" width="150">
									<?php
								}
								?>
							</center>
						</div>					
					</div>
					<div class="clr"></div>		
					@include("frontend.recruitment-quicked-2")
					<div class="clr"></div>
					@include("frontend.recruitment-search-advance")
					<div class="clr"></div>
					@include("frontend.recruitment-province")
					<div class="clr"></div>
					@include("frontend.recruitment-top-employer")			
					<div class="clr"></div>	
				</div>
			</div>
		</div>
		<?php
	}
?>
<!-- begin modal-alert-apply -->
<div class="modal fade modal-apply" id="modal-alert-apply" tabindex="-1" role="dialog" aria-labelledby="my-modal-apply">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header relative">
				<div class="dang-nhap">ĐĂNG NHẬP</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" name="frm_apply" method="POST" >
					{{ csrf_field() }}			
					<input type="hidden" name="recruitment_id" value="<?php echo @$id; ?>">							
					<div class="note note-apply margin-bottom-5" style="display: none;" ></div>      
					<div>Email</div>
					<div class="margin-top-5"><input type="text" name="email" class="vacca" placeholder="Email" value=""></div>
					<div class="margin-top-15">Mật khẩu</div>
					<div class="margin-top-5"><input type="password" name="password" class="vacca" placeholder="Mật khẩu" value=""></div>
					<div class="margin-top-15">
						<a href="javascript:void(0);" class="btn-login" onclick="loginApply('<?php echo route("frontend.index.loginApply"); ?>');" >Đăng nhập</a>
						<a href="<?php echo route('frontend.index.resetPassWrdCandidate'); ?>" class="btn-remember-password">Quên mật khẩu</a>
					</div>
				</form>				
			</div>      
		</div>
	</div>
</div>  
<!-- end modal-alert-apply -->
<!-- begin modal-alert-saved-recruitment -->
<div class="modal fade modal-apply" id="modal-alert-saved-recruitment" tabindex="-1" role="dialog" aria-labelledby="my-saved-recruitment">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header relative">
				<div class="dang-nhap">ĐĂNG NHẬP</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" name="frm_saved_recruitment" method="POST">
					{{ csrf_field() }}			
					<input type="hidden" name="recruitment_id" value="<?php echo @$id; ?>">							
					<div class="note note-saved-profile margin-bottom-5" style="display: none;" ></div>      
					<div>Email</div>
					<div class="margin-top-5"><input type="text" name="email" class="vacca" placeholder="Email" value=""></div>
					<div class="margin-top-15">Mật khẩu</div>
					<div class="margin-top-5"><input type="password" name="password" class="vacca" placeholder="Mật khẩu" value=""></div>
					<div class="margin-top-15">
						<a href="javascript:void(0);" class="btn-login" onclick="loginSavedRecruitment('<?php echo route("frontend.index.loginSavedRecruitment"); ?>');" >Đăng nhập</a>
						<a href="<?php echo route('frontend.index.resetPassWrdCandidate'); ?>" class="btn-remember-password">Quên mật khẩu</a>
					</div>
				</form>				
			</div>      
		</div>
	</div>
</div>  
<!-- end modal-alert-saved-recruitment -->
@endsection()

