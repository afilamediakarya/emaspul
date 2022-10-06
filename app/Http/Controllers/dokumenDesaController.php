<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
use QrCode;

class dokumenDesaController extends Controller
{
    public function index(){
        $type = request('type');
        if ($type == 'RPJMDes') {
            $breadcumb = 'Dokumen RPJMDes';
            $current_breadcumb = '';
            return view('module.desa.dokumen.rpjmdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'RKPDes') {
            $breadcumb = 'Dokumen RKPDes';
            $current_breadcumb = '';
            return view('module.desa.dokumen.rkpdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'SDGS') {
            $breadcumb = 'Dokumen SDGs Desa';
            $current_breadcumb = '';
            return view('module.desa.dokumen.sdgs',compact('breadcumb','current_breadcumb'));
        }
    }

    public function index_verifikator(){
        $type = request('type');
        if ($type == 'RPJMDes') {
            $breadcumb = 'Dokumen Desa';
            $current_breadcumb = 'RPJMDes';
            $role = Auth::user()->id_role;
            return view('module.desa.dokumen.partials.rpjmdes',compact('breadcumb','current_breadcumb','role'));
        }
        if ($type == 'RKPDes') {
            $breadcumb = 'Dokumen Desa';
            $current_breadcumb = 'RKPDes';
            $role = Auth::user()->id_role;
            return view('module.desa.dokumen.partials.rkpdes',compact('breadcumb','current_breadcumb','role'));
        }
        if ($type == 'SDGs') {
            $breadcumb = 'Dokumen SDGs Desa';
            $current_breadcumb = '';
            return view('module.desa.dokumen.partials.spgs',compact('breadcumb','current_breadcumb'));
        }
    }

    function getHari(){
        $hari = date ("D");
        switch($hari){
            case 'Sun':
                $hari_ini = "Minggu";
            break;
     
            case 'Mon':			
                $hari_ini = "Senin";
            break;
     
            case 'Tue':
                $hari_ini = "Selasa";
            break;
     
            case 'Wed':
                $hari_ini = "Rabu";
            break;
     
            case 'Thu':
                $hari_ini = "Kamis";
            break;
     
            case 'Fri':
                $hari_ini = "Jumat";
            break;
     
            case 'Sat':
                $hari_ini = "Sabtu";
            break;
            
            default:
                $hari_ini = "Tidak di ketahui";		
            break;
        }
       return $hari_ini;
    }



    public function konsederan(){
        $jenis = request('jenis');
        $document = request('document');

       
        $fungsi = 'html_render_'.$jenis;
        $data = array();

        $data = DB::table('documents')->select('documents.id','documents.tahun','documents.periode_awal','documents.periode_akhir','documents.nomor_konsederan','perangkat_desa.nama_desa as unit_kerja','user.nama_lengkap as nama_verifikator','user.nip as nip_verifikator','user.nama_lengkap as nama_user')->join('perangkat_desa','documents.id_perangkat','=','perangkat_desa.id')->join('user','documents.id_verifikator','=','user.id')->where('documents.id',$document)->first();
        $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();
        
        $data->hari = $this->getHari();
        $data->tanggal = date('d');
        $data->bulan = date('m');
        $data->tahun = date('Y');
        $data->nama_user = Auth::user()->nama_lengkap;
        $data->nip_user = Auth::user()->nip;

        return $this->{$fungsi}($data,$jenis,$document);
    }



    public function html_render_rpjmdes($data,$jenis,$document){

        $qrcode=QrCode::size(120)->generate('https://langitmaspul.enrekangkab.go.id/detail-dokumen?document='.$document.'&jenis='.$jenis);
        
        $html = "";

        $html .= '<h4 style="text-align:center; line-height: 15pt;">BERITA ACARA <br> HASIL VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENEGAH DESA <br> (RPJM DESA) '.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG PERIODE '.$data->periode_awal.' - '.$data->periode_akhir.'<hr></h4>';

        $html .= "<h4 style='text-align:center; line-height: -20pt;'>NOMOR : ".strtoupper($data->nomor_konsederan)."</h4>";

        $html .= '<p style="text-align:justify" line-height: 15pt; style="text-indent: 45px;">
        Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Dokumen RPJM Desa '.$data->unit_kerja.' Kabupaten Enrekang Periode '.$data->periode_awal.' - '.$data->periode_akhir.', sebagai berikut : </p>';

        $html .= '<p style="text-align:justify" style="text-indent: 45px;"> Setelah dilakukan verifikasi RPJM Desa maka disepakati : </p>';

        $html .= "<table style='vertical-align: text-top; line-height: 15pt;'>
        <tr>
            <td style='width: 18%;'>KESATU</td>
            <td style='text-align: justify;  line-height: 15pt;'>
                Pedoman penyusunan RPJM Desa agar disesuaikan dengan Ketentuan
                Peraturan Menteri Dalam Negeri Nomor 114 Tahun 2014 tentang Pedoman
                Pembangunan Desa, meliputi :
             
                <table>
                    <tr>
                    <td style='vertical-align: text-top;'>1. </td>
                    <td style='text-align: justify;  line-height: 15pt;'>Penyempurnaan RPJM Desa sesuai saran dan masukan Tim Verifikasi
                    sebagaimana tersebut pada formulir verifikasi terlampir yang
                    merupakan bagian tidak terpisahkan dari Berita Acara ini</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>2. </td>

                    <td style='text-align: justify; line-height: 15pt;'>RPJM Desa mengacu pada RPJM kabupaten/kota, yang memuat Visi
                    dan Misi Kepala Desa, rencana penyelenggaraan Pemerintahan Desa,
                    Pelaksanaan Pembangunan, Pembinaan Kemasyarakatan,
                    Pemberdayaan Masyarakat, dan arah kebijakan Pembangunan Desa</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>KEDUA</td>
            <td style='text-align:justify line-height: 15pt;'>Melakukan penyempurnaan RPJM Desa Periode 2019 - 2024 Berdasarkan
                hasil verifikasi, meliputi :
                    <table>
                    <tr>
                    <td style='vertical-align: text-top; '>1. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Penyempurnaan RPJM Desa sesuai saran dan masukan Tim Verifikasi
                    sebagaimana tersebut pada formulir verifikasi terlampir yang
                    merupakan bagian tidak terpisahkan dari Berita Acara ini;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; '>2. </td>

                    <td style='text-align: justify; line-height: 15pt;'>Melakukan Upload Dokumen perbaikan atas hasil verifikasi RPJM Desa
                    $data->unit_kerja Periode $data->periode_awal - $data->periode_akhir melalui portal
                    https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>";

    

    $html .= '<p style="text-align:justify vertical-align: text-top;"  style="text-indent: 45px;">Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.</p>';


    $html .= '<table style="width:100%">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">Verifikator RPJM Desa<br>Kabupaten Enrekang</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">'.substr($qrcode,38).'</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">'.$data->nama_verifikator.'</td>
    </tr>
    
</table>';

$mpdf = new \Mpdf\Mpdf([
	'default_font' => 'Bookman Old Style',
    'tempDir'=>storage_path('tempdir')
]);

$mpdf->adjustFontDescLineheight = 1.5;

$mpdf->SetHTMLFooter('<hr>
<table width="100%" style="vertical-align: top; ; 
    font-size: 8pt; color: #000000; ">
    Catatan
    <tr>
        <td width="85%" style="text-align: left;">
        <ul>

            <li>Dokumen ini telah ditandatangani secara elektronik yang diterbitkan oleh Bappelitbangda Enrekang</li>
            <li>Surat ini dapat dibuktikan keasliannya dengan melakukan <b>scan</b> pada <b>QR Code</b></li>
            </ul>
        </td>
        <td style="width=15%;  font-weight: bold; text-align: rigth; font-style: italic;">Halaman {PAGENO} dari {nbpg}</td>
    </tr>
</table>');


$mpdf->WriteHTML($html);
$mpdf->AddPage();

$html2 = '<h4 style="text-align:center; line-height: 15pt;">FORMULIR VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENENGAH DESA <br> (RPJM DESA) '.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG PERIODE '.$data->periode_awal.'-'.$data->periode_akhir.'<hr></h4>';

$html2 .='      
<table border="1" style="border-collapse:collapse; width:100%;">
    <thead>
    <tr>
        <th style=" line-height: 15pt; width:5%;" rowspan="2" >NO.</th>
        <th style=" line-height: 15pt; width:45%;" rowspan="2">INDIKATOR</th>
        <th style=" line-height: 15pt; width:20%;" colspan="2">KESESUAIAN</th>
        <th style="text-align: center; width:30%;  line-height: 15pt;" rowspan="2">TINDAK LANJUT</th>
    </tr>
    <tr>
        <th style=" line-height: 15pt;" >YA</th>
        <th style=" line-height: 15pt;" >TIDAK</th>
    </tr>
    </thead>
              
';

$html2 .='<tbody>';
$icons1 = '';
$icons2 = '';
foreach ( $data->tabel as $i => $row ){
    $html2 .='<tr>';
    $html2 .='<td>'.++$i.'</td>';
    $html2 .='<td>'.$row->indikator.'</td>';
    if ($row->verifikasi==1){
        $icons1 ='<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_310_17035)">
        <path d="M17.5098 7.74121H16.2536C15.9804 7.74121 15.7205 7.87246 15.5598 8.09746L11.3491 13.9367L9.44197 11.2903C9.28125 11.068 9.02411 10.9341 8.74822 10.9341H7.49197C7.31786 10.9341 7.21608 11.1323 7.31786 11.2742L10.6554 15.9028C10.7342 16.0129 10.8381 16.1025 10.9586 16.1644C11.079 16.2262 11.2124 16.2585 11.3478 16.2585C11.4831 16.2585 11.6166 16.2262 11.737 16.1644C11.8574 16.1025 11.9613 16.0129 12.0402 15.9028L17.6813 8.08139C17.7857 7.93943 17.6839 7.74121 17.5098 7.74121Z" fill="#50CD89"/>
        <path d="M12.5 0C5.87321 0 0.5 5.37321 0.5 12C0.5 18.6268 5.87321 24 12.5 24C19.1268 24 24.5 18.6268 24.5 12C24.5 5.37321 19.1268 0 12.5 0ZM12.5 21.9643C6.99821 21.9643 2.53571 17.5018 2.53571 12C2.53571 6.49821 6.99821 2.03571 12.5 2.03571C18.0018 2.03571 22.4643 6.49821 22.4643 12C22.4643 17.5018 18.0018 21.9643 12.5 21.9643Z" fill="#50CD89"/>
        </g>
        <defs>
        <clipPath id="clip0_310_17035">
        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
        </clipPath>
        </defs>
        </svg>';
        $icons2 = '';
    }
    else{
        $icons2 ='<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_310_17047)">
        <path d="M17.1448 7.78948C17.1448 7.67162 17.0483 7.5752 16.9305 7.5752L15.1626 7.58323L12.5001 10.7573L9.84029 7.58591L8.06975 7.57787C7.9519 7.57787 7.85547 7.67162 7.85547 7.79216C7.85547 7.84305 7.87422 7.89127 7.90636 7.93145L11.3912 12.0832L7.90636 16.2323C7.87399 16.2716 7.85604 16.3207 7.85547 16.3716C7.85547 16.4895 7.9519 16.5859 8.06975 16.5859L9.84029 16.5779L12.5001 13.4038L15.1599 16.5752L16.9278 16.5832C17.0456 16.5832 17.1421 16.4895 17.1421 16.3689C17.1421 16.3181 17.1233 16.2698 17.0912 16.2297L13.6117 12.0806L17.0965 7.92877C17.1287 7.89127 17.1448 7.84037 17.1448 7.78948Z" fill="#F1416C"/>
        <path d="M12.5 0.0268555C5.87321 0.0268555 0.5 5.40007 0.5 12.0269C0.5 18.6536 5.87321 24.0269 12.5 24.0269C19.1268 24.0269 24.5 18.6536 24.5 12.0269C24.5 5.40007 19.1268 0.0268555 12.5 0.0268555ZM12.5 21.9911C6.99821 21.9911 2.53571 17.5286 2.53571 12.0269C2.53571 6.52507 6.99821 2.06257 12.5 2.06257C18.0018 2.06257 22.4643 6.52507 22.4643 12.0269C22.4643 17.5286 18.0018 21.9911 12.5 21.9911Z" fill="#F1416C"/>
        </g>
        <defs>
        <clipPath id="clip0_310_17047">
        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
        </clipPath>
        </defs>
        </svg>';
        $icons1 = '';
    }
    $html2 .='<td style="text-align: center;">'.$icons1.'</td>';
    $html2 .='<td style="text-align: center;">'.$icons2.'</td>';
    $html2 .='<td>'.$row->tindak_lanjut.'</td>';
    $html2 .='</tr>';
}
$html2 .='</tbody>';
$html2 .= '</table>';

$mpdf->WriteHTML($html2);
//$mpdf->SetFooter('Dokumen ini telah ditandatangani secara elektronik yang diterbitkan oleh Bappelitbangda Enrekang');
//$mpdf->SetFooter('Halaman {PAGENO} dari {nb} ');


$mpdf->SetTitle('Berita Acara Hasil Verifikasi RPJMDes '.$data->unit_kerja.'');
$mpdf->Output();





        return $html;
    }

    

    public function html_render_rkpdes($data,$jenis,$document){
        $html = '';
        $qrcode=QrCode::size(120)->generate('https://langitmaspul.enrekangkab.go.id/detail-dokumen?document='.$document.'&jenis='.$jenis);
        
        $html .= '<h4 style="text-align:center; line-height: 15pt;">BERITA ACARA <br> HASIL VERIFIKASI RANCANGAN RENCANA KERJA PEMERINTAH DESA  <br> (RKP DESA) '.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG TAHUN '.$data->tahun.'<hr></h4>';

        $html .= "<h4 style='text-align:center; line-height: -20pt;'>NOMOR : ".strtoupper($data->nomor_konsederan)."</h4>";

        $html .= '<p style="text-align:justify" line-height: 15pt; style="text-indent: 45px;">
        Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Dokumen RKP Desa '.$data->unit_kerja.' Kabupaten Enrekang Periode '.($data->tahun+1).', sebagai berikut : </p>';

        $html .= '<p style="text-align:justify" style="text-indent: 45px;"> Setelah dilakukan verifikasi RKP Desa maka disepakati : </p>';

    
        $html .= "<table style='vertical-align: text-top; line-height: 15pt;'>
        <tr>
            <td style='width: 18%;'>KESATU</td>
            <td style='text-align: justify;  line-height: 15pt;'>
                Pedoman penyusunan RKP Desa agar disesuaikan dengan Ketentuan
                Peraturan Menteri Dalam Negeri Nomor 114 Tahun 2014 tentang Pedoman
                Pembangunan Desa, meliputi :
             
                <table>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>1. </td>
                    <td style='text-align: justify;  line-height: 15pt;'>Evaluasi pelaksanaan RKP Desa tahun sebelumnya ;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>2. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Prioritas program, kegiatan, dan anggaran Desa yang dikelola oleh Desa ;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>3. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Prioritas program, kegiatan, dan anggaran Desa yang dikelola melalui kerja sama antar-Desa dan pihak ketiga;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>4. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Rencana program, kegiatan, dan anggaran Desa yang dikelola oleh Desa sebagai kewenangan penugasan dari Pemerintah, pemerintah daerah provinsi, dan pemerintah daerah kabupaten/kota; dan</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; line-height: 15pt;'>5. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Pelaksana kegiatan Desa yang terdiri atas unsur perangkat Desa dan/atau unsur masyarakat Desa.</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>KEDUA</td>
            <td style='text-align:justify line-height: 15pt;'>Melakukan penyempurnaan RKP Desa Tahun ".($data->tahun+1)." Berdasarkan  hasil verifikasi, meliputi :
                    <table>
                    <tr>
                    <td style='vertical-align: text-top; '>1. </td>
                    <td style='text-align: justify; line-height: 15pt;'>Penyempurnaan RKP Desa sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; '>2. </td>

                    <td style='text-align: justify; line-height: 15pt;'>Melakukan Upload Dokumen perbaikan atas hasil verifikasi RKP Desa ".$data->unit_kerja." Tahun ".($data->tahun+1)." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>";

    

    $html .= '<p style="text-align:justify vertical-align: text-top;"  style="text-indent: 45px;">Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.</p>';

    $html .= '<table style="width:100%">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">Verifikator RKP Desa<br>Kabupaten Enrekang</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">'.substr($qrcode,38).'</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">'.$data->nama_verifikator.'</td>
    </tr>
    
</table>';

$mpdf = new \Mpdf\Mpdf([
	'default_font' => 'Bookman Old Style',
    'tempDir'=>storage_path('tempdir')
]);
$mpdf->adjustFontDescLineheight = 1.5;

$mpdf->SetHTMLFooter('<hr>
<table width="100%" style="vertical-align: top; ; 
    font-size: 8pt; color: #000000; ">
    Catatan
    <tr>
        <td width="85%" style="text-align: left;">
        <ul>

            <li>Dokumen ini telah ditandatangani secara elektronik yang diterbitkan oleh <b>Bappelitbangda Enrekang</b></li>
            <li>Surat ini dapat dibuktikan keasliannya dengan melakukan <b>scan</b> pada <b>QR Code</b></li>
            </ul>
        </td>
        <td style="width=15%;  font-weight: bold; text-align: rigth; font-style: italic;">Halaman {PAGENO} dari {nbpg}</td>
    </tr>
</table>');


$mpdf->WriteHTML($html);
$mpdf->AddPage();

$html2 = '<h4 style="text-align:center; line-height: 15pt;">LAMPIRAN VERIFIKASI RENCANA KERJA PEMERINTAH DESA <br> (RKP DESA) '.strtoupper($data->unit_kerja).'<b> KABUPATEN ENREKANG TAHUN '.$data->tahun.'<hr></h4>';

$html2 .='      
<table border="1" style="border-collapse:collapse; width:100%;">
    <thead>
    <tr>
        <th style=" line-height: 15pt; width:5%;" rowspan="2" >NO.</th>
        <th style=" line-height: 15pt; width:45%;" rowspan="2">INDIKATOR</th>
        <th style=" line-height: 15pt; width:20%;" colspan="2">KESESUAIAN</th>
        <th style="text-align: center; width:30%;  line-height: 15pt;" rowspan="2">TINDAK LANJUT</th>
    </tr>
    <tr>
        <th style=" line-height: 15pt;" >YA</th>
        <th style=" line-height: 15pt;" >TIDAK</th>
    </tr>
    </thead>
              
';

$html2 .='<tbody>';
$icons1 = '';
$icons2 = '';
foreach ( $data->tabel as $i => $row ){
    $html2 .='<tr>';
    $html2 .='<td>'.++$i.'</td>';
    $html2 .='<td>'.$row->indikator.'</td>';
    if ($row->verifikasi==1){
        $icons1 ='<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_310_17035)">
        <path d="M17.5098 7.74121H16.2536C15.9804 7.74121 15.7205 7.87246 15.5598 8.09746L11.3491 13.9367L9.44197 11.2903C9.28125 11.068 9.02411 10.9341 8.74822 10.9341H7.49197C7.31786 10.9341 7.21608 11.1323 7.31786 11.2742L10.6554 15.9028C10.7342 16.0129 10.8381 16.1025 10.9586 16.1644C11.079 16.2262 11.2124 16.2585 11.3478 16.2585C11.4831 16.2585 11.6166 16.2262 11.737 16.1644C11.8574 16.1025 11.9613 16.0129 12.0402 15.9028L17.6813 8.08139C17.7857 7.93943 17.6839 7.74121 17.5098 7.74121Z" fill="#50CD89"/>
        <path d="M12.5 0C5.87321 0 0.5 5.37321 0.5 12C0.5 18.6268 5.87321 24 12.5 24C19.1268 24 24.5 18.6268 24.5 12C24.5 5.37321 19.1268 0 12.5 0ZM12.5 21.9643C6.99821 21.9643 2.53571 17.5018 2.53571 12C2.53571 6.49821 6.99821 2.03571 12.5 2.03571C18.0018 2.03571 22.4643 6.49821 22.4643 12C22.4643 17.5018 18.0018 21.9643 12.5 21.9643Z" fill="#50CD89"/>
        </g>
        <defs>
        <clipPath id="clip0_310_17035">
        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
        </clipPath>
        </defs>
        </svg>';
        $icons2 = '';
    }
    else{
        $icons2 ='<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_310_17047)">
        <path d="M17.1448 7.78948C17.1448 7.67162 17.0483 7.5752 16.9305 7.5752L15.1626 7.58323L12.5001 10.7573L9.84029 7.58591L8.06975 7.57787C7.9519 7.57787 7.85547 7.67162 7.85547 7.79216C7.85547 7.84305 7.87422 7.89127 7.90636 7.93145L11.3912 12.0832L7.90636 16.2323C7.87399 16.2716 7.85604 16.3207 7.85547 16.3716C7.85547 16.4895 7.9519 16.5859 8.06975 16.5859L9.84029 16.5779L12.5001 13.4038L15.1599 16.5752L16.9278 16.5832C17.0456 16.5832 17.1421 16.4895 17.1421 16.3689C17.1421 16.3181 17.1233 16.2698 17.0912 16.2297L13.6117 12.0806L17.0965 7.92877C17.1287 7.89127 17.1448 7.84037 17.1448 7.78948Z" fill="#F1416C"/>
        <path d="M12.5 0.0268555C5.87321 0.0268555 0.5 5.40007 0.5 12.0269C0.5 18.6536 5.87321 24.0269 12.5 24.0269C19.1268 24.0269 24.5 18.6536 24.5 12.0269C24.5 5.40007 19.1268 0.0268555 12.5 0.0268555ZM12.5 21.9911C6.99821 21.9911 2.53571 17.5286 2.53571 12.0269C2.53571 6.52507 6.99821 2.06257 12.5 2.06257C18.0018 2.06257 22.4643 6.52507 22.4643 12.0269C22.4643 17.5286 18.0018 21.9911 12.5 21.9911Z" fill="#F1416C"/>
        </g>
        <defs>
        <clipPath id="clip0_310_17047">
        <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
        </clipPath>
        </defs>
        </svg>';
        $icons1 = '';
    }
    $html2 .='<td style="text-align: center;">'.$icons1.'</td>';
    $html2 .='<td style="text-align: center;">'.$icons2.'</td>';
    $html2 .='<td>'.$row->tindak_lanjut.'</td>';
    $html2 .='</tr>';
}
$html2 .='</tbody>';
$html2 .= '</table>';


$mpdf->WriteHTML($html2);


$mpdf->SetTitle('Berita Acara Hasil Verifikasi RKPDes '.$data->unit_kerja.'');
$mpdf->Output();


        return $html;
    }
    public function konsederan_rpjmdes($data){
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Konsederan RKPMDes '.$data->unit_kerja.'')
            ->setSubject('Konsederan RKPMDes '.$data->unit_kerja.'')
            ->setDescription('Konsederan RKPMDes '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('RKPMDes');
        $sheet = $spreadsheet->getActiveSheet();
        //$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        //Margin PDF
        
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.8);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(1.2);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1.0);
        $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setWrapText(true);

        // Header Text
        $sheet->setCellValue('A1', 'BERITA ACARA')->mergeCells('A1:G1');
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENEGAH DESA (RPJMDes)')->mergeCells('A2:G2');
        $sheet->setCellValue('A3', 'DESA '.strtoupper($data->unit_kerja))->mergeCells('A3:G3');
        $sheet->setCellValue('A4', 'KABUPATEN ENREKANG PERIODE '.$data->periode_awal.' - '.$data->periode_akhir)->mergeCells('A4:G4');
        $border = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:G5' )->applyFromArray($border);

        $sheet->setCellValue('A5', "NOMOR : ".strtoupper($data->nomor_konsederan))->mergeCells('A5:G5');
        $sheet->setCellValue('A6', " ")->mergeCells('A6:G6');
        $sheet->setCellValue('A7', '            Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Dokumen RPJM Desa '.$data->unit_kerja.' Kabupaten Enrekang Periode '.$data->periode_awal.' - '.$data->periode_akhir.', sebagai berikut :
            ')->mergeCells('A7:G7');
      
      $sheet->setCellValue('A8', "           Setelah dilakukan verifikasi RPJM Desa maka disepakati : ")->mergeCells('A8:G8');
      $sheet->setCellValue('A9', " ")->mergeCells('A9:G9');
      $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B11');
      $sheet->setCellValue('C10', "Pedoman penyusunan RPJM Desa agar disesuaikan dengan Ketentuan Peraturan Menteri Dalam Negeri Nomor 114 Tahun 2014 tentang Pedoman Pembangunan Desa, meliputi :")->mergeCells('C10:G10');
      $sheet->setCellValue('C11', "1. ");   
      $sheet->setCellValue('D11', "Penyempurnaan RPJM Desa sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;")->mergeCells('D11:G11');
      $sheet->setCellValue('C12', "2. ");   
      $sheet->setCellValue('D12', "RPJM Desa mengacu pada RPJM kabupaten/kota, yang memuat Visi dan Misi Kepala Desa, rencana penyelenggaraan Pemerintahan Desa, Pelaksanaan Pembangunan, Pembinaan Kemasyarakatan, Pemberdayaan Masyarakat, dan arah kebijakan Pembangunan Desa
      ")->mergeCells('D12:G12');

      //$sheet->setCellValue('A12', " ")->mergeCells('A12:G12');
      $sheet->setCellValue('A13', 'KEDUA')->mergeCells('A13:B13');
      $sheet->setCellValue('C13', "Melakukan penyempurnaan RPJM Desa Periode ".$data->periode_awal.' - '.$data->periode_akhir." Berdasarkan  hasil verifikasi, meliputi :")->mergeCells('C13:G13');
      $sheet->setCellValue('C14', "1. "); 
      $sheet->setCellValue('D14', "Penyempurnaan RPJM Desa sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;")->mergeCells('D14:G14');
      $sheet->setCellValue('C15', "2. "); 
      $sheet->setCellValue('D15', "Melakukan Upload Dokumen perbaikan atas hasil verifikasi RPJM Desa ".$data->unit_kerja." Periode ".$data->periode_awal.' - '.$data->periode_akhir." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.")->mergeCells('D15:G15');
      
      $sheet->setCellValue('A18', ' ')->mergeCells('A18:G18');
      $sheet->setCellValue('A19', '           Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A19:G19');

      $sheet->setCellValue('A20', ' ')->mergeCells('A20:G20');

      $sheet->setCellValue('A21', 'Verifikator RPJM Desa')->mergeCells('A21:E21');
      $sheet->setCellValue('A22', ' Kabupaten Enrekang')->mergeCells('A22:E22');
      $sheet->setCellValue('A23', ' ')->mergeCells('A23:F23');
      $sheet->setCellValue('A24', ' ')->mergeCells('A24:F24');
      $sheet->setCellValue('A25', ' ')->mergeCells('A25:F25');
      $sheet->setCellValue('A26', ' ')->mergeCells('A26:F26');
      
      $sheet->getStyle('A27')->getFont()->setUnderline(true);
      $sheet->setCellValue('A27', $data->nama_verifikator)->mergeCells('A27:E27');
      $sheet->setCellValue('A28', $data->nip_verifikator)->mergeCells('A28:E28');



      $sheet->setCellValue('F21', 'Tim Penyusun RPJM')->mergeCells('F21:G21');
      $sheet->setCellValue('F22', 'Desa '.$data->unit_kerja.' Kabupaten Enrekang')->mergeCells('F22:G22');
      
      $sheet->getStyle('F27')->getFont()->setUnderline(true);
      $sheet->setCellValue('F27', $data->nama_user)->mergeCells('F27:G27');
      //$sheet->setCellValue('F28', $data->nip_user)->mergeCells('F28:G28');
      $sheet->setCellValue('A29', '')->mergeCells('A29:G29');



        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENENGAH DESA')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'(RPJM DESA) '.strtoupper($data->unit_kerja).' PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . ($cell+1));
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':D' . ($cell+1));
        $sheet->setCellValue('E'. $cell,'KESESUAIAN')->mergeCells('E'. $cell . ':F' . $cell);
        $sheet->setCellValue('E'. ($cell+1),'YA');
        $sheet->setCellValue('F'. ($cell+1),'TIDAK');
        $sheet->setCellValue('G'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('G'. $cell . ':G' . ($cell+1));
        
        $cell++;

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(30);

        $cell++;
        $i=0;
        

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator)->mergeCells('B'. $cell . ':D' . $cell);
            if ($row->verifikasi==1){
                $sheet->setCellValue('E' . $cell, 'v');
            }
            else{
                $sheet->setCellValue('F' . $cell, 'v');
            }
            $sheet->setCellValue('G' . $cell, $row->tindak_lanjut);
            $cell++;
        }

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A38:G'. $cell )->applyFromArray($border);
        $sheet->getStyle('A35:G'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B40:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('G40:G'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:G6')->getFont()->setBold(true);
        $sheet->getStyle('A1:G6')->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('A7:G20')->getAlignment()->setVertical('top')->setHorizontal('justify');
        $sheet->getStyle('A21:G28')->getAlignment()->setVertical('top')->setHorizontal('center');
        
        $cell++;

        $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':G' . $cell);
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddHeader('&C&H' . url()->current());
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddFooter('&L&B &RPage &P of &N');
            $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
            \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
            header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment;filename="EVALUASI RENJA '.$dinas.'.pdf"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
            $writer->save('php://output');
            exit;
    }

    public function konsederan_rkpdes($data){
        $spreadsheet = new Spreadsheet();
        // return $data;

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Konsederan RKPDes '.$data->unit_kerja.'')
            ->setSubject('Konsederan RKPDes '.$data->unit_kerja.'')
            ->setDescription('Konsederan RKPDes '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('RKPDes');
        $sheet = $spreadsheet->getActiveSheet();
        //$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        //Margin PDF
        
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.8);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(1.2);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1.0);
        $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setWrapText(true);

        // Header Text
        $sheet->setCellValue('A1', 'BERITA ACARA')->mergeCells('A1:G1');
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN RENCANA KERJA PEMERINTAH DESA')->mergeCells('A2:G2');
        $sheet->setCellValue('A3', '(RKP Desa) '.strtoupper($data->unit_kerja))->mergeCells('A3:G3');
        $sheet->setCellValue('A4', 'KABUPATEN ENREKANG TAHUN '.$data->tahun)->mergeCells('A4:G4');
        $border = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:G5' )->applyFromArray($border);

        $sheet->setCellValue('A5', "NOMOR : ".strtoupper($data->nomor_konsederan))->mergeCells('A5:G5');
        $sheet->setCellValue('A6', " ")->mergeCells('A6:G6');
        $sheet->setCellValue('A7', '            Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Dokumen RKP Desa '.$data->unit_kerja.' Kabupaten Enrekang Tahun '.($data->tahun+1).', sebagai berikut :
            ')->mergeCells('A7:G7');
      
      $sheet->setCellValue('A8', "          Setelah dilakukan verifikasi RKP Desa maka disepakati : ")->mergeCells('A8:G8');
      $sheet->setCellValue('A9', " ")->mergeCells('A9:G9');
      $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B11');
      $sheet->setCellValue('C10', "Pedoman penyusunan RKP Desa agar disesuaikan dengan Ketentuan Peraturan Menteri Dalam Negeri Nomor 114 Tahun 2014 tentang Pedoman Pembangunan Desa, meliputi : ")->mergeCells('C10:G10');
      $sheet->setCellValue('C11', "1. ");   
      $sheet->setCellValue('D11', "Evaluasi pelaksanaan RKP Desa tahun sebelumnya ;")->mergeCells('D11:G11');
      $sheet->setCellValue('C12', "2. ");   
      $sheet->setCellValue('D12', "Prioritas program, kegiatan, dan anggaran Desa yang dikelola oleh Desa ;")->mergeCells('D12:G12');
      $sheet->setCellValue('C13', "3. ");   
      $sheet->setCellValue('D13', "Prioritas program, kegiatan, dan anggaran Desa yang dikelola melalui kerja sama antar-Desa dan pihak ketiga ;")->mergeCells('D13:G13');
      $sheet->setCellValue('C14', "4. ");   
      $sheet->setCellValue('D14', "Rencana program, kegiatan, dan anggaran Desa yang dikelola oleh Desa sebagai kewenangan penugasan dari Pemerintah, pemerintah daerah provinsi, dan pemerintah daerah kabupaten/kota; dan")->mergeCells('D14:G14');
      $sheet->setCellValue('C15', "5. ");   
      $sheet->setCellValue('D15', "Pelaksana kegiatan Desa yang terdiri atas unsur perangkat Desa dan/atau unsur masyarakat Desa.")->mergeCells('D15:G15');
    
      $sheet->setCellValue('A16', " ")->mergeCells('A16:G16');
      $sheet->setCellValue('A17', "KEDUA")->mergeCells('A17:B17');
      $sheet->setCellValue('C17', "Melakukan penyempurnaan RKP Desa Tahun ".($data->tahun+1)." Berdasarkan  hasil verifikasi, meliputi :")->mergeCells('C17:G17');
      $sheet->setCellValue('C18', "1. "); 
      $sheet->setCellValue('D18', "Penyempurnaan RKP Desa sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini; ")->mergeCells('D18:G18');
      $sheet->setCellValue('C19', "2. "); 
      $sheet->setCellValue('D19', "Melakukan Upload Dokumen perbaikan atas hasil verifikasi RKP Desa ".$data->unit_kerja." Tahun ".($data->tahun+1)." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.")->mergeCells('D19:G19');
      
      $sheet->setCellValue('A20', ' ')->mergeCells('A20:G20');
      $sheet->setCellValue('A21', '         Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A21:G21');

      $sheet->setCellValue('A22', ' ')->mergeCells('A22:G22');

      $sheet->setCellValue('A23', 'Verifikator RKP Desa')->mergeCells('A23:E23');
      $sheet->setCellValue('A24', ' Kabupaten Enrekang')->mergeCells('A24:E24');
      $sheet->setCellValue('A25', ' ')->mergeCells('A25:F25');
      $sheet->setCellValue('A26', ' ')->mergeCells('A26:F26');
      $sheet->setCellValue('A27', ' ')->mergeCells('A27:F27');
      $sheet->setCellValue('A28', ' ')->mergeCells('A28:F28');
      
      $sheet->getStyle('A29')->getFont()->setUnderline(true);
      $sheet->setCellValue('A29', $data->nama_verifikator)->mergeCells('A29:E29');
      $sheet->setCellValue('A30', $data->nip_verifikator)->mergeCells('A30:E30');



      $sheet->setCellValue('F23', 'Tim Penyusun RKP')->mergeCells('F23:G23');
      $sheet->setCellValue('F24', 'Desa '.$data->unit_kerja.' Kabupaten Enrekang')->mergeCells('F24:G24');
      
      
      $sheet->setCellValue('F29', $data->nama_user)->mergeCells('F29:G29');
      $sheet->setCellValue('A31', ' 
      











      

      
      ')->mergeCells('A31:G31');




        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RENCANA KERJA PEMERINTAH DESA')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'(RKPD DESA) '.strtoupper($data->unit_kerja).' TAHUN '.($data->tahun+1))->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . ($cell+1));
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':D' . ($cell+1));
        $sheet->setCellValue('E'. $cell,'KESESUAIAN')->mergeCells('E'. $cell . ':F' . $cell);
        $sheet->setCellValue('E'. ($cell+1),'ADA');
        $sheet->setCellValue('F'. ($cell+1),'TIDAK ADA');
        $sheet->setCellValue('G'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('G'. $cell . ':G' . ($cell+1));
        
        $cell++;

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(30);

        $cell++;
        $i=0;
        

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator)->mergeCells('B'. $cell . ':D' . $cell);
            if ($row->verifikasi==1){
                $sheet->setCellValue('E' . $cell, 'v');
            }
            else{
                $sheet->setCellValue('F' . $cell, 'v');
            }
            $sheet->setCellValue('G' . $cell, $row->tindak_lanjut);
            $cell++;
        }

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A38:G'. $cell )->applyFromArray($border);
        $sheet->getStyle('A35:G'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B40:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('G40:G'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:G6')->getFont()->setBold(true);
        $sheet->getStyle('A1:G6')->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('A7:G20')->getAlignment()->setVertical('top')->setHorizontal('justify');
        $sheet->getStyle('A21:G34')->getAlignment()->setVertical('top')->setHorizontal('center');
        
        $cell++;

        $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':G' . $cell);        
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddHeader('&C&H' . url()->current());
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddFooter('&L&B &RPage &P of &N');
            $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
            \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
            header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment;filename="EVALUASI RENJA '.$dinas.'.pdf"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
            $writer->save('php://output');
            exit;
    }

    // referensi
    public function alokasi_skpd(){
        $breadcumb = 'Data Referensi';
        $current_breadcumb = 'Daftar Alokasi SKPD';

        return view('module.desa.referensi.daftar_alokasi',compact('breadcumb','current_breadcumb'));
    } 

    public function data_group_alokasi(){
        $data = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<>'' $where_unit_kerja")->get();

        foreach($data as $unit_kerja_list){
            $unit_kerja_list->Dpa=DB::table('dpa')
            ->join('sub_kegiatan', 'dpa.id_sub_kegiatan', '=', 'sub_kegiatan.id')
            ->select('dpa.id','dpa.nilai_pagu_dpa','sub_kegiatan.nama_sub_kegiatan')
            ->where('dpa.tahun',$tahun)
            ->where('dpa.id_unit_kerja',$unit_kerja_list->id)
            ->get();
        }
    }

    public function data_alokasi_desa(){
        
        $perangkat_desa = DB::table('user')->select('perangkat_desa.id_desa')->join('perangkat_desa','user.id_unit_kerja','perangkat_desa.id')->where('user.id',Auth::user()->id)->first();

       

        $where_unit_kerja="";
        $where_paket = "";
        if (Auth::user()->id_role == 2) {
            $where_unit_kerja = " AND id=".Auth::user()->id_unit_kerja;
        }else if (Auth::user()->id_role == 3) {
            $where_paket = "AND desa.id=".$perangkat_desa->id_desa;
        }

        $result = [];
       $data = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<>'' $where_unit_kerja")->get();
       $tahun = session('tahun_penganggaran');
       $type = request('type');

        foreach($data as $unit_kerja_list){
            $unit_kerja_list->Dpa=DB::table('dpa')
            ->join('sub_kegiatan', 'dpa.id_sub_kegiatan', '=', 'sub_kegiatan.id')
            ->select('dpa.id','dpa.nilai_pagu_dpa','sub_kegiatan.nama_sub_kegiatan')
            ->where('dpa.tahun',$tahun)
            ->where('dpa.id_unit_kerja',$unit_kerja_list->id)
            ->get();
            foreach ( $unit_kerja_list->Dpa as $dpa ){
            $dpa->Paket=DB::table('paket_dak')
                ->selectRaw("paket_dak.id,paket_dak.satuan,paket_dak.id_sumber_dana_dpa,paket_dak.nama_paket,paket_dak.volume,paket_dak.anggaran_dak as pagu,@keterangan:='' as keterangan,@jenis_paket:='dak' as jenis_paket,sumber_dana_dpa.sumber_dana")
                ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dak.id_sumber_dana_dpa')
                ->whereRaw("paket_dak.id_dpa='$dpa->id' AND sumber_dana_dpa.jenis_belanja='Belanja Modal'")
                ->get();
                foreach($dpa->Paket as $paket){
                    $paket->Lokasi=DB::table('paket_dak_lokasi')
                    ->join('desa','paket_dak_lokasi.id_desa','=','desa.id')
                    ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                    ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                    ->whereRaw("id_paket_dak='$paket->id' $where_paket")
                    ->get();
                    

                    $paket->Desa='';
                    $paket->Kecamatan='';
                    foreach($paket->Lokasi as $lokasi){
                        $paket->Desa.=$lokasi->nama_desa.' ';
                        $paket->Kecamatan.=$lokasi->nama_kecamatan.' ';
                    }
                }
            }

        }

        foreach ( $data as $unit_kerja_list ){
            $unit_kerja_list->subCount=0;
            $unit_kerja_list->Pagu=0;
            foreach ( $unit_kerja_list->Dpa as $dpa ){
                $dpa->Pagu=0;
                foreach($dpa->Paket as $paket){
                    if($paket->Desa=='' || $paket->Kecamatan==''){
                        $dpa->Pagu-=$paket->pagu;
                        $unit_kerja_list->subCount-=1;
                        $unit_kerja_list->Pagu-=$paket->pagu;
                    }else{
                    }
                    $unit_kerja_list->subCount+=1;
                    $dpa->Pagu+=$paket->pagu;
                    $unit_kerja_list->Pagu+=$paket->pagu;
                }

            }

        }

        // return $data;
        foreach ( $data as $key => $res ){
            if($res->subCount==0){
                continue;
            }
            
            foreach ($res->Dpa as $dpa ){
                foreach ( $dpa->Paket as $i => $paket ){
                    if($paket->Desa=='' || $paket->Kecamatan==''){
                        continue; 
                    }
    
                    $result[] = [
                        'nama_unit_kerja' => $res->nama_unit_kerja,
                        'nama_paket' => $paket->nama_paket,
                        'pagu' => number_format($paket->pagu),
                        'volume' => $paket->volume.' '.$paket->satuan,
                        'sumber_dana' => $paket->sumber_dana,
                        'lokasi' => $paket->Desa
                    ];
                }
            }
        }
    

       if ($type == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $result,
            ]);
       }else{
            return $this->export_alokasi($data,$tahun);
       }
    }

    public function export_alokasi($data, $tahun){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('DAFTAR ALOKASI  ')
            ->setSubject('DAFTAR ALOKASI  ')
            ->setDescription('DAFTAR ALOKASI  ')
            ->setKeywords('pdf php')
            ->setCategory('KEMAJUAN');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $sheet->getRowDimension(5)->setRowHeight(25);
        $sheet->getRowDimension(1)->setRowHeight(17);
        $sheet->getRowDimension(2)->setRowHeight(17);
        $sheet->getRowDimension(3)->setRowHeight(17);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        //Margin PDF
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.8);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.3);

        $sheet->setCellValue('A1', 'LAPORAN DAFTAR ALOKASI PEMBANGUNAN');
        //$sheet->setCellValue('A2', 'tes');
        $sheet->setCellValue('A2', 'PEMERINTAH KABUPATEN ENREKANG TAHUN ANGGARAN ' . $tahun );
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $sheet->getStyle('A1')->getFont()->setSize(12);

        $sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:H6')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'NO')->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'URAIAN KEGIATAN')->mergeCells('B5:B6')->getColumnDimension('B')->setWidth(45);
        $sheet->setCellValue('C5', 'PAGU')->mergeCells('C5:C6')->getColumnDimension('C')->setWidth(25);
        $sheet->setCellValue('D5', 'LOKASI')->mergeCells('D5:E5');
        $sheet->setCellValue('F5', 'SUMBER DANA')->mergeCells('F5:F6')->getColumnDimension('F')->setWidth(15);
        $sheet->setCellValue('G5', 'VOLUME')->mergeCells('G5:G6')->getColumnDimension('G')->setWidth(15);
        $sheet->setCellValue('H5', 'KET')->mergeCells('H5:H6')->getColumnDimension('H')->setWidth(20);


        $sheet->setCellValue('D6', 'DESA/KEL')->mergeCells('D6:D6')->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E6', 'KECAMATAN')->mergeCells('E6:E6')->getColumnDimension('E')->setWidth(20);

        $sheet->getStyle('A5:H6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        $cell = 7;
        $tot_pagu=0;

        foreach ( $data as $res ){
            if($res->subCount==0){
                continue;
            }
            $sheet->getRowDimension($cell)->setRowHeight(25);
            $sheet->getStyle('A'.$cell.':H'.$cell)->getFont()->setBold(true);
            $sheet->setCellValue('B'.$cell,$res->nama_unit_kerja)->mergeCells('B'.$cell.':B'.$cell);
            $sheet->setCellValue('C'.$cell, 'Rp. '.number_format($res->Pagu))->mergeCells('D'.$cell.':D'.$cell);
            $sheet->getStyle('A' . $cell . ':H' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CC99FF');
            $tot_pagu+=$res->Pagu;
            $cell++;
            $i = 0;
            foreach ($res->Dpa as $dpa ){
                   if (count($dpa->Paket) !== 0) {
                      if ($dpa->Pagu > 0) {
                        $sheet->getRowDimension($cell)->setRowHeight(20);
                        $sheet->getStyle('A'.$cell.':I'.$cell)->getFont()->setBold(true);
                        $sheet->setCellValue('A' . $cell, ++$i);
                        $sheet->setCellValue('B' . $cell, $dpa->nama_sub_kegiatan);
                        $sheet->setCellValue('C' . $cell, 'Rp. '.number_format($dpa->Pagu));
                        $sheet->getStyle('A' . $cell . ':H' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
                        $cell++;
                      }
                   }
                    $j=0;
                    foreach ( $dpa->Paket as $paket ){
                            if($paket->Desa=='' || $paket->Kecamatan==''){
                                continue; 
                            }

                                $sheet->setCellValue('A' . $cell, $i.'.'.++$j);
                                $sheet->setCellValue('B' . $cell, $paket->nama_paket);
                                $sheet->setCellValue('C' . $cell, 'Rp. '.number_format($paket->pagu));
                                if (!empty($paket->Desa)) {
                                    $sheet->setCellValue('D' . $cell, $paket->Desa);
                                    $sheet->setCellValue('E' . $cell, rtrim($paket->Kecamatan, ", "));
                                }else{
                                    $sheet->setCellValue('D' . $cell, '');
                                    $sheet->setCellValue('E' . $cell, '');
                                }
                                $sheet->setCellValue('F' . $cell, $paket->volume.' '.$paket->satuan);
                                $sheet->setCellValue('G' . $cell, $paket->sumber_dana);
                                $sheet->setCellValue('H' . $cell, $paket->keterangan);
                                $cell++;
    
                    }
            }
        }

        $sheet->getRowDimension($cell)->setRowHeight(25);
        $sheet->getStyle('A'.$cell.':H'.$cell)->getFont()->setBold(true);
        $sheet->setCellValue('B'.$cell,'TOTAL ')->mergeCells('B'.$cell.':B'.$cell);
        $sheet->setCellValue('C'.$cell, 'Rp. '.number_format($tot_pagu));
        $sheet->getStyle('A'.$cell.':H'. $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');

        $sheet->getStyle('A1:H' . $cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:H' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //$sheet->getStyle('A4:A' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B7:B' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C7:C' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        // $sheet->getStyle('D7:D' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        // $sheet->getStyle('E7:E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        // $sheet->getStyle('H7:H' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C7:C' . $cell)->getNumberFormat()->setFormatCode('#.##0');

        $sheet->getStyle('A' . $cell . ':H' . $cell)->getFont()->setBold(true);
        $sheet->getRowDimension($cell)->setRowHeight(30);
        $sheet->getStyle('A' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:H' . $cell)->applyFromArray($border);
        $cell++;
   

            $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':H' . $cell);
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddHeader('&C&H' . url()->current());
            $spreadsheet->getActiveSheet()->getHeaderFooter()
                ->setOddFooter('&L&B &RPage &P of &N');
            $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
            \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
            header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment;filename="DAFTAR ALOKASI '.$dinas.'.pdf"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');

            $writer->save('php://output');
            exit;

    }
}
