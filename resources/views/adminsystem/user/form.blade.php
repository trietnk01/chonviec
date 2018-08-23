@extends("adminsystem.master")
@section("content")
<?php 
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');

$str_disabled           =   "";
if($task == "edit"){
    $str_disabled       =   "disabled";
}
$inputUsername          =   '<input type="text" class="form-control" name="username"  '.$str_disabled.'      value="'.@$arrRowData['username'].'">'; 
$inputEmail             =   '<input type="text" class="form-control" name="email"             value="'.@$arrRowData['email'].'">'; 
$inputPassword          =   '<input type="password"   name="password" class="form-control" />';
$inputConfirmPassword   =   '<input type="password"  name="confirm_password" class="form-control"  />';

$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");
$inputFullName          =   '<input type="text" class="form-control" name="fullname"       value="'.@$arrRowData['fullname'].'">'; 
$inputPhone          =   '<input type="text" class="form-control" name="phone"       value="'.@$arrRowData['phone'].'">'; 
$ddlGroupMember         =   cmsSelectboxGroupMemberMultiple("group_member_id[]", 'form-control', @$arrGroupMember, @$arrUserGroupMember,"",'Chọn danh mục');
$inputSortOrder         =   '<input type="text" class="form-control" name="sort_order"   value="'.@$arrRowData['sort_order'].'">';
$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id"   value="'.@$id.'" />'; 
$picture                =   "";
$strImage               =   "";
if(count(@$arrRowData)>0){
    if(!empty(@$arrRowData["image"])){
        $picture        =   '<div class="col-sm-6"><center>&nbsp;<img src="'.asset("/upload/".@$arrRowData["image"]).'" style="width:100%" />&nbsp;</center></div><div class="col-sm-6"><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div>';                        
        $strImage       =   @$arrRowData["image"];
    }        
}   
$inputPictureHidden     =   '<input type="hidden" name="image_hidden"  value="'.@$strImage.'" />';
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
        <form class="form-horizontal" role="form" name="frm" enctype="multipart/form-data">
            {{ csrf_field() }}          
                <?php echo  $inputID; ?>   
                 <?php echo $inputPictureHidden; ?>
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Username</b></label>
                        <div class="col-md-10">
                            <?php echo $inputUsername; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Email</b></label>
                        <div class="col-md-10">
                            <?php echo $inputEmail; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>      
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Password</b></label>
                        <div class="col-md-10">
                            <?php echo $inputPassword; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Xác nhận mật khẩu</b></label>
                        <div class="col-md-10">
                            <?php echo $inputConfirmPassword; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>       
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Tên người dùng</b></label>
                        <div class="col-md-10">
                            <?php echo $inputFullName; ?>
                            <span class="help-block"></span>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Điện thoại</b></label>
                        <div class="col-md-10">
                            <?php echo $inputPhone; ?>
                            <span class="help-block"></span>
                        </div>
                    </div> 
                </div>
                <div class="row">  
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Nhóm người dùng</b></label>
                        <div class="col-md-10">                            
                            <?php echo $ddlGroupMember; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Hình</b></label>
                        <div class="col-md-10">
                            <div class="box-logo">
                                <div><input type="file" name="image"  />   </div>
                                <div><font color="red"><b>Kích thước ảnh không được vượt quá <?php echo (int)max_size_upload; ?>MB</b></font></div>
                            </div> 
                            <div class="picture-area"><?php echo $picture; ?>                      </div>
                        </div>
                    </div>     
                </div>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Sắp xếp</b></label>
                        <div class="col-md-10">
                            <?php echo $inputSortOrder; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Trạng thái</b></label>
                        <div class="col-md-10">
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
        var username=$('input[name="username"]').val();
        var email = $('input[name="email"]').val();
        var password=$('input[name="password"]').val();
        var confirm_password=$('input[name="confirm_password"]').val();
        var status=$('select[name="status"]').val();
        var fullname=$('input[name="fullname"]').val();
        var phone=$('input[name="phone"]').val();
        /* begin xử lý image */
        var image_file=null;
        var image_ctrl=$('input[name="image"]');         
        var image_files = $(image_ctrl).get(0).files;        
        if(image_files.length > 0){            
            image_file  = image_files[0];  
        }        
        /* end xử lý image */
        var image_hidden=$('input[name="image_hidden"]').val(); 
        var group_member_id=$('select[name="group_member_id[]"]').val();        
        var sort_order=$('input[name="sort_order"]').val();        
        var token = $('input[name="_token"]').val();   
        
        var dataItem = new FormData();
        dataItem.append('id',id);
        dataItem.append('username',username);
        dataItem.append('email',email);
        dataItem.append('password',password);        
        dataItem.append('confirm_password',confirm_password);
        dataItem.append('status',status); 
        dataItem.append('fullname',fullname);   
        dataItem.append('phone',phone);         
        if(image_files.length > 0){
            dataItem.append('image',image_file);
        }        
        dataItem.append('image_hidden',image_hidden);
        dataItem.append('group_member_id',group_member_id);
        dataItem.append('sort_order',sort_order);         
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