@extends("adminsystem.master")
@section("content")
<?php 

$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');

$inputCode              =   '<input type="text" class="form-control" name="code"  disabled              value="'.@$arrRowData['code'].'">';
$inputUsername          =   '<input type="text" class="form-control" name="username"  disabled              value="'.@$arrRowData['username'].'">';
$inputEmail             =   '<input type="text" class="form-control" name="email"      disabled                value="'.@$arrRowData['email'].'">';
$inputFullName          =   '<input type="text" class="form-control" name="fullname"                value="'.@$arrRowData['fullname'].'">';  
$inputAddress           =   '<input type="text" class="form-control" name="address"                  value="'.@$arrRowData['address'].'">'; 
$inputPhone             =   '<input type="text" class="form-control" name="phone"                      value="'.@$arrRowData['phone'].'">';  
$inputNote              =   '<textarea  name="note" rows="2" cols="100" class="form-control" >'.@$arrRowData['note'].'</textarea>'; 

$lblQuantity            =   number_format((int)@$arrRowData['quantity'],0,".",",");
$lblTotalPrice          =   number_format((int)@$arrRowData['total_price'],0,".",",");



$arrStatus              =   array(-1 => '- Select status -', 1 => 'Publish', 0 => 'Unpublish');  
$ddlStatus              =   cmsSelectbox("status","form-control",$arrStatus,(int)@$arrRowData['status'],"");

$id                     =   (count($arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id" value="'.@$id.'" />'; 
$setting= getSettingSystem();
$product_width = $setting['product_width']['field_value'];
$product_height = $setting['product_height']['field_value'];
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
                    <button onclick="save()" class="btn purple">Save new <i class="fa fa-floppy-o"></i></button> 
                    <a href="<?php echo $linkCancel; ?>" class="btn green">Cancel <i class="fa fa-ban"></i></a></div>                                                
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
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Tên khách hàng</b></label>
                        <div class="col-md-9">
                            <?php echo $inputFullName; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Email</b></label>
                        <div class="col-md-9">
                            <?php echo $inputEmail; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>      
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Phone</b></label>
                        <div class="col-md-9">
                            <?php echo $inputPhone; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>  
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Địa chỉ</b></label>
                        <div class="col-md-9">
                            <?php echo $inputAddress; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>      
                </div>       
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Mã đơn hàng</b></label>
                        <div class="col-md-9">
                            <?php echo $inputCode; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Trạng thái giao hàng</b></label>
                        <div class="col-md-9">                            
                            <?php echo $ddlStatus; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>   
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-md-3 control-label"><b>Ghi chú</b></label>
                        <div class="col-md-9">                            
                            <?php echo $inputNote; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>   
                    <div class="form-group col-md-6">
                        
                    </div>     
                </div>                                                
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%" class="com_product16">
                            <thead>
                                <tr>
                                    <th align="center"><center>Mã sản phẩm</center></th>
                                    <th align="center"><center>Tên sản phẩm</center></th>
                                    <th align="center"><center>Hình</center></th>
                                    <th align="center"><center>Giá</center></th>
                                    <th align="center"><center>Số lượng</center></th>
                                    <th align="center"><center>Thành tiền</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($arrInvoiceDetail as $key => $value) {
                                    $product_code=$value["product_code"];
                                    $product_name=$value["product_name"];
                                    $product_image=asset('/upload/'.$product_width . 'x'.$product_height.'-'.$value["product_image"]) ;                
                                    $product_price=fnPrice($value["product_price"]);
                                    $product_quantity=$value["product_quantity"];
                                    $product_total_price=fnPrice($value["product_total_price"]);
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $product_code; ?></td>
                                        <td><?php echo $product_name; ?></td>
                                        <td width="10%" align="center"><img style="width:100%" src="<?php echo $product_image; ?>" /></td>
                                        <td align="right"><?php echo $product_price; ?></td>
                                        <td width="10%" align="center"><b><center><?php echo $product_quantity; ?></center></b> </td>
                                        <td width="15%" align="right"><b><?php echo $product_total_price; ?></b></td>
                                    </tr>
                                    <?php
                                } 
                                ?>              
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" align="center"><b>Tổng cộng</b></td>
                                    <td align="center"><b><?php echo (@$arrRowData["quantity"]); ?></b></td>
                                    <td align="right"><b><?php echo fnPrice(@$arrRowData["total_price"]); ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
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
        var address=$('input[name="address"]').val();
        var phone=$('input[name="phone"]').val();                
        var status=$('select[name="status"]').val();     
        var token = $('input[name="_token"]').val();   
        
        var dataItem={
            "id":id,        
            "fullname":fullname,
            "address":address,
            "phone":phone,


            
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