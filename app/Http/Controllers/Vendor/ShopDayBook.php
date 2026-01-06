<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\DailyStockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShopDayBook extends Controller
{
    private $sum_case = [
                        'net'=>"CASE  WHEN status = '0' THEN -net WHEN status = '1' THEN net ELSE 0 END",
                        'count'=>"CASE  WHEN status = '0' THEN -count WHEN status = '1' THEN count ELSE 0 END",
                        'cost'=>"CASE  WHEN status = '0' THEN -value WHEN status = '1' THEN value ELSE 0 END"
                    ];
    public function feed(Request $request){
        if($request->ajax() && $request->method()=='post'){

        }else{
            return view('vendors.settings.daybook.initiate');
        }
    }
    public function detailview(Request $request){
        if($request->ajax()){ 
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);           
            $summery = "";
            if($request->place){
                $sum_data = $this->getsummerydata($request);
                $date = $request->date??false;
                $summery = view('vendors.settings.daybook.summerypage.pagedata',compact('sum_data','date'))->render();
            }
            $columns = "";
            $data_txn = $this->gettransactions($request);
            $data = $data_txn['data'];
            //dd($data->toArray());

            $open_txn = $data_txn['open_txn'];
            //$data = DailyStockTransaction::where(['branch_id'=>auth()->user()->branch_id,'date'=>$request->date])->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.settings.daybook.detailbody',compact('data','open_txn'))->render();
            //$paging = view('layouts.theme.datatable.pagination', ['paginator' => $data,'type'=>1])->render();
            $paging = null;
            return response()->json(['html'=>$html,'paging'=>$paging,'summery'=>$summery]);
        }else{
            $date = $request->date??date('Y-m-d',strtotime('now'));
            return view('vendors.settings.daybook.detail',compact('date'));
        }
    }

    public function exportdaysheet(Request $request){
        $request->place  = 'yes';
        $date = $request->date??false;
        $sum_data = $this->getsummerydata($request);
        $data_txn = $this->gettransactions($request);
        $source = $this->getsourcesummery($request);
        $data = $data_txn['data'];
        $open_txn = $data_txn['open_txn'];
        $file_name = "Day_Book_TXN_A4_date_{$request->date}( ".date('d-M-Y h-i-a')." ).pdf";
        require_once base_path('app/Services/dompdf/autoload.inc.php');
        $dompdf = new \Dompdf\Dompdf();
        //$customPaper = [0, 0, 216, 576];
        //$dompdf->setPaper($customPaper);
        $html = view("vendors.settings.daybook.export", compact('date','sum_data','source','data_txn'))->render();
        //echo $html;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=$file_name");
    }

    private function gettransactions($request){
        $date = $request->date??false;
        if($date){
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1); 
            $data_query = DailyStockTransaction::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
            $columns = "source,reference,created_at,updated_at,status,action,
                        (case when object = 'gold'  then type else null end) as gold_type,
                        (case when object = 'gold'  then karet else null end) as karet,
                        (case when object = 'gold' and status='1' then net else null end) as gold_plus,
                        (case when object = 'gold' and status='0' then net else null end) as gold_minus,
                        (case when object = 'gold' and status='N' then net else null end) as gold_null,

                         -- GOLD BALANCE
                        (CASE 
                            WHEN object='gold' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='gold'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS gold_balance,


                        (case when object = 'silver' then type else null end) as silver_type,
                        (case when object = 'silver' then purity else null end) as purity,
                        (case when object = 'silver' and status='1' then net else null end) as silver_plus,
                        (case when object = 'silver' and status='0' then net else null end) as silver_minus,
                        (case when object = 'silver' and status='N' then net else null end) as silver_null,


                         -- SILVER BALANCE
                        (CASE 
                            WHEN object='silver' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='silver'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS silver_balance,

                        (case when object = 'stone' and status='1' then count else null end) as stone_plus,
                        (case when object = 'stone' and status='0' then count else null end) as stone_minus,
                        (case when object = 'stone' and status='N' then count else null end) as stone_null,

                        -- STONE BALANCE
                        (CASE 
                            WHEN object='stone' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='stone'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS stone_balance,

                        (case when object = 'artificial' and status='1' then count else null end) as art_plus,
                        (case when object = 'artificial' and status='0' then count else null end) as art_minus,
                        (case when object = 'artificial' and status='N' then count else null end) as art_null,


                        -- ARTIFICIAL BALANCE
                        (CASE 
                            WHEN object='artificial' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='artificial'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS artificial_balance,

                        
                        (case when object = 'franchise' and status='1' then count else null end) as frnch_plus,
                        (case when object = 'franchise' and status='0' then count else null end) as frnch_minus,
                        (case when object = 'franchise' and status='N' then count else null end) as frnch_null,


                        -- FRANCHISE BALANCE
                        (CASE 
                            WHEN object='franchise' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='franchise'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS franchise_balance,

                        (case when object = 'money' then type else null end) as money_type,
                        (case when object = 'money' and status='1' then value else null end) as amnt_plus,
                        (case when object = 'money' and status='0' then value else null end) as amnt_minus,
                        (case when object = 'money' and status='N' then value else null end) as amnt_null,

                         -- MONEY BALANCE
                        (CASE 
                            WHEN object='money' THEN balance_value
                            ELSE (
                                SELECT balance_value FROM daily_stock_transactions s
                                WHERE s.id < daily_stock_transactions.id AND s.object='money'
                                ORDER BY s.id DESC LIMIT 1
                            )
                        END) AS money_balance";
                         
            if($request->source){
                $src_arr = ["sell"=>'sll','purchase'=>'prc','udhar'=>'udh','cut'=>'cut','create'=>'ins','import'=>'imp','scheme'=>'sch'];
                //$data_query->addSelectRaw();
                $data_query->where('source',$src_arr[$request->source]);
            }
            $request->place = true;
            $open_txn = $this->getsummeryrecord($request,(clone $data_query));
            $data_query->where('date',$date);
            //$data = $data_query->selectRaw($columns)->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
            $data = $data_query->selectRaw($columns)->orderBy('id', 'desc')->orderByRaw("CASE action 
                WHEN 'E' THEN 3
                WHEN 'U' THEN 2
                ELSE 1
            END")->orderBy('id', 'desc')->get();
            return ['data'=>$data,'open_txn'=>$open_txn];
        }else{
            echo "Invalid Operation !";
        }
    }

    public function getsourcesummerydata(Request $request){
        if($request->ajax()){
            $data = $this->getsourcesummery($request);
            echo view('vendors.settings.daybook.summerypage.source',compact('data'));
        }else{
            echo '<p class="col-12 text-center text-danger">Invalid Operation !</p>';
        }
    }

    private function getsourcesummery($request){
        $date = $request->date??date('Y-m-d',strtotime('now'));
        $data_query = DailyStockTransaction::where(['branch_id'=>auth()->user()->branch_id,'date'=>$date]);
        
        $columns = "source,
            SUM(CASE WHEN object='gold' AND status = '1' THEN net ELSE 0 END) AS gold_net_plus,
            SUM(CASE WHEN object='gold' AND status = '0' THEN net ELSE 0 END) AS gold_net_minus,

            SUM(CASE WHEN object='silver' AND status = '1' THEN net ELSE 0 END) AS silver_net_plus,
            SUM(CASE WHEN object='silver' AND status = '0' THEN net ELSE 0 END) AS silver_net_minus,

            SUM(CASE WHEN object = 'stone' AND status = '1' THEN count ELSE 0 END) AS stone_count_plus,
            SUM(CASE WHEN object = 'stone' AND status = '0' THEN count ELSE 0 END) AS stone_count_minus,
            SUM(CASE WHEN object = 'stone' AND status = '1' THEN value ELSE 0 END) AS stone_cost_plus,
            SUM(CASE WHEN object = 'stone' AND status = '0' THEN value ELSE 0 END) AS stone_cost_minus,

            SUM(CASE WHEN object = 'artificial' AND status = '1' THEN count ELSE 0 END) AS art_count_plus,
            SUM(CASE WHEN object = 'artificial' AND status = '0' THEN count ELSE 0 END) AS art_count_minus,
            SUM(CASE WHEN object = 'artificial' AND status = '1' THEN value ELSE 0 END) AS art_cost_plus,
            SUM(CASE WHEN object = 'artificial' AND status = '0' THEN value ELSE 0 END) AS art_cost_minus,

            SUM(CASE WHEN object = 'money' AND status = '1' THEN value ELSE 0 END) AS money_sum_plus,
            SUM(CASE WHEN object = 'money' AND status = '0' THEN value ELSE 0 END) AS money_sum_minus";
        return $data_query->selectraw($columns)->groupBY('source')->get()->keyBy('source');
    }

    public function getsummerydata(Request $request){
        $date = $request->date??date('Y-m-d',strtotime('now'));
        $target= $request->target??null;
        $data_query = DailyStockTransaction::where(['branch_id'=>auth()->user()->branch_id]);
        $data = null;
        switch($target){
            case 'open':
            case 'close':
                $data = $this->getopeningclosingrecord($request,$data_query);
                break;
            default:
                $data = $this->getsummeryrecord($request,$data_query);
                break;
        }
        //dd($data);
        if(!$request->place){
            $page = (!$target=='' || in_array(strtolower($target),['open','close']))?'openclose':'summery';
            return view("vendors.settings.daybook.summerypage.{$page}",compact('data','target','date'))->render();
        }else{
            return $data;
        }
    }

    private function getopeningclosingrecord($request,$query){
        $target = strtolower($request->target);
        $date = $request->date??date('Y-m-d',strtotime('now'));
        $columns = "source,
                SUM(CASE WHEN object='gold' AND status = '1' THEN net ELSE 0 END) AS gold_net_plus,
                SUM(CASE WHEN object='gold' AND status = '0' THEN net ELSE 0 END) AS gold_net_minus,
    
                SUM(CASE WHEN object='silver' AND status = '1' THEN net ELSE 0 END) AS silver_net_plus,
                SUM(CASE WHEN object='silver' AND status = '0' THEN net ELSE 0 END) AS silver_net_minus,

                SUM(CASE WHEN object = 'stone' AND status = '1' THEN count ELSE 0 END) AS stone_count_plus,
                SUM(CASE WHEN object = 'stone' AND status = '0' THEN count ELSE 0 END) AS stone_cost_minus,

                SUM(CASE WHEN object = 'artificial' AND status = '1' THEN count ELSE 0 END) AS art_count_plus,
                SUM(CASE WHEN object = 'artificial' AND status = '0' THEN count ELSE 0 END) AS art_cost_minus,
    
                SUM(CASE WHEN object = 'money' AND holder='shop' AND status = '1' THEN value ELSE 0 END) AS money_shop_plus,
                SUM(CASE WHEN object = 'money' AND holder='shop' AND status = '0' THEN value ELSE 0 END) AS money_shop_minus,
                SUM(CASE WHEN object = 'money' AND holder='bank' AND status = '1' THEN value ELSE 0 END) AS money_bank_plus,
                SUM(CASE WHEN object = 'money' AND holder='bank' AND status = '0' THEN value ELSE 0 END) AS money_bank_minus";
        $query->selectraw($columns);
        if($target=='open'){
            $date = date('Y-m-d',strtotime("{$date}-1 day"));
        }
       return  $query->where('date','<=',$date)->groupBY('source')->get()->keyBy('source');
    }

    private function getsummeryrecord($request,$query){
        $date =  $request->date;
        if($request->place){
            $common_sel = " 
                SUM(
                    CASE 
                        WHEN object = 'gold' THEN
                                {$this->sum_case['net']}
                        ELSE 0
                    END
                    ) as gold_net_sum,
                SUM(
                    CASE 
                        WHEN object = 'silver' THEN
                           {$this->sum_case['net']}
                        ELSE 0
                    END
                    ) as silver_net_sum,
                SUM(
                    CASE 
                        WHEN object = 'stone' THEN
                           {$this->sum_case['count']}
                        ELSE 0
                    END
                    ) as stone_count_sum,
                SUM(
                    CASE 
                        WHEN object = 'stone' THEN
                            {$this->sum_case['cost']}
                        ELSE 0
                    END
                    ) as stone_cost_sum,
                SUM(
                    CASE 
                        WHEN object = 'artificial' THEN
                            {$this->sum_case['count']}
                        ELSE 0
                    END
                    ) as art_count_sum,
                SUM(
                    CASE 
                        WHEN object = 'artificial'  THEN
                            {$this->sum_case['cost']}
                        ELSE 0
                    END
                    ) as art_cost_sum,
                SUM(
                    CASE 
                        WHEN object = 'money'  THEN
                            {$this->sum_case['cost']}
                        ELSE 0
                    END
                    ) as money_sum";
            $txn = (clone $query)->selectRaw($common_sel)->where('date',$date)->first();
            $open = (clone $query)->selectRaw($common_sel)->where('date','<',$date)->first();
            return ['txn'=>$txn,'open'=>$open];
        }else{
            $common_sel = " 
                SUM(
                    CASE 
                        WHEN object = 'gold'  AND type='usual' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_jewellery_net,
                SUM(
                    CASE 
                        WHEN object = 'gold'  AND type='loose' THEN 
                           {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_loose_net,
                SUM(
                    CASE 
                        WHEN object = 'gold'  AND type='bullion' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_bullion_net ,
                SUM(
                    CASE 
                        WHEN object = 'gold'  AND type='other' THEN 
                           {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_other_net,
                SUM(
                    CASE 
                        WHEN object = 'gold'  AND type='old' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_old_net,
                
                SUM(
                    CASE 
                        WHEN object = 'silver'  AND type='usual' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS silver_jewellery_net,
                SUM(
                    CASE 
                        WHEN object = 'silver'  AND type='loose' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS silver_loose_net,
                SUM(
                    CASE 
                        WHEN object = 'silver'  AND type='bullion' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS silver_bullion_net,
                SUM(
                    CASE 
                        WHEN object = 'silver'  AND type='other' THEN 
                           {$this->sum_case['net']}
                        ELSE 0 
                    END) AS silver_other_net,
                SUM(
                    CASE 
                        WHEN object = 'silver'  AND type='old' THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS silver_old_net,
    
                SUM(
                    CASE 
                        WHEN object = 'stone'  THEN    
                            {$this->sum_case['count']}
                        ELSE 0 
                    END) AS stone_count,
                SUM(
                    CASE 
                        WHEN object = 'stone'  THEN    
                            {$this->sum_case['cost']}
                        ELSE 0 
                    END) AS stone_cost,
                SUM(
                    CASE 
                        WHEN object = 'artificial'  THEN    
                           {$this->sum_case['count']}
                        ELSE 0 
                    END) AS artificial_count, 
                SUM(
                    CASE 
                        WHEN object = 'artificial'  THEN    
                            {$this->sum_case['cost']}
                        ELSE 0 
                    END) AS artificial_cost, 
                SUM(
                    CASE 
                        WHEN object = 'money'  AND holder='shop' THEN    
                            {$this->sum_case['cost']}
                        ELSE 0 
                    END) AS money_cash,
                SUM(
                    CASE 
                        WHEN object = 'money'  AND holder ='bank' THEN    
                            {$this->sum_case['cost']}
                        ELSE 0 
                    END) AS money_bank
            ";
            $curr_summery = (clone $query)->selectRaw($common_sel)->where('date',$date)->first();
            $prev_closing = (clone $query)->selectRaw($common_sel)->where('date','<',$date)->first();
            return ['curr'=>$curr_summery,'prev'=>$prev_closing];
        }
    }

    public function list(Request $request){
        if($request->ajax()){
            $perPage = $request->input('entries');
            $currentPage = $request->input('page', 1);
    
            $day_book_q = DailyStockTransaction::where(['branch_id'=>auth()->user()->branch_id]);
            $data = $day_book_q->selectRaw("
                DATE(date) as entry_date,
                SUM(
                    CASE 
                        WHEN object = 'gold'   THEN 
                            {$this->sum_case['net']}
                        ELSE 0 
                    END) AS gold_net,
                SUM(
                    CASE 
                        WHEN object = 'silver' THEN 
                        {$this->sum_case['net']}  
                        ELSE 0 
                    END) AS silver_net,
    
                SUM(
                    CASE 
                        WHEN object = 'stone'  THEN 
                        {$this->sum_case['count']}  
                        ELSE 0 
                    END) AS stone_count,
                SUM(
                    CASE 
                        WHEN object = 'artificial' THEN 
                        {$this->sum_case['count']}  
                        ELSE 0 
                    END) AS artificial_count,
    
                SUM(
                    CASE 
                        WHEN object = 'money' THEN 
                        {$this->sum_case['cost']}  
                        ELSE 0 
                    END) AS money_val
            ")
            ->groupBy('entry_date')
            ->orderBy('entry_date', 'desc')
            ->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.settings.daybook.listbody',compact('data'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $data,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.settings.daybook.list');
        }
    }

}
