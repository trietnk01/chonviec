<div class="jp_spotlight_main_wrapper">
	<div class="spotlight_header_wrapper">
		<h4>Tìm kiếm nâng cao</h4>
	</div>
	<div class="windy padding-bottom-15 padding-left-15 padding-right-15">
		<form action="<?php echo route('frontend.index.searchRecruitment'); ?>" method="POST" name="frm-search-job-detail">
			{{ csrf_field() }}
			<?php 
			$source_job=App\JobModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
			$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
			$source_salary=App\SalaryModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
			$source_literacy=App\LiteracyModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();			
			$source_sex=App\SexModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();		
			$source_work=App\WorkModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();		
			$source_working_form=App\WorkingFormModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();		
			$source_experience=App\ExperienceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();		
			$ddlJob        =cmsSelectboxCategory("job_id", 'vacca selected2', @$source_job, @$job_id,'','Tất cả ngành nghề');
			$ddlProvince        =cmsSelectboxCategory("province_id", 'vacca selected2', @$source_province, @$province_id,'','Tất cả tỉnh thành');
			$ddlSalary        =cmsSelectboxCategory("salary_id", 'vacca', @$source_salary, @$salary_id,'','Tất cả mức lương');
			$ddlLiteracy        =cmsSelectboxCategory("literacy_id", 'vacca', @$source_literacy, @$literacy_id,'','Tất cả trình độ học vấn');
			$ddlSex        =cmsSelectboxCategory("sex_id", 'vacca', @$source_sex, @$sex_id,'','Tất cả giới tính');
			$ddlWork        =cmsSelectboxCategory("work_id", 'vacca', @$source_work, @$work_id,'','Tất cả công việc');
			$ddlWorkingForm        =cmsSelectboxCategory("working_form_id", 'vacca', @$source_working_form, @$working_form_id,'','Tất cả loại hình công việc');
			$ddlExperience        =cmsSelectboxCategory("experience_id", 'vacca', @$source_experience,@$experience_id ,'','Tất cả kinh nghiệm');
			?>
			<div class="ritacruista"><input type="text" name="q" value="<?php echo @$q; ?>" class="vacca" placeholder="Nhập từ khóa"></div>					
			<div class="ritacruista"><?php echo $ddlJob; ?></div>
			<div class="ritacruista"><?php echo $ddlProvince; ?></div>						
			<div class="ritacruista"><?php echo $ddlSalary; ?></div>
			<div class="ritacruista"><?php echo $ddlLiteracy; ?></div>						
			<div class="ritacruista"><?php echo $ddlSex; ?></div>	
			<div class="ritacruista"><?php echo $ddlWork; ?></div>	
			<div class="ritacruista"><?php echo $ddlWorkingForm; ?></div>				
			<div class="ritacruista"><?php echo $ddlExperience; ?></div>	
			<div class="ritacruista margin-bottom-5">	
				<div class="vihamus-3">
					<a href="javascript:void(0);" onclick="document.forms['frm-search-job-detail'].submit();">
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
