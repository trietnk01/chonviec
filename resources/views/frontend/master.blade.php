<?php 
$setting=getSettingSystem();
$seo=getSeo();
$telephone=$setting['telephone']['field_value'];
$email_to=$setting['email_to']['field_value'];   
$company=$setting['contacted_person']['field_value'];

$seo_title="";
if(!empty(@$title)){
    $seo_title=@$title;
}else{  
    $seo_title=@$seo["title"];
}

$seo_meta_keyword="";
if(!empty(@$meta_keyword)){
    $seo_meta_keyword=@$meta_keyword;
}else{
    $seo_meta_keyword=@$seo["meta_keyword"];
}

$seo_meta_description="";
if(!empty(@$meta_description)){
    $seo_meta_description=@$meta_description;
}else{
    $seo_meta_description=@$seo["meta_description"] . ' ' . @$telephone;
}

$seo_google_analytics=@$seo["google_analytics"];
$seo_author=@$seo["author"];
$seo_copyright=@$seo["copyright"];
$seo_generator="Mã nguồn mở phát triển bởi tichtacso.com";
$seo_google_site_verification=@$seo["google_site_verification"];
$seo_page_url=url('/');
$seo_favicon=asset('upload/'.@$seo['favicon']);
$seo_logo_frontend=asset('upload/'.@$seo['logo_frontend']);
$seo_alias="";
if(isset($alias)){
    $seo_alias=$alias;
}

$og_image=asset('upload/logo.png');
if(count(@$item) > 0){
    $image=@$item['image'];
    if($image != null){
        $og_image=asset('upload/'.@$image)   ;  
    }
}
$canonical='';
if(!empty(@$alias)){
    $canonical=@$alias.'.html';
}

?>
<!DOCTYPE html>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head itemscope itemtype="http://schema.org/WebSite">  
    <!--begin meta SEO-->     
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  
    <title><?php echo @$seo_title; ?></title>
    <meta name="keywords" content="<?php echo @$seo_meta_keyword; ?>">
    <meta name="description" content="<?php echo @$seo_meta_description; ?>">    
    <meta name="author" content="<?php echo @$seo_author; ?>">
    <meta name="copyright" content="<?php echo @$seo_copyright; ?>">
    <meta name="robots" content="index, archive, follow, noodp">
    <meta name="googlebot" content="index,archive,follow,noodp">
    <meta name="msnbot" content="all,index,follow">
    <meta name="generator" content="<?php echo @$seo_generator; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="<?php echo @$seo_google_site_verification; ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">    
    <meta itemprop="url" content="<?php echo url(@$canonical); ?>"/>
    <link rel="alternate" href="<?php echo url(@$canonical); ?>" hreflang="vi-vn" />
    <link rel="canonical" href="<?php echo url(@$canonical); ?>" itemprop="url">
    <meta property="fb:app_id" content="2161101824130389" /> 
    <meta property="og:title" content="<?php echo @$seo_title; ?>" itemprop="headline">
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:type"   content="article" /> 
    <meta property="og:description" content="<?php echo @$seo_meta_description; ?>" itemprop="description">
    <meta property="og:site_name" content="<?php echo url('/'); ?>">
    <meta property="og:url" content="<?php echo url(@$canonical); ?>" itemprop="url">
    <meta property="og:image" content="<?php echo @$og_image; ?>" itemprop="thumbnailUrl"> 
    <meta property="og:image:url" content="<?php echo @$og_image; ?>"/>  
    <link rel="shortcut icon" href="<?php echo @$seo_favicon; ?>" type="image/x-icon">
    <link rel="icon" href="<?php echo @$seo_favicon; ?>" type="image/x-icon">      
    <!--end meta SEO-->       
    <!-- begin google analytics -->
    <script src="https://www.googletagmanager.com/gtag/js?id=<?php echo @$seo_google_analytics; ?>"></script>
    <script language="javascript" type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo @$seo_google_analytics; ?>');
    </script>
    <!-- end google analytics -->
    <!--srart theme style -->
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/animate.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/bootstrap.css')}}" />    
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/fonts.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/reset.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/owl.carousel.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/owl.theme.default.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/flaticon.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/style_II.css')}}" />  
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/style_map.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/job-light/css/responsive_map.css')}}" />
    <!--end theme style -->
    <!-- begin css standard -->        
    <link href="{{asset('public/frontend/ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/fontawesome/css/all.min.css')}}" />
    <link href="{{asset('public/frontend/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('public/frontend/datatables/jquery.dataTables.min.css') }}" />
    <link href="{{asset('public/frontend/summernote/summernote.css')}}" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" type="text/css" href="{{asset('public/frontend/css/template.css')}}" /> 
    <!-- end css standard -->    
</head>
<body>		
	<!-- preloader Start -->    
    <div id="preloader">
        <div id="status"><img src="{{asset('public/frontend/job-light/images/header/loadinganimation.gif')}}" id="preloader_image" alt="loader">
        </div>
    </div>
    <!-- Top Scroll End -->    
    <!-- Header Wrapper Start -->
    <div class="jp_top_header_img_wrapper">
        <div class="gc_main_menu_wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hidden-xs hidden-sm full_width">
                        <div class="gc_header_wrapper">
                            <div class="gc_logo">
                                <a href="<?php echo route('frontend.index.getHome'); ?>"><img src="<?php echo @$seo_logo_frontend;?>" alt="<?php echo @$seo["alt_logo"]; ?>" title="<?php echo @$seo["alt_logo"]; ?>" class="img-responsive"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-8 col-sm-12 col-xs-12 center_responsive">
                        <div class="header-area hidden-menu-bar stick" id="sticker">
                            <!-- mainmenu start -->
                            <div class="mainmenu">
                                <?php     
                                $args = array(                         
                                    'menu_class'            => 'float_left',                            
                                    'before_wrapper'        => '',
                                    'before_title'          => '',
                                    'after_title'           => '',
                                    'before_wrapper_ul'     =>  '',
                                    'after_wrapper_ul'      =>  '',
                                    'after_wrapper'         => ''     ,
                                    'link_before'           => '', 
                                    'link_after'            => '<i class="fa fa-angle-down"></i>',                                                                    
                                    'theme_location'        => 'menu-header' ,
                                    'menu_li_actived'       => '',
                                    'menu_item_has_children'=> '',
                                    'alias'                 => ''
                                );                 
                                wp_nav_menu_top($args);                          
                                ?>                                      
                            </div>
                            <!-- mainmenu end -->
                            <!-- mobile menu area start -->
                            <header class="mobail_menu">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6">
                                            <div class="gc_logo">
                                                <a href="index.html"><img src="{{asset('public/frontend/job-light/images/header/logo2.png')}}" alt="Logo" title="Grace Church"></a>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6">
                                            <div class="cd-dropdown-wrapper">
                                                <a class="house_toggle" href="#0">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 31.177 31.177" style="enable-background:new 0 0 31.177 31.177;" xml:space="preserve" width="25px" height="25px"><g><g><path class="menubar" d="M30.23,1.775H0.946c-0.489,0-0.887-0.398-0.887-0.888S0.457,0,0.946,0H30.23    c0.49,0,0.888,0.398,0.888,0.888S30.72,1.775,30.23,1.775z" fill="#23c0e9"/></g><g><path class="menubar" d="M30.23,9.126H12.069c-0.49,0-0.888-0.398-0.888-0.888c0-0.49,0.398-0.888,0.888-0.888H30.23    c0.49,0,0.888,0.397,0.888,0.888C31.118,8.729,30.72,9.126,30.23,9.126z" fill="#23c0e9"/></g><g><path class="menubar" d="M30.23,16.477H0.946c-0.489,0-0.887-0.398-0.887-0.888c0-0.49,0.398-0.888,0.887-0.888H30.23    c0.49,0,0.888,0.397,0.888,0.888C31.118,16.079,30.72,16.477,30.23,16.477z" fill="#23c0e9"/></g><g><path class="menubar" d="M30.23,23.826H12.069c-0.49,0-0.888-0.396-0.888-0.887c0-0.49,0.398-0.888,0.888-0.888H30.23    c0.49,0,0.888,0.397,0.888,0.888C31.118,23.43,30.72,23.826,30.23,23.826z" fill="#23c0e9"/></g><g><path class="menubar" d="M30.23,31.177H0.946c-0.489,0-0.887-0.396-0.887-0.887c0-0.49,0.398-0.888,0.887-0.888H30.23    c0.49,0,0.888,0.398,0.888,0.888C31.118,30.78,30.72,31.177,30.23,31.177z" fill="#23c0e9"/></g></g></svg>
													</a>
                                                <nav class="cd-dropdown">
                                                    <h2><a href="#">Job<span>Pro</span></a></h2>
                                                    <a href="#0" class="cd-close">Close</a>
                                                    <ul class="cd-dropdown-content">
                                                        <li>
                                                            <form class="cd-search">
                                                                <input type="search" placeholder="Search...">
                                                            </form>
                                                        </li>
                                                        <li >
                                                            <a href="<?php echo route('frontend.index.getHome'); ?>">Trang chủ</a>
                                                        </li>                                                        
                                                        <li >
                                                            <a href="<?php echo route('frontend.index.viewEmployerAccount'); ?>">Nhà tuyển dụng</a>
                                                        </li>                                                        
                                                        <li >
                                                            <a href="<?php echo route('frontend.index.viewCandidateAccount'); ?>">Ứng viên</a>
                                                        </li>                                                        														
                                                        <li>
                                                            <a href="javascript:void(0);">Liên hệ</a>
                                                        </li>

                                                    </ul>
                                                    <!-- .cd-dropdown-content -->



                                                </nav>
                                                <!-- .cd-dropdown -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .cd-dropdown-wrapper -->
                            </header>
                        </div>
                    </div>
                    <!-- mobile menu area end -->
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 hidden-sm hidden-xs">
                        <div class="jp_navi_right_btn_wrapper">
                            <?php 
                            $arrUser=array();
                            $source_employer=array();
                            $source_candidate=array();
                            $ssNameUser='vmuser';
                            $permalink_login='';
                            if(Session::has($ssNameUser)){
                                $arrUser=Session::get($ssNameUser);
                            }
                            if(count(@$arrUser) > 0){
                                $email=@$arrUser['email'];                                 
                                $source_employer=App\EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
                                $source_candidate=App\CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
                                if(count($source_employer) > 0){
                                    $permalink_login=route('frontend.index.viewEmployerAccount');
                                }
                                if(count($source_candidate) > 0){
                                    $permalink_login=route('frontend.index.viewCandidateAccount');
                                }      
                                ?>
                                <ul>
                                    <li><a href="<?php echo $permalink_login; ?>"><i class="fa fa-user"></i>&nbsp; TÀI KHOẢN</a></li>                                    
                                </ul>
                                <?php
                            }else{
                                ?>
                                <ul>
                                    <li><a href="<?php echo route('frontend.index.register'); ?>"><i class="fa fa-user"></i>&nbsp; ĐĂNG KÝ</a></li>
                                    <li><a href="<?php echo route('frontend.index.loginFe'); ?>"><i class="fa fa-sign-in"></i>&nbsp; ĐĂNG NHẬP</a></li>
                                </ul>
                                <?php
                            }
                            ?>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Wrapper End -->
	@yield("content")
	@include("frontend.footer")        
    <!--main js file start-->  
    <script src="{{asset('public/frontend/job-light/js/jquery_min.js')}}"></script>  
    <script src="{{asset('public/frontend/job-light/js/bootstrap.js')}}"></script>
    <script src="{{asset('public/frontend/job-light/js/jquery.menu-aim.js')}}"></script>
    <script src="{{asset('public/frontend/job-light/js/jquery.countTo.js')}}"></script>
    <script src="{{asset('public/frontend/job-light/js/jquery.inview.min.js')}}"></script>
    <script src="{{asset('public/frontend/job-light/js/owl.carousel.js')}}"></script>
    <script src="{{asset('public/frontend/job-light/js/modernizr.js')}}"></script>    
    <script src="{{asset('public/frontend/job-light/js/custom_map.js')}}"></script>
    <!--main js file end-->  
    <!--begin google map-->     	            
    <?php     
    if((int)Request::is('/')){
        ?>        
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDOogBL2cC0dSezucKzQGWxMIMmclqWNts&sensor=false"></script>
        <script type="text/javascript" language="javascript">
            var infowindow = null;
            $(document).ready(function() { initialize(); });

            function initialize() {

                var centerMap = new google.maps.LatLng(41.0793, -85.1394);

                var myOptions = {
                    zoom: 6,
                    center: centerMap,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: [
                    { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                    {
                        featureType: 'administrative.locality',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                    },
                    {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{ color: '#263c3f' }]
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#6b9a76' }]
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{ color: '#38414e' }]
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#212a37' }]
                    },
                    {
                        featureType: 'road',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#9ca5b3' }]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{ color: '#746855' }]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#1f2835' }]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#f3d19c' }]
                    },
                    {
                        featureType: 'transit',
                        elementType: 'geometry',
                        stylers: [{ color: '#2f3948' }]
                    },
                    {
                        featureType: 'transit.station',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                    },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{ color: '#17263c' }]
                    },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#515c6d' }]
                    },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.stroke',
                        stylers: [{ color: '#17263c' }]
                    }
                    ]
                }

                var map = new google.maps.Map(document.getElementById("map"), myOptions);

                setMarkers(map, sites);
                infowindow = new google.maps.InfoWindow({
                    content: "loading..."
                });

                var bikeLayer = new google.maps.BicyclingLayer();
                bikeLayer.setMap(map);


            }

            var sites = [
            ['Warner Bros', 41.879, -87.624, 4, 'Warner Bros, 5400 N. Lakewood Avenue, Chicago'],
            ['Irving Homestead', 42.5000, -96.4003, 2, 'This is the Irving Homestead.'],
            ['Artis', 42.9634, -85.6681, 1, 'Artis, 101 Monroe Center St NW Grand Rapids'],
            ['Kansao', 39.0997, -94.5786, 3, 'Kansao, 4039 Euclid Ave Kansas City'],

            ['City Wyne', 41.0793, -85.1394, 8, 'City Wyne, 200 E. Berry St. Suite 470. Fort Wayne'],
            ['Microsolution', 39.9612, -82.9988, 7, 'Microsolution, 3870 Gateway Blvd Columbus'],
            ['Blue Berry', 38.6270, -90.1994, 5, 'Blue Berry, 12555 N Outer Forty Dr St. Louis'],
            ['Red Hat', 38.0406, -84.5037, 6, 'Red Hat, 2609 Red Leaf Dr, Lexington'],

            ['PlotHQ', 44.9778, -93.2650, 8, 'PlotHQ, 350 South 5th Street, Minneapolis'],
            ['Ivan Sol', 40.4406, -79.9959, 7, 'Ivan Sol, 4200 Fifth Avenue Pittsburgh'],
            ['Omni Soft', 41.2524, -95.9980, 5, 'Omni Soft, 19102 W St Omaha'],
            ['Canadian Firm', 43.6532, -79.3832, 6, 'Canadian Firm. PwC Tower 18 York Street, Suite 2600. Toronto']
            ];

            function setMarkers(map, markers) {

                for (var i = 0; i < markers.length; i++) {
                    var sites = markers[i];
                    var siteLatLng = new google.maps.LatLng(sites[1], sites[2]);
                    var marker = new google.maps.Marker({
                        position: siteLatLng,
                        map: map,
                        title: sites[0],
                        zIndex: sites[3],
                        html: sites[4]
                    });

                    var contentString = "Some content";

                    google.maps.event.addListener(marker, "click", function() {
                        infowindow.setContent(this.html);
                        infowindow.open(map, this);
                    });
                }
            }
        </script>        
        <?php
    }  
    ?>   
    <!--end google map--> 
    <!-- begin js standard -->       
    <script language="javascript" type="text/javascript" src="{{asset('public/frontend/ui/jquery-ui.js')}}"                 ></script>    
    <script src="{{ asset('public/frontend/datatables/jquery.dataTables.min.js') }}"></script>   
    <script src="{{ asset('public/frontend/js/table-library.js') }}"></script>    
    <script language="javascript" type="text/javascript" src="{{asset('public/frontend/select2/select2.min.js')}}"                 ></script>
    <script language="javascript" type="text/javascript" src="{{asset('public/frontend/summernote/summernote.js')}}"                 ></script>
    <script language="javascript" type="text/javascript" src="{{asset('public/frontend/summernote/summernote-image-attributes.js')}}"                 ></script>
    <script language="javascript" type="text/javascript" src="{{asset('public/frontend/summernote/summernote-remove-link.js')}}"                 ></script>            
    <script src="{{ asset('public/frontend/js/owl-carousel.js') }}"></script> 
    <script src="{{ asset('public/frontend/js/chonviec.js') }}"></script> 
    <script src="{{ asset('public/frontend/js/custom.js') }}"></script>    
    <!-- end js standard -->        
</body>
</html>



