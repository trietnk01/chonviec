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
use App\ProvinceModel;
use App\EmployerModel;
use App\CandidateModel;
use DB;
class ProvinceController extends Controller {
  	var $_controller="province";	
  	var $_title="Tỉnh thành phố";
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
        $data=DB::table('province')                  
                ->select('province.id','province.fullname','province.sort_order','province.status','province.created_at','province.updated_at')                
                ->where('province.fullname','like','%'.trim(mb_strtolower($filter_search,'UTF-8')).'%')                     
                ->groupBy('province.id','province.fullname','province.sort_order','province.status','province.created_at','province.updated_at')   
                ->orderBy('province.id', 'asc')                
                ->get()->toArray();              
        $data=convertToArray($data);    
        $data=provinceConverter($data,$this->_controller);            
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
              $arrRowData=ProvinceModel::find((int)@$id)->toArray();                     
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
          $alias                =   trim($request->alias);                          
          $sort_order           =   trim($request->sort_order);
          $status               =   trim($request->status);          
          $data 		            =   array();
          
          
          $item		              =   null;
          $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
          if(empty($fullname)){
                 $checked = 0;
                 
                 $msg["fullname"] = "Thiếu tên";
          }                   
          if(empty($sort_order)){
             $checked = 0;
             
             $msg["sort_order"] 		= "Thiếu sắp xếp";
          }
          if((int)$status==-1){
             $checked = 0;
             
             $msg["status"] 			= "Thiếu trạng thái";
          }
          if ($checked == 1) {    
                if(empty($id)){
                    $item         =   new ProvinceModel;       
                    $item->created_at   = date("Y-m-d H:i:s",time());        
                      
                } else{
                    $item       = ProvinceModel::find((int)@$id);   
                                  
                }  
                $item->fullname 		    =	@$fullname;  
                $item->alias            = @$alias;                                         
                $item->sort_order 		  =	(int)@$sort_order;
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
                  $item           =       ProvinceModel::find((int)@$id);        
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
            $data                   =   EmployerModel::whereRaw("province_id = ?",[(int)@$id])->get()->toArray();  
            if(count($data) > 0){
              $checked            =   0;
         
          $msg['cannotdelete']            =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }
            $data                   =   CandidateModel::whereRaw("province_id = ?",[(int)@$id])->get()->toArray();  
            if(count($data) > 0){
              $checked            =   0;
        
          $msg['cannotdelete']            =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }                  
            if($checked == 1){
              $item = ProvinceModel::find((int)@$id);
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
              $item=ProvinceModel::find($value);
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
        foreach ($arrID as $key => $value){
          $data                   =   EmployerModel::whereRaw("province_id = ?",[(int)@$value])->get()->toArray();  
            if(count($data) > 0){
              $checked            =   0;
         
          $msg['cannotdelete']            =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }
        } 
        foreach ($arrID as $key => $value){
          $data                   =   CandidateModel::whereRaw("province_id = ?",[(int)@$value])->get()->toArray();  
            if(count($data) > 0){
              $checked            =   0;
      
          $msg['cannotdelete']            =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }
        }              
        if($checked == 1){                                  

          DB::table('province')->whereIn('id',@$arrID)->delete();          
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
                  $item=ProvinceModel::find((int)@$value->id);                
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
          $dataCategoryArticle=array();
          $dataCategoryProduct=array();
          $dataArticle=array();
          $dataProduct=array();
          $dataPage=array();
          $dataProject=array();
          $dataProjectArticle=array();
          $dataProvince=array();
          $checked_trung_alias=0;          
          if (empty($id)) {              
              $dataProvince=ProvinceModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();             
            }else{
              $dataProvince=ProvinceModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
            }  
            $dataCategoryArticle=CategoryArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataCategoryProduct=CategoryProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataProduct=ProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataPage=PageModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataProject=ProjectModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataProjectArticle=ProjectArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
          if (count($dataCategoryArticle) > 0) {
            $checked_trung_alias=1;
          }
          if (count($dataCategoryProduct) > 0) {
            $checked_trung_alias=1;
          }
          if (count($dataArticle) > 0) {
            $checked_trung_alias=1;
          }
          if (count($dataProduct) > 0) {
            $checked_trung_alias=1;
          }    
          if (count($dataPage) > 0) {
            $checked_trung_alias=1;
          }  
          if (count($dataProject) > 0) {
            $checked_trung_alias=1;
          }  
          if (count($dataProjectArticle) > 0) {
            $checked_trung_alias=1;
          }      
           if (count($dataProvince) > 0) {
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
