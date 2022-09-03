@extends('general.layout')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
<!--begin::Container-->
<div id="kt_content_container" class="container">
    <div class="row">

    <div class="card">
       <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Akun</h3>
                    </div>
                    <div class="card-body">
                            <div class="mb-10">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control form-control-solid" value="{{$user->nama_lengkap}}" id="nama" name="nama">
                                <!-- <small class="text-danger nama_documents_error"></small> -->
                            </div>
                            <div class="mb-10">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control form-control-solid" value="{{$user->nip}}" id="nip" name="nip">
                                <!-- <small class="text-danger nama_documents_error"></small> -->
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control form-control-solid" value="{{$user->username}}" name="username">
                                <!-- <small class="text-danger nama_documents_error"></small> -->
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ganti Password</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-data">
                            <div class="mb-10">
                                <label class="form-label">Password Lama</label>
                                <input type="text" class="form-control form-control-solid" name="password_old">
                                <small class="text-danger nama_documents_error"></small>
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Password Baru</label>
                                <input type="text" class="form-control form-control-solid" name="password">
                                <small class="text-danger nama_documents_error"></small>
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Ulangi Password Baru</label>
                                <input type="text" class="form-control form-control-solid" name="password_confirmation">
                                <small class="text-danger nama_documents_error"></small>
                            </div>
                            <button type="submit" class="btn btn_general btn-sm btn-submit">Simpan Perubahan</button>
                        </form>   
                    </div>
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
     let element = ['nama_documents','periode_awal','periode_akhir','nomor_perbub','tanggal_perbub','referensi_nama_dokumen'];

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        let type = $(this).attr('data-type');
        control.submitFormMultipart('/general/storeDocuments?jenis=renstra&type=type_b','Tambah','Dokumen Renstra',element);
    });




    // $(function () {
       

       

    // })

</script>
@endsection