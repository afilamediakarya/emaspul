<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="shortcut icon" href="assets/media/logo/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
	
</head>
<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" data-bs-offset="200" class="bg-white position-relative">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Header Section-->
			<div class="mb-0" id="home">
				<!--begin::Wrapper-->
				<div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-blue-bg">
					<!--begin::Header-->
					<div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						<div class="container">
							<!--begin::Wrapper-->
							<div class="d-flex align-items-center justify-content-between">
								<!--begin::Logo-->
								<div class="d-flex align-items-center flex-equal">
								
									<!--end::Mobile menu toggle-->
									<!--begin::Logo image-->
									<a href="landing.html">
										<img alt="Logo" src="{{ asset('assets/media/logo/enre.png') }}" class="logo-default" />
									</a>
									<a href="landing.html">
										<img alt="Logo" src="{{ asset('assets/media/logo/logo.png') }}" class="logo-default"/>
									</a>
									<!--end::Logo image-->
								</div>
								<!--end::Logo-->
								<!--begin::Menu wrapper-->
								
								<!--end::Menu wrapper-->
								<!--begin::Toolbar-->
								<div class="flex-equal text-end ms-1">
									<a href="{{ url('/') }}" class="btn btn-light">Masuk</a>
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
                </div>
            </div>

			<div class="container">
			<div class="card shadow-sm">

			<div class="card-header">
				<h3 class="card-title">Informasi Dokumen</h3>
			</div>

				<div class="box_detail mg-3">
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

				<div id="tb_md">
					<table id="table_detail" class="table gy-7 mg-3 table-responsive">
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

				<div id="tb_xs" class="col-10">
					<h6 style="margin-left: 4rem;
    color: #005DC5;">Hasil Verifikasi</h6>
					<table id="table_detail_xs" class="table gy-7 mg-3 table-responsive">
							
					</table>
				</div>
					
			</div>
			</div>

			
		

        </div>

<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>	
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>	
<script>
	$(function () {
		let jenis = {!! json_encode($jenis) !!};
		let document = {!! json_encode($document) !!};
		$.ajax({
			url : `/detail-dokumen/get_data?jenis=${jenis}&document=${document}`,
			type : 'GET',
			success : function (res) {
				console.log(res)
				let tanggal_perbub = '-';
                let nomor_perbub = '-';
                let row = '';
				let row_xs = '';
                $.each(res.document,function name(x,y) {

                    if (y.tanggal_perbub !== null) {
                        tanggal_perbub = y.tanggal_perbub;
                    }

                    if (y.nomor_perbub !== null) {
                        nomor_perbub = y.nomor_perbub;
                    }

                    if (y.verifikator == null) {
                        $('#konsederan').css('display','none');
                    }else{
                        $('#konsederan').css('display','block');
                    }

                    $('.content_detail_1').html(y.nama_documents);
                  
                    $('.content_detail_3').html(y.verifikator);
                    $('.content_detail_4').html(nomor_perbub);
                    $('.content_detail_5').html(tanggal_perbub);

                    if (y.status_document == '1') {
                        $('.content_detail_2').html('<button class="btn btn-light-danger btn-sm">Belum Verifikasi</button>');  
                    }else if(y.status_document == '2'){
                        $('.content_detail_2').html(`<button class="btn btn-light-dark btn-sm">Perbaikan</button>`);
                    }else if(y.status_document == '3'){
                        $('.content_detail_2').html(`<button class="btn btn-light-warning btn-sm">Belum Selesai</button>`);
                    }else{
                        $('.content_detail_2').html(`<button class="btn btn-light-success btn-sm"> Selesai</button>`);
                    }
                })

                $.each(res.master_verifikasi, function (i,v) {
                    let tindak_lanjut = '-';
                    let kesesuaian = '-';
					let img_check = '';
                    if (v.tindak_lanjut !== null) {
                        tindak_lanjut = v.tindak_lanjut;
                    }

                    if (v.verifikasi == '1') {
                        kesesuaian = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_310_17035)">
                        <path d="M17.5098 7.74121H16.2536C15.9804 7.74121 15.7205 7.87246 15.5598 8.09746L11.3491 13.9367L9.44197 11.2903C9.28125 11.068 9.02411 10.9341 8.74822 10.9341H7.49197C7.31786 10.9341 7.21608 11.1323 7.31786 11.2742L10.6554 15.9028C10.7342 16.0129 10.8381 16.1025 10.9586 16.1644C11.079 16.2262 11.2124 16.2585 11.3478 16.2585C11.4831 16.2585 11.6166 16.2262 11.737 16.1644C11.8574 16.1025 11.9613 16.0129 12.0402 15.9028L17.6813 8.08139C17.7857 7.93943 17.6839 7.74121 17.5098 7.74121Z" fill="#50CD89"/>
                        <path d="M12.5 0C5.87321 0 0.5 5.37321 0.5 12C0.5 18.6268 5.87321 24 12.5 24C19.1268 24 24.5 18.6268 24.5 12C24.5 5.37321 19.1268 0 12.5 0ZM12.5 21.9643C6.99821 21.9643 2.53571 17.5018 2.53571 12C2.53571 6.49821 6.99821 2.03571 12.5 2.03571C18.0018 2.03571 22.4643 6.49821 22.4643 12C22.4643 17.5018 18.0018 21.9643 12.5 21.9643Z" fill="#50CD89"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_310_17035">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                        </clipPath>
                        </defs>
                        </svg>                        
                        `;

						img_check = `<img src="/assets/media/icons/cek1.png" alt="" srcset="">`;
                    }else{
                        kesesuaian = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_310_17047)">
                        <path d="M17.1448 7.78948C17.1448 7.67162 17.0483 7.5752 16.9305 7.5752L15.1626 7.58323L12.5001 10.7573L9.84029 7.58591L8.06975 7.57787C7.9519 7.57787 7.85547 7.67162 7.85547 7.79216C7.85547 7.84305 7.87422 7.89127 7.90636 7.93145L11.3912 12.0832L7.90636 16.2323C7.87399 16.2716 7.85604 16.3207 7.85547 16.3716C7.85547 16.4895 7.9519 16.5859 8.06975 16.5859L9.84029 16.5779L12.5001 13.4038L15.1599 16.5752L16.9278 16.5832C17.0456 16.5832 17.1421 16.4895 17.1421 16.3689C17.1421 16.3181 17.1233 16.2698 17.0912 16.2297L13.6117 12.0806L17.0965 7.92877C17.1287 7.89127 17.1448 7.84037 17.1448 7.78948Z" fill="#F1416C"/>
                        <path d="M12.5 0.0268555C5.87321 0.0268555 0.5 5.40007 0.5 12.0269C0.5 18.6536 5.87321 24.0269 12.5 24.0269C19.1268 24.0269 24.5 18.6536 24.5 12.0269C24.5 5.40007 19.1268 0.0268555 12.5 0.0268555ZM12.5 21.9911C6.99821 21.9911 2.53571 17.5286 2.53571 12.0269C2.53571 6.52507 6.99821 2.06257 12.5 2.06257C18.0018 2.06257 22.4643 6.52507 22.4643 12.0269C22.4643 17.5286 18.0018 21.9911 12.5 21.9911Z" fill="#F1416C"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_310_17047">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                        </clipPath>
                        </defs>
                        </svg>
                        
                        `;

						img_check = `<img src="/assets/media/icons/cek2.png" alt="" srcset="">`;
                    }
                    row += `<tr>
						<td>${i+1}</td>
                        <td>${v.master_verifikasi['indikator']}</td>
                        <td>${kesesuaian}</td>
                        <td>${tindak_lanjut}</td>
                    </tr>`;     
					
					row_xs += `<tr>
						<td>${img_check}</td>
                        <td>${v.master_verifikasi['indikator']}</td>
                    </tr>`;
                })

				// <td>${i+1}</td>
				// <td>${tindak_lanjut}</td>
           
                $('#table_detail tbody').html(row);
				$('#table_detail_xs').html(row_xs);
				
			}
		})
	})
	
</script>
</body>

</html>