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
<h1 style="display: none;"><?php echo @$seo_title; ?></h1>
<h2 style="display: none;"><?php echo @$seo_meta_description; ?></h2>
<?php 
$data_banner=getBanner('re-log');
$source_banner=$data_banner['items'];
if(count(@$items) > 0){
	?>
	<div class="box-meal">
		<div>			
			<div class="owl-carousel banner owl-theme">
				<?php   
				foreach ($source_banner as $key => $value) {
					$featured_img=asset('upload/'.$value['image']);
					?>
					<div><img src="<?php echo $featured_img; ?>" alt="<?php echo @$value['alt']; ?>" title="<?php echo @$value['alt']; ?>"></div>
					<?php   	
				}                   
				?>
			</div>
		</div>  
		<div class="single-cat-title">
			<div class="company-real-man">
				<div class="container">
					<div class="col-lg-12">        			
						<div class="jp_tittle_heading_wrapper">
							<div class="jp_tittle_heading">
								<h2><?php echo @$item['fullname']; ?></h2>
							</div>
							<div class="jp_tittle_breadcrumb_main_wrapper">
								<div class="jp_tittle_breadcrumb_wrapper">
									<ul itemscope="" itemtype="http://schema.org/BreadcrumbList">
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
											<i class="fa fa-angle-right"></i>					
											<meta itemprop="position" content="3">
										</li>      				
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>        	
		</div>
	</div>
	<?php
}
if(count(@$item) > 0){
	$logo='';
	if(!empty($item['logo'])){
		$logo=asset('upload/'.$width.'x'.$height.'-'.$item['logo']);
	}else{
		$logo=asset('upload/no-logo.png');
	}
	?>
	<div class="box-company">				
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="jp_cs_com_info_wrapper">
						<div class="jp_cs_com_info_img">
							<img src="<?php echo $logo; ?>" alt="<?php echo @$item['fullname']; ?>" title="<?php echo @$item['fullname']; ?>">
						</div>
						<div class="jp_cs_com_info_img_cont">
							<h2 class="company-title"><?php echo @$item['fullname']; ?></h2>
							<p><b>Người liên hệ</b> : <?php echo @$item['contacted_name'] ?> - <b>Điện thoại liên lạc</b> : <?php echo @$item['contacted_phone'] ?></p>
							<h3 class="company-address"><i class="fa fa-map-marker"></i> &nbsp;&nbsp;<?php echo @$item['address']; ?></h3>
						</div>
						<div class="clr"></div>
						<div class="company-authentication">
							<center>
								<?php 
								if((int)@$item['status_authentication'] > 0){
									?>
									<img src="<?php echo asset('upload/ok.png'); ?>" width="150">
									<?php
								}else{
									?>
									<img src="<?php echo asset('upload/no-ok.png'); ?>" width="150">
									<?php
								}
								?>
							</center>
						</div>
					</div>
				</div>				
			</div>			
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="jp_listing_left_sidebar_wrapper">
					<div class="jp_job_des">
						<div class="company-heading">
							<div>SƠ LƯỢC CÔNG TY</div>
						</div>
						<div class="margin-top-30">
							<?php echo @$item['intro']; ?>
						</div>
						<ul>
							<li><i class="fa fa-globe"></i>&nbsp;&nbsp; <a target="_blank" href="<?php echo 'https://'.@$item['website']; ?>"><?php echo @$item['website']; ?></a></li>
							<li><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp; <a target="_blank" href="javascript:void(0);">Download tài liệu</a></li>

						</ul>
					</div>					
				</div>
				<div class="clr"></div>
				<?php 
				if(count(@$items) > 0){
					?>
					<div class="lipo">
						<div class="jp_hiring_heading_wrapper jp_job_post_heading_wrapper job-general ">
							<h2>Ngành nghề liên quan</h2>
						</div>
						<div class="clr"></div>		
						<div class="ss_featured_recruitment">							
							<div class="owl-carousel recruitment-related owl-theme">
								<?php 
								$k=0;											
								foreach ($items as $key => $value) {
									$hot_attractive_fullname=truncateString(@$value['fullname'],50) ;
									$hot_attractive_employer=truncateString(@$value['employer_fullname'],9999);
									$hot_attractive_duration=datetimeConverterVn(@$value['duration']);
									$hot_attractive_hot_gif='';
									if((int)@$value['status_hot'] == 1){
										$hot_attractive_hot_gif= '&nbsp;<img src="'.asset('upload/hot.gif').'" width="40" />';
									}
									$hot_attractive_logo='';
									if(!empty(@$value['logo'])){
										$hot_attractive_logo=asset('upload/'.$width.'x'.$height.'-'.@$value['logo']);
									}else{
										$hot_attractive_logo=asset('upload/no-logo.png');
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
									if($k%4 == 0){										
										echo '<div class="item">';
									}
									?>
									<div class="jp_job_post_main_wrapper_cont jp_job_post_main_wrapper_cont2">
										<div class="jp_job_post_main_wrapper">
											<div class="row">
												<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
													<div class="jp_job_post_side_img">
														<a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><img src="<?php echo @$hot_attractive_logo; ?>" alt="<?php echo @$value['employer_fullname']; ?>" title="<?php echo @$value['employer_fullname']; ?>" /></a>
													</div>
													<div class="jp_job_post_right_cont">
														<h4 class="recent-job-title"><a title="<?php echo @$value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$value['fullname']; ?></a></h4>
														<p class="recent-job-employer-name"><a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><?php echo @$value['employer_fullname']; ?></a></p> 
														<ul>
															<li><i class="fab fa-cc-paypal"></i><span class="margin-left-15"><?php echo @$value['salary_name']; ?></span></li>
															<li><i class="fa fa-map-marker"></i>&nbsp; <?php echo @$province_text; ?></li>
														</ul>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="jp_job_post_right_btn_wrapper">
														<ul>																		
															<li><a href="javascript:void(0);">Ứng tuyển</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>													
									</div>	
									<?php
									$k++;
									if($k%4==0 || $k == count(@$items)){
										echo '</div>';
									} 	
								}		
								?>
							</div>
						</div>
					</div>		
					<?php
				}
				?>
				<div class="clr"></div>
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
				<div class="jp_rightside_career_wrapper">
					<div class="jp_rightside_career_heading">
						<h4>TỔNG QUAN</h4>
					</div>
					<div class="jp_rightside_career_main_content">
						<div class="margin-top-15">
							<center>
								<div class="jp_jop_overview_img">
									<center><img src="<?php echo $logo; ?>" alt="<?php echo @$item['fullname']; ?>" title="<?php echo @$item['fullname']; ?>"></center>
								</div>
							</center>
						</div>							
						<?php 
						$data_job=App\RecruitmentModel::whereRaw('employer_id=?',[(int)@$item['id']])->select('id')->get()->toArray();				
						?>
						<div class="jp_job_post_right_overview_btn margin-top-15">
							<center>
								<ul>
									<li><a href="javascript:void(0);"><?php echo count(@$data_job); ?> công việc</a></li>
								</ul>
							</center>
						</div>	
						<hr/>
						<div class="jp_listing_overview_list_outside_main_wrapper">
							<div class="jp_listing_overview_list_main_wrapper">
								<div class="jp_listing_list_icon">
									<i class="fa fa-map-marker"></i>
								</div>
								<div class="jp_listing_list_icon_cont_wrapper">
									<ul>
										<li>Địa chỉ:</li>
										<li><?php echo @$item['address']; ?></li>
									</ul>
								</div>
								<div class="clr"></div>
							</div>	
							<div class="jp_listing_overview_list_main_wrapper jp_listing_overview_list_main_wrapper2">
								<div class="jp_listing_list_icon">
									<i class="far fa-user"></i>
								</div>
								<div class="jp_listing_list_icon_cont_wrapper">
									<ul>
										<li>Người liên hệ:</li>
										<li><?php echo @$item['contacted_name']; ?></li>
									</ul>
								</div>
								<div class="clr"></div>
							</div>	
							<div class="jp_listing_overview_list_main_wrapper jp_listing_overview_list_main_wrapper2">
								<div class="jp_listing_list_icon">
									<i class="fas fa-phone"></i>
								</div>
								<div class="jp_listing_list_icon_cont_wrapper">
									<ul>
										<li>Điện thoại liên lạc:</li>
										<li><?php echo @$item['contacted_phone']; ?></li>
									</ul>
								</div>
								<div class="clr"></div>
							</div>						
							<div class="jp_listing_overview_list_main_wrapper jp_listing_overview_list_main_wrapper2">
								<div class="jp_listing_list_icon">
									<i class="fa fa-envelope"></i>
								</div>
								<div class="jp_listing_list_icon_cont_wrapper">
									<ul>
										<li>Email:</li>
										<li><?php echo @$item['email']; ?></li>
									</ul>
								</div>
								<div class="clr"></div>
							</div>							
							<div class="jp_listing_right_bar_btn_wrapper">
								<div class="jp_listing_right_bar_btn">
									<ul>
										<li><a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> &nbsp;FACEBOOK</a></li>
										<li><a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> &nbsp;CHIA SẼ</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clr"></div>
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
	<?php
}
?>
@endsection()               

