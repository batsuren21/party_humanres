function initAttachPicture(){
        const BYTES_PER_CHUNK = 1024 * 1024;
        const PICTURE_UPLOAD_LIMIT = 20;
        
        var PICTURE_DATA = [];
        var picstr;
        var aTag;
        var reader;
        var validator={};
        var uploaded_pics_enc_name;
        var pics_size = 0;
        var pics_index = 0;
        var cfile;
        var part = 1;
        var start;
        var end;
        var time;
        var imagekey;
        var ckey;
        var GlobalKey = 0;
        
        var progressbar = '<div class="fileuploadinfo"><div class="process" percent="0"></div></div>';
        
        
        $("#chooseBtn-picture").click(function(){
            $("#image-chooser").click();
        });
        
        $("#image-chooser").change(function(){
            var chosenpics = $(this)[0].files;
            
            for(var i = 0; i < chosenpics.length && PICTURE_DATA.length < PICTURE_UPLOAD_LIMIT; i++){
                imagekey = GlobalKey++;
                chosenpics[i]['imagekey'] = imagekey;
                PICTURE_DATA = PICTURE_DATA.concat(chosenpics[i]);
                
                $("#image-limit").html(PICTURE_UPLOAD_LIMIT - PICTURE_DATA.length);
                
                reader = new FileReader();
                reader['imagekey'] = imagekey;
                reader.onload = function(e){
                    aTag = '<a class="removeChosenPic" image-key="' + e.target.imagekey + '"><i class="flaticon-delete"></i></a>';
                    picstr = '<div image-key="' + e.target.imagekey + '" class="newIMG"><div class="imgDiv"><img src="' + e.target.result + '"></div>' + aTag + '</div>';
                    $("#new-picture-form").append(picstr);
                };
                reader.readAsDataURL(chosenpics[i]);
            }
            $("#uploadBtn-picture").show();
            $("#deleteBtn-picture").show();
        });
        
        $("#new-picture-form").on("click",".removeChosenPic",function(){
            var this_key = $(this).attr('image-key');
            var _par = $(this).parent(".newIMG");
            
            _par.animate({
                width: 0,
                height: 0,
                opacity: 0
                },{
                duration: 200,
                complete: function(){
                    for(var i = 0; i < PICTURE_DATA.length; i++){
                        if(PICTURE_DATA[i].imagekey == this_key){
                            PICTURE_DATA.splice(i, 1);
                            break;
                        }
                    }
                    $("#image-limit").html(PICTURE_UPLOAD_LIMIT - PICTURE_DATA.length);
                    _par.remove();
                    if(PICTURE_DATA.length == 0){
                        $("#uploadBtn-picture").hide();
                        $("#deleteBtn-picture").hide();
                    }
                }
            });
        });
        
        $("#deleteBtn-picture").click(function(){
            PICTURE_DATA.splice(0);
            $("#image-limit").html(PICTURE_UPLOAD_LIMIT - PICTURE_DATA.length);
            $(".newIMG").animate({
                width: 0,
                height: 0,
                opacity: 0
                },{
                duration: 200,
                complete: function(){
                    $(".newIMG").remove();
                    $("#uploadBtn-picture").hide();
                    $("#deleteBtn-picture").hide();
                }
            });
        });
        
        $("#uploadBtn-picture").click(function(){
            if(PICTURE_DATA.length > 0){
                attachPicutres();
            }
        });
        
        function attachPicutres(){
            uploaded_pics_real_name = Array();
            uploaded_pics_enc_name = Array();
            pics_size = 0;
            pics_index = 0;

            pics_size = PICTURE_DATA.length;

            $('#uploadBtn-picture').attr('disabled', 'disabled');
            $('#deleteBtn-picture').attr('disabled', 'disabled');
            $('#uploadBtn-picture').html('Хуулж байна...');
            UploadAttachPicture();
        }
        
        function UploadAttachPicture(){
            cfile = PICTURE_DATA[pics_index];
            ckey = PICTURE_DATA[pics_index].imagekey;
            
            if($('.newIMG[image-key="' + ckey + '"]').children('.fileuploadinfo').length == 0){
                $('.newIMG[image-key="' + ckey + '"]').append(progressbar);
            }

            part = 1;
            start = 0;
            end = BYTES_PER_CHUNK;
            time = new Date().getTime();

            RecurseUploadPicture();
        }
        
        function RecurseUploadPicture(){
            var data = new FormData();
            var _par = $('.newIMG[image-key="' + ckey + '"]');

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
                        uploaded_pics_enc_name.push(request.responseText);
                    }
                    if(end < cfile.size){
                        var p = (end * 100) / cfile.size;
                        var p_attr = 0;
                        p_attr = Math.round(p / 10) * 10;
                        
                        $('.process', _par).css("width", p + "%" );
                        $('.process', _par).attr('percent', p_attr);

                        start = end;
                        end = start + BYTES_PER_CHUNK;
                        
                        if(cfile.size < end) {end = cfile.size;}
                        
                        part++;
                        RecurseUploadPicture();
                    }else{
                        $('.process', _par).css( "width", "100%" );
                        $('.process', _par).attr('percent', 100);
                        $('input[name="filesource"]', _par).val('');

                        if(pics_index < pics_size - 1){
                            pics_index++;
                            UploadAttachPicture();
                        }else{
                            $.ajax({
                                url: KTAppOptions._RF_ADMIN+'/process/article/attachfileadd',
                                type: 'post',
                                dataType: "json",
                                data:{
                                    'attach[id]': AppArticleForm.getID(),
                                    'attach[attachtype]': 2,
                                    files_encrypt_name: uploaded_pics_enc_name
                                },
                                beforeSend: function(e) {
                                    $('#uploadBtn-picture').html('<i class="fa fa-cog fa-spin"></i> Зургийг боловсруулж байна...');
                                },
                                success: function(jsonData) {
                                    if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
                                        $('#uploadBtn-picture').removeAttr('disabled');
                                        $('#deleteBtn-picture').removeAttr('disabled');
                                        $('#uploadBtn-picture').html('Хадгалах');
                                        toastr.success("Амжилттай хууллаа");
                                        $("#picture-table tbody .contentnotfound").parent('tr').remove();
                                        
                                        var pic_size = jsonData._files.length;
                                        var el = '';
                                        var index = $("#picture-table tbody tr").length + 1;
                                        for(var i=0; i < pic_size; i++){
                                            el = '<tr>';
                                                el += '<td align="center">' + (index + i) + '</td>';
                                                el += '<td align="center"><a href="' + jsonData._files[i]['ImageSourceLG'] + '" title="" data-gallery><img src="' + jsonData._files[i]['ImageSourceXS'] + '"/></a></td>';
                                                el += '<td><a href="javascript:;" class="picture-descr" data-pk="' + jsonData._files[i]['AttachID'] + '"></a></td>';
                                                el += '<td align="center" nowrap>' + jsonData._files[i]['AttachCreateDate'] + '</td>';
                                                el += '<td><a href="javascript:;" class="picture-order font-12" data-pk="' + jsonData._files[i]['AttachID'] + '">' + jsonData._files[i]['AttachOrder'] + '</a></td>';
                                                el += '<td align="right" nowrap>';
                                                    el += '<a title="Устгах" class="adminBtn red pictureDeleteBtn" data-pk="' + jsonData._files[i]['AttachID'] + '">';
                                                        el += '<i class="flaticon-delete"></i>'
                                                    el += '</a>';
                                                el += '</td>';
                                            el += '</tr>';
                                            $("#picture-table tbody").append(el);
                                        }
                                        $('.picture-descr').editable(editable_option);
                                        $('.picture-order').editable(editable_option1);
                                    }else{
                                        App.showErrorValidate(jsonData,validator);
                                    }
                                    
                                    PICTURE_DATA.splice(0);
                                    $("#image-limit").html(PICTURE_UPLOAD_LIMIT - PICTURE_DATA.length);
                                }
                            });
                        }
                    }
                }
            });
            request.open("POST", KTAppOptions._RF_ADMIN+'/process/article/fileupload?action=upload');
            request.send(data);
        }
        
        var editable_option = {
            mode: 'inline',
            type: 'textarea',
            title: 'Зургийн тайлбар',
            showbuttons: 'bottom',
            inputclass: 'pic-descr-textarea',
            emptytext: 'Тайлбаргүй',
            send: 'always',
            url:  KTAppOptions._RF_ADMIN+"/process/article/editfiledescr",
            ajaxOptions: {
                dataType: 'json'
            },
            success: function(response, newValue) {
                if(typeof response !== 'undefined' && response!="" && response._state) {
                    $(".picture-descr[data-pk='" + response.pk + "']").parent().parent('tr').children().children('a[data-gallery]').attr('title',newValue);
                    toastr.success(response._text);
                } else {
                    App.showErrorValidate(response,validator);
                }
            }
        }
        var editable_option1 = {
                mode: 'inline',
                type: 'text',
                title: 'Зургийн эрэмбэ',
                showbuttons: 'bottom',
                inputclass: 'pic-descr-textarea',
                emptytext: '1',
                send: 'always',
                url:  KTAppOptions._RF_ADMIN+"/process/article/editattachorder",
                ajaxOptions: {
                    dataType: 'json'
                },
                success: function(response, newValue) {
                    if(typeof response !== 'undefined' && response!="" && response._state) {
                        $(".picture-descr[data-pk='" + response.pk + "']").parent().parent('tr').children().children('a[data-gallery]').attr('title',newValue);
                        toastr.success(response._text);
                    } else {
                        App.showErrorValidate(response,validator);
                    }
                }
            }
        $('.picture-descr').editable(editable_option);
        $('.picture-order').editable(editable_option1);
        $(document).off('click', '.pictureDeleteBtn');
        $(document).on("click", ".pictureDeleteBtn", function(){
            var data_id = $(this).attr('data-pk');
            var parentElement = $(this).parent().parent();
            var img = parentElement.children().children().children('img');
            swal({
                title: 'Хавсралт устгахдаа итгэлтэй байна уу?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Тийм!',
                cancelButtonText: 'Үгүй'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: KTAppOptions._RF_ADMIN+'/process/article/attachdeletefile',
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
                                        if($("#picture-table tbody tr").length == 0){
                                            $("#picture-table tbody").append('<tr><td colspan="4" class="contentnotfound">Зураг оруулаагүй байна!</td></tr>');
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
initAttachPicture();