@extends("adminsystem.master")
@section("content")
<?php 
$setting= getSettingSystem();
$linkCancel             =   route('adminsystem.'.$controller.'.getList',[@$category_id]);
$linkSave               =   route('adminsystem.'.$controller.'.save');


$inputCaption           =   '<textarea name="caption" rows="2" cols="100" class="form-control" >'.@$arrRowData['caption'].'</textarea>'; 
$inputAlt               =   '<textarea name="alt" rows="2" cols="100" class="form-control" >'.@$arrRowData['alt'].'</textarea>'; 
$inputPageurl           =   '<input type="text" class="form-control" name="page_url"     value="'.@$arrRowData['page_url'].'">'; 

$sort_order=1;
if(@$arrRowData == null){
    $source_sort_order=App\BannerModel::select('id','sort_order')->orderBy('sort_order','desc')->get()->toArray();
    if(count($source_sort_order) > 0){
        $sort_order=(int)@$source_sort_order[0]['sort_order']+1;
    }
}else{
    $sort_order=@$arrRowData['sort_order'];
}
$inputSortOrder         =   '<input type="text" class="form-control" name="sort_order"  value="'.@$sort_order.'">';

$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");
$ddlCategory            =   cmsSelectboxCategory('category_id', 'form-control',$arrCategoryBanner,@$category_id,"disabled",'Chọn danh mục');
$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id"  value="'.@$id.'" />'; 
$picture                =   "";
$strImage               =   "";
if(count(@$arrRowData)>0){
    if(!empty(@$arrRowData["image"])){
        $picture        =   '<div class="col-sm-6"><center>&nbsp;<img style="width:100%" src="'.asset("/upload/".@$arrRowData["image"]).'" />&nbsp;</center></div><div class="col-sm-6"><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div>';                        
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
        <form class="form-horizontal" name="frm" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <?php echo $inputPictureHidden; ?>                
            <?php echo  $inputID; ?>               
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Caption</b></label>
                        <div class="col-md-10">
                            <?php echo $inputCaption; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                       
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Alt</b></label>
                        <div class="col-md-10">
                            <?php echo $inputAlt; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>                       
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Page URL</b></label>
                        <div class="col-md-10">
                            <?php echo $inputPageurl; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                </div>     
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Khối banner</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlCategory; ?>
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
        var caption=$('textarea[name="caption"]').val();
        var alt=$('textarea[name="alt"]').val();
        var page_url=$('input[name="page_url"]').val();        
        var category_id=$('select[name="category_id"]').val();
        /* begin xử lý image */
        var image_file=null;
        var image_ctrl=$('input[name="image"]');         
        var image_files = $(image_ctrl).get(0).files;        
        if(image_files.length > 0){            
            image_file  = image_files[0];  
        }        
        /* end xử lý image */
        var image_hidden=$('input[name="image_hidden"]').val();         
        var sort_order=$('input[name="sort_order"]').val();
        var status=$('select[name="status"]').val();     
        var token = $('input[name="_token"]').val();   
        
        var dataItem = new FormData();
        dataItem.append('id',id);
        dataItem.append('caption',caption);
        dataItem.append('alt',alt);
        dataItem.append('page_url',page_url);        
        dataItem.append('category_id',category_id);        
        if(image_files.length > 0){
            dataItem.append('image',image_file);
        } 
        dataItem.append('image_hidden',image_hidden);
        dataItem.append('sort_order',sort_order); 
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