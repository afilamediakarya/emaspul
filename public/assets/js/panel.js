
class Control {
    constructor(type = null) {
        this.type = type;
        this.table= $('#kt_table_data');
        this.formData = new FormData();
    }

    modal_content(title,url){
        $('.modal-title').html(title);
    }

    overlay_form(type,module, url = null){
   
       $('.title_side_form').html(`${type} ${module}`);
       if (type == 'Tambah') {
            $('.form-data').attr('data-type','add');
       }else{
        $('.form-data').attr('data-type','update');
        $.ajax({
            url : url,
            method : 'GET',
            success : function (res) {
                if (res.status == true) {
                    console.log(res);
                    $.each(res.data, function (x,y) {
                        if (x == 'id_desa') {
                            console.log(y);
                            $('#desa-select').val(y)
                            $('#desa-select').trigger('change');
                        }else if(x == 'roles'){
                            $('#role').val(y.id_role);
                            $('#role').trigger('change');
                            setTimeout(function() { 
                                $('#select_').val(y.perangkat_bidang);
                                $('#select_').trigger('change');
                            }, 500);
                            
                        }else if(x == 'status'){
                            if (module == 'Perangkat Desa') {
                                $("select[name='status']").val(y);  
                            }
                            if (y == "1") {
                                $("#aktif").attr('checked', 'checked');
                            }else{
                                $("#tidak_aktif").attr('checked', 'checked');
                            }
                    
                        }else if(x == 'pagu_desa'){
                            $("input[name='"+x+"']").val(y);
                            $("input[name='"+x+"']").trigger('change');
                        }
                        else{
                            $("input[name='"+x+"']").val(y);
                            $("select[name='"+x+"']").val(y);  
                            
                        }
                     
                    })
                }
            },
            error : function (xhr) {
                alert('gagal');
            }
        });
       }
        // this._offcanvasObject.show();
    }

    submitForm(url,role_data = null,module = null,type = null){
        let message = '';
        // let data =  $('.form-data').serialize();
        let type_ = this.type;
        let table_ = this.table;
        let formData = new FormData($('.form-data')[0]);
        if(type_ == 'type_1') {
            message = 'Login anda berhasil';
        }else{
            message = `${module} berhasil di ${role_data}`;
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });



        if (module == 'Bidang Verifikator') {
            let data_bidang_verifikator
            var el_indikator = $('[name*=perangkat_desa]');
            el_indikator.each(function (i, el) {
                formData.append('perangkat_desa[' + i + ']=',$(el).val());
            })
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                $('.text-danger').html('');
                swal.fire({
                    text: `${message}`,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    confirmButtonColor: '#354C9F',
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    if (type_ == 'type_1') {
                        window.location.href = response;
                    }else if(type_ == 'type_3'){
                        window.location.href = response.url;
                    }else{
                        $('#side_form_close').trigger('click');
                        table_.DataTable().ajax.reload();
                    }
                });
            },
            error : function (xhr) {
                $('.text-danger').html('');
                $.each(xhr.responseJSON['errors'],function (key, value) {
                    $(`.${key}_error`).html(value);
                })
            }
        });
    }

    submitFormMultipart(url,role_data = null,module = null,element){
        // let data =  $('.form-data').serialize();
        let type_ = this.type;
        let table_ = this.table;

        let message = `${module} berhasil di ${role_data}`;

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        // let form = $('form')[0];
        var formData = this.formData;
        console.log(element);
        for (let index = 0; index < element.length; index++) {
            formData.append(`${element[index]}`,$(`#${element[index]}`).val());
        }
        // $.each(element, function (index, label) {
        //     formData.append(`${label}=`,$(`#${label}`).val());
        // })

       

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                $('.text-danger').html('');
                swal.fire({
                    text: `${message}`,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    confirmButtonColor: '#354C9F',
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    if (type_ == 'type_1') {
                        window.location.href = response;
                    }else{
                        $('#side_form_close').trigger('click');
                        table_.DataTable().ajax.reload();
                    }
                });
            },
            error : function (xhr) {
                $('.text-danger').html('');
                $.each(xhr.responseJSON['errors'],function (key, value) {
                    $(`.${key}_error`).html(value);
                })
            }
        });
    }

    push_select(url,element){
        $.ajax({
            url : url,
            method : 'GET',
            success : function (res) {
                console.log(res);
                $(element).html('');
                let html = '<option selected disabled>Pilih</option>';
                $.each(res,function (x,y) {
                    html += `<option value="${y.id}">${y.value}</option>`
                })
                $(element).html(html);
            },
            error : function (xhr) {
                alert('gagal');
            }
        })
    }

    initDatatable(url,columns,columnDefs){
     
        this.table.dataTable().fnDestroy();
        this.table.DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            processing:true,
            ajax: url,
            columns:columns,
            columnDefs:columnDefs,
        });
    }

    verifikasi_render_row(params){
  
        $.ajax({
            url : '/get-data/master-verifikasi/'+params,
            method : 'GET',
            success : function (res) {
                console.log(res);
                let html = '';
                let tindak_lanjut = '';
            
                $.each(res, function (x,y) {
                    let checked_true = '';
                    let checked_false = '';
                    if (y.tindak_lanjut !== null) {
                        tindak_lanjut = y.tindak_lanjut;
                    }
                    if (y.verifikasi == '1') {
                        checked_true = 'checked';
                    }else if(y.verifikasi == '0'){
                        checked_false = 'checked';
                    }
                    html += `<tr>
                        <td>${x+1}</td>
                        <td>${y.master_verifikasi['indikator']}
                        <input type="hidden" name="id[${x}]" value="${y.id}">
                        </td>
                        <td>
                        <div class="mb-10 mt-5">

                                <div class="radio-inline">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" value="1" type="radio" ${checked_true} name="status[${x}]" id="flexRadioDefault${x}">
                                            <label class="form-check-label" for="flexRadioDefault${x}">
                                                Sesuai
                                            </label>
                                        
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid mt-3">
                                            <input class="form-check-input" value="0" type="radio" ${checked_false} name="status[${x}]" id="flexRadioDefault${x}">
                                            <label class="form-check-label" for="flexRadioDefault${x}">
                                            Tidak
                                            </label>
                                        </div>
                                </div>    
                        </td>
                        <td>
                            <textarea class="form-control form-control-solid" name="tindak_lanjut[${x}]" rows="3">${tindak_lanjut}</textarea>
                        </td>
                    </tr>`;
                })

                $('#kt_table_data tbody').html(html);
            },
            error : function (xhr) {
                alert('gagal');
            }
        })
    }

    form_upload(){
        // let CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute()
        var id = "#kt_dropzonejs_example_2";

        // set the preview element template
        var previewNode = $(id + " .dropzone-item");
        previewNode.id = "";
        var previewTemplate = previewNode.parent(".dropzone-items").html();
        previewNode.remove();
        var formData = this.formData;

        var myDropzone = new Dropzone(id, { // Make the whole body a dropzone
            url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            maxFilesize: 1, // Max filesize in MB
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: id + " .dropzone-items", // Define the container to display the previews
            clickable: id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.on("addedfile", function(file) {
            formData.append('file',file);
            // Hookup the start button
            file.previewElement.querySelector(id + " .dropzone-start").onclick = function() { myDropzone.enqueueFile(file); };
            $(document).find( id + " .dropzone-item").css("display", "");
            $( id + " .dropzone-upload, " + id + " .dropzone-remove-all").css("display", "inline-block");
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            $(this).find( id + " .progress-bar").css("width", progress + "%");
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            $( id + " .progress-bar").css("opacity", "1");
            
            file.previewElement.querySelector(id + " .dropzone-start").setAttribute("disabled", "disabled");
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("complete", function(progress) {
            var thisProgressBar = id + " .dz-complete";
            setTimeout(function(){
                $( thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress, " + thisProgressBar + " .dropzone-start").css("opacity", "0");
            }, 300)

        });

        // Setup the buttons for all transfers
        document.querySelector( id + " .dropzone-upload").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };

        // Setup the button for remove all files
        document.querySelector(id + " .dropzone-remove-all").onclick = function() {
            $( id + " .dropzone-upload, " + id + " .dropzone-remove-all").css("display", "none");
            myDropzone.removeAllFiles(true);
        };

        // On all files completed upload
        myDropzone.on("queuecomplete", function(progress){
            $( id + " .dropzone-upload").css("display", "none");
        });

        // On all files removed
        myDropzone.on("removedfile", function(file){
            if(myDropzone.files.length < 1) {
                $( id + " .dropzone-upload, " + id + " .dropzone-remove-all").css("display", "none");
            }
        });
    }

}