@extends("adminsystem.master")
@section("content")
<?php 
$setting= getSettingSystem();
$linkCancel             =   route('adminsystem.'.$controller.'.getList');
$linkSave               =   route('adminsystem.'.$controller.'.save');
$candidate_id=@$arrRowData['candidate_id'];
$arrStatus              =   array(-1 => '- Select status -', 1 => 'Duyệt', 0 => 'Ngưng duyệt');  
$ddlStatus              =   cmsSelectbox("status","form-control",@$arrStatus,(int)@$arrRowData['status'],"");
$id                     =   (count(@$arrRowData) > 0) ? @$arrRowData['id'] : "" ;
$inputID                =   '<input type="hidden" name="id" value="'.@$id.'" />'; 
?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="note"  style="display: none;"></div>
        <div class="caption">
            <i class="{{$icon}}"></i>
            <span class="caption-subject font-dark sbold uppercase">{{$title}}</span>
        </div>
        <div class="actions">
           <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <button onclick="save()" class="btn purple">Lưu <i class="fa fa-floppy-o"></i></button> 
                    <a href="<?php echo $linkCancel; ?>" class="btn green">Thoát <i class="fa fa-ban"></i></a>                    </div>                                                
                </div>
            </div>    
        </div>
    </div>
    <div class="portlet-body form">
        <form class="form-horizontal" name="frm" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}            
            <?php echo  $inputID; ?>                   
            <div class="form-body">
                <?php 
                $candidate_fullname='';         
                $sex_id=0;                   
                $birthday='';
                $address='';
                $phone='';
                $email='';
                $avatar='';
                $product_width = $setting['product_width']['field_value'];
                $product_height = $setting['product_height']['field_value'];  
                $source_candidate=App\CandidateModel::find(@$candidate_id);
                $marriage_id=0;
                if($source_candidate != null){
                    $data_candidate=$source_candidate->toArray();
                    $candidate_fullname=$data_candidate['fullname'];
                    $sex_id=(int)$data_candidate['sex_id'];
                    $birthday=datetimeConverterVn($data_candidate['birthday']);
                    $address=$data_candidate['address'];
                    $phone=$data_candidate['phone'];
                    $email=$data_candidate['email'];
                    if(!empty(@$data_candidate['avatar'])){
                        $avatar='<div class="margin-top-15"><img width="150" height="150" src="'.asset("/upload/" . $product_width . "x" . $product_height . "-".@$data_candidate['avatar']).'"  /></div>';
                    }else{
                        $avatar='<div class="margin-top-15"><img width="100" height="100" src="'.asset("upload/avatar-default-icon.png").'"  /></div>';
                    }          
                    $marriage_id=$data_candidate['marriage_id'];          
                }                            
                ?>       
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Họ tên</b></label>
                        <div class="col-md-9">                              
                            <label class="control-label"><?php echo $candidate_fullname ?></label>                                              
                        </div>
                    </div>                         
                </div>     
                <?php 
                $sex_name='';
                $source_sex=App\SexModel::find(@$sex_id);
                if($source_sex != null){
                    $data_sex=$source_sex->toArray();
                    $sex_name=$data_sex['fullname'];
                }                
                ?>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Giới tính</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo $sex_name; ?></label>                                              
                        </div>
                    </div>                         
                </div>                   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Ngày sinh</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo $birthday; ?></label>                                              
                        </div>
                    </div>                         
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Địa chỉ</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo $address; ?></label>                                              
                        </div>
                    </div>                         
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Số điện thoại</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo $phone; ?></label>                                              
                        </div>
                    </div>                         
                </div>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Email</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo $email; ?></label>                                              
                        </div>
                    </div>                         
                </div>                    
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Hình đại diện</b></label>
                        <div class="col-md-9">                                   
                            <?php echo $avatar; ?>
                        </div>
                    </div>                         
                </div>                   
                <hr>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>THÔNG TIN CHUNG</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Tiêu đề hồ sơ</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$arrRowData['fullname']; ?></label>                                              
                        </div>
                    </div>                         
                </div> 
                <?php 
                $literacy_name='';
                $source_literacy=App\LiteracyModel::find(@$arrRowData['literacy_id']);                
                if($source_literacy!=null){
                    $data_literacy=$source_literacy->toArray();
                    $literacy_name=$data_literacy['fullname'];
                }
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Trình độ cao nhất</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$literacy_name; ?></label>                                              
                        </div>
                    </div>                         
                </div>    
                <?php 
                $experience_name='';
                $source_experience=App\ExperienceModel::find(@$arrRowData['experience_id']);                
                if($source_experience!=null){
                    $data_experience=$source_experience->toArray();
                    $experience_name=$data_experience['fullname'];
                }
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Số năm kinh nghiệm</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$experience_name; ?></label>                                              
                        </div>
                    </div>                         
                </div>      
                <?php 
                $rank_present='';
                $source_rank_present=App\RankModel::find(@$arrRowData['rank_present_id']);
                if($source_rank_present != null){
                    $data_rank_present=$source_rank_present->toArray();
                    $rank_present=$data_rank_present['fullname'];
                }
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Cấp bậc hiện tại</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$rank_present; ?></label>                                              
                        </div>
                    </div>                         
                </div>  
                <?php 
                $rank_offered='';
                $source_rank_offered=App\RankModel::find(@$arrRowData['rank_offered_id']);
                if($source_rank_offered != null){
                    $data_rank_offered=$source_rank_offered->toArray();
                    $rank_offered=$data_rank_offered['fullname'];
                }
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Cấp bậc mong muốn</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$rank_offered; ?></label>                                              
                        </div>
                    </div>                         
                </div>
                <?php 
                $source_profile_job=App\ProfileJobModel::whereRaw('profile_id = ?',[(int)@$arrRowData['id']])->select('id','profile_id','job_id')->get()->toArray();
                $job_fullname='';
                $job_id=array();
                if(count($source_profile_job) > 0){
                    foreach ($source_profile_job as $key => $value) {
                        $job_id[]=$value['job_id'];
                    }
                    $source_job=App\JobModel::whereIn('id',@$job_id)->select('fullname')->get()->toArray();         
                    if(count($source_job) > 0){
                        foreach ($source_job as $key => $value) {
                            $source_job_fullname[]=$value['fullname'];
                        }
                        $job_fullname=implode(' , ', $source_job_fullname);
                    }
                }
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Ngành nghề mong muốn</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$job_fullname; ?></label>                                              
                        </div>
                    </div>                         
                </div>
                <?php                 
                $salary=convertToTextPrice((int)@$arrRowData['salary']) . ' VNĐ/tháng';
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Mức lương mong muốn</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$salary; ?></label>                                              
                        </div>
                    </div>                         
                </div>   
                <?php 
                $source_profile_place=App\ProfilePlaceModel::whereRaw('profile_id = ?',[(int)@$arrRowData['id']])->select('id','profile_id','province_id')->get()->toArray();
                $province_id=array();
                $province_fullname='';                
                if(count($source_profile_place) > 0){
                    foreach ($source_profile_place as $key => $value) {
                        $province_id[]=$value['province_id'];
                    }
                    $source_province=App\ProvinceModel::whereIn('id',@$province_id)->select('fullname')->get()->toArray();  
                    if(count($source_province) > 0){
                        foreach ($source_province as $key => $value) {
                            $source_province_fullname[]=$value['fullname'];
                        }
                        $province_fullname=implode(' , ', $source_province_fullname);
                    }       
                }   
                ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Nơi làm việc mong muốn</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$province_fullname; ?></label>                                              
                        </div>
                    </div>                         
                </div>
                <?php 
                $marriage_name='';
                $source_marriage=App\MarriageModel::find((int)@$marriage_id);          
                if($source_marriage != null){
                    $data_marriage=$source_marriage->toArray();
                    $marriage_name=$data_marriage['fullname'];
                }      
                ?>                
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Hôn nhân</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$marriage_name; ?></label>                                              
                        </div>
                    </div>                         
                </div>  
                <?php 
                $status_search='';
                if((int)@$arrRowData['status_search'] == 1){
                    $status_search='Cho phép Nhà tuyển dụng tìm kiếm thông tin của bạn và chủ động liên hệ mời phỏng vấn';
                }else{
                    $status_search='Không cho phép nhà tuyển dụng tìm kiếm. Hồ sơ này chỉ dùng để ứng tuyển';
                }   
                ?>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Hiện tại</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label"><?php echo @$status_search; ?></label>                                              
                        </div>
                    </div>                         
                </div>  
                <hr>   
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>MỤC TIÊU NGHỀ NGHIỆP</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Mục tiêu</b></label>
                        <div class="col-md-9">                                   
                            <label class="control-label ribisachi-hp"><?php echo @$arrRowData['career_goal']; ?></label>                                              
                        </div>
                    </div>                         
                </div>    
                <hr>    
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>KINH NGHIỆM LÀM VIỆC</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div>  
                <?php 
                $source_profile_experience=App\ProfileExperienceModel::whereRaw('profile_id = ?',[@$arrRowData['id']])->select()->get()->toArray();  
                if(count($source_profile_experience) > 0){
                    
                    foreach ($source_profile_experience as $key => $value) {
                        $profile_experience_id=$value['id'];
                        $profile_experience_company_name=$value['company_name'];
                        $profile_experience_person_title=$value['person_title'];
                        $profile_experience_time_from=$value['month_from'] . '/' . $value['year_from'];
                        $profile_experience_time_to=$value['month_to'] . '/' .$value['year_to'];        
                        $profile_experience_salary=convertToTextPrice($value['salary']);
                        $currency='';
                        switch ($value['currency']) {
                            case 'vnd':         
                            $currency='VNĐ';    
                            break;
                            case 'usd':
                            $currency='USD';                            
                            break;
                        }       
                        $profile_experience_salary=@$profile_experience_salary.' '.@$currency.'/tháng';
                        $profile_experience_job_description=@$value['job_description'];
                        $profile_experience_achievement=@$value['achievement'];
                        $profile_experience_profile_id=(int)@$value['profile_id'];
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Công ty</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_experience_company_name; ?></label>                                              
                                </div>
                            </div>                         
                        </div>  
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Chức danh</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_experience_person_title; ?></label>                                              
                                </div>
                            </div>                         
                        </div> 
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Thời gian làm việc</b></label>
                                <div class="col-md-9">                                   
                                    <div class="box-logo">
                                        <div class="control-label">Từ</div>
                                        <div class="margin-left-10 control-label"><?php echo @$profile_experience_time_from; ?></div>
                                        <div class="margin-left-10 control-label">Đến</div>
                                        <div class="margin-left-10 control-label"><?php echo @$profile_experience_time_to; ?></div>
                                    </div> 
                                </div>
                            </div>                         
                        </div> 
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Mức lương</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_experience_salary; ?></label>                                              
                                </div>
                            </div>                         
                        </div>  
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Mô tả công việc</b></label>
                                <div class="col-md-9">                                   
                                    <div class="control-label ribisachi-hp"><?php echo @$profile_experience_job_description; ?></div>                                              
                                </div>
                            </div>                         
                        </div>  
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Thành tích nổi bật</b></label>
                                <div class="col-md-9">                                   
                                    <div class="control-label ribisachi-hp"><?php echo @$profile_experience_achievement; ?></div>                                              
                                </div>
                            </div>                         
                        </div>  
                        <hr>                          
                        <?php                        
                    }
                }
                ?>                 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>TRÌNH ĐỘ BẰNG CẤP</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div>
                <?php 
                $source_profile_graduation=DB::table('profile_graduation')
                ->join('literacy','profile_graduation.literacy_id','=','literacy.id')
                ->join('graduation','profile_graduation.graduation_id','=','graduation.id')
                ->where('profile_graduation.profile_id',(int)@$arrRowData['id'])
                ->select(
                    'profile_graduation.id',
                    'literacy.fullname as literacy_name',
                    'profile_graduation.training_unit',
                    'profile_graduation.year_from',
                    'profile_graduation.year_to',
                    'profile_graduation.department',
                    'graduation.fullname as graduation_name',
                    'profile_graduation.degree'
                )
                ->groupBy(
                    'profile_graduation.id',
                    'literacy.fullname',
                    'profile_graduation.training_unit',
                    'profile_graduation.year_from',
                    'profile_graduation.year_to',
                    'profile_graduation.department',
                    'graduation.fullname',
                    'profile_graduation.degree'
                )
                ->orderBy('profile_graduation.id', 'asc')
                ->get()->toArray();     
                $data_profile_graduation=convertToArray($source_profile_graduation);  
                if(count($data_profile_graduation) > 0){                    
                    foreach ($data_profile_graduation as $key => $value){
                        $profile_graduation_id=$value['id'];
                        $profile_graduation_literacy_name=$value['literacy_name'];
                        $profile_graduation_training_unit=$value['training_unit'];
                        $profile_graduation_year_from= $value['year_from'];
                        $profile_graduation_year_to=$value['year_to'];                                  
                        $profile_graduation_department=@$value['department'];
                        $profile_graduation_graduation_name=@$value['graduation_name'];         
                        $profile_graduation_degree=@$value['degree'];     
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Trình độ học vấn</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_graduation_literacy_name; ?></label>                                              
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Đơn vị đào tạo</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_graduation_training_unit; ?></label>                                              
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Thời gian</b></label>
                                <div class="col-md-9">                                   
                                    <div class="box-logo">
                                        <div class="control-label">Từ</div>
                                        <div class="margin-left-10 control-label"><?php echo @$profile_graduation_year_from; ?></div>
                                        <div class="margin-left-10 control-label">Đến</div>
                                        <div class="margin-left-10 control-label"><?php echo @$profile_graduation_year_to; ?></div>
                                    </div>                                        
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Chuyên ngành</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_graduation_department; ?></label>                                              
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Tốt nghiệp loại</b></label>
                                <div class="col-md-9">                                   
                                    <label class="control-label"><?php echo @$profile_graduation_graduation_name; ?></label>                                              
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-md-3 control-label"><b>Ảnh bằng cấp</b></label>
                                <div class="col-md-9">                                   
                                    <a target="_blank" href="<?php echo asset('upload/'.@$profile_graduation_degree); ?>">Tải về</a>                              
                                </div>
                            </div>                         
                        </div>
                        <hr>  
                        <?php                          
                    }
                }
                ?>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>TRÌNH ĐỘ NGOẠI NGỮ</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div>   
                <?php 
                $source_profile_language=DB::table('profile_language')
                ->join('language','profile_language.language_id','=','language.id')
                ->join('language_level','profile_language.language_level_id','=','language_level.id')
                ->join('classification as l','profile_language.listening','=','l.id')
                ->join('classification as s','profile_language.speaking','=','s.id')
                ->join('classification as r','profile_language.reading','=','r.id')
                ->join('classification as w','profile_language.writing','=','w.id')
                ->where('profile_language.profile_id',(int)@$arrRowData['id'])
                ->select(
                    'profile_language.id',
                    'language.fullname as language_name',                   
                    'language_level.fullname as language_level_name',
                    'l.fullname as listening',
                    's.fullname as speaking',
                    'r.fullname as reading',
                    'w.fullname as writing'             
                )
                ->groupBy(
                    'profile_language.id',
                    'language.fullname',                    
                    'language_level.fullname',
                    'l.fullname',
                    's.fullname',
                    'r.fullname',
                    'w.fullname'        
                )
                ->orderBy('profile_language.id', 'asc')
                ->get()->toArray();     
                $data_profile_language=convertToArray($source_profile_language);
                foreach ($data_profile_language as $key => $value){
                    $profile_language_id=$value['id'];
                    $profile_language_name=$value['language_name'];
                    $profile_language_level_name=$value['language_level_name'];                         
                    $profile_listening=$value['listening'];
                    $profile_speaking=$value['speaking'];
                    $profile_reading=$value['reading'];
                    $profile_writing=$value['writing'];
                    ?>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Ngoại ngữ</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_language_name; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Trình độ</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_language_level_name; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Nghe</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_listening; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Nói</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_speaking; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Đọc</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_reading; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Viết</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$profile_writing; ?></label>                                              
                            </div>
                        </div>                         
                    </div>
                    <hr>
                    <?php
                }
                $skill_word='';
                $source_skill_word=App\ClassificationModel::find(@$arrRowData['ms_word']);
                if($source_skill_word != null){
                    $data_skill_word=$source_skill_word->toArray();
                    $skill_word=$data_skill_word['fullname'];
                }
                ?> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>TIN HỌC VĂN PHÒNG</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div> 
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>MS Word</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$skill_word; ?></label>                                              
                            </div>
                        </div>                         
                </div>   
                <?php
                $skill_excel='';
                $source_skill_excel=App\ClassificationModel::find(@$arrRowData['ms_excel']);
                if($source_skill_excel != null){
                    $data_skill_excel=$source_skill_excel->toArray();
                    $skill_excel=$data_skill_excel['fullname'];
                }
                ?> 
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>MS Excel</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$skill_excel; ?></label>                                              
                            </div>
                        </div>                         
                </div>      
                <?php
                $skill_power_point='';
                $source_skill_power_point=App\ClassificationModel::find(@$arrRowData['ms_power_point']);
                if($source_skill_power_point != null){
                    $data_skill_power_point=$source_skill_power_point->toArray();
                    $skill_power_point=$data_skill_power_point['fullname'];
                }
                ?> 
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>MS Power Point</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$skill_power_point; ?></label>                                              
                            </div>
                        </div>                         
                </div> 
                <?php
                $skill_outlook='';
                $source_skill_outlook=App\ClassificationModel::find(@$arrRowData['ms_outlook']);
                if($source_skill_outlook != null){
                    $data_skill_outlook=$source_skill_outlook->toArray();
                    $skill_outlook=$data_skill_outlook['fullname'];
                }
                ?> 
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>MS Outlook</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$skill_outlook; ?></label>                                              
                            </div>
                        </div>                         
                </div>         
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Phần mềm khác</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label"><?php echo @$arrRowData['other_software']; ?></label>                                              
                            </div>
                        </div>                         
                </div>    
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Thành tích nổi bật</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label ribisachi-hp"><?php echo @$arrRowData['medal']; ?></label>                                              
                            </div>
                        </div>                         
                </div> 
                <hr>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label lisa"><b>KỸ NĂNG / SỞ TRƯỜNG</b></label>
                        <div class="col-md-9">                                   
                            
                        </div>
                    </div>                         
                </div>    
                <?php                 
                $data_profile_skill= DB::table('skill')
                                ->join('profile_skill','skill.id','=','profile_skill.skill_id')
                                ->where('profile_skill.profile_id',@$arrRowData['id'])
                                ->select('skill.id','skill.fullname')
                                ->groupBy('skill.id','skill.fullname')
                                ->orderBy('skill.id','asc')
                                ->get()
                                ->toArray();
                $data_profile_skill=convertToArray($data_profile_skill);                
                ?>
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Kỹ năng</b></label>
                            <div class="col-md-9">                                   
                                <?php 
                                if(count(@$data_profile_skill) > 0){
                                    foreach (@$data_profile_skill as $key => $value) {
                                        $skill_id=$value['id'];
                                        $skill_name=$value['fullname'];
                                        ?>
                                        <div class="margin-top-10"><?php echo $skill_name; ?></div>
                                        <?php
                                    }                           
                                }                           
                                ?>                                             
                            </div>
                        </div>                         
                </div>  
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Sở thích</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label ribisachi-hp"><?php echo @$arrRowData['hobby']; ?></label>                                              
                            </div>
                        </div>                         
                </div>
                <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-md-3 control-label"><b>Kỹ năng đặc biệt</b></label>
                            <div class="col-md-9">                                   
                                <label class="control-label ribisachi-hp"><?php echo @$arrRowData['talent']; ?></label>                                              
                            </div>
                        </div>                         
                </div>   
                <div class="row"> 
                    <div class="form-group col-md-12">
                        <label class="col-md-3 control-label"><b>Trạng thái</b></label>
                        <div class="col-md-9">                            
                            <?php echo $ddlStatus; ?>
                            <span class="help-block"></span>
                        </div>
                    </div>     
                </div>                                                 
            </div>              
        </form>
    </div>
</div>
<script type="text/javascript" language="javascript">   
    function save(){
        var id=$('input[name="id"]').val();                
        var status=$('select[name="status"]').val();     
        var token = $('input[name="_token"]').val();           
        var dataItem={
            "id":id,            
            "status":status,
            "_token": token
        };
        $.ajax({
            url: '<?php echo $linkSave; ?>',
            type: 'POST',
            data: dataItem,
            async: false,
            success: function (data) {
                if(data.checked==1){     
                alert(data.msg.success);                             
                    window.location.href = data.link_edit;
                }else{
                    showMsg('note',data);              
                }
                spinner.hide();
            },
            error : function (data){
                spinner.hide();
            },
            beforeSend  : function(jqXHR,setting){
                spinner.show();
            },
        });
    }          
    
</script>
@endsection()            