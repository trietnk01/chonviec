@extends("adminsystem.master")
@section("content")
<?php 
$linkNew			=	route('adminsystem.'.$controller.'.getForm',['add']);
$linkCancel			=	route('adminsystem.'.$controller.'.getList');
$linkLoadData		=	route('adminsystem.'.$controller.'.loadData');
$linkChangeStatus	=	route('adminsystem.'.$controller.'.changeStatus');
$linkDelete			=	route('adminsystem.'.$controller.'.deleteItem');
$linkTrash			=	route('adminsystem.'.$controller.'.trash');
$inputFilterSearch 		=	'<input type="text" class="form-control" name="filter_search"          value="">';
$arrStatus              =   array(-1 => '- Select status -', 1 => 'Kích hoạt', 0 => 'Ngưng kích hoạt');  
$ddlStatusNew              =   cmsSelectbox("status_new","form-control",@$arrStatus,0,"Việc làm mới");
$ddlStatusAttractive              =   cmsSelectbox("status_attractive","form-control",@$arrStatus,0,"Việc làm hấp dẫn");
$ddlStatusHighSalary              =   cmsSelectbox("status_high_salary","form-control",@$arrStatus,0,"Việc làm lương cao");
$ddlStatusHot              =   cmsSelectbox("status_hot","form-control",@$arrStatus,0,"Việc làm hot");
$ddlStatusQuick              =   cmsSelectbox("status_quick","form-control",@$arrStatus,0,"Việc làm tuyển gấp");
$ddlStatusInterested              =   cmsSelectbox("status_interested","form-control",@$arrStatus,0,"Việc làm được quan tâm nhiều nhất");
?>
<form class="form-horizontal" role="form" name="frm">	
	{{ csrf_field() }}
	<input type="hidden" name="sort_json"  value="" />	
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="note"  style="display: none;"></div>
			<div class="caption font-dark">
				<i class="{{$icon}}"></i>
				<span class="caption-subject bold uppercase">{{$title}}</span>
			</div>     
			<div class="actions">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-12">																								
							<a href="javascript:void(0)" onclick="trash()" class="btn red">Xóa <i class="fa fa-trash"></i></a> 	
							
						</div>                                                
					</div>
				</div>    
			</div>                                 
		</div>
		<div class="row">                     
			<div class="col-md-12">
				<table cellpadding="2" cellspacing="2" class="katana">
					<tr>
						<th>Tiêu đề tin tuyển dụng</th>
						<th>Việc làm mới</th>
						<th>Việc làm hấp dẫn</th>
						<th>Việc làm lương cao</th>
						<th>Việc làm hot</th>
						<th>Việc làm tuyển gấp</th>
						<th>Việc làm được quan tâm nhiều nhất</th>
						<th></th>
					</tr>
					<tr>
						<td><?php echo $inputFilterSearch; ?></td>
						<td><?php echo $ddlStatusNew; ?></td>
						<td><?php echo $ddlStatusAttractive; ?></td>
						<td><?php echo $ddlStatusHighSalary; ?></td>
						<td><?php echo $ddlStatusHot; ?></td>
						<td><?php echo $ddlStatusQuick; ?></td>
						<td><?php echo $ddlStatusInterested; ?></td>
						<td><button type="button" class="btn dark btn-outline sbold uppercase btn-product" onclick="getList();">Tìm kiếm</button></td>
					</tr>
				</table>                    
			</div>                             
		</div>   
		<div class="portlet-body">		
			<table class="table table-striped table-bordered table-hover table-checkable order-column" id="tbl-<?php echo $controller; ?>">
				<thead>
					<tr>
						<th width="1%"><input type="checkbox" onclick="checkAllAgent(this)"  name="checkall-toggle"></th>
						<th>Tin tuyển dụng</th>													
						<th>Khách hàng</th>						
						<th>Chăm sóc KH</th>
						<th>Ngày đăng</th>
						<th width="5%">Hiển thị tin</th>	
						<th width="5%">Kích hoạt</th>							
						<th width="1%">Sửa</th>  
						<th width="1%">Xóa</th>                    
					</tr>
				</thead>
				<tbody>                                                
				</tbody>
			</table>
		</div>
	</div>	
</form>
<script type="text/javascript" language="javascript">	

	function getList() {  	
		var token = $('form[name="frm"] input[name="_token"]').val();         
        var filter_search=$('form[name="frm"] input[name="filter_search"]').val();

        var status_new=$('form[name="frm"] select[name="status_new"]').val();
        var status_attractive=$('form[name="frm"] select[name="status_attractive"]').val();
        var status_high_salary=$('form[name="frm"] select[name="status_high_salary"]').val();
        var status_hot=$('form[name="frm"] select[name="status_hot"]').val();
        var status_quick=$('form[name="frm"] select[name="status_quick"]').val();
        var status_interested=$('form[name="frm"] select[name="status_interested"]').val();

		var dataItem={            
            '_token': token,
            'filter_search':filter_search,            
            'status_new':status_new,    
            'status_attractive':status_attractive,    
            'status_high_salary':status_high_salary,    
            'status_hot':status_hot,    
            'status_quick':status_quick,  
            'status_interested':status_interested  
        };
		$.ajax({
			url: '<?php echo $linkLoadData; ?>',
			type: 'POST', 
			data: dataItem,
			success: function (data, status, jqXHR) {  								
				vRecruitmentTable.clear().draw();
				vRecruitmentTable.rows.add(data).draw();
				spinner.hide();
			},
			beforeSend  : function(jqXHR,setting){
				spinner.show();
			},
		});
	}	
	function checkWithList(this_checkbox){
		var dr = vRecruitmentTable.row( $(this_checkbox).closest('tr') ).data();       		
		if(parseInt(dr['is_checked']) == 0){
			dr['checked'] ='<input type="checkbox" checked onclick="checkWithList(this)" name="cid" />';
			dr['is_checked'] = 1;
		}else{
			dr['checked'] ='<input type="checkbox" onclick="checkWithList(this)" name="cid" />';
			dr['is_checked'] = 0;
		}
		vRecruitmentTable.row( $(this_checkbox).closest('tr') ).data(dr);
	}	
	function changeStatus(id,status){		
		var token = $('input[name="_token"]').val();   		
		var dataItem={   
			'id':id,			
			'status':status,         
			'_token': token
		};
		$.ajax({
			url: '<?php echo $linkChangeStatus; ?>',
			type: 'POST',     
			data: dataItem,
			success: function (data, status, jqXHR) {   							                              				
				showMsg('note',data);               		
				vRecruitmentTable.clear().draw();
				vRecruitmentTable.rows.add(data.data).draw();
				spinner.hide();
			},
			beforeSend  : function(jqXHR,setting){
				spinner.show();
			},
		});		
		$("input[name='checkall-toggle']").prop("checked",false);
	}
	
	function deleteItem(id){		
		var xac_nhan = 0;
		var msg="Bạn có muốn xóa ?";
		if(window.confirm(msg)){ 
			xac_nhan = 1;
		}
		if(xac_nhan  == 0){
			return 0;
		}
		var token 	 = $('input[name="_token"]').val();   		
		var dataItem ={   
			'id':id,					           
			'_token': token
		};
		$.ajax({
			url: '<?php echo $linkDelete; ?>',
			type: 'POST', 			
			data: dataItem,
			success: function (data, status, jqXHR) {  				
				showMsg('note',data);               		
				vRecruitmentTable.clear().draw();
				vRecruitmentTable.rows.add(data.data).draw();
				spinner.hide();
			},
			beforeSend  : function(jqXHR,setting){
				spinner.show();
			},
		});
		$("input[name='checkall-toggle']").prop("checked",false);
	}	
	function trash(){	
		var xac_nhan = 0;
		var msg="Bạn có muốn xóa ?";
		if(window.confirm(msg)){ 
			xac_nhan = 1;
		}
		if(xac_nhan  == 0){
			return 0;
		}
		var token 	= 	$('input[name="_token"]').val();   		
		var dt 		= 	vRecruitmentTable.data();
		var str_id	=	"";		
		for(var i=0;i<dt.length;i++){
			var dr=dt[i];
			if(dr.is_checked==1){
				str_id +=dr.id+",";	            
			}
		}
		var dataItem ={   
			'str_id':str_id,		
			               		
			'_token': token
		};
		$.ajax({
			url: '<?php echo $linkTrash; ?>',
			type: 'POST', 
			             
			data: dataItem,
			success: function (data, status, jqXHR) {
				showMsg('note',data);  
				vRecruitmentTable.clear().draw();
				vRecruitmentTable.rows.add(data.data).draw();
				spinner.hide();
			},
			beforeSend  : function(jqXHR,setting){
				spinner.show();
			},
		});
		$("input[name='checkall-toggle']").prop("checked",false);
	}	
	$(document).ready(function(){		
		getList();
	});
</script>
@endsection()         

