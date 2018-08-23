@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo $seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo $seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
		<div class="row">			
			<div class="col-lg-9">
				<form name="frm-cabinet-saved-recruitment" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="filter_page" value="1">     
					<h1 class="dn-dk-h">VIỆC LÀM ĐÃ LƯU</h1>
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
					<div class="margin-top-15">
						<div class="row">												
							<div class="col-lg-4"><input type="text" name="recruitment_name" class="kiem-cong-viec kiatisak" value="<?php echo @$recruitment_name; ?>" placeholder="Nhập vị trí tuyển dụng..."></div>
							<div class="col-lg-4"><div class="btn-search-recruitment"><a href="javascript:void(0);" onclick="document.forms['frm-cabinet-saved-recruitment'].submit();">Lọc</a></div></div>
						</div>										
					</div>							
					<table class="table table-bordered margin-top-15">
						<thead>
							<tr>
								<th class="news-title"><center>Việc làm</center></th>		
								<th class="news-title"><center>Ngày lưu</center></th>			
								<th class="news-title"><center>Xóa</center></th>											
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($data as $key => $value) {							
								?>
								<tr>
									<td><?php echo $value["recruitment_name"]; ?></td>	
									<td><?php echo $value['created_at'] ?></td>							
									<td><?php echo $value["deleted"]; ?></td>	
								</tr>
								<?php
							}
							?>											
						</tbody>
					</table>	
					<div class="margin-top-15">
						<?php 
						if(count($data) > 0){
							echo $pagination->showPagination();
						}  
						?>
					</div>	
				</form>		
			</div>
			<div class="col-lg-3">
				@include("frontend.candidate-sidebar")				
			</div>
		</div>
	</div>
</div>
@endsection()