@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$source_scale=App\ScaleModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$arrUser=array();
$ssNameUser='vmuser';
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
} 
$picture                =   "";
$str_image               =   "";
$setting = getSettingSystem();
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$data)>0){
	if(!empty(@$data["logo"])){
		$picture        =   '<div class="box-logo"><div><center>&nbsp;<img src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$data["logo"]).'" style="width:100%" title="'.@$data['fullname'].'" alt="'.@$data['fullname'].'" />&nbsp;</center></div><div><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div></div>';                        
		$str_image       =   @$data["logo"];
	}        
} 
$inputPictureHidden     =   '<input type="hidden" name="image_hidden"  value="'.@$str_image.'" />';
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
		<div class="row">			
			<div class="col-lg-9">
				<form name="frm-employer-account" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<?php echo $inputPictureHidden; ?>
					<h1 class="dn-dk-h">Tài Khoản Nhà Tuyển Dụng</h1>
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
					$ddlProvince=cmsSelectboxCategory("province_id","vacca selected2",$source_province,@$data['province_id'],'','Chọn tỉnh thành');
					$ddlScale=cmsSelectboxCategory("scale_id","vacca",$source_scale,@$data['scale_id'],'','Chọn quy mô công ty');		
					?>					
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin đăng nhập</h2></div>
						<div class="col-lg-8"></div>
					</div>			
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Email</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<input type="text" disabled name="email" class="vacca" placeholder="Email" value="<?php echo @$arrUser['email']; ?>">
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8 change-passwrd">
							<a href="<?php echo route('frontend.index.viewEmployerSecurity'); ?>">Đổi mật khẩu</a>
						</div>
					</div>
					<hr  />
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin công ty</h2></div>
						<div class="col-lg-8"></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tên công ty</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="fullname" class="vacca" placeholder="Tên công ty" value="<?php echo @$data['fullname']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Địa chỉ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="address" class="vacca" placeholder="Địa chỉ công ty" value="<?php echo @$data['address']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Điện thoại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="phone" class="vacca" placeholder="Điện thoại công ty" value="<?php echo @$data['phone']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tỉnh/thành phố</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">						
							<?php echo $ddlProvince; ?>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Quy mô công ty</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<?php echo $ddlScale; ?>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Logo</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<div class="recommend">
								<div><input type="file" name="image"  /></div>
								<div><font color="#E30000"><b>Khuyến khích cập nhật logo hình vuông</b></font></div>
							</div>
							<div class="picture-area"><?php echo @$picture; ?>                      </div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Sơ lược công ty</div></div></div>
						<div class="col-lg-8"><textarea name="intro" placeholder="Nhập sơ lược công ty"  class="vacca summer-editor" rows="10" ><?php echo @$data['intro']; ?></textarea></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Fax</div></div></div>
						<div class="col-lg-8"><input type="text"  name="fax" class="vacca" placeholder="Fax công ty" value="<?php echo @$data['fax']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Website</div></div></div>
						<div class="col-lg-8">
							<div class="ex-website">
								<div class="tattoo"><input type="text"  name="website" class="vacca" placeholder="Nhập tên miền website của công ty" value="<?php echo @$data['website']; ?>" ></div>
								<div class="margin-left-15 lili">Ví dụ : www.pnj.com.vn</div>
							</div>						
						</div>
					</div>				
					<hr  />
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin liên hệ</h2></div>
						<div class="col-lg-8"></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tên người liên hệ</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="contacted_name" class="vacca" placeholder="Tên người liên hệ" value="<?php echo @$data['contacted_name']; ?>" ></div>
					</div>

					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Điện thoại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="contacted_phone" class="vacca" placeholder="Điện thoại người liên hệ" value="<?php echo @$data['contacted_phone']; ?>" ></div>
					</div>				
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8"><div class="btn-dang-ky"><a href="javascript:void(0);" onclick="document.forms['frm-employer-account'].submit();" >Cập nhật</a></div></div>
					</div>	
				</form>
			</div>
			<div class="col-lg-3">
				@include("frontend.employer-sidebar")				
			</div>
		</div>
	</div>
</div>
@endsection()