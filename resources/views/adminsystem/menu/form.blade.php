@extends("adminsystem.master")
@section("content")
<?php 
$linkNew                =   route('adminsystem.'.$controller.'.getComponentForm',[@$menu_type_id]);
$linkCancel             =   route('adminsystem.'.$controller.'.getList',[@$menu_type_id]);
$linkSave               =   route('adminsystem.'.$controller.'.save');
$inputFullName          =   '<input type="text" class="form-control" name="fullname"       value="'.@$fullname.'">'; 
$inputAlias             =   '';
if(strcmp(@$alias, 'no-alias')==0){
    switch ($task) {
        case 'add':
        $inputAlias             =   '<input type="text" class="form-control" name="alias"          value="">';

        break;
        
        case 'edit':
        $inputAlias             =   '<input type="text" class="form-control" name="alias"       value="'.@$arrRowData['alias'].'">';        
        break;
    }    
}else{
    $inputAlias             =   '<input type="text" class="form-control" name="alias"          value="'.@$alias.'">';
    
}
$inputMenuClass         =   '<input type="text" class="form-control" name="menu_class"      value="'.@$arrRowData['menu_class'].'">';
$inputSortOrder         =   '<input type="text" class="form-control" name="sort_order"   value="'.@$arrRowData['sort_order'].'">';

$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");
$parent_id              =   (count($arrRowData) > 0) ? @$arrRowData['parent_id'] : null ; 
$ddlMenu                =   cmsSelectboxCategory('parent_id', 'form-control',$arrMenuRecursive,@$arrRowData['parent_id'],"",'Chọn danh mục');
$ddlMenuType            =   cmsSelectboxCategory('menu_type_id', 'form-control',$arrMenuType,@$menu_type_id,"disabled",'Chọn danh mục');
$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id"  value="'.@$id.'" />'; 
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
                    <a href="<?php echo $linkNew; ?>" class="btn yellow">Thêm mới <i class="fa fa-plus"></i></a> 
                    <a href="<?php echo $linkCancel; ?>" class="btn green">Thoát <i class="fa fa-ban"></i></a>                    </div>                                                
                </div>
            </div>    
        </div>
    </div>
    <div class="portlet-body form">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" name="frm">
            {{ csrf_field() }}                                                           
                <?php echo  $inputID; ?>   
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Menu</b></label>
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
                        <label class="col-md-2 control-label"><b>Menu cha</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlMenu; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Loại menu</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlMenuType; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>    
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Menu class</b></label>
                        <div class="col-md-10">
                            <?php echo $inputMenuClass; ?>
                            <span class="help-block"></span>
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
    function save(){
        var id=$('input[name="id"]').val();        
        var fullname=$('input[name="fullname"]').val();
        var alias=$('input[name="alias"]').val();
        
        var parent_id=$('select[name="parent_id"]').val();        
        var menu_type_id=$('select[name="menu_type_id"]').val();  
        var menu_class=$('input[name="menu_class"]').val();
        var sort_order=$('input[name="sort_order"]').val();
        var status=$('select[name="status"]').val();     
        var token = $('input[name="_token"]').val();           
        var dataItem={
            "id":id,
            "fullname":fullname,
            "alias":alias,
            
            "parent_id":parent_id,
            "menu_type_id":menu_type_id,
            "menu_class":menu_class,
            "sort_order":sort_order,
            "status":status,
            "_token": token
        };
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
        });
    }    
</script>
@endsection()            