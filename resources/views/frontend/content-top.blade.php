<?php 
$arrUser=array();
$source_employer=array();
$source_candidate=array();
$ssNameUser='vmuser';
if(Session::has($ssNameUser)){
	$arrUser=Session::get($ssNameUser);
}         
if(count($arrUser) > 0){
	$email=@$arrUser['email'];   
	$source_employer=App\EmployerModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
	$source_candidate=App\CandidateModel::whereRaw('trim(lower(email)) = ?',[trim(mb_strtolower(@$email,'UTF-8'))])->select('id','email')->get()->toArray();
	if(count($source_employer) > 0){
		?>
		@include("frontend.employer-content-top")
		<?php
	}
	if(count($source_candidate) > 0){
		?>
		@include("frontend.candidate-content-top")
		<?php
	}      
}else{
	?>
	@include("frontend.content-top-register")
	<?php
}
?>