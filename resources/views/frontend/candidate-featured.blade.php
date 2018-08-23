<div class="jp_rightside_career_wrapper jp_best_deal_right_sec_wrapper">
	<?php 
	$query_resume=DB::table('profile')
	->join('candidate','profile.candidate_id','=','candidate.id');
	$query_resume->where('profile.status',1);					
	$source_resume=$query_resume->select(
		'profile.id',
		'profile.fullname',
		'profile.alias',
		'candidate.fullname as candidate_name',							
		'candidate.avatar'
	)
	->groupBy(
		'profile.id',
		'profile.fullname',
		'profile.alias',
		'candidate.fullname',						
		'candidate.avatar'
	)
	->orderBy('profile.id', 'desc')
	->take(50)
	->get()
	->toArray();
	if(count(@$source_resume) > 0){
		$data_resume=convertToArray(@$source_resume);
		?>
		<div class="jp_rightside_career_heading">
			<h4>Hồ sơ nổi bật</h4>
		</div>
		<div class="jp_spotlight_slider_wrapper">
			<div class="owl-carousel owl-theme">
				<?php 
				$k=0;
				foreach(@$data_resume as $key => $value){	
					$resume_name=truncateString($value['fullname'],40) ;	
					$resume_avatar='';
					if(!empty(@$value['avatar'])){
						$resume_avatar=asset('upload/'.$width.'x'.$height.'-'.@$value['avatar']);
					}else{
						$resume_avatar=asset('upload/no-logo.png');
					}
					if((int)@$k%2==0){
						echo '<div class="item">';
					}						
					?>
					<div>
						<div class="jp_rightside_career_img">                                	
							<img src="<?php echo @$resume_avatar; ?>" alt="<?php echo @$value['candidate_name']; ?>" title="<?php echo @$value['candidate_name']; ?>" />
						</div>
						<div class="jp_rightside_career_img_cont">
							<h4><a href="javascript:void(0);" title="<?php echo @$value['candidate_name']; ?>"><?php echo @$value['candidate_name']; ?></a></h4>
							<p><a href="<?php echo route('frontend.index.index',[@$value['alias']]) ; ?>" title="<?php echo @$value['fullname']; ?>"><i class="fa fa-folder-open-o"></i> &nbsp;<?php echo $resume_name; ?></a></p>
						</div>
					</div>
					<?php
					$k++;
					if((int)@$k%2==0 || (int)@$k==count($data_resume))
						echo '</div>';
				}
				?>					
			</div>									             
		</div>
		<?php
	}
	?>					
</div>