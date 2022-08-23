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


    // /storage/files/dokumen_daerah/rpjmdes/
    $(document).on('click','.button-show', function (e) {
        e.preventDefault();
        window.open('/storage/files/dokumen_desa/sdgs/'+$(this).attr('data-label'), '_blank');
    })

    $(function () {
      
        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'nama_desa',
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
                // width: '15rem',
                orderable: false,
                render: function(data, type, full, meta) {
                    console.log(data);
                    return `

                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i> Tampilkan</a>
                        `;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=5&type=type_b',columns,columnDefs);
        control.form_upload();
       

    })

</script>
@endsection