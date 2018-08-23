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
use App\ProfileModel;
use App\ProfileJobModel;
use App\ProfilePlaceModel;
use App\ProfileExperienceModel;
use App\ProfileGraduationModel;
use App\ProfileLanguageModel;
use App\ProfileSkillModel;
use App\CandidateModel;
use DB;
class ProfileController extends Controller {
 var $_controller="profile";	
 var $_title="Hồ sơ ứng viên";
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
     
  $query=DB::table("profile");                                      
  if(!empty(@$request->filter_search)){
    $query->where('profile.fullname','like','%'.trim(mb_strtolower(@$request->filter_search,'UTF-8')).'%');
  }
  if((int)(@$request->candidate_id) > 0){
    $query->where('profile.candidate_id',(int)@$request->candidate_id);
  }
  $data=$query->select('profile.id','profile.fullname','profile.status','profile.created_at','profile.updated_at')
  ->groupBy('profile.id','profile.fullname','profile.status','profile.created_at','profile.updated_at')   
  ->orderBy('profile.id', 'desc')                
  ->get()->toArray();              
  $data=convertToArray($data);    

  $data=profileConverter($data,$this->_controller);            
  return $data;
} 
public function getForm($task,$id=0){     
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
     $arrRowData=ProfileModel::find((int)@$id)->toArray();                     
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
    $item         =   new ProfileModel;       
    $item->created_at   = date("Y-m-d H:i:s",time());        

  } else{
    $item       = ProfileModel::find((int)@$id);   

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
  $item           =       ProfileModel::find((int)@$id);        
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
    $item = ProfileModel::find((int)@$id);
    $item->delete();        
    ProfileJobModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
    ProfilePlaceModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
    ProfileExperienceModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
    ProfileGraduationModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
    ProfileLanguageModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
    ProfileSkillModel::whereRaw("profile_id = ?",[(int)@$id])->delete();
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
        $item=ProfileModel::find($value);
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
    DB::table("profile")->whereIn('id',@$arrID)->delete();
    DB::table('profile_experience')->whereIn('profile_id',@$arrID)->delete();         
    DB::table('profile_graduation')->whereIn('profile_id',@$arrID)->delete();         
    DB::table('profile_job')->whereIn('profile_id',@$arrID)->delete();         
    DB::table('profile_language')->whereIn('profile_id',@$arrID)->delete();         
    DB::table('profile_place')->whereIn('profile_id',@$arrID)->delete();         
    DB::table('profile_skill')->whereIn('profile_id',@$arrID)->delete();         
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
        $item=ProfileModel::find((int)@$value->id);                
        $item->sort_order=(int)$value->sort_order;                         
        $item->save();                      
      }                                                  
    }           
  }        
  $data                   =   $this->loadData($request);
  $msg['success']='Cập nhật thành công'; 
  $info = array(
    "checked"       => $checked,          
    'msg'       => $msg,           
    'data'              => $data
  );
  return $info;
}     

}
?>
