@extends("frontend.master")
@section("content")
@include("frontend.content-top"))
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>


<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<form name="frm" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<h1 class="dn-dk-h">XÁC THỰC TÀI KHOẢN</h1>		
				<?php 
				if(count(@$msg) > 0){
					$type_msg='';					
					if((int)@$checked == 1){						
						$type_msg='note-success';
					}else{
						$type_msg='note-danger';
					}
					?>
					<div class="note margin-top-15 <?php echo $type_msg; ?>" >
						<ul>
							<?php 
							foreach (@$msg as $key => $value) {
								?>
								<li><?php echo $value; ?></li>
								<?php
							}
							?>                              
						</ul>	
					</div>      
					<?php
				}								
				?>	
			</form>								
		</div>
		<div class="col-lg-4"></div>
	</div>
</div>

@endsection()