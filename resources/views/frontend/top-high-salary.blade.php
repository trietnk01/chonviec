<?php 
$query_top_hiring=DB::table('employer')
->leftJoin('recruitment','employer.id','=','recruitment.employer_id');					
$query_top_hiring->where('employer.status',1);
$query_top_hiring->where('employer.status_authentication',1);					
$source_top_hiring=$query_top_hiring->select(
	'employer.id',
	'employer.fullname',
	'employer.alias',						
	'employer.logo',
	DB::raw('count(recruitment.id) as total')
)
->groupBy(
	'employer.id',
	'employer.fullname',
	'employer.alias',						
	'employer.logo'
)
->orderBy('employer.id', 'desc')
->take(100)
->get()
->toArray();
if(count(@$source_top_hiring) > 0){
	$data_top_hiring=convertToArray(@$source_top_hiring);
	?>						
	<div class="jp_hiring_slider_main_wrapper">
		<div class="jp_hiring_heading_wrapper">
			<h2>Các công ty trả lương cao</h2>
		</div>
		<div class="jp_hiring_slider_wrapper">
			<div class="owl-carousel owl-theme">
				<?php 
				foreach ($data_top_hiring as $key => $value) {
					$employer_logo='';
					if(!empty(@$value['logo'])){
						$employer_logo=asset('upload/'.$width.'x'.$height.'-'.$value['logo']);
					}else{
						$employer_logo=asset('upload/no-logo.png');
					}
					$employer_fullname=truncateString(@$value['fullname'],9999) ;
					?>
					<div class="item">
						<div class="jp_hiring_content_main_wrapper">
							<div class="jp_hiring_content_wrapper">
								<a href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>" title="<?php echo @$value['fullname']; ?>"><img src="<?php echo @$employer_logo; ?>" alt="<?php echo @$value['fullname']; ?>" title="<?php echo @$value['fullname']; ?>" /></a>
								<h4><a href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$employer_fullname; ?></a></h4>
								
								<div class="opening"><center><a href="javascript:void(0);"><?php echo @$value['total']; ?> Opening</a></center></div>															
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