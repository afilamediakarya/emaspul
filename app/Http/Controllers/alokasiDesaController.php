<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alokasi_desa;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class alokasiDesaController extends Controller
{
    public function index(){
        $breadcumb = 'Daftar Alokasi Desa';
        $current_breadcumb = '';
        return view('module.desa.alokasi',compact('breadcumb','current_breadcumb'));
    }

    public function datatable_list(){
        $jenis = request('jenis');
        $data = alokasi_desa::latest()->get();

        if ($jenis == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data
            ]);
        }else{
            return $this->export_alokasi_desa($data);
        }
    }

    public function export_alokasi_desa($data){
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

        $sheet->setCellValue('A1','Nama Paket');
        $sheet->setCellValue('B1','Volume');
        $sheet->setCellValue('C1','Pagu');
        $sheet->setCellValue('D1','Lokasi');

        $cell = 2;

        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $cell, $value->nama_paket);
            $sheet->setCellValue('B' . $cell, $value->volume. ' ' .$value->satuan);
            $sheet->setCellValue('C' . $cell, 'Rp. '.number_format($value->pagu));
            $sheet->setCellValue('D' . $cell, $value->lokasi);
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
            'nama_paket' => 'required',
            'volume' => 'required',
            'satuan' => 'required',
            'pagu' => 'required',
            'lokasi' => 'required'
        ]);

        $data = new alokasi_desa();
        $data->nama_paket = $request->nama_paket;
        $data->volume = $request->volume;
        $data->satuan = $request->satuan;
        $data->pagu = $request->pagu;
        $data->lokasi = $request->lokasi;
        $data->tahun = session('tahun_penganggaran');
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Alokasi Desa Berhasil di Tambahkan',
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Alokasi Desa Gagal di Proses',
            ]);
        }
    }

    public function byParams($params){
        $data = alokasi_desa::where('id',$params)->first();

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
            'nama_paket' => 'required',
            'volume' => 'required',
            'satuan' => 'required',
            'pagu' => 'required',
            'lokasi' => 'required'
        ]);

        $data = alokasi_desa::where('id',$params)->first();
        $data->nama_paket = $request->nama_paket;
        $data->volume = $request->volume;
        $data->satuan = $request->satuan;
        $data->pagu = $request->pagu;
        $data->lokasi = $request->lokasi;
        $data->tahun = session('tahun_penganggaran');
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Alokasi Desa Berhasil di Update',
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Alokasi Desa Gagal di Proses',
            ]);
        }
    }
}
