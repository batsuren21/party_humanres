function initAttachFile(){
        /************************************* FileUpload ******************************************/
        var MAX_FILES_COUNT = 5;
        var max_upload_size = 50;
        const BYTES_PER_CHUNK = 1024 * 1024;
        const FILE_MAX_SIZE = 1024 * 1024 * max_upload_size;
        var validator={};
        var filefields;
        var uploaded_files_real_name;
        var uploaded_files_enc_name;
        var filefields_size = 0;
        var filefields_index = 0;
        var cfile;
        var valid_formats=["txt", "jpg", "jpeg", "png", "gif", "bmp", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "rar", "zip", "mp3", "mp4", "flv", "mov", "mkv", "mpg", "avi"];
        var part = 1;
        var start;
        var end;
        var time;

        // var fileChooseForm = '<div class="form-group file-item"><div class="col-md-offset-2 col-md-6"><div class="input-group"><input type="text" class="form-control form-filter input-sm" readonly><input type="file" name="filesource" class="hidden"><span class="input-group-btn"><button class="btn btn-sm default chooseBtn" type="button" title="Сонгох"><i class="fa fa-folder-open"></i></button></span></div></div></div>';
        var fileChooseForm = $(".file-item").clone();
        var removeBtn = '<button class="btn btn-danger btn-icon removeBtn" type="button" title="Хасах"><i class="flaticon-delete"></i></button>';
        var progressbar = '<div class="fileuploadinfo"><div class="process" percent="0"></div><span class="percent">0%</span></div>';

        $(".file-form").on("click",".chooseBtn",function(){
            var _par = $(this).parent().parent(".input-group");
            var _file = $('input[type="file"]',_par);
            _file.click();
        });

        $(".file-form").on("change",'input[name="filesource"]',function(){
            $(this).parent().parent().children('.fileuploadinfo').remove();
            var file = $(this)[0].files[0];
            if(file == undefined){
                $('input[type="text"]', _par).val('');
                return;
            }

            var fileext = file.name.split('.').pop();
            var fcheck = fileext.toLowerCase();
            var a = valid_formats.indexOf(fcheck);

            if(FILE_MAX_SIZE < file.size){
                alert("Файлын хэмжээ " + max_upload_size + "MB-ээс хэтэрсэн байна.");
                $(this).val('');
                $(this).parent().children("input[type='text']").val('');
                return false;
            }
            if(a<0){
                alert("Буруу файл сонгосон байна.");
                $(this).val('');
                $(this).parent().children("input[type='text']").val('');
                return false;
            }

            var _par = $(this).parent(".input-group");
            var file = $(this)[0].files[0];
            if(file != undefined){
                $('input[type="text"]', _par).val($(this).val());
                if($('.input-group-append .removeBtn', _par).length == 0){
                    $('.input-group-append', _par).append(removeBtn);
                }

                var checkBlankForm = true;
                $('input[type="text"]', '.file-form').each(function(){
                    if($(this).val() == ''){
                        checkBlankForm = false;
                    }
                })
                if(checkBlankForm && $('.file-item').length < MAX_FILES_COUNT){
                    $("#filesDiv").append(fileChooseForm);
                }

            }
        });

        $(".file-form").on("click",'.removeBtn',function(){
             var _par = $(this).parent().parent().parent().parent(".file-item");

             $(".fileuploadinfo", _par).remove();

             if(1 < $('.file-item').length){
                 if($('.file-item').length == MAX_FILES_COUNT){
                    var checkBlankForm = true;
                    $('input[name="filesource"]', '.file-form').each(function(){
                        if($(this).val() == ''){
                            checkBlankForm = false;
                        }
                    })
                    if(checkBlankForm){
                        $("input", _par).val('');
                        $(this).remove();
                    }else{
                        _par.remove();
                    }
                 }else{
                    _par.remove();
                 }
             }else{
                 $("input", _par).val('');
                 $(this).remove();
             }
        });

        $("#submitBtn-files").click(function(){
            attachFiles()
        })

        function attachFiles(){
            uploaded_files_real_name = Array();
            uploaded_files_enc_name = Array();
            filefields = Array();
            filefields_size = 0;
            filefields_index = 0;

            $("input[name='filesource']").each(function(){
                if($(this).val() != '' && $(this).val() != undefined){
                    filefields.push($(this));
                }
            })

            filefields_size = filefields.length;

            if(filefields_size > 0){
                $('#submitBtn-files').attr('disabled', 'disabled');
                $('#submitBtn-files').html('Хуулж байна...');
                UploadAttachFile();
            }
        }

        function UploadAttachFile(){
            cfile = filefields[filefields_index][0].files[0];

            if($('.fileuploadinfo',$(filefields[filefields_index]).parent().parent()).length == 0){
                filefields[filefields_index].parent().parent().append(progressbar);
            }

            part = 1;
            start = 0;
            end = BYTES_PER_CHUNK;
            time = new Date().getTime();

            RecurseUpload();
        }

        function RecurseUpload(){
            var data = new FormData();
            var _par = $(filefields[filefields_index]).parent().parent();

            data.append('filename',cfile.name);
            data.append('part',part);
            data.append('time',time);

            var func = (cfile.slice ? 'slice' : (cfile.mozSlice ? 'mozSlice' : (cfile.webkitSlice ? 'webkitSlice' : 'slice')));
            data.append('file',cfile[func](start,end));
            var request = new XMLHttpRequest();

            request.upload.addEventListener('error',function(event){
                    alert("Файл хуулах явцад алдаа гарлаа!");
            });

            request.addEventListener('readystatechange',function(event){
                if(request.readyState==4){
                    if(part == 1){
                        uploaded_files_real_name.push(cfile.name);
                        uploaded_files_enc_name.push(request.responseText);
                    }
                    if(end < cfile.size){
                        var p = (end * 95) / cfile.size;
                        var i = (end * 100) / cfile.size;
                        var p_attr = 0;
                        p_attr = Math.round(p / 10) * 10;

                        $('.process', _par).css("width",(p - 5) + "%" );
                        $('.process', _par).attr('percent', p_attr);
                        $('.percent', _par).html(Math.round(i) + "%");

                        start = end;
                        end = start + BYTES_PER_CHUNK;

                        if(cfile.size < end) {end = cfile.size;}

                        part++;
                        RecurseUpload();
                    }else{
                        $('.process', _par).css( "width", "100%" );
                        $('.process', _par).attr('percent', 100);
                        $('.percent', _par).html("100%");
                        $('input[name="filesource"]', _par).val('');

                        if(filefields_index < filefields_size - 1){
                            filefields_index++;
                            UploadAttachFile();
                        }else{
                            $.ajax({
                                url: KTAppOptions._RF_ADMIN+'/process/article/attachfileadd',
                                type: 'post',
                                dataType: "json",
                                error: function(a,b,c) {
                                        }, 
                                data:{
                                    'attach[id]': AppArticleForm.getID(),
                                    'attach[attachtype]': 1,
                                    files_real_name: uploaded_files_real_name,
                                    files_encrypt_name: uploaded_files_enc_name
                                },
                                success: function(jsonData) {
                                    if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
                                        $('#submitBtn-files').removeAttr('disabled');
                                        $('#submitBtn-files').html('Хадгалах');
                                        toastr.success("Амжилттай хууллаа");

                                        $("#file-table tbody .contentnotfound").parent('tr').remove();

                                        var file_size = jsonData._files.length;
                                        var el = '';
                                        var index = $("#file-table tbody tr").length + 1;
                                        for(var i=0; i < file_size; i++){
                                            el = '<tr>';
                                                el += '<td align="center" class="font-12">' + (index + i) + '</td>';
                                                el += '<td><a class="title font-12" title="' + jsonData._files[i]['AttachName'] + '">' + jsonData._files[i]['AttachName'] + '</a></td>';
                                                el += '<td><a href="javascript:;" class="file-descr font-12" data-pk="' + jsonData._files[i]['AttachID'] + '"></a></td>';
                                                el += '<td align="center" class="font-12" nowrap>' + jsonData._files[i]['AttachCreateDate'] + '</td>';
                                                el += '<td><a href="javascript:;" class="file-order font-12" data-pk="' + jsonData._files[i]['AttachID'] + '">' + jsonData._files[i]['AttachOrder'] + '</a></td>';
                                                el += '<td align="right" nowrap>';
                                                    el += '<a href="'+jsonData._files[i]['AttachSource']+'" title="Татах" class="adminBtn blue" target="_blank">';
                                                        el += '<i class="fa fa-download"></i>'
                                                    el += '</a> &nbsp; ';
                                                    el += '<a title="Устгах" class="adminBtn red fileDeleteBtn" data-id="' + jsonData._files[i]['AttachID'] + '">';
                                                        el += '<i class="flaticon-delete"></i>'
                                                    el += '</a>';
                                                el += '</td>';
                                            el += '</tr>';
                                            $("#file-table tbody").append(el);
                                        }
                                        $('.file-descr').editable(editable_option);
                                        $('.file-order').editable(editable_option1);
                                    }else{
                                        App.showErrorValidate(jsonData,validator);
                                    }
                                }
                            });
                        }
                    }
                }
            });
            request.open("POST", KTAppOptions._RF_ADMIN+'/process/article/fileupload');
            request.send(data);
        }
        var editable_option = {
            mode: 'inline',
            type: 'textarea',
            title: 'Файлын тайлбар',
            showbuttons: 'bottom',
            inputclass: 'pic-descr-textarea',
            emptytext: 'Тайлбаргүй',
            send: 'always',
            url: KTAppOptions._RF_ADMIN+"/process/article/editfiledescr",
            ajaxOptions: {
                dataType: 'json'
            },
            success: function(response, newValue) {
                if(typeof response !== 'undefined' && response!="" && response._state) {
                    toastr.success(response._text);
                } else {
                    App.showErrorValidate(response,validator);
                }
            }
        }
        var editable_option1 = {
                mode: 'inline',
                type: 'text',
                title: 'Эрэмбэ',
                showbuttons: 'bottom',
                inputclass: 'pic-descr-textarea',
                emptytext: '1',
                send: 'always',
                url: KTAppOptions._RF_ADMIN+"/process/article/editattachorder",
                ajaxOptions: {
                    dataType: 'json'
                },
                success: function(response, newValue) {
                    if(typeof response !== 'undefined' && response!="" && response._state) {
                        toastr.success(response._text);
                    } else {
                        App.showErrorValidate(response,validator);
                    }
                }
            }
        $('.file-descr').editable(editable_option);
        $('.file-order').editable(editable_option1);
        $(document).off('click', '.fileDeleteBtn');
        $(document).on("click", ".fileDeleteBtn", function(){
            var data_id = $(this).attr('data-id');
            var parentElement = $(this).parent().parent();
            swal({
                title: 'Хавсралт устгахдаа итгэлтэй байна уу?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Тийм!',
                cancelButtonText: 'Үгүй'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: KTAppOptions._RF_ADMIN+"/process/article/attachdeletefile",
                        type: 'post',
                        dataType: "json",
                        data:{
                            attachid: data_id
                        },
                        success: function(result) {
                            if(typeof result !== 'undefined' && result!="" && result._state) {
                                parentElement.animate({
                                    opacity: 0
                                },{
                                    duration: 400,
                                    complete: function(){
                                        parentElement.remove();
                                        if($("#file-table tbody tr").length == 0){
                                            $("#file-table tbody").append('<tr><td colspan="4" class="contentnotfound">Файл оруулаагүй байна!</td></tr>');
                                        }
                                    }
                                });
                                toastr.success("Амжилттай устгалаа");
                            }else{
                                App.showErrorValidate(result,validator);
                            }
                        }
                    });
                }
            });
        });
}
initAttachFile();