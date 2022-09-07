<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\unitKerja;
use App\Models\bidangUrusan;
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
        
        return $this->{$fungsi}($data);
    }

    public function html_render_renstra($data){

                $html = '';

                $html .= '<h4 style="text-align:center; line-height: 15pt;">BERITA ACARA <br> HASIL VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENEGAH DESA <br> (RPJMDes) <br> DESA '.strtoupper($data->unit_kerja).' KABUPATEN ENREKANG PERIODE '.$data->periode_awal.' - '.$data->periode_akhir.'<hr></h4>';

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
                            Tallung Tondok Periode 2019 - 2024 melalui portal
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
                <td style="width:50%; text-align:center;">Verifikator RPJM Desa<br></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align:center;">'.$data->nama_verifikator.'</td>
            </tr>
            
        </table>';

        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'Bookman Old Style'
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

        $html2 = '<h4 style="text-align:center; line-height: 15pt;">FORMULIR VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENENGAH DESA <br> (RPJM DESA) '.strtoupper($data->unit_kerja).' PERIODE '.$data->periode_awal.'-'.$data->periode_akhir.'<hr></h4>';

        $mpdf->WriteHTML($html2);
        //$mpdf->SetFooter('Dokumen ini telah ditandatangani secara elektronik yang diterbitkan oleh Bappelitbangda Enrekang');
        //$mpdf->SetFooter('Halaman {PAGENO} dari {nb} ');


        $mpdf->SetTitle('Berita Acara Hasil Verifikasi RPJMDes '.$data->unit_kerja.'');
        $mpdf->Output();





        return $html;
    }

    public function html_render_renja($data){
         
        $html = '';

        $html .= '<h4 style="text-align:center; line-height: 15pt;">BERITA ACARA <br> HASIL VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENEGAH DESA <br> (RPJMDes) <br> DESA '.strtoupper($data->unit_kerja).' KABUPATEN ENREKANG PERIODE '.$data->periode_awal.' - '.$data->periode_akhir.'<hr></h4>';

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
                    Tallung Tondok Periode 2019 - 2024 melalui portal
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
        <td style="width:50%; text-align:center;">Verifikator RPJM Desa<br></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">'.$data->nama_verifikator.'</td>
    </tr>
    
</table>';

$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'Bookman Old Style'
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

$html2 = '<h4 style="text-align:center; line-height: 15pt;">FORMULIR VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENENGAH DESA <br> (RPJM DESA) '.strtoupper($data->unit_kerja).' PERIODE '.$data->periode_awal.'-'.$data->periode_akhir.'<hr></h4>';

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
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN AWAL RENCANA STRATEGIS (RENSTRA)')->mergeCells('A2:G2');
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


