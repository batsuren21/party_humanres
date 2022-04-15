"use strict";
var App = function() {
    var _spinner='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    var initToastr = function() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }
    var init =function(){
    	$('#regModalProfile').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/petition/form/add";
				var modal = $(this);
				modal.find('.modal-content').html("");
				var blockelm=modal.find('.modal-content');
				$.ajax({
					method: "POST",
					url: url,
					data: { id:id},
					beforeSend: function( xhr ) {
						KTApp.block(blockelm, {
							overlayColor: '#ffffff',
							type: 'loader',
							state: 'brand',
							opacity: 0.3,
							size: 'lg'
						});
					}
				}).fail(function( html ) {
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				}).done(function( html ) {
					modal.find('.modal-content').html(html);
					_profileForm.init(modal.find("form"),modal,id);
					
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
			}
		});
    }
    return {
        init: function() {
        	init();
            App.initToastr();
        },
        getSpinner: function(){
            return _spinner;
        },
        initToastr: function() {
            initToastr();
        },
        showErrorValidate: function(jsonData, validator) {
            if(typeof jsonData === 'undefined' || jsonData!="" && typeof jsonData._errors === 'undefined'){
                toastr.error(jsonData);
            }else{
                var isshow=0;
                var error_list=[];
                var error_str="";
                if(typeof jsonData._errors.field !== 'undefined' && jsonData._errors.field!=""){
                    if(jsonData._errors.field.length>0) isshow=1;
                    for(var j=0; j<jsonData._errors.field.length; j++){
                        error_list[jsonData._errors.field[j]._field]=jsonData._errors.field[j]._text;                                
                    }
                }
                if(typeof jsonData._errors.general !== 'undefined' && jsonData._errors.general!=""){
                    if(jsonData._errors.general.length>0) isshow=1;
                    for(var j=0; j<jsonData._errors.general.length; j++){
                        error_str+="<li> "+jsonData._errors.general[j]._text+"</li>";
                    }
                }
                if(typeof validator === 'undefined' || validator!=""){
                    validator.showErrors(error_list);
                }
                if(error_str!=""){
                    toastr.error("<ul>"+error_str+"</ul>");
                }
                if(!isshow) toastr.error("Алдаа гарсан байна");
            }
        },
    };
}();
$(document).ready(function() {
    App.init();
});
var _profileForm = function() {
	var validator = {};
	var form;
	var parentmodal;
	var id="";
	var submitBtn = "";
	var submitBtnText = "";
	var removeBtn = "";
	var removeBtnText = "";
	var initValid = function() {
		validator=form.validate({
			
			invalidHandler: function(event, validator) {
                swal.fire({
                    "title": "", 
                    "text": "Талбарыг бүрэн бөглөнө үү.", 
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary",
                    "onClose": function(e) {
                        
                    }
                });
                event.preventDefault();
            },
		});

	}
	var initForm= function(){
		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			data: { 'id':id},
			error: function (data) {
				toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			},
			beforeSubmit: function(){
				var is_valid=form.valid();
				if(!is_valid) return false;
				submitBtn.html(App.getSpinner()+" хадгалж байна");
				submitBtn.attr("disabled","disabled");
				return true;
			},
			success: function(jsonData){
				if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
					toastr.success(jsonData._text);
					$('#regModalProfile').modal("hide");
				} else {
					App.showErrorValidate(jsonData,validator);
				}
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			}
        });
	}
	return {
		init: function(par_form,par_parentmodal,par_id) {
			form=par_form;
			id=par_id;
			parentmodal=par_parentmodal;
			submitBtn=form.find('button[type="submit"]');
			submitBtnText=submitBtn.html();
			initValid(); 
			initForm(); 
			
		},
	
	};
}();