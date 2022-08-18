@extends('general.layout')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('button')
<button class="btn btn_general btn-sm " data-kt-drawer-show="true" data-kt-drawer-target="#side_form" id="button-side-form"><i class="fa fa-plus-circle" style="color:#ffffff" aria-hidden="true"></i> Tambah Dokumen RMJDes</button>
@endsection
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
<!--begin::Container-->
<div id="kt_content_container" class="container">
    <div class="row">

    <div class="card">
        <div class="card-body p-0">

            <div class="container">
            <div class="py-5">
                <table id="kt_table_data" class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Periode</th>
                            <th>Verifikator</th>
                            <th>status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        
        </div>
    </div>

    </div>
</div>
<!--end::Container-->
</div>
@endsection
@section('side-form')
<div id="side_form" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle="#side_form_button" data-kt-drawer-close="#side_form_close" data-kt-drawer-width="500px">
    <!--begin::Card-->
    <div class="card w-100">
        <!--begin::Card header-->
        <div class="card-header pe-5">
            <!--begin::Title-->
            <div class="card-title">
                <!--begin::User-->
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1 title_side_form"></a>
                </div>
                <!--end::User-->
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="side_form_close">
                    <!--begin::Svg Icon | path: icons/duotone/Navigation/Close.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body hover-scroll-overlay-y">
           <form class="form-data">
            
                <input type="hidden" name="id">
                <div class="mb-10">
                    <label class="form-label">Nama Dokumen</label>
                    <select name="nama_documents" class="form-control form-control-solid" id="nama_documents">
                        <option selected disabled>Pilih Nama Dokumen</option>
                        <option value="Dokumen RPJMDes Pokok">Dokumen RPJMDes Pokok</option>
                        <option value="Dokumen RPJMDes Perubahan">Dokumen RPJMDes Perubahan</option>
                    </select>
                    <small class="text-danger nama_documents_error"></small>
                </div>
                <input type="hidden" name="referensi_nama_dokumen" id="referensi_nama_dokumen" value="dokumen_desa">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Pilih Tahun Awal</label>
                            <input type="text" class="form-control form-control-solid datepicker" id="periode_awal" name="periode_awal" />
                            <small class="text-danger periode_awal_error"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Pilih Tahun Akhir</label>
                            <input type="text" class="form-control form-control-solid datepicker" id="periode_akhir" name="periode_akhir" />
                            <small class="text-danger periode_akhir_error"></small>
                         </div>
                    </div>
                </div>

                <div class="form-group row">
        <!--begin::Label-->
        <label class="col-lg-2 col-form-label text-lg-right">Upload File RKPD</label>
        <!--end::Label-->

        <!--begin::Col-->
        <div class="col-lg-10">
            <!--begin::Dropzone-->
            <div class="dropzone dropzone-queue mb-2" id="kt_dropzonejs_example_2">
                <!--begin::Controls-->
                <div class="dropzone-panel mb-lg-0 mb-2">
                    <a class="dropzone-select btn btn-sm btn-primary me-2">Pilih file</a>
                </div>
                <!--end::Controls-->

                <!--begin::Items-->
                <div class="dropzone-items wm-200px">
                    <div class="dropzone-item" style="display:none">
                        <!--begin::File-->
                        <div class="dropzone-file">
                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                <span data-dz-name>some_image_file_name.jpg</span>
                                <strong>(<span data-dz-size>340kb</span>)</strong>
                            </div>

                            <div class="dropzone-error" data-dz-errormessage></div>
                        </div>
                        <!--end::File-->

                        <!--begin::Progress-->
                        <div class="dropzone-progress">
                            <div class="progress">
                                <div
                                    class="progress-bar bg-primary"
                                    role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                                </div>
                            </div>
                        </div>
                        <!--end::Progress-->

                        <!--begin::Toolbar-->
                        <div class="dropzone-toolbar">
                            <span class="dropzone-start"><i class="bi bi-play-fill fs-3"></i></span>
                            <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="bi bi-x fs-3"></i></span>
                            <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                </div>
                <!--end::Items-->
                <small class="text-danger file_error"></small>
            </div>
            <!--end::Dropzone-->

            <!--begin::Hint-->
            <span class="form-text text-muted">Max file size is 1MB and max number of files is 5.</span>
            <!--end::Hint-->
        </div>
        <!--end::Col-->
    </div>

                <div class="separator separator-dashed mt-8 mb-5"></div>
                <div class="progress mb-5" style="display:none">
                    <div id="myBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                    <div class="">
                        <button type="submit" class="btn btn_general btn-sm btn-submit">Simpan</button>
                        <button type="reset" class="btn mr-2 btn-light btn-cancel btn-sm">Batal</button>
                    </div>
           </form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-xl">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> title</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
               <div class="box_detail">
                    <div class="row">
                        <div class="col-lg-6">
                            <span class="fz-12">Nama Dokumen</span>
                            <p class="fz-16 content_detail_1"></p>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-6">
                            <span class="fz-12">Status</span>
                            <p class="fz-16 content_detail_2"></p>
                        </div>
                        <div class="col-lg-6">
                            <span class="fz-12">Verifikator</span>
                            <p class="fz-16 content_detail_3"></p>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-6">
                            <span class="fz-12">Nomor Perbub</span>
                            <p class="fz-16 content_detail_4"></p>
                        </div>
                        <div class="col-lg-6">
                            <span class="fz-12">Tanggal Perbub</span>
                            <p class="fz-16 content_detail_5"></p>
                        </div>
                   </div>
               </div>

               <table id="table_detail" class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>No</th>
                            <th>Indikator</th>
                            <th>Kesesuaian</th>
                            <th>Tindak Lanjut Penyempurnaan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
     let control = new Control('type_2');
     let element = ['nama_documents','periode_awal','periode_akhir','referensi_nama_dokumen'];
    $(document).on('click','#button-side-form', function () {
        $('#password_content').show();
        control.overlay_form('Tambah','Data RPJMDes');
    })

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        if (type == 'add') {
            control.submitFormMultipart('/general/storeDocuments?jenis=rpjmdes&type=type_b','Tambah','Dokumen RPJMDes',element);
        }else{
            let id = $("input[name='id']").val();
            control.submitFormMultipart('/general/updateDocuments/'+id+'?jenis=rpjmdes&type=type_b','Update','Dokumen RPJMDes',element);
        }
    });

    $(document).on('change','#desa-select', function () {
        let text = $("#desa-select option:selected").text();
        $('input[name="nama_desa"]').val(text);
    })

    $(document).on('click','.button-update', function (e) {
        e.preventDefault();
        let url = '/general/byParams/'+$(this).attr('data-id');
        control.overlay_form('Update','Data RPJMDes', url);
    })

    $(document).on('click','.button-detail', function (e) {
        e.preventDefault();
        
       let url = `/get-data/documentByVerifikasi?jenis=${$(this).attr('data-jenis')}&document=${$(this).attr('data-id')}`;
        control.modal_content('Detail RPJMDes', url);
    })

    $(function () {
        $(".datepicker").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true
        }); 
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'nama_documents'
            },{
                data:null
            },{
                data:'verifikator'
            },{
                data:null
            },{
                data:null,
            }
        ];
        let columnDefs = [
            {
                targets: 2,
                render: function(data, type, full, meta) {
                    return data.periode_awal+' - '+data.periode_akhir;
                }
            },
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    if (data == null) {
                        return '-'; 
                    }else{
                        return data; 
                    }
                    
                }
            },
            {
                targets: 4,
                render: function(data, type, full, meta) {
                    
                    if (data.status_document == '1') {
                      return  `<button class="btn btn-light-danger btn-sm">Belum Verifikasi</button>`;
                    }else if(data.status_document == '2'){
                        return  `<button class="btn btn-light-dark btn-sm">Perbaikan</button>`;
                    }else if(data.status_document == '3'){
                        return  `<button class="btn btn-light-warning btn-sm">Belum Selesai</button>`;
                    }else{
                        return  `<button class="btn btn-light-success btn-sm"> Selesai</button>`;
                    }
                }
            },
            {
                targets: -1,
                title: 'Aksi',
                // width: '15rem',
                orderable: false,
                render: function(data, type, full, meta) {
                    let disabled = '';
                    if (data.status_document == '4' || data.status_document == '2') {
                        disabled = 'disabled'
                    }
                    return `
                        <button type="button" ${disabled} data-id="${data.id}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn_green button-update btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Edit</button>

                        <a href="javascript:;" type="button" data-id="${data.id}" data-jenis="${data.jenis_document}"  data-bs-toggle="modal" data-bs-target="#kt_modal_1" class="btn btn-info button-detail btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Lihat</a>
                        `;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=1&type=type_b',columns,columnDefs);
        control.form_upload();
       

    })

</script>
@endsection