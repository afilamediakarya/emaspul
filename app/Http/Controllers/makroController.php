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
        $tes = [];
        $tahun = session('tahun_penganggaran') + 1;
     
        $role = Auth::user()->id_role;
        return view('module.admin.kinerja_makro',compact('breadcumb','current_breadcumb','tahun','role'));
    }

    public function datatable_list(){
        $type = request('type');
        // return indikator_makro::select('*')->selectRaw("WHERE 2022 BETWEEN indikator_makro.periode_awal AND indikator_makro.periode_akhir")->latest()->get();
        $back_year = session('tahun_penganggaran') - 1;
        $data = DB::select("SELECT id, indikator_makro FROM indikator_makro WHERE ".session('tahun_penganggaran')." BETWEEN indikator_makro.periode_awal AND indikator_makro.periode_akhir");

        // $keys = '';
        foreach ($data as $key => $value) {
            $sub = DB::table("data_makro")->select('id','tahun','target','realisasi')->where('id_indikator_makro',$value->id)->get();

            foreach ($sub as $i => $v) {
                $target_key = 'target'.$i;
                $realisasi_key = 'realisasi'.$i;

               $value->{$target_key} = $v->target;
               $value->{$realisasi_key} = $v->realisasi; 
            }
        }

        

        if ($type == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data,
            ]);
        }else{
            return $this->export_kinerja_makro($data,$type);
        }
        
    }

    public function export_kinerja_makro($data,$type){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Capaian Kinerja Makro Enrekang')
            ->setSubject('Capaian Kinerja Makro Enrekang')
            ->setDescription('Capaian Kinerja Makro Enrekang')
            ->setKeywords('pdf php')
            ->setCategory('Capaian Kinerja Makro Enrekang');
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
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.5);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.5);
        $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setWrapText(true);

        $sheet->setCellValue('A1', 'CAPAIAN KINERJA MAKRO')->mergeCells('A1:L1');
        $sheet->setCellValue('A2', ' ')->mergeCells('A2:L2');
       
        
        $sheet->setCellValue('A4','No')->mergeCells('A4:A5')->getColumnDimension('A')->setWidth(5);
        $sheet->setCellValue('B4','INDIKATOR')->mergeCells('B4:B5')->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C4','TARGET ')->mergeCells('C4:G4');
        $sheet->setCellValue('H4','CAPAIAN')->mergeCells('H4:L4');

        $sheet->setCellValue('C5','2019')->getColumnDimension('C')->setWidth(8);
        $sheet->setCellValue('D5','2020')->getColumnDimension('D')->setWidth(8);
        $sheet->setCellValue('E5','2021')->getColumnDimension('E')->setWidth(8);
        $sheet->setCellValue('F5','2022')->getColumnDimension('F')->setWidth(8);
        $sheet->setCellValue('G5','2023')->getColumnDimension('G')->setWidth(8);

        $sheet->setCellValue('H5','2019')->getColumnDimension('H')->setWidth(8);
        $sheet->setCellValue('I5','2020')->getColumnDimension('I')->setWidth(8);
        $sheet->setCellValue('J5','2021')->getColumnDimension('J')->setWidth(8);
        $sheet->setCellValue('K5','2022')->getColumnDimension('K')->setWidth(8);
        $sheet->setCellValue('L5','2023')->getColumnDimension('L')->setWidth(8);
        $sheet->getStyle('A4:L5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        
        $cell = 6;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $cell, $key+1);
            $sheet->setCellValue('B' . $cell, $value->indikator_makro);

            $sheet->setCellValue('C' . $cell, $value->target1);
            $sheet->setCellValue('D' . $cell, $value->target2);
            $sheet->setCellValue('E' . $cell, $value->target3);
            $sheet->setCellValue('F' . $cell, $value->target4);
            $sheet->setCellValue('G' . $cell, $value->target5);

            $sheet->setCellValue('H' . $cell, $value->realisasi1);
            $sheet->setCellValue('I' . $cell, $value->realisasi2);
            $sheet->setCellValue('J' . $cell, $value->realisasi3);
            $sheet->setCellValue('K' . $cell, $value->realisasi4);
            $sheet->setCellValue('L' . $cell, $value->realisasi5);
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

        $sheet->getStyle('A4:L'. $cell )->applyFromArray($border);
        $sheet->getStyle('A1:L5')->getFont()->setBold(true);
        $sheet->getStyle('A1:L'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B6:B'. $cell )->getAlignment()->setVertical('top')->setHorizontal('left');
        

        $sheet->setCellValue('A' . ++$cell, '');
      
        if ($type == 'export') {
            $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':L' . $cell);
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
        }else{
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="kinerja makro.xlsx"');
        }

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

    public function byParams($params){
        $data = indikator_makro::select('id','indikator_makro','periode_akhir','periode_awal')->where('id',$params)->first();
        $data->data_makro = data_makro::where('id_indikator_makro',$data->id)->get();
        $data->periode = $data->periode_awal . ' - ' . $data->periode_akhir;
        // $data->roles = ['id_role'=>$data->id_role, 'perangkat_bidang'=>$data->id_unit_kerja];
       
        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'data' => $data,
            ]);
        }
    }

    public function update(Request $request, $params){
        $validated = $request->validate([
            'indikator_makro' => 'required',
            'periode' => 'required',
        ]);

        $data = indikator_makro::where('id',$params)->first();
        $data->indikator_makro = $request->indikator_makro;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->user_update = Auth::user()->id;
        $data->save();
        if ($data) {
            foreach ($request->target as $key => $value) {    
                $makro = data_makro::where('id',$request->id_data_makro[$key])->first();
                $makro->id_indikator_makro = $data->id;
                $makro->target = $value;
                $makro->realisasi = $request->realisasi[$key];
                $makro->tahun = $request->tahun[$key];
                $makro->user_update = Auth::user()->id;
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
