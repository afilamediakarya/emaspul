<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../">
		<meta charset="utf-8" />
		<title>Langit Maspul | Bapelitbangda Enrekang</title>
		<meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="canonical" href="Https://preview.keenthemes.com/metronic8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="assets/media/logo/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-white">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-lg-row-auto w-xl-700px positon-xl-relative" style="background-color: #005DC5">
					<!--begin::Wrapper-->
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-700px scroll-y">
						<!--begin::Content-->
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
						
                        <a href="javascript:;">
								<img alt="Logo" src="{{ asset('assets/media/logo/logo_front.png') }}" class="logo_md" />
							</a>

                           <p class="text_label_logo mt-10">LAYANAN DIGITAL IMPLEMENTASI SISTEM PERENCANAAN PEMBANGUNAN, MENUJU PERENCANAAN YANG BERKUALITAS</p>
						   <a href="javascript:;">
								<img alt="Logo" src="{{ asset('assets/media/logo/logo_front.png') }}" class="logo_md" />
							</a>
                            <div class="text_page_logo mt-5">BAPPELITBANGDA <br> KABUPATEN ENREKANG</div>

                            <!-- <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/background_bottom.png)"></div> -->
                            <!--<img class="background_bottom" src="{{ asset('assets/media/illustrations/background_bottom.png') }}" >-->

						</div>
						
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<!--begin::Content-->
					
					<div class="d-flex flex-center flex-column flex-column-fluid">
					
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10 p-lg-15 mx-auto">
						
							<!--begin::Form-->
							<form class="form-data form w-100" id="form">

								<!--begin::Heading-->
								<div class="text-center mb-10">
									<!--begin::Title-->
									<a href="javascript:;">
								<img alt="Logo" src="{{ asset('assets/media/logo/logo_front.png') }}" class="logo_md" />
								</a>
									<!--end::Title-->
								</div>
								<div class="fv-row text-center mb-10 ">
									<h1 class="text-dark mb-3">Selamat Datang</h1>
								</div>
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<!--begin::Label-->
									<label class="form-label fs-6 fw-bolder text-dark">Username</label>
									<!--end::Label-->
									<!--begin::Input-->
									<input class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off" />
									<small class="text-danger username_error"></small>
									<!--end::Input-->
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<!--begin::Wrapper-->
									<div class="d-flex flex-stack mb-2">
										<!--begin::Label-->
										<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
										<!--end::Label-->
									
									</div>
									<!--end::Wrapper-->
									<!--begin::Input-->
									<input class="form-control form-control-lg form-control-solid" id="password" type="password" name="password" autocomplete="off" />
									<small class="text-danger password_error"></small>
									<!--end::Input-->
								</div>
								<!--end::Input group-->
								<!--begin::Actions-->
								<div class="text-center">
									<!--begin::Submit button-->
									<button type="button" id="kt_sign_in_submit" class="btn btn-lg btn_general w-100 mb-5">
										<span class="indicator-label">Login</span>
									</button>
									<!--end::Submit button-->
								
								</div>
								<!--end::Actions-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
        <script src="{{ asset('assets/js/panel.js') }}"></script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
        <script>
			
            let control = new Control('type_1');
            $(document).on('click','#kt_sign_in_submit', function () {
                control.submitForm('/login');
            })

			var input = document.getElementById("password");
			input.addEventListener("keypress", function(event) {
			if (event.key === "Enter") {
				event.preventDefault();
				document.getElementById("kt_sign_in_submit").click();
			}
			});
        </script>
	</body>
	<!--end::Body-->
</html>