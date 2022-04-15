"use strict";
var AppSettings = function() {
	var tableCont;
	var mainlistTable;
	var searchvalue={};
	var initToastr = function() {
			toastr.options.showDuration = 500;
	}
	var init = function() {
		$('#settingsModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); 
			var id = button.data('id');
			var modal = $(this);
			var blockelm=modal.find('.modal-content');
			$.ajax({
					method: "POST",
					url: KTAppOptions._RF_ADMIN+"/m/settings/form",
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
					SettingsForm.init($("#settingsForm"),modal);
					SettingsForm.initRemove(id);
					setTimeout(function() {
							KTApp.unblock(blockelm);
					}, 3000);
			});
		});
	}
	var initTable = function() {
		tableCont = $('#mainlist');
		mainlistTable=tableCont.DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/settings/settingslist",
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
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
			],
			drawCallback: function(settings) {
				var api = this.api();
				var rows = api.rows({page: 'current'}).nodes();
				var last = null;

				api.column(2, {page: 'current'}).data().each(function(group, i) {
					if (last !== group) {
						$(rows).eq(i).before(
							'<tr class="group"><td colspan="7">' + group + '</td></tr>',
						);
						last = group;
					}
				});
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
		$('#m_search').on('click', function(e) {
			e.preventDefault();
			var params = {};
			$('.kt-input').each(function() {
				searchvalue[$(this).attr("name")]=$(this).val();
			});
			mainlistTable.draw();
		});

		$('#m_reset').on('click', function(e) {
			e.preventDefault();
			$('.kt-input').each(function() {
				$(this).val('');
			});
			searchvalue={};
			mainlistTable.draw();
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
	};
}();

jQuery(document).ready(function() {
	AppSettings.init();
});
var SettingsForm = function() {
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
				'class[name]': {
						required: true
				},
				'class[typeid]': {
					required: true
				},
				'class[order]': {
					required: true,
					number: true
				},
			},
			messages: {
				'class[name]':{
						required: 'Нэр хоосон байна!'
				},
				'class[typeid]':{
						required: 'Сонгогдоогүй байна!'
				},
				'class[order]': {
					required: 'Хоосон байна',
					number: 'Тоо биш байна'
				},
			},
			
			invalidHandler: function(event, validator) {
				// var alert = $('#kt_form_1_msg');
				// alert.parent().removeClass('kt-hidden');
				// KTUtil.scrollTo("kt_form_1", -200);
			}
		});    
	}
	var initForm= function(){
		form.ajaxForm({
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
							if(jsonData._refreshfull){
								AppSettings.getTable().ajax.reload();
							}else{
								AppSettings.getTable().ajax.reload( null, false );
							}
						}
						if(jsonData._refreshform){
							var def_val=$("#typeid").val();
							var def_order=$("#organorder").val();
                            form.trigger("reset");
							$("#typeid").val(def_val);
							$("#organorder").val(parseInt(def_order)+1);
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
						url: KTAppOptions._RF_ADMIN+"/process/settings/remove",
						data: { 'class[id]': id},
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
										AppSettings.getTable().ajax.reload( null, false );
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