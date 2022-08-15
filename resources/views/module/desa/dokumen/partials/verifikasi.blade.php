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
                    <form class="form-data">
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


    $(function () {
        control.verifikasi_render_row(params);
       

    })

</script>
@endsection