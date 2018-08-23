<?php 
$li_dashboard='';

$li_content_management='';
$li_category_article='';
$li_article='';

$li_employer_management='';
$li_employer='';
$li_recruitment='';

$li_category_banner='';
$li_page='';


$li_candidate_management='';
$li_candidate='';
$li_profile='';
$li_profile_applied='';

$li_category_management='';
$li_province='';
$li_scale='';
$li_sex='';
$li_marriage='';
$li_work='';
$li_literacy='';
$li_experience='';
$li_salary='';
$li_working_form='';
$li_probationary='';
$li_job='';
$li_rank='';
$li_graduation='';
$li_language='';
$li_language_level='';
$li_classification='';
$li_skill='';

$li_media='';
$li_menu_type='';

$li_module_item='';

$li_setting_system='';

$li_phan_quyen='';
$li_group_member='';
$li_user='';
$li_privilege='';

switch ($controller) {
    case 'dashboard':
    $li_dashboard='active open';
    break;
    case 'category-article':  
    $li_category_article='active open';
    $li_content_management='active open';
    break;   
    case 'article': 
    $li_article='active open';
    $li_content_management='active open';
    break;
    case 'category-banner':
    case 'banner':
    $li_category_banner='active open';
    break;  
    case 'page':    
    $li_page='active open';
    break;  
    case 'profile-applied':    
    $li_profile_applied='active open';
    break;  
    case 'employer': 
    $li_employer='active open';       
    $li_employer_management='active open';
    break;
    case 'recruitment': 
    $li_recruitment='active open';       
    $li_employer_management='active open';
    break;
    case 'candidate':     
    $li_candidate='active open';       
    $li_candidate_management='active open';
    break;
    case 'profile':
    $li_profile='active open';       
    $li_profile_management='active open';
    break;
    case 'province': 
    $li_province='active open';       
    $li_category_management='active open'; 
    break;
    case 'scale': 
    $li_scale='active open';       
    $li_category_management='active open'; 
    break;
    case 'sex': 
    $li_sex='active open';       
    $li_category_management='active open'; 
    break;
    case 'marriage': 
    $li_marriage='active open';       
    $li_category_management='active open'; 
    break; 
    case 'work': 
    $li_work='active open';       
    $li_category_management='active open'; 
    break; 
    case 'literacy': 
    $li_literacy='active open';       
    $li_category_management='active open'; 
    break; 
    case 'experience': 
    $li_experience='active open';       
    $li_category_management='active open'; 
    break; 
    case 'salary': 
    $li_salary='active open';       
    $li_category_management='active open'; 
    break;
    case 'working-form': 
    $li_working_form='active open';       
    $li_category_management='active open'; 
    break;
    case 'probationary': 
    $li_probationary='active open';       
    $li_category_management='active open'; 
    break;
    case 'job': 
    $li_job='active open';       
    $li_category_management='active open'; 
    break;
    case 'rank': 
    $li_rank='active open';       
    $li_category_management='active open'; 
    break;
    case 'graduation': 
    $li_graduation='active open';       
    $li_category_management='active open'; 
    break;
    case 'language': 
    $li_language='active open';       
    $li_category_management='active open'; 
    break;
    case 'language-level': 
    $li_language_level='active open';       
    $li_category_management='active open'; 
    break;
    case 'classification': 
    $li_classification='active open';       
    $li_category_management='active open'; 
    break;
    case 'skill': 
    $li_skill='active open';       
    $li_category_management='active open'; 
    break;
    case 'media':
    $li_media='active open';
    break; 
    case 'menu-type':
    case 'menu':
    $li_menu_type='active open';
    break;  
    
    case 'module-item':
    $li_module_item='active open';
    break;
    case 'setting-system':
    $li_setting_system='active open';
    break; 
        
    case 'group-member':
    $li_group_member='active open';
    $li_phan_quyen='active open';
    break;  
    case 'user':
    $li_user='active open';
    $li_phan_quyen='active open';
    break;
    case 'privilege':
    $li_privilege='active open';
    $li_phan_quyen='active open';
    break;       
}

$user=Sentinel::getUser();                                
$data=array();
$source=array();
$alias=null;
$source=DB::table('group_member')
->join('user_group_member','group_member.id','=','user_group_member.group_member_id')
->join('users','users.id','=','user_group_member.user_id')
->where('users.id',(int)@$user->id)                            
->select('group_member.alias')
->groupBy('group_member.alias')
->get()->toArray();
if(count($source) > 0){
    $data=convertToArray($source);
    $alias=$data[0]['alias'];
}                   
?>
<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
    <li class="nav-item  <?php echo $li_dashboard; ?>">
        <a href="{!! route('adminsystem.dashboard.getForm') !!}" class="nav-link nav-toggle">
            <i class="icon-notebook"></i>
            <span class="title">Bảng điều khiển</span>                                            
        </a>                                                                      
    </li>
    <?php     
    switch ($alias){
        case 'administrator':
        ?>                                            
        <li class="nav-item <?php echo $li_content_management; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý nội dung</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_category_article; ?>">
                    <a href="{!! route('adminsystem.category-article.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Chủ đề bài viết</span>                                            
                    </a>                                                                      
                </li>            
                <li class="nav-item  <?php echo $li_article; ?>">
                    <a href="{!! route('adminsystem.article.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Bài viết</span>                                            
                    </a>                                                                      
                </li>           
            </ul>
        </li>
        <li class="nav-item  <?php echo $li_category_banner; ?>">
            <a href="{!! route('adminsystem.category-banner.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Quản lý banner</span>                                            
            </a>                                                                      
        </li> 
        <li class="nav-item  <?php echo $li_page; ?>">
            <a href="{!! route('adminsystem.page.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Trang tĩnh</span>                                            
            </a>                                                                      
        </li> 

        <li class="nav-item <?php echo $li_employer_management; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý nhà tuyển dụng</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_employer; ?>">
                    <a href="{!! route('adminsystem.employer.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Thông tin doanh nghiệp</span>                                            
                    </a>                                                                      
                </li>
                <li class="nav-item  <?php echo $li_recruitment; ?>">
                    <a href="{!! route('adminsystem.recruitment.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Tin tuyển dụng</span>                                            
                    </a>                                                                      
                </li>                 
            </ul>
        </li>

        <li class="nav-item <?php echo $li_candidate; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý ứng viên</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_candidate; ?>">
                    <a href="{!! route('adminsystem.candidate.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Thông tin ứng viên</span>                                            
                    </a>                                                                      
                </li>      
                <li class="nav-item  <?php echo $li_profile; ?>">
                    <a href="{!! route('adminsystem.profile.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Hồ sơ ứng viên</span>                                            
                    </a>                                                                      
                </li>                 
            </ul>
        </li>
        <li class="nav-item  <?php echo $li_profile_applied; ?>">
            <a href="{!! route('adminsystem.profile-applied.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Hồ sơ ứng tuyển</span>                                            
            </a>                                                                      
        </li> 
        <li class="nav-item <?php echo $li_category_management; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý danh mục</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_province; ?>">
                    <a href="{!! route('adminsystem.province.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Tỉnh / Thành phố</span>                                            
                    </a>                                                                      
                </li>     
                <li class="nav-item  <?php echo $li_scale; ?>">
                    <a href="{!! route('adminsystem.scale.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Quy mô công ty</span>                                            
                    </a>                                                                      
                </li>    
                <li class="nav-item  <?php echo $li_sex; ?>">
                    <a href="{!! route('adminsystem.sex.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Giới tính</span>                                            
                    </a>                                                                      
                </li> 
                <li class="nav-item  <?php echo $li_marriage; ?>">
                    <a href="{!! route('adminsystem.marriage.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Tình trạng hôn nhân</span>                                            
                    </a>                                                                      
                </li> 
                <li class="nav-item  <?php echo $li_work; ?>">
                    <a href="{!! route('adminsystem.work.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Tính chất công việc</span>                                            
                    </a>                                                                      
                </li>   
                <li class="nav-item  <?php echo $li_literacy; ?>">
                    <a href="{!! route('adminsystem.literacy.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Trình độ học vấn</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_experience; ?>">
                    <a href="{!! route('adminsystem.experience.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Kinh nghiệm làm việc</span>                                            
                    </a>                                                                      
                </li>   
                <li class="nav-item  <?php echo $li_salary; ?>">
                    <a href="{!! route('adminsystem.salary.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Mức lương</span>                                            
                    </a>                                                                      
                </li>
                <li class="nav-item  <?php echo $li_working_form; ?>">
                    <a href="{!! route('adminsystem.working-form.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Hình thức làm việc</span>                                            
                    </a>                                                                      
                </li>       
                <li class="nav-item  <?php echo $li_probationary; ?>">
                    <a href="{!! route('adminsystem.probationary.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Thời gian thử việc</span>                                            
                    </a>                                                                      
                </li> 
                <li class="nav-item  <?php echo $li_job; ?>">
                    <a href="{!! route('adminsystem.job.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Ngành nghề</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_rank; ?>">
                    <a href="{!! route('adminsystem.rank.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Cấp bậc</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_graduation; ?>">
                    <a href="{!! route('adminsystem.graduation.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Xếp loại tốt nghiệp</span>                                            
                    </a>                                                                      
                </li> 
                <li class="nav-item  <?php echo $li_language; ?>">
                    <a href="{!! route('adminsystem.language.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Ngoại ngữ</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_language_level; ?>">
                    <a href="{!! route('adminsystem.language-level.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Trình độ ngoại ngữ</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_classification; ?>">
                    <a href="{!! route('adminsystem.classification.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Xếp loại ngoại ngữ</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_skill; ?>">
                    <a href="{!! route('adminsystem.skill.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Kỹ năng - Sở trường</span>                                            
                    </a>                                                                      
                </li>  
            </ul>
        </li>


        <li class="nav-item  <?php echo $li_media; ?>">
            <a href="{!! route('adminsystem.media.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Thư viện</span>                                            
            </a>                                                                      
        </li>
        <li class="nav-item  <?php echo $li_menu_type; ?>">
            <a href="{!! route('adminsystem.menu-type.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Menu</span>                                            
            </a>                                                                      
        </li> 
        <li class="nav-item  <?php echo $li_module_item; ?>">
            <a href="{!! route('adminsystem.module-item.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Module</span>                                            
            </a>                                                                      
        </li>     

        <li class="nav-item  <?php echo $li_setting_system; ?>">
            <a href="{!! route('adminsystem.setting-system.getList') !!}" class="nav-link nav-toggle">
                <i class="icon-notebook"></i>
                <span class="title">Cấu hình</span>                                            
            </a>                                                                      
        </li>       
        <li class="nav-item  <?php echo $li_phan_quyen ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý người dùng</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_group_member; ?>">
                    <a href="{!! route('adminsystem.group-member.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Nhóm người dùng</span>                                            
                    </a>                                                                      
                </li>
                <li class="nav-item  <?php echo $li_user; ?>">
                    <a href="{!! route('adminsystem.user.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Người dùng</span>                                            
                    </a>                                                                      
                </li>
                <li class="nav-item  <?php echo $li_privilege; ?>">
                    <a href="{!! route('adminsystem.privilege.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Nhóm quyền</span>                                            
                    </a>                                                                      
                </li>
            </ul>
        </li>          
        <?php
        break;
        default:
        ?>
        <li class="nav-item <?php echo $li_employer_management; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý nhà tuyển dụng</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_employer; ?>">
                    <a href="{!! route('adminsystem.employer.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Thông tin doanh nghiệp</span>                                            
                    </a>                                                                      
                </li>  
                <li class="nav-item  <?php echo $li_recruitment; ?>">
                    <a href="{!! route('adminsystem.recruitment.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Tin tuyển dụng</span>                                            
                    </a>                                                                      
                </li>                 
            </ul>
        </li>
        <li class="nav-item <?php echo $li_candidate; ?>">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-folder-open-o" ></i>
                <span class="title">Quản lý ứng viên</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">                                    
                <li class="nav-item  <?php echo $li_candidate; ?>">
                    <a href="{!! route('adminsystem.candidate.getList') !!}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Thông tin ứng viên</span>                                            
                    </a>                                                                      
                </li>                 
            </ul>
        </li>
        <?php
        break;
    }
    ?>                           
</ul>