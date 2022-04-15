"use strict";
var AppMenu = function () {
    var _jstree;
    var contextmenu_add, contextmenu_edit;
    var _portletForm;
    var _portletForm_text;
    var jstree_selected;
    var initToastr = function() {
        toastr.options.showDuration = 500;
    }
    var initContextMenu = function() {
        $("#reloadtree","#menuTreePortlet").click(function(){
            _jstree.jstree("refresh");
        });
        contextmenu_add={
            "separator_before": false,
            "separator_after": false,
            "label": "Нэмэх",
            "action": function (obj) {
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/menu/form",
                    data: { parentid: jstree_selected.id},
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
                    MenuForm.init($("#menuForm"));
                    setTimeout(function() {
                        KTApp.unblock(_portletForm);
                    }, 1000);
                });
            }
        };
        contextmenu_edit={
            "separator_before": false,
            "separator_after": false,
            "label": "Засах",
            "action": function (obj) {
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/menu/form",
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
                    MenuForm.init($("#menuForm"));
                    MenuForm.initRemove(jstree_selected.id);
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
                        return KTAppOptions._RF_ADMIN+'/m/menu/menutree';
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
                    if($node.data.isdefault==1){
                        toastr.warning('Үндсэн цэс учир мэню нэмэх засах боломжгүй');
                        return {
                        };
                    }else{
                        if($node.parent!="#"){
                            return {
                                "create": contextmenu_add,
                                "edit": contextmenu_edit,
                            };
                        }else{
                            return {
                                "create": contextmenu_add,
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
        _portletForm =$('#menuFormPortlet');
        _portletForm_text =$('#menuFormPortlet').html();
    }

    return {
        init: function () {
            initToastr();
            initPortlet();
            initContextMenu();
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
    AppMenu.init();
});
var MenuForm = function() {
    var validator = {};
    var form;
    var id="";
    var submitBtn = "";
    var submitBtnText = "";
    var removeBtn = "";
    var removeBtnText = "";
    var initValid = function() {
        validator=form.validate({
            // define validation rules
            rules: {
                'menu[typeid]': {
                    required: true
                },
                'menu[organid]': {
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
                'menu[organid]': {
                    required: 'Байгууллага сонгогдоогүй байна!'
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
            
            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                // var alert = $('#kt_form_1_msg');
                // alert.parent().removeClass('kt-hidden');
                // KTUtil.scrollTo("kt_form_1", -200);
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
            if($(this).val()=="11"  || $(this).val()=="20"){
                form.find("#menuorganrow").removeClass("kt-hide");
                form.find("#menuorgan").removeAttr("disabled");
            } else{
                form.find("#menuorganrow").addClass("kt-hide");
                form.find("#menuorgan").attr("disabled","disabled");
            }
            if($(this).val()=="16"){
                form.find("#menuorgantyperow").removeClass("kt-hide");
                form.find("#menuorgantypeid").removeAttr("disabled");
                var $_selectedvalsub=form.find("#menuorgantypeid").data("selected");
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
                    data: { "action":"organtype","val_selected":$_selectedvalsub},
                }).done(function( html ) {
                    
                });
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/ajax/select",
                    data: { "action":"organtype","val_selected":$_selectedvalsub},
                }).done(function( jsonData ) {
                    if(jsonData._state){
                        if(jsonData._html){
                            form.find("#menuorgantypeid").html(jsonData._html);
                        }
                    }else toastr.error(jsonData);
                });
            } else{
                form.find("#menuorgantyperow").addClass("kt-hide");
                form.find("#menuorgantypeid").attr("disabled","disabled");
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
        form.on('change', '#menuorgan', function() {
            if($("#menutitle").val()=="")
                $("#menutitle").val($(this).find('option:selected').text());
        });
        form.find('#menumodule').change();

        var tree=AppMenu.getTree();
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
                    if(jsonData._refreshnode>0){
                        tree.jstree(true).refresh_node(jsonData._refreshnode);
                        tree.jstree("open_node", jsonData._refreshnode);
                    }else{
                        tree.jstree("refresh");
                    }
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
        var tree=AppMenu.getTree();
        var _portletForm=AppMenu.getPortlet();
        var _portletFormText=AppMenu.getPortletText();
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
        init: function(par_form) {
            form=par_form;
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