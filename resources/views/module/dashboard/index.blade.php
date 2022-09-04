@extends('general.layout')
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
<!--begin::Container-->
<div id="kt_content_container" class="container">
    <div class="row">

        <div class="col-md-4">
            <div class="widget_dashboard widget_red">
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <div class="d-flex">
                    <h1>Jumlah Unit Kerja</h1>
                    <h2 id="jml_skpd">44 <span>Users</span></h2>
                    <h2 id="jml_desa" style="position: relative;left: 1rem;">44 <span>Users</span></h2>
                    </div>
                    
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                        <svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M31.5 0C33.9844 0 36 2.01469 36 4.5V43.5C36 45.9844 33.9844 48 31.5 48H22.5V40.5C22.5 38.0156 20.4844 36 18 36C15.5156 36 13.5 38.0156 13.5 40.5V48H4.5C2.01469 48 0 45.9844 0 43.5V4.5C0 2.01469 2.01469 0 4.5 0H31.5ZM6 25.5C6 26.325 6.67125 27 7.5 27H10.5C11.325 27 12 26.325 12 25.5V22.5C12 21.675 11.325 21 10.5 21H7.5C6.67125 21 6 21.675 6 22.5V25.5ZM16.5 21C15.675 21 15 21.675 15 22.5V25.5C15 26.325 15.675 27 16.5 27H19.5C20.325 27 21 26.325 21 25.5V22.5C21 21.675 20.325 21 19.5 21H16.5ZM24 25.5C24 26.325 24.675 27 25.5 27H28.5C29.325 27 30 26.325 30 25.5V22.5C30 21.675 29.325 21 28.5 21H25.5C24.675 21 24 21.675 24 22.5V25.5ZM7.5 9C6.67125 9 6 9.675 6 10.5V13.5C6 14.325 6.67125 15 7.5 15H10.5C11.325 15 12 14.325 12 13.5V10.5C12 9.675 11.325 9 10.5 9H7.5ZM15 13.5C15 14.325 15.675 15 16.5 15H19.5C20.325 15 21 14.325 21 13.5V10.5C21 9.675 20.325 9 19.5 9H16.5C15.675 9 15 9.675 15 10.5V13.5ZM25.5 9C24.675 9 24 9.675 24 10.5V13.5C24 14.325 24.675 15 25.5 15H28.5C29.325 15 30 14.325 30 13.5V10.5C30 9.675 29.325 9 28.5 9H25.5Z" fill="white" fill-opacity="0.8"/>
                        </svg> 
                    </div>
                 </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="widget_dashboard widget_yellow">
           
           
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <h1>Jumlah Dokumen</h1>
                    <div class="d-flex">
                    <h2 id="jml_document">44 <span>Users</span></h2>
                    </div>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                    <svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 0V12H36L24 0ZM21 12V0H4.5C2.01469 0 0 2.01469 0 4.5V43.5C0 45.9844 2.01469 48 4.5 48H31.5C33.9853 48 36 45.9853 36 43.5V15H24.0844C22.3406 15 21 13.6594 21 12ZM25.5 39H10.5C9.675 39 9 38.325 9 37.5C9 36.675 9.675 36 10.5 36H25.5C26.3284 36 27 36.6714 27 37.5C27 38.325 26.325 39 25.5 39ZM25.5 33H10.5C9.675 33 9 32.325 9 31.5C9 30.675 9.675 30 10.5 30H25.5C26.3284 30 27 30.6714 27 31.5C27 32.325 26.325 33 25.5 33ZM27 25.5C27 26.325 26.325 27 25.5 27H10.5C9.675 27 9 26.325 9 25.5C9 24.675 9.675 24 10.5 24H25.5C26.325 24 27 24.675 27 25.5Z" fill="white" fill-opacity="0.8"/>
                    </svg>


                    </div>
                 </div>

            </div>
        </div>
        <div class="col-md-4">
             <div class="widget_dashboard widget_green">
             
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <h1>Jumlah Terverifikasi</h1>
                    <div class="d-flex">
                    <h2 id="jml_terverifikasi">44 <span>Users</span></h2>
                    </div>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                        
                    <svg width="42" height="35" viewBox="0 0 42 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.477 0.499339C13.2809 1.21797 13.3465 2.44701 12.6164 3.24585L6.71344 9.73052C6.35168 10.1194 5.84144 10.3544 5.30168 10.2976C4.76191 10.3868 4.2402 10.1761 3.85793 9.73052L0.576598 6.4898C-0.192199 5.80115 -0.192199 4.56968 0.576598 3.81054C1.34531 3.0514 2.59219 3.0514 3.36082 3.81054L5.17453 5.59861L9.69609 0.64355C10.418 -0.154476 11.6648 -0.21929 12.477 0.499339ZM12.477 13.4654C13.2809 14.1784 13.3465 15.4099 12.6164 16.212L6.71344 22.6934C6.35168 23.0823 5.84144 23.3172 5.30168 23.2605C4.76191 23.3496 4.2402 23.139 3.85793 22.6934L0.576598 19.4527C-0.192199 18.764 -0.192199 17.5325 0.576598 16.771C1.34531 16.0175 2.59219 16.0175 3.36082 16.771L5.17453 18.5615L9.69609 13.6032C10.418 12.8092 11.6648 12.7444 12.477 13.4654ZM18.375 5.18541C18.375 3.75383 19.548 2.59284 21 2.59284H39.375C40.827 2.59284 42 3.75383 42 5.18541C42 6.61943 40.827 7.77799 39.375 7.77799H21C19.548 7.77799 18.375 6.61943 18.375 5.18541ZM18.375 18.1483C18.375 16.7143 19.548 15.5557 21 15.5557H39.375C40.827 15.5557 42 16.7143 42 18.1483C42 19.5823 40.827 20.7409 39.375 20.7409H21C19.548 20.7409 18.375 19.5823 18.375 18.1483ZM13.125 31.1111C13.125 29.6771 14.298 28.5186 15.75 28.5186H39.375C40.827 28.5186 42 29.6771 42 31.1111C42 32.5452 40.827 33.7037 39.375 33.7037H15.75C14.298 33.7037 13.125 32.5452 13.125 31.1111ZM0 31.1111C0 28.9642 1.76285 27.2223 3.9375 27.2223C6.11215 27.2223 7.875 28.9642 7.875 31.1111C7.875 33.2581 6.11215 35 3.9375 35C1.76285 35 0 33.2581 0 31.1111Z" fill="white" fill-opacity="0.8"/>
                    </svg>



                    </div>
                 </div>

            </div>
        </div>

        <div class="card mt-5">
        <div class="card-header">
        <h3 class="card-title">Progres Dokumen</h3>
        </div>
       
            <div class="card-body p-0">
                <div class="container">
                <div class="py-5">
                    <table id="kt_table_data" class="table table-row-dashed table-row-gray-300 gy-7">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th>No</th>
                                <th>Nama Unit Kerja</th>
                                <th>Nama Dokumen</th>
                                <th>Verifikator</th>
                                <th>Status</th>
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

      $(document).on('click','.button-detail', function (e) {
        e.preventDefault();

        let label = $(this).attr('data-label').split(" ");
        let link = 'dokumen-desa';

        // console.log(label);

        if (label[1] == 'Renstra' || label[1] == 'Renja') {
            link = 'dokumen-skpd';
        }
        
       let url = `/get-data/documentByVerifikasi?jenis=${$(this).attr('data-jenis')}&document=${$(this).attr('data-id')}`;
        control.modal_content(`Detail ${label[1]}`, url,`/${link}/konsederan?document=${$(this).attr('data-id')}&jenis=${label[1].toLowerCase()}`);
    })
    $(function () {
        $.ajax({
            url : '/get-data/dashboard',
            method : 'GET',
            success : function (res) {
                console.log(res);
                $('#jml_skpd').html(res.jml_skpd+' <span>SKPD</span>');
                $('#jml_desa').html(res.jml_desa+' <span>Desa</span>');
                $('#jml_document').html(res.jml_document+' <span>Dokumen</span>');
                $('#jml_terverifikasi').html(res.jml_terverifikasi+' <span>Dokumen</span>');

            },
            error : function (xhr) {
                alert('gagal');
            }
        });


        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'unit_kerja'
            },{
                data:'nama_documents'
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
                    return `<a href="javascript:;" type="button" data-id="${data.id}" data-label="${data.nama_documents}" data-jenis="${data.jenis_document}"  data-bs-toggle="modal" data-bs-target="#kt_modal_2" class="btn btn-info button-detail btn-sm">
                    <i class="fa fa-eye" aria-hidden="true" style="color:white"></i> Detail</a>`; 
                   
                },
            }
        ];
        control.initDatatable('/general/datatable-list?type=type_d&document_type=4',columns,columnDefs);

    })
</script>
@endsection