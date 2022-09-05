@extends('general.layout')
@section('button')
@if($role == 1)
<button class="btn btn_general btn-sm " data-kt-drawer-show="true" data-kt-drawer-target="#side_form" id="button-side-form"><i class="fa fa-plus-circle" style="color:#ffffff" aria-hidden="true"></i> Tambah Kinerja Makro</button>
@endif
 <a style="margin-left: 1rem;
" href="/kinerja-makro/datatable-list?type=export" target="_blank" role="button" class="btn btn-danger btn-sm ">  <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.2667 5.82386H10.6476C10.5795 5.82386 10.5238 5.87957 10.5238 5.94767V6.56671C10.5238 6.63481 10.5795 6.69052 10.6476 6.69052H11.2667C11.3348 6.69052 11.3905 6.63481 11.3905 6.56671V5.94767C11.3905 5.87957 11.3348 5.82386 11.2667 5.82386ZM11.7619 4.21433H9.90476V0.93338C9.90476 0.865285 9.84905 0.80957 9.78095 0.80957H3.21905C3.15095 0.80957 3.09524 0.865285 3.09524 0.93338V4.21433H1.2381C0.554048 4.21433 0 4.76838 0 5.45243V10.5286C0 10.8025 0.22131 11.0239 0.495238 11.0239H3.09524V13.0667C3.09524 13.1348 3.15095 13.1905 3.21905 13.1905H9.78095C9.84905 13.1905 9.90476 13.1348 9.90476 13.0667V11.0239H12.5048C12.7787 11.0239 13 10.8025 13 10.5286V5.45243C13 4.76838 12.446 4.21433 11.7619 4.21433ZM4.14762 1.86195H8.85238V4.21433H4.14762V1.86195ZM8.85238 12.1381H4.14762V7.86671H8.85238V12.1381ZM11.9476 9.97147H9.90476V6.81433H3.09524V9.97147H1.05238V5.45243C1.05238 5.35028 1.13595 5.26671 1.2381 5.26671H11.7619C11.864 5.26671 11.9476 5.35028 11.9476 5.45243V9.97147Z" fill="white"/>
</svg> Cetak</a>

<a style="margin-left: 1rem;" href="/kinerja-makro/datatable-list?type=excel" target="_blank" role="button" class="btn btn-success btn-sm ">  <i class="fas fa-file-excel"></i> Excel</a> <span></span>
@endsection
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
                            <th>Indikator</th>
                            <th>Target Awal</th>
                            <th>Target 2019</th>
                            <th>Target 2020</th>
                            <th>Target 2021</th>
                            <th>Target 2022</th>
                            <th>Target 2023</th>
                            <th>Realisasi 2019</th>
                            <th>Realisasi 2020</th>
                            <th>Realisasi 2021</th>
                            <th>Realisasi 2022</th>
                            <th>Realisasi 2023</th>
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
                    <label class="form-label">Masukkan Indikator</label>
                    <input type="text" class="form-control form-control-solid" name="indikator_makro" placeholder="Masukkan Indikator">
                    <small class="text-danger indikator_makro_error"></small>
                </div>

                <input type="hidden" name="id">

                <div class="mb-10">
                    <label class="form-label">Pilih Periode</label>
                    <select id="periode" name="periode" class="form-control form-control-solid">
                        <option selected disabled >Pilih Periode</option>
                        <option value="2013 - 2017">2013 - 2017</option>
                        <option value="2018 - 2023">2018 - 2023</option>
                        <option value="2024 - 2029">2024 - 2029</option>
                    </select>
                    <small class="text-danger periode_error"></small>
                </div>

                <input type="hidden" name="periode_awal" id="periode_awal">
                <input type="hidden" name="periode_akhir" id="periode_akhir">

              <div id="content_row">

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
     let role = {!! json_encode($role) !!}
    $(document).on('click','#button-side-form', function () {
        $('#password_content').show();
        control.overlay_form('Tambah','Kinerja Makro');
    })

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        if (type == 'add') {
            control.submitForm('/kinerja-makro/store','Tambah','Kinerja Makro');
        }else{
            let id = $("input[name='id']").val();
            control.submitForm('/kinerja-makro/update/'+id,'Update','Kinerja Makro');
        }
    });

    $(document).on('change','#periode', function () {
        let data = $(this).val();
        data =  data.split("-");
        $('#periode_awal').val(data[0]);
        $('#periode_akhir').val(data[1]);
        let html = '';
        for (let index = 0; index <= 5; index++) {
            
            if (index == 0) {
                html += `<div class="row">
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Tahun</label>
                        <input type="hidden" id="id_data_makro${index}" name="id_data_makro[${index}]" readonly>
                        <input type="hidden" value="${parseInt(data[0]) + index}" name="tahun[${index}]">
                        <input type="text" class="form-control form-control-solid" id="tahun${index}" value="Tahun Awal" readonly>
                    </div>
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Target</label>
                        <input type="text" class="form-control form-control-solid" id="target${index}" value="0" name="target[${index}]">
                    </div>
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Realisasi</label>
                        <input type="text" class="form-control form-control-solid" id="realisasi${index}" value="0" name="realisasi[${index}]">
                    </div>
            </div>`;
            }else{
                html += `<div class="row">
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Tahun</label>
                        <input type="hidden" id="id_data_makro${index}" name="id_data_makro[${index}]" readonly>
                        <input type="text" class="form-control form-control-solid" id="tahun${index}" value="${parseInt(data[0]) + index}" name="tahun[${index}]" readonly>
                    </div>
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Target</label>
                        <input type="text" class="form-control form-control-solid" id="target${index}" value="0" name="target[${index}]">
                    </div>
                    <div class="col-lg-4 mb-10">
                        <label class="form-label">Realisasi</label>
                        <input type="text" class="form-control form-control-solid" id="realisasi${index}" value="0" name="realisasi[${index}]">
                    </div>
            </div>`;
            }

           
        }

        $('#content_row').html(html);
    })

    $(document).on('click','.button-update', function (e) {
        e.preventDefault();
        $('#password_content').hide();
        let url = '/kinerja-makro/byParams/'+$(this).attr('data-id');
        control.overlay_form('Update','Kinerja Makro', url,'makro');
    })

    $(document).on('keyup', '#search_', function (e) {
        e.preventDefault();
        control.searchTable(this.value);
    })

    $(function () {
      let action = true;
        if (role !== 1) {
         action = false;
        }

        let columns = [
            { 
            data : null, 
                render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },{
                data:'indikator_makro'
            },{
                data:'target0'
            },{
                data:'target1'
            },{
                data:'target2'
            },{
                data:'target3'
            },{
                data:'target4'
            },{
                data:'target5'
            },{
                data:'realisasi1'
            },{
                data:'realisasi2'
            },{
                data:'realisasi3'
            },{
                data:'realisasi4'
            },{
                data:'realisasi5'
            },{
                data: 'id',
            }
        ];
        let columnDefs = [
           {
                width:"10rem",
                targets: -1,
                title: 'Aksi',
                orderable: false,
                visible : action,
                render: function(data, type, full, meta) {
                   
                        return `
                        <a title="Edit" href="javascript:;" type="button" data-id="${data}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn_green button-update btn-sm btn-icon"> 
                        <i class="fa fa-edit" aria-hidden="true" style="color:white"></i></a>
                        `;
                    
                },
            }
        ];
        control.initDatatable('/kinerja-makro/datatable-list?type=datatable',columns,columnDefs);

       

    })

</script>
@endsection