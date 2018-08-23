@extends("frontend.master")
@section("content")
@include("frontend.content-top")
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
				<h1 class="dn-dk-h">Lấy lại mật khẩu</h1>							
				<div class="row mia">
					<div class="col-lg-4" ><div class="xika"><div>Email</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
					<div class="col-lg-8"><input type="text"  name="email" class="vacca" placeholder="Email" value="<?php echo @$data['email']; ?>"></div>
				</div>				
				<div class="row mia">
					<div class="col-lg-4" ></div>
					<div class="col-lg-8">
						<div >
							<a href="javascript:void(0);" class="btn-login" onclick="document.forms['frm'].submit();" >Xác nhận</a>							
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-4"></div>
	</div>
</div>
@endsection()