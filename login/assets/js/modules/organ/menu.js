"use strict";
var AppOrgan = function() {
	var mainlistTable;
	var p_orgid;
	var tableCont;
	var searchvalue={};
	var initToastr = function() {
			toastr.options.showDuration = 500;
	}
	var init = function() {
		p_orgid=par_orgid;
		$('#organModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); 
			var id = button.data('id');
			var modal = $(this);
			var blockelm=modal.find('.modal-content');
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/organreg/formmenu",
				data: {_orgid:p_orgid,id:id},
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
					OrganForm.init($("#menuForm"),modal);
					OrganForm.initRemove(id);
					setTimeout(function() {
							KTApp.unblock(blockelm);
					}, 3000);
			});
		});
	}
	var initTable = function() {
		tableCont=$("#organlist");
		searchvalue['_orgid']=p_orgid;
		mainlistTable=$("#organlist").DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			stateSave: true,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/organreg/menulist",
				"dataSrc": function(res) {
					setTimeout(function() {
						KTApp.unblock(tableCont);
					}, 1000);
					if (res.customActionMessage) {
						if(res.customActionStatus == 'OK'){
							toastr.success(res.customActionMessage);
						}else{
							toastr.error(res.customActionMessage);
						}
					}
					return res.data;
				},
				"data": function ( d ) {
					KTApp.block(tableCont, {
						type: 'loader',
						message: 'Уншиж байна',
						state: 'brand',
						opacity: 0.1,
						size: 'lg'
					});
					return $.extend( {}, d, searchvalue );
				}
			},
			"aoColumns": [
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
            ],
            columnDefs: [
			],
			"language": { // language settings
				"lengthMenu": " Хуудсанд _MENU_ ",
				"info": "Нийт: _TOTAL_",
				"infoEmpty": "Бичлэг олдсонгүй",
				"emptyTable": "Бичлэг байхгүй байна",
				"zeroRecords": "Тохирох бичлэг олдсонгүй",
				"paginate": {
					"previous": "Өмнөх",
					"next": "Дараах",
					"last": "Сүүлийн",
					"first": "Эхний",
					"page": "Хуудас",
					"pageOf": " Нийт хуудас: "
				}
		},
		}); 
	};
	return {
		init: function() {
			init();
			initToastr();
			initTable();
		},
		getTable: function() {
			return mainlistTable;
		},
		getOrgID: function() {
			return p_orgid;
		},
	};
}();

jQuery(document).ready(function() {
	AppOrgan.init();
});
var OrganForm = function() {
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
			rules: {
                'menu[typeid]': {
                    required: true
                },
                'menu[title]': {
                    required: true
                },
                'menu[link]': {
                    required: true
                },
                'menu[order]': {
                    required: true,
                    number: true
                },
            },
            messages: {
                'menu[typeid]':{
                    required: 'Модуль сонгогдоогүй байна!'
                },
                'menu[title]':{
                    required: 'Нэр хоосон байна!'
                },
                'menu[link]': {
                    required: 'URL хоосон байна!'
                },
                'menu[order]': {
                    required: 'Эрэмбэ хоосон байна!',
                    number: 'Эрэмбэ тоо биш байна!'
                },
            },
			
			invalidHandler: function(event, validator) {
			}
		});    
	}
	var initForm= function(){
		form.on('change', '#menumodule', function() {
            var $_selectedval=$(this).val();
            if($(this).val()=="5") {
                form.find("#menulinkrow").removeClass("kt-hide");
                form.find("#menulink").removeAttr("disabled");
            }else{
                form.find("#menulinkrow").addClass("kt-hide");
                form.find("#menulink").attr("disabled","disabled");
            }
            if($(this).val()=="10") {
                form.find("#menulisttyperow").removeClass("kt-hide");
                form.find("#menulisttype").removeAttr("disabled");
            }else{
                form.find("#menulisttyperow").addClass("kt-hide");
                form.find("#menulisttype").attr("disabled","disabled");
            }
            
            $.ajax({
                method: "POST",
                url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
                data: { "action":"moduleclass","module_selected":$_selectedval,"val_selected":form.find("#menuarticleclassid").data("selected")},
            }).done(function( jsonData ) {
                if(jsonData._state){
                    if(jsonData._html){
                        if(jsonData._html!=""){
                            form.find("#menuarticleclassrow").removeClass("kt-hide");
                            form.find("#menuarticleclassid").removeAttr("disabled");
                            form.find("#menuarticleclassid").html(jsonData._html);
                        } else{
                            form.find("#menuarticleclassrow").addClass("kt-hide");
                            form.find("#menuarticleclassid").attr("disabled","disabled");
                        }
                    }else{
                        form.find("#menuarticleclassrow").addClass("kt-hide");
                        form.find("#menuarticleclassid").attr("disabled","disabled");
                    }
                }else {
                    toastr.error(jsonData);
                    form.find("#menuarticleclassrow").addClass("kt-hide");
                    form.find("#menuarticleclassid").attr("disabled","disabled");
                }
            });
		});
		form.ajaxForm({
            dataType:  'json',
			type: 'post',
			data: { "menu[organid]":AppOrgan.getOrgID()},
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
					AppOrgan.getTable().ajax.reload( null, false );
                    if(jsonData._refreshform){
                        var def_val=$("#menumodule").val();
                        var def_order=$("#menuorder").val();
                        form.trigger("reset");
                        $("#menumodule").val(def_val);
                        $("#menuorder").val(parseInt(def_order)+1);
                        form.find("#menumodule").change();
                    }
                } else {
                    App.showErrorValidate(jsonData,validator);
                }
                submitBtn.html(submitBtnText);
                submitBtn.removeAttr("disabled");
            }
        });
	}
	var initRemove= function(){
		form.on('click', '#delete', function() {
			swal({
				title: 'Устгахдаа итгэлтэй байна уу?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Тийм!',
				cancelButtonText: 'Үгүй'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: "POST",
						dataType:  'json',
						url: KTAppOptions._RF_ADMIN+"/process/menu/remove",
                        data: { 'menu[id]': id},
						beforeSend: function( xhr ) {
								removeBtn.html(App.getSpinner()+" устгаж байна");
								removeBtn.attr("disabled","disabled");
						},
						error: function (data) {
								toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
								removeBtn.html(removeBtnText);
								removeBtn.removeAttr("disabled");
						},
						success: function(jsonData){
								if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
										toastr.success(jsonData._text);
										parentmodal.modal('hide');
										AppOrgan.getTable().ajax.reload( null, false );
								} else {
										App.showErrorValidate(jsonData,validator);
								}
						}
					});
				}
			});
		});
	}
	return {
			init: function(par_form,par_parentmodal) {
					form=par_form;
					parentmodal=par_parentmodal;
					submitBtn=form.find('button[type="submit"]');
					submitBtnText=submitBtn.html();
					initValid(); 
					initForm(); 
			},
			initRemove: function(par_id) {
					id=par_id;
					if(form.find('#delete').length>0){
						removeBtn=form.find('#delete');
						removeBtnText=removeBtn.html();
						initRemove(); 
					}
			}
	};
}();