"use strict";
var AppLogin = function() {
    var validator = {};
    var form = "";
    var submitBtn = "";
    var submitBtnText = "";
    var initValid = function() {
        validator=$( "#loginform" ).validate({
            // define validation rules
            rules: {
                'user[name]': {
                    required: true
                },
                'user[password]': {
                    required: true,
                    minlength: 5,
                },
            },
            messages: {
                'user[name]':{
                    required: 'Хэрэглэгчийн нэр оруулна уу!'
                },
                'user[password]': {
                    required: 'Нууц үг оруулна уу!',
                    minlength: 'Нууц үг 5-аас дээш тэмдэшт байна!'
                }
            },
            
            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                var alert = $('#kt_form_1_msg');
                alert.parent().removeClass('kt-hidden');
                KTUtil.scrollTo("kt_form_1", -200);
            },

            submitHandler: function (form) {
                form.submit(); 
            }
        });    
    }
    var initForm= function(){
        $("#loginform").ajaxForm({
            url: KTAppOptions._RF_LOGIN+'/process/login', 
            dataType:  'json',
            type: 'post',
            error: function (data) {
                toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
                submitBtn.html(submitBtnText);
                submitBtn.removeAttr("disabled");
            },
            beforeSubmit: function(){
                submitBtn.html(App.getSpinner()+" нэвтэрч байна");
                submitBtn.attr("disabled","disabled");
                return true;
            },
            success: function(jsonData){
                if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
                    toastr.success(jsonData._text);
                    setTimeout(function(){
//                        if(jsonData._url!== 'undefined'){
//                            window.location=jsonData._url;
//                        }else window.location=KTAppOptions._RF;
                    }, 3000);
                } else {
                    App.showErrorValidate(jsonData,validator);
                }
                submitBtn.html(submitBtnText);
                submitBtn.removeAttr("disabled");
            } 

        });
    }
    return {
        init: function() {
            form=$("#loginform");
            submitBtn=form.find('button[type="submit"]');
            submitBtnText=submitBtn.html();
            initForm(); 
            initValid(); 
        }
    };
}();
jQuery(document).ready(function() {
    AppLogin.init();
});