"use strict";
var AppDepartment = function() {
	var mainlistTable;
	var tableCont;
	var searchvalue={};
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var init = function() {
		$('#departmentModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); 
			var id = button.data('id');
			var modal = $(this);
			var blockelm=modal.find('.modal-content');
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/department/form",
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
				departmentForm.init($("#departmentForm"),modal);
				departmentForm.initRemove(id);
				setTimeout(function() {
					KTApp.unblock(blockelm);
				}, 3000);
			});
		});
	}
	var initTable = function() {
		tableCont=$("#departmentlist");
		mainlistTable=$("#departmentlist").DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			stateSave: true,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/department/departmentlist",
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
            ],
            columnDefs: [
				{
					"targets": [ 1 ],
					"visible": false,
					"searchable": false
				},{
					targets: -1,
					title: '',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Засах" data-id="`+data+`" data-toggle="modal" data-target="#departmentModal">
                          <i class="la la-edit"></i>
                        </a>`;
					},
				}
			],
			drawCallback: function(settings) {
				var api = this.api();
				var rows = api.rows({page: 'current'}).nodes();
				var last = null;
				api.column(1, {page: 'current'}).data().each(function(group, i) {
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
	AppDepartment.init();
});
var departmentForm = function() {
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
				'department[name]': {
						required: true
				},
				'department[organid]': {
					required: true
				},
				'department[order]': {
					required: true,
					number: true
				},
			},
			messages: {
				'department[name]':{
						required: 'Нэр хоосон байна!'
				},
				'department[organid]':{
						required: 'Сонгогдоогүй байна!'
				},
				'department[order]': {
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
		CKEDITOR.replace("descr", {
			filebrowserBrowseUrl      : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl      : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : KTAppOptions._RF_ADMIN+'/assets/js/demo1/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
			filebrowserWindowWidth    : '1000',
			filebrowserWindowHeight   : '700',
			height: 300
		});
		form.on('change', '#organid', function() {
			var $_selectedval=$(this).val();
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
				data: { "action":"departmentorder","organid":$_selectedval},
			}).done(function( jsonData ) {
				if(jsonData._state){
					if(jsonData._html){
						form.find("#departmentorder").val(parseInt(jsonData._html));
						
					}
				}else toastr.error(jsonData);
			});
		});
		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			error: function (data) {
				toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			},
			beforeSerialize: function(){ for(var instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement(); }, 
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
								AppDepartment.getTable().ajax.reload();
							}else{
								AppDepartment.getTable().ajax.reload( null, false );
							}
						}
						if(jsonData._refreshform){
							var def_val=$("#organid").val();
							var def_order=$("#departmentorder").val();
                            form.trigger("reset");
							$("#organid").val(def_val);
							$("#departmentorder").val(parseInt(def_order)+1);
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
						url: KTAppOptions._RF_ADMIN+"/process/department/remove",
						data: { 'department[id]': id},
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
										AppDepartment.getTable().ajax.reload( null, false );
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