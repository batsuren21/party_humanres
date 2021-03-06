"use strict";
var AppPosition = function() {
	var table;
	var tableCont;
	var searchvalue={};
	var selectedID;
	var selectedURL;
	var main_modal;
	var init = function() {
		tableCont=$("#mainlist");
		$('#regModal').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/form/add_position";
				var modal = $(this);
				modal.find('.modal-content').html("");
				if(id>0){
					$("#modal-backdrop").removeClass("d-none");
				}
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
					if(id>0){
						positionFormEdit.init(modal.find("form"),modal,id);
						positionFormEdit.initRemove();
					}else{
						positionFormAdd.init(modal.find("form"),modal);
					}
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
			}
		});
		$('#regModal').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$('#detailSubModal1').off('show.bs.modal');
				if(!$("#modal-backdrop").hasClass("d-none")){
					$("#modal-backdrop").addClass("d-none");
				}
			}
		});
		$('#detailModal').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/position/detail/main";
				var modal = $(this);
				var blockelm=modal.find('.modal-content');
				modal.find('.modal-content').html("");
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
					positionDetail.init(id,url,modal);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
			}
		});
		$('#detailModal').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$('#detailSubModal').off('show.bs.modal');
				if(!$("#modal-backdrop").hasClass("d-none")){
					$("#modal-backdrop").addClass("d-none");
				}
			}
		});
		initTable();
	}
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var initDetail= function(event,modal){
		var button = $(event.relatedTarget);
		var id = button.data('id');
		var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/position/detail/main";
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
			positionDetail.init(id,modal);
			setTimeout(function() {
				KTApp.unblock(blockelm);
			}, 3000);
		});
	}
	var initTable = function() {
		$("#search").find(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			todayHighlight: true,
			autoclose:true,
            orientation: "bottom left",
        });
		
		$(document).on('change', '.custom-file-input', function() {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
		
		$(document).on('change', '.ajax_select', function() {
			var url="";
			var action="";
			var param="";
			var target="";
			var selected="";
			var val_default=0;
			if($(this).data('url')) url=$(this).data('url');
			if($(this).data('action')) action=$(this).data('action');
			if($(this).data('param')) param=$(this).data('param');
			if($(this).data('target')) target=$(this).data('target');
			if($(this).data('val_default')) val_default=$(this).data('val_default');
			if(target){
				selected=$(target).data('selected');
			}	
			var value=$(this).val();
			$.ajax({
                method: "POST",
                url: url,
                data: { "action":action,"param":param,"parent_value":value,"val_selected":selected,"val_default":val_default},
            }).done(function( jsonData ) {
                if(jsonData._state){
                    if(jsonData._html){
						$(target).html(jsonData._html);
                    }else{
                    	$(target).html("");
					}
                }else toastr.error(jsonData);
            });
		});
		table=tableCont.DataTable({
			dom: "<'row'<'col-sm-3'i><'col-sm-3'l><'col-sm-6'p>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			responsive: true,
			searchDelay: 500,
			searching: false,
			//stateSave: true,
			serverSide: true,
			"lengthChange": true,
			"pageLength":50,
			"lengthMenu": [[50, 100, 150], [50, 100, 150]],
			ajax: {
				"url": KTAppOptions._RF+"/m/humanres/list/list_position",
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
						message: '?????????? ??????????',
						state: 'brand',
						opacity: 0.1,
						size: 'lg'
					});
					return $.extend( d, searchvalue );
				}
			},
			"order": [],
            "aoColumnDefs": [
            	{"aTargets": [ '_all' ],"sClass": "font-11", "bSortable": false },
            	{"aTargets": [4,5],"sClass": "font-11 text-center", "bSortable": false },
            	{"aTargets": [ 2 ],"bVisible": false }
        	],
        	
			drawCallback: function(settings) {
				if(tableCont.find("tr.group").length<1){
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="8" class="font-12"><strong>'+group+'</strong></td></tr>'
                            );
                            last = group;
                        }
                    });
                }
			},
			"language": { // language settings
				"lengthMenu": " ???????????????? _MENU_ ",
				"info": "????????: _TOTAL_",
				"infoEmpty": "???????????? ??????????????????",
				"emptyTable": "???????????? ?????????????? ??????????",
				"zeroRecords": "?????????????? ???????????? ??????????????????",
				"paginate": {
					"previous": "??????????",
					"next": "????????????",
					"last": "??????????????",
					"first": "??????????",
					"page": "????????????",
					"pageOf": " ???????? ????????????: "
				}
		},
		});
		
		$("#search").find('.kt-input').keypress(function (e) {
			 var key = e.which;
			 if(key == 13)  // the enter key code
			  {
			    $('#m_search').click();
			    return false;  
			  }
		});
		
		
		$('#m_search').on('click', function(e) {
			e.preventDefault();
			var params = {};
			$("#search").find('.kt-input').each(function() {
				searchvalue[$(this).attr("name")]=$(this).val();
			});
			table.draw();
		});

		$('#m_reset').on('click', function(e) {
			e.preventDefault();
			$("#search").find('.kt-input').each(function() {
				$(this).val('');
			});
			searchvalue={};
			table.draw();
		});
	};
	return {
		init: function() {
			initToastr();
			init();
		},
		getTable: function() {
			return table;
		},
	};
}();
jQuery(document).ready(function() {
	AppPosition.init();
});
var positionFormAdd = function () {
	// Base elements
	var wizardEl;
	var form;
	var validator;
	var wizard;
	var parentmodal;
	
	var initWizard = function () {
		wizard = new KTWizard('kt_wizard_v1', {
			startStep: 1, // initial active step number
			clickableSteps: false  // allow step clicking
		});
		wizard.on('beforeNext', function(wizardObj) {
			if (validator.form() !== true) {
				wizardObj.stop();  // don't go to the next step
			}else{
				
			}
		});
		wizard.on('beforePrev', function(wizardObj) {
//			if (validator.form() !== true) {
//				wizardObj.stop();  // don't go to the next step
//			}
		});
		wizard.on('change', function(wizard) {
			setTimeout(function() {
				KTUtil.scrollTop();
			}, 500);
		});
		if(form.find("select[name='position[PositionDepartmentID]']").length>0){
			form.on('change', 'select[name="position[PositionDepartmentID]"]', function (event) {
				var blockelm=parentmodal;
				var paramid=$(this).val();
				var changeid=$(this).data('changeid');
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/form/add_position_field",
					data: {paramid:paramid},
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
					}, 500);
				}).done(function( html ) {
					var elem=form.find('#'+changeid);
					elem.html(html);
					elem.find('.datepicker').datepicker({
						todayHighlight: true,
						autoclose:true,
			            orientation: "bottom left",
			            format: 'yyyy-mm-dd',
			        });
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				});
			});
		}
	}

	var initValidation = function() {
		validator=form.validate({
			ignore: ":hidden",
			rules: {},
			messages: {},
			invalidHandler: function(event, validator) {
				KTUtil.scrollTop();
                swal.fire({
                    "title": "", 
                    "text": "???????????????? ?????????? ?????????????? ????.", 
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary",
                    "onClose": function(e) {
                        
                    }
                });
                event.preventDefault();
            },
		}); 
	}

	var initSubmit = function() {
		var submitBtn = form.find('[data-ktwizard-type="action-submit"]');
		var submitBtnText=submitBtn.html();
		submitBtn.on('click', function(e) {
			e.preventDefault();

			if (validator.form()) {
				form.ajaxSubmit({
					dataType:  'json',
					type: 'post',
					error: function (data) {
						toastr.error("?????????? ???????????? ??????????. Err msg: "+data.responseText);
						submitBtn.html(submitBtnText);
						submitBtn.removeAttr("disabled");
					},
					beforeSubmit: function(){
						var is_valid=form.valid();
						if(!is_valid) return false;
						submitBtn.html(App.getSpinner()+" ?????????????? ??????????");
						submitBtn.attr("disabled","disabled");
						return true;
					},
					success: function(jsonData){
						if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
							toastr.success(jsonData._text);
							if(jsonData._refresh){
								if(jsonData._refreshfull){
									AppPosition.getTable().ajax.reload();
								}else{
									AppPosition.getTable().ajax.reload( null, false );
								}
							}
							if(jsonData._refreshform){
								var order=parseInt(form.find("input[name='position[PositionOrder]']").val());
								
								form.find('.resfield').each(function() {
									$(this).val('');
								});
		                        form.find("input[name='position[PositionOrder]']").val(order+1);
		                    }
						} else {
							App.showErrorValidate(jsonData,validator);
						}
						submitBtn.html(submitBtnText);
						submitBtn.removeAttr("disabled");
					}
		        });
			}
		});
	}

	return {
		init: function(par_form, par_modal) {
			wizardEl = KTUtil.get('kt_wizard_v1');
			form = par_form;
			parentmodal=par_modal;
			
			initWizard();
			initValidation();
			initSubmit();
		}
	};
}();
var positionFormEdit = function() {
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
			rules: {},
			messages: {},
			invalidHandler: function(event, validator) {
                swal.fire({
                    "title": "", 
                    "text": "???????????????? ?????????? ?????????????? ????.", 
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
		$('#detailSubModal1').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/position/detail/test";
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
					positionFormSub.init(modal.find("form"),modal,0,0);

					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
				
			}
		});
		form.find('.datepicker').datepicker({
			todayHighlight: true,
			orientation: "bottom left",
			autoclose:true,
            format: 'yyyy-mm-dd',
        });
		form.find('.ajax_select').change();
		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			data: { 'position[id]':id},
			error: function (data) {
				toastr.error("?????????? ???????????? ??????????. Err msg: "+data.responseText);
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			},
			beforeSubmit: function(){
				var is_valid=form.valid();
				if(!is_valid) return false;
				submitBtn.html(App.getSpinner()+" ?????????????? ??????????");
				submitBtn.attr("disabled","disabled");
				return true;
			},
			success: function(jsonData){
				if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
					toastr.success(jsonData._text);
					if(jsonData._refresh){
						if(jsonData._refreshfull){
							AppPosition.getTable().ajax.reload();
						}else{
							AppPosition.getTable().ajax.reload( null, false );
						}
					}
					if(jsonData._refreshform){
                        form.trigger("reset");
                    }
					if(id>0){
						positionDetail.reloadMain();
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
			swal.fire({
				title: '?????????????????? ???????????????? ?????????? ?????',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '????????!',
				cancelButtonText: '????????'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: "POST",
						dataType:  'json',
						url: KTAppOptions._RF+"/process/humanres/removeposition",
						data: { 'position[id]': id},
						beforeSend: function( xhr ) {
							removeBtn.html(App.getSpinner()+" ???????????? ??????????");
							removeBtn.attr("disabled","disabled");
						},
						error: function (data) {
							toastr.error("?????????? ???????????? ??????????. Err msg: "+data.responseText);
							removeBtn.html(removeBtnText);
							removeBtn.removeAttr("disabled");
						},
						success: function(jsonData){
							if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
								toastr.success(jsonData._text);
								parentmodal.modal('hide');
								$('#detailModal').modal("hide");
								AppPosition.getTable().ajax.reload( null, false );
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
		init: function(par_form,par_parentmodal,par_id) {
			if(par_id==0 || par_id==""){
				KTWizard1.init(par_form);
			}else{
				form=par_form;
				id=par_id;
				parentmodal=par_parentmodal;
				submitBtn=form.find('button[type="submit"]');
				submitBtnText=submitBtn.html();
				initValid(); 
				initForm(); 
			}
		},
		initRemove: function() {
			if(form!=""){
				if(form.find('#delete').length>0){
					removeBtn=form.find('#delete');
					removeBtnText=removeBtn.html();
					initRemove(); 
				}
			}
		}
	};
}();

var positionDetail= function() {
	var parentmodal;
	var id="";
	var url="";
	var init = function() {
		$('#detailSubModal').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/position/detail/test";
				var modal = $(this);
				modal.find('.modal-content').html("");
				var paramid=0;
				if(button.data('paramid')){
					paramid=button.data('paramid');
				}
				var blockelm=modal.find('.modal-content');
				$.ajax({
					method: "POST",
					url: url,
					data: { id:id,"paramid":paramid},
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
					positionFormSub.init(modal.find("form"),modal,id,paramid);
					positionFormSub.initRemove();
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
				
			}
		});
		
		$('#detailSubModal').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").addClass("d-none");
			}
		});
	}
	var reloadMain = function (){
		var blockelm=parentmodal.find('.modal-content');
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
			parentmodal.find('.modal-content').html(html);
			setTimeout(function() {
				KTApp.unblock(blockelm);
			}, 3000);
		});
	}
	
	return {
		init: function(par_id,par_url,par_parentmodal) {
			parentmodal=par_parentmodal;
			id=par_id;
			url=par_url;
			init();
		},
		reloadMain: function(){
			reloadMain();
		},

	};
}();
var positionFormSub = function() {
	var validator = {};
	var paramvalue = {};
	var form;
	var parentmodal;
	var id="";
	var paramid=0;
	var submitBtn = "";
	var submitBtnText = "";
	var removeBtn = "";
	var removeBtnText = "";
	var initValid = function() {
		validator=form.validate({
			rules: {},
			messages: {},
			invalidHandler: function(event, validator) {
                swal.fire({
                    "title": "", 
                    "text": "???????????????? ?????????? ?????????????? ????.", 
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
		form.find('.datepicker').datepicker({
			todayHighlight: true,
			orientation: "bottom left",
			autoclose:true,
            format: 'yyyy-mm-dd',
        });
		form.find('.ajax_select').change();

		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			data: {"position[id]":id,"paramid":paramid},
			error: function (data) {
				toastr.error("?????????? ???????????? ??????????. Err msg: "+data.responseText);
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			},
			beforeSubmit: function(){
				var is_valid=form.valid();
				if(!is_valid) return false;
				submitBtn.html(App.getSpinner()+" ?????????????? ??????????");
				submitBtn.attr("disabled","disabled");
				return true;
			},
			success: function(jsonData){
				if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
					toastr.success(jsonData._text);
					$('#detailSubModal').modal("hide");
					positionDetail.reloadMain();
					AppPosition.getTable().ajax.reload( null, false );
				} else {
					App.showErrorValidate(jsonData,validator);
				}
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			}
        });
	}
	var initRemove= function(){
		var url=form.find("#delete").data("url");

		form.on('click', '#delete', function() {
			if(url){
				swal.fire({
					title: '?????????????????? ???????????????? ?????????? ?????',
					type: 'warning',
					showCancelButton: true,
					confirmButtonText: '????????!',
					cancelButtonText: '????????'
				}).then(function(result) {
					if (result.value) {
						$.ajax({
							method: "POST",
							dataType:  'json',
							url: url,
							data: {"position[id]":id,"paramid":paramid},
							beforeSend: function( xhr ) {
								removeBtn.html(App.getSpinner()+" ???????????? ??????????");
								removeBtn.attr("disabled","disabled");
							},
							error: function (data) {
								toastr.error("?????????? ???????????? ??????????. Err msg: "+data.responseText);
								removeBtn.html(removeBtnText);
								removeBtn.removeAttr("disabled");
							},
							success: function(jsonData){
								if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
									toastr.success(jsonData._text);
									positionDetail.reloadMain();
									$('#detailSubModal').modal("hide");
									AppPosition.getTable().ajax.reload( null, false );
								} else {
									App.showErrorValidate(jsonData,validator);
								}
							}
						});
					}
				});
			}
		});
	}
	return {
		init: function(par_form,par_parentmodal,par_id,par_value) {
			form=par_form;
			id=par_id;
			paramid=par_value;
			
			parentmodal=par_parentmodal;
			submitBtn=form.find('button[type="submit"]');
			submitBtnText=submitBtn.html();
			initValid(); 
			initForm(); 
			
		},
		initRemove: function() {
			if(form.find('#delete').length>0){
				removeBtn=form.find('#delete');
				removeBtnText=removeBtn.html();
				initRemove(); 
			}
		},

	};
}();