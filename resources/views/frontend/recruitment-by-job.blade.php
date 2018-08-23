<?php 
$source_job_r=DB::table('job')
		->join('recruitment_job','job.id','=','recruitment_job.job_id')
		->join('recruitment','recruitment.id','=','recruitment_job.recruitment_id')
		->where('recruitment.status',1)
		->where('recruitment.status_employer',1)
		->select('job.id','job.fullname','job.alias',DB::raw('count(recruitment.id) as recruitment_quantity'))
		->groupBy('job.id','job.fullname','job.alias')
		->orderBy('job.id','asc')					
		->get()
		->toArray();
if(count(@$source_job_r) > 0){
	$data_job_r=convertToArray($source_job_r);
	?>
	<div class="jp_rightside_job_categories_wrapper">
					<div class="jp_rightside_job_categories_heading">
						<h4>Việc làm theo ngành nghề</h4>
					</div>
					<div class="jp_rightside_job_categories_content">
						<ul>
							<?php 
							foreach ($data_job_r as $key => $value){
								?>
								<li><i class="fa fa-caret-right"></i> <a title="<?php echo @$value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$value['fullname']; ?> <span>(<?php echo @$value['recruitment_quantity']; ?>)</span></a></li>
								<?php
							}
							?>																						
						</ul>
					</div>
				</div>
	<?php
}		
?>