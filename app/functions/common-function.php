<?php 
use App\SettingSystemModel;
use App\MenuModel;
use App\MenuTypeModel;
use App\ModuleItemModel;
use App\ProductModel;
use App\ArticleModel;
use App\CategoryProductModel;
use App\CategoryArticleModel;
use Illuminate\Support\Str;
function uploadImage($name,$tmp_name,$width,$height){        
  $image_name=$name;                  
  $image_tmp_name=$tmp_name;
  $ext = pathinfo($image_name, PATHINFO_EXTENSION);                  
  $image_slug=str_slug($image_name,'.');
  $pattern_ext='#.png|.jpg|.gif#';
  $pattern_dot='#\.#';
  $image_slug=preg_replace($pattern_ext, '', $image_slug);                      
  $image_slug=preg_replace($pattern_dot, '-', $image_slug);   
  /* begin code_alias */
  $source_character = array_merge(range('a','z'), range(0,9));
  $code = implode($source_character, '');
  $code = str_shuffle($code);
  $code_alias   = substr($code, 0, 20);
  /* end code_alias */
  $image_name=$image_slug. '-' . $code_alias .'.'.$ext;     
  $image_path=base_path("upload".DS.$image_name);  
  move_uploaded_file($image_tmp_name, $image_path);  
  if((int)@$width > 0 && (int)@$height > 0){
    require_once base_path("app".DS."scripts".DS."PhpThumb".DS."ThumbLib.inc.php") ;       
    $thumb = PhpThumbFactory::create($image_path);        
    $thumb->adaptiveResize($width, $height);
    $prefix = $width . 'x' . $height ;
    $veston =base_path('upload' . DS . $prefix . '-' . $image_name);       
    $thumb->save($veston);
  }  
  return $image_name;
}
function uploadMediaFile($name,$tmp_name){        
  $image_name=$name;                  
  $image_tmp_name=$tmp_name;
  $ext = pathinfo($image_name, PATHINFO_EXTENSION);                  
  $image_slug=str_slug($image_name,'.');
  $pattern_ext='#.png|.jpg|.gif|.doc|.docx|.xls|.xlsx|.pdf#';
  $pattern_dot='#\.#';
  $image_slug=preg_replace($pattern_ext, '', $image_slug);                      
  $image_slug=preg_replace($pattern_dot, '-', $image_slug);     
  $image_name=$image_slug.'.'.$ext;   
  $image_path=base_path("upload".DS.$image_name);
  @copy($image_tmp_name, $image_path);    
  return $image_name;
}
function uploadAttachedFile($name,$tmp_name){        
  $image_name=$name;                  
  $image_tmp_name=$tmp_name;
  $ext = pathinfo($image_name, PATHINFO_EXTENSION);                  
  $image_slug=str_slug($image_name,'.');
  $pattern_ext='#.doc|.docx|.xls|.xlsx|.pdf#';
  $pattern_dot='#\.#';
  $image_slug=preg_replace($pattern_ext, '', $image_slug);                      
  $image_slug=preg_replace($pattern_dot, '-', $image_slug);   
  
  /* begin code_alias */
  $source_character = array_merge(range('a','z'), range(0,9));
  $code = implode($source_character, '');
  $code = str_shuffle($code);
  $code_alias   = substr($code, 0, 20);
  /* end code_alias */

  $image_name=$image_slug. '-' . $code_alias .'.'.$ext;   
  $image_path=base_path("upload".DS.$image_name);
  @copy($image_tmp_name, $image_path);    
  return $image_name;
}
function get_product_thumbnail($value){
  $setting= getSettingSystem();
  $product_width=$setting['product_width']['field_value'];
  $product_height=$setting['product_height']['field_value'];
  $image_link=asset('upload/'.$product_width.'x'.$product_height.'-'.$value);
  return $image_link;
}
function get_article_thumbnail($value){
  $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
  $image_link=asset('upload/'.$article_width.'x'.$article_height.'-'.$value);
  return $image_link;
}

function getStringCategoryID($category_id,&$arrCategoryID,$model){
    $arrCategory=array();    
    switch ($model) {
      case 'category_article':        
        $arrCategory=CategoryArticleModel::whereRaw("parent_id = ?",[(int)@$category_id])->select("id")->get()->toArray();    
        break;
      case 'category_product':
        $arrCategory=CategoryProductModel::whereRaw("parent_id = ?",[(int)@$category_id])->select("id")->get()->toArray();    
        break;              
    }    
    if(count($arrCategory) > 0){
      foreach ($arrCategory as $key => $value) {
        $arrCategoryID[]=$value["id"];
        getStringCategoryID((int)$value["id"],$arrCategoryID,$model);
      }
    }          
}
function convertToTextPrice($value){
  $data = getSettingSystem();
  $language = $data["currency_unit"]['field_value'] ;
  $strCurrency="";
  switch ($language) {
    case "vi_VN":
    $strCurrency= number_format($value,0,",",".") ;
    break;
    case "en_US":
    $strCurrency=number_format($value,0,".",",");
    break;
  }
  return $strCurrency;
}
function fnPrice($value){
  $data = getSettingSystem();
  $language = $data["currency_unit"]['field_value'] ;
  $strCurrency="";
  switch ($language) {
    case "vi_VN":
    $strCurrency= number_format($value,0,",",".") . ' đ';
    break;
    case "en_US":
    $strCurrency='$'.number_format($value,0,".",",");
    break;
  }
  return $strCurrency;
}
function randomCodeNumber(){
  $data = range(1,9);
  $subject=implode($data, '');
  $subject=str_shuffle($subject);
  return $subject;
}
function getArrPrivilege(){
  /* begin quyền truy cập */
  $user_id=Sentinel::getUser()->id;      
  $stdPrivilegeID=DB::table("users")
  ->join('user_group_member','users.id','=','user_group_member.user_id')
  ->join('group_member','group_member.id','=','user_group_member.group_member_id')
  ->join("group_privilege","group_member.id","=","group_privilege.group_member_id")
  ->where("users.id","=",(int)@$user_id)                
  ->select("group_privilege.privilege_id")
  ->get();
  ;     
  $arrID=array();    
  $arrPrivilege=array();
  foreach ($stdPrivilegeID as $key => $value) {
    $arrID[]=$value->privilege_id;
  }
  if(count($arrID) > 0){
    $strID=implode(",",$arrID);
    $sql="select concat(`controller`,'-',`action`) as controller_action
    from `privilege`
    where `id` in(".$strID.")";
    $stdPrivilege=DB::select(@$sql);  
    foreach ($stdPrivilege as $key => $value) {
      $arrPrivilege[]=$value->controller_action;
    }     
  }
  /* end quyền truy cập */  
  return $arrPrivilege;
}
function truncateString($str, $maxChars = 40){
  $chuoi='';
  if (strlen($str) > $maxChars) {
    $chuoi= Str::substr(trim($str),0, $maxChars) ."...";
  } else {
    $chuoi= $str;
  }
  return $chuoi;
}
?>