<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CategoryProductModel;
use App\ProductModel;
use App\PageModel;
use App\MenuModel;
use App\PaymentMethodModel;
use App\SupporterModel;
use App\CandidateModel;
use App\ScaleModel;
use App\User;
use App\SexModel;
use App\MarriageModel;
use App\ProvinceModel;
use App\ProfileModel;
use DB;
use Hash;
use Sentinel;
class CandidateController extends Controller {
 var $_controller='candidate';	
 var $_title="Ứng viên";
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
  $user=Sentinel::getUser(); 
  $query=DB::table('candidate')  ;
  $filter_search="";            
  if(!empty(@$request->filter_search)){      
   $filter_search=trim(@$request->filter_search) ;    
   $query->where('candidate.fullname','like','%'.trim(mb_strtolower($filter_search,'UTF-8')).'%');
 }
 $data=$query->select('candidate.id','candidate.fullname','candidate.email','candidate.status','candidate.created_at','candidate.updated_at')
 ->groupBy('candidate.id','candidate.fullname','candidate.email','candidate.status','candidate.created_at','candidate.updated_at')   
 ->orderBy('candidate.created_at', 'desc')                
 ->get()->toArray();              
 $data=convertToArray($data);    
 $data=candidateConverter($data,$this->_controller);            
 return $data;
} 
public function getForm($task,$id=""){     
  $controller=$this->_controller;     
  $title="";
  $icon=$this->_icon; 
  $arrRowData=array();        
  $arrPrivilege=getArrPrivilege();
  $requestControllerAction=$this->_controller."-form";  
  $arrSex=SexModel::select("id","fullname")->orderBy("fullname","asc")->get()->toArray(); 
  $arrMarriage=MarriageModel::select("id","fullname")->orderBy("fullname","asc")->get()->toArray(); 
  $arrProvince=ProvinceModel::select("id","fullname")->orderBy("fullname","asc")->get()->toArray();       
  if(in_array($requestControllerAction, $arrPrivilege)){
    switch ($task) {
     case 'edit':
     $title=$this->_title . " : Update";
     $arrRowData=CandidateModel::find((int)@$id)->toArray();                     
     break;
     case 'add':
     $title=$this->_title . " : Add new";
     break;     
   }                  
   return view("adminsystem.".$this->_controller.".form",compact("arrRowData","arrSex","arrMarriage","arrProvince","controller","task","title","icon"));
 }else{
  return view("adminsystem.no-access",compact('controller'));
}        
}
public function save(Request $request){
  $id 					        =		trim(@$request->id);              
  $password             =   (@$request->password);
  $password_confirmed   =   (@$request->password_confirmed);
  $fullname             =   trim(@$request->fullname);
  $phone                =   trim(@$request->phone);
  $day=(int)@$request->day;
  $month=(int)@$request->month;
  $year=(int)@$request->year;    
  $sex_id=trim(@$request->sex_id);
  $marriage_id=(int)trim(@$request->marriage_id);
  $province_id=(int)trim(@$request->province_id);  
  $address              =   trim(@$request->address);    
  $image_file           =   null;
  if(isset($_FILES["image"])){
    $image_file         =   $_FILES["image"];
  }
  $image_hidden         =   trim(@$request->image_hidden);                                              
  $status               =   trim(@$request->status);                   
  $data 		            =   array();               
  $item		              =   null;    
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();
  $setting= getSettingSystem();
  $width=$setting['product_width']['field_value'];
  $height=$setting['product_height']['field_value'];   
  if($password != null){
    if(mb_strlen($password) < 10 ){
      $checked = 0;                  
      $msg["password"] = "Mật khẩu tối thiểu phải 10 ký tự";
    }else{
      if(strcmp($password, $password_confirmed) !=0 ){
        $checked = 0;                    
        $msg["password"]= "Xác nhận mật khẩu không trùng khớp";
      }
    }
  }         
  if(empty($fullname)){
   $checked = 0;                 
   $msg["fullname"]= "Thiếu tên ứng viên";
 }else{
  $data=array();
  if (empty($id)) {
    $data=CandidateModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();           
  }else{
    $data=CandidateModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();   
  }  
  if (count($data) > 0) {
    $checked = 0;
    $msg["fullname"]= "Tên công ty đã tồn tại";
  }       
}                   
if(empty(@$phone)){
  $checked=0;
  $msg['phone']='Thiếu số điện thoại';
} 

if($day==0){
  $checked=0;
  $msg['day']='Thiếu ngày sinh';
}
if($month==0){
  $checked=0;
  $msg['month']='Thiếu tháng sinh';
}
if($year==0){
  $checked=0;
  $msg['year']='Thiếu năm sinh';
}
if((int)@$request->sex_id == 0){
  $checked=0;
  $msg['sex_id']='Thiếu giới tính';
}
if((int)@$request->marriage_id == 0){
  $checked=0;
  $msg['marriage_id']='Thiếu hôn nhân';
}
if((int)@$request->province_id == 0){
  $checked=0;
  $msg['province_id']='Thiếu tỉnh thành phố';
}
if(mb_strlen(@$address) < 10){
  $checked=0;
  $msg['address']='Thiếu địa chỉ';
}
if((int)$status==-1){
 $checked = 0;
 $msg["status"]= "Thiếu trạng thái";
}

if ($checked == 1) {   
  $image_name='';
  if($image_file != null){                      
    $image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);
  } 
  if(empty($id)){
    $item         =   new CandidateModel;       
    $item->created_at   = date("Y-m-d H:i:s",time());        
    if(!empty($image_name)){
      $item->avatar    =   trim($image_name) ;  
    } 
  } else{
    $item       = CandidateModel::find((int)@$id);   
    $item->avatar=null;                       
    if(!empty($image_hidden)){
      $item->avatar =$image_hidden;          
    }
    if(!empty($image_name))  {
      $item->avatar=$image_name;                                                
    }  
  }  
  if($password != null){
    $item->password         = Hash::make($password);
  }
  $item->fullname           = @$fullname;              
  $item->phone              = @$phone;  
  /* begin ngày sinh nhật */        
  $ts=mktime(0,0,0,$month,$day,$year);        
  $birthday=date('Y-m-d', $ts);
  $item->birthday=$birthday;
  /* end ngày sinh nhật */ 
  $item->sex_id=(int)@$sex_id;
  $item->marriage_id=(int)@$marriage_id;
  $item->province_id=(int)@$province_id;
  $item->address=@$address;
  $item->status 			      =	(int)@$status;     
  $item->updated_at 		    =	date("Y-m-d H:i:s",time());    	        	
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
  $id             =       (int)@$request->id;     
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();
  $status         =       (int)@$request->status;
  $item           =       CandidateModel::find((int)@$id);        
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
    $data                   =   ProfileModel::whereRaw("candidate_id = ?",[(int)@$id])->get()->toArray();  
    if(count($data) > 0){
      $checked     =   0;          
      $msg['cannotdelete']                    =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
    }                                       
    if($checked == 1){
      $item = CandidateModel::find((int)@$id);
      $item->delete();     
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
          $item=CandidateModel::find($value);
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
    $strID                =   $request->str_id;               
    $info                 =   array();
    $checked              =   1;                           
    $msg                =   array();
    $strID=substr($strID, 0,strlen($strID) - 1);
    $arrID=explode(',',$strID); 
    if(empty($strID)){
      $checked            =   0;

      $msg['chooseone']            =   "Vui lòng chọn ít nhất một phần tử";
    }else{
      foreach ($arrID as $key => $value) {
        if(!empty($value)){                  
          $data                   =   ProfileModel::whereRaw("candidate_id = ?",[(int)@$value])->get()->toArray();                     
          if(count($data) > 0){
            $checked     =   0;

            $msg['cannotdelete']                    =   "Phần tử đã có dữ liệu con vui lòng không xóa";
          }
        }                
      }
    }                          
    if($checked == 1){                                  
      DB::table('candidate')->whereIn('id',@$arrID)->delete();
      $msg['success']='Xóa thành công';                                      
    }
    $data                 =   $this->loadData($request);
    $info = array(
      "checked"       => $checked,          
      'msg'       => $msg,              
      'data'              => $data
    );
    return $info;
  }            
}
?>
