<?php

namespace App\Http\Controllers;

use App\Libraries\MasterIndia\EwayBills;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PdfData;
use Debugbar;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfToCsvController extends Controller {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('auth');
  }


public function convertPdfData(Request $request){
    
    $path = public_path('pdf-to-excel-tool\pdf.py');
    $command = escapeshellcmd($path); 
    //$output = shell_exec("py ".$command);
    $output = exec("py ".$command);
    //echo "<pre>";print_r($output);die;
    $success = true;
    return view('pdf-data',["path"=>$path],compact('success')); 
}

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function convertPdfToCsv(Request $request) {

        ini_set('memory_limit', -1);
        $csv_folder = public_path('pdf-to-excel-tool/excel_output');
        $csv_files = scandir($csv_folder);
        
        $insert_data_array = [];

        $i = 0;
        foreach ( $csv_files as $file ) {

          $check_ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        
          if($check_ext == "csv"){
            
            $file_name = basename(pathinfo($file,PATHINFO_BASENAME));
            
            $type_of_file = explode("_",$file_name);

            $type_of_file = array_shift($type_of_file);

            $lines = explode(PHP_EOL, file_get_contents($csv_folder.'/'.$file));

            if(isset($lines[1])){
            
            $array = array();
    
            $get_first_line = str_getcsv($lines[1]);

            $gem_search_array = ['RA Technical End Date/Time','Bid End Date/Time'];
            
            if(isset($get_first_line[0]) && in_array($get_first_line[0],$gem_search_array) || strtolower($type_of_file) == 'gem')
            { 
                // bid end time
                $bid_end_date_time_array = isset($lines[1]) ? str_getcsv($lines[1]) : [];
                $bid_end_date_time_array = array_filter(str_getcsv($lines[1]));
                $bid_end_time = array_pop($bid_end_date_time_array) ?? "";

                // bid opening time
                $bid_opening_date_time_array = isset($lines[2]) ? str_getcsv($lines[2]) : [];
                $bid_opening_date_time_array = array_filter(str_getcsv($lines[2]));
                $bid_opening_time = array_pop($bid_opening_date_time_array) ?? "";

                // ministry/state name
                $ministry_state_name_array = isset($lines[3]) ? str_getcsv($lines[5]) : [];
                $ministry_state_name_array = array_filter(str_getcsv($lines[5]));
                $ministry_state_name = array_pop($ministry_state_name_array) ?? "";

                // department_name
                $department_name_array = isset($lines[6]) ? str_getcsv($lines[6]) : [];
                $department_name_array = array_filter(str_getcsv($lines[6]));
                $department_name = array_pop($department_name_array) ?? "";

                // organization_name
                $organization_name_array = isset($lines[7]) ? str_getcsv($lines[7]) : [];
                $organization_name_array = array_filter(str_getcsv($lines[7]));
                $organization_name = array_pop($organization_name_array) ?? "";

                // Item category
                $string = 'Item Category';
                $item_category_value = '';
                foreach($lines as $line_val){
                    if (str_contains($line_val, $string)) { 
                        $category = array_filter(str_getcsv($line_val));
                        $item_category_value = array_pop($category);
                    }
                }
                
                // Estimated_bid_value
                $estimated_bid_value = '';
                foreach ($lines as $key => $line) {
                    if (str_contains($line, 'Estimated Bid Value')) { 
                        $estimated_bid = array_filter(str_getcsv($line));
                        $estimated_bid_value = array_pop($estimated_bid);
                    }
                }

                $array['bid_end_time'] = $bid_end_time;
                $array['bid_opening_time'] = $bid_opening_time;
                $array['ministry_state_name'] = $ministry_state_name;
                $array['organization_name'] = $organization_name;
                $array['department_name'] = $department_name;
                $array['estimated_bid_value'] = $estimated_bid_value;
                //$array['item_category'] = $item_category_value;
                $array['item_category'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $item_category_value);
                $array['pdftype'] = 'gem';
                $variable = substr($file_name, 0, strpos($file_name, ".pdf"));
                $array['filename'] = $variable;
                $pos = strpos($variable, '_');
                if(preg_match('/_/', $variable)) {
                    $array['tender_no'] = strtoupper(str_replace('_', '/', $variable));
                }else {
                    $array['tender_no'] = strtoupper(str_replace('-', '/', $variable));
                }
                try{
                    PdfData::updateOrCreate(
                        ['bid_end_time' => $bid_end_time,'bid_opening_time' => $bid_opening_time,'ministry_state_name'=>$ministry_state_name,'organization_name'=>$organization_name,'estimated_bid_value'=>$estimated_bid_value],
                        $array
                    );
                }catch (\Illuminate\Database\QueryException $e) {
                    $this->error("DB ERROR::");
                    $error = $e->getMessage();
                    echo "<pre>";print_r($error);
                    echo "<pre>";print_r($array);die;
                }
            }
            else
            {
              
                $tender_number_data_array = str_getcsv($lines[2]);
                $tender_number = substr($tender_number_data_array[0],10,100);
                //Tender title

                $tender_title_data_array = str_getcsv($lines[18]);
                $tender_title = substr($tender_title_data_array[0],13,100);
                
                // Publishing Date

                $tender_publishing_date_data_array = str_getcsv($lines[8]);
                $tender_publishing_date_data_array = array_filter($tender_publishing_date_data_array);
                if(isset($tender_publishing_date_data_array[2])){
                    $data2 = $tender_publishing_date_data_array[2];
                }else{
                    $data2 = null; 
                }
                $tender_publishing_time = isset($tender_publishing_date_data_array[3]) ? $tender_publishing_date_data_array[3] : $data2; 

                // Closing Date

                $tender_closing_date_data_array = str_getcsv($lines[12]);
                if(isset($tender_closing_date_data_array[2])){
                    $closing_data2 = $tender_closing_date_data_array[2];
                }else{
                    $closing_data2 = null; 
                }
                $tender_closing_time = isset($tender_closing_date_data_array[3]) ? $tender_closing_date_data_array[3] : $closing_data2[2] ;
                
                $array['tender_no'] = $tender_number;
                $array['tender_title'] = $tender_title;
                $array['publishing_time'] = $tender_publishing_time;
                $array['closing_time'] = $tender_closing_time;
                $array['pdftype'] = 'iris';
                $array['filename'] = $file_name;

                PdfData::updateOrCreate(
                    ['tender_no' => $tender_number],
                    $array
              );  
            }

            }
            
          }
          
        }

        $success = true;
        return view('pdf-to-csv',compact('success')); 
  }

  public function generateCsv(Request $request){

    
    $find_records = PdfData::where('pdftype','iris')->select('tender_no','tender_title','publishing_time','closing_time')->get()->toArray();

    $columns = ['Tender No','Tender Title','Publishing Time','Closing Time'];
    
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.time().'pdfdata.csv";');

    $df = fopen("php://output", 'w');

    fputcsv($df, $columns);
    
    foreach ($find_records as $record) {
        // dd(array_keys($record));
        fputcsv($df, array_values($record));
    }

   fclose($df);

  }

  public function generateCsvGem(Request $request){

    
    $find_records = PdfData::where('pdftype','gem')
                   //->select('pdf_data.bid_end_time','pdf_data.bid_opening_time','pdf_data.ministry_state_name','pdf_data.organization_name','pdf_data.estimated_bid_value','store_url.url')
                   ->select('pdf_data.item_category','pdf_data.tender_no','pdf_data.ministry_state_name','pdf_data.organization_name','pdf_data.department_name','pdf_data.estimated_bid_value','pdf_data.bid_end_time','pdf_data.bid_opening_time','store_url.url')
                   ->join("store_url", "store_url.B_no", "=", "pdf_data.filename")
                   ->get()->toArray();

    
    $columns = ['Item Category','Tender No','Ministry State Name','Organization Time','Department Name','Estimated Bid Value','Bid End Time','Bid Opening Time','Url'];
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.time().'pdfdata.csv";');

    $df = fopen("php://output", 'w');

    fputcsv($df, $columns);
    
    foreach ($find_records as $record) {
        // dd(array_keys($record));
        fputcsv($df, array_values($record));
    }

   fclose($df);

  }

  
}
