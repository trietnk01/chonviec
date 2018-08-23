<div class="source-recruitment-box margin-top-15">
	<div class="padding-left-15 padding-right-15 padding-bottom-15">
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
		if(count($source_job_r) > 0){
			?>			
			<div class="row">
				<?php 					
				$data_job_r=convertToArray($source_job_r);
				$k=1;
				foreach ($data_job_r as $key => $value) {
					?>
					<div class="col-lg-6">
						<div class="margin-top-10 madrid"><a href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$value['fullname']; ?></a>&nbsp;(<?php echo @$value['recruitment_quantity']; ?>)</div>
					</div>
					<?php
					if($k%2==0 || $k == count($data_job_r)){
						echo '<div class="clr"></div>';
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
