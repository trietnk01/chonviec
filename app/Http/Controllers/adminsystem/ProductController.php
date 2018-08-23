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
use App\ProductCategoryModel;
use App\PostParamModel;
use App\CategoryParamModel;
use App\InvoiceDetailModel;
use DB;
use Sentinel;
class ProductController extends Controller {
  	var $_controller="product";	
  	var $_title="Sản phẩm";
  	var $_icon="icon-settings font-dark";
  	public function getList(){		
    		$controller=$this->_controller;	
    		$task="list";
    		$title=$this->_title;
    		$icon=$this->_icon;		
        $arrCategoryProduct=CategoryProductModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
        $arrCategoryProductRecursive=array();              
        categoryRecursiveForm($arrCategoryProduct ,0,"",$arrCategoryProductRecursive)  ;      
    		
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-list";         
        if(in_array($requestControllerAction,$arrPrivilege)){
          return view("adminsystem.".$this->_controller.".list",compact("controller","task","title","icon","arrCategoryProductRecursive")); 
        }
        else{
          return view("adminsystem.no-access",compact('controller'));
        }
  	}	
  	public function loadData(Request $request){    
      $category_id=(int)@$request->category_id;
        $arrCategoryID[]=@$category_id;
        getStringCategoryID($category_id,$arrCategoryID,'category_product');        
      $query=DB::table('product')
      ->join('category_product','product.category_id','=','category_product.id')  ;      
      if(!empty(@$request->filter_search)){
        $query->where('product.fullname','like','%'.trim(@$request->filter_search).'%');
      }     
      if(count($arrCategoryID)){
        $query->whereIn('product.category_id',$arrCategoryID);
      }   
      $data=$query->select('product.id','product.code','product.fullname','product.alias','product.image','category_product.fullname as category_name','product.sort_order','product.status','product.created_at','product.updated_at')
                  ->groupBy('product.id','product.code','product.fullname','product.alias','product.image','category_product.fullname','product.sort_order','product.status','product.created_at','product.updated_at')
                  ->orderBy('product.sort_order', 'asc')
                  ->get()
                  ->toArray();      
      $data=convertToArray($data);    
      $data=productConverter($data,$this->_controller);            
      return $data;
    } 
    public function getForm($task,$id=""){     
        $controller=$this->_controller;     
        $title="";
        $icon=$this->_icon; 
        $arrRowData=array();        
        $arrPostParam=array();
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-form";  
        if(in_array($requestControllerAction, $arrPrivilege)){
          switch ($task) {
           case 'edit':
              $title=$this->_title . " : Update";
              $arrRowData=ProductModel::find((int)@$id)->toArray();       
              $arrPostParam=PostParamModel::whereRaw("post_id = ?",[(int)@$id])->get()->toArray();                  
           break;
           case 'add':
              $title=$this->_title . " : Add new";
           break;     
        }    
        $arrCategoryProduct=CategoryProductModel::select("id","fullname","alias","parent_id")->orderBy("sort_order","asc")->get()->toArray();       
        $arrCategoryParam=CategoryParamModel::select("id","fullname","alias","parent_id")->orderBy("sort_order","asc")->get()->toArray(); 
        $arrCategoryProductRecursive=array();
        $arrCategoryParamRecursive=array();
        categoryRecursiveForm($arrCategoryProduct ,0,"",$arrCategoryProductRecursive)   ; 
        categoryRecursiveForm($arrCategoryParam ,0,"",$arrCategoryParamRecursive)   ; 
        return view("adminsystem.".$this->_controller.".form",compact("arrCategoryProductRecursive","arrCategoryParamRecursive","arrPostParam","arrRowData","controller","task","title","icon"));
        }else{
            return view("adminsystem.no-access",compact('controller'));
        }
        
    }
        public function save(Request $request){
            $id 					        =		trim($request->id);      
            $code                 =   randomCodeNumber(); 
            $fullname 				    =		trim($request->fullname);          
            $alias                =   trim($request->alias);
            $alias_menu           =   trim($request->alias_menu);
            
            $meta_keyword         =   trim($request->meta_keyword);
            $meta_description     =   trim($request->meta_description);
            $image_file           =   null;
            $source_image_child=array();
            $source_image_child_hidden=array();
            if(isset($_FILES["image"])){
              $image_file         =   $_FILES["image"];
            }                        
            if(isset($_FILES['source_image_child'])){
              $source_image_child=$_FILES['source_image_child'];
            }                            
            if(isset($request->source_image_child_hidden)){
              $source_image_child_hidden=$request->source_image_child_hidden;                        
            }            
            $status               =   trim($request->status);
            $price                =   trim($request->price);   
            $sale_price           =   trim($request->sale_price);                    
            $detail               =   trim($request->detail);
            $technical_detail               =   trim($request->technical_detail);
            $video_id = trim($request->video_id);
            $intro                =   trim($request->intro);
            $image_hidden         =   trim($request->image_hidden);                       
            $sort_order           =   trim($request->sort_order);          
            $category_id	        =		trim($request->category_id);             
            $category_param_id    =   ($request->category_param_id);            
            $data 		            =   array();
            $info 		            =   array();
            $msg 		            =   array();
            $item		              =   null;
            $checked 	            =   1;   
            $setting= getSettingSystem();
                $width=$setting['product_width']['field_value'];
                $height=$setting['product_height']['field_value'];           
            if(empty($code)){
                 $checked = 0;
                 $msg["code"]["type_msg"] = "has-error";
                 $msg["code"]["msg"] = "Thiếu mã sản phẩm";
            }else{
                $data=array();
                if (empty($id)) {
                  $data=ProductModel::whereRaw("trim(lower(code)) = ?",[trim(mb_strtolower($code,'UTF-8'))])->get()->toArray();           
                }else{
                  $data=ProductModel::whereRaw("trim(lower(code)) = ? and id != ?",[trim(mb_strtolower($code,'UTF-8')),(int)@$id])->get()->toArray();   
                }  
                if (count($data) > 0) {
                  $checked = 0;
                  $msg["code"]["type_msg"] = "has-error";
                  $msg["code"]["msg"] = "Mã sản phẩm đã tồn tại";
                }       
            }      
            if(empty($fullname)){
               $checked = 0;
               $msg["fullname"]["type_msg"] = "has-error";
               $msg["fullname"]["msg"] = "Thiếu tên sản phẩm";
           }else{
                $data=array();
                if (empty($id)) {
                  $data=ProductModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
                }else{
                  $data=ProductModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
                }  
                if (count($data) > 0) {
                  $checked = 0;
                  $msg["fullname"]["type_msg"] = "has-error";
                  $msg["fullname"]["msg"] = "Tên sản phẩm đã tồn tại";
                }      	
            }          
      
      if(empty($category_id)){
        $checked = 0;
        $msg["category_id"]["type_msg"]   = "has-error";
        $msg["category_id"]["msg"]      = "Thiếu danh mục";
      }      
      if(empty($sort_order)){
           $checked = 0;
           $msg["sort_order"]["type_msg"] 	= "has-error";
           $msg["sort_order"]["msg"] 		= "Sort order is required";
      }
       if((int)$status==-1){
           $checked = 0;
           $msg["status"]["type_msg"] 		= "has-error";
           $msg["status"]["msg"] 			= "Status is required";
       }
      if ($checked == 1) {  
          $image_name='';
              if($image_file != null){                                           
                $image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);                                  
              }  
          if(empty($id)){
                $item 				= 	new ProductModel; 
                $item->code             = $code;
                if(!empty($image_name)){
                  $item->image    =   trim($image_name) ;  
                }  
                
                $item->created_at 	=	date("Y-m-d H:i:s",time());                        		
          } else{
                $item				=	ProductModel::find((int)@$id);   
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
          $item->meta_keyword     = $meta_keyword;
          $item->meta_description = $meta_description;                  
          $item->status           = (int)@$status; 
          $item->price            = (int)(str_replace('.', '',@$price)) ;
          $item->sale_price       = (int)(str_replace('.', '',@$sale_price)) ;                                 
          $item->detail           = $detail;  
          $item->technical_detail           = $technical_detail;    
          $item->video_id = $video_id;   
          $item->intro            = $intro;            
          $item->category_id      = (int)@$category_id;                                      
          $item->sort_order 	    =	(int)@$sort_order;                
          $item->updated_at 	    =	date("Y-m-d H:i:s",time());  
          // begin upload product child image 
          $data_image_child=array();
          if(count($source_image_child_hidden) > 0){
            $data_image_child=$source_image_child_hidden;
          }
          if(count($source_image_child) > 0){
            foreach ($source_image_child['name'] as $key => $value) {
              $item_name=uploadImage($value,$source_image_child['tmp_name'][$key],$width,$height);
              $data_image_child[]=$item_name;
            }
          }
          $item->child_image=null;
          if(count($data_image_child) > 0){
            $item->child_image=json_encode($data_image_child);  
          }          
          // end upload product child image  	        	
          $item->save();  	
          $dataMenu=MenuModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias_menu,'UTF-8'))])->get()->toArray();
          if(count($dataMenu) > 0){
            foreach ($dataMenu as $key => $value) {                   
              $menu_id=(int)$value['id'];
              $sql = "update  `menu` set `alias` = '".$alias."' WHERE `id` = ".$menu_id;           
                DB::statement($sql);    
            }          
          }    
          /* begin category param */
                if(!empty(@$category_param_id)){
                  $source_category_param_id=explode(',', $category_param_id) ; 
                  $arrPostParam=PostParamModel::whereRaw("post_id = ?",[(int)@$item->id])->select("param_id")->get()->toArray();
                  $arrCategoryParamID=array();
                  foreach ($arrPostParam as $key => $value) {
                    $arrCategoryParamID[]=$value["param_id"];
                  }                  
                  $selected=@$source_category_param_id;
                  sort($selected);
                  sort($arrCategoryParamID);                           
                  $resultCompare=0;
                  if($selected == $arrCategoryParamID){
                    $resultCompare=1;       
                  }
                  if($resultCompare==0){
                    PostParamModel::whereRaw("post_id = ?",[(int)@$item->id])->delete();  
                    foreach ($selected as $key => $value) {
                      $param_id=$value;
                      $postParam=new PostParamModel;
                      $postParam->post_id=(int)@$item->id;
                      $postParam->param_id=(int)@$param_id;            
                      $postParam->save();
                    }
                  }       
                }  
                PostParamModel::whereRaw("param_id = ?",[0])->delete();
                /* end category param */                         
          $info = array(
            'type_msg' 			=> "has-success",
            'msg' 				=> 'Save data successfully',
            "checked" 			=> 1,
            "error" 			=> $msg,
            "id"    			=> $id
          );
    }else {
      $info = array(
        'type_msg' 			=> "has-error",
        'msg' 				=> 'Input data has some warning',
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
                  $item           =       ProductModel::find((int)@$id);        
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
            $data=InvoiceDetailModel::whereRaw("product_id = ?",[(int)@$id])->select('id')->get()->toArray();
            if(count($data) > 0){
              $checked                =   0;
              $type_msg               =   "alert-warning";            
              $msg                    =   "Phần tử có dữ liệu con. Vui lòng không xoá";
            }                 
            if($checked == 1){
              $item = ProductModel::find((int)@$id);
                $item->delete();     
                PostParamModel::whereRaw("post_id = ?",[(int)@$id])->delete();           
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
          $checked     =   0;
          $type_msg           =   "alert-warning";            
          $msg                =   "Please choose at least one item";
        }
        if($checked==1){
          foreach ($arrID as $key => $value) {
            if(!empty($value)){
              $item=ProductModel::find($value);
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
              $msg                =   "Please choose at least one item";
            }
            $data=DB::table('invoice_detail')->whereIn('product_id',@$arrID)->select('id')->get()->toArray();             
            if(count($data) > 0){
              $checked                =   0;
              $type_msg               =   "alert-warning";            
              $msg                    =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
            }  
            if($checked == 1){                
                  DB::table('product')->whereIn('id',@$arrID)->delete(); 
                  DB::table('post_param')->whereIn('post_id',@$arrID)->delete();                                              
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
                  $item=ProductModel::find((int)$value->id);                
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
          $checked_trung_alias=0;
          if (empty($id)) {              
              $dataProduct=ProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
            }else{
              $dataProduct=ProductModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower($alias,'UTF-8')),(int)@$id])->get()->toArray();    
            }  
            $dataCategoryArticle=CategoryArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
              $dataCategoryProduct=CategoryProductModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
              $dataArticle=ArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
              $dataPage=PageModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
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
