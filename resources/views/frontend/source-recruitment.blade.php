@extends("frontend.master")
@section("content")
<?php 
$seo=getSeo();
$setting= getSettingSystem();
$width=$setting['product_width']['field_value'];
$height=$setting['product_height']['field_value'];  
$seo_title="";
if(!empty(@$title)){
	$seo_title=@$title;
}else{	
	$seo_title=@$seo["title"];
}
$seo_meta_description="";
if(!empty(@$meta_description)){
	$seo_meta_description=@$meta_description;
}else{
	$seo_meta_description=@$seo["meta_description"];
}
?>
@include("frontend.content-top")
<h1 style="display: none;"><?php echo @$seo_title; ?></h1>
<h2 style="display: none;"><?php echo @$seo_meta_description; ?></h2>
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
			<div class="jp_hiring_heading_wrapper jp_job_post_heading_wrapper job-general ">
				<h2><?php echo @$title; ?></h2>
			</div>
			<div class="clr"></div>
			<div class="source-recruitment-box margin-top-15 padding-left-15 padding-right-15">	
				<div class="owl-carousel main-recruitment owl-theme">
					<?php 
					if(count(@$items) > 0){					
						$k=0;
						$t=0;
						foreach ($items as $key => $value) {
							$fullname= truncateString(@$value['fullname'],70);
							$duration=datetimeConverterVn(@$value['duration']);
							$employer_name=truncateString(@$value['employer_fullname'],9999);
							$logo='';
							if(!empty(@$value['logo'])){
								$logo=asset('upload/'.$width.'x'.$height.'-'.@$value['logo']);
							}else{
								$logo=asset('upload/no-logo.png');
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
								$province_text.='<span class="margin-left-5"><a title="'.@$value_province['fullname'].'" href="'.route('frontend.index.index',[@$value_province['alias']]).'">'.@$value_province['fullname'].'</a></span>&nbsp;/';
							}
							$province_text=substr($province_text,0,strlen(@$province_text)-1);
							$hot_gif='';
							if((int)@$value['status_hot'] == 1){
								$hot_gif= '&nbsp;<img src="'.asset('upload/hot.gif').'" width="40" />';
							}
							if($k%9 == 0){								
								echo '<div class="item">';
							}
							if($t%3==0){
								echo '<div class="row">';
							}
							?>
							<div class="col-lg-4">
								<div class="box-source-recruitment">
									<div class="row">
										<div class="col-xs-4">
											<div class="padding-left-10"><a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><img alt="<?php echo @$value['employer_fullname']; ?>" title="<?php echo @$value['employer_fullname']; ?>"  src="<?php echo @$logo; ?>"></a></div>
										</div>
										<div class="col-xs-8">
											<div class="ribi"><a title="<?php echo @$value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$fullname; ?></a></div>
											<div class="khaly"><a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><?php echo @$employer_name; ?></a></div>
											<div class="source-recruitment-bill"><i class="far fa-money-bill-alt"></i><span class="margin-left-5"><?php echo @$value['salary_name']; ?></span></div>
											<div>
												<i class="fas fa-map-marker-alt"></i><?php echo @$province_text; ?>
											</div>
											<div>
												Ngày hết hạn&nbsp;:&nbsp;<?php echo @$duration; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
							$k++;
							$t++;
							if($t%3==0 || $t == count(@$items)){
								echo '</div>';
							}
							if($k%9==0 || $k == count(@$items)){
								echo '</div>';
							} 
						}
					}
					?>				
				</div>				
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
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
@endsection()               

