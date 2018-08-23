<div class="jp_spotlight_main_wrapper">
	<div class="spotlight_header_wrapper">
		<h4>Việc làm tuyển gấp</h4>
	</div>
	<div class="clr"></div>
	<div class="jp_spotlight_slider_wrapper">
		<div class="owl-carousel owl-theme">
			<?php 		
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id')
			->join('experience','recruitment.experience_id','=','experience.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_quick',1);
			$source=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'experience.fullname as experience_name',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias'
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
				'employer.alias'
			)
			->orderBy('recruitment.id', 'desc')			
			->get()
			->toArray();
			if(count(@$source) > 0){
				$data=convertToArray(@$source);
				$k=0;
				$j=0;
				foreach ($data as $key => $value){
					$fullname=truncateString($value['fullname'],99999999999) ;
					$employer=truncateString($value['employer_fullname'],9999999999);
					$duration=datetimeConverterVn($value['duration']);
					$hot_gif='';
					if((int)@$value['status_hot'] == 1){
						$hot_gif= '&nbsp;<img src="'.asset('upload/hot.gif').'" width="40" />';
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
					$data_province=convertToArray($source_province);					
					$province_text='';
					foreach ($data_province as $key_province => $value_province) {
						$province_text.='<span class="kara-province"><a title="'.@$value_province["fullname"].'" href="'.route('frontend.index.index',[$value_province['alias']]).'">'.$value_province['fullname'].'</a></span>&nbsp;,';
					}
					$province_text=mb_substr($province_text, 0,mb_strlen($province_text)-1);
					$class='fackyou';
					if((int)$k == count($data)-1){
						$class='';
					}
					if((int)@$j%5==0){
						echo '<div class="item">';
					}
					?>					
					<div class="<?php echo $class; ?> margin-top-10 padding-bottom-10">
						<div class="hot-job-name"><a title="<?php echo @$value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$fullname; ?></a><?php echo @$hot_gif; ?></div>
						<div class="khaly margin-top-5"><a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><?php echo @$employer; ?></a></div>
						<div class="margin-top-10">
							<div class="xibatuba">
								<div><i class="fa fa-map-marker-alt"></i>&nbsp;<?php echo @$province_text; ?></div>
								<div class="margin-top-5"><i class="fa fa-dollar-sign"></i>&nbsp;<?php echo @$value['salary_name']; ?></div>											
							</div>
							<div class="miranbaros">
								<div><i class="fa fa-chart-bar"></i>&nbsp;<?php echo @$value['experience_name']; ?></div>
								<div><i class="fa fa-clock"></i>&nbsp;<?php echo @$duration; ?></div>
							</div>
							<div class="clr"></div>
						</div>
					</div>					
					<?php
					$k++;
					$j++;
					if((int)@$j%5==0 || $j == count(@$data)){
						echo '</div>';
					}
				}
			}
			?>
		</div>		
	</div>
</div>