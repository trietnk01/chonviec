@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="note note-success margin-top-15" >
				<ul>
					<li>Đăng ký tài khoản thành công . Vui lòng kích hoạt tài khoản trong email</li>                        
				</ul>   
			</div>
		</div>		
	</div>
</div>
@endsection()