@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$setting = getSettingSystem();
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
	<div class="row">			
		<div class="col-lg-9">
			<div class="box-lala">
				<form name="frm-search-profile" method="POST" class="box-search-profile" action="<?php echo route('frontend.index.searchingProfile'); ?>" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="margin-top-10"><input type="text" name="q" class="vacca" placeholder="Nhập từ khóa" >		</div>	
					<div class="margin-top-10">
						<?php 
						$source_job=App\JobModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlJob        =cmsSelectboxCategory("job_id", 'vacca selected2', @$source_job, 0,'','Chọn ngành nghề');
						echo $ddlJob;
						?>		
					</div>	
					<div class="margin-top-10">
						<?php 
						/* begin province */
						$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlProvince=cmsSelectboxCategory("province_id","vacca selected2",@$source_province,0,'','Chọn tỉnh thành');
						/* end province */
						echo $ddlProvince;
						?>		
					</div>	
					<div class="margin-top-10">
						<div class="row">
							<div class="col-sm-3">
								<div class="lunarnewyear">
									<div>Lớn hơn</div>
									<div class="margin-left-5"><input type="radio" name="salary_id" value="1" checked> </div>
									<div class="margin-left-15">Nhỏ hơn</div>
									<div class="margin-left-5"><input type="radio" name="salary_id" value="0"></div>
								</div>								
							</div>
							<div class="col-sm-9">
								<input type="text" name="salary" class="vacca" placeholder="Ghi rõ mức lương bằng số"  onkeyup="PhanCachSoTien(this);"   >
							</div>
						</div>
					</div>	
					<div class="margin-top-10">
						<?php 
						$source_literacy=App\LiteracyModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlLiteracy=cmsSelectboxCategory("literacy_id","vacca",@$source_literacy,0,'','Chọn trình độ học vấn');
						echo $ddlLiteracy;
						?>
					</div>	
					<div class="margin-top-10">
						<?php 
						$source_language=App\LanguageModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlLanguage=cmsSelectboxCategory("language_id","vacca",@$source_language,0,'','Chọn ngoại ngữ');
						echo $ddlLanguage;
						?>
					</div>	
					<div class="margin-top-10">
						<?php 
						$source_sex=App\SexModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlSex=cmsSelectboxCategory("sex_id","vacca",@$source_sex,0,'','Chọn giới tính');
						echo $ddlSex;
						?>
					</div>	
					<div class="margin-top-10">
						<?php 
						$source_experience=App\ExperienceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
						$ddlExperience=cmsSelectboxCategory("experience_id","vacca",@$source_experience,0,'','Chọn kinh nghiệm');
						echo $ddlExperience;
						?>
					</div>	
					<div class="margin-top-10">
						<div class="vihamus-search-profile">
							<a href="javascript:void(0);" onclick="document.forms['frm-search-profile'].submit();">
								<div class="narit">
									<div><i class="fas fa-search"></i></div>
									<div class="margin-left-5">Tìm kiếm</div>
								</div>								
							</a>
						</div>
					</div>
				</form>
			</div>			
		</div>
		<div class="col-lg-3">
			@include("frontend.employer-sidebar")				
		</div>
	</div>
</div>
</div>
@endsection()