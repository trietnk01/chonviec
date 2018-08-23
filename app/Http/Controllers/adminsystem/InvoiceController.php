<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\InvoiceModel;
use App\InvoiceDetailModel;
use DB;
class InvoiceController extends Controller {
 var $_controller="invoice";	
 var $_title="Đơn đặt hàng";
 var $_icon="icon-settings font-dark";
 public function getList(){		
  $controller=$this->_controller;	
  $task="list";
  $title=$this->_title;
  $icon=$this->_icon;		    		
  $arrPrivilege=getArrPrivilege();
  $requestControllerAction=$this->_controller."-list";         
  if(in_array($requestControllerAction,$arrPrivilege)){
    return view("adminsystem.".$this->_controller.".list",compact("controller","task","title","icon")); 
  }
  else{
    return view("adminsystem.no-access",compact('controller'));
  }
}	
public function loadData(Request $request){           
  $query=DB::table('invoice');        
  if(!empty(@$request->filter_search)){
    $query->where('invoice.fullname','like','%'.trim(@$request->filter_search).'%');
  }
  $data=$query->select('invoice.id','invoice.code','invoice.email','invoice.fullname','invoice.address','invoice.phone','invoice.quantity','invoice.total_price','invoice.status','invoice.created_at','invoice.updated_at')
  ->groupBy('invoice.id','invoice.code','invoice.email','invoice.fullname','invoice.address','invoice.phone','invoice.quantity','invoice.total_price','invoice.status','invoice.created_at','invoice.updated_at')
  ->orderBy('invoice.created_at', 'desc')->get()->toArray()     ;              
  $data=convertToArray($data);    
  $data=invoiceConverter($data,$this->_controller);            
  return $data;
}   	
public function getForm($task,$id=""){		 
  $controller=$this->_controller;			
  $title="";
  $icon=$this->_icon; 
  $arrRowData=array();    
  $arrInvoiceDetail=array();  
  
  $arrPrivilege=getArrPrivilege();
  $requestControllerAction=$this->_controller."-form";  
  if(in_array($requestControllerAction, $arrPrivilege)){
    switch ($task) {
      case 'edit':
      $title=$this->_title . " : Update";
      $arrRowData=InvoiceModel::find((int)@$id)->toArray();           
      $arrInvoiceDetail=InvoiceDetailModel::whereRaw("invoice_id = ?",[(int)@$id])->select()->get()->toArray();
      
      break;
      case 'add':
      $title=$this->_title . " : Add new";
      break;      
    }             
    return view("adminsystem.".$this->_controller.".form",compact("arrRowData","arrInvoiceDetail","controller","task","title","icon"));
  }else{
    return view("adminsystem.no-access",compact('controller'));
  }
  
}
public function save(Request $request){
  $id 					           =	trim($request->id)	;        
  $fullname 				       =	trim($request->fullname)	;
  $address 					       = 	trim($request->address);
  $phone	                 =	trim($request->phone);                
  
  $status 				         =  trim($request->status);        
  $data 		               =  array();
  
  $item		                 =  null;
  
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();
  if((int)$status==-1){
   $checked = 0;
   
   $msg["status"] 			= "Thiếu trạng thái";
 }
 if ($checked == 1) {    
   if(empty($id)){
    $item 				= 	new InvoiceModel;       
    $item->created_at 	=	date("Y-m-d H:i:s",time());                      			
  } else{
    $item				=	InvoiceModel::find((int)@$id);                         
  }  
  $item->fullname 		=	$fullname;
  $item->address 			=	$address;
  $item->phone 		    =	$phone;            
  
  
  
  $item->status 			=	(int)@$status;    
  $item->updated_at 	=	date("Y-m-d H:i:s",time());    	        	
  $item->save();  	
  $msg['success']='Lưu thành công';
} 
$info = array(
        "checked"       => $checked,          
        'msg'       => $msg,      
        'link_edit'=>route('adminsystem.'.$this->_controller.'.getForm',['edit',@$item->id])
      );      		 			       
return $info;       
}
public function changeStatus(Request $request){
  $id             =       (int)$request->id;     
  
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();

  $status         =       (int)$request->status;
  $item           =       InvoiceModel::find((int)@$id);        
  $item->status   =       $status;
  $item->save();
  $msg['success']='Cập nhật thành công';     
  $data                   =   $this->loadData($request);
  $info = array(
    "checked"       => $checked,          
    'msg'       => $msg,           
    'data'              => $data
  );
  return $info;
}      
public function deleteItem(Request $request){
  $id                     =   (int)$request->id;              
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();                            
  if($checked == 1){
    $item               =   InvoiceModel::find((int)@$id);
    $item->delete();            
    InvoiceDetailModel::whereRaw("invoice_id = ?",[(int)@$id])->delete();
    $msg['success']='Xóa thành công';           
  }        
  $data                   =   $this->loadData($request);
  $info = array(
    "checked"       => $checked,          
    'msg'       => $msg,    
    'data'              => $data
  );
  return $info;
}
public function updateStatus(Request $request){
  $strID                 =   $request->str_id;     
  $status                 =   $request->status;            
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();                       
  $strID=substr($strID, 0,strlen($strID) - 1);
  $arrID=explode(',',$strID);                 
  if(empty($strID)){
    $checked            =   0;
    
    $msg['chooseone']            =   "Vui lòng chọn ít nhất một phần tử";
  }
  if($checked==1){
    foreach ($arrID as $key => $value) {
      if(!empty($value)){
        $item=InvoiceModel::find($value);
        $item->status=$status;
        $item->save();      
      }            
    }
    $msg['success']='Cập nhật thành công';
  }         
  $data                   =   $this->loadData($request);
  $info = array(
    "checked"       => $checked,          
    'msg'       => $msg,    
    'data'              => $data
  );
  return $info;
}
public function trash(Request $request){
  $strID                 =   $request->str_id;               
  
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();        
  $strID=substr($strID, 0,strlen($strID) - 1);
  $arrID=explode(',',$strID);                 
  if(empty($strID)){
    $checked            =   0;
    
    $msg['chooseone']            =   "Vui lòng chọn ít nhất một phần tử";
  }
  if($checked == 1){                              
    DB::table('invoice')->whereIn('id',@$arrID)->delete();   
    DB::table('invoice_detail')->whereIn('invoice_id',@$arrID)->delete(); 
    $msg['success']='Xóa thành công';      
  }
  $data                   =   $this->loadData($request);
  $info = array(
    "checked"       => $checked,          
    'msg'       => $msg,         
    'data'              => $data
  );
  return $info;
}

}
?>
