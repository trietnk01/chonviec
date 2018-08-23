<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GroupMemberModel;
use App\GroupPrivilegeModel;
use App\PrivilegeModel;
use App\User;
use App\UserGroupMemberModel;
use DB;
class GroupMemberController extends Controller {
     var $_controller="group-member";	
     var $_title="Nhóm người dùng";
     var $_icon="icon-settings font-dark";
     var $_pageAccessDenied="no-access";
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
        return view("adminsystem.".$this->_pageAccessDenied);
      }
    }	
    public function loadData(Request $request){           
      $query=DB::table('group_member');        
      if(!empty(@$request->filter_search)){
        $query->where('group_member.fullname','like','%'.trim(@$request->filter_search).'%');
      }
      $data=$query->select('group_member.id','group_member.fullname','group_member.sort_order','group_member.created_at','group_member.updated_at')
      ->groupBy('group_member.id','group_member.fullname','group_member.sort_order','group_member.created_at','group_member.updated_at')
      ->orderBy('group_member.sort_order', 'asc')->get()->toArray()     ;              
      $data=convertToArray($data);    
      $data=groupMemberConverter($data,$this->_controller);            
      return $data;
    } 	
    public function getForm($task,$id=""){		 
      $controller=$this->_controller;			
      $title="";
      $icon=$this->_icon; 
      $arrRowData=array();
      $arrGroupPrivilege=array();
      $arrPrivilege=getArrPrivilege();
      $requestControllerAction=$this->_controller."-form";  
      if(in_array($requestControllerAction, $arrPrivilege)){
        switch ($task) {
          case 'edit':
          $title=$this->_title . " : Update";
          $arrRowData=GroupMemberModel::find((int)@$id)->toArray();      
          $arrGroupPrivilege=GroupPrivilegeModel::whereRaw("group_member_id = ?",[(int)@$id])->get()->toArray();
          break;
          case 'add':
          $title=$this->_title . " : Add new";
          break;      
        }             
        $arrPrivilege=PrivilegeModel::select("id","fullname","controller","action","sort_order","created_at","updated_at")->orderBy("controller","asc")->orderBy("sort_order","asc")->get()->toArray();  
        return view("adminsystem.".$this->_controller.".form",compact("arrRowData","arrPrivilege","arrGroupPrivilege","controller","task","title","icon"));
      }else{
        return view("adminsystem.no-access",compact('controller'));
      }
      
    }
    public function save(Request $request){
      $id 					           =	trim($request->id)	;        
      $fullname 				       =	trim($request->fullname)	;         
      $alias                   =  trim($request->alias);     
      $sort_order 			       =	trim($request->sort_order);   
      $privilege_id            =  $request->privilege_id;

      $data 		               =  array();
      
      $item		                 =  null;
      $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
      if(empty($fullname)){
       $checked = 0;
       
       $msg["fullname"] = "Thiếu tên nhóm người dùng";
     }else{
      $data=array();
      if (empty($id)) {
        $data=GroupMemberModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
      }else{
        $data=GroupMemberModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
      }  
      if (count($data) > 0) {
        $checked = 0;
        
        $msg["fullname"] = "Tên nhóm người dùng đã tồn tại";
      }      	
    }        
    if(empty($sort_order)){
     $checked = 0;

     $msg["sort_order"] 		= "Thiếu sắp xếp";
    }

    if ($checked == 1) {    
     if(empty($id)){
      $item 				= 	new GroupMemberModel;       
      $item->created_at 	=	date("Y-m-d H:i:s",time());                      
    } else{
      $item				=	GroupMemberModel::find((int)@$id);                            
    }  
    $item->fullname 		=	$fullname;         
    $item->alias        = $alias;   
    $item->sort_order 	=	(int)$sort_order;     
    $item->updated_at 	=	date("Y-m-d H:i:s",time());    	        	
    $item->save();  	
    if(count($privilege_id) > 0){
      $arrGroupPrivilege=GroupPrivilegeModel::whereRaw("group_member_id = ?",[(int)@$item->id])->select("privilege_id")->get()->toArray();            
      $arrGroupPrivilegeID=array();
      foreach ($arrGroupPrivilege as $key => $value) {
        $arrGroupPrivilegeID[]=$value["privilege_id"];
      }
      $selected=@$privilege_id;
      sort($selected);
      sort($arrGroupPrivilegeID);        
      $resultCompare=0;
      if($selected == $arrGroupPrivilegeID){
        $resultCompare=1;       
      }
      if($resultCompare==0){
        GroupPrivilegeModel::whereRaw("group_member_id = ?",[(int)@$item->id])->delete();  
        foreach ($selected as $key => $value) {
          $privilege_id=$value;
          $groupPrivilege=new GroupPrivilegeModel;
          $groupPrivilege->group_member_id=(int)@$item->id;
          $groupPrivilege->privilege_id=(int)@$privilege_id;  
          $groupPrivilege->created_at=date("Y-m-d H:i:s",time());
          $groupPrivilege->updated_at=date("Y-m-d H:i:s",time());  
          $groupPrivilege->save();
        }
      }       
    }
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
        $item = GroupMemberModel::find((int)@$id);
        $item->delete();
        GroupPrivilegeModel::whereRaw("group_member_id = ?",[(int)@$id])->delete();
        UserGroupMemberModel::whereRaw('group_member_id = ?',[(int)@$id])->delete();
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
        DB::table('group_member')->whereIn('id',@$arrID)->delete();   
        DB::table('group_privilege')->whereIn('group_member_id',@$arrID)->delete();  
        DB::table('user_group_member')->whereIn('group_member_id',@$arrID)->delete();   
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
            $item=GroupMemberModel::find((int)@$value->id);                
            $item->sort_order=(int)$value->sort_order;                         
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
    public function createAlias(Request $request){
      $id                =  trim($request->id)  ; 
      $fullname                =  trim($request->fullname)  ;        
      $data                    =  array();
      
      $item                    =  null;
      
      $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
      
      $alias='';                     
      if(empty($fullname)){
       $checked = 0;           
       $msg["fullname"] = "Thiếu tên bài viết";
     }else{          
      $alias=str_slug($fullname,'-');          
      $dataGroupMember=array();          
      $checked_trung_alias=0;          
      if (empty($id)) {              
        $dataGroupMember=GroupMemberModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();             
      }else{
        $dataGroupMember=GroupMemberModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
      }            
      if (count($dataGroupMember) > 0) {
        $checked_trung_alias=1;
      }         
      if((int)$checked_trung_alias == 1){
        $code_alias=rand(1,999999);
        $alias=$alias.'-'.$code_alias;
      }
    }
    if ($checked == 1){
      $msg['success']='Lưu thành công';     
    }    
    $info = array(
      "checked"       => $checked,          
        'msg'       => $msg,                    
      "alias"            => $alias
    );           
    return $info;
    } 
}
?>
