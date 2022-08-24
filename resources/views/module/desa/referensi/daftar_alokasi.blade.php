@extends('general.layout')
@section('button')
<a href="/akun-desa/referensi/data-alokasi-skpd?type=export" target="_blank" role="button" class="btn btn-danger btn-sm ">  <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.2667 5.82386H10.6476C10.5795 5.82386 10.5238 5.87957 10.5238 5.94767V6.56671C10.5238 6.63481 10.5795 6.69052 10.6476 6.69052H11.2667C11.3348 6.69052 11.3905 6.63481 11.3905 6.56671V5.94767C11.3905 5.87957 11.3348 5.82386 11.2667 5.82386ZM11.7619 4.21433H9.90476V0.93338C9.90476 0.865285 9.84905 0.80957 9.78095 0.80957H3.21905C3.15095 0.80957 3.09524 0.865285 3.09524 0.93338V4.21433H1.2381C0.554048 4.21433 0 4.76838 0 5.45243V10.5286C0 10.8025 0.22131 11.0239 0.495238 11.0239H3.09524V13.0667C3.09524 13.1348 3.15095 13.1905 3.21905 13.1905H9.78095C9.84905 13.1905 9.90476 13.1348 9.90476 13.0667V11.0239H12.5048C12.7787 11.0239 13 10.8025 13 10.5286V5.45243C13 4.76838 12.446 4.21433 11.7619 4.21433ZM4.14762 1.86195H8.85238V4.21433H4.14762V1.86195ZM8.85238 12.1381H4.14762V7.86671H8.85238V12.1381ZM11.9476 9.97147H9.90476V6.81433H3.09524V9.97147H1.05238V5.45243C1.05238 5.35028 1.13595 5.26671 1.2381 5.26671H11.7619C11.864 5.26671 11.9476 5.35028 11.9476 5.45243V9.97147Z" fill="white"/>
                </svg> Cetak</a>
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
                            <th>Nama SKPD</th>
                            <th>Nama Paket</th>
                            <th>Volume</th>
                            <th>Pagu</th>
                            <th>Sumber Dana</th>
                            <th>Lokasi</th>
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
                data:'nama_paket'
            },{
                data:'volume'
            },{
                data:'pagu',
            },{
                data:'sumber_dana', 
            },{
                data:'lokasi', 
            }

        ];
        let columnDefs = [
            {
                targets: 1,
                width : '10rem'
            },
            {
                targets: 2,
                width : '15rem'
            },
            {
                targets: 4,
                render: function(data, type, full, meta) {
                    return 'Rp. '+data;
                }
            },
        ];
        control.initDatatable('/akun-desa/referensi/data-alokasi-skpd?type=datatable',columns,columnDefs);
      
       

    })

</script>
@endsection