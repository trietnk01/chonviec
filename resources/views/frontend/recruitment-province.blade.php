<?php 
$seo=getSeo();
$setting= getSettingSystem();
$width=$setting['product_width']['field_value'];
$height=$setting['product_height']['field_value'];  
$article_width=$setting['article_width']['field_value'];
$article_height=$setting['article_height']['field_value'];
?>
<div class="jp_spotlight_main_wrapper">
	<div class="spotlight_header_wrapper">
		<h4>Việc làm tại các thành phố lớn</h4>
	</div>
	<div class="clr"></div>
	<div class="employer-top-box">
		<div class="owl-carousel owl-theme">
			<?php 
			$data=App\ProvinceModel::select('id','fullname','alias')->orderBy('id','desc')->get()->toArray();
			if(count($data) > 0){
				$k=0;									
				foreach ($data as $key => $value) {					
					if($k%6 == 0){								
						echo '<div class="item">';
					}					
					?>
					<div class="row">
						<div class="col-lg-12">
							<div class="employer-hr-box">
								<a title="<?php echo $value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$value['fullname']; ?></a>
							</div>							
						</div>
					</div>					
					<?php
					$k++;					
					if($k%6==0 || $k == count(@$data)){
						echo '</div>';
					} 							
				}				
			}
			?>
		</div>		
	</div>
</div>
<!--<div class="jp_spotlight_main_wrapper">
	<div class="spotlight_header_wrapper">
		<h4>Việc làm tại các thành phố lớn</h4>
	</div>
	<div class="clr"></div>
	<div class="padding-left-15 padding-right-15 padding-bottom-15 province-big">
		<div class="owl-carousel employer-top-box owl-theme">
			<?php 
			$data_province_r=App\ProvinceModel::select('id','fullname','alias')->orderBy('id','desc')->get()->toArray();
			if(count($data_province_r) > 0){
				foreach ($data_province_r as $key => $value) {								
					?>					
					<div class="item">
						<a href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>">
							<?php echo @$value['fullname']; ?>																				
						</a>
					</div>
					<?php
				}
			}		
			?>
		</div>		
	</div>
</div>-->