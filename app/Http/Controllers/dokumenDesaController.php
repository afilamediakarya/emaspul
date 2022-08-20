<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

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
            return view('module.desa.dokumen.partials.rpjmdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'RKPDes') {
            $breadcumb = 'Dokumen Desa';
            $current_breadcumb = 'RKPDes';
            return view('module.desa.dokumen.partials.rkpdes',compact('breadcumb','current_breadcumb'));
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
            ->setTitle('EVALUASI RKPMDes ')
            ->setSubject('EVALUASI RKPMDes ')
            ->setDescription('EVALUASI RKPMDes ')
            ->setKeywords('pdf php')
            ->setCategory('RKPMDes');
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
        $sheet->setCellValue('B6', $data->nama_kepala);
        $sheet->setCellValue('A7', 'Jabatan Kepala : ');
        $sheet->setCellValue('B7', $data->jabatan_kepala);

        $sheet->setCellValue('A9', 'No');
        $sheet->setCellValue('B9','Indikator');
        $sheet->setCellValue('C9', 'Kesesuaian')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D9','Tindak Lanjut')->getColumnDimension('D')->setWidth(25);;

        $cell = 11;

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

    public function konsederan_rkpdes($data){
        $spreadsheet = new Spreadsheet();
        // return $data;

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('EVALUASI RKPDes ')
            ->setSubject('EVALUASI RKPDes ')
            ->setDescription('EVALUASI RKPDes ')
            ->setKeywords('pdf php')
            ->setCategory('RKPDes');
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
        $sheet->setCellValue('A1', 'FORMAT KONSEDERAN RKPDes');

        $sheet->setCellValue('A3', 'Nama Perangkat Desa / Unit Kerja : ')->getColumnDimension('A')->setWidth(45);
        $sheet->setCellValue('B3', $data->unit_kerja)->getColumnDimension('B')->setWidth(35);
        $sheet->setCellValue('A4', 'Tahun  : ');
        $sheet->setCellValue('B4', $data->tahun);
        $sheet->setCellValue('A6', 'Nama Kepala : ');
        $sheet->setCellValue('B6', $data->nama_kepala);
        $sheet->setCellValue('A7', 'Jabatan Kepala : ');
        $sheet->setCellValue('B7', $data->jabatan_kepala);

        $sheet->setCellValue('A9', 'No');
        $sheet->setCellValue('B9','Indikator');
        $sheet->setCellValue('C9', 'Kesesuaian')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D9','Tindak Lanjut')->getColumnDimension('D')->setWidth(25);;

        $cell = 11;

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
