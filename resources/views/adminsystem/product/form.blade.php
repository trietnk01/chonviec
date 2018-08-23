@extends("adminsystem.master")
@section("content")
<?php 
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');
$linkCreateAlias        =   route('adminsystem.'.$controller.'.createAlias');

$inputFullName          =   '<input type="text" class="form-control" name="fullname"    onblur="createAlias()"     value="'.@$arrRowData['fullname'].'">';
$inputAlias             =   '<input type="text" class="form-control" name="alias"     disabled      value="'.@$arrRowData['alias'].'">'; 
 
$inputMetakeyword             =   '<textarea  name="meta_keyword" rows="2" cols="100" class="form-control" >'.@$arrRowData['meta_keyword'].'</textarea>'; 
$inputMetadescription             =   '<textarea  name="meta_description" rows="2" cols="100" class="form-control" >'.@$arrRowData['meta_description'].'</textarea>'; 
$inputPrice             =   '<input type="text" class="form-control" name="price" onkeyup="PhanCachSoTien(this);"       value="'.convertToTextPrice(@$arrRowData['price']).'">';
$inputSalePrice             =   '<input type="text" class="form-control" name="sale_price" onkeyup="PhanCachSoTien(this);"       value="'.convertToTextPrice(@$arrRowData['sale_price']).'">';

$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");
$inputIntro            =   '<textarea  name="intro" rows="5" cols="100" class="form-control" >'.@$arrRowData['intro'].'</textarea>'; 
$inputDetail            =   '<textarea name="detail" rows="5" cols="100" class="form-control" >'.@$arrRowData['detail'].'</textarea>'; 
$inputTechnicalDetail            =   '<textarea name="technical_detail" rows="5" cols="100" class="form-control" >'.@$arrRowData['technical_detail'].'</textarea>'; 
$inputVideoId          =   '<input type="text" class="form-control" name="video_id"       value="'.@$arrRowData['video_id'].'">';
$inputSortOrder         =   '<input type="text" class="form-control" name="sort_order"      value="'.@$arrRowData['sort_order'].'">';

$ddlCategoryProduct      =   cmsSelectboxCategory("category_id","form-control",$arrCategoryProductRecursive,@$arrRowData['category_id'],"",'Chọn danh mục');
$ddlCategoryParam        =cmsSelectboxCategoryParamMultiple("category_param_id[]", 'form-control', @$arrCategoryParamRecursive, @$arrPostParam,"",'Chọn danh mục');
$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id" value="'.@$id.'" />'; 
$inputAliasMenu       =   '<input type="hidden" name="alias_menu" value="'.@$arrRowData['alias'].'" />'; 
$picture                =   "";
$strImage               =   "";
$setting= getSettingSystem();
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];  
if(count(@$arrRowData)>0){
    if(!empty(@$arrRowData["image"])){
        $picture        =   '<div class="col-sm-6"><center>&nbsp;<img src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$arrRowData["image"]).'" style="width:100%" />&nbsp;</center></div><div class="col-sm-6"><a href="javascript:void(0);" onclick="deleteImage();"><img src="'.asset('public/adminsystem/images/delete-icon.png').'"/></a></div>';                        
        $strImage       =   @$arrRowData["image"];
    }        
}   
$inputPictureHidden     =   '<input type="hidden" name="image_hidden"   value="'.@$strImage.'" />';
?>
<div class="portlet light bordered">
    <div class="portlet-title">
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
            <?php 
            echo $inputPictureHidden;             
            echo $inputID;
            echo $inputAliasMenu;
            ?>                
            <div class="form-body">                
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Sản phẩm</b></label>
                        <div class="col-md-10">
                            <?php echo $inputFullName; ?>
                            <span class="help-block"></span>
                        </div>
                    </div> 
                </div>
                <div class="row">  
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Alias</b></label>
                        <div class="col-md-10">
                            <?php echo $inputAlias; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>                      
                <div class="row"> 
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Giá</b></label>
                        <div class="col-md-10">
                            <?php echo $inputPrice; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>      
                </div>    
                <div class="row"> 
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Giá khuyến mãi</b></label>
                        <div class="col-md-10">
                            <?php echo $inputSalePrice; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>      
                </div>    
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Loại sản phẩm</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlCategoryProduct; ?>
                            <span class="help-block"></span>
                        </div>
                    </div> 
                </div>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Thuộc tính</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlCategoryParam; ?>
                            <span class="help-block"></span>
                        </div>
                    </div> 
                </div>                
                <div class="row">                      
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Hình</b></label>
                        <div class="col-md-4">
                            <input type="file"  name="image"  />   
                            <div class="picture-area"><?php echo $picture; ?>                      </div>
                            <div class="clr"></div>
                                                
                        </div>
                        <div class="col-md-6">
                            <div>
                                <a href="javascript:void(0);" onclick="addRow();" class="btn btn-sm green"> Thêm mới
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <table class="table-image" id="table-image" border="0" cellpadding="0" cellspacing="0" border="1" width="100%">
                                <thead>
                                    <tr>                                    
                                        <th><center>Thumbnails</center></th>                                  
                                        <th width="1%" ></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($arrRowData) > 0){
                                        $arrProductChildImage=json_decode(@$arrRowData['child_image']);    
                                        if(count($arrProductChildImage) > 0){
                                            foreach ($arrProductChildImage as $key => $value) {
                                                $featuredImg=url("/upload/" . $product_width . "x" . $product_height . "-".@$value);
                                               ?>
                                               <tr>
                                                   <td align="center" valign="middle">
                                                    <img src="<?php echo $featuredImg; ?>" width="<?php echo ((int)$product_width/6); ?>" />
                                                    <input type="hidden" name="product_child_image_hidden" value="<?php echo $value; ?>" />
                                                   </td>
                                                   <td align="center" valign="middle">
                                                       <a href="javascript:void(0);"  onclick="removeRow(this);"><img src="<?php echo url("/public/adminsystem/images/delete-icon.png"); ?>" /></a>
                                                   </td>
                                               </tr>
                                               <?php
                                            }                                            
                                        }    
                                    }   
                                    ?>                                    
                                </tbody>
                            </table>    
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
                <div class="row">  
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Meta keyword</b></label>
                        <div class="col-md-10">
                            <?php echo $inputMetakeyword; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Meta description</b></label>
                        <div class="col-md-10">                            
                            <?php echo $inputMetadescription; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Giới thiệu</b></label>
                        <div class="col-md-10">                            
                            <?php echo $inputIntro; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>VideoID</b></label>
                        <div class="col-md-10">                            
                            <?php echo $inputVideoId; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Chi tiết kỹ thuật</b></label>
                        <div class="col-md-10">                            
                            <?php echo $inputTechnicalDetail; ?>
                            <span class="help-block"></span>
                            <script type="text/javascript" language="javascript">
                                CKEDITOR.replace('technical_detail',{
                                   height:300
                               });
                           </script>
                           <span class="help-block"></span>
                       </div>
                   </div>                       
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Chi tiết</b></label>
                        <div class="col-md-10">                            
                            <?php echo $inputDetail; ?>
                            <span class="help-block"></span>
                            <script type="text/javascript" language="javascript">
                                CKEDITOR.replace('detail',{
                                   height:300
                               });
                           </script>
                           <span class="help-block"></span>
                       </div>
                   </div>                       
                </div>                                                               
            </div>                   
        </form>
    </div>
</div>
<script type="text/javascript" language="javascript">
    function resetErrorStatus(){
        var id                   =   $('input[name="id"]');
        
        var fullname             =   $('input[name="fullname"]');
        var alias                =   $('input[name="alias"]');     
        var category_id  =   $('select[name="category_id"]');   
        var sort_order           =   $('input[name="sort_order"]');
        var status               =   $('select[name="status"]');
        
        
        $(fullname).closest('.form-group').removeClass("has-error");        
        $(alias).closest('.form-group').removeClass("has-error");
        $(category_id).closest('.form-group').removeClass("has-error");
        $(sort_order).closest('.form-group').removeClass("has-error");
        $(status).closest('.form-group').removeClass("has-error");        
       
        $(fullname).closest('.form-group').find('span').empty().hide();        
        $(alias).closest('.form-group').find('span').empty().hide();
        $(category_id).closest('.form-group').find('span').empty().hide();
        $(sort_order).closest('.form-group').find('span').empty().hide();
        $(status).closest('.form-group').find('span').empty().hide();        
    }
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
        var dataItem = new FormData();
        var id=$('input[name="id"]').val();                
        var fullname=$('input[name="fullname"]').val();        
        var alias=$('input[name="alias"]').val();
        var alias_menu=$('input[name="alias_menu"]').val();
        
        var meta_keyword=$('textarea[name="meta_keyword"]').val();
        var meta_description=$('textarea[name="meta_description"]').val();
        var category_id=$('select[name="category_id"]').val();  
        var category_param_id=$('select[name="category_param_id[]"]').val();      
        
        /* begin xử lý image */
        var image_file=null;
        var image_ctrl=$('input[name="image"]');         
        var image_files = $(image_ctrl).get(0).files;        
        if(image_files.length > 0){            
            image_file  = image_files[0];  
        }        
        /* end xử lý image */
        var image_hidden=$('input[name="image_hidden"]').val();
        /* begin source child image */
        var tbody=$("table.table-image > tbody")[0]; 
        if(tbody.rows.length > 0){
            for(var i=0;i<tbody.rows.length;i++){
                var row=tbody.rows[i];
                var child_image_hidden_ctrl=$(row.cells[0]).find('input[type="hidden"][name="product_child_image_hidden"]');
                var child_image_ctrl=$(row.cells[0]).find('input[type="file"][name="product_child_image_file"]');            

                if($(child_image_hidden_ctrl).length > 0){
                    var child_image_thumb=$(child_image_hidden_ctrl).val();
                    if(child_image_thumb.length > 0){
                        dataItem.append("source_image_child_hidden[]", child_image_thumb);
                    }  
                }                            
                var child_image_file=null;                  
                if($(child_image_ctrl).length > 0){
                    var child_image_files = $(child_image_ctrl).get(0).files;        
                    if(child_image_files.length > 0){            
                        child_image_file  = child_image_files[0];  
                        dataItem.append("source_image_child[]", child_image_file);
                    }
                }                            
            }
        }       
        /* end source child image */
        var status=$('select[name="status"]').val();             
        var price=$('input[name="price"]').val();
        var sale_price=$('input[name="sale_price"]').val();       
        var intro=$('textarea[name="intro"]').val(); 
        var detail=CKEDITOR.instances['detail'].getData(); 
        var technical_detail=CKEDITOR.instances['technical_detail'].getData();                
        var video_id=$('input[name="video_id"]').val();
        var sort_order=$('input[name="sort_order"]').val();        
        var token = $('input[name="_token"]').val();   
        resetErrorStatus();                
        dataItem.append('id',id);
        dataItem.append('fullname',fullname);
        dataItem.append('alias',alias);
        dataItem.append('alias_menu',alias_menu);              
        dataItem.append('meta_keyword',meta_keyword);
        dataItem.append('meta_description',meta_description);
        if(image_files.length > 0){
            dataItem.append('image',image_file);
        } 
        dataItem.append('image_hidden',image_hidden);
        dataItem.append('status',status); 
        dataItem.append('price',price);
        dataItem.append('sale_price',sale_price);
        dataItem.append('intro',intro);
        dataItem.append('detail',detail);
        dataItem.append('technical_detail',technical_detail);     
        dataItem.append('video_id',video_id);
        dataItem.append('category_id',category_id);        
        dataItem.append('category_param_id',category_param_id);        
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
                    window.location.href = "<?php echo $linkCancel; ?>";
                }else{
                    var data_error=data.error;                    
                    if(typeof data_error.fullname               != "undefined"){
                        $('input[name="fullname"]').closest('.form-group').addClass(data_error.fullname.type_msg);
                        $('input[name="fullname"]').closest('.form-group').find('span').text(data_error.fullname.msg);
                        $('input[name="fullname"]').closest('.form-group').find('span').show();                        
                    }                    
                    if(typeof data_error.alias                  != "undefined"){
                        $('input[name="alias"]').closest('.form-group').addClass(data_error.alias.type_msg);
                        $('input[name="alias"]').closest('.form-group').find('span').text(data_error.alias.msg);
                        $('input[name="alias"]').closest('.form-group').find('span').show();                       
                    }
                    if(typeof data_error.category_id               != "undefined"){
                        $('select[name="category_id"]').closest('.form-group').addClass(data_error.category_id.type_msg);
                        $('select[name="category_id"]').closest('.form-group').find('span').text(data_error.category_id.msg);
                        $('select[name="category_id"]').closest('.form-group').find('span').show();                        
                    }
                    if(typeof data_error.sort_order               != "undefined"){
                        $('input[name="sort_order"]').closest('.form-group').addClass(data_error.sort_order.type_msg);
                        $('input[name="sort_order"]').closest('.form-group').find('span').text(data_error.sort_order.msg);
                        $('input[name="sort_order"]').closest('.form-group').find('span').show();                        
                    }
                    if(typeof data_error.status               != "undefined"){
                        $('select[name="status"]').closest('.form-group').addClass(data_error.status.type_msg);
                        $('select[name="status"]').closest('.form-group').find('span').text(data_error.status.msg);
                        $('select[name="status"]').closest('.form-group').find('span').show();

                    }                    
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
    function addRow() {
    var tbody=$("table.table-image > tbody")[0];
    var row=tbody.insertRow(tbody.length);
    var cell0 = row.insertCell(0);
    var cell1 = row.insertCell(1);
    cell0.innerHTML = '<input type="file" name="product_child_image_file">';
    cell1.innerHTML = '<a href="javascript:void(0);"  onclick="removeRow(this);"><img src="<?php echo url("/public/adminsystem/images/delete-icon.png"); ?>" />';         
    }
    function removeRow(control) {
        var tbody=$(control).closest("tbody")[0];
        var tr=$(control).closest("tr")[0];
        var index = $(tr).index();         
        tbody.deleteRow(index); 
    }
    function createAlias(){
        var id=$('input[name="id"]').val();   
        var fullname    = $('input[name="fullname"]').val();
        var token       = $('form[name="frm"] > input[name="_token"]').val();     
        var dataItem={      
            "id":id,      
            "fullname":fullname,            
            "_token": token
        };   
        $('input[name="alias"]').val(''); 
        resetErrorStatus();    
        $.ajax({
            url: '<?php echo $linkCreateAlias; ?>',
            type: 'POST',
            data: dataItem,            
            async: false,
            success: function (data) {                
                if(data.checked==1){
                    $('input[name="alias"]').val(data.alias); 
                }else{                    
                    var data_error=data.error;
                    if(typeof data_error.fullname               != "undefined"){
                        $('input[name="fullname"]').closest('.form-group').addClass(data_error.fullname.type_msg);
                        $('input[name="fullname"]').closest('.form-group').find('span').text(data_error.fullname.msg);
                        $('input[name="fullname"]').closest('.form-group').find('span').show();                        
                    }                            
                }
                spinner.hide();
            },
            error : function (data){
                spinner.hide();
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });
    }
</script>
@endsection()            