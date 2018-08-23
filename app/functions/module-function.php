<?php 
use App\SettingSystemModel;
use App\MenuModel;
use App\MenuTypeModel;
use App\ModuleItemModel;
use App\ProductModel;
use App\ArticleModel;
use App\BannerModel;
use App\PageModel;
use App\ProjectModel;
use App\OrganizationModel;
use App\ProjectArticleModel;
use App\CategoryProductModel;
use App\CategoryArticleModel;
use App\CategoryBannerModel;
use Illuminate\Support\Facades\DB;
function getSettingSystem(){        
  $alias='setting-system';
  $data                   =   SettingSystemModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias))])->get()->toArray(); 
  $data=convertToArray(json_decode($data[0]['setting']));
  $data=get_field_data_array($data,'field_code');    
  return $data;     
}
function getSeo(){        
  $alias='setting-system';
  $data                   =   SettingSystemModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias))])->get()->toArray()[0];     
  return $data;     
}
function getBanner($position){
  $data=array();
  $status=1;
  $module=CategoryBannerModel::whereRaw('trim(lower(theme_location)) = ? and status = ?',[mb_strtolower(trim(@$position)),(int)@$status])->get()->toArray();
  if(count($module) > 0){    
    $module=$module[0];
    $id=$module['id'];
    $fullname=$module['fullname'];  
    $items=BannerModel::whereRaw('category_id = ? and status = ?',[(int)@$id,(int)@$status])->orderby("sort_order")->get()->toArray();  
    if(count($items) > 0){      
      $data=array(
        "fullname"    =>  $fullname,        
        "items"       =>  $items,
      );
    }    
  }   
  return $data; 
}
function getPage($position){
  $data=array();
  $status=1;
  $module=PageModel::whereRaw('trim(lower(theme_location)) = ? and status = ?',[mb_strtolower(trim(@$position)),(int)@$status])->get()->toArray();
  if(count($module) > 0){    
    $data=$module[0];    
  }   
  return $data; 
}
function getModuleItem($position){
  $data=array();
  $status=1;
  $module=ModuleItemModel::whereRaw('trim(lower(position)) = ? and status = ?',[mb_strtolower(trim(@$position)),(int)@$status])->get()->toArray();    
  if(count($module) > 0){
    $module=$module[0];
    $fullname=$module['fullname'];
    $item_id=$module['item_id'];
    $component=$module['component'];
    $list=json_decode($item_id);            
    if(count($list) > 0){
      $list=convertToArray($list);
      $items=array();
      foreach($list as $key => $value){
        $id=@$value['id'];
        $item=array();
        switch ($component) {
          case 'article':
          $item=ArticleModel::whereRaw('id = ? and status = ?',[(int)@$id,(int)@$status])->get()->toArray();
          break;          
          case 'product':            
          $item=ProductModel::whereRaw('id = ? and status = ?',[(int)@$id,(int)@$status])->get()->toArray();
          break;
          case 'page':            
          $item=PageModel::whereRaw('id = ? and status = ?',[(int)@$id,(int)@$status])->get()->toArray();
          break;
        }            
        if(count($item) > 0){
          $items[]=$item[0];
        }                    
      }
      $data=array(
        "fullname"    =>  $fullname,
        "items"       =>  $items
      );
    }
  }            
  return $data;
}
function wp_nav_menu($args){
  $theme_location=$args['theme_location'];
  $data_menu_type=MenuTypeModel::whereRaw("trim(lower(theme_location)) = ? and status = 1",[trim(mb_strtolower($theme_location))])->select('id','fullname')->get()->toArray();
  $arr_menu=array();  
  $menu_str              =  "";      
  $lanDau                =  0;  
  $wrapper='';  
  if(count($data_menu_type) > 0){
    $data_menu_type=@$data_menu_type[0];
    $data_menu=MenuModel::whereRaw('menu_type_id = ? and status = 1',[(int)@$data_menu_type['id']])->orderBy('sort_order','asc')->get()->toArray();        
    if(count($data_menu) > 0){
      for ($i=0;$i<count($data_menu);$i++) {
        $menu=array();
        $menu=$data_menu[$i];
        $site_link='';
        if(!empty( trim($data_menu[$i]["alias"]) )){
          switch ($data_menu[$i]["alias"]) {
            case 'gio-hang':
            case 'dang-ky':
            case 'tai-khoan':
            case 'dang-nhap':
            case 'bao-mat':
            case 'lien-he':
            case 'xac-nhan-thanh-toan':
            case 'dang-nhap-thanh-toan':
            case 'hoa-don':                       
            $site_link=url('/'.$data_menu[$i]["alias"]) ;
            break;                               
            case 'trang-chu':
            $site_link=url('/');
            break;
            default:     
            $site_link=url('/'.$data_menu[$i]["alias"].".html") ;       
            break;
          }        
        }else{
          $site_link='javascript:void(0);';
        }        
        $menu["site_link"] =$site_link;            
        $data_child=MenuModel::whereRaw('parent_id = ?',[(int)$data_menu[$i]["id"]])->select('id')->get()->toArray();
        if(count($data_child) > 0){
          $menu["havechild"]=1;
        }else{
          $menu["havechild"]=0;
        }
        $arr_menu[]=$menu;
      }
      mooMenuRecursive($arr_menu,0,$menu_str,$lanDau,$args['alias'],$args['menu_class'],$args['menu_li_actived'],$args['menu_item_has_children'],$args['link_before'],$args['link_after']);
      $menu_str = str_replace('<ul></ul>', '', $menu_str);    
      if(!empty($args['before_wrapper'])){
        if(!empty($args['before_title'])){
          $wrapper=$args['before_wrapper'].$args['before_title'].$data_menu_type['fullname'].$args['after_title'].$args['before_wrapper_ul'].$menu_str.$args['after_wrapper_ul'].$args['after_wrapper'];
        }else{
          $wrapper=$args['before_wrapper'].$args['before_wrapper_ul'].$menu_str.$args['after_wrapper_ul'].$args['after_wrapper'];
        }
      }    
      else{
        $wrapper=$menu_str;
      }
    }    
  }  
  echo $wrapper;
}
function mooMenuRecursive($source,$parent,&$menu_str,&$lanDau,$alias,$menu_class,$menu_li_actived,$menu_item_has_children,$link_before,$link_after){
  if(count($source) > 0){          
    $menu_str .='<ul>';
    if($lanDau == 0){
      $menu_str ='<ul class="'.$menu_class.'"  >';
    }                          
    foreach ($source as $key => $value) 
    {                  
      if((int)$value["parent_id"]==(int)$parent)
      {
        $link=@$value["site_link"];
        $class_activated=0;          
        if( strcmp(trim(mb_strtolower($value["alias"])),trim(mb_strtolower($alias)))   ==  0 ){
          $class_activated=1;                              
        }                                        
        $class_li='';                            
        if((int)@$class_activated==1){
          $class_li .=$menu_li_actived.' ';
        }                        
        if((int)@$value["havechild"]==1){
          $class_li .=$menu_item_has_children;
        }
        $fullname=$link_before . $value['fullname'] . $link_after;           
        $a='<a href="'.$link.'">'.$fullname.'</a>';
        if(!empty($class_li)){
          $li='<li class="'.$class_li.'"  >'.$a;                                        
        }else{
          $li='<li>'.$a;                                        
        }      
        $menu_str .=$li;  
        unset($source[$key]);
        $newParent=$value["id"];
        $lanDau =$lanDau+1;
        mooMenuRecursive($source,$newParent,$menu_str,$lanDau,$alias,$menu_class,$menu_li_actived,$menu_item_has_children,$link_before,$link_after);
        $menu_str .='</li>';
      }
    }
    $menu_str .='</ul>'; 
  }
}

function getRecursiveCategoryProduct($parent_id,&$arrCategory){  
  $data=CategoryProductModel::find((int)@$parent_id);  
  if(count($data)>0){
    $data=$data->toArray();
    $arrCategory[]=$data;  
    getRecursiveCategoryProduct((int)@$data['parent_id'],$arrCategory);
  }  
}
function getBreadCrumbCategoryProduct($dataCategory){
  $i=1;
  $data=array();
  $breadcrumb='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'.url('/').'"><span itemprop="name">Trang chủ</span></a><meta itemprop="position" content="'.(int)@$i.'" /></li>';  
  getRecursiveCategoryProduct((int)@$dataCategory['parent_id'],$data);  
  $data[]=$dataCategory;    
  $i++;
  if(count($data) > 0){
    foreach ($data as $key => $value) {
      $id=$value['id'];
      $fullname=$value['fullname'];
      $alias=$value['alias'];
      $parent_id=$value['parent_id'];      
      $permalink=route('frontend.index.index',[$alias]);      
      $breadcrumb .='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'.$permalink.'"><span itemprop="name">'.$fullname.'</span></a><i class="fa fa-angle-right"></i>
      <meta itemprop="position" content="'.(int)@$i.'" />
      </li>';
      $i++;
    }
  }
  return $breadcrumb;
}
function getRecursiveCategoryArticle($parent_id,&$arrCategory){  
  $data=CategoryArticleModel::find((int)@$parent_id);  
  if(count($data)>0){
    $data=$data->toArray();
    $arrCategory[]=$data;  
    getRecursiveCategoryArticle((int)@$data['parent_id'],$arrCategory);
  }  
}
function getBreadCrumbCategoryArticle($dataCategory){

  $i=1;
  $data=array();
  $breadcrumb='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'.url('/').'"><span itemprop="name">Trang chủ</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="'.(int)@$i.'" /></li>';
  getRecursiveCategoryArticle((int)@$dataCategory['parent_id'],$data);  
  $data=get_field_data_array($data,'id');
  ksort($data);    
  $data[]=$dataCategory;      
  $i++;
  if(count($data) > 0){
    foreach ($data as $key => $value) {
      $id=$value['id'];
      $fullname=$value['fullname'];
      $alias=$value['alias'];
      $parent_id=$value['parent_id'];      
      $permalink=route('frontend.index.index',[$alias]);      
      $breadcrumb .='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'.$permalink.'"><span itemprop="name">'.$fullname.'</span></a><i class="fa fa-angle-right"></i>
      <meta itemprop="position" content="'.(int)@$i.'" />
      </li>';
      $i++;
    }
  }
  return $breadcrumb;
}
function getRecursiveMenu($alias,&$arrMenu){
  $query=DB::table('menu')
  ->join('menu_type','menu.menu_type_id', '=' ,'menu_type.id');
  $query->where('menu_type.theme_location','main-menu');
  $query->where('menu.alias',$alias);
  $query->select('menu.id','menu.fullname','menu.alias','menu.parent_id');
  $data=$query->get()->toArray();  
  if(count($data) > 0){
    $data=convertToArray($data);
    $data1=$data[0];
    $parent_id=$data1['parent_id'];
    $query2=DB::table('menu')
    ->join('menu_type','menu.menu_type_id', '=' ,'menu_type.id');
    $query2->where('menu_type.theme_location','main-menu');
    $query2->where('menu.id',(int)@$parent_id);
    $query2->select('menu.id','menu.fullname','menu.alias','menu.parent_id');
    $data2=$query2->get()->toArray();  
    if(count($data2) > 0){
      $data2=convertToArray($data2);
      $data3=$data2[0];
      $row=array(
        'id'        =>  $data3['id'],
        'fullname'  =>  $data3['fullname'],
        'alias'     =>  $data3['alias'],
        'parent_id' =>  $data3['parent_id']
      );
      $arrMenu[]=$row;
      getRecursiveMenu($data3['alias'],$arrMenu);
    }    
  }      
}
function getBreadCrumb($alias){
  $arrMenu=array();
  $strBreadcrumb='';
  getRecursiveMenu($alias,$arrMenu);  
  if(count($arrMenu) > 0){          
    $menuAlias=DB::table('menu')
                  ->join('menu_type','menu.menu_type_id','=','menu_type.id')
                  ->where('menu_type.theme_location','main-menu')
                  ->where('alias',$alias)
                  ->select('menu.id','menu.fullname','menu.alias','menu.parent_id')
                  ->groupBy('menu.id','menu.fullname','menu.alias','menu.parent_id')
                  ->get()
                  ->toArray();                  
    $menuAlias=convertToArray($menuAlias);
    $menuAlias=$menuAlias[0];
    $rowHome=array( 
        'id'        =>  -1,
        'fullname'  =>  'Trang chủ',
        'alias'     =>  'trang-chu',
        'parent_id' =>  0
      );
    $rowAlias=array(      
        'id'        =>  $menuAlias['id'],  
        'fullname'  =>  $menuAlias['fullname'],
        'alias'     =>  $menuAlias['alias'],
        'parent_id' =>  $menuAlias['parent_id']
      );
    $arrMenu[]=$rowHome;
    $arrMenu[]=$rowAlias;
    $data= get_field_data_array($arrMenu,'id');
    ksort($data);  
    foreach ($data as $key => $value) {      
      $fullname=$value['fullname'];
      $alias=$value['alias'];
      $parent_id=$value['parent_id'];
      $permalink='';
      switch ($alias) {
        case 'trang-chu':
          $permalink=url('/');
          break;        
        default:
          $permalink=route('frontend.index.index',[$alias]);
          break;
      }      
      $strBreadcrumb .='<a href="'.$permalink.'">'.$fullname.'</a>';
    }
  }
  return $strBreadcrumb;
}
function wp_nav_menu_top($args){
  $theme_location=$args['theme_location'];
  $data_menu_type=MenuTypeModel::whereRaw("trim(lower(theme_location)) = ? and status = 1",[trim(mb_strtolower($theme_location))])->select('id','fullname')->get()->toArray();
  $arr_menu=array();  
  $menu_str              =  "";      
  $lanDau                =  0;  
  $wrapper='';  
  if(count($data_menu_type) > 0){
    $data_menu_type=@$data_menu_type[0];
    $data_menu=MenuModel::whereRaw('menu_type_id = ? and status = 1',[(int)@$data_menu_type['id']])->orderBy('sort_order','asc')->get()->toArray();        
    if(count($data_menu) > 0){
      for ($i=0;$i<count($data_menu);$i++) {
        $menu=array();
        $menu=$data_menu[$i];
        $site_link='';
        if(!empty( trim($data_menu[$i]["alias"]) )){
          switch ($data_menu[$i]["alias"]) {            
            case 'tk-ntd':                       
            $site_link=route('frontend.index.viewEmployerAccount') ;
            break;      
            case 'tk-ungvien':                       
            $site_link=route('frontend.index.viewCandidateAccount') ;
            break;                               
            case 'trang-chu':
            $site_link=url('/');
            break;
            default:     
            $site_link=url('/'.$data_menu[$i]["alias"].".html") ;       
            break;
          }        
        }else{
          $site_link='javascript:void(0);';
        }        
        $menu["site_link"] =$site_link;            
        $data_child=MenuModel::whereRaw('parent_id = ?',[(int)$data_menu[$i]["id"]])->select('id')->get()->toArray();
        if(count($data_child) > 0){
          $menu["havechild"]=1;
        }else{
          $menu["havechild"]=0;
        }
        $arr_menu[]=$menu;
      }
      mooMenuRecursive_menu_top($arr_menu,0,$menu_str,$lanDau,$args['alias'],$args['menu_class'],$args['menu_li_actived'],$args['menu_item_has_children'],$args['link_before'],$args['link_after']);
      $menu_str = str_replace('<ul></ul>', '', $menu_str);    
      if(!empty($args['before_wrapper'])){
        if(!empty($args['before_title'])){
          $wrapper=$args['before_wrapper'].$args['before_title'].$data_menu_type['fullname'].$args['after_title'].$args['before_wrapper_ul'].$menu_str.$args['after_wrapper_ul'].$args['after_wrapper'];
        }else{
          $wrapper=$args['before_wrapper'].$args['before_wrapper_ul'].$menu_str.$args['after_wrapper_ul'].$args['after_wrapper'];
        }
      }    
      else{
        $wrapper=$menu_str;
      }
    }    
  }  
  echo $wrapper;
}
function mooMenuRecursive_menu_top($source,$parent,&$menu_str,&$lanDau,$alias,$menu_class,$menu_li_actived,$menu_item_has_children,$link_before,$link_after){
  if(count($source) > 0){          
    $menu_str .='<ul>';
    if($lanDau == 0){
      $menu_str ='<ul class="'.$menu_class.'"  >';
    }                          
    foreach ($source as $key => $value) 
    {                  
      if((int)@$value["parent_id"]==(int)$parent)
      {
        $link=@$value["site_link"];
       
        if((int)@$value['level'] == 0){
          if((int)@$value["havechild"]==1){
            $a='<a href="'.$link.'" class="gc_main_navigation">'.@$value['fullname'] . '&nbsp;' . @$link_after.'</a>';
          }else{
            $a='<a href="'.$link.'" class="gc_main_navigation">'.@$value['fullname'] .'</a>';
          }          
        }else{
          $a='<a href="'.$link.'" >'.@$value['fullname'].'</a>';
        }             
        if((int)@$value['level'] == 0){           
          $li='<li class="has-mega gc_main_navigation"  >'.$a;                                        
        }else{
          $li='<li class="parent"  >'.$a;                                        
        }       
        $menu_str .=$li;  
        unset($source[$key]);
        $newParent=$value["id"];
        $lanDau =$lanDau+1;
        mooMenuRecursive_menu_top($source,$newParent,$menu_str,$lanDau,$alias,$menu_class,$menu_li_actived,$menu_item_has_children,$link_before,$link_after);
        $menu_str .='</li>';
      }
    }
    $menu_str .='</ul>'; 
  }
}
?>