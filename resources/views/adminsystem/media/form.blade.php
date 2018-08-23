@extends("adminsystem.master")
@section("content")
<?php 
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');
?>
<form class="form-horizontal" method="post" action="{!! $linkSave !!}" role="form" enctype="multipart/form-data">
    {{ csrf_field() }}           
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
    function save(){
        var dataItem = new FormData();
        var token = $('input[name="_token"]').val();   
        var tbody=$("table.setting-system > tbody")[0];
        var rows=tbody.rows;
        if(rows.length > 0){
            for(var i=0;i<rows.length;i++){
                var media_ctrl=$(rows[i].cells[0]).find('input[type="file"][name="media_file"]');            
                var media_file=null;                  
                if($(media_ctrl).length > 0){
                    var media_files = $(media_ctrl).get(0).files;        
                    if(media_files.length > 0){            
                        media_file  = media_files[0];  
                        dataItem.append("source_media_file[]", media_file);
                    }
                }           
            }
        }   
        dataItem.append('_token',token);            
        $.ajax({
            url: '<?php echo $linkSave; ?>',
            type: 'POST',
            data: dataItem,
            async: false,
            success: function (data) {                                
                window.location.href = "<?php echo $linkCancel; ?>";
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