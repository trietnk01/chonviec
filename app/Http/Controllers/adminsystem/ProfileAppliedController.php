<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CategoryArticleModel;
use App\CategoryProductModel;
use App\ArticleModel;
use App\ProductModel;
use App\PageModel;
use App\MenuModel;
use App\ProjectModel;
use App\ProjectArticleModel;
use App\ArticleCategoryModel;
use App\PaymentMethodModel;
use App\SupporterModel;
use App\ProfileAppliedModel;
use App\CandidateModel;
use DB;
class ProfileAppliedController extends Controller {
 var $_controller="profile-applied";	
 var $_title="Hồ sơ ứng tuyển chờ duyệt";
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
  $filter_search="";            
  if(!empty(@$request->filter_search)){      
    $filter_search=trim(@$request->filter_search) ;    
  }        
  $data=DB::table('profile_applied') 
  ->leftJoin('recruitment','profile_applied.recruitment_id','=','recruitment.id')                 
  ->leftJoin('profile','profile_applied.profile_id','=','profile.id')
  ->leftJoin('candidate','profile_applied.candidate_id','=','candidate.id')
  ->select('profile_applied.id','recruitment.fullname as recruitment_name','profile_applied.profile_id','profile.fullname as profile_name','candidate.fullname as candidate_name','profile_applied.file_attached','profile_applied.status','profile_applied.created_at','profile_applied.updated_at')                
  ->where('candidate.fullname','like','%'.trim(mb_strtolower($filter_search,'UTF-8')).'%')                     
  ->groupBy('profile_applied.id','recruitment.fullname','profile_applied.profile_id','profile.fullname','candidate.fullname','profile_applied.file_attached','profile_applied.status','profile_applied.created_at','profile_applied.updated_at')      
  ->orderBy('profile_applied.id', 'desc')                
  ->get()->toArray();              
  $data=convertToArray($data);    
  $data=recruitmentProfile2Converter($data,$this->_controller);            
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
     $arrRowData=ProfileAppliedModel::find((int)@$id)->toArray();                     
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
  
  $status               =   trim($request->status);          
  $data 		            =   array();

  $item		              =   null;
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();
 
 if((int)$status==-1){
   $checked = 0;

   $msg["status"] 			= "Thiếu trạng thái";
 }
 if ($checked == 1) {    
  if(empty($id)){
    $item         =   new ProfileAppliedModel;       
    $item->created_at   = date("Y-m-d H:i:s",time());        

  } else{
    $item       = ProfileAppliedModel::find((int)@$id);   

  }    
  $item->status 			    =	(int)@$status;    
  $item->updated_at 		  =	date("Y-m-d H:i:s",time());    	        	                
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
  $status         =       (int)@$request->status;
  $item           =       ProfileAppliedModel::find((int)@$id);        
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
    $item = ProfileAppliedModel::find((int)@$id);
    $item->delete();    
    $msg['success']          =   'Xóa thành công';                                                     
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
        $item=ProfileAppliedModel::find($value);
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
    DB::table('profile_applied')->whereIn('id',@$arrID)->delete();
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
