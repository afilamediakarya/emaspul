<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
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
       
        $data = DB::table('documents')->select('documents.id','documents.tahun','documents.periode_awal','documents.periode_akhir','documents.nomor_konsederan','unit_kerja.nama_unit_kerja as unit_kerja','user.nama_lengkap as nama_verifikator','user.nip as nip_verifikator')->join('unit_kerja','documents.id_perangkat','=','unit_kerja.id')->join('user', 'documents.id_verifikator','=','user.id')->where('documents.id',$document)->first();

        $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();

        $data->hari = $this->getHari();
        $data->tanggal = date('d');
        $data->bulan = date('m');
        $data->tahun = date('Y');
        $data->nama_user = Auth::user()->nama_lengkap;
        $data->nip_user = Auth::user()->nip;
        
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
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        $sheet->getDefaultRowDimension()->setRowHeight(15);

        //Margin PDF
        
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.8);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(1.2);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1.0);
        $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setWrapText(true);
       
        // Header Text
        $sheet->setCellValue('A1', 'BERITA ACARA')->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN AWAL RENCANA STRATEGIS (RENSTRA)')->mergeCells('A2:F2');
        $sheet->setCellValue('A3', ''.strtoupper($data->unit_kerja))->mergeCells('A3:F3');
        $sheet->setCellValue('A4', 'KABUPATEN ENREKANG PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A4:F4');
        $border = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:F5' )->applyFromArray($border);
        

        
        $sheet->setCellValue('A5', "NOMOR : ".strtoupper($data->nomor_konsederan))->mergeCells('A5:F5');
        $sheet->setCellValue('A6', " ")->mergeCells('A6:F6');
        $sheet->setCellValue('A7', '            Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renstra PD '.$data->unit_kerja.' Kabupaten Enrekang periode '.$data->periode_awal.'-'.$data->periode_akhir.', sebagai berikut : 
              ')->mergeCells('A7:F7');
        
        $sheet->setCellValue('A8', "Setelah dilakukan verifikasi rancangan awal Renstra maka disepakati : ")->mergeCells('A8:F8');
        
        $sheet->setCellValue('A9', " ")->mergeCells('A9:F9');

        $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B10');
        $sheet->setCellValue('C10', "Sistematika penulisan Renstra agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :
            1.	Pendahuluan;
            2.	Gambaran Pelayanan Perangkat Daerah;
            3.	Permasalahan dan Isu Isu Strategis Perangkat Daerah
            4.	Tujuan dan Sasaran Perangkat Daerah;
            5.	Rencana Program dan Kegiatan serta  Pendanaan;
            6.	Kinerja Penyelenggaran Bidang Urusan; dan
            7.	Penutup.")->mergeCells('C10:F10');

        $sheet->setCellValue('A11', "KEDUA")->mergeCells('A11:B11');
        $sheet->setCellValue('C11', "Melakukan penyempurnaan rancangan Renstra Tahun periode ".$data->periode_awal.'-'.$data->periode_akhir." Berdasarkan  hasil verifikasi, meliputi :  
        1.	Penyempurnaan rancangan Renstra sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini; 
        2.	Penyempurnaan matrik Rumusan Rencana Program dan Kegiatan Perangkat Daerah periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://emonev.enrekangkab.go.id/")->mergeCells('C11:F11');
        
        $sheet->setCellValue('A12', "KETIGA")->mergeCells('A12:B12');
        $sheet->setCellValue('C12', "Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renstra periode ".$data->periode_awal.'-'.$data->periode_akhir." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF paling lambat tanggal")->mergeCells('C12:F12');

        $sheet->setCellValue('A13', ' ')->mergeCells('A13:F13');
        $sheet->setCellValue('A14', '           Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A14:F14');

        $sheet->setCellValue('A15', ' ')->mergeCells('A15:F15');

        $sheet->setCellValue('A16', 'Verifikator Renstra PD')->mergeCells('A16:D16');
        $sheet->setCellValue('A17', ' Kabupaten Enrekang')->mergeCells('A17:D17');
        $sheet->setCellValue('A18', ' ')->mergeCells('A18:F18');
        $sheet->setCellValue('A19', ' ')->mergeCells('A19:F19');
        $sheet->setCellValue('A20', ' ')->mergeCells('A20:F20');
        $sheet->setCellValue('A21', ' ')->mergeCells('A21:F21');
        
        $sheet->getStyle('A23')->getFont()->setUnderline(true);
        $sheet->setCellValue('A23', $data->nama_verifikator)->mergeCells('A23:D23');
        $sheet->setCellValue('A24', $data->nip_verifikator)->mergeCells('A24:D24');



        $sheet->setCellValue('E16', 'Tim Penyusun  Renstra')->mergeCells('E16:F16');
        $sheet->setCellValue('E17', $data->unit_kerja.' Kabupaten Enrekang')->mergeCells('E17:F17');
        
        $sheet->getStyle('E23')->getFont()->setUnderline(true);
        $sheet->setCellValue('E23', $data->nama_kepala_unit_kerja)->mergeCells('E23:F23');
        $sheet->setCellValue('E24', $data->nip_kepala_unit_kerja)->mergeCells('E24:F24');
        $sheet->setCellValue('A25', ' 
        
        
        
        
        ')->mergeCells('A25:F25');

        
        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RANCANGAN RENCANA STRATEGIS  SATUAN KERJA')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'PERANGKAT DAERAH (RENSTRA - SKPD) PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . ($cell+1));
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':C' . ($cell+1));
        $sheet->setCellValue('D'. $cell,'KESESUAIAN')->mergeCells('D'. $cell . ':E' . $cell);
        $sheet->setCellValue('D'. ($cell+1),'ADA');
        $sheet->setCellValue('E'. ($cell+1),'TIDAK ADA');
        $sheet->setCellValue('F'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('F'. $cell . ':F' . ($cell+1));
        
        $cell++;

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(7);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(30);

        $cell++;
        $i=0;
        

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator)->mergeCells('B'. $cell . ':C' . $cell);
            if ($row->verifikasi==1){
                $sheet->setCellValue('D' . $cell, 'v');
            }
            else{
                $sheet->setCellValue('E' . $cell, 'v');
            }
            $sheet->setCellValue('F' . $cell, $row->tindak_lanjut);
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

        $sheet->getStyle('A38:F'. $cell )->applyFromArray($border);
        $sheet->getStyle('A35:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B40:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('F40:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:F6')->getFont()->setBold(true);
        $sheet->getStyle('A1:F6')->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('A7:F12')->getAlignment()->setVertical('top')->setHorizontal('justify');
        $sheet->getStyle('A16:F24')->getAlignment()->setVertical('top')->setHorizontal('center');
        
        $cell++;

        $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':D' . $cell);
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
            ->setTitle('EVALUASI Renja '.$data->unit_kerja.'')
            ->setSubject('EVALUASI Renja '.$data->unit_kerja.'')
            ->setDescription('EVALUASI Renja '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('Renja');
        $sheet = $spreadsheet->getActiveSheet();
        //$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        $sheet->getDefaultRowDimension()->setRowHeight(15);

        //Margin PDF
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.8);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(1.2);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1.0);
       
        // Header Text
        $sheet->setCellValue('A1', 'BERITA ACARA')->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'HASIL VERIFIKASI RANCANGAN AWAL RENCANA KERJA (RENJA)')->mergeCells('A2:F2');
        $sheet->setCellValue('A3', ''.strtoupper($data->unit_kerja))->mergeCells('A3:F3');
        $sheet->setCellValue('A4', 'KABUPATEN ENREKANG TAHUN '.$data->tahun)->mergeCells('A4:F4');
        $border = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:F5' )->applyFromArray($border);

        $sheet->setCellValue('A5', "NOMOR : ".strtoupper($data->nomor_konsederan))->mergeCells('A5:F5');
        $sheet->setCellValue('A6', " ")->mergeCells('A6:F6');
        $sheet->setCellValue('A7', '            Pada hari ini '.$data->hari.', tanggal '.$data->tanggal.' Bulan '.$data->bulan.' tahun '.$data->tahun.' telah dilaksanakan verifikasi terhadap Rancangan awal Renja  '.$data->unit_kerja.' Kabupaten Enrekang tahun '.$data->tahun.', sebagai berikut : 
              ')->mergeCells('A7:F7');
        
        $sheet->setCellValue('A8', "Setelah dilakukan verifikasi rancangan awal Renja maka disepakati : ")->mergeCells('A8:F8');
        
        $sheet->setCellValue('A9', " ")->mergeCells('A9:F9');

        $sheet->setCellValue('A10', "KESATU")->mergeCells('A10:B10');
        $sheet->setCellValue('C10', "Sistematika penulisan Renja agar disesuaikan dengan ketentuan Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 86 Tahun 2017 tentang Tata Cara Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah, Tata Cara Evaluasi Rancangan Peraturan Daerah tentang RPJPD dan RPJMD, serta Tata Cara Perubahan RPJPD, RPJMD, dan Rencana Kerja Pemerintah Daerah, paling sedikit memuat :
        1.	Pendahuluan;
        2.	Hasil Evaluasi Renja Perangkat Daerah tahun lalu;
        3.	Tujuan dan Sasaran Perangkat Daerah;
        4.	Rencana Kerja dan Pendanaan Perangkat Daerah; dan
        5.	Penutup.")->mergeCells('C10:F10');

        $sheet->setCellValue('A11', "KEDUA")->mergeCells('A11:B11');
        $sheet->setCellValue('C11', "Melakukan penyempurnaan rancangan Renja Tahun ".$data->tahun." Berdasarkan  hasil verifikasi, meliputi :  
        1.	Penyempurnaan rancangan Renja sesuai saran dan masukan Tim Verifikasi sebagaimana tersebut pada formulir verifikasi terlampir yang merupakan bagian tidak terpisahkan dari Berita Acara ini; 
        2.	Penyempurnaan matrik Rumusan RRencana Program dan Kegiatan Perangkat Daerah Tahun ".$data->tahun." dan Prakiraan Maju Tahun ".($data->tahun+1)." melalui portal https://enrekangkab.sipd.kemendagri.go.id/")->mergeCells('C11:F11');
        
        $sheet->setCellValue('A12', "KETIGA")->mergeCells('A12:B12');
        $sheet->setCellValue('C12', "Melakukan Upload Dokumen perbaikan hasil verifikasi Rancangan Akhir Renja Tahun ".$data->tahun." melalui portal https://langitmaspul.enrekangkab.go.id/ dalam bentuk PDF paling lambat tanggal")->mergeCells('C12:F12');

        $sheet->setCellValue('A13', ' ')->mergeCells('A13:F13');
        $sheet->setCellValue('A14', '           Demikian berita acara ini dibuat dan dipergunakan sebagaimana mestinya.')->mergeCells('A14:F14');

        $sheet->setCellValue('A15', ' ')->mergeCells('A15:F15');

        $sheet->setCellValue('A16', 'Koordinator Tim Verifikasi')->mergeCells('A16:D16');
        $sheet->setCellValue('A17', ' Kepala Bidang Monev,Litbang dan Perencanaan Makro')->mergeCells('A17:D17');
        $sheet->setCellValue('A18', ' ')->mergeCells('A18:F18');
        $sheet->setCellValue('A19', ' ')->mergeCells('A19:F19');
        $sheet->setCellValue('A20', ' ')->mergeCells('A20:F20');
        $sheet->setCellValue('A21', ' ')->mergeCells('A21:F21');
        
        $sheet->getStyle('A23')->getFont()->setUnderline(true);
        $sheet->setCellValue('A23', $data->nama_verifikator)->mergeCells('A23:D23');
        $sheet->setCellValue('A24', $data->nip_verifikator)->mergeCells('A24:D24');



        $sheet->setCellValue('E16', 'Tim Penyusun Renstra')->mergeCells('E16:F16');
        $sheet->setCellValue('E17', $data->unit_kerja.' Kabupaten Enrekang')->mergeCells('E17:F17');
        
        $sheet->getStyle('E23')->getFont()->setUnderline(true);
        $sheet->setCellValue('E23', $data->nama_kepala_unit_kerja)->mergeCells('E23:F23');
        $sheet->setCellValue('E24', $data->nip_kepala_unit_kerja)->mergeCells('E24:F24');
        $sheet->setCellValue('A25', ' 
    
        ')->mergeCells('A25:F25');



        $cell = 35;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RANCANGAN RENCANA KERJA  SATUAN KERJA')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'PERANGKAT DAERAH (RENJA - SKPD) TAHUN '.$data->tahun)->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,' ')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . ($cell+1));
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':C' . ($cell+1));
        $sheet->setCellValue('D'. $cell,'KESESUAIAN')->mergeCells('D'. $cell . ':E' . $cell);
        $sheet->setCellValue('D'. ($cell+1),'ADA');
        $sheet->setCellValue('E'. ($cell+1),'TIDAK ADA');
        $sheet->setCellValue('F'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('F'. $cell . ':F' . ($cell+1));
        
        $cell++;

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(7);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(30);

        $cell++;
        $i=0;
        

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator)->mergeCells('B'. $cell . ':C' . $cell);
            if ($row->verifikasi==1){
                $sheet->setCellValue('D' . $cell, 'v');
            }
            else{
                $sheet->setCellValue('E' . $cell, 'v');
            }
            $sheet->setCellValue('F' . $cell, $row->tindak_lanjut);
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

        $sheet->getStyle('A38:F'. $cell )->applyFromArray($border);
        $sheet->getStyle('A35:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B40:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('F40:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:F6')->getFont()->setBold(true);
        $sheet->getStyle('A1:F6')->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('A7:F12')->getAlignment()->setVertical('top')->setHorizontal('justify');
        $sheet->getStyle('A16:F24')->getAlignment()->setVertical('top')->setHorizontal('center');
        
        $cell++;

        $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':D' . $cell);
            
        
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
        return view('module.opd.dokumen.ikk',compact('breadcumb','current_breadcumb'));
    }

    public function export_ikk(){
        
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
                                'realisasi' => ''
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
                                'realisasi' => ''
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
                                    if($realisasi->count()>0){
                                        $sub_kegiatan->RealisasiKinerjaRp[$p]=$realisasi->first()->realisasi_keuangan;
                                        $sub_kegiatan->RealisasiKinerjaK[$p]=$realisasi->first()->realisasi_kinerja;
                                    }else{
                                        $sub_kegiatan->RealisasiKinerjaRp[$p]=0;
                                        $sub_kegiatan->RealisasiKinerjaK[$p]=0;
                                    }

                                    $kegiatan->TotalRealisasiKinerjaK[$p]+=$sub_kegiatan->RealisasiKinerjaK[$p];
                                    $kegiatan->TotalRealisasiKinerjaRp[$p]+=$sub_kegiatan->RealisasiKinerjaRp[$p];

                                    $kegiatan->TotalPersenRealisasiKinerjaK[$p]+=$sub_kegiatan->TargetK == 0 ? 0 : (($sub_kegiatan->RealisasiKinerjaK[$p])/$sub_kegiatan->TargetK)*100;
                                    //for renstra
                                    $kegiatan->TotalPersenRealisasiKinerjaRenstra[$p]+=$sub_kegiatan->total_volume == 0 ? 0 : (($sub_kegiatan->RealisasiKinerjaK[$p])/$sub_kegiatan->total_volume)*100;


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

                                foreach ($sub_kegiatan->Indikator as $in_indikator => $dt ){
                                    $indikator.=$dt->indikator." (".$dt->satuan.") \n";
                                    $result[] = [
                                        'indikator' => $indikator,
                                        'target' => $sub_kegiatan->total_volume,
                                        'realisasi' => $sub_kegiatan->K14
                                    ];
                                    
                                }

                            }

                        }
                    }
                }
            }
        }

         
        // foreach ( $data as $dt ){
        //     foreach($dt->Urusan as $urusan){
        //         foreach($urusan->BidangUrusan as $bidang_urusan){
        //            foreach ( $bidang_urusan->Program as $program ){
        //                 $program->KegiatanTotalTargetK=0;
        //                 $program->KegiatanTotalTargetRp=0;
        //                 $program->KegiatanTotalRealisasiK=0;
        //                 $program->KegiatanTotalRealisasiRp=0;
        //                 $program->KegiatanTotalTargetKinerjaK=0;
        //                 $program->KegiatanTotalTargetKinerjaRp=0;
        //                 for($p=1;$p<=$triwulan;$p++){
        //                 $program->TotalRealisasiKinerjaK[$p]=0;
        //                 $program->TotalRealisasiKinerjaRp[$p]=0;
        //                 }


        //                 foreach ( $program->Kegiatan as $kegiatan ){
        //                     $sub_kegiatan_count=$kegiatan->SubKegiatan->count();
        //                     $kegiatan->TargetK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->SubKegiatan->sum('total_volume'))/$sub_kegiatan_count);
        //                     $kegiatan->TargetRp=$kegiatan->SubKegiatan->sum('total_pagu_renstra');
        //                     // $kegiatan->RealisasiK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->KegiatanTotalRealisasiK)/$sub_kegiatan_count);
        //                     $kegiatan->RealisasiK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->totPersenK)/$sub_kegiatan_count);
        //                     $kegiatan->RealisasiRp=$kegiatan->KegiatanTotalRealisasiRp;
        //                     // $kegiatan->TargetKinerjaK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->KegiatanTotalTargetKinerjaK)/$sub_kegiatan_count);
        //                     $kegiatan->TargetKinerjaK=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->totPersenTargetK)/$sub_kegiatan_count);
        //                     $kegiatan->TargetKinerjaRp=$kegiatan->KegiatanTotalTargetKinerjaRp;
                            
                            

        //                     $program->KegiatanTotalTargetK+=$kegiatan->TargetK;
        //                     $program->KegiatanTotalTargetRp+=$kegiatan->TargetRp;
        //                     $program->KegiatanTotalRealisasiK+=$kegiatan->RealisasiK;
        //                     $program->KegiatanTotalRealisasiRp+=$kegiatan->RealisasiRp;
        //                     $program->KegiatanTotalTargetKinerjaK+=$kegiatan->TargetKinerjaK;
        //                     $program->KegiatanTotalTargetKinerjaRp+=$kegiatan->TargetKinerjaRp;


        //                     $totRealisasiKinerjaK=0;
        //                     $totRealisasiKinerjaRp=0;

        //                     for($p=1;$p<=$triwulan;$p++){
        //                         // $kegiatan->RealisasiKinerjaK[$p]=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->TotalRealisasiKinerjaK[$p])/$sub_kegiatan_count);
        //                         $kegiatan->RealisasiKinerjaK[$p]=$sub_kegiatan_count == 0 ? 0 : (($kegiatan->TotalPersenRealisasiKinerjaK[$p])/$sub_kegiatan_count);
        //                         $kegiatan->RealisasiKinerjaRp[$p]=$kegiatan->TotalRealisasiKinerjaRp[$p];
        //                         $program->TotalRealisasiKinerjaK[$p]+=$kegiatan->RealisasiKinerjaK[$p];
        //                         $program->TotalRealisasiKinerjaRp[$p]+=$kegiatan->RealisasiKinerjaRp[$p];
        //                         $totRealisasiKinerjaK+=$kegiatan->RealisasiKinerjaK[$p];
        //                         $totRealisasiKinerjaRp+=$kegiatan->RealisasiKinerjaRp[$p];
        //                     }

        //                     $kegiatan->K13=$totRealisasiKinerjaK;
        //                     $kegiatan->Rp13=$totRealisasiKinerjaRp;

        //                     $kegiatan->K14=($kegiatan->RealisasiK+$kegiatan->K13);
        //                     $kegiatan->Rp14=$kegiatan->Rp13+$kegiatan->RealisasiRp;
        //                     $kegiatan->K15=$kegiatan->Output->first()->volume==0?0:(($kegiatan->K14/$kegiatan->Output->first()->volume)*100);
        //                     $kegiatan->Rp15=$kegiatan->TargetRp==0?0:(($kegiatan->Rp14/$kegiatan->TargetRp)*100);


        //                 }
        //             }
        //         }
        //     }
        // }
        
        // foreach ( $data as $dt ){
        //     foreach($dt->Urusan as $urusan){
        //         foreach($urusan->BidangUrusan as $bidang_urusan){

        //            foreach ( $bidang_urusan->Program as $program ){
        //                $kegiatan_count=$program->Kegiatan->count();
        //                 $program->TargetK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalTargetK/$kegiatan_count);
        //                 $program->TargetRp=$program->KegiatanTotalTargetRp;
        //                 $program->RealisasiK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalRealisasiK/$kegiatan_count);
        //                 $program->RealisasiRp=$program->KegiatanTotalRealisasiRp;
        //                 $program->TargetKinerjaK=$kegiatan_count == 0 ? 0 : ($program->KegiatanTotalTargetKinerjaK/$kegiatan_count);
        //                 $program->TargetKinerjaRp=$program->KegiatanTotalTargetKinerjaRp;

                        


        //                 $totRealisasiKinerjaK=0;
        //                 $totRealisasiKinerjaRp=0;

        //                 for($p=1;$p<=$triwulan;$p++){
        //                     $program->RealisasiKinerjaK[$p]=$kegiatan_count == 0 ? 0 : ($program->TotalRealisasiKinerjaK[$p]/$kegiatan_count);
        //                     $program->RealisasiKinerjaRp[$p]=$program->TotalRealisasiKinerjaRp[$p];

        //                     $totRealisasiKinerjaK+=$program->RealisasiKinerjaK[$p];
        //                     $totRealisasiKinerjaRp+=$program->RealisasiKinerjaRp[$p];
        //                 }

                        

        //                 $program->K13=$totRealisasiKinerjaK;
        //                 $program->Rp13=$totRealisasiKinerjaRp;

        //                 $program->K14=($program->RealisasiK+($program->TargetKinerjaK*$program->K13))/100;
        //                 $program->Rp14=$program->Rp13+$program->RealisasiRp;
        //                 $program->K15=$program->TargetK==0?0:($program->K14/$program->TargetK*100);
        //                 $program->Rp15=$program->TargetRp==0?0:($program->Rp14/$program->TargetRp*100);

                        
        //             }
        //         }
        //     }
        // }
        
      
        // foreach ( $data as $dt ){
        //     foreach($dt->Urusan as $urusan){

        //         foreach($urusan->BidangUrusan as $bidang_urusan){
                    
        //             foreach ( $bidang_urusan->Program as $program ){
        //                 foreach($program->Outcome as $po){
        //                     $poTotTarget=$program->Kegiatan->where('id_renstra_program_outcome',$po->id);
                            
        //                     $po->PoTargetRp=$poTotTarget->sum('TargetRp');
        //                     $po->PoRealisasiK=$poTotTarget->count()==0?0:$poTotTarget->sum('RealisasiK')/$poTotTarget->count();
        //                     $po->PoRealisasiRp=$poTotTarget->sum('RealisasiRp');
        //                     $po->PoTargetKinerjaRp=$poTotTarget->sum('TargetKinerjaRp');
        //                     $po->PoTargetKinerjaK=$poTotTarget->count()==0?0:$poTotTarget->sum('TargetKinerjaK')/$poTotTarget->count();

        //                     for($p=1;$p<=$triwulan;$p++){
        //                         $po->PoRealisasiKinerjaK[$p]=$poTotTarget->count()==0?0:$poTotTarget->reduce(function ($sum,$option) use ($p) {
        //                             return $sum+=$option->RealisasiKinerjaK[$p];
        //                         }, 0)/$poTotTarget->count();
        //                         $po->PoRealisasiKinerjaRp[$p]=$poTotTarget->count()==0?0:$poTotTarget->reduce(function ($sum,$option) use ($p) {
        //                             return $sum+=$option->RealisasiKinerjaRp[$p];
        //                         }, 0);
        //                     }


        //                     $po->PoRp13=$poTotTarget->sum('Rp13');
        //                     $po->PoK13=$poTotTarget->count()==0?0:$poTotTarget->sum('K13')/$poTotTarget->count();
        //                     $po->PoRp14=$poTotTarget->sum('Rp14');
        //                     $po->PoK14=$poTotTarget->count()==0?0:$poTotTarget->sum('K14')/$poTotTarget->count();
        //                     $po->PoRp15=$poTotTarget->count()==0?0:$poTotTarget->sum('Rp15')/$poTotTarget->count();
        //                     $po->PoK15=$poTotTarget->count()==0?0:$poTotTarget->sum('K15')/$poTotTarget->count();

        //                 }

        //             }

        //         }
        //     }
        // }
        
        // foreach ( $data as $dt ){

        //     foreach($dt->Urusan as $urusan){


        //         foreach($urusan->BidangUrusan as $bidang_urusan){
        //             $bidang_urusan->Program->reduce(function ($sum,$program) use ($bidang_urusan,$triwulan) {
        //                 $program_count=$program->Outcome->count();

        //                 $bidang_urusan->TargetK=$program_count == 0 ? 0 : ($program->Outcome->sum('volume')/$program_count);
        //                 $bidang_urusan->TargetRp=$program->Outcome->sum('PoTargetRp');
        //                 $bidang_urusan->RealisasiK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoRealisasiK')/$program_count);
        //                 $bidang_urusan->RealisasiRp=$program->Outcome->sum('PoRealisasiRp');
        //                 $bidang_urusan->TargetKinerjaK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoTargetKinerjaK')/$program_count);
        //                 $bidang_urusan->TargetKinerjaRp=$program->Outcome->sum('PoTargetKinerjaRp');

        //                 for($p=1;$p<=$triwulan;$p++){
        //                     $bidang_urusan->RealisasiKinerjaK[$p]=$program_count==0?0:$program->Outcome->reduce(function ($sum,$option) use ($p) {
        //                         return $sum+=$option->PoRealisasiKinerjaK[$p];
        //                     }, 0)/$program_count;
        //                     $bidang_urusan->RealisasiKinerjaRp[$p]=$program->Outcome->reduce(function ($sum,$option) use ($p) {
        //                         return $sum+=$option->PoRealisasiKinerjaRp[$p];
        //                     }, 0);
        //                 }

        //                 $bidang_urusan->TargetKinerjaK=$program_count == 0 ? 0 : ($program->Outcome->sum('PoTargetKinerjaK')/$program_count);
        //                 $bidang_urusan->TargetKinerjaRp=$program->Outcome->sum('PoTargetKinerjaRp');

        //                 $bidang_urusan->K13=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK13')/$program_count);
        //                 $bidang_urusan->Rp13=$program->Outcome->sum('PoRp13');
        //                 $bidang_urusan->K14=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK14')/$program_count);
        //                 $bidang_urusan->Rp14=$program->Outcome->sum('PoRp13')+$bidang_urusan->RealisasiRp;
        //                 $bidang_urusan->K15=$program_count == 0 ? 0 : ($program->Outcome->sum('PoK15')/$program_count);
        //                 $bidang_urusan->Rp15=$program_count == 0 ? 0 : ($program->Outcome->sum('PoRp15')/$program_count);
                        
                        

                        
                        
        //             });

        //         }
        //     }
        // }
        
        // foreach ( $data as $dt ){

        //         foreach($dt->Urusan as $urusan){

        //             $bidang_urusan_count=$urusan->BidangUrusan->count();

        //                 $urusan->TargetK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetK')/$bidang_urusan_count);
        //                 $urusan->TargetRp=$urusan->BidangUrusan->sum('TargetRp');
        //                 $urusan->RealisasiK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('RealisasiK')/$bidang_urusan_count);
        //                 $urusan->RealisasiRp=$urusan->BidangUrusan->sum('RealisasiRp');
        //                 $urusan->TargetKinerjaK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetKinerjaK')/$bidang_urusan_count);
        //                 $urusan->TargetKinerjaRp=$urusan->BidangUrusan->sum('TargetKinerjaRp');

        //                 for($p=1;$p<=$triwulan;$p++){
        //                     $urusan->RealisasiKinerjaK[$p]=$bidang_urusan_count==0?0:$urusan->BidangUrusan->reduce(function ($sum,$option) use ($p) {
        //                         return $sum+=$option->RealisasiKinerjaK[$p];
        //                     }, 0)/$bidang_urusan_count;
        //                     $urusan->RealisasiKinerjaRp[$p]=$urusan->BidangUrusan->reduce(function ($sum,$option) use ($p) {
        //                         return $sum+=$option->RealisasiKinerjaRp[$p];
        //                     }, 0);
        //                 }

        //                 $urusan->TargetKinerjaK=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('TargetKinerjaK')/$bidang_urusan_count);
        //                 $urusan->TargetKinerjaRp=$urusan->BidangUrusan->sum('TargetKinerjaRp');

        //                 $urusan->K13=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K13')/$bidang_urusan_count);
        //                 $urusan->Rp13=$urusan->BidangUrusan->sum('Rp13');
        //                 $urusan->K14=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K14')/$bidang_urusan_count);
        //                 $urusan->Rp14=$urusan->BidangUrusan->sum('Rp13')+$urusan->RealisasiRp;
        //                 $urusan->K15=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('K15')/$bidang_urusan_count);
        //                 $urusan->Rp15=$bidang_urusan_count == 0 ? 0 : ($urusan->BidangUrusan->sum('Rp15')/$bidang_urusan_count);

        //         }
        // }

        // foreach ( $data as $dt ){
                        
        //     $urusan_count=$dt->Urusan->count();

        //     $dt->TargetK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetK')/$urusan_count);
        //     $dt->TargetRp=$dt->Urusan->sum('TargetRp');
        //     $dt->RealisasiK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('RealisasiK')/$urusan_count);
        //     $dt->RealisasiRp=$dt->Urusan->sum('RealisasiRp');
        //     $dt->TargetKinerjaK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetKinerjaK')/$urusan_count);
        //     $dt->TargetKinerjaRp=$dt->Urusan->sum('TargetKinerjaRp');

        //     for($p=1;$p<=$triwulan;$p++){
        //         $dt->RealisasiKinerjaK[$p]=$urusan_count==0?0:$dt->Urusan->reduce(function ($sum,$option) use ($p) {
        //             return $sum+=$option->RealisasiKinerjaK[$p];
        //         }, 0)/$urusan_count;
        //         $dt->RealisasiKinerjaRp[$p]=$dt->Urusan->reduce(function ($sum,$option) use ($p) {
        //             return $sum+=$option->RealisasiKinerjaRp[$p];
        //         }, 0);
        //     }

        //     $dt->TargetKinerjaK=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('TargetKinerjaK')/$urusan_count);
        //     $dt->TargetKinerjaRp=$dt->Urusan->sum('TargetKinerjaRp');

        //     $dt->K13=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K13')/$urusan_count);
        //     $dt->Rp13=$dt->Urusan->sum('Rp13');
        //     $dt->K14=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K14')/$urusan_count);
        //     $dt->Rp14=$dt->Urusan->sum('Rp13')+$dt->RealisasiRp;
        //     $dt->K15=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('K15')/$urusan_count);
        //     $dt->Rp15=$urusan_count == 0 ? 0 : ($dt->Urusan->sum('Rp15')/$urusan_count);

        // }

        if ($type == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $result,
            ]);
        }else{
            return $this->export_iki_($data);
        }
    }

    public function export_iki_($data){
        return $data;
    }
}
