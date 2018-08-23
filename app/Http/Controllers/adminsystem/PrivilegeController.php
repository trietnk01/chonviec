<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PrivilegeModel;
use App\GroupPrivilegeModel;
use DB;
class PrivilegeController extends Controller {
   var $_controller="privilege";	
   var $_title="Nhóm quyền";
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
    $query=DB::table('privilege');        
    if(!empty(@$request->filter_search)){
      $query->where('privilege.fullname','like','%'.trim(@$request->filter_search).'%');
    }
    $data=$query->select('privilege.id','privilege.fullname','privilege.controller','privilege.action','privilege.sort_order','privilege.created_at','privilege.updated_at')
    ->groupBy('privilege.id','privilege.fullname','privilege.controller','privilege.action','privilege.sort_order','privilege.created_at','privilege.updated_at')
    ->orderBy('privilege.fullname', 'asc')->get()->toArray()     ;              
    $data=convertToArray($data);    
    $data=privilegeConverter($data,$this->_controller);            
    return $data;
  }   
  public function getForm($task,$id=""){     
    $controller=$this->_controller;     
    $title="";
    $icon=$this->_icon; 
    $arrRowData=array();
    $arrPrivilege=getArrPrivilege();
    $requestControllerAction=$this->_controller."-form";  
    if(in_array($requestControllerAction, $arrPrivilege)){
      switch ($task) {
       case 'edit':
       $title=$this->_title . " : Update";
       $arrRowData=PrivilegeModel::find((int)@$id)->toArray();      
       break;
       case 'add':
       $title=$this->_title . " : Add new";
       break;     
     }    
     return view("adminsystem.".$this->_controller.".form",compact("arrRowData","controller","task","title","icon"));
   }else{
    return view("adminsystem.no-access",compact('controller'));
  }

  }
  public function save(Request $request){
    $id 					        =		trim($request->id);        
    $fullname 				    =		trim($request->fullname);
    $controller           =   trim($request->controller);
    $action 					    = 	trim($request->action);          
    $sort_order           =   trim($request->sort_order);                    
    $data 		            =   array();
    
    $item		              =   null;
    
    $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
    if(empty($fullname)){
     $checked = 0;
     
     $msg["fullname"] = "Thiếu tên nhóm quyền";
   }else{
    $data=array();
    if (empty($id)) {
      $data=PrivilegeModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
    }else{
      $data=PrivilegeModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
    }  
    if (count($data) > 0) {
      $checked = 0;
      
      $msg["fullname"] = "Tên nhóm quyền đã tồn tại";
    }      	
  }          
  if(empty($sort_order)){
   $checked = 0;
   
   $msg["sort_order"] 		= "Thiếu sắp xếp";
  }          
  if ($checked == 1) {    
    if(empty($id)){
      $item 				    = 	new PrivilegeModel;       
      $item->created_at =	  date("Y-m-d H:i:s",time());                            
    } else{
      $item				      =	  PrivilegeModel::find((int)@$id);                            		  		 	
    }  
    $item->fullname 		  =	$fullname;
    $item->controller     = $controller;
    $item->action 			  =	$action;
    $item->sort_order     = (int)$sort_order;                
    $item->updated_at 		=	date("Y-m-d H:i:s",time());    	        	
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


  public function deleteItem(Request $request){
    $id                     =   (int)$request->id;              
    
    $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();

    if($checked == 1){
      $item = PrivilegeModel::find($id);
      $item->delete();
      GroupPrivilegeModel::whereRaw("privilege_id = ?",[(int)@$id])->delete();      
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
      DB::table('privilege')->whereIn('id',@$arrID)->delete();   
      DB::table('group_privilege')->whereIn('privilege_id',@$arrID)->delete();
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
  public function sortOrder(Request $request){
    $sort_json              =   $request->sort_json;           
    $data_order             =   json_decode($sort_json);       
    $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
    if(count($data_order) > 0){              
      foreach($data_order as $key => $value){      
        if(!empty($value)){
          $item=PrivilegeModel::find((int)@$value->id);                
          $item->sort_order=(int)@$value->sort_order;                         
          $item->save();                      
        }                                                  
      }           
    }        
    $msg['success']='Cập nhật thành công'; 
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
