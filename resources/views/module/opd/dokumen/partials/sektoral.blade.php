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
    // /storage/files/dokumen_daerah/rpjmdes/
    $(document).on('click','.button-show', function (e) {
        e.preventDefault();
        window.open('/storage/files/dokumen_opd/renstra/'+$(this).attr('data-label'), '_blank');
    })

    $(document).on('click', '.btn-verifikasi', function (e) {
            e.preventDefault();
            let params = $(this).attr('data-id');
          window.location.href = `/dokumen-desa/verifikasi?document=${params}&jenis=Renstra`;
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
                data:null,
            }
        ];
        let columnDefs = [
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
                    return `
                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Tampilkan</a>
                        `;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=6&type=type_c',columns,columnDefs);
        control.form_upload();
    
    })

</script>
@endsection