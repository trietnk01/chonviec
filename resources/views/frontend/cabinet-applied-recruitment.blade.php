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
				<form name="frm-cabinet-applied-recruitment" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="filter_page" value="1">     
					<h1 class="dn-dk-h">VIỆC LÀM ĐÃ ỨNG TUYỂN</h1>
					<div class="margin-top-15">
						<div class="row">												
							<div class="col-lg-4"><input type="text" name="recruitment_name" class="kiem-cong-viec kiatisak" value="<?php echo @$recruitment_name; ?>" placeholder="Nhập vị trí tuyển dụng..."></div>
							<div class="col-lg-4"><div class="btn-search-recruitment"><a href="javascript:void(0);" onclick="document.forms['frm-cabinet-applied-recruitment'].submit();">Lọc</a></div></div>
						</div>										
					</div>				
					<table class="table table-bordered margin-top-15">
						<thead>
							<tr>
								<th class="news-title"><center>Vị trí ứng tuyển</center></th>	
								<th class="news-title"><center>Tiêu đề hồ sơ</center></th>									
								<th class="news-title"><center>File hồ sơ đính kèm</center></th>	
								<th class="news-title"><center>Ngày nộp</center></th>			
								<th class="news-title"><center>Chi tiết hồ sơ</center></th>											
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($data as $key => $value) {							
								?>
								<tr>
									<td><?php echo $value["recruitment_name"]; ?></td>	
									<td><?php echo $value["profile_name"]; ?></td>																
									<td><?php echo $value["file_attached"]; ?></td>	
									<td><?php echo $value['created_at'] ?></td>							
									<td><?php echo $value["entranced"]; ?></td>	
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