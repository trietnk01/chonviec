@extends("frontend.master")
@section("content")
@include("frontend.content-top") 
<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<?php 			
			if(count($item) > 0){		
				$id=$item["id"];
				$fullname = $item["fullname"];				
				$content=$item['content'];	
				/* begin cập nhật count view */
				$count_view=(int)@$item['count_view'];
				$count_view++;
				$row				=	App\ArticleModel::find((int)@$id); 
				$row->count_view=$count_view;
				$row->save();
				$count_view_text=number_format($count_view,0,",",".");
				$created_at=datetimeConverterVn(@$item['created_at']);		
				/* end cập nhật count view */
				$source_article_category=DB::table('article_category')
				->join('category_article','article_category.category_id','=','category_article.id')		
				->select('category_article.id','category_article.fullname','category_article.alias')
				->where('article_category.article_id','=',(int)@$id)					
				->groupBy('category_article.id','category_article.fullname','category_article.alias')
				->orderBy('category_article.sort_order','asc')
				->get()->toArray();						
				$arr_category_name=array();	
				$category_name='';	
				$arr_category_id=array();				
				if(count($source_article_category) > 0){		
					$data_article_category=convertToArray($source_article_category);
					foreach ($data_article_category as $key => $value_article_category) {								
						$arr_category_id[]=$value_article_category["id"];
						$permalink_category=route('frontend.index.index',[$value_article_category['alias']]);
						$arr_category_name[]='<span><a href="'.$permalink_category.'">'.$value_article_category["fullname"].'</a></span>' ;			
					}		
					$category_name=implode(' / ', $arr_category_name);		
				}				
				$data_category_article=App\CategoryArticleModel::find(@$arr_category_id[0])->toArray();
				$breadcrumb= getBreadCrumbCategoryArticle(@$data_category_article);					
				?>	
				<div class="margin-top-15">
					<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="mybreadcrumb" >
						<?php echo @$breadcrumb; ?>
					</ul>		
				</div>	
				<div class="jp_blog_cate_left_main_wrapper margin-top-20">
					<div class="jp_first_blog_post_main_wrapper">
						<div class="jp_first_blog_post_img">
							<img src="<?php echo asset('upload/'.@$item['image']); ?>" class="img-responsive" alt="<?php echo @$item['alt_image']; ?>" title="<?php echo @$item['alt_image']; ?>" />
						</div>
						<div class="jp_first_blog_post_cont_wrapper">
							<ul>
								<li><a href="javascript:void(0);"><i class="fa fa-calendar"></i> &nbsp;&nbsp;<?php echo @$created_at; ?></a></li>
								<li><a href="javascript:void(0);"><i class="fa fa-clone"></i></a>&nbsp;&nbsp;<?php echo @$category_name; ?></li>
							</ul>
							<div class="clr"></div>
							<h1 class="article-title"><a href="javascript:void(0);"><?php echo @$fullname; ?></a></h1>
							<h2 class="article-intro"><?php echo @$item['intro']; ?></h2>
						</div>
						<div class="clr"></div>
						<div class="jp_blog_single_bottom_post_cont">
							<div><?php echo @$item['content']; ?></div>	
							<?php 					
							if(count($arr_category_id) > 0){						
								$data=DB::table('article')
								->join('article_category','article.id','=','article_category.article_id')                			
								->select('article.id','article.alias','article.fullname')
								->whereIn('article_category.category_id', $arr_category_id)
								->where('article.id','<>',(int)@$id)
								->where('article.status','=',1)
								->groupBy('article.id','article.alias','article.fullname')
								->orderBy('article.id', 'desc')
								->take(6)
								->get()->toArray();
								$data=convertToArray($data);            
								?>
								<div class="margin-top-10">
									<div>
										<b>Tin liên quan :</b>
									</div>
									<div class="related-news-right">
										<ul class="related-articles">
											<?php 
											foreach ($data as $key => $value_related) {
												$related_article_fullname=$value_related["fullname"];
												$related_article_alias=$value_related['alias'];
												$related_article_permalink=route('frontend.index.index',[$related_article_alias]) ;
												?>
												<li><a href="<?php echo $related_article_permalink; ?>"><?php echo $related_article_fullname; ?></a></li>
												<?php
											}
											?>					
										</ul>
									</div>	
									<div class="clr"></div>			
								</div>	
								<?php				
							}
							?>	
						</div>
						<div class="clr"></div>
						<div class="jp_first_blog_bottom_cont_wrapper">
							<div class="jp_blog_bottom_left_cont">
								<ul>
									<li><i class="fa fa-eye"></i>&nbsp;&nbsp; Lượt xem : <?php echo @$count_view_text; ?></li>
								</ul>
							</div>
							<div class="jp_blog_bottom_right_cont">
								<p class="hidden-xs"><a href="#" class="hidden-xs"><i class="fa fa-comments"></i></a></p>
								<ul>
													<li>CHIA SẺ :</li>
													<li><a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a></li>
													<li><a href="javascript:void(0);"><i class="fab fa-twitter"></i></a></li>
													<li><a href="javascript:void(0);"><i class="fab fa-pinterest"></i></a></li>
													<li><a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a></li>
													<li class="hidden-xs"><a href="javascript:void(0);"><i class="fab fa-google-plus-g"></i></a></li>
													<li class="hidden-xs"><a href="javascript:void(0);"><i class="fab fa-vimeo-v"></i></a></li>
												</ul>
							</div>
						</div>
						<div class="clr"></div>
					</div>
				</div>				
				<?php
			}
			?>	
		</div>
		<div class="col-lg-4">
			@include("frontend.search-article")
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
@endsection()      

