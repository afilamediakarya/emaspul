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

                        <a href="javascript:;" target="_blank" data-label="${data.file_document}" class="btn btn-primary button-show btn-sm"> <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_310_12505)"><path opacity="0.3" d="M2.42857 1H5.86821C6.04555 1 6.21715 1.06284 6.35256 1.17737L8.73435 3.19197C8.90283 3.33447 9 3.54394 9 3.7646V10.0417C9 10.9369 8.98978 11 8.07143 11H2.42857C1.51022 11 1.5 10.9369 1.5 10.0417V1.95833C1.5 1.06306 1.51022 1 2.42857 1ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/><path d="M3.42857 2H7.36821C7.54555 2 7.71715 2.06284 7.85256 2.17737L10.2343 4.19197C10.4028 4.33447 10.5 4.54394 10.5 4.7646V11.0417C10.5 11.9369 10.4898 12 9.57143 12H3.42857C2.51022 12 2.5 11.9369 2.5 11.0417V2.95833C2.5 2.06306 2.51022 2 3.42857 2ZM4 6.5C3.72386 6.5 3.5 6.72386 3.5 7C3.5 7.27614 3.72386 7.5 4 7.5H7.5C7.77614 7.5 8 7.27614 8 7C8 6.72386 7.77614 6.5 7.5 6.5H4ZM4 8.5C3.72386 8.5 3.5 8.72386 3.5 9C3.5 9.27614 3.72386 9.5 4 9.5H5.5C5.77614 9.5 6 9.27614 6 9C6 8.72386 5.77614 8.5 5.5 8.5H4Z" fill="#F1F3F6"/></g><defs><clipPath id="clip0_310_12505"><rect width="12" height="12" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>Tampilkan</a>
                        `;
                },
            }
        ];
        control.initDatatable('/general/datatable-list?jenis=5&type=type_b',columns,columnDefs);
        control.form_upload();
       

    })

</script>
@endsection