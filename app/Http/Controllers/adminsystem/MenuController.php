<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MenuModel;
use App\ProductModel;
use App\ArticleModel;
use App\PageModel;
use App\MenuTypeModel;
use App\ModMenuTypeModel;
use App\PaginationModel;
use App\CategoryArticleModel;
use App\CategoryProductModel;
use DB;
class MenuController extends Controller {
    	var $_controller="menu"; 
      var $_title="Menu";
      var $_icon="icon-settings font-dark";
      var $_totalItemsPerPage=9999;    
      var $_pageRange=10;
      public function getList(Request $request,$menu_type_id=0){   
        $controller=$this->_controller; 
        $task="list";
        $title=$this->_title;
        $icon=$this->_icon; 
        $currentPage=1;             
        $query=DB::table('menu as n')  
        ->leftJoin('menu as a','n.parent_id','=','a.id');
        $query->where('n.menu_type_id',(int)@$menu_type_id);       
        if(!empty(@$request->filter_search)){
          $query->where('n.fullname','like','%'.trim(mb_strtolower(@$request->filter_search,'UTF-8')).'%')    ;
        }
        $data=$query->select('n.id')
                    ->groupBy('n.id')
                    ->get()
                    ->toArray();            
        $totalItems=count($data);
        $totalItemsPerPage=$this->_totalItemsPerPage;       
        $pageRange=$this->_pageRange;
        if(!empty(@$request->filter_page)){
          $currentPage=(int)@$request->filter_page;    
        }            
        $arrPagination=array(
          "totalItems"=>$totalItems,
          "totalItemsPerPage"=>$totalItemsPerPage,
          "pageRange"=>$pageRange,
          "currentPage"=>$currentPage 
        );
        $pagination=new PaginationModel($arrPagination);
        $position = (@$arrPagination['currentPage']-1)*$totalItemsPerPage;
        $query=DB::table('menu as n')  
        ->leftJoin('menu as a','n.parent_id','=','a.id');   
        $query->where('n.menu_type_id',(int)@$menu_type_id);      
        if(!empty(@$request->filter_search)){
          $query->where('n.fullname','like','%'.trim(mb_strtolower(@$request->filter_search,'UTF-8')).'%')    ;
        }
        $data=$query->select('n.id','n.fullname','n.alias','n.parent_id','a.fullname as parent_fullname','n.menu_type_id','n.level','n.sort_order','n.status','n.created_at','n.updated_at')
                    ->groupBy('n.id','n.fullname','n.alias','n.parent_id','a.fullname','n.menu_type_id','n.level','n.sort_order','n.status','n.created_at','n.updated_at')
                    ->orderBy('n.sort_order', 'asc')
                    ->skip($position)
                    ->take($totalItemsPerPage)
                    ->get()
                    ->toArray();     
        $data=convertToArray($data);
        $data=menuConverter($data,$this->_controller);   
        $data_recursive=array();        
        menuRecursive($data,0,null,$data_recursive);          
        $data=$data_recursive; 
        
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-list";         
        if(in_array($requestControllerAction,$arrPrivilege)){
          return view("adminsystem.".$this->_controller.".list",compact("controller","task","title","icon",'data','pagination','filter_search','menu_type_id'));  
        }
        else{
          return view("adminsystem.no-access",compact('controller'));
        }
      } 	
      public function getForm($task,$menu_type_id="",$id="",$alias){   
        $controller=$this->_controller;			
        $title="";
        $icon=$this->_icon; 
        $arrRowData=array();
        $fullname='';
        $item=array();
        $arrPrivilege=getArrPrivilege();
        $requestControllerAction=$this->_controller."-form";  
        if(in_array($requestControllerAction, $arrPrivilege)){
          switch ($task) {
           case 'edit':
           $title=$this->_title . " : Update";
           $arrRowData=MenuModel::find((int)@$id)->toArray();       
           $fullname=$arrRowData['fullname'];
           break;
           case 'add':
           $title=$this->_title . " : Add new";
           $itemArticle=ArticleModel::whereRaw('trim(lower(alias)) = ?',[trim(mb_strtolower($alias,'UTF-8'))])->select('fullname')->get()->toArray();
           $itemProduct=ProductModel::whereRaw('trim(lower(alias)) = ?',[trim(mb_strtolower($alias,'UTF-8'))])->select('fullname')->get()->toArray(); 
           $itemPage=PageModel::whereRaw('trim(lower(alias)) = ?',[trim(mb_strtolower($alias,'UTF-8'))])->select('fullname')->get()->toArray(); 
           $itemCategoryArticle=CategoryArticleModel::whereRaw('trim(lower(alias)) = ?',[trim(mb_strtolower($alias,'UTF-8'))])->select('fullname')->get()->toArray();
           $itemCategoryProduct=CategoryProductModel::whereRaw('trim(lower(alias)) = ?',[trim(mb_strtolower($alias,'UTF-8'))])->select('fullname')->get()->toArray();                  
           if(count($itemArticle) > 0){
            $fullname=$itemArticle[0]['fullname'];
          }
          if(count($itemProduct) > 0){
            $fullname=$itemProduct[0]['fullname'];
          }
          if(count($itemPage) > 0){
            $fullname=$itemPage[0]['fullname'];
          }
          if(count($itemCategoryArticle) > 0){
            $fullname=$itemCategoryArticle[0]['fullname'];
          }
          if(count($itemCategoryProduct) > 0){
            $fullname=$itemCategoryProduct[0]['fullname'];
          }
          break;     
        }    
        $arrMenu=MenuModel::whereRaw("menu_type_id = ? and id != ?",[(int)@$menu_type_id,(int)@$id])->select("id","fullname","alias","parent_id","menu_type_id","level","sort_order","status","created_at","updated_at")->orderBy("sort_order","asc")->get()->toArray();
        $arrMenuRecursive=array();
        menuRecursiveForm($arrMenu ,0,"",$arrMenuRecursive)  ;
        $arrMenuType=MenuTypeModel::select("id","fullname","sort_order","created_at","updated_at")->orderBy("sort_order","asc")->get()->toArray();

        return view("adminsystem.".$this->_controller.".form",compact("arrMenuRecursive","arrMenuType","arrRowData","menu_type_id","controller","task","title","icon","alias","fullname"));      
      }else{
        return view("adminsystem.no-access",compact('controller'));
      }               
    }
    public function save(Request $request){
      $id 					       =	  trim($request->id)	;        
      $fullname 				   =	  trim($request->fullname)	;
      $alias               =    trim($request->alias);          
      $parent_id	         =		trim($request->parent_id);
      $menu_type_id        =    trim($request->menu_type_id);  
      $menu_class          =    trim($request->menu_class);    
      $sort_order 			   =		trim($request->sort_order);
      $status 				     =		trim($request->status);          
      $data 		           =    array();

      $item		             =    null;
      $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
      if(empty($fullname)){
       $checked = 0;

       $msg["fullname"] = "Thiếu chủ đề bài viết";
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
        $item 				      = 	new MenuModel;       
        $item->created_at 	=	date("Y-m-d H:i:s",time());                          
      } else{
        $item				         =	MenuModel::find((int)@$id);                     	  		 
      }  
      $item->fullname 		     = $fullname;
      $item->alias             = $alias;                             
      $item->parent_id 		     = (int)$parent_id;
      $item->menu_type_id      = (int)$menu_type_id;
      $level                   = 0;              
      $parent=MenuModel::find($parent_id); 
      if(count($parent) > 0){
        $level=(int)$parent->toArray()["level"]+1;                
      }                     
      $item->level             =  (int)$level; 
      $item->menu_class        = @$menu_class;           
      $item->sort_order 	     =	(int)$sort_order;
      $item->status 			     =  (int)$status;    
      $item->updated_at 	=	date("Y-m-d H:i:s",time());    	        	
      $item->save();  	
      $msg['success']='Lưu thành công';
    }    
    if(empty($alias)){
      $alias='no-alias';
    }
    $info = array(
      "checked"       => $checked,          
      'msg'       => $msg,      
      'link_edit'=>route('adminsystem.'.$this->_controller.'.getForm',['edit',@$menu_type_id,@$item->id,@$alias])
    );    		 			       
    return $info;       
  }
      public function changeStatus(Request $request){
        $id             =       (int)$request->id;  
        $status         =       (int)$request->status;

        $item=MenuModel::find($id);
        $trangThai=0;
        if($status==0){
          $trangThai=1;
        }
        else{
          $trangThai=0;
        }
        $item->status=$status;
        $item->save();
        $result = array(
          'id'      => $id, 
          'status'  => $status, 
          'link'    => 'javascript:changeStatus('.$id.','.$trangThai.');'
        );
        return $result;   
      }
      public function deleteItem($id){
        $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
        $menu_type_id           =   0;        
        $data                   =   MenuModel::whereRaw("parent_id = ?",[(int)@$id])->get()->toArray();                    
        $item                   =   MenuModel::find((int)@$id);
        $menu_type_id           =   $item->toArray()["menu_type_id"];
        if(count($data) > 0){
          $checked     =   0;
          
          $msg['cannotdelete']                    =   "Phần tử này có dữ liệu con. Vui lòng không xoá";
        }          
        if($checked == 1){
          $item               =   MenuModel::find((int)@$id);
          $item->delete();  
          $msg['success']='Xóa thành công';                      
        }        
        $info = array(
          "checked"       => $checked,          
        'msg'       => $msg,                         
        );      
        return redirect()->route("adminsystem.".$this->_controller.".getList",[$menu_type_id])->with(["message"=>$info]);                
      }
      public function updateStatus(Request $request,$status,$menu_type_id){        
        $arrID=$request->cid;
        $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();         
        if(count($arrID)==0){
          $checked     =   0;
          
          $msg['chooseone']                    =   "Vui lòng chọn ít nhất 1 phần tử";
        }
        if($checked==1){
          foreach ($arrID as $key => $value) {
            $item=MenuModel::find($value);
            $item->status=$status;
            $item->save();    
          }
          $msg['success']='Cập nhật trạng thái thành công';
        }        
        $info = array(
          "checked"       => $checked,          
        'msg'       => $msg,                             
        );        
        return redirect()->route("adminsystem.".$this->_controller.".getList",[$menu_type_id])->with(["message"=>$info]);         
      }
      public function trash(Request $request,$menu_type_id){        
        $arrID                 =   $request->cid;             
        $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
        if(count($arrID)==0){
          $checked     =   0;
          
          $msg['chooseone']                    =   "Vui lòng chọn ít nhất 1 phần tử";
        }else{
          foreach ($arrID as $key => $value) {
            $item=MenuModel::find($value);           
            $count = MenuModel::where("parent_id",$value)->count();
            if($count > 0){
             $checked     =   0;
             
             $msg['cannotdelete']                    =   "Phần tử đã có dữ liệu con vui lòng không xóa";
           } 
         }
       }

       if($checked == 1){        

        DB::table('menu')->whereIn('id',@$arrID)->delete();   
        $msg['success']='Xóa thành công';

      }
      $info = array(
        "checked"       => $checked,          
        'msg'       => $msg,                      
      );        
      return redirect()->route("adminsystem.".$this->_controller.".getList",[$menu_type_id])->with(["message"=>$info]);                          
    }
      public function sortOrder(Request $request,$menu_type_id=0){
        $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();   
        $arrOrder=array();
        $arrOrder=$request->sort_order;  
        if(count($arrOrder) == 0){
          $checked     =   0;
          
          $msg['chooseone']                    =   "Vui lòng chọn ít nhất 1 phần tử";
        }
        if($checked==1){        
          foreach($arrOrder as $id => $value){                    
            $item=MenuModel::find($id);
            $item->sort_order=(int)$value;            
            $item->save();            
          }     
          $msg['success']='Sắp xếp thành công';
        }    
        $info = array(
          "checked"       => $checked,          
        'msg'       => $msg,                              
        );        
        return redirect()->route("adminsystem.".$this->_controller.".getList",[$menu_type_id])->with(["message"=>$info]);
      }
      public function getComponentForm($menu_type_id = 0){  
        $controller=$this->_controller;     
        $title="Component";
        $icon=$this->_icon; 
        return view("adminsystem.".$this->_controller.".component",compact('menu_type_id',"title","icon","controller")); 
      }
      public function getCategoryArticleComponent(Request $request,$menu_type_id = 0){
        $controller=$this->_controller; 
        $task="list";
        $title="Category Article Component";
        $icon=$this->_icon; 
        $currentPage=1;             
        $query=DB::table('category_article');        
        if(!empty(@$request->filter_search)){
          $query->where('category_article.fullname','like','%'.trim(@$request->filter_search).'%');
        }
        $data=$query->select('category_article.id')                                
        ->groupBy('category_article.id')                
        ->get()
        ->toArray();
        $totalItems=count($data);
        $totalItemsPerPage=$this->_totalItemsPerPage;       
        $pageRange=$this->_pageRange;
        if(!empty(@$request->filter_page)){
          $currentPage=(int)@$request->filter_page;    
        }            
        $arrPagination=array(
          "totalItems"=>$totalItems,
          "totalItemsPerPage"=>$totalItemsPerPage,
          "pageRange"=>$pageRange,
          "currentPage"=>$currentPage 
        );
        $pagination=new PaginationModel($arrPagination);
        $position = (@$arrPagination['currentPage']-1)*$totalItemsPerPage;
        $data=array();
        $query=DB::table('category_article as n')
        ->leftJoin('category_article as a','n.parent_id','=','a.id');        
        if(!empty(@$request->filter_search)){
          $query->where('n.fullname','like','%'.trim(@$request->filter_search).'%');
        }
        $data=$query->select('n.id','n.fullname','n.alias','n.parent_id','a.fullname as parent_fullname','n.image','n.sort_order','n.status','n.created_at','n.updated_at')                           
        ->groupBy('n.id','n.fullname','n.alias','n.parent_id','a.fullname','n.image','n.sort_order','n.status','n.created_at','n.updated_at')
        ->orderBy('n.sort_order', 'asc')
        ->skip($position)
        ->take($totalItemsPerPage)
        ->get()
        ->toArray();    
        $data=convertToArray($data);
        $data=categoryArticleComponentConverter($data,$this->_controller,$menu_type_id);   
        $data_recursive=array();
        categoryArticleRecursive($data,0,null,$data_recursive);          
        $data=$data_recursive; 
        return view("adminsystem.".$this->_controller.".category-component",compact("controller","task","title","icon",'data','pagination','filter_search'));         
      }
      public function getCategoryProductComponent($menu_type_id = 0){
        $controller=$this->_controller; 
        $task="list";
        $title="Category Product Component";
        $icon=$this->_icon; 
        $currentPage=1;             
        $query=DB::table('category_product');        
        if(!empty(@$request->filter_search)){
          $query->where('category_product.fullname','like','%'.trim(@$request->filter_search).'%');
        }
        $data=$query->select('category_product.id')                                
        ->groupBy('category_product.id')                
        ->get()
        ->toArray();
        $totalItems=count($data);
        $totalItemsPerPage=$this->_totalItemsPerPage;       
        $pageRange=$this->_pageRange;
        if(!empty(@$request->filter_page)){
          $currentPage=(int)@$request->filter_page;    
        }              
        $arrPagination=array(
          "totalItems"=>$totalItems,
          "totalItemsPerPage"=>$totalItemsPerPage,
          "pageRange"=>$pageRange,
          "currentPage"=>$currentPage 
        );
        $pagination=new PaginationModel($arrPagination);
        $position = (@$arrPagination['currentPage']-1)*$totalItemsPerPage;
        $data=array();
        $query=DB::table('category_product as n')
        ->leftJoin('category_product as a','n.parent_id','=','a.id');        
        if(!empty(@$request->filter_search)){
          $query->where('n.fullname','like','%'.trim(@$request->filter_search).'%');
        }
        $data=$query->select('n.id','n.fullname','n.alias','n.parent_id','a.fullname as parent_fullname','n.image','n.sort_order','n.status','n.created_at','n.updated_at')                           
        ->groupBy('n.id','n.fullname','n.alias','n.parent_id','a.fullname','n.image','n.sort_order','n.status','n.created_at','n.updated_at')
        ->orderBy('n.sort_order', 'asc')
        ->skip($position)
        ->take($totalItemsPerPage)
        ->get()
        ->toArray();              
        $data=convertToArray($data);
        $data=categoryProductComponentConverter($data,$this->_controller,$menu_type_id);   
        $data_recursive=array();
        categoryProductRecursive($data,0,null,$data_recursive);          
        $data=$data_recursive; 
        return view("adminsystem.".$this->_controller.".category-component",compact("controller","task","title","icon",'data','pagination','filter_search'));         
      }
      public function getArticleComponent($menu_type_id = 0){
        $controller=$this->_controller;         
        $title='Article component';
        $icon=$this->_icon;   
        $arrCategoryArticle=CategoryArticleModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
        $arrCategoryArticleRecursive=array();              
        categoryRecursiveForm($arrCategoryArticle ,0,"",$arrCategoryArticleRecursive)  ;                    
        return view("adminsystem.".$this->_controller.".article-component",compact("controller","title","icon","arrCategoryArticleRecursive","menu_type_id")); 
      }
      public function getArticleList(Request $request){
        $filter_search="";            
        $menu_type_id=$request->menu_type_id;
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
      $data=$query->select('article.id','article.fullname','article.alias','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
                  ->groupBy('article.id','article.fullname','article.alias','article.image','article.sort_order','article.status','article.created_at','article.updated_at')
                  ->orderBy('article.sort_order', 'asc')
                  ->get()
                  ->toArray();      
      $data=convertToArray($data);
        $data=articleComponentConverter($data,$this->_controller,$menu_type_id);           
        return $data;
    } 
      
    public function getProductComponent($menu_type_id = 0){
        $controller=$this->_controller;         
        $title='Product component';
        $icon=$this->_icon;   
        $arrCategoryProduct=CategoryProductModel::select("id","fullname","parent_id")->orderBy("sort_order","asc")->get()->toArray();
        $arrCategoryProductRecursive=array();              
        categoryRecursiveForm($arrCategoryProduct ,0,"",$arrCategoryProductRecursive)  ;            
        return view("adminsystem.".$this->_controller.".product-component",compact("controller","title","icon","arrCategoryProductRecursive","menu_type_id")); 
      }
      public function getProductList(Request $request){
        $filter_search="";            
        $menu_type_id=$request->menu_type_id;
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
        $data=productComponentConverter($data,$this->_controller,$menu_type_id);            
        return $data;
      } 
    public function getPageComponent($menu_type_id = 0){
        $controller=$this->_controller;         
        $title='Page component';
        $icon=$this->_icon;    

        return view("adminsystem.".$this->_controller.".page-component",compact("controller","title","icon","menu_type_id")); 
      }
      public function getPageList(Request $request){
        $menu_type_id=$request->menu_type_id;
        $query=DB::table('page')  ;       
      if(!empty(@$request->filter_search)){
        $query->where('page.fullname','like','%'.trim(mb_strtolower(@$request->filter_search,'UTF-8')).'%')    ;
      }
      $data=$query->select('page.id','page.fullname','page.alias','page.theme_location','page.image','page.sort_order','page.status','page.created_at','page.updated_at')
      ->groupBy('page.id','page.fullname','page.alias','page.theme_location','page.image','page.sort_order','page.status','page.created_at','page.updated_at')
      ->orderBy('page.sort_order', 'asc')->get()->toArray();                
      $data=convertToArray($data);    
        $data=pageComponentConverter($data,$this->_controller,$menu_type_id);           
        return $data;
    }      
}
?>
