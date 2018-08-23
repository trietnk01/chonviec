@extends("adminsystem.master")
@section("content")
<?php 
$setting= getSettingSystem();
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');

$inputEmail             =   @$arrRowData['email']; 
$inputPassword          =   '<input type="password"   name="password" class="form-control" />';
$inputPasswordConfirmed =   '<input type="password"  name="password_confirmed" class="form-control"  />';
$inputFullName          =   '<input type="text" class="form-control" name="fullname"        value="'.@$arrRowData['fullname'].'">'; 

$inputPhone             =   '<input type="text" class="form-control" name="phone"        value="'.@$arrRowData['phone'].'">'; 

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
$birthday    = date_parse_from_format('Y-m-d H:i:s', @$arrRowData['birthday']) ;
$ddlDay=cmsSelectbox(   "day"   ,   "form-control" ,   $source_day     ,   (int)@$birthday['day']   ,   ''  );
$ddlMonth=cmsSelectbox( "month" ,   "form-control" ,   $source_month   ,   (int)@$birthday['month'] ,   ''  );
$ddlYear=cmsSelectbox(  "year"  ,   "form-control" ,   $source_year    ,   (int)@$birthday['year']  ,   ''  );
/* end ngày sinh */
/* begin giới tính */
$source_sex=App\SexModel::whereRaw('status = ?',[1])->orderBy('sort_order','asc')->select('id','fullname')->get()->toArray();
$ddlSex=cmsSelectboxCategory("sex_id","form-control",$source_sex,@$arrRowData['sex_id'],'','Chọn giới tính');
/* end giới tính */
/* begin province */
$source_province=App\ProvinceModel::whereRaw('status = ?',[1])->orderBy('fullname','asc')->select('id','fullname')->get()->toArray();
$ddlProvince=cmsSelectboxCategory("province_id","form-control selected2",$source_province,@$arrRowData['province_id'],'','Chọn tỉnh thành');
/* end province */
/* begin Hôn nhân */
$source_marriage=App\MarriageModel::whereRaw('status = ?',[1])->orderBy('sort_order','asc')->select('id','fullname')->get()->toArray();
$ddlMarriage=cmsSelectboxCategory("marriage_id","form-control",$source_marriage,@$arrRowData['marriage_id'],'','Chọn tình trạng hôn nhân');
/* end Hôn nhân */

$inputAddress           =   '<input type="text" class="form-control" name="address"        value="'.@$arrRowData['address'].'">'; 


$arrStatus              =   array(-1 => '- Select status -', 1 => 'Kích hoạt', 0 => 'Ngưng kích hoạt');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");

/* begin logo */
$picture                =   "";
$strImage               =   "";
$setting = getSettingSystem();
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$arrRowData)>0){
    if(!empty(@$arrRowData["avatar"])){
        $picture        =   '<div class="box-logo"><div><center>&nbsp;<img src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$arrRowData["avatar"]).'" style="width:100%" />&nbsp;</center></div><div><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div></div>';                        
        $strImage       =   @$arrRowData["avatar"];
    }        
} 
$inputPictureHidden     =   '<input type="hidden" name="image_hidden"  value="'.@$strImage.'" />';
/* end logo */

$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id" value="'.@$id.'" />'; 

?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="note"  style="display: none;"></div>
        <div class="caption">
            <i class="{{$icon}}"></i>
            <span class="caption-subject font-dark sbold uppercase">{{$title}}</span>
        </div>
        <div class="actions">
           <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <button onclick="save()" class="btn purple">Lưu <i class="fa fa-floppy-o"></i></button> 
                    <a href="<?php echo $linkCancel; ?>" class="btn green">Thoát <i class="fa fa-ban"></i></a>                    </div>                                                
                </div>
            </div>    
        </div>
    </div>
    <div class="portlet-body form">
        <form class="form-horizontal" name="frm" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}                     
            <?php echo  $inputID; ?>
            <?php echo $inputPictureHidden; ?>                   
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Email</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $inputEmail; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Password</b></label>
                        <div class="col-md-9">
                            <?php echo $inputPassword; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Xác nhận mật khẩu</b></label>
                        <div class="col-md-9">
                            <?php echo $inputPasswordConfirmed; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Tên ứng viên</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $inputFullName; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Điện thoại</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $inputPhone; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Ngày sinh</b></label>
                        <div class="col-md-9 ctrl-right">
                            <div class="recommend">
                                <div><?php echo $ddlDay; ?></div>
                                <div class="margin-left-15"><?php echo $ddlMonth; ?></div>
                                <div class="margin-left-15"><?php echo $ddlYear; ?></div>
                            </div>
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Giới tính</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $ddlSex; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Hôn nhân</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $ddlMarriage; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Tỉnh / thành phố</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $ddlProvince; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Địa chỉ</b></label>
                        <div class="col-md-9 ctrl-right">
                            <?php echo $inputAddress; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                         
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="col-md-3 control-label" ><b>Logo</b></div>
                        <div class="col-md-9 ctrl-right">
                            <div class="recommend">
                                <div><input type="file" name="image"  /></div>
                                <div><font color="#E30000"><b>Khuyến khích cập nhật logo hình vuông</b></font></div>
                            </div>
                            <div class="picture-area"><?php echo $picture; ?>                      </div>
                        </div>
                    </div>                    
                </div>                
                <div class="row"> 
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Trạng thái đăng nhập</b></label>
                        <div class="col-md-9">                            
                            <?php echo $ddlStatus; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>                                                                                          
            </div>              
        </form>
    </div>
</div>
<script type="text/javascript" language="javascript">    
    function deleteImage(){
        var xac_nhan = 0;
        var msg="Bạn có muốn xóa ?";
        if(window.confirm(msg)){ 
            xac_nhan = 1;
        }
        if(xac_nhan  == 0){
            return 0;
        }
        $(".picture-area").empty();
        $("input[name='image_hidden']").val("");        
    }
    function save(){
        var id=$('input[name="id"]').val();         
        var password=$('input[name="password"]').val();
        var password_confirmed=$('input[name="password_confirmed"]').val();           
        var fullname=$('input[name="fullname"]').val(); 
        var phone=$('input[name="phone"]').val();   
        var day=$('select[name="day"]').val();          
        var month=$('select[name="month"]').val();          
        var year=$('select[name="year"]').val();          
        var sex_id=$('select[name="sex_id"]').val();       
        var marriage_id=$('select[name="marriage_id"]').val();       
        var province_id=$('select[name="province_id"]').val();          
        var address=$('input[name="address"]').val();                           
        /* begin xử lý image */
        var image_file=null;
        var image_ctrl=$('input[name="image"]');         
        var image_files = $(image_ctrl).get(0).files;        
        if(image_files.length > 0){            
            image_file  = image_files[0];  
        }        
        var image_hidden=$('input[name="image_hidden"]').val(); 
        /* end xử lý image */                             
        var status=$('select[name="status"]').val();           
        var token = $('input[name="_token"]').val();           
        var dataItem = new FormData();
        dataItem.append('id',id);        
        dataItem.append('password',password);        
        dataItem.append('password_confirmed',password_confirmed);        
        dataItem.append('fullname',fullname);
        dataItem.append('phone',phone);
        dataItem.append('day',day);
        dataItem.append('month',month);
        dataItem.append('year',year);
        dataItem.append('sex_id',sex_id);
        dataItem.append('marriage_id',marriage_id);
        dataItem.append('province_id',province_id);        
        dataItem.append('address',address);                    
        if(image_files.length > 0){
            dataItem.append('image',image_file);
        } 
        dataItem.append('image_hidden',image_hidden);        
        dataItem.append('status',status);          
        dataItem.append('_token',token);      
        $.ajax({
            url: '<?php echo $linkSave; ?>',
            type: 'POST',
            data: dataItem,
            async: false,
            success: function (data) {
                if(data.checked==1){        
                alert(data.msg.success);                                              
                    window.location.href = data.link_edit;                    
                }else{                        
                    showMsg('note',data);                                                                                                                     
                }
                spinner.hide();
            },
            error : function (data){
                spinner.hide();
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }           
     
</script>
@endsection()            