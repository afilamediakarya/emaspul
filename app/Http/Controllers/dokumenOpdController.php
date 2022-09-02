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
        $fungsi = 'konsederan_'.$jenis;
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
        $sheet->setCellValue('A29', ' 
        




        



        
        ')->mergeCells('A29:G29');
        

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

    public function datatable_iki(){
        
        $tahun = session('tahun_penganggaran');
        $type = request('type');
        $triwulan=2;
        $tahun_sebelum=$tahun-1;
        $tahun_sebelum_sebelumnya=$tahun_sebelum-1;

        $data=DB::table('renstra_sub_kegiatan')->select('sasaran.*')->join('sasaran','sasaran.id','=','renstra_sub_kegiatan.id_sasaran')->whereRaw("renstra_sub_kegiatan.id_sasaran<>'' AND renstra_sub_kegiatan.id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_sub_kegiatan.id_sasaran')->get();

        foreach ( $data as $dt ){
            $dt->nama_unit_kerja='';
            $dt->Urusan=DB::table('renstra_program')->select('urusan.*')->join('urusan','urusan.id','=','renstra_program.id_urusan')->whereRaw("renstra_program.id_sasaran='$dt->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_program.id_urusan')->get();
            foreach($dt->Urusan as $urusan){

                $cek=DB::table('renstra_program')->where('id_urusan',$urusan->id)->count();
                $urusan->BidangUrusan=DB::table('renstra_program')->select('bidang_urusan.*')->join('bidang_urusan','bidang_urusan.id','=','renstra_program.id_bidang_urusan')->whereRaw("renstra_program.id_sasaran='$dt->id' AND renstra_program.id_urusan='$urusan->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_program.id_bidang_urusan')->get();
                foreach($urusan->BidangUrusan as $bidang_urusan){
                    $bidang_urusan->Program=DB::table('renstra_program')
                    ->select('renstra_program.*','program.kode_program','program.nama_program')
                    ->join('program','program.id','=','renstra_program.id_program')->whereRaw("renstra_program.id_sasaran='$dt->id' AND renstra_program.id_urusan='$urusan->id'AND id_unit_kerja=".Auth::user()->id_unit_kerja)->get();
                    foreach ( $bidang_urusan->Program as $program ){
                        $program->Outcome=DB::table('renstra_program_outcome')->where('id_renstra_program',$program->id)->get();
                        foreach ($program->Outcome as $outcame) {
                            $result[] = [
                                'indikator' => $outcame->outcome,
                                'target' => '',
                                'realisasi' => '',
                                'target_tahun_sebelum' => '',
                                'target_tahun_sebelum_sebelunya' =>  '',
                                'realisasi_tahun_sebelum' => '',
                                'realisasi_tahub_sebelum_sebelumnya' => ''
                            ];
                        }

                       
                        $program->Kegiatan=DB::table('renstra_kegiatan')
                        ->select('renstra_kegiatan.*','kegiatan.kode_kegiatan','kegiatan.nama_kegiatan')
                        ->join('kegiatan','kegiatan.id','=','renstra_kegiatan.id_kegiatan')->whereRaw("renstra_kegiatan.id_renstra_program='$program->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->get();

                        foreach ( $program->Kegiatan as $kegiatan ){
                            
                            $kegiatan->Output=DB::table('renstra_kegiatan_output')->where('id_renstra_kegiatan',$kegiatan->id)->get();
                            $kegiatan->SubKegiatan=DB::table('renstra_sub_kegiatan')
                            ->select('renstra_sub_kegiatan.*','unit_kerja.nama_unit_kerja','dpa.is_non_urusan','sub_kegiatan.kode_sub_kegiatan','sub_kegiatan.nama_sub_kegiatan')
                            ->join('sub_kegiatan','sub_kegiatan.id','=','renstra_sub_kegiatan.id_sub_kegiatan')
                            ->join('dpa','dpa.id_sub_kegiatan','=','sub_kegiatan.id')
                            ->join('unit_kerja','unit_kerja.id','=','renstra_sub_kegiatan.id_unit_kerja')
                          
                            ->whereRaw("renstra_sub_kegiatan.id_renstra_kegiatan='$kegiatan->id' AND renstra_sub_kegiatan.id_urusan='$urusan->id' AND renstra_sub_kegiatan.id_program='$program->id_program' AND renstra_sub_kegiatan.id_kegiatan='$kegiatan->id_kegiatan' AND dpa.tahun='$tahun' AND dpa.id_unit_kerja=".Auth::user()->id_unit_kerja)
                            ->get();
                            $kegiatan->totPersenK=0;
                            $kegiatan->totPersenTargetK=0;
                            $kegiatan->KegiatanTotalRealisasiK=0;
                            $kegiatan->KegiatanTotalRealisasiRp=0;
                            $kegiatan->KegiatanTotalTargetKinerjaK=0;
                            $kegiatan->KegiatanTotalTargetKinerjaRp=0;
                            for($p=1;$p<=$triwulan;$p++){
                            $kegiatan->TotalRealisasiKinerjaK[$p]=0;
                            $kegiatan->TotalPersenRealisasiKinerjaK[$p]=0;
                            $kegiatan->TotalPersenRealisasiKinerjaRenstra[$p]=0;
                            $kegiatan->TotalRealisasiKinerjaRp[$p]=0;
                            }

                            $output="";
                            $volume='';
                            $satuan='';
                            foreach ($kegiatan->Output as $dt ){
                                $volume.=$dt->volume;
                                $satuan.=$dt->satuan;
                                $output.=$dt->output."\n";
                            }

                            $result[] = [
                                'indikator' => $output,
                                'target' => '',
                                'realisasi' => '',
                                'target_tahun_sebelum' => '',
                                'target_tahun_sebelum_sebelunya' =>  '',
                                'realisasi_tahun_sebelum' => '',
                                'realisasi_tahub_sebelum_sebelumnya' => ''
                            ];

                            foreach($kegiatan->SubKegiatan as $sub_kegiatan){

                            
                                $dt->nama_unit_kerja=$sub_kegiatan->nama_unit_kerja;
                                $id_dpa=DB::table('dpa')->whereRaw("id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->first()->id;
                                $sub_kegiatan->Indikator=DB::table('renstra_sub_kegiatan_indikator')->where('id_renstra_sub_kegiatan',$sub_kegiatan->id)->get();
                                $indikator="";
                             
                               
                                $sub_kegiatan->Realisasi0=DB::table('renstra_realisasi_sub_kegiatan')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun <= '$tahun_sebelum')")->get();
                                // $sub_kegiatan->Realisasi1K=DB::table('realisasi')->whereRaw("id_dpa='$id_dpa' AND tahun='$tahun_sebelum' AND periode<=4")->unique('periode')->reduce(function($total,$value){return $total + optional($value)->realisasi_kinerja;})->get();
                                $sub_kegiatan->Realisasi1K=0;
                                $sub_kegiatan->Realisasi1Rp=0;
                                $getRealisasi=DB::table('realisasi')->selectRaw("distinct(periode),realisasi_kinerja,realisasi_keuangan")->whereRaw("id_dpa='$id_dpa' AND tahun='$tahun_sebelum' AND periode<=4")->get();
                                foreach($getRealisasi as $ds){
                                    $sub_kegiatan->Realisasi1K+=$ds->realisasi_kinerja;
                                    $sub_kegiatan->Realisasi1Rp+=$ds->realisasi_keuangan;
                                }
                                $sub_kegiatan->RealisasiK=$sub_kegiatan->Realisasi0->sum('volume')+$sub_kegiatan->Realisasi1K;


                                $sub_kegiatan->RealisasiRp=$sub_kegiatan->Realisasi0->sum('realisasi_keuangan')+$sub_kegiatan->Realisasi1Rp;
                                $sub_kegiatan->TargetK=DB::table('renstra_sub_kegiatan_target')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND tahun='$tahun'")->sum('volume');


                                $sub_kegiatan->TargetRp=DB::table('dpa')->whereRaw("dpa.id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND dpa.tahun='$tahun' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->sum('dpa.nilai_pagu_dpa');
                                
                                $kegiatan->totPersenK+=$sub_kegiatan->total_volume == 0 ? 0 : (($sub_kegiatan->RealisasiK)/$sub_kegiatan->total_volume)*100;
                                $kegiatan->totPersenTargetK+=$sub_kegiatan->TargetK == 0 ? 0 : (($sub_kegiatan->TargetK)/$sub_kegiatan->TargetK)*100;

                                $totRealisasiKinerjaK=0;
                                $totRealisasiKinerjaRp=0;
                                for($p=1;$p<=$triwulan;$p++){
                                    $realisasi=DB::table('dpa')->join('realisasi','realisasi.id_dpa','=','dpa.id')->whereRaw("dpa.id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND realisasi.periode='$p' AND realisasi.tahun='$tahun' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->limit(1);

                              

                                    // if($realisasi->count()>0){
                                    //     $sub_kegiatan->RealisasiKinerjaRp[$p]=$realisasi->first()->realisasi_keuangan;
                                    //     $sub_kegiatan->RealisasiKinerjaK[$p]=$realisasi->first()->realisasi_kinerja;
                                    // }else{
                                    //     $sub_kegiatan->RealisasiKinerjaRp[$p]=0;
                                    //     $sub_kegiatan->RealisasiKinerjaK[$p]=0;
                                    // }

                                    // $kegiatan->TotalRealisasiKinerjaK[$p]+=$sub_kegiatan->RealisasiKinerjaK[$p];
                                        // $kegiatan->TotalPersenRealisasiKinerjaRenstra[$p]+=$sub_kegiatan->total_volume == 0 ? 0 : (($sub_kegiatan->RealisasiKinerjaK[$p])/$sub_kegiatan->total_volume)*100;


                                    // $totRealisasiKinerjaK+=$sub_kegiatan->RealisasiKinerjaK[$p];
                                    // $totRealisasiKinerjaRp+=$sub_kegiatan->RealisasiKinerjaRp[$p];/ $kegiatan->TotalRealisasiKinerjaRp[$p]+=$sub_kegiatan->RealisasiKinerjaRp[$p];

                                    // $kegiatan->TotalPersenRealisasiKinerjaK[$p]+=$sub_kegiatan->TargetK == 0 ? 0 : (($sub_kegiatan->RealisasiKinerjaK[$p])/$sub_kegiatan->TargetK)*100;
                                    
                               
                                }

                                    // $kegiatan->KegiatanTotalRealisasiK+=$sub_kegiatan->RealisasiK;
                                    // $kegiatan->KegiatanTotalRealisasiRp+=$sub_kegiatan->RealisasiRp;
                            
                                
                              
                                    // $kegiatan->KegiatanTotalTargetKinerjaK+=$sub_kegiatan->TargetK;
                                    // $kegiatan->KegiatanTotalTargetKinerjaRp+=$sub_kegiatan->TargetRp;
                            

                                $sub_kegiatan->K13=$totRealisasiKinerjaK;
                                $sub_kegiatan->Rp13=$totRealisasiKinerjaRp;
                                
                                $sub_kegiatan->K14=$sub_kegiatan->RealisasiK+$sub_kegiatan->K13;

                               

                                foreach ($sub_kegiatan->Indikator as $in_indikator => $dt ){
                                    $indikator.=$dt->indikator." (".$dt->satuan.") \n";
                                    $result[] = [
                                        'indikator' => $indikator,
                                        'target' => $sub_kegiatan->total_volume,
                                        'realisasi' => $sub_kegiatan->K14,
                                        'target_tahun_sebelum' =>  DB::table('renstra_sub_kegiatan_target')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND tahun='$tahun_sebelum'")->sum('volume'),
                                        'target_tahun_sebelum_sebelunya' =>  DB::table('renstra_sub_kegiatan_target')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND tahun='$tahun_sebelum_sebelumnya'")->sum('volume'),
                                        'realisasi_tahun_sebelum' => $sub_kegiatan->Realisasi0->sum('volume'),
                                        'realisasi_tahub_sebelum_sebelumnya' => DB::table('renstra_realisasi_sub_kegiatan')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun <= '$tahun_sebelum_sebelumnya')")->get()->sum('volume')
                                    ];
                                    
                                }
                            }
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

    public function export_ikk(){
        $tahun = session('tahun_penganggaran');
        $type = request('type');
        $triwulan=2;
        $tahun_sebelum=$tahun-1;
        $tahun_sebelum_sebelumnya=$tahun_sebelum-1;
        $dinas = unitKerja::find(Auth::user()->id_unit_kerja)->nama_unit_kerja;

        $data=DB::table('renstra_sub_kegiatan')->select('sasaran.*')->join('sasaran','sasaran.id','=','renstra_sub_kegiatan.id_sasaran')->whereRaw("renstra_sub_kegiatan.id_sasaran<>'' AND renstra_sub_kegiatan.id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_sub_kegiatan.id_sasaran')->get();

        foreach ( $data as $dt ){
            $dt->nama_unit_kerja='';
            $dt->Urusan=DB::table('renstra_program')->select('urusan.*')->join('urusan','urusan.id','=','renstra_program.id_urusan')->whereRaw("renstra_program.id_sasaran='$dt->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_program.id_urusan')->get();
            foreach($dt->Urusan as $urusan){

                $cek=DB::table('renstra_program')->where('id_urusan',$urusan->id)->count();
                $urusan->BidangUrusan=DB::table('renstra_program')->select('bidang_urusan.*')->join('bidang_urusan','bidang_urusan.id','=','renstra_program.id_bidang_urusan')->whereRaw("renstra_program.id_sasaran='$dt->id' AND renstra_program.id_urusan='$urusan->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->groupBy('renstra_program.id_bidang_urusan')->get();
                foreach($urusan->BidangUrusan as $bidang_urusan){
                    $bidang_urusan->Program=DB::table('renstra_program')
                    ->select('renstra_program.*','program.kode_program','program.nama_program')
                    ->join('program','program.id','=','renstra_program.id_program')->whereRaw("renstra_program.id_sasaran='$dt->id' AND renstra_program.id_urusan='$urusan->id'AND id_unit_kerja=".Auth::user()->id_unit_kerja)->get();
                    foreach ( $bidang_urusan->Program as $program ){
                        $program->Outcome=DB::table('renstra_program_outcome')->where('id_renstra_program',$program->id)->get();
                       
                        $program->Kegiatan=DB::table('renstra_kegiatan')
                        ->select('renstra_kegiatan.*','kegiatan.kode_kegiatan','kegiatan.nama_kegiatan')
                        ->join('kegiatan','kegiatan.id','=','renstra_kegiatan.id_kegiatan')->whereRaw("renstra_kegiatan.id_renstra_program='$program->id' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->get();

                        foreach ( $program->Kegiatan as $kegiatan ){
                            
                            $kegiatan->Output=DB::table('renstra_kegiatan_output')->where('id_renstra_kegiatan',$kegiatan->id)->get();
                            $kegiatan->SubKegiatan=DB::table('renstra_sub_kegiatan')
                            ->select('renstra_sub_kegiatan.*','unit_kerja.nama_unit_kerja','dpa.is_non_urusan','sub_kegiatan.kode_sub_kegiatan','sub_kegiatan.nama_sub_kegiatan')
                            ->join('sub_kegiatan','sub_kegiatan.id','=','renstra_sub_kegiatan.id_sub_kegiatan')
                            ->join('dpa','dpa.id_sub_kegiatan','=','sub_kegiatan.id')
                            ->join('unit_kerja','unit_kerja.id','=','renstra_sub_kegiatan.id_unit_kerja')
                          
                            ->whereRaw("renstra_sub_kegiatan.id_renstra_kegiatan='$kegiatan->id' AND renstra_sub_kegiatan.id_urusan='$urusan->id' AND renstra_sub_kegiatan.id_program='$program->id_program' AND renstra_sub_kegiatan.id_kegiatan='$kegiatan->id_kegiatan' AND dpa.tahun='$tahun' AND dpa.id_unit_kerja=".Auth::user()->id_unit_kerja)
                            ->get();
                            $kegiatan->totPersenK=0;
                            $kegiatan->totPersenTargetK=0;
                            $kegiatan->KegiatanTotalRealisasiK=0;
                            $kegiatan->KegiatanTotalRealisasiRp=0;
                            $kegiatan->KegiatanTotalTargetKinerjaK=0;
                            $kegiatan->KegiatanTotalTargetKinerjaRp=0;
                            for($p=1;$p<=$triwulan;$p++){
                            $kegiatan->TotalRealisasiKinerjaK[$p]=0;
                            $kegiatan->TotalPersenRealisasiKinerjaK[$p]=0;
                            $kegiatan->TotalPersenRealisasiKinerjaRenstra[$p]=0;
                            $kegiatan->TotalRealisasiKinerjaRp[$p]=0;
                            }

                            $output="";
                            $volume='';
                            $satuan='';
                            foreach ($kegiatan->Output as $dt ){
                                $volume.=$dt->volume;
                                $satuan.=$dt->satuan;
                                $output.=$dt->output."\n";
                            }


                            foreach($kegiatan->SubKegiatan as $sub_kegiatan){

                            
                                $dt->nama_unit_kerja=$sub_kegiatan->nama_unit_kerja;
                                $id_dpa=DB::table('dpa')->whereRaw("id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->first()->id;
                                $sub_kegiatan->Indikator=DB::table('renstra_sub_kegiatan_indikator')->where('id_renstra_sub_kegiatan',$sub_kegiatan->id)->get();
                                $indikator="";
                             
                               
                                $sub_kegiatan->Realisasi0=DB::table('renstra_realisasi_sub_kegiatan')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND (tahun <= '$tahun_sebelum')")->get();
                               
                                $sub_kegiatan->Realisasi1K=0;
                                $sub_kegiatan->Realisasi1Rp=0;
                                $getRealisasi=DB::table('realisasi')->selectRaw("distinct(periode),realisasi_kinerja,realisasi_keuangan")->whereRaw("id_dpa='$id_dpa' AND tahun='$tahun_sebelum' AND periode<=4")->get();
                                foreach($getRealisasi as $ds){
                                    $sub_kegiatan->Realisasi1K+=$ds->realisasi_kinerja;
                                    $sub_kegiatan->Realisasi1Rp+=$ds->realisasi_keuangan;
                                }
                                $sub_kegiatan->RealisasiK=$sub_kegiatan->Realisasi0->sum('volume')+$sub_kegiatan->Realisasi1K;


                                $sub_kegiatan->RealisasiRp=$sub_kegiatan->Realisasi0->sum('realisasi_keuangan')+$sub_kegiatan->Realisasi1Rp;
                                $sub_kegiatan->TargetK=DB::table('renstra_sub_kegiatan_target')->whereRaw("id_renstra_sub_kegiatan='$sub_kegiatan->id' AND tahun='$tahun'")->sum('volume');


                                $sub_kegiatan->TargetRp=DB::table('dpa')->whereRaw("dpa.id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND dpa.tahun='$tahun' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->sum('dpa.nilai_pagu_dpa');
                                
                                $kegiatan->totPersenK+=$sub_kegiatan->total_volume == 0 ? 0 : (($sub_kegiatan->RealisasiK)/$sub_kegiatan->total_volume)*100;
                                $kegiatan->totPersenTargetK+=$sub_kegiatan->TargetK == 0 ? 0 : (($sub_kegiatan->TargetK)/$sub_kegiatan->TargetK)*100;

                                $totRealisasiKinerjaK=0;
                                $totRealisasiKinerjaRp=0;
                                for($p=1;$p<=$triwulan;$p++){
                                    $realisasi=DB::table('dpa')->join('realisasi','realisasi.id_dpa','=','dpa.id')->whereRaw("dpa.id_sub_kegiatan='$sub_kegiatan->id_sub_kegiatan' AND realisasi.periode='$p' AND realisasi.tahun='$tahun' AND id_unit_kerja=".Auth::user()->id_unit_kerja)->limit(1);
                                    if($realisasi->count()>0){
                                        $sub_kegiatan->RealisasiKinerjaRp[$p]=$realisasi->first()->realisasi_keuangan;
                                        $sub_kegiatan->RealisasiKinerjaK[$p]=$realisasi->first()->realisasi_kinerja;
                                    }else{
                                        $sub_kegiatan->RealisasiKinerjaRp[$p]=0;
                                        $sub_kegiatan->RealisasiKinerjaK[$p]=0;
                                    }

                                    $kegiatan->TotalRealisasiKinerjaK[$p]+=$sub_kegiatan->RealisasiKinerjaK[$p];
                                    $kegiatan->TotalRealisasiKinerjaRp[$p]+=$sub_kegiatan->RealisasiKinerjaRp[$p];

                                    $kegiatan->TotalPersenRealisasiKinerjaK[$p]+=$sub_kegiatan->total_volume == 0 ? 0 : (($sub_kegiatan->RealisasiKinerjaK[$p])/$sub_kegiatan->total_volume)*100;

                                    $totRealisasiKinerjaK+=$sub_kegiatan->RealisasiKinerjaK[$p];
                                    $totRealisasiKinerjaRp+=$sub_kegiatan->RealisasiKinerjaRp[$p];
                                }

                                $kegiatan->KegiatanTotalRealisasiK+=$sub_kegiatan->RealisasiK;
                                $kegiatan->KegiatanTotalRealisasiRp+=$sub_kegiatan->RealisasiRp;
                        
                            
                            // if ($sub_kegiatan->Target->count()>0){
                                $kegiatan->KegiatanTotalTargetKinerjaK+=$sub_kegiatan->TargetK;
                                $kegiatan->KegiatanTotalTargetKinerjaRp+=$sub_kegiatan->TargetRp;
                            // }

                            $sub_kegiatan->K13=$totRealisasiKinerjaK;
                            $sub_kegiatan->Rp13=$totRealisasiKinerjaRp;
                            
                            $sub_kegiatan->K14=$sub_kegiatan->RealisasiK+$sub_kegiatan->K13;
                            $sub_kegiatan->Rp14=$sub_kegiatan->Rp13+$sub_kegiatan->RealisasiRp;
                            $sub_kegiatan->K15=$sub_kegiatan->total_volume==0?0:(($sub_kegiatan->K14/$sub_kegiatan->total_volume)*100);
                            $sub_kegiatan->Rp15=$sub_kegiatan->total_pagu_renstra==0?0:(($sub_kegiatan->Rp14/$sub_kegiatan->total_pagu_renstra)*100);

                            }

                        }
                    }
                }
            }
        }

        foreach ( $data as $dt ){
            foreach($dt->Urusan as $urusan){
                foreach($urusan->BidangUrusan as $bidang_urusan){
                   foreach ( $bidang_urusan->Program as $program ){
                        $program->KegiatanTotalTargetK=0;
                        $program->KegiatanTotalTargetRp=0;
                        $program->KegiatanTotalRealisasiK=0;
                        $program->KegiatanTotalRealisasiRp=0;
                        $program->KegiatanTotalTargetKinerjaK=0;
                        $program->KegiatanTotalTargetKinerjaRp=0;
                        for($p=1;$p<=$triwulan;$p++){
                        $program->TotalRealisasiKinerjaK[$p]=0;
                        $program->TotalRealisasiKinerjaRp[$p]=0;
                        }


                        foreach ( $program->Kegiatan as $kegiatan ){
                            $sub_kegiatan_count=$kegiatan->SubKegiatan->count();
                            $kegiatan->TargetK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->SubKegiatan->sum('total_volume'))/$sub_kegiatan_count);
                            $kegiatan->TargetRp=$kegiatan->SubKegiatan->sum('total_pagu_renstra');
                            // $kegiatan->RealisasiK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->KegiatanTotalRealisasiK)/$sub_kegiatan_count);
                            $kegiatan->RealisasiK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->totPersenK)/$sub_kegiatan_count);
                            $kegiatan->RealisasiRp=$kegiatan->KegiatanTotalRealisasiRp;
                            // $kegiatan->TargetKinerjaK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->KegiatanTotalTargetKinerjaK)/$sub_kegiatan_count);
                            $kegiatan->TargetKinerjaK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->totPersenTargetK)/$sub_kegiatan_count);
                            $kegiatan->TargetKinerjaRp=$kegiatan->KegiatanTotalTargetKinerjaRp;
                            
                            

                            $program->KegiatanTotalTargetK+=$kegiatan->TargetK;
                            $program->KegiatanTotalTargetRp+=$kegiatan->TargetRp;
                            $program->KegiatanTotalRealisasiK+=$kegiatan->RealisasiK;
                            $program->KegiatanTotalRealisasiRp+=$kegiatan->RealisasiRp;
                            $program->KegiatanTotalTargetKinerjaK+=$kegiatan->TargetKinerjaK;
                            $program->KegiatanTotalTargetKinerjaRp+=$kegiatan->TargetKinerjaRp;


                            $totRealisasiKinerjaK=0;
                            $totRealisasiKinerjaRp=0;

                            for($p=1;$p<=$triwulan;$p++){
                                // $kegiatan->RealisasiKinerjaK[$p]=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->TotalRealisasiKinerjaK[$p])/$sub_kegiatan_count);
                                $kegiatan->RealisasiKinerjaK[$p]=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->TotalPersenRealisasiKinerjaK[$p])/$sub_kegiatan_count);
                                $kegiatan->RealisasiKinerjaRp[$p]=$kegiatan->TotalRealisasiKinerjaRp[$p];
                                $program->TotalRealisasiKinerjaK[$p]+=$kegiatan->RealisasiKinerjaK[$p];
                                $program->TotalRealisasiKinerjaRp[$p]+=$kegiatan->RealisasiKinerjaRp[$p];
                                $totRealisasiKinerjaK+=$kegiatan->RealisasiKinerjaK[$p];
                                $totRealisasiKinerjaRp+=$kegiatan->RealisasiKinerjaRp[$p];
                            }

                            $kegiatan->K13=$totRealisasiKinerjaK;
                            $kegiatan->Rp13=$totRealisasiKinerjaRp;

                            $kegiatan->K14=($kegiatan->RealisasiK+$kegiatan->K13);
                            $kegiatan->Rp14=$kegiatan->Rp13+$kegiatan->RealisasiRp;
                            $kegiatan->K15=$kegiatan->Output->first()->volume==0?0:(($kegiatan->K14/$kegiatan->Output->first()->volume)*100);
                            $kegiatan->Rp15=$kegiatan->TargetRp==0?0:(($kegiatan->Rp14/$kegiatan->TargetRp)*100);


                        }
                    }
                }
            }
        }
        
        foreach ( $data as $dt ){
            foreach($dt->Urusan as $urusan){
                foreach($urusan->BidangUrusan as $bidang_urusan){

                   foreach ( $bidang_urusan->Program as $program ){
                       $kegiatan_count=$program->Kegiatan->count();
                        $program->TargetK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalTargetK/$kegiatan_count);
                        $program->TargetRp=$program->KegiatanTotalTargetRp;
                        $program->RealisasiK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalRealisasiK/$kegiatan_count);
                        $program->RealisasiRp=$program->KegiatanTotalRealisasiRp;
                        $program->TargetKinerjaK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalTargetKinerjaK/$kegiatan_count);
                        $program->TargetKinerjaRp=$program->KegiatanTotalTargetKinerjaRp;

                        


                        $totRealisasiKinerjaK=0;
                        $totRealisasiKinerjaRp=0;

                        for($p=1;$p<=$triwulan;$p++){
                            $program->RealisasiKinerjaK[$p]=$kegiatan_count == 0 ? 0 : ($program->TotalRealisasiKinerjaK[$p]/$kegiatan_count);
                            $program->RealisasiKinerjaRp[$p]=$program->TotalRealisasiKinerjaRp[$p];

                            $totRealisasiKinerjaK+=$program->RealisasiKinerjaK[$p];
                            $totRealisasiKinerjaRp+=$program->RealisasiKinerjaRp[$p];
                        }

                        

                        $program->K13=$totRealisasiKinerjaK;
                        $program->Rp13=$totRealisasiKinerjaRp;

                        $program->K14=($program->RealisasiK+($program->TargetKinerjaK*$program->K13))/100;
                        $program->Rp14=$program->Rp13+$program->RealisasiRp;
                        $program->K15=$program->TargetK==0?0:($program->K14/$program->TargetK*100);
                        $program->Rp15=$program->TargetRp==0?0:($program->Rp14/$program->TargetRp*100);

                        
                    }
                }
            }
        }
        
        foreach ( $data as $dt ){
            foreach($dt->Urusan as $urusan){

                foreach($urusan->BidangUrusan as $bidang_urusan){
                    
                    foreach ( $bidang_urusan->Program as $program ){
                        foreach($program->Outcome as $po){
                            $poTotTarget=$program->Kegiatan->where('id_renstra_program_outcome',$po->id);
                            
                            $po->PoTargetRp=$poTotTarget->sum('TargetRp');
                            $po->PoRealisasiK=$poTotTarget->count()==0?0:$poTotTarget->sum('RealisasiK')/$poTotTarget->count();
                            $po->PoRealisasiRp=$poTotTarget->sum('RealisasiRp');
                            $po->PoTargetKinerjaRp=$poTotTarget->sum('TargetKinerjaRp');
                            $po->PoTargetKinerjaK=$poTotTarget->count()==0?0:$poTotTarget->sum('TargetKinerjaK')/$poTotTarget->count();

                            for($p=1;$p<=$triwulan;$p++){
                                $po->PoRealisasiKinerjaK[$p]=$poTotTarget->count()==0?0:$poTotTarget->reduce(function ($sum,$option) use ($p) {
                                    return $sum+=$option->RealisasiKinerjaK[$p];
                                }, 0)/$poTotTarget->count();
                                $po->PoRealisasiKinerjaRp[$p]=$poTotTarget->count()==0?0:$poTotTarget->reduce(function ($sum,$option) use ($p) {
                                    return $sum+=$option->RealisasiKinerjaRp[$p];
                                }, 0);
                            }


                            $po->PoRp13=$poTotTarget->sum('Rp13');
                            $po->PoK13=$poTotTarget->count()==0?0:$poTotTarget->sum('K13')/$poTotTarget->count();
                            $po->PoRp14=$poTotTarget->sum('Rp14');
                            $po->PoK14=$poTotTarget->count()==0?0:$poTotTarget->sum('K14')/$poTotTarget->count();
                            $po->PoRp15=$poTotTarget->count()==0?0:$poTotTarget->sum('Rp15')/$poTotTarget->count();
                            $po->PoK15=$poTotTarget->count()==0?0:$poTotTarget->sum('K15')/$poTotTarget->count();

                        }

                    }

                }
            }
        }
        
        foreach ( $data as $dt ){

            foreach($dt->Urusan as $urusan){


                foreach($urusan->BidangUrusan as $bidang_urusan){
                    $bidang_urusan->Program->reduce(function ($sum,$program) use ($bidang_urusan,$triwulan) {
                        $program_count=$program->Outcome->count();

                        $bidang_urusan->TargetK=$program_count == 0 ? 0 : ($program->Outcome->sum('volume')/$program_count);
                        $bidang_urusan->TargetRp=$program->Outcome->sum('PoTargetRp');
                        $bidang_urusan->RealisasiK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoRealisasiK')/$program_count);
                        $bidang_urusan->RealisasiRp=$program->Outcome->sum('PoRealisasiRp');
                        $bidang_urusan->TargetKinerjaK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoTargetKinerjaK')/$program_count);
                        $bidang_urusan->TargetKinerjaRp=$program->Outcome->sum('PoTargetKinerjaRp');

                        for($p=1;$p<=$triwulan;$p++){
                            $bidang_urusan->RealisasiKinerjaK[$p]=$program_count==0?0:$program->Outcome->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->PoRealisasiKinerjaK[$p];
                            }, 0)/$program_count;
                            $bidang_urusan->RealisasiKinerjaRp[$p]=$program->Outcome->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->PoRealisasiKinerjaRp[$p];
                            }, 0);
                        }

                        $bidang_urusan->TargetKinerjaK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoTargetKinerjaK')/$program_count);
                        $bidang_urusan->TargetKinerjaRp=$program->Outcome->sum('PoTargetKinerjaRp');

                        $bidang_urusan->K13=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK13')/$program_count);
                        $bidang_urusan->Rp13=$program->Outcome->sum('PoRp13');
                        $bidang_urusan->K14=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK14')/$program_count);
                        $bidang_urusan->Rp14=$program->Outcome->sum('PoRp13')+$bidang_urusan->RealisasiRp;
                        $bidang_urusan->K15=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK15')/$program_count);
                        $bidang_urusan->Rp15=$program_count == 0 ? 0 : ($program->Outcome->sum('PoRp15')/$program_count);
                        
                        

                        
                        
                    });

                }
            }
        }
        
        foreach ( $data as $dt ){

                foreach($dt->Urusan as $urusan){

                    $bidang_urusan_count=$urusan->BidangUrusan->count();

                        $urusan->TargetK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetK')/$bidang_urusan_count);
                        $urusan->TargetRp=$urusan->BidangUrusan->sum('TargetRp');
                        $urusan->RealisasiK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('RealisasiK')/$bidang_urusan_count);
                        $urusan->RealisasiRp=$urusan->BidangUrusan->sum('RealisasiRp');
                        $urusan->TargetKinerjaK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetKinerjaK')/$bidang_urusan_count);
                        $urusan->TargetKinerjaRp=$urusan->BidangUrusan->sum('TargetKinerjaRp');

                        for($p=1;$p<=$triwulan;$p++){
                            $urusan->RealisasiKinerjaK[$p]=$bidang_urusan_count==0?0:$urusan->BidangUrusan->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->RealisasiKinerjaK[$p];
                            }, 0)/$bidang_urusan_count;
                            $urusan->RealisasiKinerjaRp[$p]=$urusan->BidangUrusan->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->RealisasiKinerjaRp[$p];
                            }, 0);
                        }

                        $urusan->TargetKinerjaK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetKinerjaK')/$bidang_urusan_count);
                        $urusan->TargetKinerjaRp=$urusan->BidangUrusan->sum('TargetKinerjaRp');

                        $urusan->K13=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K13')/$bidang_urusan_count);
                        $urusan->Rp13=$urusan->BidangUrusan->sum('Rp13');
                        $urusan->K14=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K14')/$bidang_urusan_count);
                        $urusan->Rp14=$urusan->BidangUrusan->sum('Rp13')+$urusan->RealisasiRp;
                        $urusan->K15=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K15')/$bidang_urusan_count);
                        $urusan->Rp15=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('Rp15')/$bidang_urusan_count);

                }
        }

        foreach ( $data as $dt ){
                        
                        $urusan_count=$dt->Urusan->count();

                        $dt->TargetK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetK')/$urusan_count);
                        $dt->TargetRp=$dt->Urusan->sum('TargetRp');
                        $dt->RealisasiK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('RealisasiK')/$urusan_count);
                        $dt->RealisasiRp=$dt->Urusan->sum('RealisasiRp');
                        $dt->TargetKinerjaK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetKinerjaK')/$urusan_count);
                        $dt->TargetKinerjaRp=$dt->Urusan->sum('TargetKinerjaRp');

                        for($p=1;$p<=$triwulan;$p++){
                            $dt->RealisasiKinerjaK[$p]=$urusan_count==0?0:$dt->Urusan->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->RealisasiKinerjaK[$p];
                            }, 0)/$urusan_count;
                            $dt->RealisasiKinerjaRp[$p]=$dt->Urusan->reduce(function ($sum,$option) use ($p) {
                                return $sum+=$option->RealisasiKinerjaRp[$p];
                            }, 0);
                        }

                        $dt->TargetKinerjaK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetKinerjaK')/$urusan_count);
                        $dt->TargetKinerjaRp=$dt->Urusan->sum('TargetKinerjaRp');

                        $dt->K13=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K13')/$urusan_count);
                        $dt->Rp13=$dt->Urusan->sum('Rp13');
                        $dt->K14=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K14')/$urusan_count);
                        $dt->Rp14=$dt->Urusan->sum('Rp13')+$dt->RealisasiRp;
                        $dt->K15=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K15')/$urusan_count);
                        $dt->Rp15=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('Rp15')/$urusan_count);
        }

        return $this->export_ikk_($data, $tahun, $tahun_sebelum, $dinas);

    }

    public function export_ikk_($data, $tahun, $tahun_sebelum,$dinas){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI RENJA ' . $dinas . '')
            ->setSubject('EVALUASI RENJA ' . $dinas . '')
            ->setDescription('EVALUASI RENJA ' . $dinas . '')
            ->setKeywords('pdf php')
            ->setCategory('KEMAJUAN');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $sheet->getRowDimension(5)->setRowHeight(25);
        $sheet->getRowDimension(1)->setRowHeight(17);
        $sheet->getRowDimension(2)->setRowHeight(17);
        $sheet->getRowDimension(3)->setRowHeight(17);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        //Margin PDF
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.3);
       
        $sheet->setCellValue('A1', 'LAPORAN EVALUASI TERHADAP RKPD');
        $sheet->setCellValue('A2', 'KABUPATEN ENREKANG TAHUN ANGGARAN ' . session('tahun_penganggaran') . '');
        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('A4', ' ');

        $sheet->mergeCells('A1:AA1');
        $sheet->mergeCells('A2:AA2');
        $sheet->mergeCells('A3:AA3');
        $sheet->getStyle('A1')->getFont()->setSize(12);

        $sheet->setCellValue('A5', 'No')->mergeCells('A5:A6')->getColumnDimension('A')->setWidth(5,31);;
        $sheet->setCellValue('B5', 'Sasaran')->mergeCells('B5:B6')->getColumnDimension('B')->setWidth(13,16);;
        $sheet->setCellValue('C5', 'Kode')->mergeCells('C5:C6')->getColumnDimension('C')->setWidth(11,98);;
        $sheet->setCellValue('D5', 'Urusan/Bidang Urusan Pemerintah Daerah dan Program/Kegiatan/Sub Kegiatan')->mergeCells('D5:D6')->getColumnDimension('D')->setWidth(15,94);;
        $sheet->setCellValue('E5', 'Indikator Kinerja Program (outcome) / Kegiatan (output)')->mergeCells('E5:E6')->getColumnDimension('E')->setWidth(14,85);;
        $sheet->setCellValue('F5', 'Target Renstra OPD pada Tahun 2019 s/d 2023 (akhir periode Renstra OPD)')->mergeCells('F5:G6');
        $sheet->setCellValue('H5', 'Realisasi Capaian Kinerja Renstra OPD s/d Renja OPD Tahun lalu ('.$tahun_sebelum.')')->mergeCells('H5:I6');
        $sheet->setCellValue('J5', 'Target Kinerja dan Anggaran Renja OPD Tahun berjalan yang dievaluasi ('.$tahun.')')->mergeCells('J5:K6');
        $sheet->setCellValue('L5', 'Realisasi Kinerja Pada Triwulan')->mergeCells('L5:S5');
        
        $sheet->setCellValue('T5', 'Realisasi Capaian Kinerja dan Anggaran Renja OPD yang Dievaluasi')->mergeCells('T5:U6');
        $sheet->setCellValue('V5', 'Realisasi kinerja dan Anggaran Renstra OPD s/d tahun '.$tahun.' (Ahkir Tahun pelaksanaan Renja OPD)')->mergeCells('V5:W6');
        $sheet->setCellValue('X5', 'Tingkat Capaian Kinerja dan Realisasi Anggaran Renstra OPD s/d tahun '.$tahun.' (%)')->mergeCells('X5:Y6');
        $sheet->setCellValue('Z5', 'Unit OPD penanggung jawab')->mergeCells('Z5:Z7')->getColumnDimension('Z')->setWidth(17,63);;
        $sheet->setCellValue('AA5', 'Ket')->mergeCells('AA5:AA7')->getColumnDimension('AA')->setWidth(5,31);;

        $sheet->setCellValue('L6', 'I')->mergeCells('L6:M6');
        $sheet->setCellValue('N6', 'II')->mergeCells('N6:O6');
        $sheet->setCellValue('P6', 'III')->mergeCells('P6:Q6');
        $sheet->setCellValue('R6', 'IV')->mergeCells('R6:S6');

        $sheet->setCellValue('A7', '1')->mergeCells('A7:A8');
        $sheet->setCellValue('B7', '2')->mergeCells('B7:B8');
        $sheet->setCellValue('C7', '3')->mergeCells('C7:C8');
        $sheet->setCellValue('D7', '4')->mergeCells('D7:D8');
        $sheet->setCellValue('E7', '5')->mergeCells('E7:E8');
        $sheet->setCellValue('F7', '6')->mergeCells('F7:G7');
        $sheet->setCellValue('H7', '7')->mergeCells('H7:I7');
        $sheet->setCellValue('J7', '8')->mergeCells('J7:K7');
        $sheet->setCellValue('L7', '9')->mergeCells('L7:M7');
        $sheet->setCellValue('N7', '10')->mergeCells('N7:O7');
        $sheet->setCellValue('P7', '11')->mergeCells('P7:Q7');
        $sheet->setCellValue('R7', '12')->mergeCells('R7:S7');
        $sheet->setCellValue('T7', '13=9+10+11+12')->mergeCells('T7:U7');
        $sheet->setCellValue('V7', '14=7+13')->mergeCells('V7:W7');
        $sheet->setCellValue('X7', '15=14/6x100')->mergeCells('X7:Y7');

        $sheet->setCellValue('F8', 'K')->mergeCells('F8:F8')->getColumnDimension('F')->setWidth(9,36);
        $sheet->setCellValue('G8', 'Rp')->mergeCells('G8:G8')->getColumnDimension('G')->setWidth(14);
        $sheet->setCellValue('H8', 'K')->mergeCells('H8:H8')->getColumnDimension('H')->setWidth(9,36);
        $sheet->setCellValue('I8', 'Rp')->mergeCells('I8:I8')->getColumnDimension('I')->setWidth(13,5);
        $sheet->setCellValue('J8', 'K')->mergeCells('J8:J8')->getColumnDimension('J')->setWidth(9,36);
        $sheet->setCellValue('K8', 'Rp')->mergeCells('K8:K8')->getColumnDimension('K')->setWidth(13,5);
        $sheet->setCellValue('L8', 'K')->mergeCells('L8:L8')->getColumnDimension('L')->setWidth(9,5);
        $sheet->setCellValue('M8', 'Rp')->mergeCells('M8:M8')->getColumnDimension('M')->setWidth(13,5);
        $sheet->setCellValue('N8', 'K')->mergeCells('N8:N8')->getColumnDimension('N')->setWidth(9,36);
        $sheet->setCellValue('O8', 'Rp')->mergeCells('O8:O8')->getColumnDimension('O')->setWidth(13,5);
        $sheet->setCellValue('P8', 'K')->mergeCells('P8:P8')->getColumnDimension('P')->setWidth(9,36);
        $sheet->setCellValue('Q8', 'Rp')->mergeCells('Q8:Q8')->getColumnDimension('Q')->setWidth(13,5);
        $sheet->setCellValue('R8', 'K')->mergeCells('R8:R8')->getColumnDimension('R')->setWidth(9,36);
        $sheet->setCellValue('S8', 'Rp')->mergeCells('S8:S8')->getColumnDimension('S')->setWidth(13,5);
        $sheet->setCellValue('T8', 'K')->mergeCells('T8:T8')->getColumnDimension('T')->setWidth(9,36);
        $sheet->setCellValue('U8', 'Rp')->mergeCells('U8:U8')->getColumnDimension('U')->setWidth(13,5);
        $sheet->setCellValue('V8', 'K')->mergeCells('V8:V8')->getColumnDimension('V')->setWidth(9,36);
        $sheet->setCellValue('W8', 'Rp')->mergeCells('W8:W8')->getColumnDimension('W')->setWidth(13,5);
        $sheet->setCellValue('X8', 'K')->mergeCells('X8:X8')->getColumnDimension('X')->setWidth(9,36);
        $sheet->setCellValue('Y8', 'Rp')->mergeCells('Y8:Y8')->getColumnDimension('Y')->setWidth(9,36);

        $sheet->getStyle('A:AA')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:AA8')->getFont()->setBold(true);
        $sheet->getStyle('A5:AA8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');

        $cell = 9;

        $jumlah_sub_kegiatan = 0;
        $jumlah_persen_kinerja_sub_kegiatan = 0;
        $jumlah_persen_keuangan_sub_kegiatan = 0;
        $rata_rata_capaian_kinerja = 0;
        $rata_rata_capaian_keuangan = 0;
        $jumlah_rp = 0;
        $jumlah_target = 0;
        $triwulan = 2;

        $i=0;

        foreach ( $data as $sasaran ){
        $sheet->setCellValue('A' . $cell, ++$i);
        $sheet->setCellValue('B' . $cell, $sasaran->sasaran);
        $sheet->setCellValue('C' . $cell, '');
        $sheet->setCellValue('D' . $cell, '');
        $sheet->setCellValue('E' . $cell, '');
       
        $kolom='L';
        if ($triwulan==2){
        for ($p=1;$p<=$triwulan;$p++){
     
        }
        $sheet->setCellValue('P' . $cell, '');
        $sheet->setCellValue('Q' . $cell, '');
        $sheet->setCellValue('R' . $cell, '');
        $sheet->setCellValue('S' . $cell, '');
        }else{
        for ($p=1;$p<=$triwulan;$p++){
      
        }
        }


        $sheet->setCellValue('AA' . $cell, '');
        $sheet->getStyle('A' . $cell . ':AA' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
        $cell++;

        $j=0;
        foreach ( $sasaran->Urusan->sortBy('urusan') as $urusan ){
     
            $kolom='L';
            if ($triwulan==2){
            for ($p=1;$p<=$triwulan;$p++){
            
            }
            //$sheet->setCellValue('P' . $cell, '');
            //$sheet->setCellValue('Q' . $cell, '');
            //$sheet->setCellValue('R' . $cell, '');
            //$sheet->setCellValue('S' . $cell, '');
            }else{
            for ($p=1;$p<=$triwulan;$p++){
        
            }
            }
    
    
                $k=0;
                foreach ( $urusan->BidangUrusan->sortBy('kode_bidang_urusan') as $bidang_urusan ){
                
                    $kolom='L';
                    if ($triwulan==2){
                    for ($p=1;$p<=$triwulan;$p++){
                  
                    }
                    }else{
                    for ($p=1;$p<=$triwulan;$p++){
                        
                    }
                    }
        
                    if ($bidang_urusan->Program->count()>0)
                    $l=0;
                    foreach ( $bidang_urusan->Program->sortBy('kode_program') as $program ){
                        
                        if ($urusan->kode_urusan == '00') {
                            $non_urusan = true;
                            $bidang_urusan = optional(optional(auth()->user())->UnitKerja)->bidangUrusan()->with('Urusan')->first();
                            $kode_program = $bidang_urusan->Urusan->kode_urusan . '.' . $bidang_urusan->kode_bidang_urusan . '.' . $program->kode_program;
                        } else {
                            $kode_program = $urusan->kode_urusan . '.' . $bidang_urusan->kode_bidang_urusan . '.' . $program->kode_program;
                        }
                        $count_outcome=$cell+$program->Outcome->count();
                        $sheet->setCellValue('A' . $cell, '')->mergeCells('A'.$cell.':A'.$count_outcome);
                        $sheet->setCellValue('B' . $cell, '')->mergeCells('B'.$cell.':B'.$count_outcome);
                        $sheet->setCellValue('C' . $cell, $kode_program)->mergeCells('C'.$cell.':C'.$count_outcome);
                        $sheet->setCellValue('D' . $cell, $program->nama_program)->mergeCells('D'.$cell.':D'.$count_outcome);
                        $sheet->getStyle('A' . $cell . ':AA' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F0B6E8');
                        $cell++;
                        
                        foreach ($program->Outcome as $dt ){
                          

                            $outcome="";
                            $outcome.=$dt->outcome." \n";
                            $sheet->setCellValue('E' . $cell, $outcome);
                            $sheet->getStyle('E')->getAlignment()->setWrapText(true);
                            $sheet->setCellValue('F' . $cell, number_format($dt->volume,2).''.$dt->satuan);
                          
                            $sheet->getStyle('A' . $cell . ':AA' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F0B6E8');
                            $cell++;
                            
                        }
                        
                
                    
                        if ($program->Kegiatan->count() >0)
                            $m=0;
                        foreach ( $program->Kegiatan->sortBy('kode_kegiatan') as $kegiatan ){
                            if ($urusan->kode_urusan == '00') {
                                $non_urusan = true;
                                $bidang_urusan = optional(optional(auth()->user())->UnitKerja)->bidangUrusan()->with('Urusan')->first();
                                $kode_kegiatan = $bidang_urusan->Urusan->kode_urusan . '.' . $bidang_urusan->kode_bidang_urusan . '.' . $program->kode_program.'.'.$kegiatan->kode_kegiatan;
                            } else {
                                $kode_kegiatan = $urusan->kode_urusan . '.' . $bidang_urusan->kode_bidang_urusan . '.' . $program->kode_program.'.'.$kegiatan->kode_kegiatan;
                            }
                            $sheet->setCellValue('A' . $cell, '');
                            $sheet->setCellValue('B' . $cell, '');
                            $sheet->setCellValue('C' . $cell, $kode_kegiatan);
                            $sheet->setCellValue('D' . $cell, $kegiatan->nama_kegiatan);
                            $output="";
                            $volume='';
                            $satuan='';
                            foreach ($kegiatan->Output as $dt ){
                                $volume.=$dt->volume;
                                $satuan.=$dt->satuan;
                                $output.=$dt->output."\n";
                            }
                            $sheet->setCellValue('E' . $cell, $output);
                            $sheet->getStyle('E')->getAlignment()->setWrapText(true);
                            $sheet->setCellValue('F' . $cell, number_format($volume,2).''.$satuan);
                            $sheet->setCellValue('G' . $cell, number_format($kegiatan->TargetRp,2,',','.'));
                   
                            $sheet->getStyle('A' . $cell . ':AA' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CC99FF');
                            $cell++;
                                $n=0;
                                foreach ( $kegiatan->SubKegiatan->sortBy('kode_sub_kegiatan') as $sub_kegiatan ){
                                    if ($sub_kegiatan->is_non_urusan) {
                                        $bidang_urusan = optional(optional(auth()->user())->UnitKerja)->bidangUrusan()->with('Urusan')->first();
                                        $kode_sub_kegiatan= $bidang_urusan->Urusan->kode_urusan . '.' . $bidang_urusan->kode_bidang_urusan . substr($sub_kegiatan->kode_sub_kegiatan, 4);
                                    }else{
                                        $kode_sub_kegiatan=$sub_kegiatan->kode_sub_kegiatan;
                                    }
                                    
                                    

                                    $sheet->setCellValue('A' . $cell, '');
                                    $sheet->setCellValue('B' . $cell, '');
                                    $sheet->setCellValue('C' . $cell, $kode_sub_kegiatan);
                                    $sheet->setCellValue('D' . $cell, $sub_kegiatan->nama_sub_kegiatan);
                                    $indikator="";
                                    foreach ($sub_kegiatan->Indikator as $dt ){
                                        $indikator.=$dt->indikator." (".$dt->satuan.") \n";
                                    }
                                    $sheet->setCellValue('E' . $cell, $indikator);
                                    $sheet->getStyle('E')->getAlignment()->setWrapText(true);
                                    $sheet->setCellValue('F' . $cell, $sub_kegiatan->total_volume);
                                    $sheet->setCellValue('G' . $cell, $sub_kegiatan->total_pagu_renstra);
                                    
                                    $sheet->setCellValue('H' . $cell, $sub_kegiatan->RealisasiK);
                                    $sheet->setCellValue('I' . $cell, $sub_kegiatan->RealisasiRp);
                                    $sheet->setCellValue('J' . $cell, $sub_kegiatan->TargetK);
                                    $sheet->setCellValue('K' . $cell, $sub_kegiatan->TargetRp);
                                    $kolom='L';
                                    if ($triwulan==2){
                                    for ($p=1;$p<=$triwulan;$p++){
                                        $sheet->setCellValue($kolom++ . $cell, $sub_kegiatan->RealisasiKinerjaK[$p]);
                                        $sheet->setCellValue($kolom++ . $cell, $sub_kegiatan->RealisasiKinerjaRp[$p]);
                                    }
                                    $sheet->setCellValue('P' . $cell, '');
                                    $sheet->setCellValue('Q' . $cell, '');
                                    $sheet->setCellValue('R' . $cell, '');
                                    $sheet->setCellValue('S' . $cell, '');
                                    }else{
                                    for ($p=1;$p<=$triwulan;$p++){
                                        $sheet->setCellValue($kolom++ . $cell, $sub_kegiatan->RealisasiKinerjaK[$p]);
                                        $sheet->setCellValue($kolom++ . $cell, $sub_kegiatan->RealisasiKinerjaRp[$p]);
                                    }
                                    }
                                    
                                    
                                    
                                    $sheet->setCellValue('T' . $cell, $sub_kegiatan->K13);
                                    $sheet->setCellValue('U' . $cell, $sub_kegiatan->Rp13);
                                    $sheet->setCellValue('V' . $cell, $sub_kegiatan->K14);
                                    $sheet->setCellValue('W' . $cell, $sub_kegiatan->Rp14);
                                    $sheet->setCellValue('X' . $cell, number_format($sub_kegiatan->K15,2).'%');
                                    $sheet->setCellValue('Y' . $cell, number_format($sub_kegiatan->Rp15,2).'%');
                                    $sheet->setCellValue('Z' . $cell, $sasaran->nama_unit_kerja);
                                    $sheet->setCellValue('AA' . $cell, '');
                                    $sheet->getStyle('A' . $cell . ':AA' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
                                    $cell++;
                                    
                                    
                                    $jumlah_sub_kegiatan++;
                                

                                    $jumlah_persen_kinerja_sub_kegiatan+= $sub_kegiatan->TargetK == 0 ? 0 : (($sub_kegiatan->K13/$sub_kegiatan->TargetK)*100);
                                    $jumlah_persen_keuangan_sub_kegiatan+= $sub_kegiatan->TargetRp == 0 ? 0 : (($sub_kegiatan->Rp13/$sub_kegiatan->TargetRp)*100);
                                    $jumlah_rp+=$sub_kegiatan->Rp13;
                                    $jumlah_target+=$sub_kegiatan->TargetRp;
                                }
                            
                                
                            }
                        
                            }
                        }
                        }
            
        }

        $sheet->getStyle('A1:AA' . $cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:AA' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B9:B' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C9:C' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D9:D' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E9:E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('G9:G' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I9:I' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('K9:K' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('M9:M' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('O9:O' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('W9:W' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('S9:S' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('U9:U' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('W9:W' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('F9:F' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('G9:G' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('H9:H' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('I9:I' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('J9:J' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('K9:K' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('L9:L' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('M9:M' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('N9:N' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('O9:O' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('P9:P' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('Q9:Q' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('R9:R' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('S9:S' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('T9:T' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('U9:U' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('V9:V' . $cell)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('W9:W' . $cell)->getNumberFormat()->setFormatCode('#,##0');

        $rata_rata_capaian_kinerja=$jumlah_sub_kegiatan == 0 ? 0 : ($jumlah_persen_kinerja_sub_kegiatan/$jumlah_sub_kegiatan);
        $rata_rata_capaian_keuangan=$jumlah_sub_kegiatan == 0 ? 0 : ($jumlah_persen_keuangan_sub_kegiatan/$jumlah_sub_kegiatan);

        $cell++;
        $sheet->getStyle('A'.$cell.':Y' . ($cell+1))->getFont()->setBold(true);     
        $sheet->getStyle('K'.$cell.':L' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('K' . $cell,  number_format($jumlah_target==0?0:($jumlah_target),2));
        $sheet->setCellValue('L' . $cell, 'Rata-rata capaian kinerja (%)')->mergeCells('L' . $cell . ':O' . $cell);
        $sheet->setCellValue('T' . $cell, number_format($rata_rata_capaian_kinerja,2) );
        //$sheet->setCellValue('U' . $cell, pembulatanDuaDecimal($rata_rata_capaian_keuangan));
        $sheet->setCellValue('U' . $cell, number_format($jumlah_target==0?0:($jumlah_rp/$jumlah_target)*100,2));

        $sheet->setCellValue('V' . $cell, '')->mergeCells('V' . $cell . ':AA' . $cell);

        if($rata_rata_capaian_kinerja>90){
            $PredikatK='ST';
        }elseif($rata_rata_capaian_kinerja>75){
            $PredikatK='T';
        }elseif($rata_rata_capaian_kinerja>65){
            $PredikatK='S';
        }elseif($rata_rata_capaian_kinerja>50){
            $PredikatK='R';
        }else{
            $PredikatK='SR';
        }
        if($rata_rata_capaian_keuangan>90){
            $PredikatRp='ST';
        }elseif($rata_rata_capaian_kinerja>75){
            $PredikatRp='T';
        }elseif($rata_rata_capaian_kinerja>65){
            $PredikatRp='S';
        }elseif($rata_rata_capaian_kinerja>50){
            $PredikatRp='R';
        }else{
            $PredikatRp='SR';
        }

        $sheet->getStyle('A' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('T' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cell++;
        $sheet->setCellValue('A' . $cell, 'Predikat kinerja')->mergeCells('A' . $cell . ':O' . $cell);
        $sheet->setCellValue('T' . $cell, $PredikatK);
        $sheet->setCellValue('U' . $cell, $PredikatRp);
        $sheet->setCellValue('V' . $cell, '')->mergeCells('V' . $cell . ':AA' . $cell);;
        $sheet->getStyle('A' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('T' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.($cell-1).':AA'. $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        $cell++;
        $sheet->setCellValue('A' . $cell, 'Faktor pendorong keberhasilan kinerja:')->mergeCells('A' . $cell . ':AA' . $cell);
        $sheet->setCellValue('A' . ++$cell, 'Faktor penghambat pencapaian kinerja:')->mergeCells('A' . $cell . ':AA' . $cell);
        $sheet->setCellValue('A' . ++$cell, 'Tindak lanjut yang diperlukan dalam triwulan berikutnya:')->mergeCells('A' . $cell . ':AA' . $cell);
        $sheet->setCellValue('A' . ++$cell, 'Tindak lanjut yang diperlukan dalam RKPD berikutnya:')->mergeCells('A' . $cell . ':AA' . $cell);

        $sheet->getStyle('X9:X' . $cell)->getFont()->setBold(true);
        $sheet->getStyle('Y9:Y' . $cell)->getFont()->setBold(true);

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:AA' . $cell)->applyFromArray($border);
        $cell++;
 

        $sheet->setCellValue('V' . ++$cell, 'Kabupaten Enrekang, ' . date('d/m/Y'))->mergeCells('V' . $cell . ':AA' . $cell);
            $sheet->getStyle('V' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('V' . ++$cell, request('nama_jabatan_kepala', ''))->mergeCells('V' . $cell . ':AA' . $cell);
            $sheet->getStyle('V' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $cell = $cell + 5;
            $sheet->setCellValue('V' . ++$cell, request('nama', ''))->mergeCells('V' . $cell . ':AA' . $cell);
            $sheet->getStyle('V' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('V' . ++$cell, 'Pangkat/Golongan : ' . request('jabatan', ''))->mergeCells('V' . $cell . ':AA' . $cell);
            $sheet->getStyle('V' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('V' . ++$cell, 'NIP : ' . request('nip', ''))->mergeCells('V' . $cell . ':AA' . $cell);
            $sheet->getStyle('V' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


            $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':L' . $cell);
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
}
