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
                    <h1>Jumlah Paket</h1>
                    <h2 id="jml_paket" ></h2>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                    <svg width="48" height="43" viewBox="0 0 48 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.3417 3.33392L22.3417 0.31216C23.4083 -0.0903814 24.5917 -0.0903814 25.6583 0.31216L33.6583 3.33392C35.4667 4.01879 36.6667 5.74883 36.6667 7.68059V16.9932C36.775 17.0265 36.8833 17.0597 36.9917 17.0265L44.9917 20.1229C46.8 20.8036 48 22.5387 48 24.4646V34.36C48 36.203 46.9083 37.8716 45.2083 38.6104L37.2083 42.1137C36.0167 42.645 34.65 42.645 33.4583 42.1137L24 37.9795L14.5417 42.1137C13.35 42.645 11.9833 42.645 10.7917 42.1137L2.78833 38.6104C1.09417 37.8716 0 36.203 0 34.36V24.4646C0 22.5387 1.19917 20.8036 3.0125 20.1229L11.0083 17.0265C11.1167 17.0597 11.225 17.0265 11.3333 16.9932V7.68059C11.3333 5.74883 12.5333 4.01879 14.3417 3.33392ZM24.2333 4.03789C24.0833 3.9806 23.8417 3.9806 23.7667 4.03789L17.2333 6.50427L23.925 9.09019L30.7667 6.50427L24.2333 4.03789ZM32.6667 17.483V10.0449L25.8 12.6682V20.0731L32.6667 17.483ZM12.9 20.8286C12.75 20.7704 12.5083 20.7704 12.4333 20.8286L5.90083 23.2941L12.6667 25.8759L19.4333 23.2941L12.9 20.8286ZM14.4667 37.7969L21.3333 34.7917V26.8306L14.4667 29.4538V37.7969ZM28.5667 23.2941L35.3333 25.8759L42.1 23.2941L35.5667 20.8286C35.4167 20.7704 35.175 20.7704 35.1 20.8286L28.5667 23.2941ZM44 34.36V26.8306L37.1333 29.4538V37.7969L43.6 34.9661C43.8417 34.8581 44 34.6257 44 34.36Z" fill="white" fill-opacity="0.8"/>
                    </svg>

                    </div>
                 </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="widget_dashboard widget_yellow">
           
           
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <h1>Total Pagu Paket</h1>
                    <h2 id="jml_pagu"></h2>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                    <svg width="48" height="42" viewBox="0 0 48 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 0C43.6594 0 45 1.34344 45 3C45 4.65656 43.6594 6 42 6H7.5C6.67125 6 6 6.675 6 7.5C6 8.325 6.67125 9 7.5 9H42C45.3094 9 48 11.6906 48 15V36C48 39.3094 45.3094 42 42 42H6C2.68594 42 0 39.3094 0 36V6C0 2.68594 2.68594 0 6 0H42ZM39 28.5C40.6594 28.5 42 27.1594 42 25.5C42 23.8406 40.6594 22.5 39 22.5C37.3406 22.5 36 23.8406 36 25.5C36 27.1594 37.3406 28.5 39 28.5Z" fill="white" fill-opacity="0.8"/>
                    </svg>


                    </div>
                 </div>

            </div>
        </div>
        <div class="col-md-4">
             <div class="widget_dashboard widget_green">
             
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <h1>Total ADD</h1>
                    <h2 id="total_add">44 <span>Users</span></h2>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                        
                    <svg width="27" height="48" viewBox="0 0 27 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.5007 0C15.1601 0 16.5007 1.34344 16.5007 3V6.345C16.6507 6.36469 16.7914 6.38625 16.9414 6.40781C17.9351 6.55594 20.8976 7.03312 22.1164 7.34156C23.6351 7.74844 24.6945 9.38437 24.282 10.9875C23.8789 12.5906 22.2476 13.5656 20.6351 13.1625C19.7632 12.8531 17.1101 12.4969 16.0601 12.3469C13.0507 11.8969 10.4726 12.1219 8.70074 12.7969C6.98699 13.4531 6.27543 14.3813 6.08699 15.4219C5.90512 16.425 6.04293 16.9969 6.20793 17.3531C6.38512 17.7281 6.73106 18.15 7.42012 18.6094C8.94449 19.6125 11.2695 20.2875 14.2882 21.1031L14.5507 21.1781C17.2132 21.9 20.4757 22.7906 22.8945 24.3938C24.2164 25.275 25.4632 26.4656 26.232 28.1156C27.0101 29.7844 27.1789 31.6594 26.8226 33.5719C26.1664 37.2188 23.6351 39.6 20.6632 40.8375C19.3882 41.3625 17.982 41.7 16.5007 41.8688V45C16.5007 46.6594 15.1601 48 13.5007 48C11.8414 48 10.5007 46.6594 10.5007 45V41.7281C10.4632 41.7281 10.4164 41.6344 10.3789 41.7094H10.3601C8.082 41.3531 4.31981 40.3688 1.78199 39.2438C0.267932 38.5688 -0.41363 36.7969 0.259495 35.2781C0.93262 33.7687 2.70543 33.0844 4.13512 33.7594C6.17887 34.6313 9.40387 35.4844 11.2695 35.775C14.2695 36.225 16.7351 35.9625 18.4039 35.2875C19.9882 34.6406 20.7101 33.7031 20.9164 32.5781C21.0945 31.575 20.9632 31.0031 20.7945 30.6469C20.6164 30.2719 20.2695 29.85 19.5851 29.3906C18.057 28.3875 15.732 27.7125 12.7132 26.8969L12.4507 26.8219C9.78825 26.1 6.52387 25.2094 4.10324 23.6063C2.78043 22.725 1.54106 21.5344 0.77137 19.8844C-0.00956798 18.2156 -0.179255 16.3406 0.183557 14.3438C0.847307 10.6969 3.47418 8.37094 6.55949 7.19344C7.78012 6.7275 9.10387 6.42094 10.5007 6.25594V3C10.5007 1.34344 11.8414 0 13.5007 0Z" fill="white" fill-opacity="0.8"/>
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
                            <span class="fz-12">Nomor Peraturan</span>
                            <p class="fz-16 content_detail_4"></p>
                        </div>
                        <div class="col-lg-6">
                            <span class="fz-12">Tanggal Penetapan</span>
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
        
       let url = `/get-data/documentByVerifikasi?jenis=${$(this).attr('data-jenis')}&document=${$(this).attr('data-id')}`;
        control.modal_content(`Detail ${label[1]}`, url,`/dokumen-desa/konsederan?document=${$(this).attr('data-id')}&jenis=${label[1].toLowerCase()}`);
    })
    $(function () {
        $.ajax({
            url : '/get-data/dashboard',
            method : 'GET',
            success : function (res) {
                console.log(res);
                let total_pagu_paket = 0;
                $.each(res,function (x,y) {
                    if (y.total_pagu_paket !== null) {
                        total_pagu_paket = y.total_pagu_paket.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }
                    $('#jml_paket').html(y.jumlah_paket+' <span>Paket</span>');
                    $('#jml_pagu').html('Rp. '+total_pagu_paket);
                        $('#total_add').html('Rp. '+y.total_add.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                })
            
                
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
                targets: 2,
                render: function(data, type, full, meta) {
                    if (data == null) {
                        return '-'; 
                    }else{
                        return data; 
                    }
                    
                }
            },
            {
                targets: 3,
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
                width:"10rem",
                targets: -1,
                title: 'Aksi',
                // width: '15rem',
                orderable: false,
                render: function(data, type, full, meta) {
                 console.log(data);
                    return `<a title="Detail" href="javascript:;" type="button" data-id="${data.id}" data-label="${data.nama_documents}" data-jenis="${data.jenis_document}"  data-bs-toggle="modal" data-bs-target="#kt_modal_2" class="btn btn-info button-detail btn-sm btn-icon">
                    <i class="fa fa-eye" aria-hidden="true" style="color:white"></i></a>`; 
                   
                },
            }
        ];
        control.initDatatable('/general/datatable-list?type=type_d&document_type=2',columns,columnDefs);

    })
</script>
@endsection