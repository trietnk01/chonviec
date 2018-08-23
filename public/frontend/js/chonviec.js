/* begin applied-form */
function applyAppliedForm(apply_link){	
	var profile_id=0;
	var profile_id_selected=$('form[name="frm-apply-method-1"]').find('input[name="profile_id"]:checked');                
	var recruitment_id=$('form[name="frm-apply-method-1"]').find('input[name="recruitment_id"]').val(); 
	var candidate_id=$('form[name="frm-apply-method-1"]').find('input[name="candidate_id"]').val(); 
	var token=$('form[name="frm-apply-method-1"]').find('input[name="_token"]').val();           
	if(profile_id_selected.length > 0){
		profile_id=parseInt($(profile_id_selected).val()) ;
	}else{
		alert('Vui lòng chọn 1 hồ sơ để ứng tuyển');
		return 0;
	}             
	var xac_nhan = 0;
	var msg="Hồ sơ sẽ không được chỉnh sửa sau khi nộp . Bạn có chắc chắn ?";
	if(window.confirm(msg)){ 
		xac_nhan = 1;
	}
	if(xac_nhan  == 0){
		return 0;
	}		
	var dataItem = new FormData();
	dataItem.append('profile_id',profile_id);        
	dataItem.append('recruitment_id',recruitment_id);
	dataItem.append('candidate_id',candidate_id);
	dataItem.append('_token',token);
	$.ajax({
		url: apply_link,
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
		},
		error : function (data){
		},
		beforeSend  : function(jqXHR,setting){
			
		},
		cache: false,
		contentType: false,
		processData: false
	});
}

function chooseFileInfoAppliedForm(save_file_applied_link){
	$('form[name="frm-apply-method-2"]').find("input[name='file_attached']").change(function(){   
		var xac_nhan = 0;
		var msg="Bạn chắc chắn có muốn ứng tuyển vào vị trí này không ?";
		if(window.confirm(msg)){ 
			xac_nhan = 1;
		}
		if(xac_nhan  == 0){
			return 0;
		} 					
		/* begin xử lý image */
		var image_file=null;
		var image_ctrl=$('form[name="frm-apply-method-2"]').find('input[name="file_attached"]');         
		var image_files = $(image_ctrl).get(0).files;        
		if(image_files.length > 0){            
			image_file  = image_files[0];  
		}        
		/* end xử lý image */
		var recruitment_id=$('form[name="frm-apply-method-2"]').find('input[name="recruitment_id"]').val(); 
		var candidate_id=$('form[name="frm-apply-method-2"]').find('input[name="candidate_id"]').val(); 
		var token=$('form[name="frm-apply-method-2"]').find('input[name="_token"]').val();   
		var dataItem = new FormData();			
		if(image_files.length > 0){
			dataItem.append('file_attached',image_file);
		} 
		dataItem.append('recruitment_id',recruitment_id);
		dataItem.append('candidate_id',candidate_id);
		dataItem.append('_token',token);
		$.ajax({
			url: save_file_applied_link,
			type: 'POST',
			data: dataItem,
			async: false,
			success: function (data) {
				if(data.checked==1){      
					alert(data.msg.success); 
					window.location.href = data.link_edit;                                      			
				} else{
					showMsg('note',data);    
				}       			
			},
			error : function (data){
				
			},
			beforeSend  : function(jqXHR,setting){
				
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
}
/* end applied-form */
/* begin group-profile */
function chooseFileInfoGroupProfile(saved_link){
	$("form[name='frm-group-profile']").find("input[name='file_attached']").change(function(){    		
		var id=$("form[name='frm-group-profile']").find('input[name="id"]').val();        
		/* begin xử lý image */
		var image_file=null;
		var image_ctrl=$("form[name='frm-group-profile']").find('input[name="file_attached"]');         
		var image_files = $(image_ctrl).get(0).files;        
		if(image_files.length > 0){            
			image_file  = image_files[0];  
		}        
		/* end xử lý image */
		var token =$("form[name='frm-group-profile']").find('input[name="_token"]').val();       
		var dataItem = new FormData();
		dataItem.append('id',id);
		if(image_files.length > 0){
			dataItem.append('file_attached',image_file);
		} 
		dataItem.append('_token',token);
		$.ajax({
			url: saved_link,
			type: 'POST',
			data: dataItem,
			async: false,
			success: function (data) {
				if(data.checked==1){      
					alert('Lưu file đính kèm thành công');              
					window.location.href = "<?php echo $linkCancel; ?>";                    
				} else{
					showMsg('note',data);    
				}       			
			},
			error : function (data){

			},
			beforeSend  : function(jqXHR,setting){

			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
}
/* end group-profile */
/* begin profile-detail */
function saveCareerGoal(url_link){
	var id = $("form[name='frm']").find("input[name='id']").val();
	var career_goal = $("form[name='frm']").find("textarea[name='career_goal']").summernote('code');
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('career_goal',career_goal);           
	dataItem.append('_token',token);
	$.ajax({
		url: url_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){      					
				$('.career_goal_txt').empty();
				$('.career_goal_txt').append(data.career_goal);										
				$('.career_goal_edit').show();
				$('.career_goal_save').hide();
			} 
			showMsg('note_career_goal',data);     			
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function showCareerGoalSave(){
	$('.career_goal_edit').hide();
	$('.career_goal_save').show();
}
function noSaveCareerGoal(){
	$('.career_goal_edit').show();
	$('.career_goal_save').hide();	
}		
function saveExperienceJob(url_link){
	var id = $("form[name='frm']").find("input[name='id']").val();
	var company_name = $("form[name='frm']").find("input[name='company_name']").val();
	var person_title = $("form[name='frm']").find("input[name='person_title']").val();
	var month_from = $("form[name='frm']").find("select[name='month_from']").val();
	var year_from = $("form[name='frm']").find("select[name='year_from']").val();
	var month_to = $("form[name='frm']").find("select[name='month_to']").val();
	var year_to = $("form[name='frm']").find("select[name='year_to']").val();
	var currency = $("form[name='frm']").find("select[name='currency']").val();
	var salary = $("form[name='frm']").find("input[name='salary']").val();
	var job_description = $("form[name='frm']").find("textarea[name='job_description']").summernote('code');
	var achievement = $("form[name='frm']").find("textarea[name='achievement']").summernote('code');		
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('company_name',company_name);           
	dataItem.append('person_title',person_title);           
	dataItem.append('month_from',month_from);           
	dataItem.append('year_from',year_from);           
	dataItem.append('month_to',month_to);           
	dataItem.append('year_to',year_to);           
	dataItem.append('currency',currency);           
	dataItem.append('salary',salary);           
	dataItem.append('job_description',job_description);           
	dataItem.append('achievement',achievement);           		
	dataItem.append('_token',token);
	$.ajax({
		url: url_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){      	
				var data_profile_experience=data.data_profile_experience;	
				loadDataProfileExperience(data_profile_experience);
				$('.experience_job_edit').show();
				$('.experience_job_save').hide();
			}
			showMsg('note_experience',data);         			
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function noSaveExperienceJob(){
	$('.experience_job_edit').show();
	$('.experience_job_save').hide();		
}
function addExperienceJob(){
	$('.experience_job_save').show();
	$("form[name='frm']").find("input[name='company_name']").val('');
	$("form[name='frm']").find("input[name='person_title']").val('');
	$("form[name='frm']").find("select[name='month_from']").val(0);
	$("form[name='frm']").find("select[name='year_from']").val(0);
	$("form[name='frm']").find("select[name='month_to']").val(0);
	$("form[name='frm']").find("select[name='year_to']").val(0);
	$("form[name='frm']").find("select[name='currency']").val('');
	$("form[name='frm']").find("input[name='salary']").val('');
	$("form[name='frm']").find("textarea[name='job_description']").val("");
	$("form[name='frm']").find("textarea[name='achievement']").val("");
}
function loadDataProfileExperience(data_profile_experience){
	$('.experience_job_txt').empty();					
	$.each(data_profile_experience,function(index,value){
		/* begin company_name */
		var company_name_row_mia=document.createElement('div');					
		var company_name_col_lg_4=document.createElement('div');
		var company_name_col_lg_8=document.createElement('div');
		var company_name_xika=document.createElement('div');
		var company_name_xika2=document.createElement('div');
		$(company_name_row_mia).addClass('row mia');
		$(company_name_col_lg_4).addClass('col-lg-4');
		$(company_name_col_lg_8).addClass('col-lg-8');
		$(company_name_xika).addClass('xika');
		$(company_name_xika2).addClass('xika2');
		$('.experience_job_txt').append(company_name_row_mia);
		$(company_name_row_mia).append(company_name_col_lg_4);
		$(company_name_row_mia).append(company_name_col_lg_8);
		$(company_name_col_lg_4).append(company_name_xika);
		$(company_name_col_lg_8).append(company_name_xika2);
		$(company_name_xika).text('Tên công ty');
		$(company_name_xika2).text(value.company_name);						
		/* end company_name */
		/* begin person_title */
		var person_title_row_mia=document.createElement('div');					
		var person_title_col_lg_4=document.createElement('div');
		var person_title_col_lg_8=document.createElement('div');
		var person_title_xika=document.createElement('div');
		var person_title_xika2=document.createElement('div');
		$(person_title_row_mia).addClass('row mia');
		$(person_title_col_lg_4).addClass('col-lg-4');
		$(person_title_col_lg_8).addClass('col-lg-8');
		$(person_title_xika).addClass('xika');
		$(person_title_xika2).addClass('xika2');
		$('.experience_job_txt').append(person_title_row_mia);
		$(person_title_row_mia).append(person_title_col_lg_4);
		$(person_title_row_mia).append(person_title_col_lg_8);
		$(person_title_col_lg_4).append(person_title_xika);
		$(person_title_col_lg_8).append(person_title_xika2);
		$(person_title_xika).text('Chức danh');
		$(person_title_xika2).text(value.person_title);						
		/* end person_title */
		/* begin business_time */
		var business_time_row_mia=document.createElement('div');					
		var business_time_col_lg_4=document.createElement('div');
		var business_time_col_lg_8=document.createElement('div');
		var business_time_xika=document.createElement('div');
		var business_time_xika2=document.createElement('div');
		var business_time_general=document.createElement('div');
		var business_time_from=document.createElement('div');
		var business_time_month_year_from=document.createElement('div');
		var business_time_to=document.createElement('div');
		var business_time_month_year_to=document.createElement('div');
		$(business_time_row_mia).addClass('row mia');
		$(business_time_col_lg_4).addClass('col-lg-4');
		$(business_time_col_lg_8).addClass('col-lg-8');
		$(business_time_xika).addClass('xika');
		$(business_time_xika2).addClass('xika2');
		$(business_time_general).addClass('lunarnewyear');
		$(business_time_month_year_from).addClass('margin-left-10');						
		$(business_time_to).addClass('margin-left-10');
		$(business_time_month_year_to).addClass('margin-left-10');						
		$('.experience_job_txt').append(business_time_row_mia);
		$(business_time_row_mia).append(business_time_col_lg_4);
		$(business_time_row_mia).append(business_time_col_lg_8);
		$(business_time_col_lg_4).append(business_time_xika);
		$(business_time_col_lg_8).append(business_time_xika2);
		$(business_time_xika).text('Thời gian làm việc');						
		$(business_time_xika2).append(business_time_general);
		$(business_time_general).append(business_time_from);
		$(business_time_general).append(business_time_month_year_from);						
		$(business_time_general).append(business_time_to);
		$(business_time_general).append(business_time_month_year_to);						
		$(business_time_from).text('Từ');
		$(business_time_month_year_from).text(value.time_from);									
		$(business_time_to).text('Đến');
		$(business_time_month_year_to).text(value.time_to);											
		/* end business_time */
		/* begin salary */
		var salary_row_mia=document.createElement('div');					
		var salary_col_lg_4=document.createElement('div');
		var salary_col_lg_8=document.createElement('div');
		var salary_xika=document.createElement('div');
		var salary_xika2=document.createElement('div');						
		var salary_money=document.createElement('div');						
		$(salary_row_mia).addClass('row mia');
		$(salary_col_lg_4).addClass('col-lg-4');
		$(salary_col_lg_8).addClass('col-lg-8');
		$(salary_xika).addClass('xika');
		$(salary_xika2).addClass('xika2');											
		$('.experience_job_txt').append(salary_row_mia);
		$(salary_row_mia).append(salary_col_lg_4);
		$(salary_row_mia).append(salary_col_lg_8);
		$(salary_col_lg_4).append(salary_xika);
		$(salary_col_lg_8).append(salary_xika2);
		$(salary_xika).text('Mức lương');
		$(salary_xika2).append(salary_money);						
		$(salary_money).text(value.salary);						
		/* end salary */
		/* begin job_description */
		var job_description_row_mia=document.createElement('div');					
		var job_description_col_lg_4=document.createElement('div');
		var job_description_col_lg_8=document.createElement('div');
		var job_description_xika=document.createElement('div');
		var job_description_xika2=document.createElement('div');
		$(job_description_row_mia).addClass('row mia');
		$(job_description_col_lg_4).addClass('col-lg-4');
		$(job_description_col_lg_8).addClass('col-lg-8');
		$(job_description_xika).addClass('xika');
		$(job_description_xika2).addClass('xika2');
		$('.experience_job_txt').append(job_description_row_mia);
		$(job_description_row_mia).append(job_description_col_lg_4);
		$(job_description_row_mia).append(job_description_col_lg_8);
		$(job_description_col_lg_4).append(job_description_xika);
		$(job_description_col_lg_8).append(job_description_xika2);
		$(job_description_xika).text('Mô tả công việc');
		$(job_description_xika2).append(value.job_description);						
		/* end job_description */
		/* begin achievement */
		var achievement_row_mia=document.createElement('div');					
		var achievement_col_lg_4=document.createElement('div');
		var achievement_col_lg_8=document.createElement('div');
		var achievement_xika=document.createElement('div');
		var achievement_xika2=document.createElement('div');
		$(achievement_row_mia).addClass('row mia');
		$(achievement_col_lg_4).addClass('col-lg-4');
		$(achievement_col_lg_8).addClass('col-lg-8');
		$(achievement_xika).addClass('xika');
		$(achievement_xika2).addClass('xika2');
		$('.experience_job_txt').append(achievement_row_mia);
		$(achievement_row_mia).append(achievement_col_lg_4);
		$(achievement_row_mia).append(achievement_col_lg_8);
		$(achievement_col_lg_4).append(achievement_xika);
		$(achievement_col_lg_8).append(achievement_xika2);
		$(achievement_xika).text('Thành tích đạt được');
		$(achievement_xika2).append(value.achievement);	
		/* end achievement */
		/* begin delete */
		var delete_row_mia=document.createElement('div');					
		var delete_col_lg_4=document.createElement('div');
		var delete_col_lg_8=document.createElement('div');							
		$(delete_row_mia).addClass('row mia');
		$(delete_col_lg_4).addClass('col-lg-4');
		$(delete_col_lg_8).addClass('col-lg-8');								
		$('.experience_job_txt').append(delete_row_mia);
		$(delete_row_mia).append(delete_col_lg_4);
		$(delete_row_mia).append(delete_col_lg_8);	
		var delete_html='<div class="vihamus-3"><a href="javascript:void(0);" onclick="deleteProfileExperience('+parseInt(value.id)+');"><div class="narit"><div><i class="far fa-times-circle"></i></div><div class="margin-left-5">Xóa</div></div></a></div>';		
		$(delete_col_lg_8).append(delete_html);									
		/* end delete */
		/* begin hr */
		var hr=document.createElement('hr');
		$('.experience_job_txt').append(hr);				
		/* end hr */
	});										
}
function deleteProfileExperience(profile_experience_id,url_link){
	var xac_nhan = 0;
	var msg="Bạn có muốn xóa ?";
	if(window.confirm(msg)){ 
		xac_nhan = 1;
	}
	if(xac_nhan  == 0){
		return 0;
	}
	var id = $("form[name='frm']").find("input[name='id']").val();		
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('profile_experience_id',profile_experience_id);           		
	dataItem.append('_token',token);
	$.ajax({
		url: url_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){      	
				var data_profile_experience=data.data_profile_experience;	
				loadDataProfileExperience(data_profile_experience);
			} 
			showMsg('note_experience',data);       			
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function saveGraduation(url_link,deleted_link){
	var id = $("form[name='frm']").find("input[name='id']").val();
	var literacy_id = $("form[name='frm']").find("select[name='literacy_id']").val();
	var training_unit = $("form[name='frm']").find("input[name='training_unit']").val();
	var graduation_year_from = $("form[name='frm']").find("select[name='graduation_year_from']").val();
	var graduation_year_to = $("form[name='frm']").find("select[name='graduation_year_to']").val();
	var department = $("form[name='frm']").find("input[name='department']").val();
	var graduation_id = $("form[name='frm']").find("select[name='graduation_id']").val();
	/* begin xử lý image */
	var image_file=null;
	var image_ctrl=$('input[name="degree"]');         
	var image_files = $(image_ctrl).get(0).files;        
	if(image_files.length > 0){            
		image_file  = image_files[0];  
	}        
	/* end xử lý image */   
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('literacy_id',literacy_id);
	dataItem.append('training_unit',training_unit);
	dataItem.append('graduation_year_from',graduation_year_from);
	dataItem.append('graduation_year_to',graduation_year_to);
	dataItem.append('department',department);
	dataItem.append('graduation_id',graduation_id);
	if(image_files.length > 0){
		dataItem.append('image',image_file);
	}  	
	dataItem.append('_token',token);
	$.ajax({
		url: url_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){
				var data_profile_graduation=data.data_profile_graduation;	
				loadDataProfileGraduation(data_profile_graduation,deleted_link);
				$('.graduation_edit').show();
				$('.graduation_save').hide();			
			}
			showMsg('note_graduation',data);    
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function loadDataProfileGraduation(data_profile_graduation,deleted_link){
	$('.graduation_txt').empty();
	$.each(data_profile_graduation,function(index,value){
		/* begin literacy */
		var literacy_row_mia=document.createElement('div');					
		var literacy_col_lg_4=document.createElement('div');
		var literacy_col_lg_8=document.createElement('div');
		var literacy_xika=document.createElement('div');
		var literacy_xika2=document.createElement('div');
		$(literacy_row_mia).addClass('row mia');
		$(literacy_col_lg_4).addClass('col-lg-4');
		$(literacy_col_lg_8).addClass('col-lg-8');
		$(literacy_xika).addClass('xika');
		$(literacy_xika2).addClass('xika2');
		$('.graduation_txt').append(literacy_row_mia);
		$(literacy_row_mia).append(literacy_col_lg_4);
		$(literacy_row_mia).append(literacy_col_lg_8);
		$(literacy_col_lg_4).append(literacy_xika);
		$(literacy_col_lg_8).append(literacy_xika2);
		$(literacy_xika).text('Trình độ học vấn');
		$(literacy_xika2).text(value.literacy_name);						
		/* end literacy */
		/* begin training_unit */
		var training_unit_row_mia=document.createElement('div');					
		var training_unit_col_lg_4=document.createElement('div');
		var training_unit_col_lg_8=document.createElement('div');
		var training_unit_xika=document.createElement('div');
		var training_unit_xika2=document.createElement('div');
		$(training_unit_row_mia).addClass('row mia');
		$(training_unit_col_lg_4).addClass('col-lg-4');
		$(training_unit_col_lg_8).addClass('col-lg-8');
		$(training_unit_xika).addClass('xika');
		$(training_unit_xika2).addClass('xika2');
		$('.graduation_txt').append(training_unit_row_mia);
		$(training_unit_row_mia).append(training_unit_col_lg_4);
		$(training_unit_row_mia).append(training_unit_col_lg_8);
		$(training_unit_col_lg_4).append(training_unit_xika);
		$(training_unit_col_lg_8).append(training_unit_xika2);
		$(training_unit_xika).text('Đơn vị đào tạo');
		$(training_unit_xika2).text(value.training_unit);						
		/* end training_unit */
		/* begin business_time */
		var business_time_row_mia=document.createElement('div');					
		var business_time_col_lg_4=document.createElement('div');
		var business_time_col_lg_8=document.createElement('div');
		var business_time_xika=document.createElement('div');
		var business_time_xika2=document.createElement('div');
		var business_time_general=document.createElement('div');
		var business_time_from=document.createElement('div');
		var business_time_month_year_from=document.createElement('div');
		var business_time_to=document.createElement('div');
		var business_time_month_year_to=document.createElement('div');
		$(business_time_row_mia).addClass('row mia');
		$(business_time_col_lg_4).addClass('col-lg-4');
		$(business_time_col_lg_8).addClass('col-lg-8');
		$(business_time_xika).addClass('xika');
		$(business_time_xika2).addClass('xika2');
		$(business_time_general).addClass('lunarnewyear');
		$(business_time_month_year_from).addClass('margin-left-10');						
		$(business_time_to).addClass('margin-left-10');
		$(business_time_month_year_to).addClass('margin-left-10');						
		$('.graduation_txt').append(business_time_row_mia);
		$(business_time_row_mia).append(business_time_col_lg_4);
		$(business_time_row_mia).append(business_time_col_lg_8);
		$(business_time_col_lg_4).append(business_time_xika);
		$(business_time_col_lg_8).append(business_time_xika2);
		$(business_time_xika).text('Thời gian');						
		$(business_time_xika2).append(business_time_general);
		$(business_time_general).append(business_time_from);
		$(business_time_general).append(business_time_month_year_from);						
		$(business_time_general).append(business_time_to);
		$(business_time_general).append(business_time_month_year_to);						
		$(business_time_from).text('Từ');
		$(business_time_month_year_from).text(value.year_from);									
		$(business_time_to).text('Đến');
		$(business_time_month_year_to).text(value.year_to);											
		/* end business_time */
		/* begin department */
		var department_row_mia=document.createElement('div');					
		var department_col_lg_4=document.createElement('div');
		var department_col_lg_8=document.createElement('div');
		var department_xika=document.createElement('div');
		var department_xika2=document.createElement('div');
		$(department_row_mia).addClass('row mia');
		$(department_col_lg_4).addClass('col-lg-4');
		$(department_col_lg_8).addClass('col-lg-8');
		$(department_xika).addClass('xika');
		$(department_xika2).addClass('xika2');
		$('.graduation_txt').append(department_row_mia);
		$(department_row_mia).append(department_col_lg_4);
		$(department_row_mia).append(department_col_lg_8);
		$(department_col_lg_4).append(department_xika);
		$(department_col_lg_8).append(department_xika2);
		$(department_xika).text('Chuyên ngành');
		$(department_xika2).text(value.department);						
		/* end department */
		/* begin graduation */
		var graduation_row_mia=document.createElement('div');					
		var graduation_col_lg_4=document.createElement('div');
		var graduation_col_lg_8=document.createElement('div');
		var graduation_xika=document.createElement('div');
		var graduation_xika2=document.createElement('div');
		$(graduation_row_mia).addClass('row mia');
		$(graduation_col_lg_4).addClass('col-lg-4');
		$(graduation_col_lg_8).addClass('col-lg-8');
		$(graduation_xika).addClass('xika');
		$(graduation_xika2).addClass('xika2');
		$('.graduation_txt').append(graduation_row_mia);
		$(graduation_row_mia).append(graduation_col_lg_4);
		$(graduation_row_mia).append(graduation_col_lg_8);
		$(graduation_col_lg_4).append(graduation_xika);
		$(graduation_col_lg_8).append(graduation_xika2);
		$(graduation_xika).text('Tốt nghiệp loại');
		$(graduation_xika2).text(value.graduation_name);						
		/* end graduation */
		/* begin degree */
		var degree_row_mia=document.createElement('div');					
		var degree_col_lg_4=document.createElement('div');
		var degree_col_lg_8=document.createElement('div');
		var degree_xika=document.createElement('div');
		var degree_xika2=document.createElement('div');
		var degree_img=document.createElement('img');
		$(degree_row_mia).addClass('row mia');
		$(degree_col_lg_4).addClass('col-lg-4');
		$(degree_col_lg_8).addClass('col-lg-8');
		$(degree_xika).addClass('xika');
		$(degree_xika2).addClass('xika2');
		$('.graduation_txt').append(degree_row_mia);
		$(degree_row_mia).append(degree_col_lg_4);
		$(degree_row_mia).append(degree_col_lg_8);
		$(degree_col_lg_4).append(degree_xika);
		$(degree_col_lg_8).append(degree_xika2);
		$(degree_xika).text('Bằng cấp');
		$(degree_img).prop('src',value.degree);
		$(degree_xika2).append(degree_img);
		/* end degree */
		/* begin delete */
		var delete_row_mia=document.createElement('div');					
		var delete_col_lg_4=document.createElement('div');
		var delete_col_lg_8=document.createElement('div');							
		$(delete_row_mia).addClass('row mia');
		$(delete_col_lg_4).addClass('col-lg-4');
		$(delete_col_lg_8).addClass('col-lg-8');								
		$('.graduation_txt').append(delete_row_mia);
		$(delete_row_mia).append(delete_col_lg_4);
		$(delete_row_mia).append(delete_col_lg_8);	
		var delete_html='<div class="vihamus-3"><a href="javascript:void(0);" onclick="deleteProfileGraduation('+parseInt(value.id)+','+deleted_link+');"><div class="narit"><div><i class="far fa-times-circle"></i></div><div class="margin-left-5">Xóa</div></div></a></div>';		
		$(delete_col_lg_8).append(delete_html);									
		/* end delete */
		/* begin hr */
		var hr=document.createElement('hr');
		$('.graduation_txt').append(hr);				
		/* end hr */
	});				
}
function deleteProfileGraduation(profile_graduation_id,deleted_link){
	var xac_nhan = 0;
	var msg="Bạn có muốn xóa ?";
	if(window.confirm(msg)){ 
		xac_nhan = 1;
	}
	if(xac_nhan  == 0){
		return 0;
	}
	var id = $("form[name='frm']").find("input[name='id']").val();		
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('profile_graduation_id',profile_graduation_id);           		
	dataItem.append('_token',token);
	$.ajax({
		url: url_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){      	
				var data_profile_graduation=data.data_profile_graduation;	
				loadDataProfileGraduation(data_profile_graduation,deleted_link);				
			} 
			showMsg('note_graduation',data);         			
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function noSaveGraduation(){
	$('.graduation_edit').show();
	$('.graduation_save').hide();		
}
function addGraduation(){
	$('.graduation_save').show();
	$("form[name='frm']").find("select[name='literacy_id']").val(0);
	$("form[name='frm']").find("input[name='training_unit']").val('');
	$("form[name='frm']").find("select[name='graduation_year_from']").val(0);
	$("form[name='frm']").find("select[name='graduation_year_to']").val(0);
	$("form[name='frm']").find("input[name='department']").val('');
	$("form[name='frm']").find("select[name='graduation_id']").val(0);
}
function loadDataProfileLanguage(data_profile_language,deleted_link){
	$('.language_txt').empty();
	$.each(data_profile_language,function(index,value){
		/* begin language */
		var language_row_mia=document.createElement('div');					
		var language_col_lg_4=document.createElement('div');
		var language_col_lg_8=document.createElement('div');
		var language_xika=document.createElement('div');
		var language_xika2=document.createElement('div');
		$(language_row_mia).addClass('row mia');
		$(language_col_lg_4).addClass('col-lg-4');
		$(language_col_lg_8).addClass('col-lg-8');
		$(language_xika).addClass('xika');
		$(language_xika2).addClass('xika2');
		$('.language_txt').append(language_row_mia);
		$(language_row_mia).append(language_col_lg_4);
		$(language_row_mia).append(language_col_lg_8);
		$(language_col_lg_4).append(language_xika);
		$(language_col_lg_8).append(language_xika2);
		$(language_xika).text('Ngoại ngữ');
		$(language_xika2).text(value.language_name);						
		/* end language */
		/* begin language_level */
		var language_level_row_mia=document.createElement('div');					
		var language_level_col_lg_4=document.createElement('div');
		var language_level_col_lg_8=document.createElement('div');
		var language_level_xika=document.createElement('div');
		var language_level_xika2=document.createElement('div');
		$(language_level_row_mia).addClass('row mia');
		$(language_level_col_lg_4).addClass('col-lg-4');
		$(language_level_col_lg_8).addClass('col-lg-8');
		$(language_level_xika).addClass('xika');
		$(language_level_xika2).addClass('xika2');
		$('.language_txt').append(language_level_row_mia);
		$(language_level_row_mia).append(language_level_col_lg_4);
		$(language_level_row_mia).append(language_level_col_lg_8);
		$(language_level_col_lg_4).append(language_level_xika);
		$(language_level_col_lg_8).append(language_level_xika2);
		$(language_level_xika).text('Trình độ');
		$(language_level_xika2).text(value.language_level_name);						
		/* end language_level */	
		/* begin listening */
		var listening_row_mia=document.createElement('div');					
		var listening_col_lg_4=document.createElement('div');
		var listening_col_lg_8=document.createElement('div');
		var listening_xika=document.createElement('div');
		var listening_xika2=document.createElement('div');
		$(listening_row_mia).addClass('row mia');
		$(listening_col_lg_4).addClass('col-lg-4');
		$(listening_col_lg_8).addClass('col-lg-8');
		$(listening_xika).addClass('xika');
		$(listening_xika2).addClass('xika2');
		$('.language_txt').append(listening_row_mia);
		$(listening_row_mia).append(listening_col_lg_4);
		$(listening_row_mia).append(listening_col_lg_8);
		$(listening_col_lg_4).append(listening_xika);
		$(listening_col_lg_8).append(listening_xika2);
		$(listening_xika).text('Nghe');
		$(listening_xika2).text(value.listening);						
		/* end listening */	
		/* begin speaking */
		var speaking_row_mia=document.createElement('div');					
		var speaking_col_lg_4=document.createElement('div');
		var speaking_col_lg_8=document.createElement('div');
		var speaking_xika=document.createElement('div');
		var speaking_xika2=document.createElement('div');
		$(speaking_row_mia).addClass('row mia');
		$(speaking_col_lg_4).addClass('col-lg-4');
		$(speaking_col_lg_8).addClass('col-lg-8');
		$(speaking_xika).addClass('xika');
		$(speaking_xika2).addClass('xika2');
		$('.language_txt').append(speaking_row_mia);
		$(speaking_row_mia).append(speaking_col_lg_4);
		$(speaking_row_mia).append(speaking_col_lg_8);
		$(speaking_col_lg_4).append(speaking_xika);
		$(speaking_col_lg_8).append(speaking_xika2);
		$(speaking_xika).text('Nói');
		$(speaking_xika2).text(value.speaking);						
		/* end speaking */
		/* begin reading */
		var reading_row_mia=document.createElement('div');					
		var reading_col_lg_4=document.createElement('div');
		var reading_col_lg_8=document.createElement('div');
		var reading_xika=document.createElement('div');
		var reading_xika2=document.createElement('div');
		$(reading_row_mia).addClass('row mia');
		$(reading_col_lg_4).addClass('col-lg-4');
		$(reading_col_lg_8).addClass('col-lg-8');
		$(reading_xika).addClass('xika');
		$(reading_xika2).addClass('xika2');
		$('.language_txt').append(reading_row_mia);
		$(reading_row_mia).append(reading_col_lg_4);
		$(reading_row_mia).append(reading_col_lg_8);
		$(reading_col_lg_4).append(reading_xika);
		$(reading_col_lg_8).append(reading_xika2);
		$(reading_xika).text('Đọc');
		$(reading_xika2).text(value.reading);						
		/* end reading */
		/* begin writing */
		var writing_row_mia=document.createElement('div');					
		var writing_col_lg_4=document.createElement('div');
		var writing_col_lg_8=document.createElement('div');
		var writing_xika=document.createElement('div');
		var writing_xika2=document.createElement('div');
		$(writing_row_mia).addClass('row mia');
		$(writing_col_lg_4).addClass('col-lg-4');
		$(writing_col_lg_8).addClass('col-lg-8');
		$(writing_xika).addClass('xika');
		$(writing_xika2).addClass('xika2');
		$('.language_txt').append(writing_row_mia);
		$(writing_row_mia).append(writing_col_lg_4);
		$(writing_row_mia).append(writing_col_lg_8);
		$(writing_col_lg_4).append(writing_xika);
		$(writing_col_lg_8).append(writing_xika2);
		$(writing_xika).text('Viết');
		$(writing_xika2).text(value.writing);						
		/* end writing */
		/* begin delete */
		var delete_row_mia=document.createElement('div');					
		var delete_col_lg_4=document.createElement('div');
		var delete_col_lg_8=document.createElement('div');							
		$(delete_row_mia).addClass('row mia');
		$(delete_col_lg_4).addClass('col-lg-4');
		$(delete_col_lg_8).addClass('col-lg-8');								
		$('.language_txt').append(delete_row_mia);
		$(delete_row_mia).append(delete_col_lg_4);
		$(delete_row_mia).append(delete_col_lg_8);	
		var delete_html='<div class="vihamus-3"><a href="javascript:void(0);" onclick="deleteProfileLanguage('+parseInt(value.id)+','+deleted_link+');"><div class="narit"><div><i class="far fa-times-circle"></i></div><div class="margin-left-5">Xóa</div></div></a></div>';		
		$(delete_col_lg_8).append(delete_html);									
		/* end delete */
		/* begin hr */
		var hr=document.createElement('hr');
		$('.language_txt').append(hr);				
		/* end hr */
	});				
}
function saveLanguage(saved_link,deleted_link){
	var id = $("form[name='frm']").find("input[name='id']").val();
	var language_id = $("form[name='frm']").find("select[name='language_id']").val();		
	var language_level_id = $("form[name='frm']").find("select[name='language_level_id']").val();
	var listening = $("form[name='frm']").find("input[name='listening']:checked").val();		
	var speaking = $("form[name='frm']").find("input[name='speaking']:checked").val();		
	var reading = $("form[name='frm']").find("input[name='reading']:checked").val();		
	var writing = $("form[name='frm']").find("input[name='writing']:checked").val();			
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('language_id',language_id);	
	dataItem.append('language_level_id',language_level_id);	
	dataItem.append('listening',listening);	
	dataItem.append('speaking',speaking);	
	dataItem.append('reading',reading);	
	dataItem.append('writing',writing);	
	dataItem.append('_token',token);
	$.ajax({
		url: saved_link,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){
				var data_profile_language=data.data_profile_language;					
				loadDataProfileLanguage(data_profile_language,deleted_link);
				$('.language_edit').show();
				$('.language_save').hide();			
			}			
			showMsg('note_language',data); 
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function deleteProfileLanguage(profile_language_id,deleted_link){
	var xac_nhan = 0;
	var msg="Bạn có muốn xóa ?";
	if(window.confirm(msg)){ 
		xac_nhan = 1;
	}
	if(xac_nhan  == 0){
		return 0;
	}
	var id = $("form[name='frm']").find("input[name='id']").val();		
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	dataItem.append('profile_language_id',profile_language_id);           		
	dataItem.append('_token',token);
	$.ajax({
		url:deleted_link ,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){      	
				var data_profile_language=data.data_profile_language;	
				loadDataProfileLanguage(data_profile_language,deleted_link);				
			} 
			showMsg('note_language',data);         			
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function noSaveLanguage(){
	$('.language_edit').show();
	$('.language_save').hide();		
}
function addLanguage(){
	$('.language_save').show();
	$("form[name='frm']").find("select[name='language_id']").val(0);	
	$("form[name='frm']").find("select[name='language_level_id']").val(0);
}
function saveOffice(saved_link){
	var id = $("form[name='frm']").find("input[name='id']").val();	
	var ms_word = $("form[name='frm']").find("input[name='ms_word']:checked").val();		
	var ms_excel = $("form[name='frm']").find("input[name='ms_excel']:checked").val();		
	var ms_power_point = $("form[name='frm']").find("input[name='ms_power_point']:checked").val();		
	var ms_outlook = $("form[name='frm']").find("input[name='ms_outlook']:checked").val();			
	var other_software = $("form[name='frm']").find("input[name='other_software']").val();	
	var medal = $("form[name='frm']").find("textarea[name='medal']").summernote('code');	
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);
	
	dataItem.append('ms_word',ms_word);	
	dataItem.append('ms_excel',ms_excel);	
	dataItem.append('ms_power_point',ms_power_point);	
	dataItem.append('ms_outlook',ms_outlook);	
	dataItem.append('other_software',other_software);	
	dataItem.append('medal',medal);	
	dataItem.append('_token',token);
	$.ajax({
		url:saved_link ,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){	
				$('.office_txt').empty();	
				/* begin ms_word */
				var ms_word_row_mia=document.createElement('div');					
				var ms_word_col_lg_4=document.createElement('div');
				var ms_word_col_lg_8=document.createElement('div');
				var ms_word_xika=document.createElement('div');
				var ms_word_xika2=document.createElement('div');
				$(ms_word_row_mia).addClass('row mia');
				$(ms_word_col_lg_4).addClass('col-lg-4');
				$(ms_word_col_lg_8).addClass('col-lg-8');
				$(ms_word_xika).addClass('xika');
				$(ms_word_xika2).addClass('xika2');
				$('.office_txt').append(ms_word_row_mia);
				$(ms_word_row_mia).append(ms_word_col_lg_4);
				$(ms_word_row_mia).append(ms_word_col_lg_8);
				$(ms_word_col_lg_4).append(ms_word_xika);
				$(ms_word_col_lg_8).append(ms_word_xika2);
				$(ms_word_xika).text('MS Word');
				$(ms_word_xika2).text(data.ms_word_level);						
				/* end ms_word */
				/* begin ms_excel */
				var ms_excel_row_mia=document.createElement('div');					
				var ms_excel_col_lg_4=document.createElement('div');
				var ms_excel_col_lg_8=document.createElement('div');
				var ms_excel_xika=document.createElement('div');
				var ms_excel_xika2=document.createElement('div');
				$(ms_excel_row_mia).addClass('row mia');
				$(ms_excel_col_lg_4).addClass('col-lg-4');
				$(ms_excel_col_lg_8).addClass('col-lg-8');
				$(ms_excel_xika).addClass('xika');
				$(ms_excel_xika2).addClass('xika2');
				$('.office_txt').append(ms_excel_row_mia);
				$(ms_excel_row_mia).append(ms_excel_col_lg_4);
				$(ms_excel_row_mia).append(ms_excel_col_lg_8);
				$(ms_excel_col_lg_4).append(ms_excel_xika);
				$(ms_excel_col_lg_8).append(ms_excel_xika2);
				$(ms_excel_xika).text('MS Excel');
				$(ms_excel_xika2).text(data.ms_excel_level);						
				/* end ms_excel */
				/* begin ms_power_point */
				var ms_power_point_row_mia=document.createElement('div');					
				var ms_power_point_col_lg_4=document.createElement('div');
				var ms_power_point_col_lg_8=document.createElement('div');
				var ms_power_point_xika=document.createElement('div');
				var ms_power_point_xika2=document.createElement('div');
				$(ms_power_point_row_mia).addClass('row mia');
				$(ms_power_point_col_lg_4).addClass('col-lg-4');
				$(ms_power_point_col_lg_8).addClass('col-lg-8');
				$(ms_power_point_xika).addClass('xika');
				$(ms_power_point_xika2).addClass('xika2');
				$('.office_txt').append(ms_power_point_row_mia);
				$(ms_power_point_row_mia).append(ms_power_point_col_lg_4);
				$(ms_power_point_row_mia).append(ms_power_point_col_lg_8);
				$(ms_power_point_col_lg_4).append(ms_power_point_xika);
				$(ms_power_point_col_lg_8).append(ms_power_point_xika2);
				$(ms_power_point_xika).text('MS Power Point');
				$(ms_power_point_xika2).text(data.ms_power_point_level);						
				/* end ms_power_point */
				/* begin ms_outlook */
				var ms_outlook_row_mia=document.createElement('div');					
				var ms_outlook_col_lg_4=document.createElement('div');
				var ms_outlook_col_lg_8=document.createElement('div');
				var ms_outlook_xika=document.createElement('div');
				var ms_outlook_xika2=document.createElement('div');
				$(ms_outlook_row_mia).addClass('row mia');
				$(ms_outlook_col_lg_4).addClass('col-lg-4');
				$(ms_outlook_col_lg_8).addClass('col-lg-8');
				$(ms_outlook_xika).addClass('xika');
				$(ms_outlook_xika2).addClass('xika2');
				$('.office_txt').append(ms_outlook_row_mia);
				$(ms_outlook_row_mia).append(ms_outlook_col_lg_4);
				$(ms_outlook_row_mia).append(ms_outlook_col_lg_8);
				$(ms_outlook_col_lg_4).append(ms_outlook_xika);
				$(ms_outlook_col_lg_8).append(ms_outlook_xika2);
				$(ms_outlook_xika).text('MS Outlook');
				$(ms_outlook_xika2).text(data.ms_outlook_level);						
				/* end ms_outlook */
				/* begin other_software */
				var other_software_row_mia=document.createElement('div');					
				var other_software_col_lg_4=document.createElement('div');
				var other_software_col_lg_8=document.createElement('div');
				var other_software_xika=document.createElement('div');
				var other_software_xika2=document.createElement('div');
				$(other_software_row_mia).addClass('row mia');
				$(other_software_col_lg_4).addClass('col-lg-4');
				$(other_software_col_lg_8).addClass('col-lg-8');
				$(other_software_xika).addClass('xika');
				$(other_software_xika2).addClass('xika2');
				$('.office_txt').append(other_software_row_mia);
				$(other_software_row_mia).append(other_software_col_lg_4);
				$(other_software_row_mia).append(other_software_col_lg_8);
				$(other_software_col_lg_4).append(other_software_xika);
				$(other_software_col_lg_8).append(other_software_xika2);
				$(other_software_xika).text('Phần mềm khác');
				$(other_software_xika2).text(data.other_software);						
				/* end other_software */
				/* begin medal */
				var medal_row_mia=document.createElement('div');					
				var medal_col_lg_4=document.createElement('div');
				var medal_col_lg_8=document.createElement('div');
				var medal_xika=document.createElement('div');
				var medal_xika2=document.createElement('div');
				$(medal_row_mia).addClass('row mia');
				$(medal_col_lg_4).addClass('col-lg-4');
				$(medal_col_lg_8).addClass('col-lg-8');
				$(medal_xika).addClass('xika');
				$(medal_xika2).addClass('xika2');
				$('.office_txt').append(medal_row_mia);
				$(medal_row_mia).append(medal_col_lg_4);
				$(medal_row_mia).append(medal_col_lg_8);
				$(medal_col_lg_4).append(medal_xika);
				$(medal_col_lg_8).append(medal_xika2);
				$(medal_xika).text('Thành tích');
				$(medal_xika2).append(data.medal);						
				/* end medal */
				$('.office_edit').show();
				$('.office_save').hide();			
			}			
			showMsg('note_office',data); 
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function showOfficeSave(){
	$('.office_edit').hide();
	$('.office_save').show();
}
function noSaveOffice(){
	$('.office_edit').show();
	$('.office_save').hide();		
}
function saveSkill(saved_link){
	var id = $("form[name='frm']").find("input[name='id']").val();			
	var skill_ctrl = $("form[name='frm']").find("input[name='skill_id[]']:checked");
	var source_skill_id = $.map($(skill_ctrl), function(e,i) {
		return +e.value;
	});			
	var hobby = $("form[name='frm']").find("input[name='hobby']").val();	
	var talent = $("form[name='frm']").find("textarea[name='talent']").summernote('code');	
	var token = $("form[name='frm']").find("input[name='_token']").val();
	var dataItem = new FormData();
	dataItem.append('id',id);		
	dataItem.append('source_skill_id',source_skill_id);	
	dataItem.append('hobby',hobby);	
	dataItem.append('talent',talent);	
	dataItem.append('_token',token);
	$.ajax({
		url:saved_link ,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {
			if(data.checked==1){	
				$('.skill_txt').empty();	
				/* begin skill */
				var source_profile_skill=data.source_profile_skill;
				var skill_row_mia=document.createElement('div');					
				var skill_col_lg_4=document.createElement('div');
				var skill_col_lg_8=document.createElement('div');
				var skill_xika=document.createElement('div');
				var skill_xika2=document.createElement('div');
				$(skill_row_mia).addClass('row mia');
				$(skill_col_lg_4).addClass('col-lg-4');
				$(skill_col_lg_8).addClass('col-lg-8');
				$(skill_xika).addClass('xika');
				$(skill_xika2).addClass('xika2');
				$('.skill_txt').append(skill_row_mia);
				$(skill_row_mia).append(skill_col_lg_4);
				$(skill_row_mia).append(skill_col_lg_8);
				$(skill_col_lg_4).append(skill_xika);
				$(skill_col_lg_8).append(skill_xika2);
				$(skill_xika).text('Sở trường');
				for(var i=0;i<source_profile_skill.length;i++){
					$(skill_xika2).append('<div>'+source_profile_skill[i]['fullname']+'</div>');						
				}				
				/* end skill */				
				/* begin hobby */
				var hobby_row_mia=document.createElement('div');					
				var hobby_col_lg_4=document.createElement('div');
				var hobby_col_lg_8=document.createElement('div');
				var hobby_xika=document.createElement('div');
				var hobby_xika2=document.createElement('div');
				$(hobby_row_mia).addClass('row mia');
				$(hobby_col_lg_4).addClass('col-lg-4');
				$(hobby_col_lg_8).addClass('col-lg-8');
				$(hobby_xika).addClass('xika');
				$(hobby_xika2).addClass('xika2');
				$('.skill_txt').append(hobby_row_mia);
				$(hobby_row_mia).append(hobby_col_lg_4);
				$(hobby_row_mia).append(hobby_col_lg_8);
				$(hobby_col_lg_4).append(hobby_xika);
				$(hobby_col_lg_8).append(hobby_xika2);
				$(hobby_xika).text('Sở thích');
				$(hobby_xika2).text(data.hobby);						
				/* end hobby */
				/* begin talent */
				var talent_row_mia=document.createElement('div');					
				var talent_col_lg_4=document.createElement('div');
				var talent_col_lg_8=document.createElement('div');
				var talent_xika=document.createElement('div');
				var talent_xika2=document.createElement('div');
				$(talent_row_mia).addClass('row mia');
				$(talent_col_lg_4).addClass('col-lg-4');
				$(talent_col_lg_8).addClass('col-lg-8');
				$(talent_xika).addClass('xika');
				$(talent_xika2).addClass('xika2');
				$('.skill_txt').append(talent_row_mia);
				$(talent_row_mia).append(talent_col_lg_4);
				$(talent_row_mia).append(talent_col_lg_8);
				$(talent_col_lg_4).append(talent_xika);
				$(talent_col_lg_8).append(talent_xika2);
				$(talent_xika).text('Kỹ năng đặc biệt');
				$(talent_xika2).append(data.talent);						
				/* end talent */
				$('.skill_edit').show();
				$('.skill_save').hide();			
			}			
			showMsg('note_skill',data); 
		},
		error : function (data){

		},
		beforeSend  : function(jqXHR,setting){

		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function showSkillSave(){
	$('.skill_edit').hide();
	$('.skill_save').show();
}
function noSaveSkill(){
	$('.skill_edit').show();
	$('.skill_save').hide();		
}
/* end profile-detail */
/* begin recruitment-detail */
function loginApply(logined_applied_link){
	var email=$('form[name="frm_apply"]').find('input[name="email"]').val();        
	var password=$('form[name="frm_apply"]').find('input[name="password"]').val();                  
	var recruitment_id=$('form[name="frm_apply"]').find('input[name="recruitment_id"]').val(); 
	var token=$('form[name="frm_apply"]').find('input[name="_token"]').val();                

	var dataItem = new FormData();
	dataItem.append('email',email);
	dataItem.append('password',password);                        
	dataItem.append('recruitment_id',recruitment_id);
	dataItem.append('_token',token);
	$.ajax({
		url:logined_applied_link ,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {                
			if(data.checked==1){    
				alert(data.msg.success);                      
				window.location.href = data.link_edit;                    
			}else{
				showMsg('note-apply',data);  
			}
			spinner.hide();
		},
		error : function (data){
			spinner.hide();
		},
		beforeSend  : function(jqXHR,setting){
			spinner.show();
		},
		cache: false,
		contentType: false,
		processData: false
	});
}
function loginSavedRecruitment(logined_saved_link){
	var email=$('form[name="frm_saved_recruitment"]').find('input[name="email"]').val();        
	var password=$('form[name="frm_saved_recruitment"]').find('input[name="password"]').val();                  
	var recruitment_id=$('form[name="frm_saved_recruitment"]').find('input[name="recruitment_id"]').val(); 
	var token=$('form[name="frm_saved_recruitment"]').find('input[name="_token"]').val();                

	var dataItem = new FormData();
	dataItem.append('email',email);
	dataItem.append('password',password);                        
	dataItem.append('recruitment_id',recruitment_id);
	dataItem.append('_token',token);
	$.ajax({
		url:logined_saved_link ,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) { 
			if(data.checked==1){
				alert(data.msg.success);                      
			}else{
				alert(data.msg.error);                      
			}
			window.location.href = data.link_edit;    
		},
		error : function (data){
			spinner.hide();
		},
		beforeSend  : function(jqXHR,setting){
			spinner.show();
		},
		cache: false,
		contentType: false,
		processData: false
	});
}
/* end recruitment-detail */
function changeStatusLink(id,status,link,ctrl){		
	var frm=$(ctrl).closest('form');
	var token = $(frm).find("input[name='_token']").val();   
	var dataItem={   
		'id':id,
		'status':status,         
		'_token': token
	};
	$.ajax({
		url: link,
		type: 'POST',     
		data: dataItem,
		success: function (data, status, jqXHR) {   				
			var element     = 'a#status-' + data['id'];
			var classRemove = 'publish';
			var classAdd    = 'unpublish';
			if(parseInt(data['status']) ==1){
				classRemove = 'unpublish';
				classAdd    = 'publish';
			}
			$(element).attr('onclick',data['link']);
			$(element + ' span').removeClass(classRemove).addClass(classAdd);
		},
		beforeSend  : function(jqXHR,setting){

		},
	});				
}	
function deleteImage(){
	var xac_nhan = 0;
	var msg="Bạn có muốn xóa ?";
	if(window.confirm(msg)){ 
		xac_nhan = 1;
	}
	if(xac_nhan  == 0){
		return 0;
	}
	$(".picture-area").empty();
	$("input[name='image_hidden']").val("");        
}
function uploadFile(ctrl) {
	var frm=$(ctrl).closest('form');
	$(frm).find("input[name='file_attached']").click();
}