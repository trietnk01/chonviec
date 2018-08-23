<?php 
$query_quicked_job=DB::table('recruitment')
->join('employer','recruitment.employer_id','=','employer.id')
->join('salary','recruitment.salary_id','=','salary.id')
->join('experience','recruitment.experience_id','=','experience.id');
$query_quicked_job->where('recruitment.status',1);
$query_quicked_job->where('recruitment.status_employer',1);
$query_quicked_job->where('recruitment.status_quick',1);
$source_quicked_job=$query_quicked_job->select(
	'recruitment.id',
	'recruitment.fullname',
	'recruitment.alias',
	'recruitment.duration',
	'recruitment.status_hot',
	'experience.fullname as experience_name',
	'salary.fullname as salary_name',
	'employer.fullname as employer_fullname',
	'employer.alias as employer_alias',
	'employer.logo'
)
->groupBy(
	'recruitment.id',
	'recruitment.fullname',
	'recruitment.alias',
	'recruitment.duration',
	'recruitment.status_hot',
	'experience.fullname',
	'salary.fullname',
	'employer.fullname',
	'employer.alias',
	'employer.logo'
)
->orderBy('recruitment.id', 'desc')
->take(12)
->get()
->toArray();		
if(count(@$source_quicked_job) > 0){
	$data_quicked_job=convertToArray(@$source_quicked_job);
	?>
	<div class="jp_spotlight_main_wrapper">
		<div class="spotlight_header_wrapper">
			<h4>Việc làm tuyển gấp</h4>
		</div>
		<div class="jp_spotlight_slider_wrapper">
			<div class="owl-carousel owl-theme">
				<?php 
				foreach ($data_quicked_job as $key => $value) {
					$quicked_job_fullname=truncateString($value['fullname'],999999999) ;
					$quicked_job_employer=truncateString($value['employer_fullname'],9999999999);
					$quicked_job_duration=datetimeConverterVn($value['duration']);
					$quicked_job_logo='';
					if(!empty(@$value['logo'])){
						$quicked_job_logo=asset('upload/'.$width.'x'.$height.'-'.@$value['logo']);
					}else{
						$quicked_job_logo=asset('upload/no-logo.png');
					}
					$quicked_job_hot_gif='';
					if((int)@$value['status_hot'] == 1){
						$quicked_job_hot_gif= '&nbsp;<img src="'.asset('upload/hot.gif').'" width="40" />';
					}
					$source_province=DB::table('province')
					->join('recruitment_place','province.id','=','recruitment_place.province_id')
					->where('recruitment_place.recruitment_id',(int)@$value['id'])
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
					$data_province=convertToArray(@$source_province);					
					$province_text='';
					foreach ($data_province as $key_province => $value_province) {
						$province_text.='<span class="margin-left-15"><a title="'.@$value_province['fullname'].'" href="'.route('frontend.index.index',[@$value_province['alias']]).'">'.@$value_province['fullname'].'</a></span>';
					}								
					?>
					<div class="item">
						<div class="jp_spotlight_slider_img_Wrapper">
							<a href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>" title="<?php echo @$value['employer_fullname']; ?>"><center><img src="<?php echo @$quicked_job_logo; ?>" alt="<?php echo @$value['employer_fullname']; ?>" title="<?php echo @$value['employer_fullname']; ?>" /></center></a>
						</div> 
						<div class="jp_spotlight_slider_cont_Wrapper">
							<h4><a href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>" title="<?php echo @$value['fullname']; ?>"><?php echo @$value['fullname']; ?></a></h4>
							<p><a href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>" title="<?php echo @$value['employer_fullname']; ?>"><?php echo @$value['employer_fullname']; ?></a></p>
							<ul>
								<li><i class="fab fa-cc-paypal"></i>&nbsp; <?php echo @$value['salary_name']; ?></li>
								<li><i class="fa fa-map-marker"></i>&nbsp; <?php echo @$province_text; ?></li>
							</ul>
						</div>
						<div class="jp_spotlight_slider_btn_wrapper">
							<div class="jp_spotlight_slider_btn">
								<ul>
									<li><a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> &nbsp;ADD RESUME</a></li>
								</ul>
							</div>
						</div>
					</div>
					<?php
				}
				?>																					
			</div>
		</div>
	</div>
	<?php
}
?>