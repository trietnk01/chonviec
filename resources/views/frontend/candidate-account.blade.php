@extends("frontend.master")
@section("content")
@include("frontend.content-top")
<?php 
$seo=getSeo();
$arrUser=array();
$ssNameUser='vmuser';
if(Session::has($ssNameUser)){
      $arrUser=Session::get($ssNameUser);
} 
$picture                =   "";
$strImage               =   "";
$setting = getSettingSystem();
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$data)>0){
    if(!empty(@$data["avatar"])){
        $picture        =   '<div class="box-logo"><div><center>&nbsp;<img src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$data["avatar"]).'" style="width:100%" title="'.@$data['fullname'].'" alt="'.@$data['fullname'].'" />&nbsp;</center></div><div><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div></div>';                        
        $strImage       =   @$data["avatar"];
    }        
} 
$inputPictureHidden     =   '<input type="hidden" name="image_hidden"  value="'.@$strImage.'" />';
/* begin ngày sinh */
$source_day=array();
$source_month=array();
$source_year=array();
for($i=0;$i<=31;$i++){
	if($i==0){
		$source_day[]='Ngày';
	}else{
		$source_day[]=$i;
	}	
}
for($i=0;$i<=12;$i++){
	if($i==0){
		$source_month[]='Tháng';
	}else{
		$source_month[]=$i;
	}	
}
$arrDate    = date_parse_from_format('Y-m-d H:i:s', date("Y-m-d")) ;
for ($i=1953; $i <= (int)@$arrDate['year']; $i++) { 
	$source_year[$i]=$i;
}
$source_year[0]='Năm';
krsort($source_year);
$ddlDay=cmsSelectbox(	"day"	,	"vacca"	,	$source_day		,	@$data['day']	,	''	);
$ddlMonth=cmsSelectbox(	"month"	,	"vacca"	,	$source_month	,	@$data['month']	,	''	);
$ddlYear=cmsSelectbox(	"year"	,	"vacca"	,	$source_year	,	@$data['year']	,	''	);
/* end ngày sinh */
/* begin giới tính */
$source_sex=App\SexModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$ddlSex=cmsSelectboxCategory("sex_id","vacca",@$source_sex,@$data['sex_id'],'','Chọn giới tính');
/* end giới tính */
/* begin province */
$source_province=App\ProvinceModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$ddlProvince=cmsSelectboxCategory("province_id","vacca selected2",@$source_province,@$data['province_id'],'','Chọn tỉnh thành');
/* end province */
/* begin Hôn nhân */
$source_marriage=App\MarriageModel::orderBy('id','asc')->select('id','fullname')->get()->toArray();
$ddlMarriage=cmsSelectboxCategory("marriage_id","vacca",@$source_marriage,@$data['marriage_id'],'','Chọn tình trạng hôn nhân');
/* end Hôn nhân */
?>
<h1 style="display: none;"><?php echo @$seo["title"]; ?></h1>
<h2 style="display: none;"><?php echo @$seo["meta_description"]; ?></h2>
<div class="wrapper-register">
	<div class="container">
		<div class="row">			
			<div class="col-lg-9">
				<form name="frm-candidate-account" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<?php echo $inputPictureHidden; ?>
					<h1 class="dn-dk-h">Tài Khoản Ứng Viên</h1>
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
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin đăng nhập</h2></div>
						<div class="col-lg-8"></div>
					</div>			
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Email</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text" disabled name="email" class="vacca" placeholder="Email" value="<?php echo @$arrUser['email']; ?>"></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8 change-passwrd">
							<a href="<?php echo route('frontend.index.viewCandidateSecurity'); ?>">Đổi mật khẩu</a>
						</div>
					</div>			
					<hr  />
					<div class="row mia">
						<div class="col-lg-4"><h2 class="login-information">Thông tin cá nhân</h2></div>
						<div class="col-lg-8"></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Họ tên</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="fullname" class="vacca" placeholder="Họ tên ứng viên" value="<?php echo @$data['fullname']; ?>" ></div>
					</div>				
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Điện thoại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="phone" class="vacca" placeholder="Điện thoại ứng viên" value="<?php echo @$data['phone']; ?>" ></div>
					</div>	
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Avatar</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<div class="recommend">
								<div><input type="file" name="image"  /></div>
								<div><font color="#E30000"><b>Khuyến khích cập nhật avatar hình vuông</b></font></div>
							</div>
							<div class="picture-area"><?php echo $picture; ?>                      </div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Ngày sinh</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8">
							<div class="recommend">
								<div><?php echo $ddlDay; ?></div>
								<div class="margin-left-15"><?php echo $ddlMonth; ?></div>
								<div class="margin-left-15"><?php echo $ddlYear; ?></div>
							</div>
						</div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Giới tính</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlSex; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Hôn nhân</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlMarriage; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Tỉnh / Thành phố</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><?php echo $ddlProvince; ?></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ><div class="xika"><div>Chỗ ở hiện tại</div><div class="pappa margin-left-5"><i class="fas fa-asterisk"></i></div></div></div>
						<div class="col-lg-8"><input type="text"  name="address" class="vacca" placeholder="Chỗ ở hiện tại" value="<?php echo @$data['address']; ?>" ></div>
					</div>
					<div class="row mia">
						<div class="col-lg-4" ></div>
						<div class="col-lg-8"><div class="btn-dang-ky"><a href="javascript:void(0);" onclick="document.forms['frm-candidate-account'].submit();" >Cập nhật</a></div></div>
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