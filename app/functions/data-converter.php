<?php 
function article2Converter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);        
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];    
            $category_name= getCategoryArticleName($id);  
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithListArticle(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                
                "category_name"                    =>   $category_name,                
                "image"                    =>   $image,
                
                
            );
        }
    }
    return $result;
}
function product2Converter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $product_width.'x'.$product_height . "-".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          
            $id=$data[$i]["id"];  
            $alias=$data[$i]['alias'];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithListProduct(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                
                "fullname"                 =>   $data[$i]["fullname"], 
                
                "category_name"            =>   $data[$i]["category_name"],                
                "image"                    =>   $image,
                
            );
        }
    }
    return $result;
}
function categoryArticleConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $linkDelete=route('adminsystem.'.$controller.'.deleteItem',[$data[$i]['id']]);
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.$linkDelete.'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            
            $sort_order = '<center><input name="sort_order['.$data[$i]['id'].']" type="text"   value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          

            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox"  name="cid[]" value="'.$data[$i]["id"].'" />',                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "parent_fullname"          =>   $data[$i]["parent_fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "parent_id"                =>   $data[$i]["parent_id"],
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function categoryParamConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $linkDelete=route('adminsystem.'.$controller.'.deleteItem',[$data[$i]['id']]);
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.$linkDelete.'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            
            $sort_order = '<center><input name="sort_order['.$data[$i]['id'].']" type="text"   value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                              
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox"  name="cid[]" value="'.$data[$i]["id"].'" />',                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "parent_fullname"          =>   $data[$i]["parent_fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "parent_id"                =>   $data[$i]["parent_id"],
                "param_value"              =>   $data[$i]["param_value"],
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function getCategoryArticleName($id=0){    
    $title="";
    $data=DB::table('article')
            ->join('article_category','article.id','=','article_category.article_id')
            ->join('category_article','article_category.category_id','=','category_article.id')
            ->select('article.id','article.fullname as article_name','category_article.fullname')
            ->where('article.id','=',@$id)
            ->get();
    if(count($data) > 0){
        $data=$data->toArray();
        $data=convertToArray($data);
        foreach ($data as $key => $value) {
            $title .=$value['fullname'].',';
        }
    }    
    $title=substr($title, 0,strlen($title)-1);
    return $title;
}
function getGroupMemberName($id=0){    
    $title="";
    $data=DB::table('users')
            ->join('user_group_member','users.id','=','user_group_member.user_id')
            ->join('group_member','group_member.id','=','user_group_member.group_member_id')
            ->select('users.id','users.fullname as user_fullname','group_member.fullname')
            ->where('users.id','=',@$id)
            ->get();
    if(count($data) > 0){
        $data=$data->toArray();
        $data=convertToArray($data);
        foreach ($data as $key => $value) {
            $title .=$value['fullname'].',';
        }
    }    
    $title=substr($title, 0,strlen($title)-1);
    return $title;
}

function articleConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);        
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];    
            $category_name= getCategoryArticleName($id);  
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                
                "category_name"                    =>   $category_name,                
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function projectConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);        
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                               
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function projectArticleConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);    
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid" />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],       
                "project_name"             =>   $data[$i]["project_name"]  ,                      
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function menuTypeConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>'; 
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';           
            $entranced='<center><a href="'.route('adminsystem.menu.getList',[$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/entrance.png").'" /></a></center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';        
            $fullname =$data[$i]["fullname"];

            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $fullname,
                "theme_location"                 =>   $data[$i]["theme_location"],                                
                "sort_order"               =>   $sort_order,                               
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "entranced"                =>   $entranced,
                "status"                   =>   $status,
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted                
            );
        }
    }
    return $result;
}
function menuConverter($data=array(),$controller){        
    $result = array();

    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $alias='no-alias';
            if(!empty($data[$i]['alias'])){
                $alias=$data[$i]['alias'];
            }            
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['menu_type_id'],$data[$i]['id'],$alias]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $linkDelete=route('adminsystem.'.$controller.'.deleteItem',[$data[$i]['id']]);
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.$linkDelete.'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            
            $sort_order = '<center><input name="sort_order['.$data[$i]['id'].']" type="text"  value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox"  name="cid[]" value="'.$data[$i]["id"].'" />',                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "level"                    =>   $data[$i]["level"],                               
                "parent_id"                =>   $data[$i]["parent_id"],
                "parent_fullname"          =>   $data[$i]["parent_fullname"],                
                "menu_type_id"             =>   $data[$i]["menu_type_id"],                
                "level"                    =>   $data[$i]["level"],                
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function productParamConverter($data=array(),$controller){        
    $result = array();

    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $alias='no-alias';
            if(!empty($data[$i]['alias'])){
                $alias=$data[$i]['alias'];
            }            
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['menu_type_id'],$data[$i]['id'],$alias]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $linkDelete=route('adminsystem.'.$controller.'.deleteItem',[$data[$i]['id']]);
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.$linkDelete.'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            
            $sort_order = '<center><input name="sort_order['.$data[$i]['id'].']" type="text"  value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox"  name="cid[]" value="'.$data[$i]["id"].'" />',                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "level"                    =>   $data[$i]["level"],                               
                "parent_id"                =>   $data[$i]["parent_id"],
                "parent_fullname"          =>   $data[$i]["parent_fullname"],                
                "menu_type_id"             =>   $data[$i]["menu_type_id"],                
                "level"                    =>   $data[$i]["level"],                
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function bannerConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['category_id'],$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }      
            $category_name=$data[$i]["category_name"]; 
            $id=$data[$i]["id"];       
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,               
                "image"                    =>   $image,
                "category_name"            =>   $category_name,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function categoryProductConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $linkDelete=route('adminsystem.'.$controller.'.deleteItem',[$data[$i]['id']]);
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.$linkDelete.'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            
            $sort_order = '<center><input name="sort_order['.$data[$i]['id'].']" type="text"  value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" .$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox"  name="cid[]" value="'.$data[$i]["id"].'" />',               
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "parent_fullname"          =>   $data[$i]["parent_fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "parent_id"                =>   $data[$i]["parent_id"],
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function productConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $product_width.'x'.$product_height . "-".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          
            $id=$data[$i]["id"];  
            $alias=$data[$i]['alias'];            
            $price='<div class="calmoney">'.fnPrice((int)@$data[$i]["price"]).'</div>'; 
            $sale_price='<div class="calmoney">'.fnPrice((int)@$data[$i]["sale_price"]).'</div>'; 
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "code"                     =>   $data[$i]["code"],
                "fullname"                 =>   $data[$i]["fullname"], 
                "alias"                    =>   $alias     ,          
                "category_name"            =>   $data[$i]['category_name'],                
                "image"                    =>   $image,
                "price"                    =>   $price,
                "sale_price"                    =>   $sale_price,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function product3Converter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('frontend.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            $status='';
            if((int)$data[$i]["status"]==1){
                $status     = '<center><img src="'.asset('/public/adminsystem/images/active.png').'"  /></center>';
            }
            else{
                $status     = '<center><img src="'.asset('/public/adminsystem/images/inactive.png').'"  /></center>';
            }                        
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $product_width.'x'.$product_height . "-".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          
            $id=$data[$i]["id"];  
            $alias=$data[$i]['alias'];            
            $price='<div class="calmoney">'.fnPrice((int)@$data[$i]["price"]).'</div>'; 
            $sale_price='<div class="calmoney">'.fnPrice((int)@$data[$i]["sale_price"]).'</div>'; 
            $fullname='<a href="'.route('frontend.index.index',[$alias]).'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "code"                     =>   $data[$i]["code"],
                "fullname"                 =>   $fullname, 
                "alias"                    =>   $alias     ,          
                "category_name"            =>   $data[$i]['category_name'],                
                "image"                    =>   $image,
                "price"                    =>   $price,
                "sale_price"               =>   $sale_price,
                
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function categoryBannerConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>'; 
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';           
            $entranced='<center><a href="'.route('adminsystem.banner.getList',[$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/entrance.png").'" /></a></center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';        
            $fullname =$data[$i]["fullname"];

            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $fullname,
                "theme_location"                 =>   $data[$i]["theme_location"],                                
                "sort_order"               =>   $sort_order,                               
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "entranced"                =>   $entranced,
                "status"                   =>   $status,
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted                
            );
        }
    }
    return $result;
}
function moduleItemConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],         
                "position"                 =>   $data[$i]["position"],                                                    
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function paymentMethodConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],         
                "alias"                 =>   $data[$i]["alias"],                                                    
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function settingSystemConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],         
                "alias"                 =>   $data[$i]["alias"],                                                    
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}

function groupMemberConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                        
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],                                                                
                "sort_order"               =>   $sort_order,                
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function userConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';         
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $group_member_name=getGroupMemberName((int)@$data[$i]["id"]);
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';   
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "username"                 =>   $data[$i]["username"],                
                "email"                    =>   $data[$i]["email"],                
                "fullname"                 =>   $data[$i]["fullname"],      
                "group_member_name"        =>   $group_member_name,          
                "status"                   =>   $status,
                "sort_order"               =>   $sort_order,                
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function privilegeConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $data[$i]["fullname"],
                "controller"               =>   $data[$i]["controller"],
                "action"                   =>   $data[$i]["action"]   ,                                   
                "sort_order"               =>   $sort_order,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function customerConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
                    
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "username"                 =>   $data[$i]["username"],
                "email"                    =>   $data[$i]["email"],
                "fullname"                 =>   $data[$i]["fullname"],
                "address"                  =>   $data[$i]["address"],
                "phone"                    =>   $data[$i]["phone"],
                "mobilephone"              =>   $data[$i]["mobilephone"],
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function invoiceConverter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
           
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "code"                     =>   $data[$i]["code"],                
                "email"                    =>   $data[$i]["email"],
                "fullname"                 =>   $data[$i]["fullname"],
                "address"                  =>   $data[$i]["address"],
                "phone"                    =>   $data[$i]["phone"],                
                "quantity"                 =>   $data[$i]["quantity"],
                "total_price"              =>   $data[$i]["total_price"],                
                
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function invoice2Converter($data=array(),$controller){        
    $result = array();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('frontend.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked='';
            if((int)$data[$i]["status"]==1){
                $kicked='Đã giao hàng';
            }
            else{
                $kicked='Chưa giao hàng';
            }
            $status     = '<center>'.$kicked.'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $data[$i]["id"],
                "code"                     =>   $data[$i]["code"],
                "username"                 =>   $data[$i]["username"],
                "email"                    =>   $data[$i]["email"],
                "fullname"                 =>   $data[$i]["fullname"],
                "address"                  =>   $data[$i]["address"],
                "phone"                    =>   $data[$i]["phone"],                
                "quantity"                 =>   $data[$i]["quantity"],
                "total_price"              =>   $data[$i]["total_price"],                
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function itemArticleConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem(this)"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'"  value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=$data[$i]["id"]; 
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }         
            $result[$i] = array(                
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid" />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],     
                "image"                    =>   $image,                                             
                "sort_order"               =>   $sort_order,                                            
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function itemProductConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem(this)"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'"  value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=$data[$i]["id"]; 
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $product_width.'x'.$product_height . "-".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          
            $result[$i] = array(   
            'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid" />',
                'is_checked'               =>   0,             
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],     
                "image"                    =>   $image,                                             
                "sort_order"               =>   $sort_order,                                            
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}

function categoryArticleComponentConverter($data=array(),$controller,$menu_type_id){        
    $result = array();    
    $setting= getSettingSystem();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='';
            $linkDelete='';
            $deleted='';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '';
            
            $sort_order = '<center>'.$data[$i]["sort_order"].'</center>';            
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }          
            $linkMenu=route('adminsystem.menu.getForm',['add',$menu_type_id,0,$data[$i]["alias"]]);
            $fullname='<a href="'.$linkMenu.'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $fullname,
                "parent_fullname"          =>   $data[$i]["parent_fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "parent_id"                =>   $data[$i]["parent_id"],
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function categoryProductComponentConverter($data=array(),$controller,$menu_type_id){        
    $result = array();    
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='';
            $linkDelete='';
            $deleted='';
            
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '';
            
            $sort_order = '<center>'.$data[$i]["sort_order"].'</center>';            
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }         
            $linkMenu=route('adminsystem.menu.getForm',['add',$menu_type_id,0,$data[$i]["alias"]]);
            $fullname='<a href="'.$linkMenu.'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(                
                "id"                       =>   $data[$i]["id"],
                "fullname"                 =>   $fullname,
                "parent_fullname"          =>   $data[$i]["parent_fullname"],
                "alias"                    =>   $data[$i]["alias"],
                "parent_id"                =>   $data[$i]["parent_id"],
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function articleComponentConverter($data=array(),$controller,$menu_type_id){        
    $result = array();
    $setting= getSettingSystem();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $sort_order = '<center>'.$data[$i]["sort_order"].'</center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];      
            $category_name= getCategoryArticleName($id);   
            $linkMenu=route('adminsystem.menu.getForm',['add',$menu_type_id,0,$data[$i]["alias"]]);
            $fullname='<a href="'.$linkMenu.'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(                   
                "id"                       =>   $id,
                "fullname"                 =>   $fullname, 
                "category_name"                    =>   $category_name,                                           
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,                
            );
        }
    }
    return $result;
}
function pageComponentConverter($data=array(),$controller,$menu_type_id){        
    $result = array();
    $setting= getSettingSystem();
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $sort_order = '<center>'.$data[$i]["sort_order"].'</center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];       
            $linkMenu=route('adminsystem.menu.getForm',['add',$menu_type_id,0,$data[$i]["alias"]]);
            $fullname='<a href="'.$linkMenu.'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(                       
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                                
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,                
            );
        }
    }
    return $result;
}
function productComponentConverter($data=array(),$controller,$menu_type_id){        
    $result = array();
    $setting= getSettingSystem();
    $product_width=$setting['product_width']['field_value'];
    $product_height=$setting['product_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $sort_order = '<center>'.$data[$i]["sort_order"].'</center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $product_width.'x'.$product_height . "-".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }           
            $id=$data[$i]["id"];               
            $linkMenu=route('adminsystem.menu.getForm',['add',$menu_type_id,0,$data[$i]["alias"]]);
            $fullname='<a href="'.$linkMenu.'">'.$data[$i]['fullname'].'</a>';
            $result[$i] = array(                      
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,   
                "category_name"            =>   $data[$i]['category_name'],                                    
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,                
            );
        }
    }
    return $result;
}
function pageConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)$data[$i]["id"],(int)$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order" id="sort-order-'.$data[$i]["id"].'" sort_order_id="'.$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty($data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);        
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];       
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                
                "alias"                    =>   $data[$i]["alias"],
                "theme_location"           =>   $data[$i]["theme_location"],                
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function mediaConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem(\''.$data[$i].'\')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $id=$data[$i];      
            $featured_file="";
            $file_path=base_path("upload".DS.$data[$i]);
            if(file_exists($file_path)){
                /* begin check if file image */
                if(@is_array(getimagesize($file_path))){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('upload/'.$data[$i]).'" ><img src="'.asset('upload/'.$data[$i]).'" style="width:25%" /></a>';
                }
                /* end check if file image */
                /* begin check if file ico */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.ico)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('upload/'.$data[$i]).'" ><img src="'.asset('upload/'.$data[$i]).'" /></a>';
                }
                /* end check if file ico */
                /* begin check if file word */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.doc|.docx)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/word.png').'"><img src="'.asset('public/adminsystem/images/word.png').'" /></a>';
                }
                /* end check if file word */
                /* begin check if file excel */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/excel.png').'"><img src="'.asset('public/adminsystem/images/excel.png').'" /></a>';
                }
                /* end check if file excel */
                /* begin check if file pdf */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.pdf)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/pdf.png').'"><img src="'.asset('public/adminsystem/images/pdf.png').'" /></a>';
                }
                /* end check if file pdf */
                /* begin check if file pdf */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.zip|.rar)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/zip.png').'"><img src="'.asset('public/adminsystem/images/zip.png').'" /></a>';
                }
                /* end check if file pdf */
            }            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "featured_file"            =>   $featured_file,
                "fullname"                 =>   $id,                                                        
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function media2Converter($data=array(),$controller,$directory){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem(\''.$data[$i].'\')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $id=$data[$i];      
            $featured_file="";
            $file_path=base_path($directory.DS.$data[$i]);
            if(file_exists($file_path)){
                /* begin check if file image */
                if(@is_array(getimagesize($file_path))){
                    $featured_file='<a data-fancybox="gallery" href="'.asset($directory.'/'.$data[$i]).'" ><img src="'.asset($directory.'/'.$data[$i]).'" style="width:25%" /></a>';
                }
                /* end check if file image */
                /* begin check if file ico */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.ico)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset($directory.'/'.$data[$i]).'" ><img src="'.asset($directory.'/'.$data[$i]).'" /></a>';
                }
                /* end check if file ico */
                /* begin check if file word */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.doc|.docx)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/word.png').'"><img src="'.asset('public/adminsystem/images/word.png').'" /></a>';
                }
                /* end check if file word */
                /* begin check if file excel */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/excel.png').'"><img src="'.asset('public/adminsystem/images/excel.png').'" /></a>';
                }
                /* end check if file excel */
                /* begin check if file pdf */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.pdf)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/pdf.png').'"><img src="'.asset('public/adminsystem/images/pdf.png').'" /></a>';
                }
                /* end check if file pdf */
                /* begin check if file pdf */
                $pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.zip|.rar)$#";
                if(preg_match($pattern, $data[$i])==true){
                    $featured_file='<a data-fancybox="gallery" href="'.asset('public/adminsystem/images/zip.png').'"><img src="'.asset('public/adminsystem/images/zip.png').'" /></a>';
                }
                /* end check if file pdf */
            }            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "featured_file"            =>   $featured_file,
                "fullname"                 =>   $id,                                                        
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function memberConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){  
            $id=(int)@$data[$i]['id'];          
            $fullname=@$data[$i]['fullname'];
            $email=@$data[$i]['email'];
            $mobilephone=@$data[$i]['mobilephone'];
            $address=@$data[$i]['address'];
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" name="cid"  />',                
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                                
                "email"                    =>   $email,
                "mobilephone"              =>   $mobilephone,
                "address"                  =>   $address,                
            );
        }
    }
    return $result;
}
function supporterConverter($data=array(),$controller){        
    $result = array();
    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=@$data[$i]["id"];   
            $number_money='<div class="calmoney">'.fnPrice((int)@$data[$i]["number_money"]).'</div>';            
            $payment_method_name=@$data[$i]["payment_method_name"];
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                               
                "number_money"             =>   $number_money,
                "payment_method_name"      =>   $payment_method_name,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function organizationConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);      
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];
            $phone=$data[$i]["phone"];
            $email=$data[$i]["email"];
            $website=$data[$i]["website"];
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,         
                "image"                    =>   $image,                      
                "phone"                    =>   $phone,
                "email"                    =>   $email,
                "website"                  =>   $website,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function provinceConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function scaleConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function literacyConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function salaryConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function recruitmentConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';

            $status_employer='';
            if((int)@$data[$i]['status_employer'] == 1){
                $status_employer='<center><a href="javascript:void(0);" class="jgrid"><span class="state publish">&nbsp;</span></a></center';
            }else{
                $status_employer='<center><a href="javascript:void(0);" class="jgrid"><span class="state unpublish">&nbsp;</span></a></center';
            }            

            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';

            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];      
            $employer_fullname=@$data[$i]['employer_fullname'];            
            $user_fullname=@$data[$i]['user_fullname'];   
            $created_at='<center>'.getDateTime($data[$i]["created_at"]).'</center>';               
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                'employer_fullname'        =>   $employer_fullname,
                'user_fullname'            =>   $user_fullname,
                "status_employer"          =>   $status_employer,
                "status"                   =>   $status,
                "created_at"               =>   $created_at,
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function recruitmentProfileConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){        
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];      
            $recruitment_name=@$data[$i]['recruitment_name']; 
            $profile_id=@$data[$i]['profile_id'];
            $entranced='';
            $link_cv='';
            if(!empty(@$data[$i]['profile_id'])){
                $entranced='<center><a href="'.route('frontend.index.getAppliedProfileDetail',[@$profile_id,0]).'"><img src="'.asset("/public/adminsystem/images/entrance.png").'" /></a></center>';
            }
            if(!empty(@$data[$i]['file_attached'])){
                $link_cv='<div class="download-cv"><center><a href="'.asset('upload/'.@$data[$i]['file_attached']).'" target="_blank"><i class="fas fa-download"></i></a></center></div>';   
            }                       
            $result[$i] = array(                
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                'recruitment_name'           =>   $recruitment_name, 
                'entranced'               =>$entranced,
                'link_cv'               =>$link_cv,
                'created_at'            => '<center>'.datetimeConverterVn(@$data[$i]['created_at']).'</center>' ,
            );
        }
    }
    return $result;
}


function appliedRecruitmentConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){                                
            $entranced='';
            $file_attached='';
            if(!empty(@$data[$i]['profile_id'])){
                $entranced='<center><a href="'.route('frontend.index.getProfileDetail',[(int)@$data[$i]['profile_id']]).'"><img src="'.asset("/public/adminsystem/images/entrance.png").'" /></a></center>';
            }
            if(!empty(@$data[$i]['file_attached'])){
                $file_attached='<div class="download-cv"><center><a href="'.asset('upload/'.@$data[$i]['file_attached']).'" target="_blank"><i class="fas fa-download"></i></a></center></div>';   
            }                       
            $result[$i] = array(                
                "id"                    =>  @$data[$i]["id"],                                
                'recruitment_name'      =>  @$data[$i]['recruitment_name'], 
                'profile_name'          =>  @$data[$i]['profile_name'],                
                'file_attached'         =>  @$file_attached,
                'entranced'             =>  @$entranced,
                'created_at'            => '<center>'.datetimeConverterVn(@$data[$i]['created_at']).'</center>' ,
            );
        }
    }
    return $result;
}


function searchingProfileConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){        
            $profile_name='<a href="'.route('frontend.index.getAppliedProfileDetail',[@$data[$i]['id'],1]).'">'.@$data[$i]['profile_name'].'</a>';
            $result[$i] = array(    
                "id"                    =>  @$data[$i]['id']   ,         
                "profile_name"          =>  @$profile_name,
                "candidate_name"        =>  @$data[$i]['candidate_name'],                         
                'literacy_name'         =>  @$data[$i]['literacy_name'], 
                'experience_name'       =>  @$data[$i]['experience_name'],
                'salary'                =>  convertToTextPrice(@$data[$i]['salary']) ,                
            );
        }
    }
    return $result;
}
function savedProfileConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){        
            $profile_name='<a href="'.route('frontend.index.getAppliedProfileDetail',[@$data[$i]['id'],0]).'">'.@$data[$i]['profile_name'].'</a>';
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.route('frontend.index.deleteSavedProfile',[(int)@$data[$i]["id"]]).'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';        
            $result[$i] = array(    
                "id"                    =>  @$data[$i]['id']   ,         
                "profile_name"          =>  @$profile_name,
                "candidate_name"        =>  @$data[$i]['candidate_name'],                         
                'literacy_name'         =>  @$data[$i]['literacy_name'], 
                'experience_name'       =>  @$data[$i]['experience_name'],
                'salary'                =>  convertToTextPrice(@$data[$i]['salary']) ,     
                'deleted'               =>  @$deleted ,
                'created_at'            =>  datetimeConverterVn(@$data[$i]['created_at'])          
            );
        }
    }
    return $result;
}
function recruitment2Converter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.route('frontend.index.deleteRecruitment',[(int)@$data[$i]["id"]]).'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $edited='<center><a href="'.route('frontend.index.getFormRecruitment',['edit',(int)@$data[$i]["id"]]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';          
            $kicked=0;
            if((int)@$data[$i]["status_employer"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatusLink((int)@$data[$i]["id"],(int)@$data[$i]["status_employer"],$kicked,route('frontend.index.changeRecruitmentAppearedStatus')).'</center>';            
            $id=@$data[$i]["id"];   
            $fullname=@$data[$i]['fullname'];    
            $created_at='<center>'.datetimeConverterVn($data[$i]["created_at"]).'</center>';          
            $result[$i] = array(                
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                                         
                "status"                   =>   $status,
                "created_at"               =>   $created_at,                                
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function savedRecruitmentConverter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.route('frontend.index.deleteSavedRecruitment',[(int)@$data[$i]["id"]]).'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';                                    
            $recruitment_name='<a href="'.route("frontend.index.index",[@$data[$i]['alias']]).'">'.@$data[$i]['recruitment_name'].'</a>' ;    
            $created_at='<center>'.datetimeConverterVn(@$data[$i]["created_at"]).'</center>';          
            $result[$i] = array(                
                "id"                       =>   @$data[$i]["id"],
                "recruitment_name"         =>   @$recruitment_name,                                                         
                "created_at"               =>   @$created_at,                                                
                "deleted"                  =>   @$deleted
            );
        }
    }
    return $result;
}
function profile2Converter($data=array()){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a onclick="return xacnhanxoa();" href="'.route('frontend.index.deleteProfile',[(int)@$data[$i]["id"]]).'" ><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $edited='<center><a href="'.route('frontend.index.getFormProfile',['edit',(int)@$data[$i]["id"]]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>'; 
            $profile_detail='<center><a href="'.route('frontend.index.getProfileDetail',[(int)@$data[$i]["id"]]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';          
            $kicked=0;
            if((int)@$data[$i]["status_search"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }            
            $status_search     = '<center>'.cmsStatusLink((int)@$data[$i]["id"],(int)@$data[$i]["status_search"],$kicked,route('frontend.index.changeProfileSearchStatus')).'</center>';            
            $status='';
            if((int)@$data[$i]['status'] == 1){
                $status='<center><img src="'.asset("/public/adminsystem/images/active.png").'" /></center>';
            }else{
                $status='<center><img src="'.asset("/public/adminsystem/images/inactive.png").'" /></center>';
            }
            $id=@$data[$i]["id"];   
            $fullname=@$data[$i]['fullname'];    
            $created_at='<center>'.datetimeConverterVn($data[$i]["created_at"]).'</center>';          
            $result[$i] = array(                
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                                         
                "status_search"            =>   $status_search,
                "status"                   =>   $status,
                "created_at"               =>   $created_at,                                
                "edited"                   =>   $edited,
                "detail"                   =>   $profile_detail,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function jobConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function rankConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function probationaryConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function workingFormConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function experienceConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function workConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function sexConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function recruitmentProfile2Converter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='';
            if((int)@$data[$i]['profile_id'] > 0){
                $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            }            
            $file_attached='';
            if(!empty(@$data[$i]['file_attached'])){
                $file_attached='<center><a href="'.asset('upload/'.@$data[$i]['file_attached']).'" target="_blank"><img src="'.asset('upload/download-icon.png').'" /></a></center>';   
            }            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $profile_name='';
            if((int)@$data['profile_id'] > 0){
                $profile_name=@$data['profile_name'];
            }            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   @$data[$i]["id"],
                "candidate_name"           =>   @$data[$i]['candidate_name'],          
                "profile_name"             =>   @$data[$i]['profile_name'],                  
                "file_attached"            =>   $file_attached,                                             
                "recruitment_name"         =>   @$data[$i]["recruitment_name"],                
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),                
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function profileConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $created_at='<center>'.datetimeConverterVn($data[$i]["created_at"]).'</center>';       
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
               
                "status"                   =>   $status,
                "created_at"               =>   $created_at,
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function skillConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function classificationConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function languageConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function languageLevelConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function graduationConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function employerConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';                   
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];   
            $email=@$data[$i]['email'];
            $user_fullname=@$data[$i]['user_fullname'];         
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,
                "email"                    =>   $email,   
                'user_fullname'            =>   $user_fullname,                                      
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function candidateConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';            
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';                   
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];   
            $email=@$data[$i]['email'];                   
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,
                "email"                    =>   $email,                                                           
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function marriageConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,                         
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function districtConverter($data=array(),$controller){        
    $result = array();    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';            
            $id=@$data[$i]["id"];   
            $fullname=$data[$i]["fullname"];            
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,  
                'province_name'            =>   $data[$i]['province_name'],                       
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}

function albumConverter($data=array(),$controller){        
    $result = array();
    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                               
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function photoConverter($data=array(),$controller){        
    $result = array();
    $setting= getSettingSystem();
    $article_width=$setting['article_width']['field_value'];
    $article_height=$setting['article_height']['field_value'];
    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){            
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/" . $article_width.'x'.$article_height . "-".$data[$i]["image"]);  
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];          
           
            $album_name=$data[$i]["album_name"];
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                
                "album_name"               =>   $album_name,                              
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),                
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function categoryVideoConverter($data=array(),$controller){        
    $result = array();
    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];                
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $data[$i]["fullname"],                               
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),
                "edited"                   =>   $edited,
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function videoConverter($data=array(),$controller){        
    $result = array();
    
    if( count($data) > 0){
        for($i = 0 ;$i < count($data);$i++){
            $edited='<center><a href="'.route('adminsystem.'.$controller.'.getForm',['edit',@$data[$i]['id']]).'"><img src="'.asset("/public/adminsystem/images/edit-icon.png").'" /></a></center>';
            $deleted='<center><a href="javascript:void(0)" onclick="deleteItem('.@$data[$i]["id"].')"><img src="'.asset("/public/adminsystem/images/delete-icon.png").'" /></a></center>';
            $kicked=0;
            if((int)@$data[$i]["status"]==1){
                $kicked=0;
            }
            else{
                $kicked=1;
            }
            $status     = '<center>'.cmsStatus((int)@$data[$i]["id"],(int)@$data[$i]["status"],$kicked).'</center>';
            $sort_order = '<center><input name="sort_order"  id="sort-order-'.@$data[$i]["id"].'" sort_order_id="'.@$data[$i]["id"].'" onkeyup="setSortOrder(this)" value="'.$data[$i]["sort_order"].'" size="3" style="text-align:center" onkeypress="return isNumberKey(event);" /></center>';
            $link_image="";
            $image="";
            if(!empty(@$data[$i]["image"])){
                $link_image=url("/upload/".$data[$i]["image"]);            
                $image = '<center><img src="'.$link_image.'" style="width:100%" /></center>';
            }       
            $id=$data[$i]["id"];          
           $fullname=$data[$i]["fullname"];
            $category_name=$data[$i]["category_name"];
            $result[$i] = array(
                'checked'                  =>   '<input type="checkbox" onclick="checkWithList(this)" name="cid"  />',
                'is_checked'               =>   0,
                "id"                       =>   $id,
                "fullname"                 =>   $fullname,
                "category_name"            =>   $category_name,                              
                "image"                    =>   $image,
                "sort_order"               =>   $sort_order,
                "status"                   =>   $status,
                "created_at"               =>   datetimeConverterVn($data[$i]["created_at"]),
                "updated_at"               =>   datetimeConverterVn($data[$i]["updated_at"]),  
                "edited"                   =>   $edited,              
                "deleted"                  =>   $deleted
            );
        }
    }
    return $result;
}
function convertToArray($stdArray){
    $newArr=array();
    if(count($stdArray) > 0){
        foreach($stdArray as $key => $value){
            $newArr[$key] = (array) $value;    
        }
    }    
    return $newArr;
}
function get_field_data_array($array,$idField = null)
    {
        $_out = array();

        if (is_array($array)) {
            if ($idField == null) {
                foreach ($array as $value) {
                    $_out[] = $value;
                }
            } else {
                foreach ($array as $value) {
                    $_out[$value[$idField]] = $value;
                }
            }
            return $_out;
        } else {
            return false;
        }
    }
function convertToSourceArray($data=array()){
    $source=array();
    foreach ($data as $key => $value) {
        $source[]=$value;
    }
    return $source;
}
?>