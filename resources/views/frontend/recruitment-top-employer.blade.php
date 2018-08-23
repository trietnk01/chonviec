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
		<h4>Nhà tuyển dụng hàng đầu</h4>
	</div>
	<div class="clr"></div>
	<div class="jp_spotlight_slider_wrapper">
		<div class="owl-carousel owl-theme">
			<?php 
			$data_employer_r=App\EmployerModel::whereRaw('status = 1')->select('id','fullname','alias','logo')->orderBy('id','desc')->get()->toArray();
			if(count($data_employer_r) > 0){
				$k=0;	
				$t=0;						
				foreach ($data_employer_r as $key => $value) {
					$employer_r_img='';
					if(!empty($value['logo'])){
						$employer_r_img=asset('upload/'.$width.'x'.$height.'-'.$value['logo']);
					}else{
						$employer_r_img=asset('upload/no-logo.png');
					}
					if($k%9 == 0){								
						echo '<div class="item">';
					}
					if($t%3==0){
						echo '<div class="row">';
					}
					?>
					<div class="col-lg-4">
						<div class="employer-box margin-top-5">
							<a title="<?php echo $value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><img alt="<?php echo @$value['fullname']; ?>" title="<?php echo @$value['fullname']; ?>"   src="<?php echo $employer_r_img; ?>"></a>
						</div>
					</div>
					<?php
					$k++;
					$t++;
					if($t%3==0 || $t == count(@$data_employer_r)){
						echo '</div>';
					}
					if($k%9==0 || $k == count(@$data_employer_r)){
						echo '</div>';
					} 							
				}				
			}
			?>
		</div>		
	</div>
</div>