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
                    <h1>Jumlah SKPD</h1>
                    <h2>44 <span>Users</span></h2>
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
                    <h2>44 <span>Users</span></h2>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                    <svg width="48" height="43" viewBox="0 0 48 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M27.3417 9.33334L46.975 33.7833C47.6417 34.6167 48 35.6417 48 36.7V38.6667C48 40.875 46.2083 42.6667 44 42.6667H4C1.79083 42.6667 0 40.875 0 38.6667V36.7C0 35.6417 0.360667 34.6167 1.0225 33.7833L20.5833 9.33334L16.5833 4.3325C15.5917 3.1825 15.85 1.50417 17 0.584419C18.15 -0.335581 19.825 -0.149081 20.675 1.00084L24 5.065L27.25 1.00084C28.175 -0.149081 29.85 -0.335581 30.925 0.584419C32.15 1.50417 32.3333 3.1825 31.3417 4.3325L27.3417 9.33334ZM33.9583 37.3333L24 24.3083L14.0417 37.3333H33.9583Z" fill="white" fill-opacity="0.8"/>
                    </svg>

                    </div>
                 </div>

            </div>
        </div>
        <div class="col-md-4">
             <div class="widget_dashboard widget_green">
             
                <div class="d-flex bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                    <h1>Jumlah Verifikator</h1>
                    <h2>44 <span>Users</span></h2>
                    </div>
                    <div class="ms-auto p-2 bd-highlight">
                        
                    <svg width="48" height="40" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.6025 23.3846H12.9975C5.82075 23.3846 0 29.3538 0 36.7154C0 38.1923 1.164 39.3846 2.5995 39.3846H30.9975C32.4375 39.3846 33.6 38.1923 33.6 36.7154C33.6 29.3538 27.78 23.3846 20.6025 23.3846ZM16.8 19.6923C22.1025 19.6923 26.4 15.2838 26.4 9.84615C26.4 4.40846 22.1025 0 16.8 0C11.4975 0 7.2 4.40846 7.2 9.84615C7.2 15.2838 11.4975 19.6923 16.8 19.6923ZM47.4225 10.3385C46.6948 9.64615 45.5542 9.68823 44.8785 10.4358L38.3497 17.6474L35.4705 14.6943C34.7674 13.9732 33.6285 13.9732 32.925 14.6943C32.2215 15.4155 32.2219 16.5835 32.925 17.3051L37.125 21.6128C37.4625 21.9615 37.92 22.1538 38.4 22.1538H38.4328C38.9226 22.1442 39.3868 21.9315 39.7198 21.5637L47.5198 12.9483C48.195 12.2 48.15 11.0308 47.4225 10.3385Z" fill="white" fill-opacity="0.8"/>
                    </svg>


                    </div>
                 </div>

            </div>
        </div>

    </div>
</div>
<!--end::Container-->
</div>
@endsection