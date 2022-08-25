@extends('general.layout')
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
                            <th>Nama Satuan Kerja</th>
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

            <a href="#" id="konsederan" style="width: 18%" target="_blank" style="width: 18%" class="btn btn-danger mt-5 btn-sm">
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
        window.open('/storage/files/dokumen_skpd/renstra/'+$(this).attr('data-label'), '_blank');
    })

    $(document).on('click', '.btn-verifikasi', function (e) {
            e.preventDefault();
            let params = $(this).attr('data-id');
          window.location.href = `/dokumen-desa/verifikasi?document=${params}&jenis=Renstra`;
     })

     $(document).on('click','.button-detail', function (e) {
        e.preventDefault();
        
       let url = `/get-data/documentByVerifikasi?jenis=${$(this).attr('data-jenis')}&document=${$(this).attr('data-id')}`;
        control.modal_content('Detail Renstra', url, `/dokumen-skpd/konsederan?document=${$(this).attr('data-id')}&jenis=renstra`);
    })

    $(function () {
      
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'nama_unit_kerja'
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
                width: '20rem',
                orderable: false,
                render: function(data, type, full, meta) {
                    let disabled = '';
                    if (data.status_document == '3') {
                        disabled = 'disabled'
                    }
                    let action = '';

                    if (role == 1) {
                       action = `<a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Tampilkan</a>

                       <a href="javascript:;" type="button" data-id="${data.id}" data-jenis="${data.jenis_document}"  data-bs-toggle="modal" data-bs-target="#kt_modal_2" class="btn btn-info button-detail btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Lihat</a>
                        `; 
                    }else{
                        action = `<button ${disabled} type="button" data-id="${data.id}" class="btn btn-warning btn-verifikasi btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Verifikasi</button>
                            
                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Tampilkan</a>`; 
                    }

                    return action;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=3&type=type_c',columns,columnDefs);
        control.form_upload();
    
    })

</script>
@endsection