"use strict";
var AppArea = function () {
    var _jstree;
    var contextarea_add, contextarea_edit;
    var _portletForm;
    var _portletForm_text;
    var jstree_selected;
    var initToastr = function() {
        toastr.options.showDuration = 500;
    }
    var initContextArea = function() {
        $("#reloadtree","#areaTreePortlet").click(function(){
            _jstree.jstree("refresh");
        });
        contextarea_add={
            "separator_before": false,
            "separator_after": false,
            "label": "Нэмэх",
            "action": function (obj) {
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF+"/m/reference/form/add_area",
                    data: { parentid: jstree_selected.id},
                    beforeSend: function( xhr ) {
        				var blockelm=$("#areaFormPortlet");
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
                        KTApp.unblock(_portletForm);
                    }, 1000);
                }).done(function( html ) {
                    _portletForm.html(html);
                    AreaForm.init($("#areaForm"),0);
                    setTimeout(function() {
                        KTApp.unblock(_portletForm);
                    }, 1000);
                });
            }
        };
        contextarea_edit={
            "separator_before": false,
            "separator_after": false,
            "label": "Засах",
            "action": function (obj) {
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF+"/m/reference/form/add_area",
                    data: { id: jstree_selected.id},
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
                    AreaForm.init($("#areaForm"),jstree_selected.id);
                    AreaForm.initRemove(jstree_selected.id);
                    setTimeout(function() {
                        KTApp.unblock(_portletForm);
                    }, 1000);
                });
            }
        };
    };
    var initTree = function() {
        _jstree=$("#jstree").jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }, 
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return KTAppOptions._RF+'/m/reference/list/list_area';
                    },
                    'data' : function (node) {
                        return { 'parent' : node.id};
                    }
                }
            },
            "plugins" : [ "contextmenu" ],
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            },
            "contextmenu": {
                "items": function ($node) {
                    jstree_selected=$node;
                    if($node.data.isaddable==0){
                    	return {
                            "edit": contextarea_edit,
                        };
                    }else{
	                    if($node.parent!="#"){
	                        return {
	                            "create": contextarea_add,
	                            "edit": contextarea_edit,
	                        };
	                    }else{
	                        return {
	                            "create": contextarea_add,
	                        };
	                    }
                    }
                }
            }
        }).on('loaded.jstree', function(e,data) {
            // _jstree.jstree('open_all');
        });
    }
    var initPortlet = function() {
        _portletForm =$('#areaFormPortlet');
        _portletForm_text =$('#areaFormPortlet').html();
    }

    return {
        init: function () {
            initToastr();
            initPortlet();
            initContextArea();
            initTree();
        },
        getTree: function(){
            return _jstree;
        },
        getPortlet: function(){
            return _portletForm;
        },
        getPortletText: function(){
            return _portletForm_text;
        }
    };
}();
jQuery(document).ready(function() {
    AppArea.init();
});
var AreaForm = function() {
    var validator = {};
    var form;
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
              
            }
        });    
    }
    var initForm= function(){
        var tree=AppArea.getTree();
        form.ajaxForm({
            dataType:  'json',
            type: 'post',
			data: { 'area[id]':id},
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
                    if(jsonData._refreshnode>0){
                        tree.jstree(true).refresh_node(jsonData._refreshnode);
                        tree.jstree("open_node", jsonData._refreshnode);
                    }else{
                        tree.jstree("refresh");
                    }
                    if(jsonData._refreshform){
                        var def_val=$("#areamodule").val();
                        var def_order=$("#areaorder").val();
                        form.trigger("reset");
                        $("#areamodule").val(def_val);
                        $("#areaorder").val(parseInt(def_order)+1);
                        form.find("#areamodule").change();
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
        var tree=AppArea.getTree();
        var _portletForm=AppArea.getPortlet();
        var _portletFormText=AppArea.getPortletText();
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
                        url: KTAppOptions._RF+"/process/reference/removearea",
                        data: { 'area[id]': id},
                        beforeSend: function( xhr ) {
                            removeBtn.html(App.getSpinner()+" устгаж байна");
                            removeBtn.attr("disabled","disabled");
                            KTApp.block(_portletForm, {
                                overlayColor: '#ffffff',
                                type: 'loader',
                                state: 'brand',
                                opacity: 0.3,
                                size: 'lg'
                            });
                        },
                        error: function (data) {
                            toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
                            removeBtn.html(removeBtnText);
                            removeBtn.removeAttr("disabled");
                            setTimeout(function() {
                                KTApp.unblock(_portletForm);
                            }, 1000);
                        },
                        success: function(jsonData){
                            if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
                                toastr.success(jsonData._text);
                                _portletForm.html(_portletFormText);
                                if(jsonData._refreshnode>0){
                                    tree.jstree(true).refresh_node(jsonData._refreshnode);
                                    tree.jstree("open_node", jsonData._refreshnode);
                                }else{
                                    tree.jstree("refresh");
                                }
                            } else {
                                App.showErrorValidate(jsonData,validator);
                            }
                            setTimeout(function() {
                                KTApp.unblock(_portletForm);
                            }, 1000);
                        }
                    });
                }
            });
        });
    }
    return {
        init: function(par_form,par_id) {
            form=par_form;
            id=par_id;
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