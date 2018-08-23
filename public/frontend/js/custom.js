function checkAllAgent(cid){
	var tbl=$(cid).closest("table");	
	var checkStatus = cid.checked;
	var tbody=$(tbl).find("tbody");
	var tr=(tbody).children("tr");	
	$(tr).find(':checkbox').each(function(){
		checkWithList(this);
	});
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
function xacnhanxoa(){
	var msg="Bạn chắc chắn có muốn xóa ?";
    var xac_nhan=false;
	if(window.confirm(msg)){
		xac_nhan=true;
	}
	return xac_nhan;
}
function showMsg(ctrl,data){		
	var ul='<ul>';	
	$.each(data.msg,function(index,value){
		ul+='<li>'+value+'</li>';
	});                    
	ul+='</ul>';
	var type_msg = '';
	if(parseInt(data.checked) == 1){
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
	setTimeout(hideMsg,60000,ctrl);		 
}
function hideMsg(ctrl){
    $('.'+ctrl).fadeOut();
}       
function loadSummerNote(){
	$('textarea.summer-editor').summernote({						
    		height: 300,    		    	
    		
    		toolbar: [        		
    		['style', ['ul', 'ol']],    		
    		['link'],
    		['codeview']
    		]
    		
    	});
}

$(document).ready(function(){	
	basicTable.init();		
	setTimeout(hideMsg,60000,'note');	
	loadSummerNote();
	$('.selected2').select2({
		theme: "classic"
	});
	$( 'input[name="duration"]' ).datepicker({
			dateFormat: "dd/mm/yy",
			defaultDate: "+3d",
			changeYear: true,
			changeMonth: true,
			yearRange: "1975:3000"
		});	
});