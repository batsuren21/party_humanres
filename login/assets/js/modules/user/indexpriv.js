"use strict";
var AppMenu = function () {
    var _jstree;
    var p_uid;
    var contextmenu;
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
        contextmenu={
            "separator_before": false,
            "separator_after": false,
            "label": "Засах",
            "action": function (obj) {
                $.ajax({
                    method: "POST",
                    url: KTAppOptions._RF_ADMIN+"/m/user/formpriv",
                    data: {_uid:p_uid, id: jstree_selected.id},
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
    };
    var initTree = function() {
        p_uid=par_uid;
        _jstree=$("#jstree").jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }, 
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return KTAppOptions._RF_ADMIN+'/m/user/menutree';
                    },
                    'data' : function (node) {
                        return {"_uid":par_uid, 'parent' : node.id};
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
                    return {
                        "create": contextmenu,
                    };
                }
            }
        }).on('loaded.jstree', function(e,data) {
            // _jstree.jstree('open_all');
        });
    }
    var initPortlet = function() {
        _portletForm =$('#privFormPortlet');
        _portletForm_text =$('#privFormPortlet').html();
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
        },
        getUserID: function(){
            return p_uid;
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
    var initValid = function() {
        validator=form.validate({
            // define validation rules
            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                // var alert = $('#kt_form_1_msg');
                // alert.parent().removeClass('kt-hidden');
                // KTUtil.scrollTo("kt_form_1", -200);
            }
        });    
    }
    var initForm= function(){
        var tree=AppMenu.getTree();
        form.ajaxForm({
            dataType:  'json',
            type: 'post',
            data: {"priv[userid]":AppMenu.getUserID()},
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
                } else {
                    App.showErrorValidate(jsonData,validator);
                }
                submitBtn.html(submitBtnText);
                submitBtn.removeAttr("disabled");
            }
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
    };
}();