@extends("adminsystem.master")
@section("content")
<?php 
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');
$ddlAlbum               =   cmsSelectboxCategory("album_id","form-control",$arrAlbum,@$arrRowData['album_id'],"",'Chọn danh mục');
?>
<form class="form-horizontal" method="post" action="{!! $linkSave !!}" role="form" enctype="multipart/form-data">
    {{ csrf_field() }}           
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
                        <a class="btn purple" href="javascript:void(0);" onclick="save();">Lưu</a>
                        <a href="<?php echo $linkCancel; ?>" class="btn green">Cancel <i class="fa fa-ban"></i></a>                    </div>                                                
                    </div>
                </div>    
            </div>
        </div>
        <div class="portlet-body form">        
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Chủ đề</b></label>
                        <div class="col-md-10">
                            <?php echo $ddlAlbum; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                    
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="javascript:void(0);" onclick="addRow();" class="btn btn-sm green"> Thêm mới
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>                
                <div class="row">
                    <div class="form-group col-md-12">
                        <table class="table table-bordered table-recursive setting-system">
                            <thead>
                                <tr>
                                    <th width="20%">File</th>                                    
                                    <th width="1%"></th>                                
                                </tr>
                            </thead>  
                            <tbody></tbody>                                                                       
                        </table>
                    </div> 
                </div>     
            </div>            
        </div>
    </div>
</form>
<script type="text/javascript" language="javascript">
    function resetErrorStatus(){
        var id                   =   $('input[name="id"]');
        var album_id  =   $('select[name="album_id"]');        
        $(album_id).closest('.form-group').removeClass("has-error");
        $(album_id).closest('.form-group').find('span').empty().hide();
    }    
    function save(){        
        var token = $('input[name="_token"]').val();   
        var album_id=$('select[name="album_id"]').val();   
        var tbody=$("table.setting-system > tbody")[0];
        var rows=tbody.rows;
        for(var i=0;i<rows.length;i++){            
            var ctrl_media=$(rows[i].cells[0]).find('input[type="file"][name="media_file"]');    
            var file_upload=$(ctrl_media).get(0);        
            var files = file_upload.files;
            var file  = files[0];    
            var frmdata = new FormData();        
            frmdata.append("image", file);
            frmdata.append("album_id",album_id);
            frmdata.append("_token", token);            
            $.ajax({
            url: '<?php echo $linkSave; ?>',
            type: 'POST',
            data: frmdata,
            async: false,
            contentType: false, 
            processData: false,
            success: function (data) {
                alert(data.msg.success);      
            },
            error : function (data){
                
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });
        }
        window.location.href = "<?php echo $linkCancel; ?>";
    }
    function addRow() {
        var tbody=$("table.setting-system > tbody")[0];
        var row=tbody.insertRow(tbody.length);
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        cell0.innerHTML = '<input type="file" name="media_file">';        
        cell1.innerHTML = '<a href="javascript:void(0);"  onclick="removeRow(this);"><img src="<?php echo url("/public/adminsystem/images/delete-icon.png"); ?>" />'
    }
    function removeRow(control) {
        var tbody=$(control).closest("tbody")[0];
        var tr=$(control).closest("tr")[0];
        var index = $(tr).index(); 
        tbody.deleteRow(index);                      
    }        
</script>
@endsection()            