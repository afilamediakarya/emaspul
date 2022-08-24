<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
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
        if ($type == 'SPGs') {
            $breadcumb = 'Dokumen SPGs Desa';
            $current_breadcumb = '';
            return view('module.desa.dokumen.partials.spgs',compact('breadcumb','current_breadcumb'));
        }
    }

    public function verifikasi(){
        $document = request('document');
        $jenis = request('jenis');
        $breadcumb = 'Dokumen Desa';
        $current_breadcumb = 'Verifikasi';
        return view('module.desa.dokumen.partials.verifikasi',compact('breadcumb','current_breadcumb','document','jenis'));
    }

    public function konsederan(){
        $jenis = request('jenis');
        $document = request('document');
        $fungsi = 'konsederan_'.$jenis;
        $data = array();

        if ($jenis == 'rpjmdes') {
            $data = DB::table('documents')->select('documents.id','documents.periode_awal','documents.periode_akhir','perangkat_desa.nama_desa as unit_kerja','perangkat_desa.nama_kepala','perangkat_desa.jabatan_kepala')->join('perangkat_desa','documents.id_perangkat','=','perangkat_desa.id')->where('documents.id',$document)->first();
            $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();
        }else {
            $data = DB::table('documents')->select('documents.id','documents.tahun','perangkat_desa.nama_desa as unit_kerja','perangkat_desa.nama_kepala','perangkat_desa.jabatan_kepala')->join('perangkat_desa','documents.id_perangkat','=','perangkat_desa.id')->where('documents.id',$document)->first();
            $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();
        }

        return $this->{$fungsi}($data);
    }

    public function konsederan_rpjmdes($data){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI RKPMDes '.$data->unit_kerja.'')
            ->setSubject('EVALUASI RKPMDes '.$data->unit_kerja.'')
            ->setDescription('EVALUASI RKPMDes '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('RKPMDes');
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
        $cell = 1;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RENCANA PEMBANGUNAN JANGKA MENENGAH DESA ')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'(RPJM DESA) '.strtoupper($data->unit_kerja),' PERIODE '.$data->periode_awal.'-'.$data->periode_akhir)->mergeCells('A'. $cell . ':F' . $cell);
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

        $sheet->getStyle('A4:F'. $cell )->applyFromArray($border);
        $sheet->getStyle('A1:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B6:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('F6:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:F5')->getFont()->setBold(true);
        
        $cell++;

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
            $writer->save('php://output');
            exit;
    }

    public function konsederan_rkpdes($data){
        $spreadsheet = new Spreadsheet();
        // return $data;

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI RKPDes '.$data->unit_kerja.'')
            ->setSubject('EVALUASI RKPDes '.$data->unit_kerja.'')
            ->setDescription('EVALUASI RKPDes '.$data->unit_kerja.'')
            ->setKeywords('pdf php')
            ->setCategory('RKPDes');
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
        $cell = 1;
        $sheet->setCellValue('A'.$cell,'FORMULIR VERIFIKASI RENCANA KERJA PEMERINTAH DESA')->mergeCells('A'. $cell . ':F' . $cell);
        $cell++;
        $sheet->setCellValue('A'.$cell,'(RKPD DESA) '.strtoupper($data->unit_kerja).' TAHUN '.$data->tahun)->mergeCells('A'. $cell . ':F' . $cell);
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

        $sheet->getStyle('A4:F'. $cell )->applyFromArray($border);
        $sheet->getStyle('A1:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B6:B'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');
        $sheet->getStyle('F6:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('left');

        $sheet->getStyle('A1:F5')->getFont()->setBold(true);
        
        $cell++;

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
            $writer->save('php://output');
            exit;
    }

    // referensi
    public function alokasi_skpd(){
        $breadcumb = 'Data Referensi';
        $current_breadcumb = 'Daftar Alokasi SKPD';
        return view('module.desa.referensi.daftar_alokasi',compact('breadcumb','current_breadcumb'));
    } 

    public function data_alokasi_desa(){

        $perangkat_desa = DB::table('user')->select('perangkat_desa.id_desa')->join('perangkat_desa','user.id_unit_kerja','perangkat_desa.id')->where('user.id',Auth::user()->id)->first();

        $result = [];
       // Data yang diperlukan id_desa, 
       $data = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<>''")->get();
       $tahun = session('tahun_penganggaran');
       $type = request('type');

       foreach($data as $unit_kerja_list){
            $unit_kerja_list->Dpa=DB::table('dpa')
            ->join('sub_kegiatan', 'dpa.id_sub_kegiatan', '=', 'sub_kegiatan.id')
            ->join('pegawai_penanggung_jawab', 'dpa.id_pegawai_penanggung_jawab', '=', 'pegawai_penanggung_jawab.id')
            ->select('dpa.id','dpa.nilai_pagu_dpa','sub_kegiatan.nama_sub_kegiatan','sub_kegiatan.kode_sub_kegiatan','pegawai_penanggung_jawab.nama_lengkap as ppk')
            ->where('dpa.tahun',$tahun)
            ->where('dpa.id_unit_kerja',$unit_kerja_list->id)
            ->get();

            foreach ( $unit_kerja_list->Dpa as $dpa ){
                
            $dpa->Paket1=DB::table('paket_dau')
                ->selectRaw("paket_dau.id,paket_dau.satuan,paket_dau.id_sumber_dana_dpa,paket_dau.nama_paket,paket_dau.volume,paket_dau.pagu,paket_dau.keterangan,@jenis_paket:='dau' as jenis_paket,sumber_dana_dpa.sumber_dana")
                ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dau.id_sumber_dana_dpa')
                ->whereRaw("paket_dau.id_dpa='$dpa->id'");
                $dpa->Paket=DB::table('paket_dak')
                ->selectRaw("paket_dak.id,paket_dak.satuan,paket_dak.id_sumber_dana_dpa,paket_dak.nama_paket,paket_dak.volume,paket_dak.anggaran_dak as pagu,@keterangan:='' as keterangan,@jenis_paket:='dak' as jenis_paket,sumber_dana_dpa.sumber_dana")
                ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dak.id_sumber_dana_dpa')
                ->whereRaw("paket_dak.id_dpa='$dpa->id' AND sumber_dana_dpa.jenis_belanja='Belanja Modal'")
                ->union($dpa->Paket1)->get();

                foreach($dpa->Paket as $paket){
                    if($paket->jenis_paket=='dau'){
                        $paket->Lokasi=DB::table('paket_dau_lokasi')
                        ->join('desa','paket_dau_lokasi.id_desa','=','desa.id')
                        ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                        ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                        ->whereRaw("id_paket_dau='$paket->id' AND desa.id=".$perangkat_desa->id_desa)
                        ->get();
                    }else{
                        $paket->Lokasi=DB::table('paket_dak_lokasi')
                        ->join('desa','paket_dak_lokasi.id_desa','=','desa.id')
                        ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                        ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                        ->whereRaw("id_paket_dak='$paket->id' AND desa.id=".$perangkat_desa->id_desa)
                        ->get();
                    }
                    $paket->Desa='';
                    $paket->Kecamatan='';
                    foreach($paket->Lokasi as $lokasi){
                        $paket->Desa.=$lokasi->nama_desa;
                        $paket->Kecamatan.=$lokasi->nama_kecamatan;

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
                    // $result[$i]['nama_unit_kerja'] = $res->nama_unit_kerja;
                    // $result[$i]['nama_paket'] =  $paket->nama_paket;
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
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        //Margin PDF
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.3);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.3);
        // if ($sumber_dana_selected == 'semua') {
        //     $sumber_dana_selected = 'DAK FISIK & NON FISIK';
        // } else {
        //     $sumber_dana_selected = strtoupper($sumber_dana_selected);
        // }

        $sheet->setCellValue('A1', 'LAPORAN DAFTAR ALOKASI PEMBANGUNAN');
        $sheet->setCellValue('A2', 'tes');
        $sheet->setCellValue('A3', 'PEMERINTAH KABUPATEN ENREKANG TAHUN ANGGARAN ' . $tahun );
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A1')->getFont()->setSize(12);

        $sheet->getStyle('A:I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:I6')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'NO')->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'URAIAN KEGIATAN')->mergeCells('B5:B6')->getColumnDimension('B')->setWidth(50);
        $sheet->setCellValue('C5', 'PAGU')->mergeCells('C5:C6')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D5', 'LOKASI')->mergeCells('D5:E5');
        $sheet->setCellValue('F5', 'VOLUME')->mergeCells('F5:F6')->getColumnDimension('F')->setWidth(12);
        $sheet->setCellValue('G5', 'PPK/PPTK')->mergeCells('G5:G6')->getColumnDimension('G')->setWidth(25);
        $sheet->setCellValue('H5', 'SUMBER DANA')->mergeCells('H5:H6')->getColumnDimension('H')->setWidth(20);
        $sheet->setCellValue('I5', 'KET')->mergeCells('I5:I6')->getColumnDimension('I')->setWidth(20);


        $sheet->setCellValue('D6', 'DESA/KEL')->mergeCells('D6:D6')->getColumnDimension('D')->setWidth(15);
        $sheet->setCellValue('E6', 'KECAMATAN')->mergeCells('E6:E6')->getColumnDimension('E')->setWidth(15);

        $sheet->getStyle('A5:I6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        $cell = 7;
        $tot_pagu=0;

        foreach ( $data as $res ){
            if($res->subCount==0){
                continue;
            }
            $sheet->getRowDimension($cell)->setRowHeight(25);
            $sheet->getStyle('A'.$cell.':I'.$cell)->getFont()->setBold(true);
            $sheet->setCellValue('B'.$cell,$res->nama_unit_kerja)->mergeCells('B'.$cell.':B'.$cell);
            $sheet->setCellValue('C'.$cell, 'Rp. '.number_format($res->Pagu))->mergeCells('C'.$cell.':C'.$cell);
            $sheet->setCellValue('D'.$cell,'')->mergeCells('D'.$cell.':I'.$cell);
            $sheet->getStyle('A' . $cell . ':I' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CC99FF');
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
                        $sheet->setCellValue('D' . $cell, '');
                        $sheet->setCellValue('E' . $cell, '');
                        $sheet->setCellValue('F' . $cell, '');
                        $sheet->setCellValue('G' . $cell, '');
                        $sheet->setCellValue('H' . $cell, '');
                        $sheet->setCellValue('I' . $cell, '');
                        $sheet->getStyle('A' . $cell . ':I' . $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
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
                                $sheet->setCellValue('G' . $cell, $dpa->ppk);
                                $sheet->setCellValue('H' . $cell, $paket->sumber_dana);
                                $sheet->setCellValue('I' . $cell, $paket->keterangan);
                                $cell++;
    
                    }
            }
        }

        $sheet->getRowDimension($cell)->setRowHeight(25);
        $sheet->getStyle('A'.$cell.':I'.$cell)->getFont()->setBold(true);
        $sheet->setCellValue('B'.$cell,'TOTAL ')->mergeCells('B'.$cell.':B'.$cell);
        $sheet->setCellValue('C'.$cell, 'Rp. '.number_format($tot_pagu))->mergeCells('C'.$cell.':C'.$cell);
        $sheet->setCellValue('D'.$cell,'')->mergeCells('D'.$cell.':I'.$cell);
        $sheet->getStyle('A'.$cell.':I'. $cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');

        $sheet->getStyle('A1:I' . $cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:I' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:A' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B7:B' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C7:C' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D7:D' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E7:E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('G7:G' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('H7:H' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C7:C' . $cell)->getNumberFormat()->setFormatCode('#.##0');

        $sheet->getStyle('A' . $cell . ':I' . $cell)->getFont()->setBold(true);
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

        $sheet->getStyle('A5:I' . $cell)->applyFromArray($border);
        $cell++;
   

        $sheet->setCellValue('E' . ++$cell, '')->mergeCells('E' . $cell . ':I' . $cell);
            $sheet->getStyle('E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E' . ++$cell, request('nama_jabatan_kepala', ''))->mergeCells('E' . $cell . ':I' . $cell);
            $sheet->getStyle('E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $cell = $cell + 3;
            $sheet->setCellValue('E' . ++$cell, request('nama', ''))->mergeCells('E' . $cell . ':I' . $cell);
            $sheet->getStyle('E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E' . ++$cell, 'Pangkat/Golongan : ' . request('jabatan', ''))->mergeCells('E' . $cell . ':I' . $cell);
            $sheet->getStyle('E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E' . ++$cell, 'NIP : ' . request('nip', ''))->mergeCells('E' . $cell . ':I' . $cell);
            $sheet->getStyle('E' . $cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':L' . $cell);
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
