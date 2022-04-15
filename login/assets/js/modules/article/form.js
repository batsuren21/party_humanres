"use strict";
var AppArticleForm = function () {
	var validator = {};
	var form;
	var menuid = "";
	var id = "";
	var formEditor = "";
	var submitBtn = "";
	var submitBtnText = "";
	var removeBtn = "";
	var removeBtnText = "";
	var init = function () {
		menuid=$("#menuid").val();
		$('#date').datepicker({
			todayBtn: "linked",
			format: 'yyyy-mm-dd',
            clearBtn: true,
            todayHighlight: true,
            templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		});
		$('#datelaw').datepicker({
			todayBtn: "linked",
			format: 'yyyy-mm-dd',
            clearBtn: true,
            todayHighlight: true,
            templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		});
		form.on('change', '#isspec', function() {
            if($(this).val()==1){
				$("#imagesourcespecrow",form).removeClass("kt-hide");
			}else{
				$("#imagesourcespecrow",form).addClass("kt-hide");
			} 
		});
		$('.editor').each(function(e){
			CKEDITOR.replace(this.id, {
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
		});
	}
	var initValid = function () {
		validator = form.validate({
			rules: {
				'article[classid]': {
					required: true
				},
				'article[typeid]': {
					required: true
				},
				'article[title]': {
					required: true
				},
				'article[intro]': {
					required: true
				},
				'article[number]': {
					required: true
				},
				'article[date]': {
					required: true
				},
				'article[datelaw]': {
					required: true
				},
				'article[year]': {
					required: true,
					number: true
				},
				'article[month]': {
					required: true,
				},
			},
			messages: {
				'article[classid]': {
					required: 'Сонгоогүй байна'
				},
				'article[typeid]': {
					required: 'Сонгоогүй байна'
				},
				'article[title]': {
					required: 'Хоосон байна'
				},
				'article[intro]': {
					required: 'Хоосон байна'
				},
				'article[number]': {
					required: 'Хоосон байна'
				},
				'article[date]': {
					required: 'Хоосон байна'
				},
				'article[datelaw]': {
					required: 'Хоосон байна'
				},
				'article[year]': {
					required: 'Хоосон байна',
					number: 'Тоо биш байна',
				},
				'article[month]': {
					required: 'Сонгоогүй байна',
				},
			},
		});
		if($("#organid").data("required"))
			$("select[id=organid]").rules("add", "required");
	}
	var initForm = function () {
		var image = new KTAvatar('imagesource');
        var cloneImageSource=$("#imagesource").clone();
		var imagespec = new KTAvatar('imagesourcespec');
		var cloneImageSpecSource=$("#imagesourcespec").clone();
		if($("#employeeids").length>0){
			form.on('change', '#organid', function() {
				var orgid=$(this).val();
				$.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
                    data: { "action":"employee","organid":orgid,"val_selected":$("#employeeids").data("selected"),"val_default":-1},
                }).done(function( jsonData ) {
                    if(jsonData._state){
                        if(jsonData._html){
							form.find("#employeeids").html(jsonData._html);
                        }else{
							form.find("#employeeids").html("");
						}
                    }else toastr.error(jsonData);
                });
			});
			$("#organid").change();
		}
		form.ajaxForm({
			dataType: 'json',
			data: {"article[menuid]":menuid},
			type: 'post',
			error: function (data) {
				toastr.error("Алдаа гарсан байна. Err msg: " + data.responseText);
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			},
			beforeSerialize: function(){ for(var instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement(); }, 
			beforeSubmit: function () {
				var is_valid = form.valid();
				if (!is_valid) return false;
				submitBtn.html(App.getSpinner() + " хадгалж байна");
				submitBtn.attr("disabled", "disabled");
				return true;
			},
			success: function (jsonData) {
				if (typeof jsonData !== 'undefined' && jsonData != "" && jsonData._state) {
					toastr.success(jsonData._text);
					if(jsonData._id){
						if(jsonData._loadattach){
							swal({
								title: 'Та хавсралт файл нэмэх үү?',
								type: 'success',
								showCancelButton: true,
								confirmButtonText: 'Тийм!',
								cancelButtonText: 'Үгүй'
							}).then(function (result) {
								if(result.value){
									window.location=KTAppOptions._RF_ADMIN+"/"+menuid+"/edit/"+jsonData._id;
								}else if(result.dismiss == 'cancel'){
									if (jsonData._refreshform) {
										swal({
											title: 'Та дахин бичлэг нэмэх үү?',
											type: 'success',
											showCancelButton: true,
											confirmButtonText: 'Тийм!',
											cancelButtonText: 'Үгүй'
										}).then(function (result) {
											if(result.value){
												var val_classid = $("#classid").val();
												var val_typeid = $("#typeid").val();
												var val_organid = $("#organid").val();
												form.trigger("reset");
												$("#imagesource").replaceWith(cloneImageSource);
												$("#imagesourcespec").replaceWith(cloneImageSpecSource);
												var avatar1 = new KTAvatar('imagesource');
												var avatar2 = new KTAvatar('imagesourcespec');
												cloneImageSource=$("#imagesource").clone();
												cloneImageSpecSource=$("#imagesourcespec").clone();
												$("#classid").val(val_classid);
												$("#typeid").val(val_typeid);
												$("#organid").val(val_organid);
												$('#isspec',form).change();
												// formEditor.setData( '' );
												initAttach(0);
												for(var instance in CKEDITOR.instances) 
													CKEDITOR.instances[instance].setData('');
											}else if(result.dismiss == 'cancel'){
												window.location=$("#backbtn").attr("href");
											}
										});
									}
								}
							});	
						}
					}
					
					if(jsonData._img){
						$("#imagesource").addClass("kt-hide");
						$("#imagesourcermv").find(".kt-avatar__holder").css("background-image","url("+jsonData._img+")");
						$("#imagesourcermv").removeClass("kt-hide");
					}
					if(jsonData._img_spec){
						$("#imagesourcespec").addClass("kt-hide");
						$("#imagesourcespecrmv").find(".kt-avatar__holder").css("background-image","url("+jsonData._img_spec+")");
						$("#imagesourcespecrmv").removeClass("kt-hide");
					}
				} else {
					App.showErrorValidate(jsonData, validator);
				}
				submitBtn.html(submitBtnText);
				submitBtn.removeAttr("disabled");
			}
		});
		form.on('click', '#removephoto', function() {
			swal({
				title: 'Зураг устгахдаа итгэлтэй байна уу?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Тийм!',
				cancelButtonText: 'Үгүй'
			}).then(function(result) {
				if (result.value) {
                    var blockelm=$("#imagesource");
					$.ajax({
						method: "POST",
						dataType:  'json',
						url: KTAppOptions._RF_ADMIN+"/process/article/removepic",
						data: { 'article[id]': id,"picture":1},
						beforeSend: function( xhr ) {
							KTApp.block(blockelm, {
                                type: 'loader',
                                state: 'brand',
                                message: 'Устгаж байна',
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
                                toastr.success(jsonData._text);
                                $("#imagesource").removeClass("kt-hide");
                                $("#imagesourcermv").addClass("kt-hide");
                            } else {
                                App.showErrorValidate(jsonData,validator);
                            }
                            setTimeout(function() {
                                KTApp.unblock(blockelm);
                            }, 1000);
						}
					});
				}
			});
		});
		form.on('click', '#removespecphoto', function() {
			swal({
				title: 'Онцлох зураг устгахдаа итгэлтэй байна уу?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Тийм!',
				cancelButtonText: 'Үгүй'
			}).then(function(result) {
				if (result.value) {
                    var blockelm=$("#imagesourcespec");
					$.ajax({
						method: "POST",
						dataType:  'json',
						url: KTAppOptions._RF_ADMIN+"/process/article/removepic",
						data: { 'article[id]': id,"picture":2},
						beforeSend: function( xhr ) {
							KTApp.block(blockelm, {
                                type: 'loader',
                                state: 'brand',
                                message: 'Устгаж байна',
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
                                toastr.success(jsonData._text);
                                $("#imagesourcespec").removeClass("kt-hide");
                                $("#imagesourcespecrmv").addClass("kt-hide");
                            } else {
                                App.showErrorValidate(jsonData,validator);
                            }
                            setTimeout(function() {
                                KTApp.unblock(blockelm);
                            }, 1000);
						}
					});
				}
			});
		});
	}
	var initRemove = function () {
		form.on('click', '#delete', function () {
			swal({
				title: 'Устгахдаа итгэлтэй байна уу?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Тийм!',
				cancelButtonText: 'Үгүй'
			}).then(function (result) {
				if (result.value) {
					$.ajax({
						method: "POST",
						dataType: 'json',
						url: KTAppOptions._RF_ADMIN + "/process/article/remove",
						data: { 'article[id]': id },
						beforeSend: function (xhr) {
							removeBtn.html(App.getSpinner() + " устгаж байна");
							removeBtn.attr("disabled", "disabled");
						},
						error: function (data) {
							toastr.error("Алдаа гарсан байна. Err msg: " + data.responseText);
							removeBtn.html(removeBtnText);
							removeBtn.removeAttr("disabled");
						},
						success: function (jsonData) {
							if (typeof jsonData !== 'undefined' && jsonData != "" && jsonData._state) {
								toastr.success(jsonData._text);
								setTimeout(function() {
									window.location=$("#backbtn").attr("href");
								}, 2000);
							} else {
								App.showErrorValidate(jsonData, validator);
							}
						}
					});
				}
			});
		});
	}
	var initAttach = function (par_id) {
		var _portletForm=$("#kt_tabs_2");
		$.ajax({
			method: "GET",
			url: KTAppOptions._RF_ADMIN+"/m/article/attach",
			data: { id: par_id},
			beforeSend: function( xhr ) {
				KTApp.block(_portletForm, {
					overlayColor: '#ffffff',
					type: 'loader',
					state: 'brand',
					opacity: 0.3,
					size: 'lg'
				});
			}
		}).fail(function( html ) {
			setTimeout(function() {
				KTApp.unblock(_portletForm);
			}, 1000);
		}).done(function( html ) {
			_portletForm.html(html);
			setTimeout(function() {
				KTApp.unblock(_portletForm);
			}, 1000);
		});
	}
	return {
		init: function (par_form) {
			id=_id;
			form = par_form;
			submitBtn = form.find('button[type="submit"]');
			submitBtnText = submitBtn.html();
			init();
			initValid();
			initForm();
			initAttach(id);
			if (form.find('#delete').length > 0) {
				removeBtn = form.find('#delete');
				removeBtnText = removeBtn.html();
				initRemove();
			}
		},
		getID:function(){
			return id;
		}
	};
}();
jQuery(document).ready(function () {
	AppArticleForm.init($("#articleForm"));
});
