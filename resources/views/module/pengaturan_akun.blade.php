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
                                <input type="password" class="form-control form-control-solid" id="password_lama" name="password_lama">
                                <small class="text-danger password_lama_error"></small>
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control form-control-solid" id="password" name="password">
                                <small class="text-danger password_error"></small>
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Ulangi Password Baru</label>
                                <input type="password" class="form-control form-control-solid" name="password_confirmation">
                                <small class="text-danger password_confirmation_error"></small>
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
     let control = new Control('type_3');
     let element = ['password_lama','password','password_confirmation'];

    $(document).on('submit', ".form-data", function(e){
        e.preventDefault();
        control.submitForm('/pengaturan-akun/update','Update','Akun',element);
    });




    // $(function () {
       

       

    // })

</script>
@endsection