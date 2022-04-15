"use strict";
var AppEmployee = function() {
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
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/form/add_employee";
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
						employeeFormEdit.init(modal.find("form"),modal,id);
						employeeFormEdit.initRemove();
					}else{
						employeeFormAdd.init(modal.find("form"),modal);
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
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/employee/detail/main";
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
					employeeDetail.init(id,url,modal);
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
		
		$(document).on('click', '.detailsubpage', function (event) {
			var button=$(this);
			var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/employee/main";
			employeeDetail.setUrl(url);
			employeeDetail.reloadMain();
			
		});
		initTable();
	}
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var initDetail= function(event,modal){
		var button = $(event.relatedTarget);
		var id = button.data('id');
		var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/employee/detail/main";
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
			employeeDetail.init(id,modal);
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
                async: false,
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
		$(document).on("change","#changeEmployeeState",function(){
			console.log(employeeDetail.getID());
			var id=employeeDetail.getID();
			var blockelm=employeeDetail.getModal().find('.modal-content');
			var edit=1;
			if(!$(this).prop("checked")){
				edit=0;
			}
			$.ajax({
				method: "POST",
				dataType:  'json',
				url: KTAppOptions._RF+"/process/humanres/editperson",
				data: {"editparam[id]":id,'person[PersonIsEditable]':edit},
				beforeSend: function( xhr ) {
					KTApp.block(blockelm, {
						overlayColor: '#ffffff',
						type: 'loader',
						state: 'brand',
						opacity: 0.3,
						size: 'lg'
					});
				},
				error: function (data) {
					toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				},
				success: function(jsonData){
					if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
						toastr.success("Төлвийг амжилттай өөрчиллөө");
						if($("#mainlist").length>0){
							AppEmployee.getTable().ajax.reload( null, false );
						}
					}
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				}
			});
			
		});
		if(tableCont.length>0){
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
					"url": KTAppOptions._RF+"/m/humanres/list/list_employee",
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
		}
		
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
	AppEmployee.init();
});
var employeeFormAdd = function () {
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
		form.find('.datepicker').datepicker({
			todayHighlight: true,
			orientation: "bottom left",
			autoclose:true,
            format: 'yyyy-mm-dd',
        });
		form.find('.ajax_select').change();
		form.on('click', '#checkregister', function (event) {
			var blockelm=parentmodal;
			var register=form.find("input[name='person[PersonRegisterNumber]']").val();
			if(register==""){
				toastr.error("Та шалгах регистрийн дугаар оруулна уу");
				return false;
			}
			var url=$(this).data("url")?KTAppOptions._RF+"/m/humanres/form/"+$(this).data("url"):KTAppOptions._RF+"/m/humanres/form/add_employee_field";
			$.ajax({
				method: "POST",
				url: url,
				data: {register:register},
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
				var field=form.find('#mainfield');
				field.html(html);
				var nextBtn = form.find('[data-ktwizard-type="action-next"]');
				
				if(field.find("input[name='employee[EmployeeID]']").length<1){
					nextBtn.removeClass("d-none");
				}else{
					nextBtn.addClass("d-none");					
				}
				form.find('.datepicker').datepicker({
					todayHighlight: true,
					orientation: "bottom left",
					autoclose:true,
		            format: 'yyyy-mm-dd',
		        });
				var avatar = new KTAvatar('kt_user_avatar');
				
				setTimeout(function() {
					KTApp.unblock(blockelm);
					blockelm.focus();
				}, 1000);
			});
		});
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
							if(jsonData._refresh){
								if($("#mainlist").length>0){
									if(jsonData._refreshfull){
										AppEmployee.getTable().ajax.reload();
									}else{
										AppEmployee.getTable().ajax.reload( null, false );
									}
								}
							}
							if(jsonData._refreshform){
								form.find("#mainfield").html("");
								form.find('.resfield').each(function() {
									$(this).val('');
								});
								var beforeBtn = form.find('[data-ktwizard-type="action-prev"]');
								beforeBtn.click();
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
var employeeFormEdit = function() {
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
		var avatar = new KTAvatar('kt_user_avatar');
	
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
			data: { 'editparam[id]':id},
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
					if(jsonData._refresh){
						if($("#mainlist").length>0){
							if(jsonData._refreshfull){
								AppEmployee.getTable().ajax.reload();
							}else{
								AppEmployee.getTable().ajax.reload( null, false );
							}
						}
					}
					if(jsonData._refreshform){
                        form.trigger("reset");
                    }
					if(id>0){
						employeeDetail.reloadMain();
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
						url: KTAppOptions._RF+"/process/humanres/removeemployee",
						data: { 'employee[id]': id},
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
								$('#detailModal').modal("hide");
								AppEmployee.getTable().ajax.reload( null, false );
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

var employeeDetail= function() {
	var parentmodal;
	var id="";
	var url="";
	var init = function() {
		
		
		$('#detailSubModal').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/employee/detail/test";
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
					employeeFormSub.init(modal.find("form"),modal,id,paramid);
					employeeFormSub.initRemove();
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
				
			}
		});
		$('#detailPrintModalHumanres').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/test";
				var print = button.data('print')?button.data('print'):"humanres";
				var modal = $(this);
				modal.find('.modal-content').html("");
				var blockelm=modal.find('.modal-content');
				$('body').addClass('modalprinter');
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/detail/print",
					data: { id:id,my:1},
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
					Stimulsoft.Base.StiLicense.key =
						"6vJhGtLLLz2GNviWmUTrhSqnOItdDwjBylQzQcAOiHl+frPmrtRTu7G3lOXSiLetMDBzrT56tP84t8Oj" +
						"NUfqbTb2q0S7Z98NNXOEHFgmXBCxlOvTlo1h8l00da/HEp6ZwdSjki1DiHNVVhXx/dVyl/Kq+2GK42dz" +
						"3wTC49MmkrtZjZEA1ETwdW8meIqQl4qX20A3Q/fDB/yS0e1XRKGgCf4k9l1oJHEjK/tnoOYV1NIfbBnG" +
						"KI101HA/b1bQM/mJ6ZkPdLzZ/ti1WrR7gbeWEJ5u6ABh/tdPc2o38YZ2pEijX0Xi5N2GLRgPL2Jyp+YW" +
						"pEad0fTJXfoxFUYNiyxB5FZ/GZI+XchXXg+hB5WmUJWMkv2GU21w8vV3DLtaD64IwaRncwseUDFZ+sYj" +
						"XDqdVo8KNufLmbdX5YzS49B5AYlGve9oZigL2laGMvW1DkbDx3+wvURdRzjZGhxqboXJOjnZ55ZjTjKm" +
						"90n+ps7Vl+nCn3LJfHZcgQdJJ1KLlPPK1Oomqc7iz+MyQ3nq4R0JsjO4qk20cQ+A78S/QkS4GbYqdIqs" +
						"GIfxGP/rr2GmcXBv";

					var report = new Stimulsoft.Report.StiReport();
					report.loadFile(KTAppOptions._RF+"/print/"+print);
					
					report.dictionary.databases.clear();

					var dsJSON = new Stimulsoft.System.Data.DataSet();
					dsJSON.readJsonFile(url);
					
					report.regData("JSON", null, dsJSON);

					var options = new Stimulsoft.Viewer.StiViewerOptions();
					var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
					viewer.report = report;
					viewer.renderHtml("viewerContent");
				});
				
			}
		}).on('hide.bs.modal', function (event) {
			var modal = $(this);
			modal.find('.modal-content').html("");
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").addClass("d-none");
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
		getID: function(){
			return id;
		},
		getModal: function (){
			return parentmodal;
		},
		reloadMain: function(){
			reloadMain();
		},
		setUrl: function(par_url) {
			url=par_url;
		},
		reloadMain: function(){
			reloadMain();
		},
	};
}();
var employeeFormSub = function() {
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
			ignore: ":hidden",
			rules: {},
			messages: {},
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
		form.find('.datepicker').datepicker({
			todayHighlight: true,
			orientation: "bottom left",
			autoclose:true,
            format: 'yyyy-mm-dd',
        });
		form.find('.ajax_select').change();
		if(form.find("select[name='family[FamilyBirthIsAbroad]']").length>0){
			form.on('change', "select[name='family[FamilyBirthIsAbroad]']", function (event) {
				if($(this).val()==1){
					if($("#isbornabroad").hasClass("d-none")){
						$("#isbornabroad").removeClass("d-none");
						$("#isbornabroad").find(".form-control").removeAttr("disabled");
					}
					$("#isbornabroad1").addClass("d-none");
					$("#isbornabroad1").find(".form-control").attr("disabled","disabled");
				}else{
					if($("#isbornabroad1").hasClass("d-none")){
						$("#isbornabroad1").removeClass("d-none");
						$("#isbornabroad1").find(".form-control").removeAttr("disabled");
					}
					$("#isbornabroad").addClass("d-none");
					$("#isbornabroad").find(".form-control").attr("disabled","disabled");
				}
			});
		}
		if(form.find("select[name='award[AwardRefID]']").length>0){
			form.on('change', 'select[name="award[AwardRefID]"]', function (event) {
				var blockelm=$('#detailSubModal');
				var parentid=$(this).val();
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/formemployee/award_field",
					data: {id:id ,parentid:parentid,"paramid":paramid},
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
					form.find('#awardfield').html(html);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				});
			});
		}
		if(form.find("select[name='job[JobOrganTypeID]']").length>0){
			form.on('change', 'select[name="job[JobOrganTypeID]"]', function (event) {
				var isnow=form.find('input:radio[name="job[JobIsNow]"]:checked').val();
				
				var blockelm=$('#detailSubModal');
				var typeid=$(this).val();
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/formemployee/job_field",
					data: {id:id ,typeid:typeid,"paramid":paramid,"isnow":isnow},
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
					form.find('#jobfield').html(html);
					form.find('.datepicker').datepicker({
						todayHighlight: true,
						orientation: "bottom left",
						autoclose:true,
			            format: 'yyyy-mm-dd',
			        });
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				});
			});
			form.on('change', 'select[name="job[JobOrganID]"]', function (event) {
				if($(this).val()!=6){
					form.find("#_startdate").html("Ажилд орсон огноо:");
					form.find("#_enddate").html("Ажлаас гарсан огноо:");
					form.find(".notorgan").removeClass("d-none");
					form.find(".notorgan").find(".form-control").removeAttr("disabled");
				}else{
					form.find("#_startdate").html("Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож эхэлсэн огноо:");
					form.find("#_enddate").html("Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож дууссан огноо:");
					form.find(".notorgan").addClass("d-none");
					$(".notorgan").find(".form-control").attr("disabled","disabled");
				}
			});
			form.on('change', '#joborganid', function (event) {
				if($(this).val()==1){
					form.find("#type1").removeClass("d-none");
					form.find("#type1").find(".form-control").removeAttr("disabled");
					form.find("#typeOther").addClass("d-none");
					form.find("#typeOther").find(".form-control").attr("disabled","disabled");
				}else{
					form.find("#typeOther").removeClass("d-none");
					form.find("#typeOther").find(".form-control").removeAttr("disabled");
					form.find("#type1").addClass("d-none");
					form.find("#type1").find(".form-control").attr("disabled","disabled");
				}
			});
		}
			
		form.on('change', 'input:radio[name="job[JobIsNow]"]', function (event) {
			if($(this).val()==0){
				if($("#formIsNow").hasClass("d-none")){
					$("#formIsNow").removeClass("d-none");
					$("#formIsNow").find(".form-control").removeAttr("disabled");
				}
			}else{
				form.find("#_startdate").html("Ажилд орсон огноо:");
				form.find("#_enddate").html("Ажлаас гарсан огноо:");
				form.find(".notorgan").removeClass("d-none");
				form.find(".notorgan").find(".form-control").removeAttr("disabled");
				form.find('select[name="job[JobOrganID]"]').val("");
				if(!$("#formIsNow").hasClass("d-none")){
					$("#formIsNow").addClass("d-none");
					$("#formIsNow").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		form.on('change', 'input:radio[name="education[EducationIsNow]"]', function (event) {
			if($(this).val()==0){
				if($("#formIsNow").hasClass("d-none")){
					$("#formIsNow").removeClass("d-none");
					$("#formIsNow").find(".form-control").removeAttr("disabled");
				}
			}else{
				if(!$("#formIsNow").hasClass("d-none")){
					$("#formIsNow").addClass("d-none");
					$("#formIsNow").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		form.on('change', 'input:radio[name="person[PersonIsSoldiering]"]', function (event) {
			if($(this).val()==1){
				if($("#soldierfield").hasClass("d-none")){
					$("#soldierfield").removeClass("d-none");
					$("#soldierfield").find(".form-control").removeAttr("disabled");
				}
			}else{
				if(!$("#soldierfield").hasClass("d-none")){
					$("#soldierfield").addClass("d-none");
					$("#soldierfield").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		if(form.find("select[name='education[EducationLevelID]']").length>0){
			form.on('change', 'select[name="education[EducationLevelID]"]', function (event) {
				var val=$(this).val();
				if(val>5){
					$("#edulevel").removeClass("d-none");
					$("#edulevel").find(".form-control").removeAttr("disabled");
					$("#eduprof").removeClass("d-none");
					$("#eduprof").find(".form-control").removeAttr("disabled");
				}else{
					$("#edulevel").addClass("d-none");
					$("#edulevel").find(".form-control").attr("disabled","disabled");
					$("#eduprof").addClass("d-none");
					$("#eduprof").find(".form-control").attr("disabled","disabled");
				}
			});
		}
		form.on('change', 'select[name="punishment[PunishmentRefID]"]', function (event) {
			if($(this).val()==2){
				if($("#formIsNow").hasClass("d-none")){
					$("#formIsNow").removeClass("d-none");
					$("#formIsNow").find(".form-control").removeAttr("disabled");
				}
			}else{
				if(!$("#formIsNow").hasClass("d-none")){
					$("#formIsNow").addClass("d-none");
					$("#formIsNow").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		form.on('change', 'select[name="family[FamilyJobTypeID]"]', function (event) {
			if($(this).val()<6){
				if($("#formJobType").hasClass("d-none")){
					$("#formJobType").removeClass("d-none");
					$("#formJobType").find(".form-control").removeAttr("disabled");
				}
			}else{
				if(!$("#formJobType").hasClass("d-none")){
					$("#formJobType").addClass("d-none");
					$("#formJobType").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		form.on('change', 'select[name="relation[RelationJobTypeID]"]', function (event) {
			if($(this).val()<6){
				if($("#formJobType").hasClass("d-none")){
					$("#formJobType").removeClass("d-none");
					$("#formJobType").find(".form-control").removeAttr("disabled");
				}
			}else{
				if(!$("#formJobType").hasClass("d-none")){
					$("#formJobType").addClass("d-none");
					$("#formJobType").find(".form-control").attr("disabled","disabled");
				}
			}
		});
		if(form.find("select[name='study[StudyDirectionID]']").length>0){
			form.on('change', 'select[name="study[StudyDirectionID]"]', function (event) {
				var blockelm=$('#detailSubModal');
				var directionid=$(this).val();
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/formemployee/study_field",
					data: {id:id ,directionid:directionid,"paramid":paramid},
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
					form.find('#studyfield').html(html);
					form.find('.datepicker').datepicker({
						todayHighlight: true,
						orientation: "bottom left",
						autoclose:true,
			            format: 'yyyy-mm-dd',
			        });
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 1000);
				});
			});
		}
		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			data: {"editparam[id]":id,"paramid":paramid},
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
					$('#detailSubModal').modal("hide");
					employeeDetail.reloadMain();
					AppEmployee.getTable().ajax.reload( null, false );
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
					title: 'Цуцлахдаа итгэлтэй байна уу?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Тийм!',
					cancelButtonText: 'Үгүй'
				}).then(function(result) {
					if (result.value) {
						$.ajax({
							method: "POST",
							dataType:  'json',
							url: url,
							data: {"editparam[id]":id,"paramid":paramid},
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
									employeeDetail.reloadMain();
									$('#detailSubModal').modal("hide");
									AppEmployee.getTable().ajax.reload( null, false );
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