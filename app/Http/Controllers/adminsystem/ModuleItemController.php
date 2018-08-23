<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModuleItemModel;
use App\MenuTypeModel;
use App\MenuModel;
use App\CategoryArticleModel;
use App\CategoryProductModel;
use App\ArticleModel;
use App\ProductModel;
use DB;
class ModuleItemController extends Controller {
  	var $_controller="module-item";	
  	var $_title="Module";
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
        $query=DB::table('module_item');        
        if(!empty(@$request->filter_search)){
          $query->where('module_item.fullname','like','%'.trim(@$request->filter_search).'%');
        }
        $data=$query->select('module_item.id','module_item.fullname','module_item.item_id','module_item.position','module_item.component','module_item.setting','module_item.status','module_item.sort_order','module_item.created_at','module_item.updated_at')
        ->groupBy('module_item.id','module_item.fullname','module_item.item_id','module_item.position','module_item.component','module_item.setting','module_item.status','module_item.sort_order','module_item.created_at','module_item.updated_at')
        ->orderBy('module_item.sort_order', 'asc')->get()->toArray()     ;              
        $data=convertToArray($data);    
        $data=moduleItemConverter($data,$this->_controller);            
        return $data;
      }   
      public function loadDataArticle(Request $request){      
        $category_id=(int)@$request->category_id;
        $arrCategoryID[]=@$category_id;
        getStringCategoryID($category_id,$arrCategoryID,'category_article');     
        $query=DB::table('article')
        ->join('article_category','article.id','=','article_category.article_id')
        ->join('category_article','category_article.id','=','article_category.category_id')  ;      
        if(!empty(@$request->filter_search)){
          $query->where('article.fullname','like','%'.trim(@$request->filter_search).'%');
        }     
        if(count($arrCategoryID) > 0){
          $query->whereIn('article_category.category_id',$arrCategoryID);
        }   
        $data=$query->select('article.id','article.fullname','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
        ->groupBy('article.id','article.fullname','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
        ->orderBy('article.sort_order', 'asc')
        ->get()
        ->toArray();      
        
        $data=convertToArray($data);          
        $data=article2Converter($data,$this->_controller);            
        return $data;
      } 
    public function loadDataProduct(Request $request){
    $category_id=(int)@$request->category_product_id;
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
      $data=product2Converter($data,$this->_controller);            
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
       $arrRowData=ModuleItemModel::find(@$id)->toArray();  
       break;
       case 'add':
       $title=$this->_title . " : Add new";
       break;     
     }    
     $arrCategoryArticle=CategoryArticleModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
     $arrCategoryProduct=CategoryProductModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
     $arrCategoryArticleRecursive=array();      
     $arrCategoryProductRecursive=array();      
     categoryRecursiveForm($arrCategoryArticle ,0,"",$arrCategoryArticleRecursive)  ;    
     categoryRecursiveForm($arrCategoryProduct ,0,"",$arrCategoryProductRecursive)  ;         
     return view("adminsystem.".$this->_controller.".form",compact("arrRowData","controller","task","title","icon","arrCategoryArticleRecursive","arrCategoryProductRecursive"));
        } else{
          return view("adminsystem.no-access",compact('controller'));
        } 
      
   }
             public function save(Request $request){
              $id 					        =		trim($request->id);        
              $fullname 				    =		trim($request->fullname);
              $item_id              =   trim($request->item_id);   

              $position 					  = 	trim($request->position);
              $component            =   trim($request->component); 
              $setting              =   trim($request->setting);   
              $status               =   trim($request->status);        
              $sort_order           =   trim($request->sort_order);                  
              $data 		            =   array();

              $item		              =   null;
              $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();

              if(empty($fullname)){
               $checked = 0;                 
               $msg["fullname"] = "Thiếu tên module";
             }else{
              $data=array();
              if (empty($id)) {
                $data=ModuleItemModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
              }else{
                $data=ModuleItemModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
              }  
              if (count($data) > 0) {
                $checked = 0;                  
                $msg["fullname"] = "Tên module đã tồn tại";
              }      	
            } 
            if(empty($item_id)){
             $checked = 0;             
             $msg["item_id"]    = "Thiếu dữ liệu import";
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
              $item 				      = 	new ModuleItemModel;       
              $item->created_at 	=	date("Y-m-d H:i:s",time());                            
            } else{
              $item				        =	ModuleItemModel::find((int)@$id);                        		 
            }  
            $item->fullname 		    =	$fullname;
            $item->item_id = null;
            if(!empty($item_id)){
              $item->item_id          = $item_id;
            }                
            $item->position 		    =	$position;  
            $item->component        = $component;  
            $item->setting = null;
            $arrSetting=json_decode(@$setting);
            if(count($arrSetting) > 0){
              $item->setting          = @$setting ;    
            }                
            $item->status           = (int)$status;                  
            $item->sort_order 	    =	(int)$sort_order;                
            $item->updated_at 	    =	date("Y-m-d H:i:s",time());    	        	
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
            $item           =       ModuleItemModel::find((int)@$id);        
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
              $item = ModuleItemModel::find((int)@$id);
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
                  $item=ModuleItemModel::find($value);
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

              DB::table('module_item')->whereIn('id',@$arrID)->delete();   
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
                  $item=ModuleItemModel::find((int)$value->id);                
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
      public function insertArticle(Request $request){
        $str_id                 =   $request->str_id;  
        $str_id=substr($str_id, 0,strlen($str_id) - 1);     
        $sql = 'select 0 AS is_checked , id , fullname , image , sort_order from article where id in ('.$str_id.')';   
        $data=DB::select($sql);       
        $data=convertToArray($data);    
        $data=itemArticleConverter($data,$this->_controller);        
        return $data;
      } 
      public function insertProduct(Request $request){
        $str_id                 =   $request->str_id;  
        $str_id=substr($str_id, 0,strlen($str_id) - 1);     
        $sql = 'select 0 AS is_checked , id , fullname , image , sort_order from product where id in ('.$str_id.')';   
        $data=DB::select($sql);       
        $data=convertToArray($data);    
        $data=itemProductConverter($data,$this->_controller);        
        return $data;
      } 
      
      public function sortItems(Request $request){
        $data=array();
        $data_sort=$request->data_sort;
        $data=json_decode($data_sort);
        $data=convertToArray($data);    
        $data_1=array();     
        $data_3=array();   
        foreach ($data as $key => $value) {
          $item=array( 
            'id'=>$value['id'],
            'sort_order'=>$value['sort_order_text']
          );
          $data_1[]=$item;
        }
        $data_1=get_field_data_array($data_1,'sort_order');        
        $data_2=get_field_data_array($data,'sort_order_text');        
        ksort($data_1);        
        ksort($data_2); 
        foreach ($data_2 as $key => $value) {
          $sort_input='<center><input name="sort_order" id="sort-order-'.$value['id'].'" sort_order_id="'.$value['id'].'" value="'.$value['sort_order_text'].'" size="3" style="text-align:center"></center>';
          $item=array(
            'checked'=>$value['checked'],
            'deleted'=>$value['deleted'],
            'fullname'=>$value['fullname'],
            'id'=>(int)$value['id'],
            'image'=>$value['image'],
            'is_checked'=>(int)$value['is_checked'],
            'sort_order'=>$sort_input
          ); 
          $data_3[]=$item;   
        }  
        $data_2=$data_3;        
        $data_1=convertToSourceArray($data_1);        
        $dataReturn=array(
          'data_1'=>$data_1,
          'data_2'=>$data_2
        );     
        return $dataReturn;
      }
      public function getItems(Request $request){
        $id=$request->id;
        $data=array();
        $row=array();      
        $arrRowData=ModuleItemModel::find(@$id);        
        if(count($arrRowData) > 0){
          $arrRowData=$arrRowData->toArray();          
          $item_id=$arrRowData['item_id'];
          $component=$arrRowData['component'];        
          $list=json_decode($item_id);          
          if(count($list) > 0){
            $list=convertToArray($list);
            foreach ($list as $key => $value) {
              $sort_order=(int)@$value['sort_order'];          
              switch ($component) {
                case 'article':
                $row=ArticleModel::whereRaw('id = ?',[(int)@$value['id']])->select('id','fullname','image')->get()->toArray()[0];
                break;          
                case 'product':            
                $row=ProductModel::whereRaw('id = ?',[(int)@$value['id']])->select('id','fullname','image')->get()->toArray()[0];
                break;                
              } 
              $item=array(
                'is_checked'=>0,
                'id'=>$row['id'],
                'fullname'=>$row['fullname'],
                'image'=>$row['image'],
                'sort_order'=>$sort_order
              );
              $data[]=$item;
            }  
            switch ($component) {
             case 'article':
             $data=itemArticleConverter($data,$this->_controller);
             break;                       
             case 'product':  
             $data=itemProductConverter($data,$this->_controller);       
             break;             
           }             
         }
       }        
       return $data;
     }
}
?>
