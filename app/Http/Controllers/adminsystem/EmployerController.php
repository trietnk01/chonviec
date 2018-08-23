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
use App\EmployerModel;
use App\ProvinceModel;
use App\ScaleModel;
use App\User;
use App\RecruitmentModel;
use DB;
use Hash;
use Sentinel;
class EmployerController extends Controller {
  	var $_controller="employer";	
  	var $_title="Nhà tuyển dụng";
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
  		$query=DB::table('employer')->leftJoin('users','employer.user_id','=','users.id')  ;
  		if((int)@$user->id > 1){
  			$query->where('employer.user_id',(int)@$user->id);
  		}  		
      $filter_search="";            
      if(!empty(@$request->filter_search)){      
        $filter_search=trim(@$request->filter_search) ;    
        $query->where('employer.fullname','like','%'.trim(mb_strtolower($filter_search,'UTF-8')).'%');
      }     		
  		$data=$query->select('employer.id','employer.fullname','employer.email','users.fullname as user_fullname','employer.status','employer.created_at','employer.updated_at')    		                     
  		->groupBy('employer.id','employer.fullname','employer.email','users.fullname','employer.status','employer.created_at','employer.updated_at')   
  		->orderBy('employer.created_at', 'desc')                
  		->get()->toArray();              
  		$data=convertToArray($data);    
  		$data=employerConverter($data,$this->_controller);            
  		return $data;
  	} 
    public function getForm($task,$id=""){     
      $controller=$this->_controller;     
      $title="";
      $icon=$this->_icon; 
      $arrRowData=array();        
      $arrPrivilege=getArrPrivilege();
      $requestControllerAction=$this->_controller."-form";  
      $arrUser=User::select("id","fullname")->orderBy("fullname","asc")->get()->toArray(); 
      $arrProvince=ProvinceModel::select("id","fullname")->orderBy("fullname","asc")->get()->toArray(); 
      $arrScale=ScaleModel::select("id","fullname")->orderBy("fullname","asc")->get()->toArray(); 
      if(in_array($requestControllerAction, $arrPrivilege)){
        switch ($task) {
         case 'edit':
         $title=$this->_title . " : Update";
         $arrRowData=EmployerModel::find((int)@$id)->toArray();                     
         break;
         case 'add':
         $title=$this->_title . " : Add new";
         break;     
       }                  
       return view("adminsystem.".$this->_controller.".form",compact("arrRowData","arrProvince","arrScale","arrUser","controller","task","title","icon"));
     }else{
      return view("adminsystem.no-access",compact('controller'));
    }        
  }
  public function save(Request $request){
    $id 					        =		trim(@$request->id);              
    $password             =   (@$request->password);
    $password_confirmed   =   (@$request->password_confirmed);
    $fullname             =   trim(@$request->fullname);
    $alias                =   trim(@$request->alias);    
    $meta_keyword 				=		trim(@$request->meta_keyword);
    $meta_description     =   trim(@$request->meta_description);
    $address              =   trim(@$request->address);
    $phone                =   trim(@$request->phone);
    $province_id          =   trim(@$request->province_id);
    $scale_id             =   trim(@$request->scale_id);  
    $intro                =   trim(@$request->intro);  
    $fax                  =   trim(@$request->fax);
    $website              =   trim(@$request->website);
    $contacted_name       =   trim(@$request->contacted_name);
    
    $contacted_phone      =   trim(@$request->contacted_phone);
    $user_id              =   trim(@$request->user_id); 
    $image_file           =   null;
    if(isset($_FILES["image"])){
      $image_file         =   $_FILES["image"];
    }
    $image_hidden         =   trim(@$request->image_hidden);      
    $status               =   trim(@$request->status);   
    $status_authentication =  trim(@$request->status_authentication);       
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
     $msg["fullname"]= "Thiếu tên công ty";
   }else{
    $data=array();
    if (empty($id)) {
      $data=EmployerModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();           
    }else{
      $data=EmployerModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();   
    }  
    if (count($data) > 0) {
      $checked = 0;
      $msg["fullname"]= "Tên công ty đã tồn tại";
    }       
  }                   
  if(empty($alias)){
   $checked = 0;
   $msg["alias"]= "Thiếu alias";
 }else{
  $data=array();
  if (empty($id)) {
    $data=EmployerModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();           
  }else{
    $data=EmployerModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();   
  }  
  if (count($data) > 0) {
    $checked = 0;
    $msg["alias"]= "Alias đã tồn tại";
  }       
}   
if((int)@$province_id == 0){
  $checked = 0;
  $msg["province_id"]= "Thiếu tỉnh thành phố";
}
if((int)@$scale_id == 0){
  $checked = 0;
  $msg["scale_id"] = "Thiếu quy mô công ty";
}   
if((int)$status==-1){
 $checked = 0;
 $msg["status"]= "Thiếu trạng thái";
}
if((int)@$status_authentication==-1){
 $checked = 0;           
 $msg["status_authentication"]= "Thiếu trạng thái xác thực";
}
if ($checked == 1) {   
  $image_name='';
  if($image_file != null){                      
    $image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);
  } 
  if(empty($id)){
    $item         =   new EmployerModel;       
    $item->created_at   = date("Y-m-d H:i:s",time());        
    if(!empty($image_name)){
      $item->logo    =   trim($image_name) ;  
    } 
  } else{
    $item       = EmployerModel::find((int)@$id);   
    $item->logo=null;                       
    if(!empty($image_hidden)){
      $item->logo =$image_hidden;          
    }
    if(!empty($image_name))  {
      $item->logo=$image_name;                                                
    }  
  }  
  if($password != null){
    $item->password         = Hash::make($password);
  }
  $item->fullname           = @$fullname;              
  $item->alias              = @$alias;
  $item->meta_keyword       = @$meta_keyword;
  $item->meta_description   = @$meta_description;
  $item->address            = @$address;
  $item->phone              = @$phone;
  $item->province_id        = (int)@$province_id;
  $item->scale_id           = (int)@$scale_id;
  $item->intro              = @$intro;
  $item->fax                = @$fax;
  $item->website            = @$website;
  $item->contacted_name     = @$contacted_name;

  $item->contacted_phone    = @$contacted_phone;
  $item->user_id            = (int)@$user_id;
  $item->status 			      =	(int)@$status;   
  $item->status_authentication = @$status_authentication; 
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
            $item           =       EmployerModel::find((int)@$id);        
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
            $id                     =   (int)@$request->id;              
            $info                 =   array();
            $checked              =   1;                           
            $msg                =   array();     
            $data                   =   RecruitmentModel::whereRaw("employer_id = ?",[(int)@$id])->get()->toArray();  
            if(count($data) > 0){
              $checked     =   0;          
              $msg['cannotdelete']                    =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }                             
            if($checked == 1){
              $item = EmployerModel::find((int)@$id);
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
                  $item=EmployerModel::find($value);
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
            if(count($arrID) == 0){
              $checked            =   0;

              $msg['chooseone']            =   "Vui lòng chọn ít nhất một phần tử";
            }else{
              foreach ($arrID as $key => $value) {
                if(!empty($value)){                  
                  $data                   =   RecruitmentModel::whereRaw("employer_id = ?",[(int)@$value])->get()->toArray();                     
                  if(count($data) > 0){
                    $checked     =   0;

                    $msg['cannotdelete']                    =   "Phần tử đã có dữ liệu con vui lòng không xóa";
                  }
                }                
              }
            }                    
            if($checked == 1){                                  
              DB::table('employer')->whereIn('id',@$arrID)->delete();
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
