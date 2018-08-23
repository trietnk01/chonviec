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
use App\SalaryModel;
use App\RecruitmentModel;
use App\EmployerModel;
use App\RecruitmentJobModel;
use App\RecruitmentPlaceModel;
use DB;
use Sentinel;
class RecruitmentController extends Controller {
 var $_controller='recruitment';	
 var $_title='Tin tuyển dụng';
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
  $query=DB::table('recruitment');
  $query->leftJoin('employer','recruitment.employer_id','=','employer.id');  
  $query->leftJoin('users','employer.user_id','=','users.id')  ;
  if(!empty(@$request->filter_search)){          
    $query->where('recruitment.fullname','like','%'.trim(mb_strtolower(@$request->filter_search,'UTF-8')).'%')  ;                   
  }
  if((int)@$user->id > 1){
    $query->where('users.id',(int)@$user->id);
  }       
  if((int)@$request->status_new==1){
    $query->where('recruitment.status_new',(int)@$request->status_new);
  }               
  if((int)@$request->status_attractive==1){
    $query->where('recruitment.status_attractive',(int)@$request->status_attractive);
  }
  if((int)@$request->status_high_salary==1){
    $query->where('recruitment.status_high_salary',(int)@$request->status_high_salary);
  }
  if((int)@$request->status_hot==1){
    $query->where('recruitment.status_hot',(int)@$request->status_hot);
  }
  if((int)@$request->status_quick==1){
    $query->where('recruitment.status_quick',(int)@$request->status_quick);
  }
  if((int)@$request->status_interested==1){
    $query->where('recruitment.status_interested',(int)@$request->status_interested);
  }
  $data= $query->select('recruitment.id','recruitment.fullname','employer.fullname as employer_fullname','users.fullname as user_fullname','recruitment.status_employer','recruitment.status','recruitment.created_at','recruitment.updated_at')
  ->groupBy('recruitment.id','recruitment.fullname','employer.fullname','users.fullname','recruitment.status_employer','recruitment.status','recruitment.created_at','recruitment.updated_at')
  ->orderBy('recruitment.id', 'desc')                
  ->get()->toArray();              
  $data=convertToArray($data);    
  $data=recruitmentConverter($data,$this->_controller);            
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
     $arrRowData=RecruitmentModel::find((int)@$id)->toArray();                     
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
  $fullname         =   trim(@$request->fullname);
  $alias         =   trim(@$request->alias);
  $quantity         =   trim(@$request->quantity);
  $sex_id           =   trim(@$request->sex_id);  
  $description      =   trim(@$request->description);
  $requirement      =   trim(@$request->requirement);
  $meta_keyword         =   trim($request->meta_keyword);
      $meta_description     =   trim($request->meta_description);
  $work_id          =   trim(@$request->work_id);
  $literacy_id      =   trim(@$request->literacy_id);
  $experience_id    =   trim(@$request->experience_id);
  $salary_id        =   trim(@$request->salary_id);
  $commission_from  =   trim(@$request->commission_from);
  $commission_to    =   trim(@$request->commission_to);
  $working_form_id  =   trim(@$request->working_form_id);
  $probationary_id  =   trim(@$request->probationary_id);
  $benefit          =   trim(@$request->benefit);
  $job_id           =   @$request->job_id;
  $province_id      =   @$request->province_id;
  $requirement_profile          =   trim(@$request->requirement_profile);
  $duration         =   trim(@$request->duration);
  $status_new           =   trim(@$request->status_new); 
  $status_attractive           =   trim(@$request->status_attractive); 
  $status_high_salary           =   trim(@$request->status_high_salary); 
  $status_hot           =   trim(@$request->status_hot); 
  $status_quick           =   trim(@$request->status_quick); 
  $status_interested           =   trim(@$request->status_interested); 
  $status           =   trim(@$request->status); 
  $data 		            =   array();
  $item		              =   null;
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();
  if(mb_strlen(@$fullname) < 15){
    $msg["fullname"] = 'Tiêu đề phải từ 15 ký tự trở lên';    
    $checked = 0;
  }    
  if((int)@$quantity == 0){
    $msg["quantity"] = 'Số lượng cần tuyển phải lớn hơn 0';    
    $checked = 0;
  } 
  if((int)@$sex_id == 0){
    $msg["sex_id"] = 'Vui lòng chọn giới tính';    
    $checked = 0;
  }
  if(mb_strlen(@$description) == 0){
    $msg["description"] = 'Vui lòng nhập mô tả công việc';    
    $checked = 0; 
  }
  if(mb_strlen(@$requirement) == 0){
    $msg["requirement"] = 'Vui lòng nhập yêu cầu công việc';    
    $checked = 0; 
  }
  if((int)@$work_id == 0){
    $msg["work_id"] = 'Vui lòng chọn tính chất công việc';    
    $checked = 0;  
  }
  if((int)@$literacy_id == 0){
    $msg["literacy_id"] = 'Vui lòng chọn trình độ học vấn';    
    $checked = 0;   
  }
  if((int)@$experience_id == 0){
    $msg["experience_id"] = 'Vui lòng chọn kinh nghiệm';    
    $checked = 0;    
  }
  if((int)@$salary_id == 0){
    $msg["salary_id"] = 'Vui lòng chọn mức lương';    
    $checked = 0;     
  }
  if((int)@$commission_from != 0 || (int)@$commission_to != 0){
    if((int)@$commission_to <= (int)@$commission_from){
      $msg["commission_from"] = 'Mức hoa hồng không hợp lệ';    
      $checked = 0;     
    }
  }
  if((int)@$working_form_id == 0){
    $msg["working_form_id"] = 'Vui lòng chọn hình thức công việc';    
    $checked = 0;      
  }
  if((int)@$probationary_id == 0){
    $msg["probationary_id"] = 'Vui lòng chọn thời gian thử việc';    
    $checked = 0;      
  }
  if(mb_strlen(@$benefit)  == 0){
    $msg["benefit"] = 'Vui lòng nhập quyền lợi';    
    $checked = 0;      
  }
  if(count(@$job_id) == 0){
    $msg["job_id"] = 'Vui lòng chọn ngành nghề';    
    $checked = 0;      
  }else{
    if((int)@$job_id[0]==0){
      $msg["job_id"] = 'Vui lòng chọn ngành nghề';    
      $checked = 0;      
    }
  }
  if(count(@$province_id) == 0){
    $msg["province_id"] = 'Vui lòng chọn nơi làm việc';    
    $checked = 0;      
  }else{
    if((int)@$province_id[0]==0){
      $msg["province_id"] = 'Vui lòng chọn nơi làm việc';    
      $checked = 0;      
    }
  }
  if((int)@$province_id == 0){
    $msg["province_id"] = 'Vui lòng chọn nơi làm việc';    
    $checked = 0;      
  }
  if(mb_strlen(@$duration)  == 0){
    $msg["duration"] = 'Vui lòng chọn thời gian hết hạn';      
    $checked = 0;      
  }
  if((int)@$status == -1){
    $msg["status"] = 'Vui lòng chọn trạng thái hiển thị tin';    
    $checked = 0;      
  }       
  if ($checked == 1) {    
    if(empty($id)){
      $item         =   new RecruitmentModel;       
      $item->created_at   = date("Y-m-d H:i:s",time());        
    } else{
      $item       = RecruitmentModel::find((int)@$id);   
    }  
    $item->fullname           = @$fullname;   
    $item->alias            = @$alias;
    $item->quantity         = (int)@$quantity;
    $item->sex_id           = (int)@$sex_id;
    $item->description      = @$description;
    $item->meta_keyword     = @$meta_keyword;
    $item->meta_description = @$meta_description;           
    $item->requirement      = @$requirement;
    $item->work_id          = (int)@$work_id;
    $item->literacy_id      = (int)@$literacy_id;
    $item->experience_id    = (int)@$experience_id;
    $item->salary_id        = (int)@$salary_id;
    $item->commission_from  = (int)@$commission_from;
    $item->commission_to    = (int)@$commission_to;
    $item->working_form_id  = (int)@$working_form_id;
    $item->probationary_id  = (int)@$probationary_id;
    $item->benefit          = @$benefit;      
    $item->requirement_profile          = @$requirement_profile;        
    /* begin duration */
    $arrDate                = date_parse_from_format('d/m/Y',@$duration) ;
    $ts                     = mktime(@$arrDate["hour"],@$arrDate["minute"],@$arrDate["second"],@$arrDate['month'],@$arrDate['day'],@$arrDate['year']);
    $real_date                 = date('Y-m-d', $ts);
    /* end duration */
    $item->duration         = @$real_date;           
    $item->status_new           = (int)@$status_new; 
    $item->status_attractive           = (int)@$status_attractive; 
    $item->status_high_salary           = (int)@$status_high_salary; 
    $item->status_hot           = (int)@$status_hot; 
    $item->status_quick           = (int)@$status_quick; 
    $item->status_interested           = (int)@$status_interested; 
    $item->status           = (int)@$status;    
    $item->updated_at       = date("Y-m-d H:i:s",time());           
    $item->save();     

    $source_recruitment_job=RecruitmentJobModel::whereRaw('recruitment_id = ?',[(int)@$item->id])->select('job_id')->get()->toArray();
    $source_recruitment_place=RecruitmentPlaceModel::whereRaw('recruitment_id = ?',[(int)@$item->id])->select('province_id')->get()->toArray();
    $source_job_id=array();
    $source_province_id=array();
    if(count($source_recruitment_job) > 0){
      foreach ($source_recruitment_job as $key => $value) {
        $source_job_id[]=$value['job_id'];
      }
    }
    if(count($source_recruitment_place) > 0){
      foreach ($source_recruitment_place as $key => $value) {
        $source_province_id[]=$value['province_id'];
      }
    }  
    sort($source_job_id);
    sort($source_province_id);
    sort($job_id);
    sort($province_id);
    $compare_job=0;
    $compare_province=0;
    if($source_job_id == $job_id){
      $compare_job=1;       
    }    
    if($source_province_id == $province_id){
      $compare_province=1;       
    }
    if($compare_job == 0){
      RecruitmentJobModel::whereRaw("recruitment_id = ?",[(int)@$item->id])->delete();   
      foreach ($job_id as $key => $value) {
        $item2=new RecruitmentJobModel;
        $item2->recruitment_id = (int)@$item->id;
        $item2->job_id         = (int)@$value;
        $item2->save();
      }
    }
    if($compare_province == 0){
      RecruitmentPlaceModel::whereRaw("recruitment_id = ?",[(int)@$item->id])->delete();  
      foreach ($province_id as $key => $value) {
        $item3=new RecruitmentPlaceModel;
        $item3->recruitment_id = (int)@$item->id;
        $item3->province_id         = (int)@$value;
        $item3->save();
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
public function changeStatus(Request $request){
  $id             =       (int)@$request->id;     
  $info                 =   array();
  $checked              =   1;                           
  $msg                =   array();    
  $status         =       (int)@$request->status;
  $item           =       RecruitmentModel::find((int)@$id);        
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
    $item = RecruitmentModel::find((int)@$id);
    $item->delete();                      
    RecruitmentJobModel::whereRaw('recruitment_id = ?',[@$id])->delete();  
    RecruitmentPlaceModel::whereRaw('recruitment_id = ?',[@$id])->delete();                          
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
    DB::table('recruitment')->whereIn('id',@$arrID)->delete();   
    DB::table('recruitment_job')->whereIn('recruitment_id',@$arrID)->delete();   
    DB::table('recruitment_place')->whereIn('recruitment_id',@$arrID)->delete();   
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

   $msg["fullname"] = "Thiếu tên";
 }else{          
  $alias=str_slug($fullname,'-');          
  $dataScale=array();
  $checked_trung_alias=0;          
  if (empty($id)) {              
    $dataScale=RecruitmentModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();             
  }else{
    $dataScale=RecruitmentModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
  }  

  if (count($dataScale) > 0) {
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
