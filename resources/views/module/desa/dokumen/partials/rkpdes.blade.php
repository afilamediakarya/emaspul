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
                            <th>Nama Desa</th>
                            <th>Nama Dokumen</th>
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
@section('script')
<script>
     let control = new Control('type_2');
     let element = ['nama_documents','periode_awal','periode_akhir'];
    $(document).on('click','#button-side-form', function () {
        $('#password_content').show();
        control.overlay_form('Tambah','Data RKPD');
    })

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        if (type == 'add') {
            control.submitFormMultipart('/general/storeDocuments?jenis=rpjmdes','Tambah','Dokumen RPJMDes',element);
        }else{
            let id = $("input[name='id']").val();
            control.submitFormMultipart('/general/updateDocuments/'+id+'?jenis=rpjmdes','Update','Dokumen RPJMDes',element);
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
        // let url = '/general/byParams/'+$(this).attr('data-id');
       let url = '';
        control.modal_content('Detail RPJMDes', url);
    })

    // /storage/files/dokumen_daerah/rpjmdes/
    $(document).on('click','.button-show', function (e) {
        e.preventDefault();
        window.open('/storage/files/dokumen_daerah/rkpdes/'+$(this).attr('data-label'), '_blank');
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
                data:'verifikator'
            },{
                data:null
            },{
                data:null,
            }
        ];
        let columnDefs = [
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    console.log(data);
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
                    console.log(data);
                    return `
                        <a href="/dokumen-desa/verifikasi?document=${data.id}&jenis=RKPDes" type="button" data-id="${data.id}" class="btn btn-warning btn-sm"> <svg style="position: relative;bottom: 2px;" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0.515625C10.0938 0.515625 9.74479 0.661458 9.45312 0.953125L4.39062 6L4.01562 7.90625L3.85938 8.64062L4.59375 8.48438L6.5 8.10938L11.5469 3.04688C11.8385 2.75521 11.9844 2.40625 11.9844 2C11.9844 1.59375 11.8385 1.24479 11.5469 0.953125C11.2552 0.661458 10.9062 0.515625 10.5 0.515625ZM10.5 1.48438C10.6146 1.48438 10.7292 1.54167 10.8438 1.65625C10.9583 1.77083 11.0156 1.88542 11.0156 2C11.0156 2.11458 10.9583 2.22917 10.8438 2.34375L6 7.1875L5.14062 7.35938L5.3125 6.5L10.1562 1.65625C10.2708 1.54167 10.3854 1.48438 10.5 1.48438ZM0 2.5V12.5H10V5.90625L9 6.90625V11.5H1V3.5H5.59375L6.59375 2.5H0Z" fill="white"/></svg> Verifikasi</a>

                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Tampilkan</a>
                        `;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=2',columns,columnDefs);
        control.form_upload();
       

    })

</script>
@endsection