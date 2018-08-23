<?php namespace App\Http\Controllers\frontend;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CategoryModel;
use App\CategoryArticleModel;
use App\CategoryProductModel;
use App\GroupModel;
use App\MenuModel;
use App\ArticleModel;
use App\PageModel;
use App\PaginationModel;
use App\ProductModel;
use App\ModuleMenuModel;
use App\ModuleCustomModel;
use App\ModuleArticleModel;
use App\ModMenuTypeModel;
use App\User;
use App\UserGroupMemberModel;
use App\GroupMemberModel;
use App\CustomerModel;
use App\InvoiceModel;
use App\InvoiceDetailModel;
use App\BannerModel;
use App\ModuleItemModel;
use App\PaymentMethodModel;
use App\ProjectModel;
use App\ProjectArticleModel;
use App\ProjectMemberModel;
use App\OrganizationModel;
use App\AlbumModel;
use App\PhotoModel;
use App\CategoryVideoModel;
use App\VideoModel;
use App\EmployerModel;
use App\CandidateModel;
use App\RecruitmentModel;
use App\RecruitmentJobModel;
use App\RecruitmentPlaceModel;
use App\JobModel;
use App\ProfileModel;
use App\ProfileJobModel;
use App\ProfilePlaceModel;
use App\ProfileExperienceModel;
use App\ProfileGraduationModel;
use App\ProfileLanguageModel;
use App\ProfileSkillModel;
use App\ProvinceModel;
use App\ProfileSavedModel;
use App\ProfileAppliedModel;
use App\RecruitmentSavedModel;
use App\NL_CheckOutV3;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Session;
use DB;
use Hash;
use Sentinel;
class IndexController extends Controller {  
	var $_pageRange=4;
	var $_ssNameUser="vmuser";  
	var $_ssNameCart="vmart";      
	var $_ssNameInvoice="vminvoice";
	public function getHome(Request $request){       
		\Artisan::call('sitemap:auto');   
		return view("frontend.home");        
	} 	
	public function searchRecruitment(Request $request){
		/* begin standard */
		$title="Tìm kiếm công việc";
		$meta_keyword="";
		$meta_description="";                                                                
		$totalItems=0;
		$totalItemsPerPage=0;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ;                                              
		$setting= getSettingSystem();   
		$seo=getSeo(); 
		$view="source-recruitment";       
		/* end standard */   
		$q='';  		
		$job_id=0;
		$province_id=0;
		$salary_id=0;
		$literacy_id=0;
		$sex_id=0;
		$work_id=0;
		$working_form_id=0;
		$experience_id=0;		
		$title=@$seo["title"];
		$meta_description=@$seo["meta_description"];
		$query=DB::table('recruitment')
		->join('employer','recruitment.employer_id','=','employer.id')
		->join('salary','recruitment.salary_id','=','salary.id');	
		if((int)@$request->job_id > 0){
			$query->join('recruitment_job','recruitment.id','=','recruitment_job.recruitment_id');
		}	
		if((int)@$request->province_id > 0){
			$query->join('recruitment_place','recruitment.id','=','recruitment_place.recruitment_id');
		}						
		if(!empty(@$request->q)){
			$query->where('recruitment.fullname','like','%'.trim(@$request->q).'%');
			$q=trim(@$request->q);
		}	
		if((int)@$request->job_id > 0){
			$job_id=(int)@$request->job_id;
			$query->where('recruitment_job.job_id','=',@$job_id);
		}	
		if((int)@$request->province_id > 0){
			$province_id=(int)@$request->province_id;
			$query->where('recruitment_place.province_id','=',@$province_id);
		}
		if((int)@$request->salary_id > 0){
			$salary_id=(int)@$request->salary_id;
			$query->where('recruitment.salary_id','=',@$salary_id);
		}
		if((int)@$request->literacy_id > 0){
			$literacy_id=(int)@$request->literacy_id;
			$query->where('recruitment.literacy_id','=',@$literacy_id);
		}
		if((int)@$request->sex_id > 0){
			$sex_id=(int)@$request->sex_id;
			$query->where('recruitment.sex_id','=',@$sex_id);
		}
		if((int)@$request->work_id > 0){
			$work_id=(int)@$request->work_id;
			$query->where('recruitment.work_id','=',@$work_id);
		}
		if((int)@$request->working_form_id > 0){
			$working_form_id=(int)@$request->working_form_id;
			$query->where('recruitment.working_form_id','=',@$working_form_id);
		}
		if((int)@$request->experience_id > 0){
			$experience_id=(int)@$request->experience_id;
			$query->where('recruitment.experience_id','=',@$experience_id);
		}
		$query->where('recruitment.status',1);
		$query->where('recruitment.status_employer',1);	
		$data=$query->select(
			'recruitment.id',
			'recruitment.fullname',
			'recruitment.alias',
			'recruitment.duration',
			'recruitment.status_hot',
			'salary.fullname as salary_name',
			'employer.fullname as employer_fullname',
			'employer.alias as employer_alias',
			'employer.logo'
		)                
		->groupBy(
			'recruitment.id',
			'recruitment.fullname',
			'recruitment.alias',
			'recruitment.duration',
			'recruitment.status_hot',
			'salary.fullname',
			'employer.fullname',
			'employer.alias',
			'employer.logo'
		)
		->orderBy('recruitment.id', 'desc')				
		->get()
		->toArray();        
		$items=convertToArray($data); 
		return view("frontend.".@$view,compact("alias","title","meta_keyword","meta_description","items","q","job_id","province_id","salary_id","literacy_id","sex_id","work_id","working_form_id","experience_id","pagination"));   	
	}
	public function search(Request $request){		
		/* begin standard */
		$title="";
		$meta_keyword="";
		$meta_description="";                                                                
		$totalItems=0;
		$totalItemsPerPage=0;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ;                                              
		$setting= getSettingSystem();   
		$seo=getSeo(); 
		$view="category-article";                        
		/* end standard */   
		$q='';  					
		$title=@$seo["title"];
		$meta_description=@$seo["meta_description"];
		$query=DB::table('article')
				->join('article_category','article.id','=','article_category.article_id')
				->join('category_article','category_article.id','=','article_category.category_id')				
				->where('article.status',1);						
		if(!empty(@$request->q)){
			$query->where('article.fullname','like','%'.trim(@$request->q).'%');
			$q=trim(@$request->q);
		}			
		$data=$query->select('article.id','article.alias','article.fullname','article.image','article.alt_image','article.intro','article.count_view','article.created_at')                
		->groupBy('article.id','article.alias','article.fullname','article.image','article.alt_image','article.intro','article.count_view','article.created_at')
		->orderBy('article.sort_order', 'desc')		
		->get()
		->toArray(); 
		$items=convertToArray($data);   
		return view("frontend.".@$view,compact("title","meta_keyword","meta_description","items","q","pagination"));   	
	}
	public function index(Request $request,$alias)
	{                     
		/* begin standard */
		$title="";
		$meta_keyword="";
		$meta_description="";                                                                
		$totalItems=0;
		$totalItemsPerPage=0;
		$pageRange=0;      
		$currentPage=1;  
		$pagination =null;                                              
		$setting= getSettingSystem();    
		$view="";       
		/* end standard */          
		$item=array();     
		$items=array();                  
		$category=array();  
		$component=$alias;         
		$source_menu=MenuModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();    
		$source_category_article=CategoryArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();
		$source_article=ArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();		
		$source_page=PageModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();   
		$source_province=ProvinceModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray(); 
		$source_employer=EmployerModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray(); 
		$source_job=JobModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray(); 
		$source_recruitment=RecruitmentModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray(); 
		if(count(@$source_category_article) > 0){
			$component='category-article';
		}
		if(count(@$source_article) > 0){
			$component='article';
		}
		if(count(@$source_page) > 0){
			$component='page';
		}            		
		if(count(@$source_province) > 0){
			$component='recruitment-by-province';
		}
		if(count(@$source_employer) > 0){
			$component='employer-detail';
		}
		if(count(@$source_job) > 0){
			$component='recruitment-by-job';
		}
		if(count(@$source_recruitment) > 0){
			$component='recruitment-detail';
		}
		switch ($component) {
			case 'category-article':      
			$category_id=0;
			$category=CategoryArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias,'UTF-8'))])->get()->toArray();         
			if(count($category) > 0){
				$category     = $category[0];
				$category_id    = $category['id'];        
				$arr_category_id[]=$category_id;
				getStringCategoryID($category_id,$arr_category_id,'category_article');   
				$query=DB::table('article')
				->join('article_category','article.id','=','article_category.article_id')
				->join('category_article','category_article.id','=','article_category.category_id')
				->whereIn('article_category.category_id', $arr_category_id)
				->where('article.status',1);
				$data=$query->select('article.id','article.alias','article.fullname','article.image','article.alt_image','article.intro','article.count_view','article.created_at')                
				->groupBy('article.id','article.alias','article.fullname','article.image','article.alt_image','article.intro','article.count_view','article.created_at')
				->orderBy('article.sort_order', 'desc')								
				->get()
				->toArray();        
				$items=convertToArray($data); 
				$view="category-article";                        
			}              
			break;
			case 'article':
			$row=ArticleModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();              
			if(count($row) > 0){
				$item=$row[0];
			}        
			$view="article";        
			break;        
			case 'page':
			$row=PageModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower($alias,'UTF-8'))])->get()->toArray();              
			if(count($row) > 0){
				$item=$row[0];
			}     			
			$view="page";     
			break;
			case 'viec-lam-moi':			
			$title='Việc làm mới';
			$meta_keyword='metakeyword việc làm mới';
			$meta_description='metadescription việc làm mới';
			$view="source-recruitment";
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_new',1);
			$data=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias',
				'employer.logo'
			)                
			->groupBy(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname',
				'employer.fullname',
				'employer.alias',
				'employer.logo'
			)
			->orderBy('recruitment.id', 'desc')				
			->get()
			->toArray(); 	
			$items=convertToArray($data);				
			break; 	
			case 'viec-lam-hap-dan':
			$title='Việc làm hấp dẫn';
			$meta_keyword='metakeyword việc làm hấp dẫn';
			$meta_description='metadescription việc làm hấp dẫn';
			$view="source-recruitment";
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_attractive',1);
			$data=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias',
				'employer.logo'
			)                
			->groupBy(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname',
				'employer.fullname',
				'employer.alias',
				'employer.logo'
			)
			->orderBy('recruitment.id', 'desc')			
			->get()
			->toArray();        
			$items=convertToArray($data);   
			break; 		
			case 'viec-lam-luong-cao':
			$title='Việc làm lương cao';
			$meta_keyword='metakeyword việc làm lương cao';
			$meta_description='metadescription việc làm lương cao';
			$view="source-recruitment";
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_high_salary',1);
			$data=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias',
				'employer.logo'
			)                
			->groupBy(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname',
				'employer.fullname',
				'employer.alias',
				'employer.logo'
			)
			->orderBy('recruitment.id', 'desc')
			->get()
			->toArray();        
			$items=convertToArray($data);   			
			break; 	
			case 'viec-lam-duoc-quan-tam':
			$title='Việc làm được quan tâm nhiều nhất';
			$meta_keyword='metakeyword việc làm được quan tâm nhiều nhất';
			$meta_description='metadescription việc làm được quan tâm nhiều nhất';
			$view="source-recruitment";
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_interested',1);
			$data=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias',
				'employer.logo'
			)                
			->groupBy(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname',
				'employer.fullname',
				'employer.alias',
				'employer.logo'
			)
			->orderBy('recruitment.id', 'desc')			
			->get()
			->toArray();        
			$items=convertToArray($data);   
			break; 		
			case 'viec-lam-tuyen-gap':
			$title='Việc làm tuyển gấp';
			$meta_keyword='metakeyword việc làm tuyển gấp';
			$meta_description='metadescription việc làm tuyển gấp';
			$view="source-recruitment";
			$query=DB::table('recruitment')
			->join('employer','recruitment.employer_id','=','employer.id')
			->join('salary','recruitment.salary_id','=','salary.id');
			$query->where('recruitment.status',1);
			$query->where('recruitment.status_employer',1);
			$query->where('recruitment.status_quick',1);
			$data=$query->select(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname as salary_name',
				'employer.fullname as employer_fullname',
				'employer.alias as employer_alias',
				'employer.logo'
			)                
			->groupBy(
				'recruitment.id',
				'recruitment.fullname',
				'recruitment.alias',
				'recruitment.duration',
				'recruitment.status_hot',
				'salary.fullname',
				'employer.fullname',
				'employer.alias',
				'employer.logo'
			)
			->orderBy('recruitment.id', 'desc')						
			->get()
			->toArray();        
			$items=convertToArray($data);   
			break; 			
			case 'recruitment-by-province':						
			$view="source-recruitment";
			if(count(@$source_province) > 0){
				$data_province=convertToArray(@$source_province);
				$province_id=@$data_province[0]['id'];
				$title='Việc làm tại '. @$data_province[0]['fullname'];
				$meta_keyword='metakeyword việc làm tại '. @$data_province[0]['fullname'];
				$meta_description='metadescription việc làm tại '. @$data_province[0]['fullname'];
				$query=DB::table('recruitment')
				->join('employer','recruitment.employer_id','=','employer.id')
				->join('salary','recruitment.salary_id','=','salary.id')
				->join('recruitment_place','recruitment.id','=','recruitment_place.recruitment_id');
				$query->where('recruitment.status',1);
				$query->where('recruitment.status_employer',1);
				$query->where('recruitment_place.province_id',(int)@$province_id);			
				$data=$query->select(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname as salary_name',
					'employer.fullname as employer_fullname',
					'employer.alias as employer_alias',
					'employer.logo'
				)                
				->groupBy(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname',
					'employer.fullname',
					'employer.alias',
					'employer.logo'
				)
				->orderBy('recruitment.id', 'desc')				
				->get()
				->toArray();        
				$items=convertToArray($data); 
			}			
			break; 		
			case 'employer-detail':						
			$view="employer-detail";
			if(count(@$source_employer) > 0){
				$data_employer=convertToArray(@$source_employer);
				$employer_id=@$data_employer[0]['id'];
				$item=EmployerModel::find((int)@$employer_id)->toArray();				
				$query=DB::table('recruitment')
				->join('employer','recruitment.employer_id','=','employer.id')
				->join('salary','recruitment.salary_id','=','salary.id');
				$query->where('recruitment.status',1);
				$query->where('recruitment.status_employer',1);
				$query->where('recruitment.employer_id',(int)@$employer_id);			
				$data=$query->select(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname as salary_name',
					'employer.fullname as employer_fullname',
					'employer.alias as employer_alias',
					'employer.logo'
				)                
				->groupBy(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname',
					'employer.fullname',
					'employer.alias',
					'employer.logo'
				)
				->orderBy('recruitment.id', 'desc')				
				->get()
				->toArray();        
				$items=convertToArray($data); 				
			}			
			break; 	
			case 'recruitment-by-job':						
			$view="source-recruitment";
			if(count(@$source_job) > 0){
				$data_job=convertToArray(@$source_job);
				$job_id=@$data_job[0]['id'];
				$title="Việc làm ngành " .@$data_job[0]['fullname'];
				$meta_keyword='metakeyword '.@$data_job[0]['fullname'];
				$meta_description='metadescription '. @$data_job[0]['fullname'];
				$query=DB::table('recruitment')
				->join('employer','recruitment.employer_id','=','employer.id')
				->join('salary','recruitment.salary_id','=','salary.id')
				->join('recruitment_job','recruitment.id','=','recruitment_job.recruitment_id');
				$query->where('recruitment.status',1);
				$query->where('recruitment.status_employer',1);
				$query->where('recruitment_job.job_id',(int)@$job_id);	
				$data=$query->select(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname as salary_name',
					'employer.fullname as employer_fullname',
					'employer.alias as employer_alias',
					'employer.logo'
				)                
				->groupBy(
					'recruitment.id',
					'recruitment.fullname',
					'recruitment.alias',
					'recruitment.duration',
					'recruitment.status_hot',
					'salary.fullname',
					'employer.fullname',
					'employer.alias',
					'employer.logo'
				)
				->orderBy('recruitment.id', 'desc')				
				->get()
				->toArray(); 	
				$items=convertToArray($data);								     
			}			
			break; 	
			case 'recruitment-detail':
			$view="recruitment-detail";
			$row=RecruitmentModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias,'UTF-8'))])->get()->toArray();              
			if(count($row) > 0){
				$item=$row[0];
			}            
			break;	 	
		}  
		if(count(@$source_menu) > 0){
			$source_menu=convertToArray(@$source_menu);
			$title=@$source_menu[0]['fullname'];
		}       
		if(count(@$item) > 0){
			$title=@$item['fullname'];                      
			if(!empty(@$item['meta_keyword'])){
				$meta_keyword=@$item['meta_keyword'];
			}                
			if(!empty(@$item['meta_description'])){
				$meta_description=@$item['meta_description'];
			}                
		}         		
		if(count(@$category) > 0){      
			$title=@$category['fullname'];                         
			if(!empty(@$category['meta_keyword'])){
				$meta_keyword=@$category['meta_keyword'];
			}                
			if(!empty(@$category['meta_description'])){
				$meta_description=@$category['meta_description'];
			}
		}    
		\Artisan::call('sitemap:auto');        
		return view("frontend.".@$view,compact("alias","title","meta_keyword","meta_description","item","items","pagination","category","component"));   		
	}	
	public function register(){             
		return view("frontend.register");         
	}
	public function loginFe(){             
		return view("frontend.login");         
	}
	public function registerEmployer(Request $request){        
		$checked=1;
		$msg=array();        
		$data=array();       
		if($request->isMethod('post')){
			$data               = @$request->all();
			$email              = trim(@$request->email);
			$password           = @$request->password;
			$password_confirmed = @$request->password_confirmed;
			$fullname           = trim(@$request->fullname);
			$address            = trim(@$request->address);
			$phone              = trim(@$request->phone);
			$province_id        = trim(@$request->province_id);
			$scale_id           = trim(@$request->scale_id);
			$intro              = trim(@$request->intro);
			$fax                = trim(@$request->fax);
			$website            = trim(@$request->website);
			$contacted_name     = trim(@$request->contacted_name);
			
			$contacted_phone    = trim(@$request->contacted_phone);
			if(!preg_match("#^[a-z][a-z0-9_\.]{4,31}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$#", mb_strtolower($email,'UTF-8')   )){
				$msg["email"] = 'Email nhà tuyển dụng không hợp lệ'; 
				$data['email']='';           
				$checked = 0;
			}
			else{
				$source=array();
				$source=EmployerModel::whereRaw("trim(lower(email)) = ?",[trim(mb_strtolower($email,'UTF-8'))])->get()->toArray();     
				if (count($source) > 0) {
					$msg["email"]= "Email nhà tuyển dụng đã có trong hệ thống. ";
					$data['email']='';
					$checked = 0;                    
				}
				$source=CandidateModel::whereRaw("trim(lower(email)) = ?",[trim(mb_strtolower($email,'UTF-8'))])->get()->toArray();     
				if (count($source) > 0) {
					$msg["email"]= "Email nhà tuyển dụng đã có trong hệ thống. ";
					$data['email']='';
					$checked = 0;                    
				}       
			}
			if(mb_strlen($password) < 10 ){
				$msg["password"] = "Mật khẩu tối thiểu phải 10 ký tự";
				$data['password']='';
				$data['password_confirmed']='';
				$checked = 0;                
			}else{
				if(strcmp($password, $password_confirmed) !=0 ){
					$msg["password"] = "Xác nhận mật khẩu không trùng khớp";
					$data['password']='';
					$data['password_confirmed']='';
					$checked = 0;                  
				}
			}    
			if(mb_strlen($fullname) < 15){
				$msg["fullname"] = 'Tên công ty phải từ 15 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}else{
				$source=array();
				$source=EmployerModel::whereRaw("trim(lower(fullname)) = ?",[trim(mb_strtolower($fullname,'UTF-8'))])->get()->toArray();    
				if (count($source) > 0) {
					$msg["fullname"] = "Tên công ty đã có trong hệ thống. ";
					$data['fullname']='';
					$checked = 0;                    
				}       
			}  
			if(mb_strlen($address) < 15){
				$msg["address"] = 'Địa chỉ phải từ 15 ký tự trở lên';      
				$data['address']='';      
				$checked = 0;
			}   
			if(mb_strlen($phone) < 10){
				$msg["phone"] = 'Điện thoại công ty phải từ 10 ký tự trở lên';   
				$data['phone']='';         
				$checked = 0;
			}else{
				$source=array();
				$source=EmployerModel::whereRaw("trim(lower(phone)) = ?",[trim(mb_strtolower($phone,'UTF-8'))])->get()->toArray();    
				if (count($source) > 0) {
					$msg["phone"] = "Điện thoại công ty đã có trong hệ thống. ";
					$data['phone']='';
					$checked = 0;                
				}       
			}  
			if((int)@$request->province_id == 0){
				$msg["province_id"] = 'Vui lòng chọn tỉnh thành phố';            
				$data['province_id']=0;
				$checked = 0;
			}
			if((int)@$request->scale_id == 0){
				$msg["scale_id"] = 'Vui lòng chọn quy mô công ty';    
				$data['scale_id']=0;        
				$checked = 0;
			}      
			if(mb_strlen($website) > 0){
				if(!preg_match("#^(https?://(www\.)?|(www\.))[a-z0-9\-]{3,}(\.[a-z]{2,4}){1,2}$#", mb_strtolower($website,'UTF-8')   )){
					$msg["website"] = 'Website công ty không hợp lệ';     
					$data['website']='';       
					$checked = 0;
				}
			}      
			if(mb_strlen($contacted_name) < 6){
				$msg["contacted_name"] = 'Họ tên người liên hệ phải từ 6 ký tự trở lên';   
				$data['contacted_name']='';         
				$checked = 0;
			} 
			
			if(mb_strlen($contacted_phone) < 10){
				$msg["contacted_phone"] = 'Số điện thoại người liên hệ phải từ 10 ký tự trở lên';            
				$data['contacted_phone']='';
				$checked = 0;
			}
			if($checked==1){
				$item               = new EmployerModel;
				$item->email        = @$email;
				$item->password     = Hash::make(@$password) ;
				$item->fullname     = @$fullname;
				/* begin save alias */
				$alias=str_slug(@$fullname,'-');				
				$data_employer=array();        
				$data_employer=EmployerModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias,'UTF-8'))])->get()->toArray();        
				if (count(@$data_employer) > 0) {
					$code_alias=rand(1,999999);
					$alias=$alias.'-'.$code_alias;
				}        				
				$item->alias=@$alias;
				/* end save alias */
				$item->address      = @$address;
				$item->phone        = @$phone;
				$item->province_id  = @$province_id;
				$item->scale_id     = @$scale_id;
				$item->intro        = @$intro;
				$item->fax          = @$fax;
				$item->website      = @$website;
				$item->contacted_name   = @$contacted_name;
				
				$item->contacted_phone  = @$contacted_phone; 				
				/* begin code_alias */
				$source_character = array_merge(range('a','z'), range(0,9));
				$code = implode($source_character, '');
				$code = str_shuffle($code);
				$certification_code   = substr($code, 0, 30);
				/* end code_alias */
				$item->certification_code=$certification_code;
				$item->certificated_number=0;
				$item->status = 0;
				$item->created_at=date("Y-m-d H:i:s",time());
				$item->updated_at=date("Y-m-d H:i:s",time());   
				$item->save();   
				/* begin load setting */
				$setting=getSettingSystem();    
				$smtp_host      = @$setting['smtp_host']['field_value'];
				$smtp_port      = @$setting['smtp_port']['field_value'];
				$smtp_auth      = @$setting['authentication']['field_value'];
				$encription     = @$setting['encription']['field_value'];
				$smtp_username  = @$setting['smtp_username']['field_value'];
				$smtp_password  = @$setting['smtp_password']['field_value'];				
				$email_from     = 'tichtacso@gmail.com';
				$email_to       = @$email;				
				/* end load setting */       
				/* begin send mail certification */
				$mail = new PHPMailer(true);
				$mail->SMTPDebug = 0;                           
				$mail->isSMTP();     
				$mail->CharSet = "UTF-8";          
				$mail->Host = $smtp_host; 
				$mail->SMTPAuth = $smtp_auth;                         
				$mail->Username = $smtp_username;             
				$mail->Password = $smtp_password;             
				$mail->SMTPSecure = $encription;                       
				$mail->Port = $smtp_port;                            
				$mail->setFrom($email_from, 'CÔNG TY TNHH VIDOCO');
				$mail->addAddress($email_to, @$fullname);   
				$mail->Subject = 'Thông tin xác thực tài khoản tại '.url('/') ; 
				$html_content='';
				$html_content='<div>Vui lòng nhấn vào link bên dưới để kích hoạt tài khoản</div>';
				$link_certification=route('frontend.index.certificateEmployer',[@$item->id,@$certification_code]);
				$html_content.='<div><a href="'.$link_certification.'" target="_blank">'.$link_certification.'</a></div>';
				$mail->msgHTML($html_content);
				$mail->Send();
				/* end send mail certification */
				if(Session::has($this->_ssNameUser)){					
					Session::forget($this->_ssNameUser);      
				}    				
				return redirect()->route('frontend.index.finishedRegister');
			}
		}
		return view("frontend.employer-register",compact('data','msg','checked'));         
	}
	public function finishedRegister(){      			      	
        return view("frontend.finished-register");                  
      }  
	public function certificateEmployer($id,$certification_code)
	{
		$checked=1;
		$msg=array();        		  		
		$employer=EmployerModel::find((int)@$id);		
		if($employer == null){						
			$msg['certification']='Thông tin kích hoạt không khớp. Vui lòng kiểm tra lại trong mail';
			$checked=0;		
		}else{
			if((int)@$employer->certificated_number > 0){
				$msg['certification']='Tài khoản đã được xác thực . Vui lòng liên hệ lại bộ phận hỗ trợ';
				$checked=0;	
			}else{
				if(strcmp(@$employer->certification_code, @$certification_code) != 0){
					$msg['certification']='Mã xác thực không khớp. Vui lòng kiểm tra lại trong mail';
					$checked=0;		
				}				
			}	
		}
		if((int)$checked==1){
			$employer->certificated_number=1;
			$employer->status=1;
			$employer->save();
			$msg['success']='Kích hoạt tài khoản thành công. Vui lòng đăng nhập <a href="'.route('frontend.index.employerLogin').'">tại đây</a>';
		}
		return view("frontend.certification",compact('msg','checked'));         
	}
	public function registerCandidate(Request $request){             
		$checked=1;
		$msg=array();        
		$data=array();       
		if($request->isMethod('post')){
			$data               = @$request->all();
			$email              = trim(@$request->email);
			$password           = @$request->password;
			$password_confirmed = @$request->password_confirmed;
			$fullname           = trim(@$request->fullname);
			$phone              = trim(@$request->phone);      
			if(!preg_match("#^[a-z][a-z0-9_\.]{4,31}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$#", mb_strtolower($email,'UTF-8')   )){
				$msg["email"] = 'Email ứng viên không hợp lệ'; 
				$data['email']='';           
				$checked = 0;
			}
			else{
				$source=array();
				$source=CandidateModel::whereRaw("trim(lower(email)) = ?",[trim(mb_strtolower($email,'UTF-8'))])->get()->toArray();     
				if (count($source) > 0) {
					$msg["email"]= "Email ứng viên đã có trong hệ thống. ";
					$data['email']='';
					$checked = 0;                    
				}
				$source=EmployerModel::whereRaw("trim(lower(email)) = ?",[trim(mb_strtolower($email,'UTF-8'))])->get()->toArray();     
				if (count($source) > 0) {
					$msg["email"]= "Email ứng viên đã có trong hệ thống. ";
					$data['email']='';
					$checked = 0;                    
				}       
			}
			if(mb_strlen($password) < 10 ){
				$msg["password"] = "Mật khẩu tối thiểu phải 10 ký tự";
				$data['password']='';
				$data['password_confirmed']='';
				$checked = 0;                
			}else{
				if(strcmp($password, $password_confirmed) !=0 ){
					$msg["password"] = "Xác nhận mật khẩu không trùng khớp";
					$data['password']='';
					$data['password_confirmed']='';
					$checked = 0;                  
				}
			}    
			if(mb_strlen($fullname) < 6){
				$msg["fullname"] = 'Tên ứng viên phải từ 6 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}        
			if(mb_strlen($phone) < 10){
				$msg["phone"] = 'Điện thoại ứng viên phải từ 10 ký tự trở lên';   
				$data['phone']='';         
				$checked = 0;
			}else{
				$source=array();
				$source=CandidateModel::whereRaw("trim(lower(phone)) = ?",[trim(mb_strtolower($phone,'UTF-8'))])->get()->toArray();    
				if (count($source) > 0) {
					$msg["phone"] = "Điện thoại ứng viên đã có trong hệ thống. ";
					$data['phone']='';
					$checked = 0;                
				}       
			}        
			if($checked==1){
				$item               = new CandidateModel;
				$item->email        = @$email;
				$item->password     = Hash::make(@$password) ;
				$item->fullname     = @$fullname;
				$item->phone        = @$phone;  
				/* begin code_alias */
				$source_character = array_merge(range('a','z'), range(0,9));
				$code = implode($source_character, '');
				$code = str_shuffle($code);
				$certification_code   = substr($code, 0, 30);
				/* end code_alias */
				$item->certification_code=$certification_code;
				$item->certificated_number=0;    
				$item->status =0;
				$item->created_at=date("Y-m-d H:i:s",time());
				$item->updated_at=date("Y-m-d H:i:s",time());   
				$item->save();   
				/* begin load setting */
				$setting=getSettingSystem();    
				$smtp_host      = @$setting['smtp_host']['field_value'];
				$smtp_port      = @$setting['smtp_port']['field_value'];
				$smtp_auth      = @$setting['authentication']['field_value'];
				$encription     = @$setting['encription']['field_value'];
				$smtp_username  = @$setting['smtp_username']['field_value'];
				$smtp_password  = @$setting['smtp_password']['field_value'];				
				$email_from     = 'tichtacso@gmail.com';
				$email_to       = @$email;				
				/* end load setting */       
				/* begin send mail certification */
				$mail = new PHPMailer(true);
				$mail->SMTPDebug = 0;                           
				$mail->isSMTP();     
				$mail->CharSet = "UTF-8";          
				$mail->Host = $smtp_host; 
				$mail->SMTPAuth = $smtp_auth;                         
				$mail->Username = $smtp_username;             
				$mail->Password = $smtp_password;             
				$mail->SMTPSecure = $encription;                       
				$mail->Port = $smtp_port;                            
				$mail->setFrom($email_from, 'CÔNG TY TNHH VIDOCO');
				$mail->addAddress($email_to, @$fullname);   
				$mail->Subject = 'Thông tin xác thực tài khoản tại '.url('/') ; 
				$html_content='';
				$html_content='<div>Vui lòng nhấn vào link bên dưới để kích hoạt tài khoản</div>';
				$link_certification=route('frontend.index.certificateCandidate',[@$item->id,@$certification_code]);
				$html_content.='<div><a href="'.$link_certification.'" target="_blank">'.$link_certification.'</a></div>';
				$mail->msgHTML($html_content);
				$mail->Send();
				/* end send mail certification */
				return redirect()->route('frontend.index.finishedRegister');
			}
		}
		return view("frontend.candidate-register",compact('data','msg','checked'));         
	}    
	public function certificateCandidate($id,$certification_code)
	{
		$checked=1;
		$msg=array();        		  		
		$candidate=CandidateModel::find((int)@$id);		
		if($candidate == null){						
			$msg['certification']='Thông tin kích hoạt không khớp. Vui lòng kiểm tra lại trong mail';
			$checked=0;		
		}else{
			if((int)@$candidate->certificated_number > 0){
				$msg['certification']='Tài khoản đã được xác thực . Vui lòng liên hệ lại bộ phận hỗ trợ';
				$checked=0;	
			}else{
				if(strcmp(@$candidate->certification_code, @$certification_code) != 0){
					$msg['certification']='Mã xác thực không khớp. Vui lòng kiểm tra lại trong mail';
					$checked=0;		
				}				
			}	
		}
		if((int)@$checked==1){
			$candidate->certificated_number=1;
			$candidate->status=1;
			$candidate->save();
			$msg['success']='Kích hoạt tài khoản thành công. Vui lòng đăng nhập <a href="'.route('frontend.index.candidateLogin').'">tại đây</a>';
		}
		return view("frontend.certification",compact('msg','checked'));         
	}
	public function loginEmployer(Request $request){      
		$msg=array();
		$data=array();     
		$checked=1;  
		$arrUser=array();
		$source=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) > 0){
			$email=@$arrUser['email'];   
			$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
			if(count($source) > 0){
				return redirect()->route('frontend.index.viewEmployerAccount');
			}      
		}
		if($request->isMethod('post')){                    
			$email              = trim(@$request->email);
			$password           = @$request->password ;
			$source=EmployerModel::whereRaw('trim(lower(email)) = ? and status = ?',[trim(mb_strtolower(@$email,'UTF-8')),1])->select('id','email','password')->get()->toArray();
			if(count($source) > 0){
				$password_hashed=$source[0]['password'];
				if(Hash::check($password,$password_hashed)){
					$arrUser=array("id"=>$source[0]["id"],"email" => $source[0]["email"]);         
					Session::forget($this->_ssNameUser);                                 
					Session::put($this->_ssNameUser,$arrUser);  
					return redirect()->route('frontend.index.viewEmployerAccount'); 
				}else{
					$checked=0;
					$msg['success']="Đăng nhập sai mật khẩu";
				}              
			}else{
				$checked=0;
				$msg['success']="Đăng nhập sai email hoặc tài khoản chưa được kích hoạt";
			}          
		}                          
		return view("frontend.employer-login",compact('msg',"data",'checked'));                       
	}
	public function resetPassWrdEmployer(Request $request){
		$msg=array();
		$data=array();     
		$checked=1;  
		if($request->isMethod('post')){
			$data               = @$request->all();
			$email              = trim(@$request->email);
			$source=EmployerModel::whereRaw('email = ?',[@$email])->select('id')->get()->toArray();
			if(count($source) == 0){
				$checked=0;
				$msg['not-email']='Email này không có trong hệ thống';
			}
			if((int)@$checked == 1){
				/* begin code_alias */
				$source_character = array_merge(range('a','z'), range(0,9));
				$code = implode($source_character, '');
				$code = str_shuffle($code);
				$password   = substr($code, 0, 12);
				/* end code_alias */
				$employer=EmployerModel::find((int)@$source[0]['id']);
				$employer->password     = Hash::make(@$password) ;
				$employer->save();
				/* begin load setting */
				$setting=getSettingSystem();    
				$smtp_host      = @$setting['smtp_host']['field_value'];
				$smtp_port      = @$setting['smtp_port']['field_value'];
				$smtp_auth      = @$setting['authentication']['field_value'];
				$encription     = @$setting['encription']['field_value'];
				$smtp_username  = @$setting['smtp_username']['field_value'];
				$smtp_password  = @$setting['smtp_password']['field_value'];				
				$email_from     = 'tichtacso@gmail.com';
				$email_to       = @$email;				
				/* end load setting */       
				/* begin send mail certification */
				$mail = new PHPMailer(true);
				$mail->SMTPDebug = 0;                           
				$mail->isSMTP();     
				$mail->CharSet = "UTF-8";          
				$mail->Host = $smtp_host; 
				$mail->SMTPAuth = $smtp_auth;                         
				$mail->Username = $smtp_username;             
				$mail->Password = $smtp_password;             
				$mail->SMTPSecure = $encription;                       
				$mail->Port = $smtp_port;                            
				$mail->setFrom($email_from, 'CÔNG TY TNHH VIDOCO');
				$mail->addAddress($email_to, 'Khách hàng');   
				$mail->Subject = 'Thông tin lấy lại mật khẩu tại website '.url('/') ; 
				$html_content='';     
				$html_content .='<table border="1" cellspacing="5" cellpadding="5" width="50%">';
				$html_content .='<thead>';
				$html_content .='<tr>';
				$html_content .='<th colspan="2"><h3>Thông tin tài khoản</h3></th>';
				$html_content .='</tr>';
				$html_content .='</thead>';
				$html_content .='<tbody>';
				$html_content .='<tr><td>Email</td><td>'.@$email.'</td></tr>';
				$html_content .='<tr><td>Mật khẩu</td><td>'.@$password.'</td></tr>';              								  
				$html_content .='</tbody>';
				$html_content .='</table>';                
				$mail->msgHTML($html_content);
				$mail->Send();
				/* end send mail certification */
				$msg['success']="Mật khẩu đã được gửi về email của bạn . Vui lòng kiểm tra";
			}
		}
		return view("frontend.employer-reset-password",compact('msg',"data",'checked'));
	}
	public function resetPassWrdCandidate(Request $request){
		$msg=array();
		$data=array();     
		$checked=1;  
		if($request->isMethod('post')){
			$data               = @$request->all();
			$email              = trim(@$request->email);
			$source=CandidateModel::whereRaw('email = ?',[@$email])->select('id')->get()->toArray();
			if(count($source) == 0){
				$checked=0;
				$msg['not-email']='Email này không có trong hệ thống';
			}
			if((int)@$checked == 1){
				/* begin code_alias */
				$source_character = array_merge(range('a','z'), range(0,9));
				$code = implode($source_character, '');
				$code = str_shuffle($code);
				$password   = substr($code, 0, 12);
				/* end code_alias */
				$candidate=CandidateModel::find((int)@$source[0]['id']);
				$candidate->password     = Hash::make(@$password) ;
				$candidate->save();
				/* begin load setting */
				$setting=getSettingSystem();    
				$smtp_host      = @$setting['smtp_host']['field_value'];
				$smtp_port      = @$setting['smtp_port']['field_value'];
				$smtp_auth      = @$setting['authentication']['field_value'];
				$encription     = @$setting['encription']['field_value'];
				$smtp_username  = @$setting['smtp_username']['field_value'];
				$smtp_password  = @$setting['smtp_password']['field_value'];				
				$email_from     = 'tichtacso@gmail.com';
				$email_to       = @$email;				
				/* end load setting */       
				/* begin send mail certification */
				$mail = new PHPMailer(true);
				$mail->SMTPDebug = 0;                           
				$mail->isSMTP();     
				$mail->CharSet = "UTF-8";          
				$mail->Host = $smtp_host; 
				$mail->SMTPAuth = $smtp_auth;                         
				$mail->Username = $smtp_username;             
				$mail->Password = $smtp_password;             
				$mail->SMTPSecure = $encription;                       
				$mail->Port = $smtp_port;                            
				$mail->setFrom($email_from, 'CÔNG TY TNHH VIDOCO');
				$mail->addAddress($email_to, 'Khách hàng');   
				$mail->Subject = 'Thông tin lấy lại mật khẩu tại website '.url('/') ; 
				$html_content='';     
				$html_content .='<table border="1" cellspacing="5" cellpadding="5" width="50%">';
				$html_content .='<thead>';
				$html_content .='<tr>';
				$html_content .='<th colspan="2"><h3>Thông tin tài khoản</h3></th>';
				$html_content .='</tr>';
				$html_content .='</thead>';
				$html_content .='<tbody>';
				$html_content .='<tr><td>Email</td><td>'.@$email.'</td></tr>';
				$html_content .='<tr><td>Mật khẩu</td><td>'.@$password.'</td></tr>';              								  
				$html_content .='</tbody>';
				$html_content .='</table>';                
				$mail->msgHTML($html_content);
				$mail->Send();
				/* end send mail certification */
				$msg['success']="Mật khẩu đã được gửi về email của bạn . Vui lòng kiểm tra";
			}
		}
		return view("frontend.candidate-reset-password",compact('msg',"data",'checked'));
	}
	public function loginCandidate(Request $request){         
		$msg=array();
		$checked=1;
		$data=array();       
		$arrUser=array();
		$source=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}     
		if(count($arrUser) > 0){
			$email=@$arrUser['email'];   
			$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
			if(count($source) > 0){
				return redirect()->route('frontend.index.viewCandidateAccount');
			}      
		}
		if($request->isMethod('post')){                    
			$email              = trim(@$request->email);
			$password           = @$request->password ;
			$source=CandidateModel::whereRaw('trim(lower(email)) = ? and status = ?',[trim(mb_strtolower(@$email,'UTF-8')),1])->select('id','email','password')->get()->toArray();
			if(count($source) > 0){
				$password_hashed=$source[0]['password'];
				if(Hash::check($password,$password_hashed)){
					$arrUser=array("id"=>$source[0]["id"],"email" => $source[0]["email"]);         
					Session::forget($this->_ssNameUser);                                 
					Session::put($this->_ssNameUser,$arrUser);  
					return redirect()->route('frontend.index.viewCandidateAccount'); 
				}else{
					$msg['success']="Đăng nhập sai mật khẩu";
					$checked=0;
				}              
			}else{
				$msg['success']="Đăng nhập sai email hoặc tài khoản chưa được kích hoạt";
				$checked=0;
			}          
		}                       
		return view("frontend.candidate-login",compact('msg',"data",'checked'));                       
	}    

	public function loginApply(Request $request){         
		$msg=array();
		$checked=1;		     		
		$source=array();	
		$info=array();	
		$link='';
		if($request->isMethod('post')){                    
			$email              = trim(@$request->email);
			$password           = @$request->password ;	
			$recruitment_id 	= (int)@$request->recruitment_id;		
			$source=CandidateModel::whereRaw('trim(lower(email)) = ? and status = ?',[trim(mb_strtolower(@$email,'UTF-8')),1])->select('id','email','password')->get()->toArray();
			if(count($source) > 0){
				$password_hashed=$source[0]['password'];
				if(Hash::check($password,$password_hashed)){
					$arrUser=array("id"=>$source[0]["id"],"email" => $source[0]["email"]);         
					Session::forget($this->_ssNameUser);                                 
					Session::put($this->_ssNameUser,$arrUser);  									
					$msg['success']="Đăng nhập thành công";
					$link=route("frontend.index.getFormApplied",[@$recruitment_id]);
				}else{
					$msg['error']="Đăng nhập sai mật khẩu";
					$checked=0;
				}              
			}else{
				$msg['error']="Đăng nhập sai email hoặc tài khoản chưa được kích hoạt";
				$checked=0;
			}          
		}                       
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,      
			'link_edit'=>$link
		);                        
		return $info;                  
	}   


	public function loginSavedRecruitment(Request $request){         
		$msg=array();
		$checked=1;		     		
		$source=array();	
		$info=array();	
		$link='';
		if($request->isMethod('post')){                    
			$email              = trim(@$request->email);
			$password           = @$request->password ;	
			$recruitment_id 	= (int)@$request->recruitment_id;
			$recruitment=RecruitmentModel::find((int)@$recruitment_id)->toArray();			
			$source=CandidateModel::whereRaw('trim(lower(email)) = ? and status = ?',[trim(mb_strtolower(@$email,'UTF-8')),1])->select('id','email','password')->get()->toArray();
			if(count($source) == 0){
				$msg['error']="Đăng nhập sai email hoặc tài khoản chưa được kích hoạt";
				$checked=0;
			}else{
				$password_hashed=$source[0]['password'];
				if(Hash::check($password,$password_hashed)){
					$arrUser=array("id"=>$source[0]["id"],"email" => $source[0]["email"]);         
					Session::forget($this->_ssNameUser);                                 
					Session::put($this->_ssNameUser,$arrUser);  
					$data_recruitment_saved=RecruitmentSavedModel::whereRaw('candidate_id = ? and recruitment_id = ?',[(int)@$arrUser['id'],(int)@$recruitment_id])->select('id')->get()->toArray();
					if(count($data_recruitment_saved) > 0){
						$msg['error']="Việc làm đã được lưu";						
						$checked=0;
					}				
				}else{
					$msg['error']="Đăng nhập sai mật khẩu";
					$checked=0;
				}           
			}
			if((int)@$checked == 1){
				$item=new RecruitmentSavedModel;
				$item->candidate_id=(int)@$arrUser['id'];
				$item->recruitment_id=(int)@$recruitment_id;
				$item->created_at 	=	date("Y-m-d H:i:s",time());        
				$item->updated_at 	=	date("Y-m-d H:i:s",time());        
				$item->save();																				
				$msg['success']="Lưu việc làm thành công";									
			}	  
			$link=route("frontend.index.index",[@$recruitment['alias']]);
		}                       
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,      
			'link_edit'=>$link
		);                        
		return $info;                  
	}   

	public function saveQuicklyRecruitment(Request $request){         
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();  		
		$alias='';
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}         
		if($request->isMethod('post')){
			$recruitment_id 	= (int)@$request->recruitment_id;
			$recruitment=RecruitmentModel::find((int)@$recruitment_id)->toArray();			
			$alias=@$recruitment['alias'];
			$data_recruitment_saved=RecruitmentSavedModel::whereRaw('candidate_id = ? and recruitment_id = ?',[(int)@$arrUser['id'],(int)@$recruitment_id])->select('id')->get()->toArray();
			if(count(@$data_recruitment_saved) > 0){
				$msg['error']="Việc làm đã được lưu";						
				$checked=0;
			}
			if((int)@$checked == 1){
				$item=new RecruitmentSavedModel;
				$item->candidate_id=(int)@$arrUser['id'];
				$item->recruitment_id=(int)@$recruitment_id;
				$item->created_at 	=	date("Y-m-d H:i:s",time());        
				$item->updated_at 	=	date("Y-m-d H:i:s",time());        
				$item->save();																				
				$msg['success']="Lưu việc làm thành công";									
			}	  
		}
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,                    
		);    
		return redirect()->route('frontend.index.index',[@$alias])->with(["message"=>$info]);        
	}   

	public function apply(Request $request){
		$msg=array();
		$checked=1;		     				
		$info=array();	
		$link_edit='';			
		if($request->isMethod('post')){                    
			$recruitment_id 	= (int)@$request->recruitment_id;	
			$profile_id              = (int)@$request->profile_id;								
			$candidate_id = (int)@$request->candidate_id;
			$data_profile=ProfileModel::find((int)@$profile_id)->toArray();
			if((int)@$data_profile['status'] == 0){
				$msg['error']='Hồ sơ chưa được duyệt nên không thể nộp vào vị trí này';
				$checked=0;		
			}
			$data_profile_applied=ProfileAppliedModel::whereRaw('recruitment_id = ? and candidate_id = ?',[(int)@$recruitment_id,(int)@$candidate_id])->select('id')->get()->toArray();			
			if(count($data_profile_applied) > 0){
				$msg['error']='Ứng viên đã ứng tuyển vị trí này';
				$checked=0;	
			}			
			if((int)@$checked==1){
				$item=new ProfileAppliedModel;
				$item->recruitment_id=(int)@$recruitment_id;
				$item->profile_id=(int)@$profile_id;				
				$item->candidate_id=(int)@$candidate_id;				
				$item->created_at 	=	date("Y-m-d H:i:s",time());        
				$item->updated_at 	=	date("Y-m-d H:i:s",time());        
				$item->save();			
				$msg['success']='Nộp hồ sơ hoàn tất';
				$link_edit=route('frontend.index.getHome');
			}			
		}                       
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,      
			'link_edit'=>$link_edit
		);                        
		return $info;    
	}
	public function viewAppliedProfile(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count(@$arrUser)==0){
			return redirect()->route("frontend.index.employerLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$recruitment_name='';
		$candidate_name='';
		$query=DB::table('candidate')		
		->join('profile_applied','candidate.id','=','profile_applied.candidate_id')		
		->join('recruitment','recruitment.id','=','profile_applied.recruitment_id');     		
		if(!empty(@$request->candidate_name)){
			$candidate_name=@$request->candidate_name;
			$query->where('candidate.fullname','like', '%'.trim(@$candidate_name).'%');
		}
		if(!empty(@$request->recruitment_name)){
			$recruitment_name=@$request->recruitment_name;
			$query->where('recruitment.fullname','like', '%'.trim(@$recruitment_name).'%');
		}		
		$query->where('recruitment.employer_id',(int)@$arrUser['id']);
		$query->where('profile_applied.status',1);
		$data=$query->select('candidate.id')
		->groupBy('candidate.id')                
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('candidate.id','candidate.fullname','recruitment.fullname as recruitment_name','profile_applied.profile_id','profile_applied.file_attached','profile_applied.created_at')
		->groupBy('candidate.id','candidate.fullname','recruitment.fullname','profile_applied.profile_id','profile_applied.file_attached','profile_applied.created_at')
		->orderBy('recruitment.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=recruitmentProfileConverter($data);
		return view('frontend.cabinet-applied-profile',compact('data','msg','checked',"pagination",'recruitment_name','candidate_name'));     
	}

	public function viewAppliedRecruitment(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$recruitment_name='';		
		$query=DB::table('profile_applied')		
		->leftJoin('recruitment','profile_applied.recruitment_id','=','recruitment.id')		
		->leftJoin('profile','profile_applied.profile_id','=','profile.id');
		if(!empty(@$request->recruitment_name)){
			$recruitment_name=@$request->recruitment_name;
			$query->where('recruitment.fullname','like', '%'.trim(@$recruitment_name).'%');
		}		
		$query->where('profile_applied.candidate_id',(int)@$arrUser['id']);		
		$data=$query->select('profile_applied.id')
		->groupBy('profile_applied.id')                
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('profile_applied.id','recruitment.fullname as recruitment_name','profile_applied.profile_id','profile.fullname as profile_name','profile_applied.file_attached','profile_applied.created_at')
		->groupBy('profile_applied.id','recruitment.fullname','profile_applied.profile_id','profile.fullname','profile_applied.file_attached','profile_applied.created_at')
		->orderBy('profile_applied.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=appliedRecruitmentConverter($data);
		return view('frontend.cabinet-applied-recruitment',compact('data','msg','checked',"pagination",'recruitment_name'));     
	}
	public function viewSavedRecruitment(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$recruitment_name='';		
		$query=DB::table('recruitment_saved')		
		->join('recruitment','recruitment_saved.recruitment_id','=','recruitment.id')		;
		if(!empty(@$request->recruitment_name)){
			$recruitment_name=@$request->recruitment_name;
			$query->where('recruitment.fullname','like', '%'.trim(@$recruitment_name).'%');
		}		
		$query->where('recruitment_saved.candidate_id',(int)@$arrUser['id']);		
		$data=$query->select('recruitment_saved.id')
		->groupBy('recruitment_saved.id')                
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     
		$data=$query->select('recruitment_saved.id','recruitment.fullname as recruitment_name','recruitment.alias','recruitment_saved.created_at')
		->groupBy('recruitment_saved.id','recruitment.fullname','recruitment.alias','recruitment_saved.created_at')
		->orderBy('recruitment_saved.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    		
		$data=savedRecruitmentConverter($data);
		return view('frontend.cabinet-saved-recruitment',compact('data','msg','checked',"pagination",'recruitment_name'));     
	}
	public function viewSavedProfile(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.employerLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$q='';				
		$query=DB::table('profile_saved')
		->join('profile','profile_saved.profile_id','=','profile.id')				
		->join('candidate','profile.candidate_id','=','candidate.id')
		->join('literacy','profile.literacy_id','=','literacy.id')
		->join('experience','profile.experience_id','=','experience.id'); 
		$query->where('profile_saved.employer_id',(int)@$arrUser['id']);
		if(!empty(@$request->q)){
			$q=@$request->q;
			$query->where('candidate.fullname','like', '%'.trim(@$q).'%');
		}			
		$data=$query->select('profile.id')
		->groupBy('profile.id')       
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('profile_saved.id',
				'profile.fullname as profile_name',
				'candidate.fullname as candidate_name',
				'literacy.fullname as literacy_name',
				'experience.fullname as experience_name',
				'profile.salary',
				'profile_saved.created_at')
		->groupBy('profile_saved.id',
				'profile.fullname',
				'candidate.fullname',
				'literacy.fullname',
				'experience.fullname',
				'profile.salary',
				'profile_saved.created_at')
		->orderBy('profile.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=savedProfileConverter($data);
		return view('frontend.saved-profile',compact('data','msg','checked',"pagination","q"));     
	}

	public function searchingProfile(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.employerLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$q='';		
		$job_id=0;
		$province_id=0;
		$salary_id=0;		
		$salary_text='';
		$literacy_id=0;
		$language_id=0;
		$sex_id=0;
		$experience_id=0;
		$query=DB::table('profile')		
		->join('candidate','profile.candidate_id','=','candidate.id')
		->join('literacy','profile.literacy_id','=','literacy.id')
		->join('experience','profile.experience_id','=','experience.id')
		->join('profile_place','profile.id','=','profile_place.profile_id')
		->join('province','profile_place.province_id','=','province.id');     
		$query->where('profile.status',1)
				->where('profile.status_search',1);		
		if(!empty(@$request->q)){
			$q=@$request->q;
			$query->where('profile.fullname','like', '%'.trim(@$q).'%');
		}	
		if((int)@$request->job_id > 0){
			$job_id=(int)@$request->job_id;
			$query->join('profile_job','profile.id','=','profile_job.profile_id');
			$query->where('profile_job.job_id',(int)@$request->job_id);
		}	
		if((int)@$request->province_id > 0){	
			$province_id=		(int)@$request->province_id;
			$query->where('profile_place.province_id',(int)@$request->province_id);
		}
		$salary_id=(int)@$request->salary_id;
		if(!empty(@$request->salary)){
			$pattern_dot='#\.#';
			$salary_text=@$request->salary;
			$salary=preg_replace($pattern_dot, '', @$request->salary);   						
			if($salary_id == 1){
				$query->where('profile.salary','>=',(int)@$salary);
			}else{
				$query->where('profile.salary','<',(int)@$salary);
			}			
		}
		if((int)@$request->literacy_id > 0){
			$literacy_id=(int)@$request->literacy_id;
			$query->where('profile.literacy_id',(int)@$request->literacy_id);
		}
		if((int)@$request->language_id > 0){
			$language_id=(int)@$request->language_id;
			$query->join('profile_language','profile.id','=','profile_language.profile_id');
			$query->where('profile_language.language_id',(int)@$request->language_id);
		}		
		if((int)@$request->sex_id > 0){
			$sex_id=		(int)@$request->sex_id;
			$query->where('candidate.sex_id',(int)@$request->sex_id);
		}
		if((int)@$request->experience_id > 0){	
			$experience_id=		(int)@$request->experience_id;
			$query->where('profile.experience_id',(int)@$request->experience_id);
		}	
		$data=$query->select('profile.id')
		->groupBy('profile.id')       
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('profile.id',
				'profile.fullname as profile_name',
				'candidate.fullname as candidate_name',
				'literacy.fullname as literacy_name',
				'experience.fullname as experience_name',
				'profile.salary')
		->groupBy('profile.id',
				'profile.fullname',
				'candidate.fullname',
				'literacy.fullname',
				'experience.fullname',
				'profile.salary')
		->orderBy('profile.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=searchingProfileConverter($data);
		return view('frontend.searching-profile',compact('data','msg','checked',"pagination","q","job_id","province_id","salary_id","salary_text","literacy_id","language_id","sex_id","experience_id"));     
	}
	public function getAppliedProfileDetail(Request $request, $profile_id,$save_id){
		$checked=1;
		$msg=array();        		
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.employerLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		if($request->isMethod('post')){
			$employer_id=(int)@$request->employer_id;
			$data_profile_saved=ProfileSavedModel::whereRaw('employer_id = ? and profile_id = ?',[(int)@$employer_id,(int)@$profile_id])->select('id')->get()->toArray();
			if(count(@$data_profile_saved) > 0){
				$msg['error']='Hồ sơ này đã được lưu';
				$checked=0;
			}
			if((int)@$checked == 1){
				$item=new ProfileSavedModel;
				$item->employer_id=(int)@$employer_id;			
				$item->profile_id=(int)@$profile_id;
				$item->created_at=date("Y-m-d H:i:s",time());   
				$item->updated_at=date("Y-m-d H:i:s",time());   
				$item->save();   
				$msg['success']='Lưu hồ sơ thành công';
			}			
		}
		return view('frontend.applied-profile-detail',compact('msg','checked','profile_id','save_id'));  
	}
	public function getFormSearchProfile(){
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.employerLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		return view('frontend.form-search-profile');  
	}
	public function getFormApplied(Request $request,$recruitment_id){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}     
		if(count(@$arrUser) == 0){
			return redirect()->route("frontend.index.candidateLogin");
		}    
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin");
		}		
		return view("frontend.applied-form",compact('data','msg','checked','recruitment_id'));        
	} 
	public function viewEmployerAccount(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}    
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		$data=EmployerModel::find((int)@$source[0]['id'])->toArray();     

		if($request->isMethod('post')){
			$data               = @$request->all();          
			$fullname           = trim(@$request->fullname);
			$address            = trim(@$request->address);
			$phone              = trim(@$request->phone);
			$province_id        = trim(@$request->province_id);
			$scale_id           = trim(@$request->scale_id);
			$image_file           =   null;
			if(isset($_FILES["image"])){
				$image_file         =   $_FILES["image"];
			}                 
			$image_hidden         =   trim($request->image_hidden);
			$data['logo']=$image_hidden;
			$intro              = trim(@$request->intro);
			$fax                = trim(@$request->fax);
			$website            = trim(@$request->website);
			$contacted_name     = trim(@$request->contacted_name);
			
			$contacted_phone    = trim(@$request->contacted_phone);
			if(mb_strlen($fullname) < 15){
				$msg["fullname"] = 'Tên công ty phải từ 15 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}else{
				$source=array();
				$source=EmployerModel::whereRaw("trim(lower(fullname)) = ? and id != ?",[trim(mb_strtolower($fullname,'UTF-8')),(int)@$arrUser['id']])->get()->toArray();    
				if (count($source) > 0) {
					$msg["fullname"] = "Tên công ty đã có trong hệ thống. ";
					$data['fullname']='';
					$checked = 0;                    
				}       
			}  
			if(mb_strlen($address) < 15){
				$msg["address"] = 'Địa chỉ phải từ 15 ký tự trở lên';      
				$data['address']='';      
				$checked = 0;
			}       
			if(mb_strlen($phone) < 10){
				$msg["phone"] = 'Điện thoại công ty phải từ 10 ký tự trở lên';   
				$data['phone']='';         
				$checked = 0;
			}else{
				$source=array();
				$source=EmployerModel::whereRaw("trim(lower(phone)) = ? and id != ?",[trim(mb_strtolower($phone,'UTF-8')),(int)@$arrUser['id']])->get()->toArray();    
				if (count($source) > 0) {
					$msg["phone"] = "Điện thoại công ty đã có trong hệ thống. ";
					$data['phone']='';
					$checked = 0;                
				}       
			}  
			if((int)@$request->province_id == 0){
				$msg["province_id"] = 'Vui lòng chọn tỉnh thành phố';            
				$data['province_id']=0;
				$checked = 0;
			}
			if((int)@$request->scale_id == 0){
				$msg["scale_id"] = 'Vui lòng chọn quy mô công ty';    
				$data['scale_id']=0;        
				$checked = 0;
			}      
			if(mb_strlen($website) > 0){
				if(!preg_match("#^(https?://(www\.)?|(www\.))[a-z0-9\-]{3,}(\.[a-z]{2,4}){1,2}$#", mb_strtolower($website,'UTF-8')   )){
					$msg["website"] = 'Website công ty không hợp lệ';     
					$data['website']='';       
					$checked = 0;
				}
			}      
			if(mb_strlen($contacted_name) < 6){
				$msg["contacted_name"] = 'Họ tên người liên hệ phải từ 6 ký tự trở lên';   
				$data['contacted_name']='';         
				$checked = 0;
			} 
			
			if(mb_strlen($contacted_phone) < 10){
				$msg["contacted_phone"] = 'Số điện thoại người liên hệ phải từ 10 ký tự trở lên';            
				$data['contacted_phone']='';
				$checked = 0;
			}
			if($checked==1){
				$item               = EmployerModel::find((int)@$arrUser['id']);
				$item->fullname     = @$fullname;
				$item->address      = @$address;
				$item->phone        = @$phone;
				$item->province_id  = @$province_id;
				$item->scale_id     = @$scale_id;
				/* begin upload logo */
				$setting= getSettingSystem();
				$width=$setting['product_width']['field_value'];
				$height=$setting['product_height']['field_value'];  
				$image_name='';
				if($image_file != null){          
					if(!empty($image_file['name'])){
						$image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);
					}   	        	
				}
				$item->logo=null;                       
				if(!empty($image_hidden)){
					$item->logo =$image_hidden;          
				}
				if(!empty($image_name))  {
					$item->logo=$image_name;                                                
				}   
				/* end upload logo */
				$item->intro        = @$intro;
				$item->fax          = @$fax;
				$item->website      = @$website;
				$item->contacted_name   = @$contacted_name;
				
				$item->contacted_phone  = @$contacted_phone;               
				$item->updated_at=date("Y-m-d H:i:s",time());   
				$item->save();   
				$data=EmployerModel::find((int)@$arrUser['id'])->toArray();
				$msg['success']='<span>Cập nhật tài khoản nhà tuyển dụng thành công.</span>';
			}
		}
		return view("frontend.employer-account",compact('data','msg','checked'));         
	}
	public function viewCandidateAccount(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}     
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.candidateLogin");
		}    
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin");
		}
		$data=CandidateModel::find((int)@$source[0]['id'])->toArray();  
		$birthday=$data['birthday'];
		$arrDate    = date_parse_from_format('Y-m-d', $birthday) ;     
		$data['day']=(int)@$arrDate['day'];
		$data['month']=(int)@$arrDate['month'];
		$data['year']=(int)@$arrDate['year'];
		if($request->isMethod('post')){
			$data               = @$request->all();      
			$fullname           = trim(@$request->fullname);
			$phone              = trim(@$request->phone);   
			/* begin avatar */
			$image_file           =   null;
			if(isset($_FILES["image"])){
				$image_file         =   $_FILES["image"];
			}                 
			$image_hidden         =   trim($request->image_hidden);    
			$data['avatar']=$image_hidden;     
			/* end avatar */
			$day=(int)@$request->day;
			$month=(int)@$request->month;
			$year=(int)@$request->year;     
			$sex_id=trim(@$request->sex_id);
			$marriage_id=trim(@$request->marriage_id);
			$province_id=trim(@$request->province_id); 
			$address=trim(@$request->address);
			if(mb_strlen($fullname) < 6){
				$msg["fullname"] = 'Tên ứng viên phải từ 6 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}        
			if(mb_strlen($phone) < 10){
				$msg["phone"] = 'Điện thoại ứng viên phải từ 10 ký tự trở lên';   
				$data['phone']='';         
				$checked = 0;
			}else{
				$source=array();
				$source=CandidateModel::whereRaw("trim(lower(phone)) = ? and id != ?",[trim(mb_strtolower($phone,'UTF-8')),(int)@$arrUser['id']])->get()->toArray();    
				if (count($source) > 0) {
					$msg["phone"] = "Điện thoại ứng viên đã có trong hệ thống. ";
					$data['phone']='';
					$checked = 0;                
				}       
			}        
			if($day==0){
				$msg["day"] = 'Thiếu ngày sinh';    
				$data['day']='';        
				$checked = 0;
			}
			if($month==0){
				$msg["month"] = 'Thiếu tháng sinh';    
				$data['month']='';        
				$checked = 0;
			}
			if($year==0){
				$msg["year"] = 'Thiếu năm sinh';    
				$data['year']='';        
				$checked = 0;
			}
			if((int)@$request->sex_id == 0){
				$msg["sex_id"] = 'Vui lòng chọn giới tính';            
				$data['sex_id']=0;
				$checked = 0;
			}
			if((int)@$request->marriage_id == 0){
				$msg["marriage_id"] = 'Vui lòng chọn tình trạng hôn nhân';            
				$data['marriage_id']=0;
				$checked = 0;
			}
			if((int)@$request->province_id == 0){
				$msg["province_id"] = 'Vui lòng chọn tỉnh thành phố';            
				$data['province_id']=0;
				$checked = 0;
			}
			if(mb_strlen(@$address) < 10){
				$msg["address"] = 'Vui lòng nhập chỗ ở hiện tại';            
				$data['address']='';
				$checked = 0;
			}
			if($checked==1){
				$item               = CandidateModel::find((int)@$arrUser['id']);
				$item->fullname     = @$fullname;
				$item->phone        = @$phone;   
				/* begin ngày sinh nhật */        
				$ts=mktime(0,0,0,$month,$day,$year);        
				$birthday=date('Y-m-d', $ts);
				$item->birthday=$birthday;
				/* end ngày sinh nhật */ 
				$item->sex_id=(int)@$sex_id;
				$item->marriage_id=(int)@$marriage_id;
				$item->province_id=(int)@$province_id;
				$item->address=@$address;
				/* begin upload avatar */
				$setting= getSettingSystem();
				$width=$setting['product_width']['field_value'];
				$height=$setting['product_height']['field_value'];  
				$image_name='';
				if($image_file != null){          
					if(!empty($image_file['name'])){
						$image_name=uploadImage($image_file['name'],$image_file['tmp_name'],$width,$height);
					}   	        	
				}
				$item->avatar=null;                       
				if(!empty($image_hidden)){
					$item->avatar =$image_hidden;          
				}
				if(!empty($image_name))  {
					$item->avatar=$image_name;                                                
				} 
				/* end upload avatar */                         
				$item->updated_at=date("Y-m-d H:i:s",time());   
				$item->save();   
				$data               = CandidateModel::find((int)@$arrUser['id']);
				$data['day']=(int)@$day;
				$data['month']=(int)@$month;
				$data['year']=(int)@$year;
				$msg['success']='<span>Cập nhật tài khoản ứng viên thành công.</span>';
			}
		}
		return view("frontend.candidate-account",compact('data','msg','checked'));         
	}
	public function viewEmployerSecurity(Request $request){   
		$checked=1;
		$msg=array();        
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}          
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}
		if($request->isMethod('post')){
			$password           = @$request->password;
			$password_confirmed = @$request->password_confirmed;
			if(mb_strlen($password) < 10 ){
				$msg["password"] = "Mật khẩu tối thiểu phải 10 ký tự";
				$checked = 0;                
			}else{
				if(strcmp($password, $password_confirmed) !=0 ){
					$msg["password"] = "Xác nhận mật khẩu không trùng khớp";
					$checked = 0;                  
				}
			}  
			if($checked==1){
				$item               = EmployerModel::find((int)@$arrUser['id']);
				$item->password     = Hash::make(@$password) ;
				$item->save();   
				$msg['success']='<span>Đổi mật khẩu thành công.</span>';
			} 
		}
		return view("frontend.employer-security",compact('msg','checked'));                               
	}
	public function viewCandidateSecurity(Request $request){   
		$checked=1;
		$msg=array();          
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}
		if($request->isMethod('post')){
			$password           = @$request->password;
			$password_confirmed = @$request->password_confirmed;
			if(mb_strlen($password) < 10 ){
				$msg["password"] = "Mật khẩu tối thiểu phải 10 ký tự";
				$checked = 0;                
			}else{
				if(strcmp($password, $password_confirmed) !=0 ){
					$msg["password"] = "Xác nhận mật khẩu không trùng khớp";
					$checked = 0;                  
				}
			}  
			if($checked==1){
				$item               = CandidateModel::find((int)@$arrUser['id']);
				$item->password     = Hash::make(@$password) ;
				$item->save();   
				$msg['success']='<span>Đổi mật khẩu thành công.</span>';
			} 
		}
		return view("frontend.candidate-security",compact('msg','checked'));                                   
	}
	public function logoutEmployer(){
		$arrUser=array();            
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
			Session::forget($this->_ssNameUser);      
		}    
		return redirect()->route("frontend.index.employerLogin");
	}
	public function logoutCandidate(){
		$arrUser=array();            
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
			Session::forget($this->_ssNameUser);      
		}    
		return redirect()->route("frontend.index.candidateLogin");
	}	
	public function getFormRecruitment(Request $request,$task,$id){
		$checked=1;
		$msg=array();        
		$data=array();
		
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}

		$email=@$arrUser['email'];       
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','contacted_name','address','contacted_phone')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}           
		if($task == 'edit'){
			$source2=RecruitmentModel::find((int)@$id);
			if($source2==null){
				Session::forget($this->_ssNameUser);   
				return redirect()->route("frontend.index.employerLogin");    
			}else{
				$data=$source2->toArray();
				if((int)@$data['employer_id'] != (int)@$arrUser['id']){
					Session::forget($this->_ssNameUser);   
					return redirect()->route("frontend.index.employerLogin");    
				}
			}		
			$source_recruitment_job=RecruitmentJobModel::whereRaw('recruitment_id = ?',[(int)@$id])->select('job_id')->get()->toArray();
			$source_recruitment_place=RecruitmentPlaceModel::whereRaw('recruitment_id = ?',[(int)@$id])->select('province_id')->get()->toArray();
			$source_job_id=array();
			$source_province_id=array();
			if(count($source_recruitment_job) > 0){
				foreach ($source_recruitment_job as $key => $value) {
					$source_job_id[]=$value['job_id'];
				}
			}
			if(count($source_recruitment_place) > 0){
				foreach ($source_recruitment_place as $key => $value) {
					$source_province_id[]=$value['province_id'];
				}
			}      
			$data['job_id']=$source_job_id;
			$data['province_id']=$source_province_id;
			$data['duration']=datetimeConverterVn($data['duration']);      
		}
		$data['contacted_name']=@$source[0]['contacted_name'];		
		$data['address']=@$source[0]['address'];
		$data['contacted_phone']=@$source[0]['contacted_phone'];
		if($request->isMethod('post')){
			$data             =   @$request->all();              
			$fullname         =   trim(@$request->fullname);
			$quantity         =   trim(@$request->quantity);
			$sex_id           =   trim(@$request->sex_id);  
			$description      =   trim(@$request->description);
			$requirement      =   trim(@$request->requirement);
			$work_id          =   trim(@$request->work_id);
			$literacy_id      =   trim(@$request->literacy_id);
			$experience_id    =   trim(@$request->experience_id);
			$salary_id        =   trim(@$request->salary_id);
			$commission_from  =   trim(@$request->commission_from);
			$commission_to    =   trim(@$request->commission_to);
			$working_form_id  =   trim(@$request->working_form_id);
			$probationary_id  =   trim(@$request->probationary_id);
			$benefit          =   trim(@$request->benefit);
			$job_id           =   @$request->job_id;
			$province_id      =   @$request->province_id;
			$requirement_profile          =   trim(@$request->requirement_profile);
			$duration         =   trim(@$request->duration);
			$status_employer           =   trim(@$request->status_employer); 
			$contacted_name   =   trim(@$request->contacted_name);
			
			$address          =   trim(@$request->address);
			$contacted_phone  =   trim(@$request->contacted_phone);     
			if(mb_strlen(@$fullname) < 15){
				$msg["fullname"] = 'Tiêu đề phải từ 15 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}    
			if((int)@$quantity == 0){
				$msg["quantity"] = 'Số lượng cần tuyển phải lớn hơn 0';    
				$data['quantity']='';        
				$checked = 0;
			} 
			if((int)@$sex_id == 0){
				$msg["sex_id"] = 'Vui lòng chọn giới tính';    
				$data['sex_id']='';        
				$checked = 0;
			}
			if(mb_strlen(@$description) == 0){
				$msg["description"] = 'Vui lòng nhập mô tả công việc';    
				$data['description']='';        
				$checked = 0; 
			}
			if(mb_strlen(@$requirement) == 0){
				$msg["requirement"] = 'Vui lòng nhập yêu cầu công việc';    
				$data['requirement']='';        
				$checked = 0; 
			}
			if((int)@$work_id == 0){
				$msg["work_id"] = 'Vui lòng chọn tính chất công việc';    
				$data['work_id']='';        
				$checked = 0;  
			}
			if((int)@$literacy_id == 0){
				$msg["literacy_id"] = 'Vui lòng chọn trình độ học vấn';    
				$data['literacy_id']='';        
				$checked = 0;   
			}
			if((int)@$experience_id == 0){
				$msg["experience_id"] = 'Vui lòng chọn kinh nghiệm';    
				$data['experience_id']='';        
				$checked = 0;    
			}
			if((int)@$salary_id == 0){
				$msg["salary_id"] = 'Vui lòng chọn mức lương';    
				$data['salary_id']='';        
				$checked = 0;     
			}
			if((int)@$commission_from != 0 || (int)@$commission_to != 0){
				if((int)@$commission_from < 0 || (int)@$commission_to < 0){
					$msg["commission_from"] = 'Mức hoa hồng phải lớn hơn 0';    
					$data['commission_from']='';        
					$data['commission_to']='';        
					$checked = 0;    
				}
				if((int)@$commission_to <= (int)@$commission_from){
					$msg["commission_from"] = 'Mức hoa hồng không hợp lệ';    
					$data['commission_from']='';        
					$data['commission_to']='';        
					$checked = 0;     
				}
			}
			if((int)@$working_form_id == 0){
				$msg["working_form_id"] = 'Vui lòng chọn hình thức công việc';    
				$data['working_form_id']='';        
				$checked = 0;      
			}
			if((int)@$probationary_id == 0){
				$msg["probationary_id"] = 'Vui lòng chọn thời gian thử việc';    
				$data['probationary_id']='';        
				$checked = 0;      
			}
			if(mb_strlen(@$benefit)  == 0){
				$msg["benefit"] = 'Vui lòng nhập quyền lợi';    
				$data['benefit']='';        
				$checked = 0;      
			}
			if(count(@$job_id) == 0){
				$msg["job_id"] = 'Vui lòng chọn ngành nghề';    
				$data['job_id']='';        
				$checked = 0;      
			}else{
				if((int)@$job_id[0]==0){
					$msg["job_id"] = 'Vui lòng chọn ngành nghề';    
					$data['job_id']='';        
					$checked = 0;      
				}
			}
			if(count(@$province_id) == 0){
				$msg["province_id"] = 'Vui lòng chọn nơi làm việc';    
				$data['province_id']='';        
				$checked = 0;      
			}else{
				if((int)@$province_id[0]==0){
					$msg["province_id"] = 'Vui lòng chọn nơi làm việc';    
					$data['province_id']='';        
					$checked = 0;      
				}
			}			
			if(mb_strlen(@$duration)  == 0){
				$msg["duration"] = 'Vui lòng chọn thời gian hết hạn';    
				$data['duration']='';        
				$checked = 0;      
			}
			if((int)@$status_employer == -1){
				$msg["status_employer"] = 'Vui lòng chọn trạng thái hiển thị tin';    
				$data['status_employer']='';        
				$checked = 0;      
			} 
			if(mb_strlen(@$contacted_name) < 6){
				$msg["contacted_name"] = 'Họ tên người liên hệ phải từ 6 ký tự trở lên';   
				$data['contacted_name']='';         
				$checked = 0;
			} 
			
			if(mb_strlen(@$address) < 15){
				$msg["address"] = 'Địa chỉ phải từ 15 ký tự trở lên';      
				$data['address']='';      
				$checked = 0;
			}  
			if(mb_strlen(@$contacted_phone) < 10){
				$msg["contacted_phone"] = 'Số điện thoại người liên hệ phải từ 10 ký tự trở lên';            
				$data['contacted_phone']='';
				$checked = 0;
			}   
			if($checked==1){
				$item=null;
				switch ($task) {
					case 'add':            
					$item                   = new RecruitmentModel;
					$item->created_at=date("Y-m-d H:i:s");
					$item->updated_at=date("Y-m-d H:i:s");
					break;
					case 'edit':
					$item                   = RecruitmentModel::find((int)@$id);
					$item->updated_at=date("Y-m-d H:i:s");
					break;          
				}              
				$item->fullname           = @$fullname;
				/* begin save alias */
				$alias=str_slug(@$fullname,'-');				
				$data_employer=array();        
				switch ($task) {
					case 'add':
					$data_employer=RecruitmentModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias,'UTF-8'))])->get()->toArray();        
					break;
					case 'edit':
					$data_employer=RecruitmentModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower(@$alias,'UTF-8')),@$id])->get()->toArray();                    
					break;
				}        
				if (count(@$data_employer) > 0) {
					$code_alias=rand(1,999999);
					$alias=$alias.'-'.$code_alias;
				}        				
				$item->alias=@$alias;
				/* end save alias */
				$item->alias            = @$alias;
				$item->quantity         = (int)@$quantity;
				$item->sex_id           = (int)@$sex_id;
				$item->description      = @$description;
				$item->requirement      = @$requirement;
				$item->work_id          = (int)@$work_id;
				$item->literacy_id      = (int)@$literacy_id;
				$item->experience_id    = (int)@$experience_id;
				$item->salary_id        = (int)@$salary_id;
				$item->commission_from  = (int)@$commission_from;
				$item->commission_to    = (int)@$commission_to;
				$item->working_form_id  = (int)@$working_form_id;
				$item->probationary_id  = (int)@$probationary_id;
				$item->benefit          = @$benefit;        
				$item->requirement_profile = @$requirement_profile;
				/* begin duration */
				$arrDate                = date_parse_from_format('d/m/Y',@$duration) ;
				$ts                     = mktime(@$arrDate["hour"],@$arrDate["minute"],@$arrDate["second"],@$arrDate['month'],@$arrDate['day'],@$arrDate['year']);
				$real_date                 = date('Y-m-d', $ts);
				/* end duration */
				$item->duration         = @$real_date;        
				$item->employer_id      = (int)@$arrUser['id'];        
				$item->status_employer           = (int)@$status_employer;
				   
				$item->save();           
				
				$source_recruitment_job=RecruitmentJobModel::whereRaw('recruitment_id = ?',[(int)@$item->id])->select('job_id')->get()->toArray();
				$source_recruitment_place=RecruitmentPlaceModel::whereRaw('recruitment_id = ?',[(int)@$item->id])->select('province_id')->get()->toArray();
				$source_job_id=array();
				$source_province_id=array();
				if(count($source_recruitment_job) > 0){
					foreach ($source_recruitment_job as $key => $value) {
						$source_job_id[]=$value['job_id'];
					}
				}
				if(count($source_recruitment_place) > 0){
					foreach ($source_recruitment_place as $key => $value) {
						$source_province_id[]=$value['province_id'];
					}
				}  
				sort($source_job_id);
				sort($source_province_id);
				sort($job_id);
				sort($province_id);
				$compare_job=0;
				$compare_province=0;
				if($source_job_id == $job_id){
					$compare_job=1;       
				}    
				if($source_province_id == $province_id){
					$compare_province=1;       
				}
				if($compare_job == 0){
					RecruitmentJobModel::whereRaw("recruitment_id = ?",[(int)@$item->id])->delete();   
					foreach ($job_id as $key => $value) {
						$item2=new RecruitmentJobModel;
						$item2->recruitment_id = (int)@$item->id;
						$item2->job_id         = (int)@$value;
						$item2->save();
					}
				}
				if($compare_province == 0){
					RecruitmentPlaceModel::whereRaw("recruitment_id = ?",[(int)@$item->id])->delete();  
					foreach ($province_id as $key => $value) {
						$item3=new RecruitmentPlaceModel;
						$item3->recruitment_id = (int)@$item->id;
						$item3->province_id         = (int)@$value;
						$item3->save();
					}
				}                        
				$item4=EmployerModel::find((int)@$arrUser['id']);
				$item4->contacted_name   = @$contacted_name;				
				$item4->address          = @$address;
				$item4->contacted_phone  = @$contacted_phone; 
				$item4->save();
				switch ($task) {
					case 'add':            
					$msg['success']='<span>Đăng tin thành công.&nbsp;</span><span class="margin-left-5 review"><a  href="'.route('frontend.index.getFormRecruitment',['edit',@$item->id]).'">Xem tin đã đăng</a></span>';
					break;
					case 'edit':            
					$msg['success']='<span>Cập nhật tin đăng thành công</span>';
					break;          
				}        
			}  
		}
		return view('frontend.recruitment',compact('data','msg','checked','task'));     
	}
	public function manageRecruitment(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin");
		}
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}  
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$q='';
		$query=DB::table('recruitment')   ;     
		$query->where('recruitment.employer_id',(int)@$arrUser['id']);    
		if(!empty(@$request->q)){
			$q=@$request->q;
			$query->where('recruitment.fullname','like', '%'.trim(@$q).'%');
		}
		$data=$query->select('recruitment.id')
		->groupBy('recruitment.id')                
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('recruitment.id','recruitment.fullname','recruitment.status_employer','recruitment.created_at')
		->groupBy('recruitment.id','recruitment.fullname','recruitment.status_employer','recruitment.created_at')
		->orderBy('recruitment.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=recruitment2Converter($data,'recruitment');
		return view('frontend.manage-recruitment',compact('data','msg','checked',"pagination",'q'));     
	}
	public function changeRecruitmentAppearedStatus(Request $request){
		$id             =       (int)$request->id;  
		$status         =       (int)$request->status;

		$item=RecruitmentModel::find($id);
		$trangThai=0;
		if($status==0){
			$trangThai=1;
		}
		else{
			$trangThai=0;
		}
		$item->status_employer=$status;
		$item->save();
		$result = array(
			'id'      => $id, 
			'status'  => $status, 
			'link'    => 'javascript:changeStatus('.$id.','.$trangThai.');'
		);
		return $result;   
	}      
	public function deleteRecruitment($id){   
		$info                 =   array();
		$checked              =   1;                           
		$msg                =   array();
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin");
		}
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}  
		$source2=RecruitmentModel::find((int)@$id);
		if($source2 == null){
			$checked=0;
			$msg['errorid']='Không đúng id';
		}else{
			$data=$source2->toArray();
			if((int)@$data['employer_id'] != (int)@$arrUser['id']){
				$checked=0;
				$msg['errorid']='Sai tin tuyển dụng';
			}
		}
		if($checked == 1){			
			RecruitmentModel::find((int)@$id)->delete();          
			RecruitmentJobModel::whereRaw('recruitment_id = ?',[@$id])->delete();  
			RecruitmentPlaceModel::whereRaw('recruitment_id = ?',[@$id])->delete();
			$msg['success']='Xóa thành công';
		}  
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,                    
		);      
		return redirect()->route('frontend.index.manageRecruitment')->with(["message"=>$info]);                             
	}
	public function deleteSavedProfile($id){   
		$info                 =   array();
		$checked              =   1;                           
		$msg                =   array();
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count($arrUser) == 0){
			return redirect()->route("frontend.index.employerLogin");
		}
		$email=@$arrUser['email'];   
		$source=EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.employerLogin"); 
		}  
		$source2=ProfileSavedModel::find((int)@$id);
		if($source2 == null){
			$checked=0;
			$msg['errorid']='Không đúng id';
		}else{
			$data=$source2->toArray();
			if((int)@$data['employer_id'] != (int)@$arrUser['id']){
				$checked=0;
				$msg['errorid']='Sai hồ sơ';
			}
		}
		if($checked == 1){			
			ProfileSavedModel::find((int)@$id)->delete();          			
			$msg['success']='Xóa thành công';
		}  
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,                    
		);      
		return redirect()->route('frontend.index.viewSavedProfile')->with(["message"=>$info]);                             
	}
	public function deleteSavedRecruitment($id){   
		$info                 =   array();
		$checked              =   1;                           
		$msg                =   array();
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}         
		if(count(@$arrUser) == 0){
			return redirect()->route("frontend.index.candidateLogin");
		}
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}  
		$source2=RecruitmentSavedModel::find((int)@$id);
		if($source2 == null){
			$checked=0;
			$msg['errorid']='Không đúng id';
		}else{
			$data=$source2->toArray();
			if((int)@$data['candidate_id'] != (int)@$arrUser['id']){
				$checked=0;
				$msg['errorid']='Sai tin tuyển dụng';
			}
		}
		if($checked == 1){			
			RecruitmentSavedModel::find((int)@$id)->delete();          			
			$msg['success']='Xóa thành công';
		}  
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,                    
		);      
		return redirect()->route('frontend.index.viewSavedRecruitment')->with(["message"=>$info]);                             
	}
	public function getFormProfile(Request $request,$task,$id){
		$info                 =   array();
		$checked=1;
		$msg=array();          
		$arrUser=array();    
		$data=array();				
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}
		if($task == 'edit'){			
			$source2=ProfileModel::find((int)@$id);
			if($source2==null){
				Session::forget($this->_ssNameUser);   
				return redirect()->route("frontend.index.candidateLogin");    
			}else{
				$data=$source2->toArray();
				if((int)@$data['candidate_id'] != (int)@$arrUser['id']){
					Session::forget($this->_ssNameUser);   
					return redirect()->route("frontend.index.candidateLogin");    
				}
			}			
			$source_profile_job=ProfileJobModel::whereRaw('profile_id = ?',[(int)@$id])->select('job_id')->get()->toArray();
			$source_profile_place=ProfilePlaceModel::whereRaw('profile_id = ?',[(int)@$id])->select('province_id')->get()->toArray();
			$source_job_id=array();
			$source_province_id=array();
			if(count($source_profile_job) > 0){
				foreach ($source_profile_job as $key => $value) {
					$source_job_id[]=$value['job_id'];
				}
			}
			if(count($source_profile_place) > 0){
				foreach ($source_profile_place as $key => $value) {
					$source_province_id[]=$value['province_id'];
				}
			}      
			$data['job_id']=$source_job_id;
			$data['province_id']=$source_province_id;
			$data['salary']=convertToTextPrice($data['salary']);      
		}
		if($request->isMethod('post')){
			$data             	=	@$request->all();      
			$fullname         	=   trim(@$request->fullname);
			$literacy_id      	=   trim(@$request->literacy_id);
			$experience_id    	=   trim(@$request->experience_id);
			$rank_present_id  	=   trim(@$request->rank_present_id);
			$rank_offered_id  	=   trim(@$request->rank_offered_id);
			$job_id           	=   @$request->job_id;
			$salary 			= 	trim(@$request->salary);
			$salary            = (int)(str_replace('.', '',@$salary)) ;
			$province_id      	=   @$request->province_id;			
			$status_search 			=	trim(@$request->status_search);
			if(mb_strlen(@$fullname) < 15){
				$msg["fullname"] = 'Tiêu đề phải từ 15 ký tự trở lên';    
				$data['fullname']='';        
				$checked = 0;
			}
			if((int)@$literacy_id == 0){
				$msg["literacy_id"] = 'Vui lòng chọn trình độ học vấn';    
				$data['literacy_id']='';        
				$checked = 0;   
			}
			if((int)@$experience_id == 0){
				$msg["experience_id"] = 'Vui lòng chọn số năm kinh nghiệm';    
				$data['experience_id']='';        
				$checked = 0;    
			}
			if((int)@$rank_present_id == 0){
				$msg["rank_present_id"] = 'Vui lòng chọn cấp bậc hiện tại';    
				$data['rank_present_id']='';        
				$checked = 0;   
			}
			if((int)@$rank_offered_id == 0){
				$msg["rank_offered_id"] = 'Vui lòng chọn cấp bậc mong muốn';    
				$data['rank_offered_id']='';        
				$checked = 0;   
			}
			if(count(@$job_id) == 0){
				$msg["job_id"] = 'Vui lòng chọn ngành nghề mong muốn';    
				$data['job_id']='';        
				$checked = 0;      
			}else{
				if((int)@$job_id[0]==0){
					$msg["job_id"] = 'Vui lòng chọn ngành nghề mong muốn';    
					$data['job_id']='';        
					$checked = 0;      
				}
			}
			if((int)@$salary == 0){
				$msg["salary"] = 'Vui lòng ghi mức lương mong muốn';    
				$data['salary']='';        
				$checked = 0;   
			}
			if(count(@$province_id) == 0){
				$msg["province_id"] = 'Vui lòng chọn nơi làm việc mong muốn';    
				$data['province_id']='';        
				$checked = 0;      
			}else{
				if((int)@$province_id[0]==0){
					$msg["province_id"] = 'Vui lòng chọn nơi làm việc mong muốn';    
					$data['province_id']='';        
					$checked = 0;      
				}
			}
			if((int)@$status_search == -1){
				$msg["status_search"] = 'Vui lòng chọn trạng thái nhà tuyển dụng cho phép tìm kiếm thông tin hay không';    
				$data['status_search']='';        
				$checked = 0;      
			} 
			if($checked==1){
				$item=null;
				switch ($task) {
					case 'add':            
					$item                   = new ProfileModel;
					$item->created_at=date("Y-m-d H:i:s");
					$item->updated_at=date("Y-m-d H:i:s");   
					break;
					case 'edit':
					$item                   = ProfileModel::find((int)@$id);
					$item->updated_at=date("Y-m-d H:i:s");   
					break;          
				}              
				$item->fullname           = @$fullname;
				/* begin save alias */
				$alias=str_slug(@$fullname,'-');				
				$data_employer=array();        
				switch ($task) {
					case 'add':
					$data_employer=ProfileModel::whereRaw("trim(lower(alias)) = ?",[trim(mb_strtolower(@$alias,'UTF-8'))])->get()->toArray();        
					break;
					case 'edit':
					$data_employer=ProfileModel::whereRaw("trim(lower(alias)) = ? and id != ?",[trim(mb_strtolower(@$alias,'UTF-8')),@$id])->get()->toArray();                    
					break;
				}        
				if (count(@$data_employer) > 0) {
					$code_alias=rand(1,999999);
					$alias=$alias.'-'.$code_alias;
				}        				
				$item->alias=@$alias;
				/* end save alias */
				$item->alias            = @$alias;
				$item->literacy_id = @$literacy_id;
				$item->experience_id = @$experience_id;
				$item->rank_present_id = @$rank_present_id;
				$item->rank_offered_id=@$rank_offered_id;
				$item->salary=@$salary;
				$item->candidate_id      = (int)@$arrUser['id'];        				
				$item->status_search           = (int)@$status_search;				
				$item->status           = 0;				
				$item->save();    
				$source_profile_job=ProfileJobModel::whereRaw('profile_id = ?',[(int)@$item->id])->select('job_id')->get()->toArray();
				$source_profile_place=ProfilePlaceModel::whereRaw('profile_id = ?',[(int)@$item->id])->select('province_id')->get()->toArray();
				$source_job_id=array();
				$source_province_id=array();
				if(count($source_profile_job) > 0){
					foreach ($source_profile_job as $key => $value) {
						$source_job_id[]=$value['job_id'];
					}
				}
				if(count($source_profile_place) > 0){
					foreach ($source_profile_place as $key => $value) {
						$source_province_id[]=$value['province_id'];
					}
				}  
				sort($source_job_id);
				sort($source_province_id);
				sort($job_id);
				sort($province_id);
				$compare_job=0;
				$compare_province=0;
				if($source_job_id == $job_id){
					$compare_job=1;       
				}    
				if($source_province_id == $province_id){
					$compare_province=1;       
				}
				if($compare_job == 0){
					ProfileJobModel::whereRaw("profile_id = ?",[(int)@$item->id])->delete();   
					foreach ($job_id as $key => $value) {
						$item2=new ProfileJobModel;
						$item2->profile_id = (int)@$item->id;
						$item2->job_id         = (int)@$value;
						$item2->save();
					}
				}
				if($compare_province == 0){
					ProfilePlaceModel::whereRaw("profile_id = ?",[(int)@$item->id])->delete();  
					foreach ($province_id as $key => $value) {
						$item3=new ProfilePlaceModel;
						$item3->profile_id = (int)@$item->id;
						$item3->province_id         = (int)@$value;
						$item3->save();
					}
				} 
				$msg['success']='Bước tạo thông tin sơ bộ thành công'; 
				$info = array(
					"checked"       => $checked,          
					'msg'       => $msg,                    
				);  
				switch ($task) {
					case 'add':            
					return redirect()->route("frontend.index.getGroupProfile",[$item->id])->with(["message"=>$info]);
					break;
					case 'edit':            
					$msg['success']='<span>Cập nhật hồ sơ thành công</span>';
					break;          
				}  				
			}
		}
		return view('frontend.profile',compact('data','msg','checked','task'));     
	}
	public function viewProfileCabinet(Request $request){
		$checked=1;
		$msg=array();        
		$data=array();       
		$arrUser=array();    
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}
		$totalItems=0;
		$totalItemsPerPage=20;
		$pageRange=0;      
		$currentPage=1;  
		$pagination ='';            
		$q='';
		$query=DB::table('profile')   ;     
		$query->where('profile.candidate_id',(int)@$arrUser['id']);    
		if(!empty(@$request->q)){
			$q=@$request->q;
			$query->where('profile.fullname','like', '%'.trim(@$q).'%');
		}
		$data=$query->select('profile.id')
		->groupBy('profile.id')                
		->get()->toArray();
		$data=convertToArray($data);
		$totalItems=count($data);    
		$pageRange=$this->_pageRange;
		if(isset($request->filter_page)){
			if(!empty(@$request->filter_page)){
				$currentPage=@$request->filter_page;
			}
		}          
		$arrPagination=array(
			"totalItems"=>$totalItems,
			"totalItemsPerPage"=>$totalItemsPerPage,
			"pageRange"=>$pageRange,
			"currentPage"=>$currentPage   
		);           
		$pagination=new PaginationModel($arrPagination);
		$position   = ((int)@$currentPage-1)*$totalItemsPerPage;     

		$data=$query->select('profile.id','profile.fullname','profile.status_search','profile.status','profile.created_at')
		->groupBy('profile.id','profile.fullname','profile.status_search','profile.status','profile.created_at')
		->orderBy('profile.id', 'desc')
		->skip($position)
		->take($totalItemsPerPage)
		->get()->toArray();   
		$data=convertToArray($data);    
		$data=profile2Converter($data);
		return view('frontend.cabinet-profile',compact('data','msg','checked',"pagination",'q'));     
	}
	public function changeProfileSearchStatus(Request $request){
		$id             =       (int)$request->id;  
		$status         =       (int)$request->status;

		$item=ProfileModel::find($id);
		$trangThai=0;
		if($status==0){
			$trangThai=1;
		}
		else{
			$trangThai=0;
		}
		$item->status_search=$status;
		$item->save();
		$result = array(
			'id'      => $id, 
			'status'  => $status, 
			'link'    => 'javascript:changeStatus('.$id.','.$trangThai.');'
		);
		return $result;   
	}      
	public function deleteProfile($id){   
		$info                 =   array();
		$checked              =   1;                           
		$msg                =   array();
		$arrUser=array();    
		$data=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}else{
			$source2=ProfileModel::find((int)@$id);
			if($source2 == null){
				$checked=0;
				$msg['errorid']='Không đúng id';
			}else{
				$data=$source2->toArray();
				if((int)@$data['candidate_id'] != (int)@$arrUser['id']){
					$checked=0;
					$msg['errorid']='Sai hồ sơ';
				}
			}
		}		
		$data_profile_applied=ProfileAppliedModel::whereRaw('profile_id = ?',[(int)@$id])->select()->get()->toArray();		
		if(count(@$data_profile_applied) > 0){
			$msg['error']='Hồ sơ đã được nộp không thể xóa';
			$checked=0;
		}
		if($checked == 1){			
			ProfileModel::find((int)@$id)->delete();          
			ProfileJobModel::whereRaw('profile_id = ?',[@$id])->delete();  
			ProfilePlaceModel::whereRaw('profile_id = ?',[@$id])->delete();
			$msg['success']='Xóa thành công';
		}  
		$info = array(
			"checked"       => $checked,          
			'msg'       => $msg,                    
		);      
		return redirect()->route('frontend.index.viewProfileCabinet')->with(["message"=>$info]);     
	}
	public function getGroupProfile($id){
		$arrUser=array();    
		$data=array();
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}else{
			$source2=ProfileModel::find((int)@$id);
			if($source2==null){
				Session::forget($this->_ssNameUser);   
				return redirect()->route("frontend.index.candidateLogin");    
			}else{
				$data=$source2->toArray();
				if((int)@$data['candidate_id'] != (int)@$arrUser['id']){
					Session::forget($this->_ssNameUser);   
					return redirect()->route("frontend.index.candidateLogin");    
				}
			}	
		}
		return view('frontend.group-profile',compact('id'));     
	}
	public function saveFileAttached(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		$id 					=	trim(@$request->id); 
		$source_file           	=   null;
    	if(isset($_FILES["file_attached"])){
    		$source_file         =   $_FILES["file_attached"];
    	}        	         	 
    	if($source_file == null){
    		$checked=0;
	    	$msg['notexistfileattached']='Vui lòng cập nhật file đính kèm';
    	}else{
    		$pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.doc|.docx|.pdf)$#"; 
    		if(preg_match($pattern, $source_file['name'])==false){
	    		$checked=0;
	    		$msg['notfileattached']='Đính kèm file lỗi . File đính kèm phải ở dạng word , pdf';
    		}
    	}    	    
		if ($checked == 1){
			$attachment_name='';
    		if($source_file != null){                                                
    			$attachment_name=uploadAttachedFile($source_file['name'],$source_file['tmp_name']);
    		}    		
    		$item				=	ProfileModel::find((int)@$id);      		    		
    		$item->file_attached=null;
    		if(!empty($attachment_name))  {
    			$item->file_attached=$attachment_name;                                                
    		}  
    		$item->save();  
    		$msg['success']='Lưu file đính kèm thành công'; 
		}  
		$info = array(
    		"checked"       => $checked,       		
    		'msg'       	=> $msg,                
    		"id"            => (int)@$id
    	);                       
    	return $info;   		
	}
	public function saveFileApplied(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		$recruitment_id 		=	trim(@$request->recruitment_id); 
		$candidate_id 			=	trim(@$request->candidate_id); 
		$source_file           	=   null;
		$link_edit		='';
    	if(isset($_FILES["file_attached"])){
    		$source_file         =   $_FILES["file_attached"];
    	}        	         	 
    	if($source_file == null){
    		$checked=0;
	    	$msg['notexistfileattached']='Vui lòng cập nhật file đính kèm';
    	}else{
    		$pattern = "#^([a-zA-Z0-9\s_\\.\-:])+(.doc|.docx|.pdf)$#"; 
    		if(preg_match($pattern, $source_file['name'])==false){
	    		$checked=0;
	    		$msg['notfileattached']='Đính kèm file lỗi . File đính kèm phải ở dạng word , pdf';
    		}
    	}  
    	$source_profile_applied=ProfileAppliedModel::whereRaw('recruitment_id = ? and candidate_id = ?',[(int)@$recruitment_id,(int)@$candidate_id])->select('id')->get()->toArray();
    	if(count(@$source_profile_applied) > 0){
    		$msg['error']='Ứng viên đã ứng tuyển vị trí này';
    		$checked=0;	
    	}  	    
		if ($checked == 1){
			$attachment_name='';
    		if($source_file != null){                                                
    			$attachment_name=uploadAttachedFile($source_file['name'],$source_file['tmp_name']);
    		}    		
    		$item				= new	ProfileAppliedModel;
    		$item->recruitment_id=(int)@$recruitment_id;
    		$item->candidate_id=(int)@$candidate_id;    		
    		$item->file_attached=null;
    		if(!empty($attachment_name))  {
    			$item->file_attached=$attachment_name;                                                
    		}  
    		$item->save();  
    		$msg['success']='Ứng tuyển vị trí thành công'; 
    		$link_edit=route('frontend.index.getHome');
		}  
		$info = array(
    		"checked"       => $checked,       		
    		'msg'       	=> $msg,
    		'link_edit'		=>	$link_edit
    	);                       
    	return $info;   		
	}
	public function getProfileDetail(Request $request,$id){
		$info                 =   array();
		$checked=1;
		$msg=array();          
		$arrUser=array();    
		$data=array();					
		if(Session::has($this->_ssNameUser)){
			$arrUser=Session::get($this->_ssNameUser);
		}   
		if(count($arrUser)==0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}      
		$email=@$arrUser['email'];   
		$source=CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
		if(count($source) == 0){
			return redirect()->route("frontend.index.candidateLogin"); 
		}else{
			$source2=ProfileModel::find((int)@$id);
			if($source2==null){
				Session::forget($this->_ssNameUser);   
				return redirect()->route("frontend.index.candidateLogin");    
			}else{
				$data=$source2->toArray();
				if((int)@$data['candidate_id'] != (int)@$arrUser['id']){
					Session::forget($this->_ssNameUser);   
					return redirect()->route("frontend.index.candidateLogin");    
				}
			}	
		}						
		return view('frontend.profile-detail',compact('data','msg','checked','id'));     
	}
	public function updateCareerGoal(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		$id             		=   (int)@$request->id;  
		$career_goal    		=  	trim(@$request->career_goal);
		if(mb_strlen(@$career_goal) < 11){
			$checked=0;
			$msg['career_goal']='Vui lòng nhập mục tiêu trên 10 ký tự';
		}
		if($checked == 1){
			$item=ProfileModel::find(@$id);		
			$item->career_goal=$career_goal;
			$item->save();
			$msg['success']='Cập nhật mục tiêu nghề nghiệp thành công';
		}		
		$info = array(
    		"checked"       => $checked,       		
    		'msg'       	=> $msg,                
    		"id"            => (int)@$id,
    		"career_goal"	=> @$career_goal
    	);                       
    	return $info;   
	}
	public function deleteExperienceJob(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();

		$data_profile_experience=array();

		$id             		=   (int)@$request->id;  
		$profile_experience_id	=	(int)@$request->profile_experience_id;

		$source_profile_experience=ProfileExperienceModel::whereRaw('id = ? and profile_id = ?',[@$profile_experience_id,@$id])->select()->get()->toArray();
		if(count(@$source_profile_experience) == 0){
			$checked = 0;
			$msg['wrongid']='Sai địa chỉ id';
		}
		if((int)@$checked == 1){
			ProfileExperienceModel::find((int)@$profile_experience_id)->delete();
			$msg['success']='Xóa kinh nghiệm làm việc thành công';
			$source_profile_experience=ProfileExperienceModel::whereRaw('profile_id = ?',[@$id])->select()->get()->toArray();		
			if(count($source_profile_experience) > 0){
				foreach ($source_profile_experience as $key => $value) {
					$row=array();
					$row['id']=$value['id'];
					$row['company_name']=$value['company_name'];
					$row['person_title']=$value['person_title'];
					$row['time_from']=$value['month_from'] . '/' . $value['year_from'];				
					$row['time_to']=$value['month_to'] . '/' .$value['year_to'];				
					$salary=convertToTextPrice($value['salary']);
					$currency='';
					switch ($value['currency']) {
						case 'vnd':			
						$currency='VNĐ';	
						break;
						case 'usd':
						$currency='USD';							
						break;
					}				
					$row['salary']=@$salary.' '.@$currency.'/tháng';
					$row['job_description']=@$value['job_description'];
					$row['achievement']=@$value['achievement'];
					$row['profile_id']=(int)@$value['profile_id'];
					$data_profile_experience[]=@$row;
				}
			}		
		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			'data_profile_experience' => $data_profile_experience
		);                       
		return $info;
	}
	public function saveExperienceJob(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		
		$item 					=	null;
		$data_profile_experience=array();

		$id             		=   (int)@$request->id;  
		$company_name = trim(@$request->company_name);
		$person_title =trim(@$request->person_title);
		$month_from =trim(@$request->month_from) ;
		$year_from =trim(@$request->year_from);
		$month_to =trim(@$request->month_to) ;
		$year_to = trim(@$request->year_to);
		$currency =trim(@$request->currency);
		$salary 			= 	trim(@$request->salary);
		$salary            = (int)(str_replace('.', '',@$salary)) ;
		$job_description =trim(@$request->job_description);
		$achievement =trim(@$request->achievement) ;		

		if(mb_strlen(@$company_name) < 6){
			$checked=0;
			$msg['company_name']='Vui lòng nhập tên công ty trên 6 ký tự';
		}
		if(mb_strlen(@$person_title) < 6){
			$checked=0;
			$msg['person_title']='Vui lòng nhập chức danh trên 6 ký tự';
		}
		if((int)@$month_from == 0){
			$checked=0;
			$msg['month_from']='Vui chọn tháng';
		}
		if((int)@$year_from == 0){
			$checked=0;
			$msg['year_from']='Vui chọn năm';
		}
		if((int)@$month_to == 0){
			$checked=0;
			$msg['month_to']='Vui chọn tháng';
		}
		if((int)@$year_to == 0){
			$checked=0;
			$msg['year_to']='Vui chọn năm';
		}
		if(empty(@$currency)){
			$checked=0;
			$msg['currency']='Vui lòng chọn đơn vị tiền tệ';
		}
		if(empty(@$job_description)){
			$checked=0;
			$msg['job_description']='Vui lòng nhập mô tả công việc';
		}					
		if($checked == 1){
			$item=new ProfileExperienceModel;		
			$item->company_name=@$company_name;
			$item->person_title=@$person_title;
			$item->month_from=(int)@$month_from;			
			$item->year_from=(int)@$year_from;
			$item->month_to=(int)@$month_to;
			$item->year_to=(int)@$year_to;
			$item->currency=@$currency;
			$item->salary=(int)@$salary;
			$item->job_description=@$job_description;
			$item->achievement=@$achievement;
			$item->profile_id=@$id;
			$item->created_at=date("Y-m-d H:i:s",time());
			$item->updated_at=date("Y-m-d H:i:s",time());   
			$item->save();
			$msg['success']='Cập nhật kinh nghiệm làm việc thành công';
		}		
		$source_profile_experience=ProfileExperienceModel::whereRaw('profile_id = ?',[@$id])->select()->get()->toArray();		
		if(count($source_profile_experience) > 0){
			foreach ($source_profile_experience as $key => $value) {
				$row=array();
				$row['id']=$value['id'];
				$row['company_name']=$value['company_name'];
				$row['person_title']=$value['person_title'];
				$row['time_from']=$value['month_from'] . '/' . $value['year_from'];				
				$row['time_to']=$value['month_to'] . '/' .$value['year_to'];				
				$salary=convertToTextPrice($value['salary']);
				$currency='';
				switch ($value['currency']) {
					case 'vnd':			
					$currency='VNĐ';	
					break;
					case 'usd':
					$currency='USD';							
					break;
				}				
				$row['salary']=@$salary.' '.@$currency.'/tháng';
				$row['job_description']=@$value['job_description'];
				$row['achievement']=@$value['achievement'];
				$row['profile_id']=(int)@$value['profile_id'];
				$data_profile_experience[]=@$row;
			}
		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			"id"            => (int)@$id,
			'data_profile_experience' => $data_profile_experience
		);                       
		return $info;   
	}
	public function saveGraduation(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		
		$item 					=	null;
		$data_profile_graduation=array();

		$id             		=   (int)@$request->id;  
		$literacy_id = (int)trim(@$request->literacy_id);
		$training_unit = trim(@$request->training_unit);
		$graduation_year_from = (int)trim(@$request->graduation_year_from) ;
		$graduation_year_to = (int)trim(@$request->graduation_year_to);
		$department = trim(@$request->department) ;
		$graduation_id = (int)trim(@$request->graduation_id);
		$image_file           =   null;
    	if(isset($_FILES["image"])){
    		$image_file         =   $_FILES["image"];
    	}  
		if(@$literacy_id == 0){
			$checked=0;
			$msg['literacy_id']='Vui lòng chọn trình độ học vấn';
		}
		if(mb_strlen(@$training_unit) < 6){
			$checked=0;
			$msg['training_unit']='Vui lòng chọn đơn vị đào tạo';
		}		
		if(@$graduation_year_from == 0){
			$checked=0;
			$msg['graduation_year_from']='Vui lòng chọn năm từ';
		}		
		if(@$graduation_year_to == 0){
			$checked=0;
			$msg['graduation_year_to']='Vui chòng chọn năm đến';
		}
		if(empty(@$department)){
			$checked=0;
			$msg['department']='Vui lòng chọn chuyên ngành';
		}
		if(@$graduation_id==0){
			$checked=0;
			$msg['graduation_id']='Vui lòng chọn loại tốt nghiệp';
		}					
		if($checked == 1){
			$image_name='';
    		if($image_file != null){                                                
    			$image_name=uploadImage($image_file['name'],$image_file['tmp_name'],0,0);
    		}
			$item=new ProfileGraduationModel;		
			$item->literacy_id=@$literacy_id;
			$item->training_unit=@$training_unit;			
			$item->year_from=@$graduation_year_from;			
			$item->year_to=@$graduation_year_to;
			$item->department=@$department;
			$item->graduation_id=@$graduation_id;	
			if(!empty($image_name)){
    			$item->degree    =   trim($image_name) ;  
    		}			
			$item->profile_id=@$id;
			$item->created_at=date("Y-m-d H:i:s",time());
			$item->updated_at=date("Y-m-d H:i:s",time());   
			$item->save();
			$msg['success']='Cập nhật trình độ bằng cấp thành công';
		}		
		$source_profile_graduation=DB::table('profile_graduation')
									->join('literacy','profile_graduation.literacy_id','=','literacy.id')
									->join('graduation','profile_graduation.graduation_id','=','graduation.id')
									->where('profile_graduation.profile_id',(int)@$id)
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
		$source_profile_graduation=convertToArray($source_profile_graduation);
		if(count($source_profile_graduation) > 0){
			foreach ($source_profile_graduation as $key => $value) {
				$row=array();
				$row['id']=$value['id'];
				$row['literacy_name']=$value['literacy_name'];
				$row['training_unit']=$value['training_unit'];
				$row['year_from']=$value['year_from'];				
				$row['year_to']=$value['year_to'];				
				$row['department']=$value['department'];
				$row['graduation_name']=$value['graduation_name'];
				$row['degree']=asset('upload/'.$value['degree']) ;				
				$data_profile_graduation[]=@$row;
			}
		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			"id"            => (int)@$id,
			'data_profile_graduation' => $data_profile_graduation
		);                       
		return $info;   
	}
	public function deleteGraduation(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();

		$data_profile_graduation=array();

		$id             		=   (int)@$request->id;  
		$profile_graduation_id	=	(int)@$request->profile_graduation_id;

		$source_profile_graduation=ProfileGraduationModel::whereRaw('id = ? and profile_id = ?',[@$profile_graduation_id,@$id])->select()->get()->toArray();		
		if(count(@$source_profile_graduation) == 0){
			$checked = 0;
			$msg['wrongid']='Sai địa chỉ id';
		}
		if((int)@$checked == 1){
			ProfileGraduationModel::find((int)@$profile_graduation_id)->delete();
			$msg['success']='Xóa trình độ bằng cấp thành công';
			$source_profile_graduation=DB::table('profile_graduation')
			->join('literacy','profile_graduation.literacy_id','=','literacy.id')
			->join('graduation','profile_graduation.graduation_id','=','graduation.id')
			->where('profile_graduation.profile_id',(int)@$id)
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
			$source_profile_graduation=convertToArray($source_profile_graduation);
			if(count($source_profile_graduation) > 0){
				foreach ($source_profile_graduation as $key => $value) {
					$row=array();
					$row['id']=$value['id'];
					$row['literacy_name']=$value['literacy_name'];
					$row['training_unit']=$value['training_unit'];
					$row['year_from']=$value['year_from'];				
					$row['year_to']=$value['year_to'];				
					$row['department']=$value['department'];
					$row['graduation_name']=$value['graduation_name'];
					$row['degree']=asset('/upload/'.$value['degree']) ;				
					$data_profile_graduation[]=@$row;
				}
			}		
		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			'data_profile_graduation' => $data_profile_graduation
		);                       
		return $info;
	}
	public function saveLanguage(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		
		$item 					=	null;
		$data_profile_language=array();

		$id             		=   (int)@$request->id;  
		$language_id = (int)trim(@$request->language_id);		
		$language_level_id = (int)trim(@$request->language_level_id) ;
		$listening = (int)trim(@$request->listening);		
		$speaking = (int)trim(@$request->speaking);		
		$reading = (int)trim(@$request->reading);		
		$writing = (int)trim(@$request->writing);	

		if(@$language_id == 0){
			$checked=0;
			$msg['language_id']='Vui lòng chọn ngoại ngữ';
		}				
		if(@$language_level_id == 0){
			$checked=0;
			$msg['language_level_id']='Vui lòng chọn trình độ';
		}		
		if(@$listening == 0){
			$checked=0;
			$msg['listening']='Vui lòng chọn nghe';
		}					
		if(@$speaking == 0){
			$checked=0;
			$msg['speaking']='Vui lòng chọn nói';
		}				
		if(@$reading == 0){
			$checked=0;
			$msg['reading']='Vui lòng chọn đọc';
		}				
		if(@$writing == 0){
			$checked=0;
			$msg['writing']='Vui lòng chọn viết';
		}		
		if($checked == 1){			
			$item=new ProfileLanguageModel;		
			$item->language_id=@$language_id;			
			$item->language_level_id=@$language_level_id;								
			$item->listening=@$listening;
			$item->speaking=@$speaking;
			$item->reading=@$reading;
			$item->writing=@$writing;
			$item->profile_id=@$id;
			$item->created_at=date("Y-m-d H:i:s",time());
			$item->updated_at=date("Y-m-d H:i:s",time());   
			$item->save();
			$msg['success']='Cập nhật trình độ ngoại ngữ thành công';
		}		
		$source_profile_language=DB::table('profile_language')
				->join('language','profile_language.language_id','=','language.id')
				->join('language_level','profile_language.language_level_id','=','language_level.id')
				->join('classification as l','profile_language.listening','=','l.id')
				->join('classification as s','profile_language.speaking','=','s.id')
				->join('classification as r','profile_language.reading','=','r.id')
				->join('classification as w','profile_language.writing','=','w.id')
				->where('profile_language.profile_id',(int)@$id)
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
		$source_profile_language=convertToArray($source_profile_language);
		if(count($source_profile_language) > 0){
			foreach ($source_profile_language as $key => $value) {
				$row=array();
				$row['id']=$value['id'];
				$row['language_name']=$value['language_name'];
				$row['language_level_name']=$value['language_level_name'];				
				$row['listening']=$value['listening'];
				$row['speaking']=$value['speaking'];
				$row['reading']=$value['reading'];
				$row['writing']=$value['writing'];
				$data_profile_language[]=@$row;
			}
		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			"id"            => (int)@$id,
			'data_profile_language' => $data_profile_language
		);                       
		return $info;   
	}
	public function deleteLanguage(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();

		$data_profile_language=array();

		$id             		=   (int)@$request->id;  
		$profile_language_id	=	(int)@$request->profile_language_id;

		$source_profile_language=ProfileLanguageModel::whereRaw('id = ? and profile_id = ?',[@$profile_language_id,@$id])->select()->get()->toArray();		
		if(count(@$source_profile_language) == 0){
			$checked = 0;
			$msg['wrongid']='Sai địa chỉ id';
		}
		if((int)@$checked == 1){
			ProfileLanguageModel::find((int)@$profile_language_id)->delete();
			$msg['success']='Xóa trình độ ngoại ngữ thành công';
			
			$source_profile_language=DB::table('profile_language')
			->join('language','profile_language.language_id','=','language.id')
			->join('language_level','profile_language.language_level_id','=','language_level.id')
			->join('classification as l','profile_language.listening','=','l.id')
			->join('classification as s','profile_language.speaking','=','s.id')
			->join('classification as r','profile_language.reading','=','r.id')
			->join('classification as w','profile_language.writing','=','w.id')
			->where('profile_language.profile_id',(int)@$id)
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
			$source_profile_language=convertToArray($source_profile_language);
			if(count($source_profile_language) > 0){
				foreach ($source_profile_language as $key => $value) {
					$row=array();
					$row['id']=$value['id'];
					$row['language_name']=$value['language_name'];
					$row['language_level_name']=$value['language_level_name'];				
					$row['listening']=$value['listening'];
					$row['speaking']=$value['speaking'];
					$row['reading']=$value['reading'];
					$row['writing']=$value['writing'];
					$data_profile_language[]=@$row;
				}
			}		

		}		
		$info = array(
			"checked"       => $checked,       		
			'msg'       	=> $msg,                
			'data_profile_language' => $data_profile_language
		);                       
		return $info;
	}
	public function saveOffice(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		$data_profile=array();
		$id             		=   (int)@$request->id;  
		$ms_word = (int)trim(@$request->ms_word);		
		$ms_excel = (int)trim(@$request->ms_excel);		
		$ms_power_point = (int)trim(@$request->ms_power_point);		
		$ms_outlook = (int)trim(@$request->ms_outlook);		
		$other_software = trim(@$request->other_software);		
		$medal = trim(@$request->medal);				
		if(@$ms_word == 0){
			$checked=0;
			$msg['ms_word']='Vui lòng chọn kỹ năng sử dụng MS Word';
		}	
		if(@$ms_excel == 0){
			$checked=0;
			$msg['ms_excel']='Vui lòng chọn kỹ năng sử dụng MS Excel';
		}	
		if(@$ms_power_point == 0){
			$checked=0;
			$msg['ms_power_point']='Vui lòng chọn kỹ năng sử dụng MS Power Point';
		}	
		if(@$ms_outlook == 0){
			$checked=0;
			$msg['ms_outlook']='Vui lòng chọn kỹ năng sử dụng MS Outlook';
		}			
		if($checked == 1){
			$item=ProfileModel::find(@$id);		
			$item->ms_word=$ms_word;
			$item->ms_excel=$ms_excel;
			$item->ms_power_point=$ms_power_point;
			$item->ms_outlook=$ms_outlook;
			$item->other_software=$other_software;
			$item->medal=$medal;
			$item->save();
			$msg['success']='Cập nhật thành công';
			$query=DB::table('profile')
			->join('classification as w','profile.ms_word','=','w.id')
			->join('classification as e','profile.ms_excel','=','e.id')
			->join('classification as p','profile.ms_power_point','=','p.id')
			->join('classification as o','profile.ms_outlook','=','o.id')
			;
			$query->where('profile.id',@$id);
			$source_profile=$query->select(				
				'w.fullname as ms_word_level'
				,'e.fullname as ms_excel_level'
				,'p.fullname as ms_power_point_level'
				,'o.fullname as ms_outlook_level'
				,'profile.other_software'
				,'profile.medal'
				)
			->groupBy(
				'w.fullname'
				,'e.fullname'
				,'p.fullname'
				,'o.fullname'
				,'profile.other_software'
				,'profile.medal'
				)	
			->get()->toArray();	
			if(count($source_profile) > 0){
				$source_profile2=convertToArray($source_profile);	
				$data_profile=$source_profile2[0];
			}
		}		

		$info = array(
			"checked"       		=> $checked,       		
			'msg'       			=> $msg,                
			"id"            		=> (int)@$id,
			"ms_word_level"			=> @$data_profile['ms_word_level'],
			"ms_excel_level"		=> @$data_profile['ms_excel_level'],
			"ms_power_point_level"	=> @$data_profile['ms_power_point_level'],
			"ms_outlook_level"		=> @$data_profile['ms_outlook_level'],
			"other_software"		=> @$data_profile['other_software'],
			"medal"					=> @$data_profile['medal']
		);                       
		return $info;   
	}
	public function saveSkill(Request $request){
		$info                 	=   array();
		$checked              	=   1;                           
		$msg                	=   array();
		$data_profile_skill=array();
		$item=null;
		$id             		=   (int)@$request->id;  		
		$skill_id = @$request->source_skill_id;		
		$hobby = trim(@$request->hobby);		
		$talent = trim(@$request->talent);			
		if($skill_id == null){
			$checked=0;
			$msg['skill_id']='Vui lòng chọn ít nhất 1 sở trường';
		}		
		if(mb_strlen(@$hobby) < 6){
			$checked=0;
			$msg['hobby']='Vui lòng nhập sở thích';
		}
		if(mb_strlen(@$talent) < 6){
			$checked=0;
			$msg['talent']='Vui lòng nhập kỹ năng đặc biệt';
		}					
		if($checked == 1){
			$item=ProfileModel::find(@$id);					
			$item->hobby=$hobby;
			$item->talent=$talent;
			$item->save();
			if($skill_id != null){
				$source_selected=explode(',', $skill_id);
				$source_skill_id=ProfileSkillModel::whereRaw("profile_id = ?",[(int)@$item->id])->select("skill_id")->get()->toArray();
				$source_skill_id2=array();
				foreach ($source_skill_id as $key => $value) {
					$source_skill_id2[]=$value['skill_id'];
				}
				sort($source_selected);
				sort($source_skill_id2);
				$compare=0;
				if($source_selected == $source_skill_id2){
					$compare=1;
				}
				if((int)@$compare == 0){
					ProfileSkillModel::whereRaw("profile_id = ?",[(int)@$item->id])->delete();
					foreach ($source_selected as $key => $value) {
						$item2=new ProfileSkillModel;
						$item2->skill_id=(int)@$value;
						$item2->profile_id=(int)@$item->id;
						$item2->save();
					}
				}
			}
			$msg['success']='Cập nhật kỹ năng sở trường thành công';
			$data_profile_skill= DB::table('skill')
								->join('profile_skill','skill.id','=','profile_skill.skill_id')
								->where('profile_skill.profile_id',@$id)
								->select('skill.id','skill.fullname')
								->groupBy('skill.id','skill.fullname')
								->orderBy('skill.id','asc')
								->get()
								->toArray();
		}		

		$info = array(
			"checked"       		=> $checked,       		
			'msg'       			=> $msg,                
			"id"            		=> (int)@$id,
			"hobby"			=> @$item->hobby,
			"talent"		=> @$item->talent,	
			"source_profile_skill"=>@$data_profile_skill		
		);                       
		return $info;   
	}
}







