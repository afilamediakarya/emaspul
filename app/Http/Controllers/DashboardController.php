<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard_admin(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '';
        return view('module.dashboard.index',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_opd(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        return view('module.dashboard.opd',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_desa(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        return view('module.dashboard.desa',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_verifikator(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        return view('module.dashboard.verifikator',compact('breadcumb','current_breadcumb'));
    }

    public function get_dashboard(){
      
        $current = Auth::user()->id_role;
        $data = array();
        if ($current == '3') {
            $data = DB::select("select COUNT(*) AS jumlah_paket, SUM(pagu) AS total_pagu_paket from alokasi_desa where tahun=".
            session('tahun_penganggaran')." and id_perangkat_desa=".Auth::user()->id_unit_kerja);

            $add = DB::table('pagu_desa')->select('pagu_desa')->where('id_perangkat_desa',Auth::user()->id_unit_kerja)->first()->pagu_desa;
            // $data[0]['total_add'] =;
            foreach ($data as $key => $value) {
               $value->total_add =  $add;
            }

            
        }else if($current == '4'){
        

            // $document = DB::select("SELECT COUNT(*) AS jml_terverifikasi FROM documents WHERE status_document=4 AND id_verifikator=".Auth::user()->id." AND jenis_document <= 4 AND tahun=".session('tahun_penganggaran'));

            $data['jml_skpd'] = DB::table('unit_bidang_verifikasi')->where('id_bidang',Auth::user()->id_unit_kerja)->get()->count();
            $data['jml_desa'] = DB::table('perangkat_desa')->get()->count();
            $data['jml_dokumen'] = DB::table('documents')->select('documents.id')->join('unit_bidang_verifikasi','unit_bidang_verifikasi.id_perangkat','=','documents.id_perangkat')->where('documents.jenis_document','<=','4')->where('documents.tahun',session('tahun_penganggaran'))->where('documents.id_verifikator',Auth::user()->id)->get()->count();
            $data['jml_terverifikasi'] = DB::table('documents')->where('status_document',4)->where('id_verifikator',Auth::user()->id)->where('tahun',session('tahun_penganggaran'))->count();

        }else if($current == '2'){
          
            $counts = array();
            $total_pagu = 0;

            $result = [];
            $where_unit_kerja = " AND id=".Auth::user()->id_unit_kerja;
            // $paguPaket = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<> AND id=".Auth::user()->id_unit_kerja)->get();
            $paguPaket = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<>'' $where_unit_kerja")->get();
            $tahun = session('tahun_penganggaran');

            $tahun = session('tahun_penganggaran');
            $type = request('type');
     
             foreach($paguPaket as $unit_kerja_list){
                 $unit_kerja_list->Dpa=DB::table('dpa')
                 ->join('sub_kegiatan', 'dpa.id_sub_kegiatan', '=', 'sub_kegiatan.id')
                 ->select('dpa.id','dpa.nilai_pagu_dpa','sub_kegiatan.nama_sub_kegiatan')
                 ->where('dpa.tahun',$tahun)
                 ->where('dpa.id_unit_kerja',$unit_kerja_list->id)
                 ->get();
                 foreach ( $unit_kerja_list->Dpa as $dpa ){
                 $dpa->Paket=DB::table('paket_dak')
                     ->selectRaw("paket_dak.id,paket_dak.satuan,paket_dak.id_sumber_dana_dpa,paket_dak.nama_paket,paket_dak.volume,paket_dak.anggaran_dak as pagu,@keterangan:='' as keterangan,@jenis_paket:='dak' as jenis_paket,sumber_dana_dpa.sumber_dana")
                     ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dak.id_sumber_dana_dpa')
                     ->whereRaw("paket_dak.id_dpa='$dpa->id' AND sumber_dana_dpa.jenis_belanja='Belanja Modal'")
                     ->get();
                     foreach($dpa->Paket as $paket){
                             $paket->Lokasi=DB::table('paket_dak_lokasi')
                             ->join('desa','paket_dak_lokasi.id_desa','=','desa.id')
                             ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                             ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                             ->whereRaw("id_paket_dak='$paket->id'")
                             ->get();
                         $paket->Desa='';
                         $paket->Kecamatan='';
                         foreach($paket->Lokasi as $lokasi){
                             $paket->Desa.=$lokasi->nama_desa;
                             $paket->Kecamatan.=$lokasi->nama_kecamatan;
     
                         }
                     }
                 }
     
             }
     
             foreach ( $paguPaket as $key => $res ){ 
                 foreach ($res->Dpa as $dpa ){
                    foreach ( $dpa->Paket as $i => $paket ){
                        if($paket->Desa=='' || $paket->Kecamatan==''){
                             continue; 
                         }

                        $counts[] = $res->nama_unit_kerja;
                        $total_pagu += $paket->pagu;
                    
                    }
                 }
             }


            $data['jml_dokumen'] = DB::table('documents')->select('documents.id')->where('user_insert',Auth::user()->id)->where('documents.tahun',session('tahun_penganggaran'))->get()->count();
            $data['jml_paket'] = count($counts);
            $data['total_pagu'] = 'Rp. '.number_format($total_pagu);
        }else{

            $document = DB::select("SELECT COUNT(*) AS jml_documents, (SELECT COUNT(*) FROM documents WHERE status_document=4 AND jenis_document <= 4 AND tahun=".session('tahun_penganggaran').") AS jml_terverifikasi FROM documents WHERE jenis_document<=4 AND tahun=".session('tahun_penganggaran'));

            // return $document;

            $data['jml_skpd'] = DB::table('unit_kerja')->select('id')->get()->count();
            $data['jml_desa'] = DB::table('perangkat_desa')->get()->count();
            $data['jml_document'] = $document[0]->jml_documents;
            $data['jml_terverifikasi'] = $document[0]->jml_terverifikasi;
        }

        return response()->json($data);
    }

    public function export(){
        $type = request('type');
        $document_type = request('document_type');
        $data = array();
        if (Auth::user()->id_role == 4) {
  
            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,documents.user_insert,documents.id_verifikator, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents INNER JOIN unit_bidang_verifikasi ON unit_bidang_verifikasi.id_perangkat = documents.id_perangkat WHERE documents.jenis_document <= 4 AND documents.tahun=".session('tahun_penganggaran')." AND documents.id_verifikator=".Auth::user()->id);

            foreach ($data as $key => $value) {
                if (strpos($value->nama_documents, 'Renstra') !== false || strpos($value->nama_documents, 'Renja') !== false) {
                    $value->unit_kerja = DB::table('unit_kerja')->select('unit_kerja.nama_unit_kerja')->join('user','user.id_unit_kerja','=','unit_kerja.id')->where('user.id',$value->user_insert)->first()->nama_unit_kerja;
                }else{
                    $value->unit_kerja = DB::table('perangkat_desa')->select('perangkat_desa.nama_desa')->join('user','user.id_unit_kerja','=','perangkat_desa.id')->where('user.id',$value->user_insert)->first()->nama_desa;
                    
                }
            }

        }else{

            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,documents.user_insert, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents WHERE documents.jenis_document <= ".$document_type." AND documents.tahun=".session('tahun_penganggaran'));

            foreach ($data as $key => $value) {
                if (strpos($value->nama_documents, 'Renstra') !== false || strpos($value->nama_documents, 'Renja') !== false) {
                    $value->unit_kerja = DB::table('unit_kerja')->select('unit_kerja.nama_unit_kerja')->join('user','user.id_unit_kerja','=','unit_kerja.id')->where('user.id',$value->user_insert)->first()->nama_unit_kerja;
                }else{
                    $value->unit_kerja = DB::table('perangkat_desa')->select('perangkat_desa.nama_desa')->join('user','user.id_unit_kerja','=','perangkat_desa.id')->where('user.id',$value->user_insert)->first()->nama_desa;
                    
                }
            }
        }

        return $this->export_table_admin($data,$type);
    }

    public function export_table_admin($data,$type){
        // return $data;
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

        $sheet->setCellValue('A1', 'PROGRESS DOKUMEN')->mergeCells('A1:E1');
        $sheet->setCellValue('A2', ' ')->mergeCells('A2:E2');
       
        
        $sheet->setCellValue('A4','No')->getColumnDimension('A')->setWidth(5);
        $sheet->setCellValue('B4','Nama Unit Kerja')->getColumnDimension('B')->setWidth(20);
        $sheet->setCellValue('C4','Nama Dokumen ')->getColumnDimension('C')->setWidth(20);
        $sheet->setCellValue('D4','Verifikator')->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E4','Status')->getColumnDimension('E')->setWidth(20);

        $sheet->getStyle('A4:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
        
        $cell = 6;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $cell, $key+1);
            $sheet->setCellValue('B' . $cell, $value->unit_kerja);

            $sheet->setCellValue('C' . $cell, $value->nama_documents);
            $sheet->setCellValue('D' . $cell, $value->verifikator);

            $status = '';
            if ($value->status_document == '1') {
                $status = 'Belum Verifikasi';
            }else if($value->status_document == '2'){
                $status = 'Perbaikan';
            }else if($value->status_document == '3'){
                $status = 'Belum Selesai';
            }else{
                $status = 'Selesai';
            }
         
            $sheet->setCellValue('E' . $cell, $status);
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

        $sheet->getStyle('A4:E'. $cell )->applyFromArray($border);
        $sheet->getStyle('A1:E5')->getFont()->setBold(true);
        $sheet->getStyle('A1:E'. $cell )->getAlignment()->setVertical('center')->setHorizontal('center');
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
            header('Content-Disposition: attachment;filename="progress_dokumen.xlsx"');
        }

            $writer->save('php://output');
            exit;
    }

    public function export_table_verifikator($data){
        
    }
}
