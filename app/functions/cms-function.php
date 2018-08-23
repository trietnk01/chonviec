<?php
function cmsStatus($id,$statusValue,$kicked){
  $strStatus = ($statusValue == 0) ? 'unpublish' : 'publish';
  $xhtml    = '<a class="jgrid" id="status-'.$id.'" href="javascript:void(0)" onclick="changeStatus('.$id.','.$kicked.');">
  <span class="state '.$strStatus.'">&nbsp;</span>
  </a>';
  return $xhtml;
}
function cmsStatusLink($id,$statusValue,$kicked,$link){
  $strStatus = ($statusValue == 0) ? 'unpublish' : 'publish';
  $xhtml    = '<a class="jgrid" id="status-'.$id.'" href="javascript:void(0)" onclick="changeStatusLink('.$id.','.$kicked.','.$link.',this);">
  <span class="state '.$strStatus.'">&nbsp;</span>
  </a>';
  return $xhtml;
}
function cmsSelectbox($name = "",$class="",$arrValue=array(), $keySelect = "",$disabled){
  $xhtml = '<select  name="'.$name.'" class="'.$class.'" '.$disabled.' >';  
  foreach($arrValue as $key => $value){
    if((int)$key == (int)@$keySelect ){
      $xhtml .= '<option selected="selected" value = "'.$key.'">'.$value.'</option>';
    }else{
      $xhtml .= '<option value = "'.$key.'">'.$value.'</option>';
    }
  }
  $xhtml .= '</select>';
  return $xhtml;
}
function cmsSelectboxCategory($name, $class, $arrValue, $val = 0,$disabled,$slogan=""){   
  $xhtml = '<select  name="'.$name.'" class="'.$class.'" '.$disabled.'  >';
  $xhtml .= '<option value = "">'.$slogan.'</option>';
  foreach($arrValue as $key => $value){
    if((int)$value["id"] == (int)$val){
      $xhtml .= '<option selected="selected" value = "'.$value["id"].'">'.$value["fullname"].'</option>';
    }else{
      $xhtml .= '<option value = "'.$value["id"].'">'.$value["fullname"].'</option>';
    }
  }
  $xhtml .= '</select>';
  return $xhtml;
}
function cmsSelectboxMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
    $arrName=$name.'[]';
    $xhtml = '<select size="20"  name="'.$arrName.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
    $xhtml .= '<option value = "">'.$slogan.'</option>';
    foreach($arrValue as $key => $value){
      $id=$value["id"];
      $name=$value["fullname"];     
      $strOption='<option value="'.$id.'">'.$name.'</option>';
      if(!empty($arrValueSelected)){
          foreach($arrValueSelected as $key_1 => $value_1) {
              if((int)$id == (int)$value_1){
                $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
              }
          }
      }      
      $xhtml .=$strOption;
    }
    $xhtml .= '</select>';
    return $xhtml;
  }
function cmsSelectboxCategoryArticleMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
    $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
    $xhtml .= '<option value = "">'.$slogan.'</option>';
    foreach($arrValue as $key => $value){
      $id=$value["id"];
      $name=$value["fullname"];     
      $strOption='<option value="'.$id.'">'.$name.'</option>';
      if(!empty($arrValueSelected)){
          foreach($arrValueSelected as $key_1 => $value_1) {
              if((int)$id == (int)$value_1["category_id"]){
                $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
              }
          }
      }      
      $xhtml .=$strOption;
    }
    $xhtml .= '</select>';
    return $xhtml;
  }
  function cmsSelectboxGroupMemberMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
    $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
    $xhtml .= '<option value = "">'.$slogan.'</option>';
    foreach($arrValue as $key => $value){
      $id=$value["id"];
      $name=$value["fullname"];     
      $strOption='<option value="'.$id.'">'.$name.'</option>';
      if(!empty($arrValueSelected)){
          foreach($arrValueSelected as $key_1 => $value_1) {
              if((int)$id == (int)$value_1["group_member_id"]){
                $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
              }
          }
      }      
      $xhtml .=$strOption;
    }
    $xhtml .= '</select>';
    return $xhtml;
  }
  function cmsSelectboxCategoryParamMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
    $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
    $xhtml .= '<option value = "">'.$slogan.'</option>';
    foreach($arrValue as $key => $value){
      $id=$value["id"];
      $name=$value["fullname"];     
      $strOption='<option value="'.$id.'">'.$name.'</option>';
      if(!empty($arrValueSelected)){
          foreach($arrValueSelected as $key_1 => $value_1) {
              if((int)$id == (int)$value_1["param_id"]){
                $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
              }
          }
      }      
      $xhtml .=$strOption;
    }
    $xhtml .= '</select>';
    return $xhtml;
  }
function cmsSelectboxMenuMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
  $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
  $xhtml .= '<option value = "">'.$slogan.'</option>';
  foreach($arrValue as $key => $value){
    $id=$value["id"];
    $name=$value["fullname"];     
    $strOption='<option value="'.$id.'">'.$name.'</option>';
    if(!empty($arrValueSelected)){
      foreach($arrValueSelected as $key_1 => $value_1) {
        if((int)$id == (int)$value_1["menu_id"]){
          $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
        }
      }
    }      
    $xhtml .=$strOption;
  }
  $xhtml .= '</select>';
  return $xhtml;
}  
function cmsSelectboxGroupMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
  $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
  $xhtml .= '<option value = "">'.$slogan.'</option>';
  foreach($arrValue as $key => $value){
    $id=$value["id"];
    $name=$value["fullname"];     
    $strOption='<option value="'.$id.'">'.$name.'</option>';
    if(!empty($arrValueSelected)){
      foreach($arrValueSelected as $key_1 => $value_1) {
        if((int)$id == (int)$value_1["group_id"]){
          $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
        }
      }
    }      
    $xhtml .=$strOption;
  }
  $xhtml .= '</select>';
  return $xhtml;
}
function cmsSelectboxGroupPrivilegeMultiple($name, $class, $arrValue, $arrValueSelected,$disabled,$slogan=""){
  $xhtml = '<select size="20"  name="'.$name.'" class="'.$class.'" '.$disabled.' multiple="multiple" >';
  $xhtml .= '<option value = "">'.$slogan.'</option>';
  foreach($arrValue as $key => $value){
    $id=$value["id"];
    $name=$value["fullname"];     
    $strOption='<option value="'.$id.'">'.$name.'</option>';
    if(!empty($arrValueSelected)){
      foreach($arrValueSelected as $key_1 => $value_1) {
        if((int)$id == (int)$value_1["privilege_id"]){
          $strOption = str_replace('<option', '<option selected="selected" ', $strOption);
        }
      }
    }      
    $xhtml .=$strOption;
  }
  $xhtml .= '</select>';
  return $xhtml;
}
?>