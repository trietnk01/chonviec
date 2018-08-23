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

use DB;
class ProjectArticleController extends Controller {
  	var $_controller="project-article";	
  	var $_title="Tin tức dự án";
  	var $_icon="icon-settings font-dark";    
  	public function getList(){   
        $controller=$this->_controller; 
        $task="list";
        $title=$this->_title;
        $icon=$this->_icon;   
        $arrProject=ProjectModel::select("id","fullname")->orderBy("sort_order","asc")->get()->toArray();                    
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-list";         
        if(in_array($requestControllerAction,$arrPrivilege)){
          return view("adminsystem.".$this->_controller.".list",compact("controller","task","title","icon","arrProject")); 
        }
        else{
          return view("adminsystem.no-access",compact('controller'));
        }
    }     
    public function loadData(Request $request){      
      $query=DB::table('project_article')
      ->join('project','project_article.project_id','=','project.id')  ;      
      if(!empty(@$request->filter_search)){
        $query->where('project_article.fullname','like','%'.trim(@$request->filter_search).'%');
      }     
      if(!empty(@$request->project_id)){
        $query->where('project_article.project_id','=',(int)@$request->project_id);
      }   
      $data=$query->select('project_article.id','project_article.fullname','project.fullname as project_name','project_article.image','project_article.sort_order','project_article.status','project_article.created_at','project_article.updated_at')
      ->groupBy('project_article.id','project_article.fullname','project.fullname','project_article.image','project_article.sort_order','project_article.status','project_article.created_at','project_article.updated_at')
      ->orderBy('project_article.sort_order', 'asc')
      ->get()->toArray();      
      $data=convertToArray($data);    
      $data=projectArticleConverter($data,$this->_controller);            
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
         $arrRowData=ProjectArticleModel::find((int)@$id)->toArray();                     
         break;
         case 'add':
         $title=$this->_title . " : Add new";
         break;     
       }    
       $arrProject=ProjectModel::select("id","fullname")->orderBy("sort_order","asc")->get()->toArray();              
       $arrDistrict=DistrictModel::select("id","fullname")->orderBy("sort_order","asc")->get()->toArray();                        
       return view("adminsystem.".$this->_controller.".form",compact("arrProject","arrRowData","controller","task","title","icon"));
     }else{
      return view("adminsystem.no-access",compact('controller'));
    }        
  }
     public function save(Request $request){
          $id 					        =		trim($request->id);        
          $fullname 				    =		trim($request->fullname);
          
          $alias 					      = 	trim($request->alias);          
          $image                =   trim($request->image);
          $image_hidden         =   trim($request->image_hidden);
          $intro                =   trim($request->intro);
          $content              =   trim($request->content);          
          $description          =   trim($request->description);
          $meta_keyword         =   trim($request->meta_keyword);
          $meta_description     =   trim($request->meta_description);
          $sort_order           =   trim($request->sort_order);
          $status               =   trim($request->status);
          $project_id	=		@$request->project_id;                 
          $data 		            =   array();
          $info 		            =   array();
          $msg 		            =   array();
          $item		              =   null;
          $checked 	            =   1;              
          if(empty($fullname)){
                 $checked = 0;
                 $msg["fullname"]["type_msg"] = "has-error";
                 $msg["fullname"]["msg"] = "Thiếu tên bài viết";
          }else{
              $data=array();
              if (empty($id)) {
                $data=ProjectArticleModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
              }else{
                $data=ProjectArticleModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
              }  
              if (count($data) > 0) {
                  $checked = 0;
                  $msg["fullname"]["type_msg"] = "has-error";
                  $msg["fullname"]["msg"] = "Bài viết đã tồn tại";
              }      	
          }          
          
          if((int)@$project_id == 0){
              $checked = 0;
              $msg["project_id"]["type_msg"]   = "has-error";
              $msg["project_id"]["msg"]      = "Thiếu danh mục";
          }          
          if(empty($sort_order)){
             $checked = 0;
             $msg["sort_order"]["type_msg"] 	= "has-error";
             $msg["sort_order"]["msg"] 		= "Thiếu sắp xếp";
          }
          if((int)$status==-1){
             $checked = 0;
             $msg["status"]["type_msg"] 		= "has-error";
             $msg["status"]["msg"] 			= "Thiếu trạng thái";
          }
          if ($checked == 1) {    
                if(empty($id)){
                    $item 				= 	new ProjectArticleModel;       
                    $item->created_at 	=	date("Y-m-d H:i:s",time());        
                    if(!empty($image)){
                      $item->image    =   trim($image) ;  
                    }				
                } else{
                    $item				=	ProjectArticleModel::find((int)@$id);   
                    $item->image=null;                       
                    if(!empty($image_hidden)){
                      $item->image =$image_hidden;          
                    }
                    if(!empty($image))  {
                      $item->image=$image;                                                
                    }                    
                }  
                $item->fullname 		    =	$fullname;
                
                $item->alias 			      =	$alias;
                $item->intro            = $intro;
                $item->content          = $content;
                $item->project_id       = (int)@$project_id;
                $item->description      = $description;
                $item->meta_keyword     = $meta_keyword;
                $item->meta_description = $meta_description;           
                $item->sort_order 		  =	(int)$sort_order;
                $item->status 			    =	(int)$status;    
                $item->updated_at 		  =	date("Y-m-d H:i:s",time());    	        	
                $item->save();                  
                $info = array(
                  'type_msg' 			=> "has-success",
                  'msg' 				=> 'Lưu dữ liệu thành công',
                  "checked" 			=> 1,
                  "error" 			=> $msg,
                  "id"    			=> $id
                );
            }else {
                    $info = array(
                      'type_msg' 			=> "has-error",
                      'msg' 				=> 'Lưu dữ liệu thất bại',
                      "checked" 			=> 0,
                      "error" 			=> $msg,
                      "id"				=> ""
                    );
            }        		 			       
            return $info;       
    }
          public function changeStatus(Request $request){
                  $id             =       (int)$request->id;     
                  $checked                =   1;
                  $type_msg               =   "alert-success";
                  $msg                    =   "Cập nhật thành công";              
                  $status         =       (int)$request->status;
                  $item           =       ProjectArticleModel::find((int)@$id);        
                  $item->status   =       $status;
                  $item->save();
                  $data                   =   $this->loadData($request);
                  $info = array(
                    'checked'           => $checked,
                    'type_msg'          => $type_msg,                
                    'msg'               => $msg,                
                    'data'              => $data
                  );
                  return $info;
          }
        
      public function deleteItem(Request $request){
            $id                     =   (int)$request->id;              
            $checked                =   1;
            $type_msg               =   "alert-success";
            $msg                    =   "Xóa thành công";                    
            if($checked == 1){
              $item = ProjectArticleModel::find((int)@$id);
                $item->delete();                
            }        
            $data                   =   $this->loadData($request);
            $info = array(
              'checked'           => $checked,
              'type_msg'          => $type_msg,                
              'msg'               => $msg,                
              'data'              => $data
            );
            return $info;
      }
      public function updateStatus(Request $request){
          $strID                 =   $request->str_id;     
        $status                 =   $request->status;            
        $checked                =   1;
        $type_msg               =   "alert-success";
        $msg                    =   "Cập nhật thành công";                  
        $strID=substr($strID, 0,strlen($strID) - 1);
        $arrID=explode(',',$strID);                 
        if(empty($strID)){
                    $checked                =   0;
                    $type_msg               =   "alert-warning";            
                    $msg                    =   "Vui lòng chọn ít nhất một phần tử";
          }
          if($checked==1){
              foreach ($arrID as $key => $value) {
                if(!empty($value)){
                    $item=ProjectArticleModel::find($value);
                    $item->status=$status;
                    $item->save();      
                }            
              }
          }                 
          $data                   =   $this->loadData($request);
          $info = array(
            'checked'           => $checked,
            'type_msg'          => $type_msg,                
            'msg'               => $msg,                
            'data'              => $data
          );
          return $info;
      }
      public function trash(Request $request){
            $strID                 =   $request->str_id;               
            $checked                =   1;
            $type_msg               =   "alert-success";
            $msg                    =   "Xóa thành công";                  
            $strID=substr($strID, 0,strlen($strID) - 1);
            $arrID=explode(',',$strID);                 
            if(empty($strID)){
              $checked     =   0;
              $type_msg           =   "alert-warning";            
              $msg                =   "Vui lòng chọn ít nhất một phần tử để xóa";
            }
            if($checked == 1){                                   
                  DB::table('project_article')->whereIn('id',@$arrID)->delete();                
            }
            $data                   =   $this->loadData($request);
            $info = array(
              'checked'           => $checked,
              'type_msg'          => $type_msg,                
              'msg'               => $msg,                
              'data'              => $data
            );
            return $info;
      }
      public function sortOrder(Request $request){
            $sort_json              =   $request->sort_json;           
            $data_order             =   json_decode($sort_json);       
          
            $checked                =   1;
            $type_msg               =   "alert-success";
            $msg                    =   "Cập nhật thành công";      
            if(count($data_order) > 0){              
              foreach($data_order as $key => $value){      
                if(!empty($value)){
                  $item=ProjectArticleModel::find((int)@$value->id);                
                $item->sort_order=(int)$value->sort_order;                         
                $item->save();                      
                }                                                  
              }           
            }        
            $data                   =   $this->loadData($request);
            $info = array(
              'checked'           => $checked,
              'type_msg'          => $type_msg,                
              'msg'               => $msg,                
              'data'              => $data
            );
            return $info;
      }

        public function createAlias(Request $request){
          $id                =  trim($request->id)  ; 
          $fullname                =  trim($request->fullname)  ;        
          $data                    =  array();
          $info                    =  array();
          $msg                   =  array();
          $item                    =  null;
          $checked  = 1;   
          $alias='';                     
          if(empty($fullname)){
           $checked = 0;
           $msg["fullname"]["type_msg"] = "has-error";
           $msg["fullname"]["msg"] = "Thiếu tên bài viết";
         }else{
          $alias=str_slug($fullname,'-');
          $dataCategoryArticle=array();
        $dataCategoryProduct=array();
        $dataArticle=array();
        $dataProduct=array();
        $dataPage=array();
        $dataProject=array();
        $dataProjectArticle=array();
          $checked_trung_alias=0;          
          if (empty($id)) {              
              $dataProjectArticle=ProjectArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();             
            }else{
              $dataProjectArticle=ProjectArticleModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
            }  
            $dataCategoryArticle=CategoryArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataCategoryProduct=CategoryProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataArticle=ArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataProduct=ProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataPage=PageModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            $dataProject=ProjectModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
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
          if((int)$checked_trung_alias == 1){
            $code_alias=rand(1,999999);
            $alias=$alias.'-'.$code_alias;
          }
        }
        if ($checked == 1){
          $info = array(
            'type_msg'      => "has-success",
            'msg'         => 'Lưu dữ liệu thành công',
            "checked"       => 1,
            "error"       => $msg,
            
            "alias"       =>$alias
          );
        }else {
          $info = array(
            'type_msg'      => "has-error",
            'msg'         => 'Nhập dữ liệu có sự cố',
            "checked"       => 0,
            "error"       => $msg,
            "alias"        => $alias
          );
        }    
        return $info;
      }
      
}
?>
