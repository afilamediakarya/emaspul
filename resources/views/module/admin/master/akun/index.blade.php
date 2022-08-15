@extends('general.layout')
@section('button')
<button class="btn btn_general btn-sm " data-kt-drawer-show="true" data-kt-drawer-target="#side_form" id="button-side-form"><i class="fa fa-plus-circle" style="color:#ffffff" aria-hidden="true"></i> Tambah Akun</button>
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
                            <th>Username</th>
                            <th>Nama lengkap</th>
                            <th>Bidang</th>
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
                <div class="mb-10">
                    <label class="form-label">Pilih Role</label>
                    <select class="form-control form-control-solid" id="role" name="role">
                        <option disabled selected> Pilih Role </option>
                        <option value="2">Admin OPD</option>
                        <option value="3">Desa</option>
                        <option value="4">Verifikator</option>
                    </select>
                    <small class="text-danger role_error"></small>
                </div>

                <div class="mb-10">
                    <label class="form-label">Pilih Perangkat / Bidang</label>
                    <select class="form-select" id="select_" name="perangkat" data-control="select2" data-placeholder="Pilih Desa">
                    </select>
                    <small class="text-danger perangkat_error"></small>
                </div>
               
                <input type="hidden" name="id">
                <div class="mb-10">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control form-control-solid" name="username" placeholder="Masukkan username">
                    <small class="text-danger username_error"></small>
                </div>

                <div class="row" id="password_content">
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control form-control-solid" name="password" placeholder="Masukkan password">
                            <small class="text-danger password_error"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-10">
                            <label class="form-label">Ulangi password</label>
                            <input type="password" class="form-control form-control-solid" name="password_confirmation" placeholder="Masukkan konfirmasi password">
                            <small class="text-danger password_confirmation_error"></small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-10">
                    <label class="form-label">NIP/NIK</label>
                    <input type="text" class="form-control form-control-solid" name="nip" placeholder="Masukkan nip">
                    <small class="text-danger nip_error"></small>
                </div>
             
                <div class="mb-10">
                    <label class="form-label">Nama lengkap</label>
                    <input type="text" class="form-control form-control-solid" name="nama_lengkap" placeholder="Nama lengkap">
                    <small class="text-danger nama_lengkap_error"></small>
                </div>

                <div class="mb-10">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control form-control-solid" name="jabatan" placeholder="Masukkan jabatan">
                    <small class="text-danger jabatan_error"></small>
                </div>

                <div class="mb-10">
                    <label class="form-label">No. telp</label>
                    <input type="text" class="form-control form-control-solid" name="no_telp" placeholder="Masukkan no telp">
                    <small class="text-danger no_telp_error"></small>
                </div>

                <div class="mb-10 mt-5">
                    <label class="form-label">Status</label>
                    <div class="form-group">
                        <div class="radio-inline">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" id="aktif" value="1" type="radio" name="status" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid mt-3">
                                <input class="form-check-input" id="tidak_aktif" value="0" type="radio" name="status" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                   Tidak Aktif
                                </label>
                            </div>
                        </div>
                    </div>    

                    <small class="text-danger status_error"></small>
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

@endsection
@section('script')
<script>
     let control = new Control('type_2');
    $(document).on('click','#button-side-form', function () {
        $('#password_content').show();
        control.overlay_form('Tambah','Akun');
    })

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        if (type == 'add') {
            alert('add');
            control.submitForm('/akun/store','Tambah','Akun');
        }else{
            alert('update');
            let id = $("input[name='id']").val();
            control.submitForm('/akun/update/'+id,'Update','Akun');
        }
    });

    $(document).on('change','#desa-select', function () {
        let text = $("#desa-select option:selected").text();
        $('input[name="nama_desa"]').val(text);
    })

    $(document).on('click','.button-update', function (e) {
        e.preventDefault();
        $('#password_content').hide();
        let url = '/akun/byParams/'+$(this).attr('data-id');
        control.overlay_form('Update','Akun', url);
    })

    $(document).on('change', '#role', function () {
       let data = $(this).val();
       if (data == 4) {
            control.push_select('/get-data/bidang','#select_');
       }else if(data == 3){
            control.push_select('/get-data/perangkat-desa','#select_');
       }else{
        control.push_select('/get-data/unit-kerja','#select_');
       }
    })

    $(function () {
      
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'username'
            },{
                data:'nama_lengkap'
            },{
                data:'bidang'
            },{
                data:'id',
            }
        ];
        let columnDefs = [
            {
                targets: 3,
                width: '28rem'
            },
            {
                targets: -1,
                title: 'Aksi',
                // width: '15rem',
                orderable: false,
                render: function(data, type, full, meta) {
                    return `
                        <a href="javascript:;" type="button" data-id="${data}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn_green button-update btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Edit</a>

                        `;
                },
            }
        ];
        control.initDatatable('/akun/datatable-list',columns,columnDefs);

       

    })

</script>
@endsection