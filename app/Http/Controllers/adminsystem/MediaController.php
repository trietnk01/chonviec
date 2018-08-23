<?php namespace App\Http\Controllers\adminsystem;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class MediaController extends Controller {	
	var $_controller="media";	
  	var $_title="Media";
  	var $_icon="icon-settings font-dark";
	public function getList(){		
    		$controller=$this->_controller;	
    		$task="list";
    		$title=$this->_title;
    		$icon=$this->_icon;		            
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
  		$strDirUpload=base_path("upload");
  		$data = scandir($strDirUpload);		  		
  		$data=mediaConverter($data,$this->_controller);		           		
  		return $data;
  	}	 
	public function getForm($task){		 
		$controller=$this->_controller;	
		$title=$this->_title . " : Add new";
		$icon=$this->_icon; 				
		return view("adminsystem.".$this->_controller.".form",compact("controller","task","title","icon"));
	}
  public function save(Request $request){
    $info                 =   array();
      $checked              =   1;                           
      $msg                =   array();
    $source_media_file=array();            
    if(isset($_FILES['source_media_file'])){
      $source_media_file=$_FILES['source_media_file'];
    }                 
    $media_item=null;
    $width=0;
    $height=0;        
    if(count($source_media_file) > 0){
      foreach ($source_media_file['name'] as $key => $value) {
        $media_item=uploadMediaFile($value,$source_media_file['tmp_name'][$key]);
      }      
    }  
    $msg['success']='Lưu thành công';     
    $info = array(
                "checked"       => $checked,          
        'msg'       => $msg,               
                "id"            => 0
              );             
    return $info;                       
  }
  public function saveSummerFile(Request $request){
    $info                 =   array();
    $checked              =   1;                           
    $msg                =   array();
    $summer_file           =   null;
    if(isset($_FILES["summer_file"])){
      $summer_file         =   $_FILES["summer_file"];
    }   
    /* begin checkfilesize */
    $file_size=0;
    if($summer_file != null){              
      if((int)@$summer_file['size']==0){
        $checked = 0;               
        $msg["status"]      = "Vui lòng nhập hình ảnh dưới 2MB";
      }else{
        $file_size=@$summer_file['size']/1024/1024;
        if($file_size > (int)max_size_upload ){
          $checked = 0;               
          $msg["status"]      = "Vui lòng nhập hình ảnh dưới 2MB";
        }
      }      
    }

    /* end checkfilesize */
    $summer_url='';
    if((int)@$checked == 1){
      $summer_name='';
      if($summer_file != null){                                                
        $summer_name=uploadImage($summer_file['name'],$summer_file['tmp_name'],0,0);
      }
      $summer_url='/upload/'.$summer_name;
      $msg['success']='Lưu thành công';     
    }    
    $info = array(
      "checked"       => $checked,          
      'msg'       => $msg,               
      "summer_url"            => $summer_url
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
      		foreach ($arrID as $key => $value) {
      			if(!empty($value)){
      				$pathFile=base_path("upload/".$value);
      				if(file_exists($pathFile)){
      					unlink($pathFile);
      				}	
      			}			 	
      		}
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
	public function deleteItem(Request $request){
		$id                     =   $request->id;              
		$info                 =   array();
      $checked              =   1;                           
      $msg                =   array();    
		$pathFile 				= 	base_path("upload/".$id);			
		if(!file_exists($pathFile)){
			$checked=0;
		}			         	    
		if($checked == 1){
			unlink($pathFile);
		}        
    $msg['success']='Xóa thành công';     
		$data                   =   $this->loadData($request);
		$info = array(
              "checked"       => $checked,          
        'msg'       => $msg,                     
              'data'              => $data
            );
		return $info;
	}
	
}

