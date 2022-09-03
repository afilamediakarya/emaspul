@extends('general.layout')
@section('button')
    @if($role == 1)
    <button class="btn btn_general btn-sm " data-kt-drawer-show="true" data-kt-drawer-target="#side_form" id="button-side-form"><i class="fa fa-plus-circle" style="color:#ffffff" aria-hidden="true"></i> Tambah Dokumen RKPD</button>
    @endif
@endsection
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
<!--begin::Container-->
<div id="kt_content_container" class="container">
    <div class="row">

    <div class="card">
        <div class="card-body p-0">

        <div class="d-flex justify-content-end mt-5">
                <span class="svg-icon svg-icon-1">
                    <svg style="position: relative;left: 34px; top: 10px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                            <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
                        </g>
                    </svg>
                </span>
            
                <input type="text" id="search_" class="form-control form-control-solid w-250px ps-15" placeholder="Search">
            </div>

            <div class="container">
            <div class="py-5">
                <table id="kt_table_data" class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Nomor Perbub</th>
                            <th>Tanggal Perbub</th>
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
                        <option value="Dokumen RKPD Pokok">Dokumen RKPD Pokok</option>
                        <option value="Dokumen RKPD Perubahan">Dokumen RKPD Perubahan</option>
                    </select>
                    <small class="text-danger nama_documents_error"></small>
                </div>
                <input type="hidden" name="referensi_nama_dokumen" id="referensi_nama_dokumen" value="dokumen_daerah">
                <div class="mb-10">
                    <label class="form-label">Nomor Perbub</label>
                    <input type="text" class="form-control form-control-solid" id="nomor_perbub" name="nomor_perbub" placeholder="Masukkan Nomor Perbub">
                    <small class="text-danger nomor_perbub_error"></small>
                </div>

                <div class="mb-10">
                    <label class="form-label">Tanggal Perbub</label>
                    <input type="date" class="form-control form-control-solid" id="tanggal_perbub" name="tanggal_perbub">
                    <small class="text-danger tanggal_perbub_error"></small>
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
            <span class="form-text text-muted">Max file size is 25MB and max number of files is 1.</span>
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

@endsection
@section('script')
<script>
     let control = new Control('type_2');
     let element = ['nama_documents','nomor_perbub','tanggal_perbub','referensi_nama_dokumen'];
     let role = {!! json_encode($role) !!};
    $(document).on('click','#button-side-form', function () {
        $('#password_content').show();
        control.overlay_form('Tambah','Data RKPD');
    })

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        if (type == 'add') {
            control.submitFormMultipart('/general/storeDocuments?jenis=rkpd&type=type_a','Tambah','RKPD',element);
        }else{
            let id = $("input[name='id']").val();
            control.submitFormMultipart('/general/updateDocuments/'+id+'?jenis=rkpd&type=type_a','Update','RKPD',element);
        }
    });

    $(document).on('change','#desa-select', function () {
        let text = $("#desa-select option:selected").text();
        $('input[name="nama_desa"]').val(text);
    })

    $(document).on('click','.button-update', function (e) {
        e.preventDefault();
        $('#password_content').hide();
        let url = '/general/byParams/'+$(this).attr('data-id');
        control.overlay_form('Update','Data RPJMD', url);
    })

    $(document).on('click','.button-show', function (e) {
        e.preventDefault();
        window.open('/storage/files/dokumen_daerah/rkpd/'+$(this).attr('data-label'), '_blank');
    })

    $(document).on('keyup', '#search_', function (e) {
        e.preventDefault();
        control.searchTable(this.value);
    })

    function formatDate(date) {
        let data = date.split("-")
     return [data[2], data[1], data[0]].join('/');
    }

    $(function () {
      
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'nama_documents'
            },{
                data:'nomor_perbub'
            },{
                data:'tanggal_perbub'
            },{
                data:null,
            }
        ];
        let columnDefs = [
            {
                targets: 3,
                render: function(data) {
                    return formatDate(data);
                }
            },
            {
                targets: -1,
                title: 'Aksi',
                // width: '15rem',
                orderable: false,
                render: function(data, type, full, meta) {
                    if (role == 1) {
                        return `
                        <a href="javascript:;" type="button" data-id="${data.id}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn_green button-update btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Edit</a>
                        `;
                    }else{
                        return `<a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_310_12505)"><path opacity="0.3" d="M2.42857 1H5.86821C6.04555 1 6.21715 1.06284 6.35256 1.17737L8.73435 3.19197C8.90283 3.33447 9 3.54394 9 3.7646V10.0417C9 10.9369 8.98978 11 8.07143 11H2.42857C1.51022 11 1.5 10.9369 1.5 10.0417V1.95833C1.5 1.06306 1.51022 1 2.42857 1ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/><path d="M3.42857 2H7.36821C7.54555 2 7.71715 2.06284 7.85256 2.17737L10.2343 4.19197C10.4028 4.33447 10.5 4.54394 10.5 4.7646V11.0417C10.5 11.9369 10.4898 12 9.57143 12H3.42857C2.51022 12 2.5 11.9369 2.5 11.0417V2.95833C2.5 2.06306 2.51022 2 3.42857 2ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/></g><defs><clipPath id="clip0_310_12505"><rect width="12" height="12" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>Tampilkan</a>`;
                    }
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=9&type=type_a&type_query=query_2',columns,columnDefs);
        control.form_upload();
       

    })

</script>
@endsection