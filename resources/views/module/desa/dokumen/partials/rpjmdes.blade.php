@extends('general.layout')
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
                            <th>Nama Desa</th>
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
                    <input type="text" id="nama_documents" class="form-control form-control-solid" name="nama_documents" placeholder="Masukkan Nama Dokumen">
                    <small class="text-danger nama_documents_error"></small>
                </div>
           
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Pilih Tahun Awal</label>
                            <select name="periode_awal" class="form-control form-control-solid" id="periode_awal">
                                <option selected disabled> Pilih Tahun </option>
                                <option value="2020">2020</option>
                                <option value="2021"> 2021 </option>
                                <option value="2023"> 2023 </option>
                                <option value="2024"> 2024</option>
                                <option value="2025"> 2025 </option>
                            </select>
                            <small class="text-danger periode_awal_error"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Pilih Tahun Akhir</label>
                            <select name="periode_akhir" class="form-control form-control-solid" id="periode_akhir">
                                <option selected disabled> Pilih Tahun </option>
                                <option value="2020">2020</option>
                                <option value="2021"> 2021 </option>
                                <option value="2023"> 2023 </option>
                                <option value="2024"> 2024</option>
                                <option value="2025"> 2025 </option>
                            </select>
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
            <span class="form-text text-muted">Max file size is 25MB and max number of files is 1.</span>
            <!--end::Hint-->
        </div>
        <!--end::Col-->
    </div>

                <div class="separator separator-dashed mt-8 mb-5"></div>
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
    <div class="modal-dialog modal-md modal-xl modal-dialog-centered">
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
                    
               </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_2" aria-hidden="true">
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
                <a href="#" id="konsederan" style="width: 18%" target="_blank" class="btn btn-danger mt-5 btn-sm">
                <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.2667 5.82386H10.6476C10.5795 5.82386 10.5238 5.87957 10.5238 5.94767V6.56671C10.5238 6.63481 10.5795 6.69052 10.6476 6.69052H11.2667C11.3348 6.69052 11.3905 6.63481 11.3905 6.56671V5.94767C11.3905 5.87957 11.3348 5.82386 11.2667 5.82386ZM11.7619 4.21433H9.90476V0.93338C9.90476 0.865285 9.84905 0.80957 9.78095 0.80957H3.21905C3.15095 0.80957 3.09524 0.865285 3.09524 0.93338V4.21433H1.2381C0.554048 4.21433 0 4.76838 0 5.45243V10.5286C0 10.8025 0.22131 11.0239 0.495238 11.0239H3.09524V13.0667C3.09524 13.1348 3.15095 13.1905 3.21905 13.1905H9.78095C9.84905 13.1905 9.90476 13.1348 9.90476 13.0667V11.0239H12.5048C12.7787 11.0239 13 10.8025 13 10.5286V5.45243C13 4.76838 12.446 4.21433 11.7619 4.21433ZM4.14762 1.86195H8.85238V4.21433H4.14762V1.86195ZM8.85238 12.1381H4.14762V7.86671H8.85238V12.1381ZM11.9476 9.97147H9.90476V6.81433H3.09524V9.97147H1.05238V5.45243C1.05238 5.35028 1.13595 5.26671 1.2381 5.26671H11.7619C11.864 5.26671 11.9476 5.35028 11.9476 5.45243V9.97147Z" fill="white"/>
                </svg>
                Cetak Konsederan</a>
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
<script>
     let control = new Control('type_2');
     let role = {!! json_encode($role) !!};
    // /storage/files/dokumen_daerah/rpjmdes/
    $(document).on('click','.button-show', function (e) {
        e.preventDefault();
        window.open('/storage/files/dokumen_desa/rpjmdes/'+$(this).attr('data-label'), '_blank');
    })

    $(document).on('click', '.btn-verifikasi', function (e) {
            e.preventDefault();
            let params = $(this).attr('data-id');
          window.location.href = `/verifikasi?document=${params}&jenis=RPJMDes`;
     })

     $(document).on('click','.button-detail', function (e) {
        e.preventDefault();
        
       let url = `/get-data/documentByVerifikasi?jenis=${$(this).attr('data-jenis')}&document=${$(this).attr('data-id')}`;
        control.modal_content('Detail RPJMDes', url,`/dokumen-desa/konsederan?document=${$(this).attr('data-id')}&jenis=rpjmdes`);
    })

    $(document).on('keyup', '#search_', function (e) {
        e.preventDefault();
        control.searchTable(this.value);
    })

    $(function () {
      
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'nama_desa'
            },{
                data:'nama_documents'
            },{
                data:null
            },{
                data:'verifikator'
            },{
                data:null,
            },{
                data:null,
            }
        ];
        let columnDefs = [
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    return data.periode_awal+' - '+data.periode_akhir;
                }
            },
            {
                targets: 4,
                render: function(data, type, full, meta) {
                    if (data == null) {
                        return '-'; 
                    }else{
                        return data; 
                    }
                    
                }
            },
            {
                targets: 5,
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
                    if (data.status_document == '3') {
                        disabled = 'disabled'
                    }
                    let action = '';

                    if (role == 1) {
                    action = `<a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_310_12505)"><path opacity="0.3" d="M2.42857 1H5.86821C6.04555 1 6.21715 1.06284 6.35256 1.17737L8.73435 3.19197C8.90283 3.33447 9 3.54394 9 3.7646V10.0417C9 10.9369 8.98978 11 8.07143 11H2.42857C1.51022 11 1.5 10.9369 1.5 10.0417V1.95833C1.5 1.06306 1.51022 1 2.42857 1ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/><path d="M3.42857 2H7.36821C7.54555 2 7.71715 2.06284 7.85256 2.17737L10.2343 4.19197C10.4028 4.33447 10.5 4.54394 10.5 4.7646V11.0417C10.5 11.9369 10.4898 12 9.57143 12H3.42857C2.51022 12 2.5 11.9369 2.5 11.0417V2.95833C2.5 2.06306 2.51022 2 3.42857 2ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/></g><defs><clipPath id="clip0_310_12505"><rect width="12" height="12" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>Tampilkan</a>
                    <a href="javascript:;" type="button" data-id="${data.id}" data-jenis="${data.jenis_document}"  data-bs-toggle="modal" data-bs-target="#kt_modal_2" class="btn btn-info button-detail btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Lihat</a>
                        `; 
                    }else{
                        action = `<button ${disabled} type="button" data-id="${data.id}" class="btn btn-warning btn-verifikasi btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Verifikasi</button>
                            
                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_310_12505)"><path opacity="0.3" d="M2.42857 1H5.86821C6.04555 1 6.21715 1.06284 6.35256 1.17737L8.73435 3.19197C8.90283 3.33447 9 3.54394 9 3.7646V10.0417C9 10.9369 8.98978 11 8.07143 11H2.42857C1.51022 11 1.5 10.9369 1.5 10.0417V1.95833C1.5 1.06306 1.51022 1 2.42857 1ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/><path d="M3.42857 2H7.36821C7.54555 2 7.71715 2.06284 7.85256 2.17737L10.2343 4.19197C10.4028 4.33447 10.5 4.54394 10.5 4.7646V11.0417C10.5 11.9369 10.4898 12 9.57143 12H3.42857C2.51022 12 2.5 11.9369 2.5 11.0417V2.95833C2.5 2.06306 2.51022 2 3.42857 2ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/></g><defs><clipPath id="clip0_310_12505"><rect width="12" height="12" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>Tampilkan</a>`; 
                    }

                    return action;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=1&type=type_b',columns,columnDefs);
        control.form_upload();
    
    })

</script>
@endsection