
class Control {
    constructor(type = null) {
        this.type = type;
        this.table= $('#kt_table_data');
        this.formData = new FormData();
    }

    searchTable(data){
        this.table.DataTable().search( data ).draw();
    }

    modal_content(title,url,url_konsederan){
        $('.modal-title').html(title);

        $.ajax({
            url : url,
            method : 'GET',
            success : function (res) {
                console.log(res);
                let tanggal_perbub = '-';
                let nomor_perbub = '-';
                let row = '';
                $('#konsederan').attr('href',url_konsederan)
                $.each(res.document,function name(x,y) {

                    if (y.tanggal_perbub !== null) {
                        tanggal_perbub = y.tanggal_perbub;
                    }

                    if (y.nomor_perbub !== null) {
                        nomor_perbub = y.nomor_perbub;
                    }

                    if (y.verifikator == null) {
                        $('#konsederan').css('display','none');
                    }else{
                        $('#konsederan').css('display','block');
                    }

                    $('.content_detail_1').html(y.nama_documents);
                  
                    $('.content_detail_3').html(y.verifikator);
                    $('.content_detail_4').html(nomor_perbub);
                    $('.content_detail_5').html(tanggal_perbub);

                    if (y.status_document == '1') {
                        $('.content_detail_2').html('<button class="btn btn-light-danger btn-sm">Belum Verifikasi</button>');  
                    }else if(y.status_document == '2'){
                        $('.content_detail_2').html(`<button class="btn btn-light-dark btn-sm">Perbaikan</button>`);
                    }else if(y.status_document == '3'){
                        $('.content_detail_2').html(`<button class="btn btn-light-warning btn-sm">Belum Selesai</button>`);
                    }else{
                        $('.content_detail_2').html(`<button class="btn btn-light-success btn-sm"> Selesai</button>`);
                    }
                })

                $.each(res.master_verifikasi, function (i,v) {
                    let tindak_lanjut = '-';
                    let kesesuaian = '-';
                    if (v.tindak_lanjut !== null) {
                        tindak_lanjut = v.tindak_lanjut;
                    }

                    if (v.verifikasi == '1') {
                        kesesuaian = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_310_17035)">
                        <path d="M17.5098 7.74121H16.2536C15.9804 7.74121 15.7205 7.87246 15.5598 8.09746L11.3491 13.9367L9.44197 11.2903C9.28125 11.068 9.02411 10.9341 8.74822 10.9341H7.49197C7.31786 10.9341 7.21608 11.1323 7.31786 11.2742L10.6554 15.9028C10.7342 16.0129 10.8381 16.1025 10.9586 16.1644C11.079 16.2262 11.2124 16.2585 11.3478 16.2585C11.4831 16.2585 11.6166 16.2262 11.737 16.1644C11.8574 16.1025 11.9613 16.0129 12.0402 15.9028L17.6813 8.08139C17.7857 7.93943 17.6839 7.74121 17.5098 7.74121Z" fill="#50CD89"/>
                        <path d="M12.5 0C5.87321 0 0.5 5.37321 0.5 12C0.5 18.6268 5.87321 24 12.5 24C19.1268 24 24.5 18.6268 24.5 12C24.5 5.37321 19.1268 0 12.5 0ZM12.5 21.9643C6.99821 21.9643 2.53571 17.5018 2.53571 12C2.53571 6.49821 6.99821 2.03571 12.5 2.03571C18.0018 2.03571 22.4643 6.49821 22.4643 12C22.4643 17.5018 18.0018 21.9643 12.5 21.9643Z" fill="#50CD89"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_310_17035">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                        </clipPath>
                        </defs>
                        </svg>                        
                        `;
                    }else{
                        kesesuaian = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_310_17047)">
                        <path d="M17.1448 7.78948C17.1448 7.67162 17.0483 7.5752 16.9305 7.5752L15.1626 7.58323L12.5001 10.7573L9.84029 7.58591L8.06975 7.57787C7.9519 7.57787 7.85547 7.67162 7.85547 7.79216C7.85547 7.84305 7.87422 7.89127 7.90636 7.93145L11.3912 12.0832L7.90636 16.2323C7.87399 16.2716 7.85604 16.3207 7.85547 16.3716C7.85547 16.4895 7.9519 16.5859 8.06975 16.5859L9.84029 16.5779L12.5001 13.4038L15.1599 16.5752L16.9278 16.5832C17.0456 16.5832 17.1421 16.4895 17.1421 16.3689C17.1421 16.3181 17.1233 16.2698 17.0912 16.2297L13.6117 12.0806L17.0965 7.92877C17.1287 7.89127 17.1448 7.84037 17.1448 7.78948Z" fill="#F1416C"/>
                        <path d="M12.5 0.0268555C5.87321 0.0268555 0.5 5.40007 0.5 12.0269C0.5 18.6536 5.87321 24.0269 12.5 24.0269C19.1268 24.0269 24.5 18.6536 24.5 12.0269C24.5 5.40007 19.1268 0.0268555 12.5 0.0268555ZM12.5 21.9911C6.99821 21.9911 2.53571 17.5286 2.53571 12.0269C2.53571 6.52507 6.99821 2.06257 12.5 2.06257C18.0018 2.06257 22.4643 6.52507 22.4643 12.0269C22.4643 17.5286 18.0018 21.9911 12.5 21.9911Z" fill="#F1416C"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_310_17047">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                        </clipPath>
                        </defs>
                        </svg>
                        
                        `;
                    }
                    row += `<tr>
                        <td>${i+1}</td>
                        <td>${v.master_verifikasi['indikator']}</td>
                        <td>${kesesuaian}</td>
                        <td>${tindak_lanjut}</td>
                    </tr>`;                    
                })
           
                $('#table_detail tbody').html(row);
            },
            error : function (xhr) {
                alert('gagal');
            }
        })
    }

    overlay_form(type,module, url = null, role = null){
   
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
                    
                        }else if(x == 'pagu_desa' || x == 'pagu'){
                            $("input[name='"+x+"']").val(y);
                            $("input[name='"+x+"']").trigger('change');
                        }else if(x == 'tahapans'){
                            $('#tahapan').val(y.tahapan);
                            $('#tahapan').trigger('change');
                            setTimeout(function() { 
                                $('#sub_tahapan').val(y.sub_tahapan);
                                $('#sub_tahapan').trigger('change');
                            }, 500);
                        }
                        else{
                            $("input[name='"+x+"']").val(y);
                            $("select[name='"+x+"']").val(y);  
                            $("select[name='"+x+"']").trigger('change');  
                        }

                        if (role == 'makro') {
                           $.each(res.data['data_makro'], function (key,val) {
                                $(`#id_data_makro${key}`).val(val.id);
                                $(`#target${key}`).val(val.target);
                                $(`#realisasi${key}`).val(val.realisasi);
                           }) 
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
                if (type_ == 'type_1') { 
                    if (response.status == true) {
                        Swal.fire("Sukses!",'Anda berhasil login', "success");
                        setTimeout(function() { 
                            window.location.href = response.callback;
                        }, 2000);
                        
                    }else{
                        Swal.fire("Maaf anda gagal login!",`${response.callback}`, "warning");
                    }
                }else{
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
                       if(type_ == 'type_3'){
                            window.location.href = response.url;
                        }else{
                            $('#side_form_close').trigger('click');
                            table_.DataTable().ajax.reload();
                            $("form")[0].reset();
                        }
                    });
                }
               
            },
            error : function (xhr) {
                $('.text-danger').html('');
                $.each(xhr.responseJSON['errors'],function (key, value) {
                    $(`.${key}_error`).html(value);
                })
            }
        });
    }

    progress_bar_process(percentage, timer,message,type_,table_)
    {
        console.log('percentage : '+percentage+'%');
        $('#myBar').css('width',percentage +'%');
        $('#myBar').trigger('change');
        if(percentage > 100)
        {
            clearInterval(timer);

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
                    $('.dropzone-delete').trigger('click');
                    table_.DataTable().ajax.reload();
                    $('.progress').css('display', 'none');
                    $('#myBar').css('width', '0%');
                    $("form")[0].reset();
                }
            });
        }
    }

    submitFormMultipart(url,role_data = null,module = null,element){
        let this_ = this;
        let type_ = this.type;
        let table_ = this.table;
        let message = `${module} berhasil di ${role_data}`;

      
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var formData = this.formData;
        console.log(element);
        for (let index = 0; index < element.length; index++) {
            formData.append(`${element[index]}`,$(`#${element[index]}`).val());
        }
       
        $.ajax({
            xhr: function(){
                var xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                  //Do something with upload progress
                  console.log(percentComplete);
                  }
                }, false);
                   // Download progress
                xhr.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        // Do something with download progress
                        console.log(percentComplete);
                    }
                }, false);

                return xhr;
              },
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend:function()
            {
             $('.progress').css('display', 'block');
            },
            success: function (response) {
                console.log(response);
                $('.text-danger').html('');
                if (response.status == true) {
                    var percentage = 0;
                    var timer = setInterval(function(){
                     percentage = percentage + 20;
                     this_.progress_bar_process(percentage, timer, message,type_,table_);
                    }, 1000);
                }else{
                    $("form")[0].reset();
                    Swal.fire("Gagal Memproses data!",`${response.message}`, "warning");
                }

              

        
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
        let value = '';
        $.ajax({
            url : url,
            method : 'GET',
            success : function (res) {
                console.log(res);
                $(element).html('');
                let html = '<option selected disabled>Pilih</option>';
                $.each(res,function (x,y) {
                    if (url == '/get-data/pagu-desa') {
                        value = y.value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }else{
                        value = y.value;
                    }
                    html += `<option value="${y.id}">${value}</option>`
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
            // scrollX:true,
            // fixedColumns: {
            //     leftColumns: 1,
            //     rightColumns: 1
            // }
        });
    }

    checkJadwal(tahapan,sub_tahapan){
    
        $.ajax({
            url : `/get-data/checkJadwal?tahapan=${tahapan}&sub_tahapan=${sub_tahapan}`,
            method : 'GET',
            success : function (res) {
                console.log(res);
                if (res.status !== true) {
                    $('.filter-btn').prop('disabled', true);
                    $("#jadwals").html(`Jadwal Input : ${res.jadwal['jadwal_mulai']} sd ${res.jadwal['jadwal_selesai']}`)
                }
            },
            error : function (xhr) {
                alert('gagal');
            }
        })
    }

    verifikasi_render_row(params){
  
        $.ajax({
            url : '/get-data/master-verifikasi/'+params,
            method : 'GET',
            success : function (res) {
                console.log(res);
                let html = '';
                let tindak_lanjut = '';

                $('#jenis_document').val(res[0]['jenis_document']);
                $('#nama_documents').val(res[0]['nama_documents']);
            
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
                                            <input class="form-check-input true_check" value="1" data-index="${x}" type="radio" ${checked_true} name="status[${x}]" id="flexRadioDefault${x}">
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
                            <textarea class="form-control form-control-solid" id="tindak_lanjut_${x}" name="tindak_lanjut[${x}]" rows="3">${tindak_lanjut}</textarea>
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
            maxFilesize: 25, // Max filesize in MB
            maxFiles: 1,
            init: function() {
                this.on('addedfile', function(file) {
                  if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                  }
                });
              },
            // accept: function(file, done) {
            //     alert("uploaded");
            //     done();
            // },
            // init: function() {
            //     this.on("maxfilesexceeded", function(file){
            //         alert("No more files please!");
            //     });
            // },
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: id + " .dropzone-items", // Define the container to display the previews
            clickable: id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.hiddenFileInput.removeAttribute("multiple");


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

    qr_code(id_documents,jenis,label){
        // var qrcode = new QRCode(document.getElementById("qrcode"), {
        //     text: `https://langitmaspul.enrekangkab.go.id/detail-dokumen?documents=${id_documents}&jenis=${jenis}&label=${label}`,
        //     width: 240,
        //     height: 240,
        //     colorDark: "#005DC5",
        //     logo: 'assets/media/logo/logo.png',
        //     logoWidth:80,
        //     logoHeight:80,
        //     logoBackgroundColor: '#ffffff', // Logo backgroud color, Invalid when `logBgTransparent` is true; default is '#ffffff'
        //     logoBackgroundTransparent: true, // Whether use transparent image, default is false

        //                         //PI: '#f55066',

        //     correctLevel: QRCode.CorrectLevel.H // L, M, Q, H
        // });
    }

}