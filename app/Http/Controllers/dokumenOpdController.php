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
}
