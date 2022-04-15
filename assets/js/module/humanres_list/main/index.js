"use strict";
var AppFelonyList = function() {
	var table;
	var tableCont;
	var searchvalue={};
	var selectedID;
	var selectedURL;
	var main_modal;
	var init = function() {
		tableCont=$("#mainlist");
		$('#detailModalFelony').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/main";
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
					felonyDetail.init(id,url,modal);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
			}
		});
		$('#detailModalFelony').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$('#detailPrintModalFelony').off('show.bs.modal');
				$('#detailSubModalFelony').off('show.bs.modal');
				
			}
		});
		$('#detailSubModal').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/test";
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
					felonyFormSub.init(modal.find("form"),modal,id,paramid);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
				
			}
		});
		$('#detailSubModalFelony').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var modal = $(this);
				modal.find('.modal-content').html("");
				$('#detailPrintModalFelony').off('show.bs.modal');
				$('#detailSubModalFelony').off('show.bs.modal');
				
			}
		});
		$('#detailModalPetition').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/petition/detail/main";
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
					petitionDetail.init(id,url,modal);
					setTimeout(function() {
						KTApp.unblock(blockelm);
					}, 3000);
				});
			}
		});
		$('#detailModalPetition').on('hide.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$('#detailSubModalPetition').off('show.bs.modal');
				$('#detailPrintModalPetition').off('show.bs.modal');
				
			}
		});
		$(document).on('click', '.detailsubpage', function (event) {
			var button=$(this);
			var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/main";
			felonyDetail.setUrl(url);
			felonyDetail.reloadMain();
		});
		$(".download_excel").click(function(){
			var download_search={};
			$("#search").find('.kt-input').each(function() {
				download_search[$(this).attr("name")]=$(this).val();
			});
			
			var url = $(this).data('url')?$(this).data('url'):"";
			var title = $(this).data('title')?$(this).data('title'):"";
			
			download_search["title"]=title;
			if(url!=""){
				$.ajax({
					method: "POST",
					dataType:  'json',
					url: url,
					data:  download_search,
					beforeSend: function( xhr ) {
						KTApp.block("body", {
							overlayColor: '#000000',
							type: 'loader',
							state: 'brand',
							message: 'Татаж байна',
							opacity: 0.3,
							size: 'lg'
						});
					},
					error: function (data) {
						toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
						setTimeout(function() {
							KTApp.unblock("body");
						}, 2000);
					},
					success: function(jsonData){
						if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
							var $a = $("<a>");
						    $a.attr("href",jsonData.file);
						    $("body").append($a);
						    $a.attr("download",jsonData.filename);
						    $a[0].click();
						    $a.remove();
						} else if(jsonData.responseText){
							toastr.error("Алдаа гарсан байна. Err msg: "+jsonData.responseText);
						} else {
							toastr.error("Алдаа гарсан байна. Err msg: "+jsonData);
						}
						setTimeout(function() {
							KTApp.unblock("body");
						}, 2000);
					}
				});
			}else{
				toastr.error("Татах боломжгүй байна");
			}
		});
		initTable();
	}
	
	var initDetail= function(event,modal){
		var button = $(event.relatedTarget);
		var id = button.data('id');
		var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/main";
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
			felonyDetail.init(id,modal);
			setTimeout(function() {
				KTApp.unblock(blockelm);
			}, 3000);
		});
	}
	var initTable = function() {
		$("#mainlist").find(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true,
            todayHighlight: true,
            orientation: "bottom left",
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
			var value=$(this).val();
			if(target){
            	if($(target).find("option").length<1){
            		if($(this).data('selected')){
            			value = $(this).data('selected');
            		}
            	}
				selected=$(target).data('selected');
			}
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
		
		$('#mainlist thead tr:eq(1)').html($('#mainlist tfoot tr:eq(0)').html());
		$('#mainlist tfoot tr:eq(0)').remove();
		var url = tableCont.data('url')?tableCont.data('url'):KTAppOptions._RF+"/m/humanres_list/list/list";
		
		table=tableCont.DataTable({
			dom: "<'row'<'col-sm-3'i><'col-sm-3'l><'col-sm-6'p>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			searchDelay: 500,
			searching: false,
//			stateSave: true,
			serverSide: true,
//			"scrollX": true,
//			"scrollY": 500,
			"lengthChange": true,
			"pageLength":100,
			"lengthMenu": [[100, 150, 200], [100, 150, 200]],
			ajax: {
				"url": url,
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
            	{"aTargets": [ '_all' ],"sClass": "font-11", "bSortable": false }
        	],
			drawCallback: function(settings) {
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
		$("#search").find('.kt-input').keypress(function (e) {
			 var key = e.which;
			 if(key == 13)  // the enter key code
			  {
			    $('#m_search').click();
			    return false;  
			  }
		});
		$("#search").find('select.kt-input').on("change",function (e) {
		    $('#m_search').click();
		    return false;  
		});
		jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');
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
			init();
		},
		getTable: function() {
			return table;
		},
	};
}();
jQuery(document).ready(function() {
	AppFelonyList.init();
});
var felonyDetail= function() {
	var parentmodal;
	var id="";
	var url="";
	var init = function() {
		$('#detailPrintModalFelony').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/humanres/detail/test";
				var print = button.data('print')?button.data('print'):"def";
				console.log(print);
				var modal = $(this);
				modal.find('.modal-content').html("");
				var blockelm=modal.find('.modal-content');
				$('body').addClass('modalprinter');
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/humanres/detail/print",
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
					report.loadFile(KTAppOptions._RF+"/print/felony/"+print);
					
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
		$('#detailSubModalFelony').on('hide.bs.modal', function (event) {
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
		setUrl: function(par_url) {
			url=par_url;
		},
		reloadMain: function(){
			reloadMain();
		},

	};
}();
var petitionDetail= function() {
	var parentmodal;
	var id="";
	var url="";
	var init = function() {
		
		$('#detailPrintModalPetition').on('show.bs.modal', function (event) {
			if (event.namespace === 'bs.modal') {
				$("#modal-backdrop").removeClass("d-none");
				var button = $(event.relatedTarget); 
				var id = button.data('id');
				var url = button.data('url')?button.data('url'):KTAppOptions._RF+"/m/petition/detail/test";
				var print = button.data('print')?button.data('print'):"petition";
				var modal = $(this);
				modal.find('.modal-content').html("");
				var blockelm=modal.find('.modal-content');
				$('body').addClass('modalprinter');
				$.ajax({
					method: "POST",
					url: KTAppOptions._RF+"/m/petition/detail/print",
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
var felonyFormSub = function() {
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
			rules: {
				
			},
			messages: {
				
			},
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
		
		form.ajaxForm({
			dataType:  'json',
			type: 'post',
			data: {"felony[id]":id,"paramid":paramid},
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
					AppFelonyList.getTable().ajax.reload( null, false );
				} else {
					App.showErrorValidate(jsonData,validator);
				}
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
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
	};
}();