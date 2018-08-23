@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$linkChangeProfileSearchStatus	=	route('frontend.index.changeProfileSearchStatus');
$inputID     =   '<input type="hidden" name="id"  value="'.@$id.'" />';
$linkSave               =   route('frontend.index.saveFileAttached');
$linkCancel               =   route('frontend.index.getGroupProfile',[@$id]);
$linkCreateProfileStepByStep=route('frontend.index.getProfileDetail',[@$id]);
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>


   
<div class="container">
	<div class="row">			
		<div class="col-lg-9">
			<form name="frm-group-profile" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<?php echo $inputID; ?>
				<input type="hidden" name="filter_page" value="1">      
				<h1 class="dn-dk-h">BỘ HỒ SƠ</h1>
				@if(Session::has("message"))	
				<?php 
				$type_msg='';
				$checked=Session::get('message')['checked'];
				if((int)@$checked==1){
					$type_msg='note-success';
				}else{
					$type_msg='note-danger';
				}
				?>		
				<div class="note margin-top-15 <?php echo $type_msg; ?>" >
					<?php 				
					$msg=Session::get("message")['msg'];				
					if(count(@$msg) > 0){
						?>					
						<ul>
							<?php 
							foreach (@$msg as $key => $value) {
								?>
								<li><?php echo $value; ?></li>
								<?php
							}
							?>                              
						</ul>					
						<?php
					}				
					?>
				</div>                                                                            
				@endif			
				<div class="box-group-profile">					
					<div class="rawon">
						<div><center><i class="far fa-folder-open"></i></center></div>
						<div class="mimi"><a href="<?php echo $linkCreateProfileStepByStep; ?>">Tạo hồ sơ từng bước</a></div>
					</div>										
					<div class="rawon margin-left-15">
						<div><center><i class="fas fa-upload"></i></center></div>
						<div class="mimi">							
							<a href="javascript:void(0);" onclick="uploadFile(this);">Upload hồ sơ</a>
							<div style="height: 0px; width: 0; overflow: hidden;">
								<input type="file" name="file_attached"  />                                    
							</div>
						</div>
					</div>
					
				</div>
			</form>	
		</div>
		<div class="col-lg-3">
			@include("frontend.candidate-sidebar")				
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript">		
	chooseFileInfoGroupProfile("<?php echo $linkSave; ?>");
</script>
@endsection()