  function uploadSummerFile(ctrl,summer_file){
  	var callback_url=$('input[name="callback_url"]').val();
	var token = $('input[name="_token"]').val();  		        
	var dataItem = new FormData();      
	dataItem.append('summer_file',summer_file);
	dataItem.append('_token',token);   
	$.ajax({
		url: callback_url,
		type: 'POST',
		data: dataItem,
		async: false,
		success: function (data) {						
			if(data.checked==1){
				$(ctrl).summernote('editor.insertImage', data.summer_url,function(summer_img){										
					$(summer_img).css('width','100%');
				});
			}else{                    
				alert(data.msg.status);                         
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

function loadSummerNote(){
	$('textarea.summer-editor').summernote({						
    		height: 500,    		    	
    		popover: {
    			image: [
    			['custom', ['imageAttributes']],
    			['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
    			['float', ['floatLeft', 'floatRight', 'floatNone']],
    			['remove', ['removeMedia']]
    			],
    		},	
    		toolbar: [    
    		['fontname'],		
    		['style', ['bold', 'italic', 'underline','strikethrough','superscript', 'subscript', 'clear']],			
    		['fontsize'],    		
    		['color'],
    		['style', ['ul', 'ol']],    		
    		['paragraph'],	    		
    		['table'],		
    		['picture'],
    		['link'],
    		['custom',['removeLinkPlugin']],
    		['video'],			
    		['hr'],
    		['undo'],
    		['redo'],
    		['fullscreen'],
    		['codeview'],			
    		['help']		
    		],
    		callbacks:{
    			onImageUpload : function(files,editor,welEditable){     				    				    				
    				for(var i = 0; i < files.length; i++) {
    					uploadSummerFile(this,files[i]);
    				}
    			}                   		
    		}
    	});
}


function checkAllAgent(cid){
	var tbl=$(cid).closest("table");	
	var checkStatus = cid.checked;
	var tbody=$(tbl).find("tbody");
	var tr=(tbody).children("tr");	
	$(tr).find(':checkbox').each(function(){
		checkWithList(this);
	});
}  
function checkAllAgentArticle(cid){
	var tbl=$(cid).closest("table");	
	var checkStatus = cid.checked;
	var tbody=$(tbl).find("tbody");
	var tr=(tbody).children("tr");	
	$(tr).find(':checkbox').each(function(){
		checkWithListArticle(this);
	});
}   
function checkAllAgentProduct(cid){
	var tbl=$(cid).closest("table");	
	var checkStatus = cid.checked;
	var tbody=$(tbl).find("tbody");
	var tr=(tbody).children("tr");	
	$(tr).find(':checkbox').each(function(){
		checkWithListProduct(this);
	});
}  
function showMsg(ctrl,data){		
	var ul='<ul>';	
	$.each(data.msg,function(index,value){
		ul+='<li>'+value+'</li>';
	});                    
	ul+='</ul>';
	var type_msg = '';
	if(data.checked == 1){
		type_msg='note-success';
	}else{
		type_msg='note-danger';
	}
	$('.'+ctrl).empty();
	$('.'+ctrl).removeClass('note-success');
	$('.'+ctrl).removeClass('note-danger');
	$('.'+ctrl).append(ul);	
	$('.'+ctrl).addClass(type_msg);                    
	$('.'+ctrl).show();     
	setTimeout(hideMsg,10000,ctrl);		 
}
function hideMsg(ctrl){
	$('.'+ctrl).fadeOut();
}      
function submitForm(url){
	$('form[name="frm"]').attr('action', url);
	$('form[name="frm"]').submit();
}
function trashForm(url){
	var xac_nhan=false;
	var msg='Bạn xác nhận có chắc chắn xóa?';
	if(window.confirm(msg)){
		xac_nhan=true;
	}
	if(xac_nhan == true){
		$('form[name="frm"]').attr('action', url);
		$('form[name="frm"]').submit();
	}
	return xac_nhan;    
}
function xacnhanxoa(){
	var msg="Bạn chắc chắn có muốn xóa ?";
    var xac_nhan=false;
	if(window.confirm(msg)){
		xac_nhan=true;
	}
	return xac_nhan;
}

function changePage(page,ctrl){
	$('input[name=filter_page]').val(page);
	var frm=$(ctrl).closest('form');
	$(frm)[0].submit();
}
function isNumberKey(evt){var hopLe=true;var charCode=(evt.which)?evt.which:event.keyCode;if(charCode>31&&(charCode<48||charCode>57))hopLe=false;return hopLe;}
function PhanCachSoTien(Ctrl) {
	var vMoney = Ctrl.value;
	vMoney = vMoney.replace(/[^\d]+/g, '');
	var vNewMoney = "";
	if (vMoney.length > 3) {
		var x = 1;
		for (var i = vMoney.length - 1; i >= 0; i--) {            
			vNewMoney = vNewMoney + "" + vMoney[i];
			if (x % 3 == 0) {
				vNewMoney = vNewMoney + ".";
			}
			x++;

		}

		var tmp = "";
		for (var i = vNewMoney.length - 1; i >= 0; i--) {
			tmp = tmp + "" + vNewMoney[i];
		}

		vNewMoney = tmp.replace(/^[\.]/g, '');
	} else {
		vNewMoney = vMoney;
	}
	Ctrl.value = vNewMoney;
}
$(document).ready(function(){
	basicTable.init();
	$('table.table-recursive > thead > tr > th > input[name="checkall-toggle"]').change(function(){		
		var checkStatus = this.checked;
		$('table.table-recursive').find(':checkbox').each(function(){
			this.checked = checkStatus;
		});
	});	
	setTimeout(hideMsg,60000,'note');	
	loadSummerNote();   
	$('.selected2').select2({
		theme: "classic"
	});
});