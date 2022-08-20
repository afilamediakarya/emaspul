<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

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
            return view('module.opd.dokumen.partials.renstra',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Renja') {
            $breadcumb = 'Dokumen Renja';
            $current_breadcumb = 'Renja';
            return view('module.opd.dokumen.partials.renja',compact('breadcumb','current_breadcumb'));
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
       
        $data = DB::table('documents')->select('documents.id','documents.tahun','documents.periode_awal','documents.periode_akhir','documents.nomor_konsederan','unit_kerja.nama_unit_kerja as unit_kerja','unit_kerja.nama_kepala as nama_kepala_unit_kerja','unit_kerja.nip_kepala as nip_kepala_unit_kerja','user.nama_lengkap as nama_verifikator','user.nip as nip_verifikator')->join('unit_kerja','documents.id_perangkat','=','unit_kerja.id')->join('user', 'documents.id_verifikator','=','user.id')->where('documents.id',$document)->first();

        $data->tabel = DB::table('verifikasi_documents')->select('verifikasi_documents.id','verifikasi_documents.verifikasi','verifikasi_documents.tindak_lanjut','master_verifikasi.indikator')->join('master_verifikasi','verifikasi_documents.id_master_verifikasi','=','master_verifikasi.id')->where('id_documents',$document)->get();

        $data->hari = $this->getHari();
        $data->tanggal = date('d');
        $data->bulan = date('m');
        $data->tahun = date('Y');
        
        return $this->{$fungsi}($data);
    }

  

    public function konsederan_renstra($data){
      
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI Renstra ')
            ->setSubject('EVALUASI Renstra ')
            ->setDescription('EVALUASI Renstra ')
            ->setKeywords('pdf php')
            ->setCategory('Renstra');
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
       
        // Header Text
        $sheet->setCellValue('A1', 'FORMAT KONSEDERAN RPJMDES');

        $sheet->setCellValue('A3', 'Nama Perangkat Desa / Unit Kerja : ')->getColumnDimension('A')->setWidth(45);
        $sheet->setCellValue('B3', $data->unit_kerja)->getColumnDimension('B')->setWidth(35);
        $sheet->setCellValue('A4', 'Tahun Awal : ');
        $sheet->setCellValue('B4', $data->periode_awal);
        $sheet->setCellValue('A5', 'Tahun Akhir : ');
        $sheet->setCellValue('B5', $data->periode_akhir);
        $sheet->setCellValue('A6', 'Nama Kepala : ');
        $sheet->setCellValue('B6', $data->nama_kepala_unit_kerja);
        $sheet->setCellValue('A7', 'Nip Kepala : ');
        $sheet->setCellValue('B7', $data->nip_kepala_unit_kerja);
        $sheet->setCellValue('A8', 'Nama Verifikator : ');
        $sheet->setCellValue('B8', $data->nama_verifikator);
        $sheet->setCellValue('A9', 'Nip Verifikator : ');
        $sheet->setCellValue('B9', $data->nip_verifikator);

        $sheet->setCellValue('A11', 'Nomor Konsederan : ');
        $sheet->setCellValue('B11', $data->nomor_konsederan);

        $sheet->setCellValue('A13', 'Hari : ');
        $sheet->setCellValue('B13', $data->hari);
        $sheet->setCellValue('A14', 'Tanggal : ');
        $sheet->setCellValue('B14', $data->tanggal);
        $sheet->setCellValue('A15', 'Bulan : ');
        $sheet->setCellValue('B15', $data->bulan);
        $sheet->setCellValue('A16', 'Tahun : ');
        $sheet->setCellValue('B16', $data->tahun);


        $sheet->setCellValue('A18', 'No');
        $sheet->setCellValue('B18','Indikator');
        $sheet->setCellValue('C18', 'Kesesuaian')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D18','Tindak Lanjut')->getColumnDimension('D')->setWidth(25);;

        $cell = 20;

        $i=0;

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator);
            $sheet->setCellValue('C' . $cell, $row->verifikasi);
            $sheet->setCellValue('D' . $cell, $row->tindak_lanjut);
            $cell++;
        }
        
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

    public function konsederan_renja($data){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI Renja ')
            ->setSubject('EVALUASI Renja ')
            ->setDescription('EVALUASI Renja ')
            ->setKeywords('pdf php')
            ->setCategory('Renja');
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
       
        // Header Text
        $sheet->setCellValue('A1', 'FORMAT KONSEDERAN RPJMDES');

        $sheet->setCellValue('A3', 'Nama Perangkat Desa / Unit Kerja : ')->getColumnDimension('A')->setWidth(45);
        $sheet->setCellValue('B3', $data->unit_kerja)->getColumnDimension('B')->setWidth(35);
        $sheet->setCellValue('A4', 'Tahun Awal : ');
        $sheet->setCellValue('B4', $data->periode_awal);
        $sheet->setCellValue('A5', 'Tahun Akhir : ');
        $sheet->setCellValue('B5', $data->periode_akhir);
        $sheet->setCellValue('A6', 'Nama Kepala : ');
        $sheet->setCellValue('B6', $data->nama_kepala_unit_kerja);
        $sheet->setCellValue('A7', 'Nip Kepala : ');
        $sheet->setCellValue('B7', $data->nip_kepala_unit_kerja);
        $sheet->setCellValue('A8', 'Nama Verifikator : ');
        $sheet->setCellValue('B8', $data->nama_verifikator);
        $sheet->setCellValue('A9', 'Nip Verifikator : ');
        $sheet->setCellValue('B9', $data->nip_verifikator);

        $sheet->setCellValue('A11', 'Nomor Konsederan : ');
        $sheet->setCellValue('B11', $data->nomor_konsederan);

        $sheet->setCellValue('A13', 'Hari : ');
        $sheet->setCellValue('B13', $data->hari);
        $sheet->setCellValue('A14', 'Tanggal : ');
        $sheet->setCellValue('B14', $data->tanggal);
        $sheet->setCellValue('A15', 'Bulan : ');
        $sheet->setCellValue('B15', $data->bulan);
        $sheet->setCellValue('A16', 'Tahun : ');
        $sheet->setCellValue('B16', $data->tahun);


        $sheet->setCellValue('A18', 'No');
        $sheet->setCellValue('B18','Indikator');
        $sheet->setCellValue('C18', 'Kesesuaian')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D18','Tindak Lanjut')->getColumnDimension('D')->setWidth(25);;

        $cell = 20;

        $i=0;

        foreach ( $data->tabel as $row ){
            $sheet->setCellValue('A' . $cell, ++$i);
            $sheet->setCellValue('B' . $cell, $row->indikator);
            $sheet->setCellValue('C' . $cell, $row->verifikasi);
            $sheet->setCellValue('D' . $cell, $row->tindak_lanjut);
            $cell++;
        }
        
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
