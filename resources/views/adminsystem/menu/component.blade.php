@extends("adminsystem.master")
@section("content")
<?php 
$linkCategoryArticleComponent           =   route('adminsystem.'.$controller.'.getCategoryArticleComponent',[@$menu_type_id]);
$linkCategoryProductComponent           =   route('adminsystem.'.$controller.'.getCategoryProductComponent',[@$menu_type_id]);
$linkArticleComponent                   =   route('adminsystem.'.$controller.'.getArticleComponent',[@$menu_type_id]);
$linkProductComponent                   =   route('adminsystem.'.$controller.'.getProductComponent',[@$menu_type_id]);
$linkPageComponent                   =   route('adminsystem.'.$controller.'.getPageComponent',[@$menu_type_id]);
$linkGetForm                            =   route('adminsystem.'.$controller.'.getForm',['add',@$menu_type_id,0,'no-alias']);
?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="{{$icon}}"></i>
            <span class="caption-subject font-dark sbold uppercase">{{$title}}</span>
        </div>
        <div class="actions">
         <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                </div>                                                
            </div>
        </div>    
    </div>
</div>
    <div class="portlet-body form">
        <form class="form-horizontal" role="form" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkCategoryArticleComponent; ?>">Chủ đề</a></center>
                    </div>   
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkCategoryProductComponent; ?>">Loại sản phẩm</a></center>
                    </div>   
                </div> 
                <div class="row">
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkArticleComponent; ?>">Bài viết</a></center> 
                    </div>   
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkProductComponent; ?>">Sản phẩm</a></center> 
                    </div>   
                </div>  
                <div class="row">
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkPageComponent; ?>">Trang tĩnh</a></center> 
                    </div>   
                    <div class="form-group col-md-6">
                        <center><a class="btn dark btn-outline sbold uppercase btn-component" href="<?php echo $linkGetForm; ?>">Link tùy biến</a></center> 
                    </div>   
                </div>                                                                  
            </div>  
            <div class="form-actions noborder">
                                
            </div>                  
        </form>
    </div>
</div>
@endsection()            