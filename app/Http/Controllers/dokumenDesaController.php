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
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . $cell+1);
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':C' . $cell+1);
        $sheet->setCellValue('D'. $cell,'KESESUAIAN')->mergeCells('D'. $cell . ':E' . $cell);
        $sheet->setCellValue('D'. $cell+1,'ADA');
        $sheet->setCellValue('E'. $cell+1,'TIDAK ADA');
        $sheet->setCellValue('F'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('F'. $cell . ':F' . $cell+1);
        
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
        $sheet->setCellValue('A'.$cell,'NO')->mergeCells('A'. $cell . ':A' . $cell+1);
        $sheet->setCellValue('B'. $cell,'INDIKATOR')->mergeCells('B'. $cell . ':C' . $cell+1);
        $sheet->setCellValue('D'. $cell,'KESESUAIAN')->mergeCells('D'. $cell . ':E' . $cell);
        $sheet->setCellValue('D'. $cell+1,'ADA');
        $sheet->setCellValue('E'. $cell+1,'TIDAK ADA');
        $sheet->setCellValue('F'. $cell,'TINDAK LANJUT PENYEMPURNAAN')->mergeCells('F'. $cell . ':F' . $cell+1);
        
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
}
