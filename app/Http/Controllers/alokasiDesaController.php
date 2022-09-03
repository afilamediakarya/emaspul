<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alokasi_desa;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Auth;
use DB;
class alokasiDesaController extends Controller
{
    public function index(){
        $breadcumb = 'Daftar Alokasi Desa';
        $current_breadcumb = '';
        $role = Auth::user()->id_role;
        return view('module.desa.alokasi',compact('breadcumb','current_breadcumb','role'));
    }

    public function datatable_list(){
        $jenis = request('jenis');
        $data = array();
        $tahun= session('tahun_penganggaran');
        $nama_desa=array();

       
        if (Auth::user()->id_role == 3) {
            $data = alokasi_desa::where('tahun',$tahun)->where('id_perangkat_desa',Auth::user()->id_unit_kerja)->latest()->get();
            $nama_desa = DB::table('user')->select('perangkat_desa.nama_desa')->join('perangkat_desa','user.id_unit_kerja','perangkat_desa.id')->where('user.id',Auth::user()->id)->first();
        }else{
            $data = DB::select("SELECT alokasi_desa.id, alokasi_desa.nama_paket, alokasi_desa.volume,alokasi_desa.satuan, alokasi_desa.pagu, alokasi_desa.sumber_dana, alokasi_desa.lokasi,alokasi_desa.sumber_dana, desa.nama FROM alokasi_desa INNER JOIN perangkat_desa ON alokasi_desa.id_perangkat_desa = perangkat_desa.id INNER JOIN desa ON perangkat_desa.id_desa = desa.id WHERE alokasi_desa.tahun=".$tahun);
        }

        

        if ($jenis == 'datatable') {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data
            ]);
        }else{
            return $this->export_alokasi_desa($data,$tahun,$nama_desa);
        }
    }

    public function export_alokasi_desa($data,$tahun,$nama_desa){
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('AFILA')
            ->setLastModifiedBy('AFILA')
            ->setTitle('Alokasi Dana Desa')
            ->setSubject('Alokasi Dana Desa')
            ->setDescription('Alokasi Dana Desa')
            ->setKeywords('pdf php')
            ->setCategory('Alokasi Dana Desa');
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

        $sheet->setCellValue('A1', 'DAFTAR ALOKASI DANA DESA '.strtoupper($nama_desa->nama_desa))->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'TAHUN ANGGARAN '.$tahun)->mergeCells('A1:F1');
        $sheet->setCellValue('A3', ' ')->mergeCells('A2:F2');
       
        
        $sheet->setCellValue('A4','NO')->getColumnDimension('A')->setWidth(5);
        $sheet->setCellValue('B4','NAMA KEGIATAN')->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C4','VOLUME')->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D4','PAGU')->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E4','SUMBER DANA')->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('F4','LOKASI')->getColumnDimension('E')->setWidth(20);
        
        $sheet->getStyle('A4:F4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        

        $cell = 5;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $cell, $key+1);
            $sheet->setCellValue('B' . $cell, $value->nama_paket);
            $sheet->setCellValue('C' . $cell, $value->volume. ' ' .$value->satuan);
            $sheet->setCellValue('D' . $cell, number_format($value->pagu));
            $sheet->setCellValue('E' . $cell, $value->sumber_dana);
            $sheet->setCellValue('F' . $cell, $value->lokasi);
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
        $sheet->getStyle('A1:F4')->getFont()->setBold(true);
        $sheet->getStyle('A1:F'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('B5:B'. $cell )->getAlignment()->setVertical('top')->setHorizontal('left');
        $sheet->getStyle('D5:D'. $cell )->getAlignment()->setVertical('top')->setHorizontal('right');
        

        $sheet->setCellValue('A' . ++$cell, '');
        $sheet->setCellValue('A' . ++$cell, 'Dicetak melalui ' . url()->current())->mergeCells('A' . $cell . ':F' . $cell);
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
            'lokasi' => 'required',
            'sumber_dana' => 'required'
        ]);

        $data = new alokasi_desa();
        $data->nama_paket = $request->nama_paket;
        $data->volume = $request->volume;
        $data->satuan = $request->satuan;
        $data->pagu = str_replace(',', '', $request->pagu);
        $data->lokasi = $request->lokasi;
        $data->sumber_dana = $request->sumber_dana;
        $data->id_perangkat_desa = Auth::user()->id_unit_kerja;
        $data->tahun = session('tahun_penganggaran');
        $data->user_insert = Auth::user()->id;
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
            'lokasi' => 'required',
            'sumber_dana' => 'required'
        ]);

        $data = alokasi_desa::where('id',$params)->first();
        $data->nama_paket = $request->nama_paket;
        $data->volume = $request->volume;
        $data->satuan = $request->satuan;
        $data->pagu = str_replace(',', '', $request->pagu);
        $data->lokasi = $request->lokasi;
        $data->sumber_dana = $request->sumber_dana;
        $data->tahun = session('tahun_penganggaran');
        $data->user_update = Auth::user()->id;
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
