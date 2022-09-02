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
                    <form class="form-data">
                        <input type="hidden" id="jenis_document" name="jenis_document">
                        <input type="hidden" id="nama_documents" name="nama_documents">
                    <table id="kt_table_data" class="table table-row-dashed table-row-gray-300 gy-7">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th>No</th>
                                <th>Indikator</th>
                                <th>Kesesuaian</th>
                                <th>Tindak Lanjut Penyempurnaan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </form>
                </div>

                <div class="mb-5">
                    <button type="button" id="submit" class="btn btn_general btn-sm btn-submit">Simpan</button>
                    <button type="reset" class="btn mr-2 btn-light btn-cancel btn-sm">Batal</button>
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
     let control = new Control('type_3');
     let params = {!! json_encode($document) !!};
     let jenis = {!! json_encode($jenis) !!}

     $(document).on('click','#submit', function () {
        control.submitForm(`/get-data/master-verifikasi?jenis=${jenis}&document=${params}`,'Tambah',`Dokumen ${jenis}`);
     })

     $(document).on('keyup', '#search_', function (e) {
        e.preventDefault();
        control.searchTable(this.value);
    })

    $(document).on('change', '.true_check', function (e) {
        e.preventDefault();
        let index = $(this).attr('data-index');
        $(`#tindak_lanjut_${index}`).val('');
    })


    // $(document).on('change','.true_check' function () {
    //     alert('sfsdf');
    // })

    $(function () {
        control.verifikasi_render_row(params);
       

    })

</script>
@endsection