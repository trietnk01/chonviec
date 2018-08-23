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
		<div class="col-lg-9">
			<form name="frm-cabinet-applied-profile" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<input type="hidden" name="filter_page" value="1">     
				<h1 class="dn-dk-h">KẾT QUẢ TÌM KIẾM HỒ SƠ ỨNG VIÊN</h1>				
				<table class="table table-bordered margin-top-15">
					<thead>
						<tr>
							<th class="news-title"><center>Tiêu đề hồ sơ</center></th>	
							<th class="news-title"><center>Ứng viên</center></th>	
							<th class="news-title"><center>Trình độ</center></th>	
							<th class="news-title"><center>Kinh nghiệm</center></th>														
							<th class="news-title"><center>Mức lương</center></th>		
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach ($data as $key => $value) {							
							?>
							<tr>
								<td><?php echo @$value['profile_name']; ?></td>	
								<td><?php echo @$value['candidate_name']; ?></td>								
								<td><?php echo @$value['literacy_name']; ?></td>				
								<td><?php echo @$value['experience_name']; ?></td>				
								<td><?php echo @$value['salary']; ?></td>												
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
			@include("frontend.employer-searching-sidebar")	
			@include("frontend.employer-sidebar")				
		</div>
	</div>
</div>
@endsection()