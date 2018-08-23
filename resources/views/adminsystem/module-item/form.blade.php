@extends("adminsystem.master")
@section("content")
<?php 
$linkLoadDataArticle    =   route('adminsystem.'.$controller.'.loadDataArticle');
$linkLoadDataProduct    =   route('adminsystem.'.$controller.'.loadDataProduct');
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');
$linkInsertArticle      =   route('adminsystem.'.$controller.'.insertArticle');
$linkInsertProduct      =   route('adminsystem.'.$controller.'.insertProduct');
$linkSortItems          =   route('adminsystem.'.$controller.'.sortItems');
$linkGetItems           =   route('adminsystem.'.$controller.'.getItems');
$inputFullName          =   '<input type="text" class="form-control" name="fullname"     value="'.@$arrRowData['fullname'].'">'; 
$ddlCategoryArticle     =   cmsSelectboxCategory('category_id', 'form-control', $arrCategoryArticleRecursive, 0,"",'Chọn danh mục');
$ddlCategoryProduct     =   cmsSelectboxCategory('category_product_id', 'form-control', $arrCategoryProductRecursive, 0,"",'Chọn danh mục');
$inputPosition          =   '<input type="text" class="form-control" name="position"      value="'.@$arrRowData['position'].'">'; 
$inputComponent         =   '<input type="hidden" class="form-control" name="component"      value="'.@$arrRowData['component'].'">'; 
$data                   =   array();
if(count(@$arrRowData) > 0){
    if(!empty(@$arrRowData)){
        $data                   =   json_decode(@$arrRowData['setting']);
        $data = convertToArray($data);
    }
}

$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");
$inputSortOrder         =   '<input type="text" class="form-control" name="main_sort_order"     value="'.@$arrRowData['sort_order'].'">';
$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id" value="'.@$id.'" />'; 
$inputSortJson          =   '<input type="hidden" name="sort_json" id=value=\''.@$arrRowData['item_id'].'\' />';
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
        <form class="form-horizontal" role="form" name="frm" enctype="multipart/form-data" >
            {{ csrf_field() }}         
            <?php echo $inputSortJson; ?>
            <?php echo $inputComponent; ?>                           
            <?php echo  $inputID; ?>        
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Tên module</b></label>
                        <div class="col-md-10">
                            <?php echo $inputFullName; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="row">   
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Vị trí trong theme</b></label>
                        <div class="col-md-10">
                            <?php echo $inputPosition; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>      
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>Lấy dữ liệu từ</b></label>
                        <div class="col-md-10">
                            <button type="button" class="btn dark btn-outline sbold uppercase btn-article" data-toggle="modal" data-target="#modal-article">BÀI VIẾT</button>
                            <button type="button" class="btn dark btn-outline sbold uppercase btn-product" data-toggle="modal" data-target="#modal-product">SẢN PHẨM</button>                            
                        </div>
                    </div>   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-2 control-label"><b>List</b></label>
                        <div class="col-md-10 list">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="tbl-item">
                                <thead>
                                    <tr>
                                        <th width="1%"><input type="checkbox" onclick="checkAllAgent(this)"  name="checkall-toggle"></th>                             
                                        <th>Bài viết - Sản phẩm</th>   
                                        <th width="1%">Hình</th>                                                
                                        <th width="1%">Sắp xếp</th>                                                                                
                                        <th width="1%">Xóa</th>                
                                    </tr>
                                </thead>
                                <tbody>                                                
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
                                    <th width="20%">Tên field</th>
                                    <th width="20%">Mã field</th>                                    
                                    <th>Giá trị</th>
                                    <th width="1%"></th>                                
                                </tr>
                            </thead>                            
                            <tbody>
                                <?php 
                                if(count($data) > 0){
                                    for($i=0;$i<count($data);$i++){
                                        $field_name=$data[$i]['field_name'];
                                        $field_code=$data[$i]['field_code'];
                                        $field_value=$data[$i]['field_value'];
                                        ?>
                                        <tr>
                                            <td><input type="text" name="field_name" value="<?php echo $field_name; ?>" class="form-control"></td>
                                            <td><input type="text" name="field_code" value="<?php echo $field_code; ?>" class="form-control"></td>                                    
                                            <td><input type="text" name="field_value" value="<?php echo $field_value; ?>" class="form-control"></td>
                                            <td ><center><a href="javascript:void(0);" onclick="removeRow(this);"><img src="<?php echo asset('public/adminsystem/images/delete-icon.png'); ?>"  /></a></center></td>                         
                                        </tr>
                                        <?php                                        
                                    }
                                }
                                ?>                                
                            </tbody>                            
                        </table>
                    </div> 
                </div>                                                                  
            </div>                      
        </form>
    </div>
</div>
<div class="modal fade category-modal" id="modal-article" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">  
                <b>BÀI VIẾT      </b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
            </div>
            <div class="modal-body">
                <form name="frm-article">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <div><b>CHỦ ĐỀ</b>  </div>
                            <div><?php echo $ddlCategoryArticle ; ?></div>
                        </div>            
                        <div class="col-md-4">
                            <div><b>TÊN BÀI VIẾT</b>  </div>
                            <div><input type="text" class="form-control" name="filter_search"          value=""></div>
                        </div>            
                        <div class="col-md-4">
                            <div>&nbsp;</div>
                            <div>
                                <button type="button" class="btn dark btn-outline sbold uppercase" onclick="getListArticle();">Tìm kiếm</button>
                                &nbsp;  
                                <button type="button" class="btn dark btn-outline sbold uppercase" onclick="insertArticle();">Thêm</button>                      
                            </div>                
                        </div>                
                    </div>   
                    <div class="row margin-top-15">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="tbl-article-module-item">
                                <thead>
                                    <tr>
                                        <th width="1%"><input type="checkbox" onclick="checkAllAgentArticle(this)"  name="checkall-toggle"></th>                
                                        
                                        <th>Bài viết</th>
                                        <th>Chủ đề</th>
                                        <th width="1%">Hình</th>                                
                                    </tr>
                                </thead>
                                <tbody>                                                
                                </tbody>
                            </table>
                        </div>            
                    </div>     
                </form>        
            </div>      
        </div>
    </div>
</div>
<div class="modal fade category-modal" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">  
                <b>SẢN PHẨM      </b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
            </div>
            <div class="modal-body">
                <form name="frm-product">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <div><b>LOẠI SẢN PHẨM</b>  </div>
                            <div><?php echo $ddlCategoryProduct ; ?></div>
                        </div>            
                        <div class="col-md-4">
                            <div><b>SẢN PHẨM</b>  </div>
                            <div><input type="text" class="form-control" name="filter_search"          value=""></div>
                        </div>            
                        <div class="col-md-4">
                            <div>&nbsp;</div>
                            <div>
                                <button type="button" class="btn dark btn-outline sbold uppercase" onclick="getListProduct();">Tìm kiếm</button>
                                &nbsp;  
                                <button type="button" class="btn dark btn-outline sbold uppercase" onclick="insertProduct();">Thêm</button>
                            </div>                
                        </div>
                    </div>   
                    <div class="row margin-top-15">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="tbl-product-module-item">
                                <thead>
                                    <tr>
                                        <th width="1%"><input type="checkbox" onclick="checkAllAgentProduct(this)"  name="checkall-toggle"></th>                                                
                                        <th>Sản phẩm</th>
                                        <th>Nhóm</th>
                                        <th width="1%">Hình</th>                                
                                        
                                    </tr>
                                </thead>
                                <tbody>                                                
                                </tbody>
                            </table>
                        </div>            
                    </div>     
                </form>        
            </div>      
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
    function checkWithList(this_checkbox){
        var dr = vItemTable.row( $(this_checkbox).closest('tr') ).data();               
        if(parseInt(dr['is_checked']) == 0){
            dr['checked'] ='<input type="checkbox" checked onclick="checkWithList(this)" name="cid" />';
            dr['is_checked'] = 1;
        }else{
            dr['checked'] ='<input type="checkbox" onclick="checkWithList(this)" name="cid" />';
            dr['is_checked'] = 0;
        }
        vItemTable.row( $(this_checkbox).closest('tr') ).data(dr);
    }   
    function checkWithListArticle(this_checkbox){
        var dr = vArticleModuleItemTable.row( $(this_checkbox).closest('tr') ).data();            
        if(parseInt(dr['is_checked']) == 0){
            dr['checked'] ='<input type="checkbox" checked onclick="checkWithListArticle(this)" name="cid" />';
            dr['is_checked'] = 1;
        }else{
            dr['checked'] ='<input type="checkbox" onclick="checkWithListArticle(this)" name="cid" />';
            dr['is_checked'] = 0;
        }
        vArticleModuleItemTable.row( $(this_checkbox).closest('tr') ).data(dr);
    }       
    function checkWithListProduct(this_checkbox){
        var dr = vProductModuleItemTable.row( $(this_checkbox).closest('tr') ).data();            
        if(parseInt(dr['is_checked']) == 0){
            dr['checked'] ='<input type="checkbox" checked onclick="checkWithListProduct(this)" name="cid" />';
            dr['is_checked'] = 1;
        }else{
            dr['checked'] ='<input type="checkbox" onclick="checkWithListProduct(this)" name="cid" />';
            dr['is_checked'] = 0;
        }
        vProductModuleItemTable.row( $(this_checkbox).closest('tr') ).data(dr);
    }       
    function getListArticle() {    
        var token = $('form[name="frm-article"] > input[name="_token"]').val(); 
        var category_id=$('form[name="frm-article"] select[name="category_id"]').val();
        var filter_search=$('form[name="frm-article"] input[name="filter_search"]').val();
        var dataItem={            
            '_token': token,
            'filter_search':filter_search,
            'category_id':category_id            
        };
        $.ajax({
            url: '<?php echo $linkLoadDataArticle; ?>',
            type: 'POST', 
            data: dataItem,
            success: function (data, status, jqXHR) {                  
                vArticleModuleItemTable.clear().draw();
                vArticleModuleItemTable.rows.add(data).draw();
                spinner.hide();
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });
    }      
    function getListProduct() {    
        var token = $('form[name="frm-product"] > input[name="_token"]').val(); 
        var category_product_id=$('form[name="frm-product"] select[name="category_product_id"]').val();
        var filter_search=$('form[name="frm-product"] input[name="filter_search"]').val();
        var dataItem={            
            '_token': token,
            'filter_search':filter_search,
            'category_product_id':category_product_id        
        };
        $.ajax({
            url: '<?php echo $linkLoadDataProduct; ?>',
            type: 'POST', 
            data: dataItem,
            success: function (data, status, jqXHR) {                                  
                vProductModuleItemTable.clear().draw();
                vProductModuleItemTable.rows.add(data).draw();
                spinner.hide();
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });
    }     
    
    function insertArticle(){
        var dt      =   vArticleModuleItemTable.data();        
        var str_id  =   "";     
        for(var i=0;i<dt.length;i++){
            var dr=dt[i];
            if(dr.is_checked==1){
                str_id +=dr.id+",";	    
            }
        }
        
        if(str_id == ''){
            alert('Vui lòng chọn ít nhất một phần tử');    
        }else{
            var token = $('form[name="frm-article"] > input[name="_token"]').val(); 
            var dataItem ={   
                'str_id':str_id,    
                '_token': token
            };      
            $.ajax({
                url: '<?php echo $linkInsertArticle; ?>',
                type: 'POST',                        
                data: dataItem,
                success: function (data, status, jqXHR) {  
                    var list=new Array(data.length);
                    for(var k=0;k<data.length;k++){
                        var sort_order=parseInt($(data[k].sort_order).find('input[name="sort_order"]').val());
                        var doituong={
                            id:data[k].id,
                            sort_order:sort_order
                        };
                        list[k]=doituong;
                    }
                    $('form[name="frm"] > input[name="sort_json"]').val(JSON.stringify(list));
                    var dataItemTable=vItemTable.data();
                    if(dataItemTable.length > 0){
                        var result=1;   
                        for(var j=0;j<data.length;j++){                                                                             
                            for(var k=0;k<dataItemTable.length;k++){                                                                
                                if(parseInt(data[j].id) == parseInt(dataItemTable[k]['id'])){
                                    result=0;                                       
                                }                    
                            }                            
                        }
                        if(result==1){                                
                            vItemTable.rows.add(data).draw();
                        }else{
                            alert('Danh mục đã tồn tại');
                        }
                    }else{
                        vItemTable.rows.add(data).draw();
                    }
                    spinner.hide();
                    $('input[name="component"]').val('article');
                    $('#modal-article').modal('hide');                  
                },
                beforeSend  : function(jqXHR,setting){
                    spinner.show();
                },
            });
        }             
    }
    function insertProduct(){
        var dt      =   vProductModuleItemTable.data();        
        var str_id  =   "";     
        for(var i=0;i<dt.length;i++){
            var dr=dt[i];
            if(dr.is_checked==1){
                str_id +=dr.id+",";	
            }
        }        
        if(str_id == ''){
            alert('Vui lòng chọn ít nhất một phần tử');    
        }else{
            var token = $('form[name="frm-product"] > input[name="_token"]').val(); 
            var dataItem ={   
                'str_id':str_id,    
                '_token': token
            };      
            $.ajax({
                url: '<?php echo $linkInsertProduct; ?>',
                type: 'POST',                        
                data: dataItem,
                success: function (data, status, jqXHR) { 
                    var list=new Array(data.length);
                    for(var k=0;k<data.length;k++){
                        var sort_order=parseInt($(data[k].sort_order).find('input[name="sort_order"]').val());
                        var doituong={
                            id:data[k].id,
                            sort_order:sort_order
                        };
                        list[k]=doituong;
                    }
                    $('form[name="frm"] > input[name="sort_json"]').val(JSON.stringify(list));
                    var dataItemTable=vItemTable.data();
                    if(dataItemTable.length > 0){
                        var result=1;   
                        for(var j=0;j<data.length;j++){                                                                             
                            for(var k=0;k<dataItemTable.length;k++){                                                                
                                if(parseInt(data[j].id) == parseInt(dataItemTable[k]['id'])){
                                    result=0;                                       
                                }                    
                            }                            
                        }
                        if(result==1){                                
                            vItemTable.rows.add(data).draw();
                        }else{
                            alert('Danh mục đã tồn tại');
                        }
                    }else{
                        vItemTable.rows.add(data).draw();
                    }
                    spinner.hide();
                    $('input[name="component"]').val('product');
                    $('#modal-product').modal('hide');                  
                },
                beforeSend  : function(jqXHR,setting){
                    spinner.show();
                },
            });
        }             
    }
    
    function save(){
        var id=$('input[name="id"]').val();        
        var fullname=$('input[name="fullname"]').val();
        var item_id=$('input[name="sort_json"]').val();

        var menu_id=$('input[name="menu_id"]').val();
        var position=$('input[name="position"]').val();          
        var component=$('input[name="component"]').val();
        var status=$('select[name="status"]').val();        
        var sort_order=$('input[name="main_sort_order"]').val();        

        var tbody=$(".setting-system > tbody")[0];
        var rows=tbody.rows;
        var setting=new Array(rows.length);
        for(var i=0 ; i < rows.length ; i++){
            var field_name=$(rows[i].cells[0]).find('input[name="field_name"]').val();
            var field_code=$(rows[i].cells[1]).find('input[name="field_code"]').val();
            var field_value=$(rows[i].cells[2]).find('input[name="field_value"]').val();
            var row={
                'field_name':field_name,
                'field_code':field_code,
                'field_value':field_value
            };
            setting[i]=row;
        }        
        var token = $('form[name="frm"] > input[name="_token"]').val(); 
        
        var dataItem={
            "id":id,
            "fullname":fullname,
            "setting":JSON.stringify(setting),
            "item_id":item_id,
            "menu_id":menu_id,
            "position":position,    
            "component":component,
            "status":status,        
            "sort_order":sort_order,            
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
    function sort(){
        var tbody=$('div.list > div.dataTables_wrapper > div.table-scrollable > table > tbody');        
        var rows=tbody[0].rows;
        var classname= $(rows[0].cells[0]).attr('class');        
        if(classname == 'dataTables_empty'){
            alert('Vui lòng chọn ít nhất một phần tử');
            return false;
        }
        var data=new Array(rows.length);
        for(var i=0;i<rows.length;i++){
            var row=rows[i];
            var cell_sort_order=row.cells[3];
            var input_sort_order=$(cell_sort_order).find('input[name="sort_order"]');
            var id=parseInt($(input_sort_order).attr('sort_order_id')) ;
            var sort_order_text=$(input_sort_order).val();
            var fullname=$(row.cells[1]).text();
            var image=$(row.cells[2]).html();
            var checked=$(row.cells[0]).html();
            var deleted=$(row.cells[4]).html();
            var sort_order=$(row.cells[3]).html();
            var item={
                'checked':checked,
                'is_checked':0,
                'id':id,
                'fullname':fullname,
                'image':image,
                'sort_order':sort_order,
                'sort_order_text':sort_order_text,
                'deleted':deleted
            };                        
            data[i]=item;
        }
        var data_sort=JSON.stringify(data);
        var token = $('form[name="frm"] > input[name="_token"]').val(); 
        var dataItem={
            'data_sort' : data_sort,
            "_token": token
        };
        $.ajax({
            url: '<?php echo $linkSortItems; ?>',
            type: 'POST',                        
            data: dataItem,
            success: function (data, status, jqXHR) {           
                vItemTable.clear().draw();
                vItemTable.rows.add(data.data_2).draw();                
                $('form[name="frm"] > input[name="sort_json"]').empty();
                $('form[name="frm"] > input[name="sort_json"]').val(JSON.stringify(data.data_1));
                spinner.hide();                
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });  
    }    
    function deleteItem(ctrl){
        var xac_nhan = 0;
        var msg="Bạn có muốn xóa ?";
        if(window.confirm(msg)){ 
            xac_nhan = 1;
        }
        if(xac_nhan  == 0){
            return false;   
        }
        var tr=$(ctrl).closest('tr');
        var sort_order_input=$(tr[0].cells[3]).find('input[name="sort_order"]');
        var sort_order_id=$(sort_order_input).attr('sort_order_id');
        var data = $.parseJSON($('form[name="frm"] > input[name="sort_json"]').val());        
        var index=0;
        for(var i=0;i<data.length;i++){
            if( parseInt(data[i].id) == parseInt(sort_order_id) ){
                index=i;
            }
        }
        data.splice(index,1);
        $('form[name="frm"] > input[name="sort_json"]').val(JSON.stringify(data));
        vItemTable.row(tr).remove().draw();        
    }
    function getItems(){
        var id          =   $('form[name="frm"] > input[name="id"]').val();        
        var token       =   $('form[name="frm"] > input[name="_token"]').val(); 
        var dataItem={
            'id' : id,            
            '_token': token
        };
        $.ajax({
            url: '<?php echo $linkGetItems; ?>',
            type: 'POST',                        
            data: dataItem,
            success: function (data, status, jqXHR) {  
                if(data.length > 0){
                    vItemTable.clear().draw();
                    vItemTable.rows.add(data).draw();                                
                }                                 
                spinner.hide();                
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });  
    }    
    function addRow() {
        var tbody=$("table.setting-system > tbody")[0];
        var row=tbody.insertRow(tbody.length);
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var cell3 = row.insertCell(3);

        cell0.innerHTML = '<input type="text" name="field_name" value="" class="form-control">';
        cell1.innerHTML = '<input type="text" name="field_code" value="" class="form-control">';
        cell2.innerHTML = '<input type="text" name="field_value" value="" class="form-control">';
        cell3.innerHTML = '<a href="javascript:void(0);"  onclick="removeRow(this);"><img src="<?php echo url("/public/adminsystem/images/delete-icon.png"); ?>" />'
    }
    function removeRow(control) {
        var tbody=$(control).closest("tbody")[0];
        var tr=$(control).closest("tr")[0];
        var index = $(tr).index(); 
        tbody.deleteRow(index);                      
    }        
    $(document).ready(function(){
        vItemTable.clear().draw();
        var sort_button='<div class="sort-button"><a href="javascript:void(0)" onclick="sort();" class="btn dark btn-outline sbold uppercase">Sort <i class="fa fa-sort"></i></a></div>';        
        $('div.list > div.dataTables_wrapper > div:first-child > div:nth-child(2)').append(sort_button);        
        getListArticle();
        getListProduct();        
        getItems();        
    });
</script>
@endsection()            