<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MenuTypeModel;
use App\MenuModel;
use DB;
class MenuTypeController extends Controller {
  	var $_controller="menu-type";	
  	var $_title="Loại menu";
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
      $query=DB::table('menu_type')   ;   
      if(!empty(@$request->filter_search)){
        $query->where('menu_type.fullname','like','%'.trim(@$request->filter_search).'%');
      }             
      $data=$query->select('menu_type.id','menu_type.fullname','menu_type.theme_location','menu_type.sort_order','menu_type.status','menu_type.created_at','menu_type.updated_at')
                  ->groupBy('menu_type.id','menu_type.fullname','menu_type.theme_location','menu_type.sort_order','menu_type.status','menu_type.created_at','menu_type.updated_at')
                  ->orderBy('menu_type.sort_order', 'asc')
                  ->get()
                  ->toArray();      
      $data=convertToArray($data);    
      $data=menuTypeConverter($data,$this->_controller);            
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
                $arrRowData=MenuTypeModel::find((int)@$id)->toArray();       
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
        $id 					       =	trim($request->id)	;        
        $fullname 				   =	trim($request->fullname)	;  
        $theme_location            =  trim($request->theme_location)  ;  
        $status                  =  trim($request->status);
        $sort_order 			   =	trim($request->sort_order);
        $data 		= array();
        
        $item		= null;
        $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
        if(empty($fullname)){
            $checked = 0;            
            $msg["fullname"] = "Thiếu nhóm menu";
        }else{
            $data=array();
            if (empty($id)) {
                $data=MenuTypeModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();	        	
            }else{
              $data=MenuTypeModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$id])->get()->toArray();		
            }  
            if (count($data) > 0) {
              $checked = 0;              
              $msg["fullname"] = "Nhóm menu đã tồn tại";
            }      	
        }
        if(empty($theme_location)){
            $checked = 0;            
            $msg["theme_location"] = "Thiếu theme location";
        }
        if(empty($sort_order)){
             $checked = 0;             
             $msg["sort_order"] 		= "Thiếu sắp xếp";
        }
        if((int)@$status == -1){
          $checked = 0;             
             $msg["status"]     = "Thiếu trạng thái"; 
        }
        if($checked == 1) {    
             if(empty($id)){
                $item 				      = 	new MenuTypeModel;       
                $item->created_at 	=	date("Y-m-d H:i:s",time());        
                if(!empty($image)){
                  $item->image      =   trim($image) ;  
                }				
              }else{
                    $item				    =	MenuTypeModel::find((int)@$id);     	  		 
              }  
              $item->fullname 		  =	$fullname;
              $item->theme_location       = $theme_location;
              $item->status       = (int)$status;   
              $item->sort_order 		=	(int)$sort_order;  
              $item->updated_at 		=	date("Y-m-d H:i:s",time());    	        	
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
        $status         =       (int)$request->status;
        $item           =       MenuTypeModel::find((int)@$id);        
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
      $data=MenuModel::whereRaw("menu_type_id = ?",[(int)@$id])->select('id')->get()->toArray();
      if(count($data) > 0){
        $checked            =   0;
          
          $msg['cannotdelete']            =   "Phần tử có dữ liệu con . Vui lòng không xóa";
      }           
      if($checked == 1){                        
        $item               =   MenuTypeModel::find((int)@$id);
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
            $item=MenuTypeModel::find($value);
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
      $data=DB::table('menu')->whereIn('menu_type_id',@$arrID)->select('id')->get()->toArray();             
      if(count($data) > 0){
        $checked            =   0;
          
          $msg['cannotdelete']            =   "Phần tử này có dữ liệu con . Vui lòng không xóa";
      }   
      if($checked == 1){                                                               

        DB::table('menu_type')->whereIn('id',@$arrID)->delete();   
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
            $item=MenuTypeModel::find((int)$value->id);                
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
}
?>
