<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\indikator_makro;
use App\Models\data_makro;
use Auth;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class makroController extends Controller
{
    public function index(){
        $breadcumb = 'Dokumen Kinerja Makro';
        $current_breadcumb = '';
        $tahun = session('tahun_penganggaran') - 1;
        $tes = [];
        return view('module.admin.kinerja_makro',compact('breadcumb','current_breadcumb','tahun'));
    }

    public function datatable_list(){
        $type = request('type');
        // return indikator_makro::select('*')->selectRaw("WHERE 2022 BETWEEN indikator_makro.periode_awal AND indikator_makro.periode_akhir")->latest()->get();
        $back_year = session('tahun_penganggaran') - 1;
        $data = DB::select("SELECT id, indikator_makro FROM indikator_makro WHERE ".session('tahun_penganggaran')." BETWEEN indikator_makro.periode_awal AND indikator_makro.periode_akhir");

        $keys = '';
        foreach ($data as $key => $value) {
            for ($i= 0; $i < 2; $i++) { 
                $keys = 'child'.$i;
                $value->{$keys} = DB::table('data_makro')->select('target','realisasi')->where('id_indikator_makro',$value->id)->where('tahun',$back_year + $i)->first();
            }
        }

        if ($type == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data,
            ]);
        }else{
            return $this->export_kinerja_makro($data);
        }
        
    }

    public function export_kinerja_makro($data){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Alokasi Desa')
            ->setSubject('Alokasi Desa')
            ->setDescription('Alokasi Desa')
            ->setKeywords('pdf php')
            ->setCategory('Alokasi Desa');
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

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Indikator');
        $sheet->setCellValue('C1','Target');
        $sheet->setCellValue('D1','Realisasi');
        $sheet->setCellValue('E1','Target');
        $sheet->setCellValue('F1','Realisasi');

        $cell = 2;

        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $cell, $key+1);
            $sheet->setCellValue('B' . $cell, $value->indikator_makro);
            $sheet->setCellValue('C' . $cell, $value->child0->target);
            $sheet->setCellValue('D' . $cell, $value->child0->realisasi);
            $sheet->setCellValue('E' . $cell, $value->child1->target);
            $sheet->setCellValue('F' . $cell, $value->child1->realisasi);
        }

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

    public function store(Request $request){
        $validated = $request->validate([
            'indikator_makro' => 'required',
            'periode' => 'required',
        ]);

        $data = new indikator_makro();
        $data->indikator_makro = $request->indikator_makro;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->user_insert = Auth::user()->id;
        $data->save();
        if ($data) {
            foreach ($request->target as $key => $value) {    
                $makro = new data_makro();
                $makro->id_indikator_makro = $data->id;
                $makro->target = $value;
                $makro->realisasi = $request->realisasi[$key];
                $makro->tahun = $request->tahun[$key];
                $makro->user_insert = Auth::user()->id;
                $makro->save();
            }
        }

        return response()->json([
            'type' => 'success',
            'status' => true,
            'data' => $data
        ]);
        
    }
}
