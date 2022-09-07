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
										<img alt="Logo" src="{{ asset('assets/media/logo/logo.png') }}" class="logo-default" style="margin-left:10px" />
									</a>
									<!--end::Logo image-->
								</div>
								<!--end::Logo-->
								<!--begin::Menu wrapper-->
								
								<!--end::Menu wrapper-->
								<!--begin::Toolbar-->
								<div class="flex-equal text-end ms-1">
									<a href="authentication/flows/basic/sign-in.html" class="btn btn-light">Masuk</a>
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
                </div>
            </div>
        </div>
</body>
</html>