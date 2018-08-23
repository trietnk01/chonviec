<div class="jp_rightside_job_categories_wrapper jp_blog_right_box_search">
	<div class="jp_rightside_job_categories_heading">
		<h4>Tìm kiếm</h4>
	</div>
	<form class="jp_blog_right_search_wrapper" method="POST" action="<?php echo route('frontend.index.search'); ?>">
		{{ csrf_field() }}	
		<input type="text" placeholder="Tìm kiếm" name="q">
		<button type="submit"><i class="fa fa-search"></i></button>
	</form>				
</div>