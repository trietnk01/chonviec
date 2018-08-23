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
				<form name="frm-cabinet-profile" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="filter_page" value="1">    
					<h1 class="dn-dk-h">TỦ HỒ SƠ</h1>
					<div class="margin-top-15">
						<div class="row">
							<div class="col-lg-8"><input type="text" name="q" class="kiem-cong-viec kiatisak" value="<?php echo $q; ?>" placeholder="Nhập tiêu đề hồ sơ..."></div>
							
							<div class="col-lg-4"><div class="btn-search-recruitment"><a href="javascript:void(0);" onclick="document.forms['frm-cabinet-profile'].submit();">Lọc</a></div></div>
						</div>										
					</div>						
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
					<table class="table table-bordered margin-top-15">
						<thead>
							<tr>
								<th class="news-title">Tiêu đề hồ sơ</th>	
								<th class="news-title"><center>Ngày tạo</center></th>							
								<th class="news-title"><center>Cho phép nhà tuyển dụng tìm kiếm</center></th>
								<th class="news-title"><center>Duyệt</center></th>
								<th class="news-title"><center>Sửa</center></th>
								<th class="news-title"><center>Chi tiết hồ sơ</center></th>
								<th class="news-title"><center>Xóa</center></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($data as $key => $value) {
								$id=$value['id'];
								$fullname=$value['fullname'];	
								$created_at=$value['created_at'];
								$status_search=$value['status_search'];						
								$status=$value['status'];						
								$edited=$value['edited'];
								$detail=$value['detail'];
								$deleted=$value['deleted'];
								?>
								<tr>
									<td><?php echo $fullname; ?></td>	
									<td><?php echo $created_at; ?></td>
									<td><?php echo $status_search; ?></td>
									<td><?php echo $status; ?></td>
									<td><?php echo $edited; ?></td>
									<td><?php echo $detail; ?></td>
									<td><?php echo $deleted; ?></td>						
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