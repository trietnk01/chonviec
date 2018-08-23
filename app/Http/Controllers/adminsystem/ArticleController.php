<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CategoryArticleModel;
use App\CategoryProductModel;
use App\ArticleModel;
use App\ProductModel;
use App\PageModel;
use App\ProjectModel;
use App\ProjectArticleModel;
use App\MenuModel;
use App\ArticleCategoryModel;
use DB;
class ArticleController extends Controller {
  	var $_controller="article";	
  	var $_title="Bài viết";
  	var $_icon="icon-settings font-dark";    
  	public function getList(){		
    		$controller=$this->_controller;	
    		$task="list";
    		$title=$this->_title;
    		$icon=$this->_icon;		
        $arrCategoryArticle=CategoryArticleModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
        $arrCategoryArticleRecursive=array();              
        categoryRecursiveForm($arrCategoryArticle ,0,"",$arrCategoryArticleRecursive)  ;            
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-list";         
        if(in_array($requestControllerAction,$arrPrivilege)){
          return view("adminsystem.".$this->_controller.".list",compact("controller","task","title","icon","arrCategoryArticleRecursive")); 
        }
        else{
          return view("adminsystem.no-access",compact('controller'));
        }
  	}	    
  	public function loadData(Request $request){      
      $category_id=(int)@$request->category_id;
        $arrCategoryID[]=@$category_id;
        getStringCategoryID($category_id,$arrCategoryID,'category_article');     
      $query=DB::table('article')
      ->leftJoin('article_category','article.id','=','article_category.article_id')
      ->leftJoin('category_article','category_article.id','=','article_category.category_id')  ;      
      if(!empty(@$request->filter_search)){
        $query->where('article.fullname','like','%'.trim(@$request->filter_search).'%');
      }     
      if(count($arrCategoryID) > 0){
        $query->whereIn('article_category.category_id',$arrCategoryID);
      }   
      $data=$query->select('article.id','article.fullname','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
                  ->groupBy('article.id','article.fullname','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
                  ->orderBy('article.sort_order', 'desc')
                  ->get()
                  ->toArray();      
      $data=convertToArray($data);    
      $data=articleConverter($data,$this->_controller);            
      return $data;
    } 
    public function getForm($task,$id=""){     
        $controller=$this->_controller;     
        $title="";
        $icon=$this->_icon; 
        $arrRowData=array();
        $arrArticleCategory=array();
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-form";  
        if(in_array($requestControllerAction, $arrPrivilege)){
          switch ($task) {
           case 'edit':
              $title=$this->_title . " : Update";
              $arrRowData=ArticleModel::find((int)@$id)->toArray();       
              $arrArticleCategory=ArticleCategoryModel::whereRaw("article_id = ?",[(int)@$id])->get()->toArray();
           break;
           case 'add':
              $title=$this->_title . " : Add new";
           break;     
        }    
        $arrCategoryArticle=CategoryArticleModel::select("id","fullname","alias","parent_id","image","sort_order","status","created_at","updated_at")->orderBy("sort_order","asc")->get()->toArray();        
        $arrCategoryArticleRecursive=array();
        categoryRecursiveForm($arrCategoryArticle ,0,"",$arrCategoryArticleRecursive)   ;      
        return view("adminsystem.".$this->_controller.".form",compact("arrCategoryArticleRecursive","arrRowData","arrArticleCategory","controller","task","title","icon"));
        }else{
          return view("adminsystem.no-access",compact('controller'));
        }        
    }
    public function save(Request $request){
    	$id 					        =		trim($request->id);        
    	$fullname 				    =		trim($request->fullname);          
    	$alias 					      = 	trim($request->alias);
    	$alias_menu           =   trim($request->alias_menu); 
    	$image_file           =   null;
    	if(isset($_FILES["image"])){
    		$image_file         =   $_FILES["image"];
    	}                   
    	$image_hidden         =   trim($request->image_hidden);
      $alt_image                =   trim($request->alt_image);
    	$intro                =   trim($request->intro);
    	$content              =   trim($request->content);          
    	$description          =   trim($request->description);
    	$meta_keyword         =   trim($request->meta_keyword);
    	$meta_description     =   trim($request->meta_description);
    	$sort_order           =   trim($request->sort_order);
    	$status               =   trim($request->status);
    	$category_id	        =		$request->category_id;             
    	$data 		            =   array();

    	$item		              =   null;                
    	$info                 =   array();
    	$checked              =   1;                           
    	$msg                =   array();
    	$setting= getSettingSystem();
    	$width=$setting['article_width']['field_value'];
    	$height=$setting['article_height']['field_value'];            
    	if(empty($fullname)){
    		$checked = 0;                 
    		$msg["fullname"] = "Thiếu tên bài viết";
    	}else{
    		$data=array();
    		if (empty($id)) {
    			$data=ArticleModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
    		}else{
    			$data=ArticleModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
    		}  
    		if (count($data) > 0) {
    			$checked = 0;                  
    			$msg["fullname"] = "Bài viết đã tồn tại";
    		}      	
    	}              
    	if( ($category_id) == null ){    		
    		$checked = 0;                
    		$msg["category_id"]      = "Thiếu danh mục";
    	}else{
        if((int)$category_id[0]==0){
          $checked = 0;                
          $msg["category_id"]      = "Thiếu danh mục";
        }
      }    
      /* begin checkfilesize */      
      $file_size=0;
      if($image_file != null){        
        $file_size=((int)@$image_file['size'])/1024/1024;
        if($file_size > (int)max_size_upload ){
          $checked = 0;               
          $msg["status"]      = "Vui lòng nhập hình ảnh dưới 2MB";
        }
      }
      /* end checkfilesize */
    	if(empty($sort_order)){
    		$checked = 0;               
    		$msg["sort_order"] 		= "Thiếu sắp xếp";
    	}
    	if((int)$status==-1){
    		$checked = 0;               
    		$msg["status"] 			= "Thiếu trạng thái";
    	}
      
    	if ($checked == 1) {    
    		$image_name='';
    		if($image_file != null){                                                
    			$image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);
    		}
    		if(empty($id)){
    			$item 				= 	new ArticleModel;       
    			$item->created_at 	=	date("Y-m-d H:i:s",time());        
    			if(!empty($image_name)){
    				$item->image    =   trim($image_name) ;  
    			}				
    		} else{
    			$item				=	ArticleModel::find((int)@$id);   
    			$item->image=null;                       
    			if(!empty($image_hidden)){
    				$item->image =$image_hidden;          
    			}
    			if(!empty($image_name))  {
    				$item->image=$image_name;                                                
    			}                    
    		}  
    		$item->fullname 		    =	$fullname;

    		$item->alias 			      =	$alias;
    		$item->intro            = $intro;
    		$item->content          = $content;
        $item->alt_image          = $alt_image;
    		$item->description      = $description;
    		$item->meta_keyword     = $meta_keyword;
    		$item->meta_description = $meta_description;           
    		$item->sort_order 		  =	(int)$sort_order;
    		$item->status 			    =	(int)$status;    
    		$item->updated_at 		  =	date("Y-m-d H:i:s",time());    	        	
    		$item->save();  
    		$dataMenu=MenuModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias_menu,'UTF-8'))])->get()->toArray();
    		if(count($dataMenu) > 0){
    			foreach ($dataMenu as $key => $value) {                   
    				$menu_id=(int)$value['id'];
    				$sql = "update  `menu` set `alias` = '".$alias."' WHERE `id` = ".$menu_id;           
    				DB::statement($sql);    
    			}          
    		} 
    		if(!empty($category_id)){ 
    			$arr_category_id=explode(',', $category_id);                           
    			$arrArticleCategory=ArticleCategoryModel::whereRaw("article_id = ?",[(int)@$item->id])->select("category_id")->get()->toArray();
    			$arrCategoryArticleID=array();
    			foreach ($arrArticleCategory as $key => $value) {
    				$arrCategoryArticleID[]=$value["category_id"];
    			}
    			$selected=@$arr_category_id;
    			sort($selected);
    			sort($arrCategoryArticleID);         
    			$resultCompare=0;
    			if($selected == $arrCategoryArticleID){
    				$resultCompare=1;       
    			}
    			if($resultCompare==0){
    				ArticleCategoryModel::whereRaw("article_id = ?",[(int)@$item->id])->delete();  
    				foreach ($selected as $key => $value) {
    					$category_id=$value;
    					$articleCategory=new ArticleCategoryModel;
    					$articleCategory->article_id=(int)@$item->id;
    					$articleCategory->category_id=(int)@$category_id;            
    					$articleCategory->save();
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
          public function changeStatus(Request $request){
                  $id             =       (int)$request->id;     
                  $info                 =   array();
    	$checked              =   1;                           
    	$msg                =   array();
                  $status         =       (int)$request->status;
                  $item           =       ArticleModel::find((int)@$id);        
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
              $item = ArticleModel::find((int)@$id);
                $item->delete();
                ArticleCategoryModel::whereRaw("article_id = ?",[(int)$id])->delete();
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
              $item=ArticleModel::find($value);
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
                  DB::table('article')->whereIn('id',@$arrID)->delete();   
                  DB::table('article_category')->whereIn('article_id',@$arrID)->delete();         
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
      public function sortOrder(Request $request){
        $sort_json              =   $request->sort_json;           
        $data_order             =   json_decode($sort_json);       

        $info                 =   array();
    	$checked              =   1;                           
    	$msg                =   array();
        if(count($data_order) > 0){              
          foreach($data_order as $key => $value){      
            if(!empty($value)){
              $item=ArticleModel::find((int)@$value->id);                
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

      public function createAlias(Request $request){
        $id                =  trim($request->id)  ; 
        $fullname                =  trim($request->fullname)  ;        
        $data                    =  array();
        $info                 =   array();
    	$checked              =   1;                           
    	$msg                =   array();
        $item                    =  null;          
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
        $checked_trung_alias=0;          
        if (empty($id)) {              
          $dataArticle=ArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();             
        }else{
          $dataArticle=ArticleModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
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
