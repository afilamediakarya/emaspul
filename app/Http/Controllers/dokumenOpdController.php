<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\unitKerja;
use App\Models\bidangUrusan;
use QrCode;
class dokumenOpdController extends Controller
{
    public function index(){
        $type = request('type');
        if ($type == 'Renstra') {
            $breadcumb = 'Dokumen Renstra';
            $current_breadcumb = '';
            return view('module.opd.dokumen.renstra',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Renja') {
            $breadcumb = 'Dokumen Renja';
            $current_breadcumb = '';
            return view('module.opd.dokumen.renja',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Data-sektoral') {
            $breadcumb = 'Dokumen Data Sektoral';
            $current_breadcumb = '';
            return view('module.opd.dokumen.sektoral',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Data-lainnya') {
            $breadcumb = 'Dokumen Data Lainnya';
            $current_breadcumb = '';
            return view('module.opd.dokumen.skpd',compact('breadcumb','current_breadcumb'));
        }
    }

    public function index_verifikator(){
        $type = request('type');
        if ($type == 'Renstra') {
            $breadcumb = 'Dokumen Renstra';
            $current_breadcumb = 'Renstra';
            $role = Auth::user()->id_role;
            return view('module.opd.dokumen.partials.renstra',compact('breadcumb','current_breadcumb','role'));
        }
        if ($type == 'Renja') {
            $breadcumb = 'Dokumen Renja';
            $current_breadcumb = 'Renja';
            $role = Auth::user()->id_role;
            return view('module.opd.dokumen.partials.renja',compact('breadcumb','current_breadcumb','role'));
        }
        if ($type == 'Sektoral') {
            $breadcumb = 'Dokumen Data Sektoral';
            $current_breadcumb = 'Data Sektoral';
            return view('module.opd.dokumen.partials.sektoral',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Skpd') {
            $breadcumb = 'Dokumen Data Skpd';
            $current_breadcumb = 'Data Skpd';
            return view('module.opd.dokumen.partials.skpd',compact('breadcumb','current_breadcumb'));
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
       
        $data = DB::table('documents')->select('documents.id','documents.tahun','documents.periode_awal','documents.periode_akhir','documents.nomor_konsederan','documents.user_insert','unit_kerja.nama_unit_kerja as unit_kerja','user.nama_lengkap as nama_verifikator','user.nip as nip_verifikator')->join('unit_kerja','documents.id_perangkat','=','unit_kerja.id')->join('user', 'documents.id_verifikator','=','user.id')->where('documents.id',$document)->first();
        $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();

        $user = DB::table('user')->where('id',$data->user_insert)->first();        

        $data->hari = $this->getHari();
        $data->tanggal = date('d');
        $data->bulan = date('m');
        $data->tahun = date('Y');
        $data->nama_user = $user->nama_lengkap;
        $data->nip_user = $user->nip;
        
        return $this->{$fungsi}($data,$jenis,$document);
    }

    public function html_render_renstra($data,$jenis,$document){

        $qrcode=QrCode::size(120)->generate('https://langitmaspul.enrekangkab.go.id/detail-dokumen?document='.$document.'&jenis='.$jenis);

                $html = '';

                $html .= '<h4 style="text-align:center; line-height: 12pt;">BERITA ACARA <br> HASIL VERIFIKASI RANCANGAN RENCANA STRATEGIS (RENSTRA) <br>'.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG PERIODE '.$data->periode_awal.' - '.$data->periode_akhir.'<hr></h4>';

                $html .= "<h4 style='text-align:center; line-height: -20pt;'>NOMOR : ".strtoupper($data->nomor_konsederan)."</h4>";

                $html .= '<p style="text-align:justify" line-height: 12pt; style="text-indent: 45px;">
                Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renstra PD '.$data->unit_kerja.' Kabupaten Enrekang Periode '.$data->periode_awal.' - '.$data->periode_akhir.', sebagai berikut : </p>';

                $html .= '<p style="text-align:justify" style="text-indent: 45px;"> Setelah dilakukan verifikasi rancangan awal Renstra maka disepakati : </p>';

                $html .= "<table style='vertical-align: text-top; line-height: 12pt;'>
                <tr>
                    <td style='width: 18%;'>KESATU</td>
                    <td style='text-align: justify;  line-height: 12pt;'>
                    Sistematika penulisan Renstra agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :
                    
                        <table>
                            <tr>
                            <td style='vertical-align: text-top;'>1. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Pendahuluan;</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>2. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Gambaran Pelayanan Perangkat Daerah;</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>3. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Permasalahan dan Isu Isu Strategis Perangkat Daerah</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>4. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Tujuan dan Sasaran Perangkat Daerah;</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>5. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Rencana Program dan Kegiatan serta  Pendanaan;</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>6. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Kinerja Penyelenggaran Bidang Urusan; dan</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top;'>7. </td>
                            <td style='text-align: justify;  line-height: 12pt;'>Penutup.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>KEDUA</td>
                    <td style='text-align:justify line-height: 12pt;'>Melakukan penyempurnaan rancangan Renstra Tahun periode ".$data->periode_awal.'-'.$data->periode_akhir." Berdasarkan  hasil verifikasi, meliputi :
                            <table>
                            <tr>
                            <td style='vertical-align: text-top; '>1. </td>
                            <td style='text-align: justify; line-height: 12pt;'>Penyempurnaan rancangan Renstra sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;</td>
                            </tr>
                            <tr>
                            <td style='vertical-align: text-top; '>2. </td>
                            <td style='text-align: justify; line-height: 12pt;'>Penyempurnaan matrik Rumusan Rencana Program dan Kegiatan Perangkat Daerah periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://emonev.enrekangkab.go.id/</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>KETIGA</td>
                    <td style='text-align:justify line-height: 12pt;'>Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renstra periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.
                            
                    </td>
                </tr>
            </table>";

            

            $html .= '<p style="text-align:justify vertical-align: text-top;"  style="text-indent: 45px;">Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.</p>';

            $html .= '<table style="width:100%">
            <tr>
                <td style="width:50%;"></td>
                <td style="width:50%; text-align:center;">Verifikator Renstra PD<br>Kabupaten Enrekang</td>
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

        $html2 = '<h4 style="text-align:center; line-height: 12pt;">FORMULIR VERIFIKASI RANCANGAN RENCANA STRATEGIS (RENSTRA) <br>'.strtoupper($data->unit_kerja).'<br>KABUPATEN ENREKANG PERIODE '.$data->periode_awal.'-'.$data->periode_akhir.'<hr></h4>';

        $html2 .='      
<table border="1" style="border-collapse:collapse; width:100%;">
    <thead>
    <tr>
        <th style=" line-height: 12pt; width:5%;" rowspan="2" >NO.</th>
        <th style=" line-height: 12pt; width:45%;" rowspan="2">INDIKATOR</th>
        <th style=" line-height: 12pt; width:20%;" colspan="2">KESESUAIAN</th>
        <th style="text-align: center; width:30%;  line-height: 12pt;" rowspan="2">TINDAK LANJUT</th>
    </tr>
    <tr>
        <th style=" line-height: 12pt;" >YA</th>
        <th style=" line-height: 12pt;" >TIDAK</th>
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


        $mpdf->SetTitle('Berita Acara Hasil Verifikasi Renstra '.$data->unit_kerja.'');
        $mpdf->Output();





        return $html;
    }

    public function html_render_renja($data,$jenis,$document){

        $qrcode=QrCode::size(120)->generate('https://langitmaspul.enrekangkab.go.id/detail-dokumen?document='.$document.'&jenis='.$jenis);
         
        $html = '';

        $html .= '<h4 style="text-align:center; line-height: 12pt;">BERITA ACARA <br> HASIL VERIFIKASI RANCANGAN RENCANA KERJA<br>'.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG PERIODE '.$data->tahun.'<hr></h4>';

        $html .= "<h4 style='text-align:center; line-height: -20pt;'>NOMOR : ".strtoupper($data->nomor_konsederan)."</h4>";

        $html .= '<p style="text-align:justify" line-height: 12pt; style="text-indent: 45px;">
        Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renja  '.$data->unit_kerja.' Kabupaten Enrekang Periode '.$data->tahun.', sebagai berikut : </p>';

        $html .= '<p style="text-align:justify" style="text-indent: 45px;"> Setelah dilakukan verifikasi rancangan awal Renja maka disepakati : </p>';

        $html .= "<table style='vertical-align: text-top; line-height: 12pt;'>
        <tr>
            <td style='width: 18%;'>KESATU</td>
            <td style='text-align: justify;  line-height: 12pt;'>Sistematika penulisan Renja agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :
            
                <table>
                    <tr>
                    <td style='vertical-align: text-top;'>1. </td>
                    <td style='text-align: justify;  line-height: 12pt;'>Pendahuluan;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top;'>2. </td>
                    <td style='text-align: justify;  line-height: 12pt;'>Hasil Evaluasi Renja Perangkat Daerah tahun lalu;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top;'>3. </td>
                    <td style='text-align: justify;  line-height: 12pt;'>Tujuan dan Sasaran Perangkat Daerah;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top;'>4. </td>
                    <td style='text-align: justify;  line-height: 12pt;'>Rencana Kerja dan Pendanaan Perangkat Daerah; dan</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top;'>5. </td>
                    <td style='text-align: justify;  line-height: 12pt;'>Penutup.</td>
                    </tr>
                    
                </table>
            </td>
        </tr>
        <tr>
            <td>KEDUA</td>
            <td style='text-align:justify line-height: 12pt;'>Melakukan penyempurnaan rancangan Renja Tahun ".($data->tahun+1)." Berdasarkan  hasil verifikasi, meliputi :
                    <table>
                    <tr>
                    <td style='vertical-align: text-top; '>1. </td>
                    <td style='text-align: justify; line-height: 12pt;'>Penyempurnaan rancangan Renja sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;</td>
                    </tr>
                    <tr>
                    <td style='vertical-align: text-top; '>2. </td>

                    <td style='text-align: justify; line-height: 12pt;'>Penyempurnaan matrik Rumusan Rencana Program dan Kegiatan Perangkat Daerah Tahun ".($data->tahun+1)." dan Prakiraan Maju Tahun ".($data->tahun+2)." melalui portal https://enrekangkab.sipd.kemendagri.go.id/</td>
                    </tr>
                </table>
            </td>
            <tr>
            <td>KETIGA</td>
            <td style='text-align:justify line-height: 12pt;'>Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renja Tahun ".($data->tahun+1)." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.
            </tr>     
            </td>
        </tr>
    </table>";

    

    $html .= '<p style="text-align:justify vertical-align: text-top;"  style="text-indent: 45px;">Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.</p>';

    $html .= '<table style="width:100%">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">Verifikator Renja PD<br>Kabupaten Enrekang</td>
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

$html2 = '<h4 style="text-align:center; line-height: 12pt;">FORMULIR VERIFIKASI RANCANGAN RENCANA KERJA <br>'.strtoupper($data->unit_kerja).'<br> KABUPATEN ENREKANG TAHUN PERIODE '.$data->tahun.'<hr></h4>';

$html2 .='      
<table border="1" style="border-collapse:collapse; width:100%;">
    <thead>
    <tr>
        <th style=" line-height: 12pt; width:5%;" rowspan="2" >NO.</th>
        <th style=" line-height: 12pt; width:45%;" rowspan="2">INDIKATOR</th>
        <th style=" line-height: 12pt; width:20%;" colspan="2">KESESUAIAN</th>
        <th style="text-align: center; width:30%;  line-height: 12pt;" rowspan="2">TINDAK LANJUT</th>
    </tr>
    <tr>
        <th style=" line-height: 12pt;" >YA</th>
        <th style=" line-height: 12pt;" >TIDAK</th>
    </tr>
    </thead>
              
';

$html2 .='<tbody>';
$icons1 = '';
$icons2 = '';
foreach ( $data->tabel as $i => $row ){
    $html2 .='<tr>';
    $html2 .='<td>'.$i.'</td>';
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

    public function konsederan_renstra($data){
      
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Konsederan Renstra '.$data->unit_kerja.'')
            ->setSubject('Konsederan Renstra '.$data->unit_kerja.'')
            ->setDescription('Konsederan Renstra '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('Renstra');
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
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN RENCANA STRATEGIS (RENSTRA)')->mergeCells('A2:G2');
        $sheet->setCellValue('A3', ''.strtoupper($data->unit_kerja))->mergeCells('A3:G3');
        $sheet->setCellValue('A4', 'KABUPATEN ENREKANG PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A4:G4');
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
        $sheet->setCellValue('A7', '        Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renstra PD '.$data->unit_kerja.' Kabupaten Enrekang periode '.$data->periode_awal.'-'.$data->periode_akhir.', sebagai berikut : 
              ')->mergeCells('A7:G7');
        
        $sheet->setCellValue('A8', "           Setelah dilakukan verifikasi rancangan awal Renstra maka disepakati : ")->mergeCells('A8:G8');
        $sheet->setCellValue('A9', " ")->mergeCells('A9:G9');
        $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B11');
        $sheet->setCellValue('C10', "Sistematika penulisan Renstra agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :")->mergeCells('C10:G10');
        $sheet->setCellValue('C11', "1.
2.
3.
4.
5.
6.
7.");   
        $sheet->setCellValue('D11', "Pendahuluan;
Gambaran Pelayanan Perangkat Daerah;
Permasalahan dan Isu Isu Strategis Perangkat Daerah
Tujuan dan Sasaran Perangkat Daerah;
Rencana Program dan Kegiatan serta  Pendanaan;
Kinerja Penyelenggaran Bidang Urusan; dan
Penutup.")->mergeCells('D11:G11');
        $sheet->setCellValue('A12', " ")->mergeCells('A12:G12');
        $sheet->setCellValue('A13', "KEDUA")->mergeCells('A13:B13');
        $sheet->setCellValue('C13', "Melakukan penyempurnaan rancangan Renstra Tahun periode ".$data->periode_awal.'-'.$data->periode_akhir." Berdasarkan  hasil verifikasi, meliputi :")->mergeCells('C13:G13');
        $sheet->setCellValue('C14', "1. "); 
        $sheet->setCellValue('D14', "Penyempurnaan rancangan Renstra sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;")->mergeCells('D14:G14');
        $sheet->setCellValue('C15', "2. "); 
        $sheet->setCellValue('D15', "Penyempurnaan matrik Rumusan Rencana Program dan Kegiatan Perangkat Daerah periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://emonev.enrekangkab.go.id/")->mergeCells('D15:G15');
        
        $sheet->setCellValue('A16', " ")->mergeCells('A16:G16');
        $sheet->setCellValue('A17', "KETIGA")->mergeCells('A17:B17');
        $sheet->setCellValue('C17', "Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renstra periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.")->mergeCells('C17:G17');

        $sheet->setCellValue('A18', ' ')->mergeCells('A18:G18');
        $sheet->setCellValue('A19', '           Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A19:G19');

        $sheet->setCellValue('A20', ' ')->mergeCells('A20:G20');

        $sheet->setCellValue('A21', 'Verifikator Renstra PD')->mergeCells('A21:E21');
        $sheet->setCellValue('A22', ' Kabupaten Enrekang')->mergeCells('A22:E22');
        $sheet->setCellValue('A23', ' ')->mergeCells('A23:F23');
        $sheet->setCellValue('A24', ' ')->mergeCells('A24:F24');
        $sheet->setCellValue('A25', ' ')->mergeCells('A25:F25');
        $sheet->setCellValue('A26', ' ')->mergeCells('A26:F26');
        
        $sheet->getStyle('A27')->getFont()->setUnderline(true);
        $sheet->setCellValue('A27', $data->nama_verifikator)->mergeCells('A27:E27');
        $sheet->setCellValue('A28', $data->nip_verifikator)->mergeCells('A28:E28');



        $sheet->setCellValue('F21', 'Tim Penyusun  Renstra')->mergeCells('F21:G21');
        $sheet->setCellValue('F22', $data->unit_kerja.' Kabupaten Enrekang')->mergeCells('F22:G22');
        
        $sheet->getStyle('F27')->getFont()->setUnderline(true);
        $sheet->setCellValue('F27', $data->nama_user)->mergeCells('F27:G27');
        $sheet->setCellValue('F28', $data->nip_user)->mergeCells('F28:G28');
        $sheet->setCellValue('A29', ' 
        





        
        
        
        ')->mergeCells('A29:G29');
       
        
        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RANCANGAN RENCANA STRATEGIS  SATUAN KERJA')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'PERANGKAT DAERAH (RENSTRA - SKPD) PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':F' . $cell);
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
                ->setOddFooter('&L&B &RPage &P of &N'.url()->current());
            $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
            \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
            header('Content-Type: application/pdf');
            //header('Content-Disposition: attachment;filename="Konsederan Renstra '.$data->unit_kerja.'.pdf"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
            $writer->save('php://output');
            exit;
    }

    public function konsederan_renja($data){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Konsederan Renja '.$data->unit_kerja.'')
            ->setSubject('Konsederan Renja '.$data->unit_kerja.'')
            ->setDescription('Konsederan Renja '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('Renja');
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
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN AWAL RENCANA KERJA (RENJA)')->mergeCells('A2:G2');
        $sheet->setCellValue('A3', ''.strtoupper($data->unit_kerja))->mergeCells('A3:G3');
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
        $sheet->setCellValue('A7', '           Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renja  '.$data->unit_kerja.' Kabupaten Enrekang tahun '.($data->tahun+1).', sebagai berikut :
              ')->mergeCells('A7:G7');
        
        $sheet->setCellValue('A8', "           Setelah dilakukan verifikasi rancangan awal Renja maka disepakati : ")->mergeCells('A8:G8');
        $sheet->setCellValue('A9', " ")->mergeCells('A9:G9');
        $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B11');
        $sheet->setCellValue('C10', "Sistematika penulisan Renja agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :")->mergeCells('C10:G10');
        $sheet->setCellValue('C11', "1.
2.
3.
4.
5.");   
        $sheet->setCellValue('D11', "Pendahuluan;
Hasil Evaluasi Renja Perangkat Daerah tahun lalu;
Tujuan dan Sasaran Perangkat Daerah;
Rencana Kerja dan Pendanaan Perangkat Daerah; dan
Penutup.")->mergeCells('D11:G11');
        $sheet->setCellValue('A12', " ")->mergeCells('A12:G12');
        $sheet->setCellValue('A13', "KEDUA")->mergeCells('A13:B13');
        $sheet->setCellValue('C13', "Melakukan penyempurnaan rancangan Renja Tahun ".($data->tahun+1)." Berdasarkan  hasil verifikasi, meliputi :")->mergeCells('C13:G13');
        $sheet->setCellValue('C14', "1. "); 
        $sheet->setCellValue('D14', "Penyempurnaan rancangan Renja sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini;")->mergeCells('D14:G14');
        $sheet->setCellValue('C15', "2. "); 
        $sheet->setCellValue('D15', "Penyempurnaan matrik Rumusan Rencana Program dan Kegiatan Perangkat Daerah Tahun ".($data->tahun+1)." dan Prakiraan Maju Tahun ".($data->tahun+2)." melalui portal https://enrekangkab.sipd.kemendagri.go.id/")->mergeCells('D15:G15');
        
        $sheet->setCellValue('A16', " ")->mergeCells('A16:G16');
        $sheet->setCellValue('A17', "KETIGA")->mergeCells('A17:B17');
        $sheet->setCellValue('C17', "Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renja Tahun ".($data->tahun+1)." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF.")->mergeCells('C17:G17');

        $sheet->setCellValue('A18', ' ')->mergeCells('A18:G18');
        $sheet->setCellValue('A19', '           Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A19:G19');

        $sheet->setCellValue('A20', ' ')->mergeCells('A20:G20');

        $sheet->setCellValue('A21', 'Verifikator Renja PD')->mergeCells('A21:E21');
        $sheet->setCellValue('A22', ' Kabupaten Enrekang')->mergeCells('A22:E22');
        $sheet->setCellValue('A23', ' ')->mergeCells('A23:G23');
        $sheet->setCellValue('A24', ' ')->mergeCells('A24:G24');
        $sheet->setCellValue('A25', ' ')->mergeCells('A25:G25');
        $sheet->setCellValue('A26', ' ')->mergeCells('A26:G26');
        
        $sheet->getStyle('A27')->getFont()->setUnderline(true);
        $sheet->setCellValue('A27', $data->nama_verifikator)->mergeCells('A27:E27');
        $sheet->setCellValue('A28', $data->nip_verifikator)->mergeCells('A28:E28');



        $sheet->setCellValue('F21', 'Tim Penyusun  Renja')->mergeCells('F21:G21');
        $sheet->setCellValue('F22', $data->unit_kerja.' Kabupaten Enrekang')->mergeCells('F22:G22');
        
        $sheet->getStyle('F27')->getFont()->setUnderline(true);
        $sheet->setCellValue('F27', $data->nama_user)->mergeCells('F27:G27');
        $sheet->setCellValue('F28', $data->nip_user)->mergeCells('F28:G28');
        $sheet->setCellValue('A29', '')->mergeCells('A29:G29');
        

        $spreadsheet->createSheet();
        // Zero based, so set the second tab as active sheet
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('Second tab');

        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RANCANGAN RENCANA KERJA  SATUAN KERJA')->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'PERANGKAT DAERAH (RENJA - SKPD) TAHUN '.($data->tahun+1))->mergeCells('A'. $cell . ':G' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':F' . $cell);
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

    public function ikk_view(){
        $breadcumb = 'Data Indikator Kinerja Kunci';
        $current_breadcumb = '';
        $tahun = session('tahun_penganggaran') - 2;
        return view('module.opd.dokumen.ikk',compact('breadcumb','current_breadcumb','tahun'));
    }

    public function export_ikk(){
        $tahun = session('tahun_penganggaran');
        $type = request('type');
        $tahun_sebelum=$tahun-1;
        $tahun_setelah=$tahun+1;
        $dinas = unitKerja::find(Auth::user()->id_unit_kerja)->nama_unit_kerja;

        $data=DB::table('renstra_program')
        ->select('renstra_program.id','program.kode_program','program.nama_program','renstra_program.id_program')
        ->join('program','program.id','=','renstra_program.id_program')
        ->whereRaw("id_unit_kerja=".Auth::user()->id_unit_kerja)->get();
     
            foreach ( $data as $program ){
            $program->Outcome=DB::table('renstra_program_outcome')
            ->select('renstra_program_outcome.outcome')
            ->where('id_renstra_program',$program->id)->get();
            $program->Kegiatan=DB::table('renstra_kegiatan')
            ->select('renstra_kegiatan.id','kegiatan.nama_kegiatan')
            ->join('kegiatan','kegiatan.id','=','renstra_kegiatan.id_kegiatan')
            ->whereRaw("renstra_kegiatan.id_renstra_program='$program->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->get();
            foreach ( $program->Kegiatan as $kegiatan ){
                $kegiatan->Output=DB::table('renstra_kegiatan_output')
                ->select('renstra_kegiatan_output.output')
                ->where('id_renstra_kegiatan',$kegiatan->id)->get();
                $kegiatan->SubKegiatan=DB::table('renstra_sub_kegiatan')
                ->select('renstra_sub_kegiatan.id','dpa.is_non_urusan','sub_kegiatan.nama_sub_kegiatan','tolak_ukur.tolak_ukur','tolak_ukur.satuan','dpa.id_sub_kegiatan')
                ->join('sub_kegiatan','sub_kegiatan.id','=','renstra_sub_kegiatan.id_sub_kegiatan')
                ->join('dpa','dpa.id_sub_kegiatan','=','sub_kegiatan.id')
                ->join('unit_kerja','unit_kerja.id','=','renstra_sub_kegiatan.id_unit_kerja')
                ->join('tolak_ukur','dpa.id','=','tolak_ukur.id_dpa')
                ->whereRaw("renstra_sub_kegiatan.id_renstra_kegiatan='$kegiatan->id' AND dpa.tahun='$tahun' AND dpa.id_unit_kerja=".Auth::user()->id_unit_kerja)
                ->get();

                foreach($kegiatan->SubKegiatan as $sub_kegiatan){
                    $id_dpa=DB::table('dpa')
                    ->select('id')
                    ->whereRaw("id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->first()->id;
                    $sub_kegiatan->target0=DB::table('renstra_sub_kegiatan_target')
                    ->select('renstra_sub_kegiatan_target.volume')
                    ->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun = '$tahun_sebelum')")->first();
                    $sub_kegiatan->target1=DB::table('renstra_sub_kegiatan_target')
                    ->select('renstra_sub_kegiatan_target.volume')
                    ->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun = '$tahun')")->first();
                    $sub_kegiatan->target2=DB::table('renstra_sub_kegiatan_target')
                    ->select('renstra_sub_kegiatan_target.volume')
                    ->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun = '$tahun_setelah')")->first();
                    $sub_kegiatan->realisasi0=DB::table('realisasi')
                    ->select('realisasi.realisasi_kinerja)')
                    ->whereRaw("id_dpa='$id_dpa' AND (tahun = '$tahun_sebelum')")->sum('realisasi.realisasi_kinerja');
                    
                    
                }
                
            }
        }
        

        if ($type == 'export' || $type == 'excel') {
            return $this->export_ikk_($data, $tahun, $tahun_sebelum, $dinas, $type);
        }else{
          return $this->datatable_iki($data);
        }

    }

    public function datatable_iki($data){
        
        $result = [];
        foreach ( $data as $program ){
            if ($program->id_program!=166){
                foreach ($program->Outcome as $dt ){
                    $outcome="";
                    $outcome.=$dt->outcome." \n";
                    $result[] = [
                        'indikator' => $outcome,
                        'target0' => '',
                        'target1' =>  '',
                        'target2' =>  '',
                        'realisasi' =>  '',
                    ];
                    foreach ( $program->Kegiatan->sortBy('kode_kegiatan') as $kegiatan ){
                        $output="";
                        foreach ($kegiatan->Output as $dt ){
                            $output.=$dt->output."\n";
                        }

                        $result[] = [
                            'indikator' => $output,
                            'target0' => '',
                            'target1' =>  '',
                            'target2' =>  '',
                            'realisasi' =>  '',
                        ];

                        foreach ( $kegiatan->SubKegiatan->sortBy('kode_sub_kegiatan') as $sub_kegiatan ){
                            $indikator="";
                            $indikator=$sub_kegiatan->tolak_ukur;
                            $satuan='';
                            $satuan=$sub_kegiatan->satuan;
                            $result[] = [
                                'indikator' => $indikator,
                                'target0' => $sub_kegiatan->target0->volume.' '.$satuan,
                                'target1' =>  $sub_kegiatan->target1->volume.' '.$satuan,
                                'target2' =>  $sub_kegiatan->target2->volume.' '.$satuan,
                                'realisasi' =>  $sub_kegiatan->realisasi0.' '.$satuan,
                            ];
                        }
                    }
                }
            }
        }

        return response()->json([
            'type' => 'success',
            'status' => true,
            'data' => $result,
        ]);
    }

    public function export_ikk_($data, $tahun, $tahun_sebelum,$dinas, $type){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Indikator Kinerja Kunci ' . $dinas . '')
            ->setSubject('Indikator Kinerja Kunci ' . $dinas . '')
            ->setDescription('Indikator Kinerja Kunci ' . $dinas . '')
            ->setKeywords('pdf php')
            ->setCategory('Indikator Kinerja Kunci');
        $sheet = $spreadsheet->getActiveSheet();
        //$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $sheet->getRowDimension(5)->setRowHeight(25);
        $sheet->getRowDimension(1)->setRowHeight(17);
        $sheet->getRowDimension(2)->setRowHeight(17);
        $sheet->getRowDimension(3)->setRowHeight(17);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        //Margin PDF
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.3);
       
        $sheet->setCellValue('A1', 'INDIKATOR KINERJA KUNCI ')->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'DINAS '.strtoupper($dinas))->mergeCells('A2:F2');
        $sheet->setCellValue('A3', 'KABUPATEN ENREKANG TAHUN ' . session('tahun_penganggaran') . '')->mergeCells('A3:F3');
        $sheet->setCellValue('A4', ' ')->mergeCells('A4:F4');

        
        $sheet->setCellValue('A5', 'No')->mergeCells('A5:A6')->getColumnDimension('A')->setWidth(5);
        $sheet->setCellValue('B5', 'INDIKATOR')->mergeCells('B5:B6')->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C5', 'TARGET')->mergeCells('C5:E5');
        $sheet->setCellValue('F5', 'REALISASI');

        $sheet->setCellValue('C6', ($tahun-1))->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D6', $tahun)->getColumnDimension('D')->setWidth(15);
        $sheet->setCellValue('E6', ($tahun+1))->getColumnDimension('E')->setWidth(15);
        $sheet->setCellValue('F6', ($tahun-1))->getColumnDimension('F')->setWidth(15);

        $sheet->getStyle('A5:F6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F0B6E8');

        $cell=7;
        $no=1;
        //return $data;
        foreach ( $data as $program ){
            if ($program->id_program!=166){
            $count_outcome=$cell+$program->Outcome->count();
            $sheet->setCellValue('B' . $cell, $count_outcome);
            foreach ($program->Outcome as $dt ){
                $outcome="";
                $outcome.=$dt->outcome." \n";
                $sheet->setCellValue('A' . $cell, $no++);
                $sheet->setCellValue('B' . $cell, $outcome);
                //$sheet->getStyle('A' . $cell . ':F' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F0B6E8');
                $cell++;
            }
            foreach ( $program->Kegiatan->sortBy('kode_kegiatan') as $kegiatan ){
                $output="";
                foreach ($kegiatan->Output as $dt ){
                    $output.=$dt->output."\n";
                }
                $sheet->setCellValue('A' . $cell, $no++);
                $sheet->setCellValue('B' . $cell, $output);
                //$sheet->getStyle('A' . $cell . ':F' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CC99FF');
                $cell++;
                foreach ( $kegiatan->SubKegiatan->sortBy('kode_sub_kegiatan') as $sub_kegiatan ){
                    $indikator="";
                    $indikator=$sub_kegiatan->tolak_ukur;
                    $satuan='';
                    $satuan=$sub_kegiatan->satuan;
                    $sheet->setCellValue('A' . $cell, $no++);
                    $sheet->setCellValue('B' . $cell, $indikator);
                    $sheet->setCellValue('C' . $cell, $sub_kegiatan->target0->volume.' '.$satuan);
                    $sheet->setCellValue('D' . $cell, $sub_kegiatan->target1->volume.' '.$satuan);
                    $sheet->setCellValue('E' . $cell, $sub_kegiatan->target2->volume.' '.$satuan);
                    $sheet->setCellValue('F' . $cell, $sub_kegiatan->realisasi0.' '.$satuan);
                    $cell++;
                }
            }
        }
        }
        $sheet->getStyle('C7:C' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('d1d1d1');
        $sheet->getStyle('F7:F' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('d1d1d1');
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];
        $sheet->getStyle('A5:F'. $cell )->applyFromArray($border);
        $sheet->getStyle('A7:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B7:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:F6')->getFont()->setBold(true);
        $sheet->getStyle('A1:F6')->getAlignment()->setVertical('center')->setHorizontal('center');
        $cell++;

        if ($type == 'excel') {
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="IKK ' . $dinas . '.xlsx"');
        }else{
            $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':F' . $cell);
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
        }
            

            $writer->save('php://output');
            exit;

    }
}


