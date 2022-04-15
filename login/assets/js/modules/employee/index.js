"use strict";
var AppEmployee = function() {
	var mainlistTable;
	var tableCont;
	var searchvalue={};
	var organid_selected;
	var _portletForm;
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var init = function() {
		_portletForm =$('#employeePortlet');
		searchvalue["organid"]=$("#mainorganid option:first").val();
		$(document).on("change","#mainorganid",function(){
			organid_selected=$(this).val();
			searchvalue["organid"]=organid_selected;
			mainlistTable.draw();
			$("#organtitle").html($("option:selected",this).text());

			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
				data: { "action":"department","organid":organid_selected},
			}).done(function( jsonData ) {
				if(jsonData._state){
					if(jsonData._html){
						_portletForm.find("#search_department").html(jsonData._html);
					}
				}else toastr.error(jsonData);
			});
		});
		
		$('#employeeModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); 
			var id = button.data('id');
			var modal = $(this);
			var blockelm=modal.find('.modal-content');
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/employee/form",
				data: { id:id,organid:organid_selected},
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
				employeeForm.init($("#employeeForm"),modal);
				employeeForm.initRemove(id);
				setTimeout(function() {
					KTApp.unblock(blockelm);
				}, 3000);
			});
		});
	}
	var initTable = function() {
		tableCont=$("#employeelist");
		mainlistTable=$("#employeelist").DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			stateSave: true,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/employee/employeelist",
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
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Засах" data-id="`+data+`" data-toggle="modal" data-target="#employeeModal">
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
			$('.kt-input',"#employeePortlet").each(function() {
				searchvalue[$(this).attr("name")]=$(this).val();
			});
			mainlistTable.draw();
		});

		$('#m_reset').on('click', function(e) {
			e.preventDefault();
			$('.kt-input',"#employeePortlet").each(function() {
				$(this).val('');
			});
			searchvalue={};
			searchvalue["organid"]=organid_selected;
			mainlistTable.draw();
		});
	};
	return {
		init: function() {
			init();
			initToastr();
			initTable();
			$("#mainorganid").change();
		},
		getTable: function() {
			return mainlistTable;
		},
	};
}();

jQuery(document).ready(function() {
	AppEmployee.init();
});

var employeeForm = function() {
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
				'employee[firstname]': {
						required: true
				},
				'employee[lastname]': {
						required: true
				},
				'employee[position]': {
					required: true
				},
				'employee[departmentid]': {
					required: true
				},
				'employee[order]': {
					required: true,
					number: true
				},
			},
			messages: {
				'employee[firstname]':{
						required: 'Өөрийн нэр хоосон байна!'
				},
				'employee[lastname]':{
						required: 'Овог хоосон байна!'
				},
				'employee[position]':{
						required: 'Албан тушаал хоосон байна!'
				},
				'employee[departmentid]':{
						required: 'Сонгогдоогүй байна!'
				},
				'employee[order]': {
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
		form.on('change', '#departmentid', function() {
			var $_selectedval=$(this).val();
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
				data: { "action":"employeeorder","departmentid":$_selectedval},
			}).done(function( jsonData ) {
				if(jsonData._state){
					if(jsonData._html){
						form.find("#employeeorder").val(parseInt(jsonData._html));
						
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
								AppEmployee.getTable().ajax.reload();
							}else{
								AppEmployee.getTable().ajax.reload( null, false );
							}
						}
						if(jsonData._refreshform){
							var def_val=$("#departmentid").val();
							var def_order=$("#employeeorder").val();
								form.trigger("reset");
							$("#departmentid").val(def_val);
							$("#employeeorder").val(parseInt(def_order)+1);
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
						url: KTAppOptions._RF_ADMIN+"/process/employee/remove",
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