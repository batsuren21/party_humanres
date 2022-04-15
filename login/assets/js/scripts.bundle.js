"use strict";

var App = function() {
    var _spinner='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    var initToastr = function() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }
    return {
        init: function() {
            App.initToastr();
        },
        getSpinner: function(){
            return _spinner;
        },
        initToastr: function() {
            initToastr();
        },
        showErrorValidate: function(jsonData, validator) {
            if(typeof jsonData === 'undefined' || jsonData!="" && typeof jsonData._errors === 'undefined'){
                toastr.error(jsonData);
            }else{
                var isshow=0;
                var error_list=[];
                var error_str="";
                if(typeof jsonData._errors.field !== 'undefined' && jsonData._errors.field!=""){
                    if(jsonData._errors.field.length>0) isshow=1;
                    for(var j=0; j<jsonData._errors.field.length; j++){
                        error_list[jsonData._errors.field[j]._field]=jsonData._errors.field[j]._text;                                
                    }
                }
                if(typeof jsonData._errors.general !== 'undefined' && jsonData._errors.general!=""){
                    if(jsonData._errors.general.length>0) isshow=1;
                    for(var j=0; j<jsonData._errors.general.length; j++){
                        error_str+="<li> "+jsonData._errors.general[j]._text+"</li>";
                    }
                }
                if(typeof validator === 'undefined' || validator!=""){
                    validator.showErrors(error_list);
                }
                if(error_str!=""){
                    toastr.error("<ul>"+error_str+"</ul>");
                }
                if(!isshow) toastr.error("Алдаа гарсан байна");
            }
        },
    };
}();
$(document).ready(function() {
    App.init();
});