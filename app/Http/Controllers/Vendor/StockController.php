<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\ItemAssocElement;
use App\Models\Purchase;
use App\Models\Counter;
use App\Models\Category;
use App\Models\StockItem;
use App\Models\StockItemGroup;
use App\Models\InventoryStock;
use App\Models\InventoryStockElement;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use App\Notifications\StockNotification;

class StockController extends Controller
{
    /**
     * 
     * START OF NEW STOCK CREATE
     * 
     */
	private $daily_txn_arr = [];
	
	public function newdashboard(Request $request,$stock=false,$item=false,$cat=false){
        switch($stock){
            case 'gold': 
            case 'silver': 
            case 'stone':
            case 'artificial':
			case 'franchise':
                return $this->dashboardstock($request,$stock,$item);
                break;
            default:
                return $this->dashboardhome();
                break;

        }
    }
	
	private function savestocktransaction($data = false,$store=true){
        $this->daily_txn_arr = ($data)?$data:$this->daily_txn_arr;
        //print_r($this->daily_txn_arr);
        $object = ($this->daily_txn_arr['stock_type'] == 'Franchise-Jewellery')?'franchise':$this->daily_txn_arr['stock_type'];
        $type = ($this->daily_txn_arr['entry_mode']=='tag' || (isset($this->daily_txn_arr['tag']) && $this->daily_txn_arr['tag']!=''))?'usual':(($this->daily_txn_arr['entry_mode']!='loose')?'other':'loose');
        //$property = [];
        //print_r($this->daily_txn_arr);
        if(in_array(strtolower($object),['gold','silver'])){
            $tunch = $this->daily_txn_arr['tunch']??null;
            $caret = $this->daily_txn_arr['caret']??null;
            if(empty($tunch) && !empty($caret)){
                $tunch = round(($this->daily_txn_arr['caret']/24)*100);
            }
            if(empty($caret) && !empty($tunch)){
                if(isset($this->daily_txn_arr['tunch'])){
                    $caret = round(($this->daily_txn_arr['tunch']/100)*24);
                }
            }
            $net = $this->daily_txn_arr['net']??null;
            $fine = $this->daily_txn_arr['fine']??null;
            //$property = [((!$store)?-$net:$net),((!$store)?-$fine:$fine),$tunch,$caret];
            $property = [$net,$fine,$tunch,$caret];
            
        }else{
            $count = $this->daily_txn_arr['count']??1;
            //$count = (!$store)?-$count:$count;
        }
        $total = $this->daily_txn_arr['total']??null;
        //$total = ($total)?((!$store)?-$total:$total):null;
        $status = ($this->daily_txn_arr['status']??1);
        $holder = ($this->daily_txn_arr['holder']??'shop');
        $valuation = [$total,$status,$holder];
        $source = [$this->daily_txn_arr['source'],$this->daily_txn_arr['entry_num']];
        $action = [($this->daily_txn_arr['action']??'A'),($this->daily_txn_arr['action_on']??null)];
        $stock_txn = ["object"=>[$object,$type,@$count],"valuation"=>@$valuation,"source"=>$source,'action'=>$action];
        if(isset($property)){
            $stock_txn['property'] = $property;
        }
        /*echo '<pre>';
        print_r($stock_txn);
        echo '</pre>';*/
        $dtxnsrvc = app("App\Services\DailyStockTransactionService");
        $response = $dtxnsrvc->savetransaction($stock_txn);
    }
	
    public function savethumbnail(Request $request){
        $image = $request->file('image');
        $sourcePath = $image->getRealPath();
        $file_name = $request->type."_".$request->item;
        //echo $file_name;
        [$width, $height, $type] = getimagesize($sourcePath);
        $target_dir = "assets/images/stockdashboard/".auth()->user()->shop_id."_".auth()->user()->branch_id;
        try{
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $img = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $img = imagecreatefrompng($sourcePath);
                    break;
                case IMAGETYPE_WEBP:
                    $img = imagecreatefromwebp($sourcePath);
                    break;
                default:
                    return response()->json(['status'=>false,"msg"=>"Unsupported image type !"]);
            }
			//echo public_path("{$target_dir}");
            if(!file_exists(public_path("{$target_dir}"))){
                mkdir(public_path("{$target_dir}"),0755,true);
            }
            $now_file = "{$target_dir}/{$file_name}.png";
            $destination = public_path($now_file);
            if(file_exists($destination)){
                unlink($destination);
            }
            imagepng($img, $destination, 6);
            imagedestroy($img);
            return response()->json(["status"=>true,"path"=>asset("{$now_file}")]);
        }catch(Exception $e){
            return response()->json(["status"=>false,"msg"=>"Operation Failed !".$e->getMessage()]);
        }
    }
	 
	private function dashboardhome(){
        $stocks = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
        //dd($stocks);
        //$raw_selection = 'SUM(avail_gross) as gross,SUM(avail_net) as net,SUM(avail_fine)  as fine';
		$raw_selection = 'SUM(avail_gross) as gross,SUM(avail_net) as net,ROUND(
							SUM(
								CASE 
									WHEN tunch IS NOT NULL AND tunch != 0 
										THEN (avail_net * tunch) / 100
									ELSE (avail_net * (caret / 24))
								END
							),
							3
						) as fine';
        $gold = (clone $stocks)->where('stock_type','Gold')->selectRaw($raw_selection)->where('avail_net','>',0)->first();
        $silver = (clone $stocks)->where('stock_type','Silver')->selectRaw($raw_selection)->where('avail_net','>',0)->first();
		
		$artificial = (clone $stocks)->where('stock_type','Artificial')->selectRaw('count(id) as num,SUM(avail_count * rate) as total,SUM(avail_count) as count')->where('avail_count','>',0)->first();
		
        //$stone = (clone $stocks)->where('stock_type','Stone')->selectRaw('count(id) as num,sum(avail_net) as net, SUM(total)  as total')->where('avail_net','>',0)->first();
        $stone = (clone $stocks)->where('stock_type','Stone')->selectRaw('count(id) as num,sum(avail_net) as net,SUM(avail_count * rate) as total')->where('avail_net','>',0)->first();
		
		$franchise = (clone $stocks)->where('stock_type','Franchise-Jewellery')->selectRaw('count(id) as num,sum(avail_count) as count,sum(avail_net) as net,sum(avail_count * rate) as total')->first();
		
        $dash_arr = ['Gold'=>$gold,"Silver"=>$silver,"Artificial"=>$artificial,"Stone"=>$stone,"Franchise"=>$franchise];
        return view("vendors.stocks.newpage.dashboard.newdashboard",compact('dash_arr'));
    }

    private function dashboardstock(Request $request,$stock,$item=false){
        $stock_data = null;
        if($stock){
			$common_cond = "";
            $common_cond = ['inventory_stocks.shop_id'=>auth()->user()->shop_id,'inventory_stocks.branch_id'=>auth()->user()->branch_id];
            /*$stock_query = InventoryStock::join('stock_item_group as ig', 'inventory_stocks.group_id', '=', 'ig.id')->select(
                    DB::raw('COUNT(inventory_stocks.id) as total_count'),
                    DB::raw('SUM(inventory_stocks.avail_gross) as total_gross'),
                    DB::raw('SUM(inventory_stocks.avail_net) as total_net'),
                    DB::raw('SUM(inventory_stocks.avail_fine) as total_fine')
            )->where($common_cond)->where(function($smq){
				$smq->where('inventory_stocks.avail_net','>','0');
			});*/
			
			$stock_query = InventoryStock::join('stock_item_group as ig', 'inventory_stocks.group_id', '=', 'ig.id')->select( DB::raw('COUNT(inventory_stocks.id) as total_count'));
			if($stock=='artificial'){
                $stock_query->addselect(
                    DB::raw('SUM(inventory_stocks.avail_count) as total_piece'),
                    DB::raw('SUM((inventory_stocks.avail_count * inventory_stocks.rate)) as total_cost')
                );
            }else{
                $stock_query->addselect(
                    /*DB::raw('COUNT(inventory_stocks.id) as total_count'),*/
                    DB::raw('SUM(inventory_stocks.avail_gross) as total_gross'),
                    DB::raw('SUM(inventory_stocks.avail_net) as total_net'),
                    
                );
				if($stock=='stone'){
					$stock_query->addselect(DB::raw('ROUND( SUM(inventory_stocks.avail_count * inventory_stocks.rate),3 )  as total_cost'));
				}elseif($stock=='franchise'){
                    $stock_query->addSelect(
                        DB::raw('SUM(inventory_stocks.avail_count) as total_piece'),
                        DB::raw('SUM((inventory_stocks.avail_count * inventory_stocks.rate)) as total_cost')
                    );
                }else{
					$stock_query->addselect(
						DB::raw('ROUND(
							SUM(
								CASE 
									WHEN inventory_stocks.tunch IS NOT NULL AND inventory_stocks.tunch != 0 
										THEN (inventory_stocks.avail_net * inventory_stocks.tunch) / 100
									ELSE (inventory_stocks.avail_net * (inventory_stocks.caret / 24))
								END
							),
							3
						)  as total_fine')
					);
				}
            }
			$stock_query->where($common_cond)->where(function($smq) use ($stock){
				if(in_array($stock,['artificial','franchise'])){
                    $smq->where('inventory_stocks.avail_count','>','0');
                }else{
                    $smq->where('inventory_stocks.avail_net','>','0');
                }
			});
			
			$stock = ($stock=='franchise')?'Franchise-Jewellery':$stock;
            $cat_name = str_replace('-'," ",$stock);
			
			$item_list = StockItemGroup::select('coll_name')->where('cat_name',"{$cat_name}")->groupBy('coll_name')->get();
			if($request->filter){
                if($request->caret || $request->start || $request->start){
                    $start = ($request->start??$request->end)??false;
                    $end = ($request->end??$request->start)??false;
					$stock_query->addSelect('ig.coll_name');
                    if($request->item){
                        $stock_query->where('ig.coll_name',"{$request->item}");
                    }
                    if($request->caret){
                        $stock_query->where('inventory_stocks.caret',"{$request->caret}");
                    }
                    if($start && $end){
                        $stock_query->whereBetween('inventory_stocks.avail_net',[$start,$end]);
                    }
                }
                $stock_data = $stock_query->where('ig.cat_name', "{$cat_name}")->groupBy('ig.coll_name')->get();
                //echo $stock_query->toSql();
                return response()->json(['data'=>$stock_data]);
            }else{
				/*$stock_query->where(function($ssq){
					$ssq->whereNotNull('tag')->orWhere('tag',"!=","")->orWhere('count','=','1');
				});*/
				if(!$item){
					$stock_query->addSelect('ig.coll_name')->where('ig.cat_name', "{$cat_name}")->groupBy('ig.coll_name');
					//echo $stock_query->toSql();
					$stock_data = $stock_query->get();
					return view("vendors.stocks.newpage.dashboard.stockdashboard",compact('stock','item_list','stock_data'));
				}else{
					$stock_query->addSelect('inventory_stocks.caret')->where(['ig.cat_name'=>"{$cat_name}","ig.coll_name"=>"{$item}"])->groupBy('inventory_stocks.caret');
					$stock_data = $stock_query->get();
					return view("vendors.stocks.newpage.dashboard.stockitemdetailpopup",compact('stock','item','stock_data'))->render();
				}
			}
        }else{
            echo "Invalid Operation !";
        }
    }

	//----Grouped View Inventory--------------------//
    private function getgroupinventotystock(Request $request){
		$stock_title = str_replace([' ','_'],"-",strtolower($request->stock));
        $inventory_stock_query = InventoryStock::select(
                                    'name',
                                    'caret',
                                    'item_id',
                                    'stock_type',
                                    DB::raw('COUNT(id) as total_count'),
                                    DB::raw('SUM(avail_gross) as total_avail_gross'),
                                    DB::raw('SUM(gross) as total_gross'),
                                    DB::raw('SUM(avail_count) as total_avail_num'),
                                    DB::raw('SUM(count) as total_num'),
                                    DB::raw('SUM(avail_net) as total_avail_net'),
                                    DB::raw('SUM(net) as total_net'),
                                    DB::raw('ROUND(
													SUM(
														CASE 
															WHEN tunch IS NOT NULL AND tunch != 0 
																THEN (avail_net * tunch)/100
															ELSE (avail_net * (caret/24))
														END
													),
													3
												) as total_avail_fine'),
                                    DB::raw('SUM(fine) as total_fine'),
									DB::raw('SUM(rate) as total_rate'),
									DB::raw('SUM(total) as total_sum')
									)
                                ->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
            $inventory_stock_query->where('stock_type',$stock_title);
            if($request->item_type){
                 $inventory_stock_query->whereHas('itemgroup',function($groupq) use ($request){
                    $groupq->where('coll_name',$request->item_type);
                 });
            }
            if($request->stock_type){
                $inventory_stock_query->where('entry_mode',$request->stock_type);
            }
			if($request->status){
				$status = (strtolower($request->status)=='avail')?'>':'=';
				$check_column = ($stock_title=='artificial')?'avail_count':'avail_net';
				$inventory_stock_query->where("{$check_column}","{$status}",0);
			}
            if($request->caret  && $stock_title=='gold'){
                $inventory_stock_query->where('caret',$request->caret);
            }
			if($request->keyword){
                $inventory_stock_query->where('name','like',"%{$request->keyword}%");
            }
            $inventory_stock_query->groupBy('name','caret','item_id','stock_type')->orderBy('name');
			//echo $inventory_stock_query->toSql();
			return $inventory_stock_query;
    }

    public function groupinventory(Request $request,$sub=false){
        //$form = ($request->stock && $request->stock!="stone")?'metal':$request->stock;
		$form = 'metal';
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
			$stock_title = str_replace([' ','_'],'-',strtolower($request->stock)); 
			$stock_status = strtolower($request->status);
            $stock_query = $this->getgroupinventotystock($request);
			$stock_sum = (clone $stock_query)
						->getQuery() // converts to Query\Builder
						->cloneWithout(['columns', 'groups', 'orders']) // keep WHERE, drop select/group/order
						->selectRaw('SUM(avail_gross) as sum_gross, SUM(avail_net) as sum_net,
						ROUND(SUM(CASE WHEN tunch IS NOT NULL AND tunch != 0  THEN (avail_net * tunch) / 100 ELSE (avail_net * (caret / 24))
								END),3) as sum_fine,
						SUM(avail_count) as sum_count,SUM(avail_count * rate) as sum_total')
						->first();
			$sum_block = view("vendors.stocks.newpage.stockpages.inventory.stocksumblock",compact('stock_sum','stock_title'))->render();
            $stocks = $stock_query->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view("vendors.stocks.newpage.stockpages.inventory.{$form}groupinventorybody",compact('stocks','stock_title','stock_status'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
			
            return response()->json(['html'=>$html,'paging'=>$paging,'sum_block'=>$sum_block]);
        }else{
            if(view()->exists("vendors.stocks.newpage.stockpages.inventory.{$form}groupinventory")){
                //$jewelleries = StockItemGroup::select('cat_name','coll_name')->whereIn('cat_name',['Gold','Silver','Stone'])->groupby('cat_name', 'coll_name')->get()->groupBy('cat_name');
                $jewelleries = StockItemGroup::select('cat_name','coll_name')->get()->groupBy('cat_name');
                $stock_cat = $request->stock;
				$sub = $request->item_type??false;
                $caret = $request->caret??false;
                return view("vendors.stocks.newpage.stockpages.inventory.{$form}groupinventory",compact('stock_cat','sub','caret','jewelleries'));
            }else{
                echo "Invalid Operation !";
            }
        }
    }

    public function groupinventoryexport(Request $request,$type=false){
		//print_r($request->toArray());
        //exit();
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $stocks = $this->getgroupinventotystock($request)->orderby('name','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
        //dd($stocks->currentPage());
        //$stocks = $this->getinventotystock($request)->orderby('name','ASC')->get();
        $export = false;
        $page_e = method_exists($stocks, 'currentPage')?$stocks->currentPage(): false;
		$stock_title = strtolower($request->stock);
        $data = [];
        if($request->stock){
            array_push($data,ucfirst($request->stock));
        }
        if($request->stock_type){
            array_push($data,ucfirst($request->stock_type));
        }
        $page_append = ($page_e)?"_PAGE#{$page_e}":"";
        switch($type){
            case 'excel':
                $file_name = "STOCK_";
                if(!empty($data)){
                    $string = implode('_',$data);
                    $file_name .= "{$string}_";
                }
                $file_name .= "( ".date('d-M-Y h-i-a')." ){$page_append}.csv";
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$file_name\"");
                $output = fopen("php://output", "w");
                 // Add CSV column headers
                fputcsv($output, ['SN','NAME','KARET',"PIECE",'GROSS','NET','FINE','RATE','TOTAL']);
                $unit_arr = ['w'=>'gm','p'=>'%','r'=>'Rs'];
                foreach ($stocks as $stki=>$stock) {
                    fputcsv($output, [
                        $stki+1,
                        @$stock->name,
                        @$stock->caret,
                        @$stock->total_count,
                        @$stock->total_gross,
                        @$stock->total_net,
                        @$stock->total_fine,
                        @$stock->rate,
                        @$stock->total,
                    ]);
                }
                fclose($output);
                break;
            case 'pdf':
                $export = 'pdf';
                $file_name = "{$request->stock}_{$request->stock_type}_STOCK_( ".date('d-M-Y h-i-a')." ){$page_append}.pdf";
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                //$customPaper = [0, 0, 216, 576];
                $dompdf->setPaper('A4', 'portrait');
                $html = view("vendors.stocks.newpage.stockpages.inventory.metalgroupinventorybody", compact('stocks','export','data','page_e','stock_title'))->render();
                //echo $html;
                $dompdf->loadHtml($html);
                $dompdf->render();
                return response($dompdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', "inline; filename=$file_name");
                break;
            default:
                break;
        }
    }
	
/*--------------INVENTORY JUMP FROM GROUP-------------------------------------*/
	private function getiteminventory(Request $request,$cat_title=false){
		$item_code = $request->code;
		$iteminventoryquery = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'item_id'=>$item_code]);
		
		//print_r($request->toArray());
		if($request->status){
			/*$check_column = ($cat_title && $cat_title=='artificial')?'avail_count':'avail_net';
			$check_case = (strtolower($request->status) =='avail')?'!=':'=';
			$iteminventoryquery->where("{$check_column}","{$check_case}",0);*/
			
			$check_column = ($cat_title && $cat_title=='artificial')?'avail_count':'avail_net';
            if(strtolower($request->status) =='avail'){
                $iteminventoryquery->where("{$check_column}","!=",0);
            }else{
                $ini_column = str_replace('avail_','',$check_column);
                $iteminventoryquery->whereColumn("{$check_column}","<","{$ini_column}");
            }
		}
		if($request->stock_type && $request->stock_type !='all'){
			$iteminventoryquery->where('entry_mode',$request->stock_type);
		}
		
		if($request->start_wght!="" && $request->end_wght!= ""){
			if($request->start_wght <= $request->end_wght){
				$iteminventoryquery->whereBetween('avail_net',[$request->start_wght,$request->end_wght]);
			}
		}
		//echo $iteminventoryquery->toSql();
		return $iteminventoryquery;
	}

	public function iteminventory(Request $request){
		$item = StockItem::find($request->code);
        if(!empty($item)){
            $stock_cat = strtolower($item->itemgroup->cat_name);
			if($request->ajax()){
				$avail = strtolower($request->status);
				$perPage = $request->input('entries') ;
				$currentPage = $request->input('page', 1);
				$iteminventoryquery = $this->getiteminventory($request,$stock_cat);
				$stock_sum = (clone $iteminventoryquery)
							->getQuery() // converts to Query\Builder
							->cloneWithout(['columns', 'groups', 'orders']) // keep WHERE, drop select/group/order
							->selectRaw('SUM(avail_gross) as sum_gross, SUM(avail_net) as sum_net, ROUND(
							SUM(
								CASE 
									WHEN tunch IS NOT NULL AND tunch != 0 
										THEN (avail_net * tunch)/100
									ELSE (avail_net * (caret/24))
								END
							),
							3
						) as sum_fine,SUM(avail_count) as sum_count,SUM(avail_count * rate) as sum_total')->first();
				$item_data = $iteminventoryquery->paginate($perPage, ['*'], 'page', $currentPage);
				$stock_title = str_replace([' ','_'],'-',$stock_cat);
				$sum_block = view("vendors.stocks.newpage.stockpages.inventory.stocksumblock",compact('stock_sum','stock_title'))->render();
				$html = view("vendors.stocks.newpage.stockpages.inventory.iteminventorybody",compact('item_data','avail','stock_cat'))->render();
				$paging = view('layouts.theme.datatable.pagination', ['paginator' => $item_data,'type'=>1])->render();
				
				return response()->json(['html'=>$html,'paging'=>$paging,'stock_sum'=>$stock_sum,'sum_block'=>$sum_block]);
			}else{
				$item = StockItem::find($request->code);
				$stock_cat = $item->itemgroup->cat_name;
				//dd($stock_cat);
				return view("vendors.stocks.newpage.stockpages.inventory.iteminventory",compact('item','stock_cat'));
			}
		}else{
			echo "Invalid Data Selection !";
		}
        
    }

	public function iteminventoryexport(Request $request){
		$group = StockItem::find($request->code);
		//dd($group);
		$stock_cat = strtolower($group->itemgroup->cat_name);
		$perPage = $request->input('entries') ;
		$currentPage = $request->input('page', 1);
		$avail = strtolower($request->status);
		$iteminventoryquery = $this->getiteminventory($request,$stock_cat);
		$item_data = $iteminventoryquery->paginate($perPage, ['*'], 'page', $currentPage);
		$page_e = method_exists($item_data, 'currentPage')?$item_data->currentPage(): false;
		$data = [];
        if(!empty($group->itemgroup)){
			if($group->itemgroup->cat_name){
				array_push($data,ucfirst($group->itemgroup->cat_name));
			}
			if($group->itemgroup->coll_name){
				array_push($data,ucfirst($group->itemgroup->coll_name));
			}
        }
        if(!empty($group) && $group->item_name){
            array_push($data,ucfirst($group->item_name));
        }
		//dd($data);
		$page_append = ($page_e)?"_PAGE#{$page_e}":"";
		switch($request->export){
			case 'pdf':
				$export = 'pdf';
				$stock_name = (@$group->itemgroup->cat_name)?$group->itemgroup->cat_name."_":'';
				$stock_name.= (@$group->itemgroup->coll_name)?$group->itemgroup->coll_name."_":'';
				$stock_name.= (@$group->item_name)?"( {$group->item_name} )"."_":'';
                $file_name = "{$stock_name}{$request->stock_type}_STOCK_( ".date('d-M-Y h-i-a')." ){$page_append}.pdf";
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                //$customPaper = [0, 0, 216, 576];
                $dompdf->setPaper('A4', 'portrait');
                $html = view("vendors.stocks.newpage.stockpages.inventory.iteminventorybody", compact('item_data','export','data','page_e','avail','stock_cat'))->render();
                //echo $html;
                $dompdf->loadHtml($html);
                $dompdf->render();
                return response($dompdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', "inline; filename=$file_name");
                break;
				break;
			case 'excel':
				$file_name = "STOCK_";
                if(!empty($data)){
                    $string = implode('_',$data);
                    $file_name .= "{$string}_";
                }
				 $file_name .= "( ".date('d-M-Y h-i-a')." ){$page_append}.csv";
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$file_name\"");
                $output = fopen("php://output", "w");
                 // Add CSV column headers
                fputcsv($output, ['SN','NAME','TAG','HUID','KARET',"PIECE",'GROSS','LESS','NET','TUNCH','WASTAGE','FINE','ADDON_COST','LABOUR','LABOUR_UNIT','OTHER','RATE','DISCOUNT','DISCOUNT_UNIT','TOTAL']);
                $unit_arr = ['w'=>'gm','p'=>'%','r'=>'Rs'];
                foreach ($item_data as $stki=>$stock) {
                    fputcsv($output, [
                        $stki+1,
                        @$stock->name,
                        @$stock->tag,
                        @$stock->huid,
                        @$stock->caret,
                        @$stock->count,
                        @$stock->avail_gross,
                        @$stock->avail_less,
                        @$stock->avail_net,
                        @$stock->tunch,
                        @$stock->wastage,
                        @$stock->avail_fine,
                        @$stock->element_charge,
                        @$stock->labour,
						@$unit_arr[$stock->labour_unit],
                        @$stock->charge,
                        @$stock->rate,
                        @$stock->discount, 
						@$unit_arr[$stock->discount_unit],
                        @$stock->total,
                    ]);
                }
                fclose($output); 
				break;
			default:
				break;
		}
	}

/*--------------END INVENTORY JUMP FROM GROUP-------------------------------------*/
	private function getinventotystock(Request $request){
		$stock_query = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]);
		$stock_title = str_replace([' ','_'],"-",strtolower($request->stock));
		$stock_query->where('stock_type',"{$stock_title}");
		if($request->status){
			//$stock_status = strtolower($request->status);
			$check_column = ($stock_title=='artificial')?'avail_count':'avail_net';
			//$check_cond = ($stock_status=='avail')?'!=':'=';
			//$stock_query->where("{$check_column}","{$check_cond}",0);
			if(strtolower($request->status) =='avail'){
				$stock_query->where("{$check_column}","!=",0);
			}else{
				$ini_column = str_replace('avail_','',$check_column);
				$stock_query->whereColumn("{$check_column}","<","{$ini_column}");
			}
		}
		if($request->stock_type){
			$stock_query->where('entry_mode',$request->stock_type);
		}
		if($request->item_type){
			$item_type = $request->item_type;
			$stock_query->whereHas('itemgroup',function($grpq) use ($item_type){
				$grpq->where('coll_name',$item_type);
			});
		}
		if($request->caret && $stock_title=='gold'){
			$stock_query->where('caret',$request->caret);
		}
		if($request->keyword ){
			$stock_query->where('name','like',"%{$request->keyword}%");
		}
		if(($request->start_wght!="" && $request->end_wght!= "") && $stock_title !='artificial'){
			if($request->start_wght <= $request->end_wght){
				$stock_query->whereBetween('net',[$request->start_wght,$request->end_wght]);
			}
		}
		//echo $stock_query->toSql();
        return $stock_query;
    }

    public function inventory(Request $request,$sub=false){
        //echo $type;
        //$form = ($request->stock && $request->stock!="stone")?'metal':$request->stock;
		$form = 'metal';
		$stock_cat = strtolower($request->stock);
        if($request->ajax()){
			$avail = strtolower($request->status);
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $stock_query = $this->getinventotystock($request);
			$stock_sum_query = (clone $stock_query)
						->getQuery() // converts to Query\Builder
						->cloneWithout(['columns', 'groups', 'orders']); // keep WHERE, drop select/group/order
						if($stock_cat=='artificial'){
                            $stock_sum_query->selectRaw('SUM(avail_count) as sum_count,SUM(avail_count * rate) as sum_total');
                        }else{
                            $select_column = 'SUM(avail_gross) as sum_gross, SUM(avail_net) as sum_net';
                            if($stock_cat == 'stone' ){
								
                            }elseif($stock_cat=='franchise-jewellery'){
                                $select_column .= ', SUM(avail_count) as sum_count';
                            }else{
                               $select_column.=',ROUND(
                                                SUM(
                                                    CASE 
                                                        WHEN tunch IS NOT NULL AND tunch != 0 
                                                            THEN (avail_net * tunch) / 100
                                                        ELSE (avail_net * (caret / 24))
                                                    END
                                                ),3) as sum_fine';
                            }
                            $stock_sum_query->selectRaw($select_column);
                        }
						/*->selectRaw('SUM(gross) as sum_gross, SUM(net) as sum_net, SUM(fine) as sum_fine')
						->first();*/
			$stock_sum = $stock_sum_query->first();
			$stock_title = str_replace([' ','_'],'-',strtolower($stock_cat));
			$sum_block = view("vendors.stocks.newpage.stockpages.inventory.stocksumblock",compact('stock_sum','stock_title'))->render();
            $stocks = $stock_query->orderBy('name','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
            //dd($stocks);
            $html = view("vendors.stocks.newpage.stockpages.inventory.{$form}inventorybody",compact('stocks','avail','stock_cat'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
			
            return response()->json(['html'=>$html,'paging'=>$paging,'sum_block'=>$sum_block]);
        }else{
            if(view()->exists("vendors.stocks.newpage.stockpages.inventory.{$form}inventory")){
				$jewelleries = StockItemGroup::select('cat_name','coll_name')->whereIn('cat_name',['Gold','Silver'])->groupby('cat_name', 'coll_name')->get()->groupBy('cat_name');
                return view("vendors.stocks.newpage.stockpages.inventory.{$form}inventory",compact('stock_cat','sub','jewelleries'));
            }else{
                echo "Invalid Operation !";
            }
        }
    }

    public function csvsample(){
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Just_Udhari_Stock_Import_Sample_Csv.csv");
        $output = fopen("php://output", "w");
        fputcsv($output, ['NAME','TAG','HUID','KARET',"PIECE",'GROSS','LESS','NET','TUNCH','WASTAGE','FINE','ADDON_COST','LABOUR','LABOUR_UNIT','OTHER','RATE','DISCOUNT','DISCOUNT_UNIT','TOTAL']);
        fclose($output);
    }

    public function inventoryexport(Request $request,$type=false){  
		$perPage = $request->input('entries');
        $currentPage = $request->input('page',1);
		$stocks = $this->getinventotystock($request)->orderby('name','ASC')->paginate($perPage, ['*'], 'page', $currentPage);
        //$stocks = $this->getinventotystock($request)->orderby('name','ASC')->get();
		$avail = strtolower($request->status);
		$page_e = method_exists($stocks, 'currentPage')?$stocks->currentPage(): false;
		
        $export = false;
        $data = [];
        if($request->stock){
            array_push($data,ucfirst($request->stock));
        }
        if($request->stock_type){
            array_push($data,ucfirst($request->stock_type));
        }
		$page_append = ($page_e)?"_PAGE#{$page_e}":"";
        switch($type){
            case 'excel':
                $file_name = "STOCK_";
                if(!empty($data)){
                    $string = implode('_',$data);
                    $file_name .= "{$string}_";
                }
				 $file_name .= "( ".date('d-M-Y h-i-a')." ){$page_append}.csv";
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$file_name\"");
                $output = fopen("php://output", "w");
                 // Add CSV column headers
                fputcsv($output, ['SN','NAME','TAG','HUID','KARET',"PIECE",'GROSS','LESS','NET','TUNCH','WASTAGE','FINE','ADDON_COST','LABOUR','LABOUR_UNIT','OTHER','RATE','DISCOUNT','DISCOUNT_UNIT','TOTAL']);
                $unit_arr = ['w'=>'gm','p'=>'%','r'=>'Rs'];
                foreach ($stocks as $stki=>$stock) {
                    fputcsv($output, [
                        $stki+1,
                        @$stock->name,
                        @$stock->tag,
                        @$stock->huid,
                        @$stock->caret,
                        @$stock->count,
                        @$stock->avail_gross,
                        @$stock->avail_less,
                        @$stock->avail_net,
                        @$stock->tunch,
                        @$stock->wastage,
                        @$stock->avail_fine,
                        @$stock->element_charge,
                        @$stock->labour,
						@$unit_arr[$stock->labour_unit],
                        @$stock->charge,
                        @$stock->rate,
                        @$stock->discount, 
						@$unit_arr[$stock->discount_unit],
                        @$stock->total,
                    ]);
                }
                fclose($output); 
                break;
            case 'pdf':
                $export = 'pdf';
                $file_name = 
                $file_name = "{$request->stock}_{$request->stock_type}_STOCK_( ".date('d-M-Y h-i-a')." ){$page_append}.pdf";
                require_once base_path('app/Services/dompdf/autoload.inc.php');
                $dompdf = new \Dompdf\Dompdf();
                //$customPaper = [0, 0, 216, 576];
                $dompdf->setPaper('A4', 'portrait');
                $html = view("vendors.stocks.newpage.stockpages.inventory.metalinventorybody", compact('stocks','export','data','page_e','avail'))->render();
                //echo $html;
                $dompdf->loadHtml($html);
                $dompdf->render();
                return response($dompdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', "inline; filename=$file_name");
                break;
            default:
                break;
        }
    }

    public function importstock(Request $request){
        if($request->ajax()){
            $rules = [
                "stock"=>'required',
                "stock_type"=>'required|in:both,loose,tag',
				"group"=>'required',
                "csv_file"=>'required|file|mimes:csv',
            ];
            $msgs = [
                'stock.required'=>"Select The Stock !",
                'stock_type.required'=>'Select The Stock Type !',
                "stock_type.in"=>'Invalid Stock Type',
                /*"csv_file"=>'required|file|mimes:csv,txt|max:2048',*/
				"group.required"=>'Please Select The Item group !',
                'csv_file.required'=>'CSV File required !',
                'csv_file.file'=>'Please select the CSV File !',
                'csv_file.mimes:csv'=>'Invalid File Selected !',
            ];
            $validator = Validator::make($request->all(),$rules,$msgs);
            if($validator->fails()){
                return response()->json(['status'=>false,'errors'=>$validator->errors()]);
            }else{
                if($request->isMethod('post')){
                    if($request->hasFile('csv_file')){
                        $stock = $request->stock;
                        $stock_type = $request->stock_type;
                        $col_ref = [
                            "name"=>'name',
                            "tag"=>'tag',
                            "huid"=>'',
                            "karet"=>'caret',
                            'piece'=>'count',
                            "gross"=>'gross',
                            "less"=>'less',
                            "net"=>'net',
                            "tunch"=>'tunch',
                            "wastage"=>'wastage',
                            "fine"=>'fine',
                            "addon_cost"=>'element_charge',
                            "rate"=>'rate',
                            "labour"=>'labour',
                            "labour_unit"=>'labour_unit',
                            "other"=>'charge',
                            "discount"=>'discount',
                            "discount_unit"=>'discount_unit',
                            "total"=>'total',
                            "source"=>"imp",
							"colour"=>'colour',
                            "clearity"=>'clearity',
                            "remark"=>'remark',
                            'crt_no'=>'crt'
                        ];
                        $file = $request->file('csv_file')->getRealPath();
                        if (($handle = fopen($file, 'r')) !== false) {
                            $header = fgetcsv($handle, 1000, ','); // optional, read first row as header
                            $import_fail = $import_data = [];
                            $rows_count = $import_count = 0;
                            $entry_num = InventoryStock::where(['stock_type'=>$stock,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->max('entry_num');
                            $entry_num = ($entry_num)?$entry_num+1:1;
							
							$rows = array_map('str_getcsv', file($file));
                            $csv_value_data = array_slice($rows, 1); // skip header row

                            // 🧩 Choose column to check duplicates (for example, 'name')
                            $columnToCheck = 'tag';
                            $csv_heads_lower = array_map('strtolower', array_map('trim', $header));
                            $colIndex = array_search(strtolower($columnToCheck), $csv_heads_lower);

                            if ($colIndex !== false) {
                                $values = array_column($csv_value_data, $colIndex);
                                $values = array_filter(array_map(fn($v) => strtolower(trim($v)), $values));
                                $duplicateValues = array_unique(array_diff_assoc($values, array_unique($values)));
								if (!empty($duplicateValues)) {
                                    $tag_stream = implode(' | ',$duplicateValues);
                                    return response()->json(['status'=>false,'msg'=>"Tag Can't Be Repeat !<br><b>{$tag_stream}</b>"]);
                                }else{
                                    //--Now Check For DB Existance fotr Tag
                                    $db_exist = InventoryStock::whereIn('tag',$values)->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->pluck('tag')->unique()->values()->toArray();
                                    if(count($db_exist) > 0){
                                        $db_exist_tag = implode('|',$db_exist);
                                        return response()->json(['status'=>false,'msg'=>"Tag already in Use !<br><b>{$db_exist_tag}</b>"]);
                                    }
                                }
                            }
							
							$unit_arr = ['%'=>'p','rs'=>'r','gm'=>'w'];
							$group = $request->group??0;
							
							$csv_heads = array_map('strtolower', array_map('trim', $header));
							$needles = ($stock=='stone')?['gross','name']:['gross','net','name'];
							//echo count(array_intersect($needles, $csv_heads)).'<br>';
							//echo count($needles).'<br>';
							if (count(array_intersect($needles, $csv_heads)) === count($needles)) {
								$ref_colds = array_map('strtolower', array_keys($col_ref));
								$different = array_diff($csv_heads, $ref_colds);
								$col_err = "";
								if(!empty($different)){
									$col_err =implode('|',$different) ;
									return response()->json(['status'=>false,'msg'=>"Unknow Column <b>{$col_err}</b>"]);
								}else{
									while (($row = fgetcsv($handle, 1000, ',')) !== false) {
										$input_arr = [];
										if(count($row) > 0 ){
											$rows_count++;
											foreach($row as $coli=>$val){
												$head_title  = strtolower($header[$coli]);
												$db_col = $col_ref[$head_title];
												if($val!=""){
													if($head_title=='name'){
														$item_data = [
															"item_name"=>trim($val),
															"group_id"=>$group,
															"stock_method"=>$stock_type
														];
														$items = StockItem::where($item_data)->first();
														if(empty($items)){
															$item_data = array_merge($item_data,["shop_id"=>auth()->user()->shop_id,"branch_id"=>auth()->user()->branch_id]);
															$items = StockItem::create($item_data);
														}
													}
													if(!in_array($head_title,['labour_unit','discount_unit'])){
                                                        if($head_title=='karet'){
                                                            $input_arr["{$db_col}"] = str_replace(['K', 'k'],"",$val);
                                                        }elseif($head_title=='gross' || $head_title=='net' || $head_title=='fine' || $head_title=='less'){
                                                            $input_arr["{$db_col}"] = number_format($val,3);
                                                        }else{
														    $input_arr["{$db_col}"] = ($val!="")?$val:0;
                                                        }
													}else{
														$unit_val = strtolower($val);
														$input_arr["{$db_col}"] = ($val!="")?$unit_arr["{$unit_val}"]:0;
													}
												}										
											}
											if(!empty($input_arr)){
												$input_arr['net'] = number_format(($input_arr['net']??$input_arr['gross']),3);
												$fine = (isset($input_arr['fine']))?$input_arr['fine']:$input_arr['net'];
												if(isset($input_arr['caret'])){
													$tunch = round((100/24) * str_replace(['K', 'k'],"",$input_arr['caret']));
                                                    $fine_val = ($input_arr['net'] * $tunch)/100;
													//$fine_del_val =  $input_arr['net']*((100-$tunch)/100);
                                                    $fine = $fine_val;
													//$fine = $input_arr['net'] - $fine_del_val;
												}
												/*if(isset($input_arr['caret'])){
													//echo str_replace(['K', 'k'],"",$input_arr['caret']);
													//exit();
													$tunch = round((100/24) * str_replace(['K', 'k'],"",$input_arr['caret']));
													$fine_del_val =  $input_arr['net']*((100-$tunch)/100);
													$fine = $input_arr['net'] - $fine_del_val;
												}*/
												if(!isset($input_arr['less']) && ($input_arr['gross']!=$input_arr['net'])){
													$input_arr['less'] = $input_arr['gross'] - $input_arr['net'];
												}

}
												if(isset($input_arr['wastage']) && ($input_arr['wastage']!="")){
                                                    $fine = $fine*(1+($input_arr['wastage']/100));
                                                }
												$input_arr['fine'] = number_format($fine,3);
												$input_arr['stock_type'] = $stock; 
												$input_arr['entry_num'] = ($request->$entry_num??($entry_num??0));
												$input_arr['entry_mode'] = $stock_type;
												$input_arr['item_id'] = ($items)?$items->id:0;
												$input_arr['group_id'] = $group;
												$input_arr['shop_id'] = auth()->user()->shop_id;
												$input_arr['branch_id'] = auth()->user()->branch_id;
												$input_arr['avail_gross'] = number_format($input_arr['gross'],3); 
												$input_arr['avail_net'] = number_format($input_arr['net'],3); 
												$input_arr['avail_fine'] = number_format($input_arr['fine'],3); 
												$input_arr['source'] = 'imp';
												//$import_data[] = $input_arr;
												$imp_stock = InventoryStock::create($input_arr);
												if($imp_stock){
													$import_count++;
												}else{
												   $import_fail[] = $input_arr;
												}
											}
										}
									}
									fclose($handle);
									$msg = "";
									$bool = true;
									if($import_count>0){
										if($import_count==$rows_count){
											$msg = "Total {$import_count} {$stock} Stock Items Imported !";
										}else{
											$msg = "Total <b>{$import_count}</b> {$stock} Stock Items out of <b>{$rows_count}</b>  Imported !";
										}

                                  $data = [
                                    'title' => 'Stock Imported',
                                    'message' => 'Bulk stock imported successfully',
                                    'link' => route('stock.new.inventory'),
                                    'stock_type' => $request->stock_type
                                ];

                                auth()->user()->notify(new StockNotification($data));

										return response()->json(['status'=>$bool,'msg'=>$msg,'next'=>route('stock.new.inventory.import.preview',[$stock,$entry_num]),'data'=>$import_fail]);
									}else{
										$bool = false;
										$msg = "Importing Failed !";
										return response()->json(['status'=>$bool,'error'=>$msg]);
									}
								}
							}else{
								return response()->json(['status'=>false,'msg'=>".CSV must have Name|Gross|Net"]);
							} 
                        }
                    }
                }
            // print_r($request->toArray());
            // exit();
        }else{
			//--When Artificial Will Actiove The Funtionality--------------//
			//$groups = StockItemGroup::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->get()->groupBy('cat_name');
			$groups = StockItemGroup::whereIn('cat_name',['Gold','Silver','Stone'])->get()->groupBy('cat_name');
            $groups = StockItemGroup::where([
            'shop_id'=>auth()->user()->shop_id,
            'branch_id'=>auth()->user()->branch_id
        ])->get()->groupBy('cat_name');

        // 🔥 POPUP REQUEST (only section view)
        if ($request->has('popup')) {
            return view('vendors.stocks.newpage.importstock_popup', compact('groups'));
        }

        // Normal full page view
            return view('vendors.stocks.newpage.importstock', compact('groups'));
        }
    }

    public function importpreview(Request $request,$stock_type=false,$entry_num = false){
        if($request->ajax()){
            $perPage = $request->input('entries') ;
            $currentPage = $request->input('page', 1);
            $stocks = InventoryStock::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'stock_type'=>$stock_type,'entry_num'=>$entry_num,'source'=>'imp'])->paginate($perPage, ['*'], 'page', $currentPage);
            $html = view('vendors.stocks.newpage.importstockpreviewbody',compact('stocks'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
            //dd($stocks);
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.stocks.newpage.importstockpreview',compact('stock_type','entry_num'));

        }
    }

	public function allitemgroups(Request $request){
        if($request->ajax()){
            $currentpage = $request->input('page', 1);
            $perPage = $request->input('entries');
            $items_q = StockItem::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->with('itemgroup')->orderBy('created_at','ASC');
            if($request->keyword){
                $items_q->where(function ($sub_q) use ($request){
                    $sub_q->where('item_name','like',"{$request->keyword}%")->orwhereHas('itemgroup',function($grpq) use ($request){
                        $grpq->where('item_group_name','like',"{$request->keyword}%");
                        });
                });
            }
            
            if($request->cat || $request->type){
                $keyword = $request->keyword;
                $items_q->whereHas('itemgroup',function($gq) use ($request){
                    if($request->type){
                        $gq->where('cat_name',"{$request->type}");
                    }
                    if($request->cat){
                        $gq->where('coll_name',"{$request->cat}");
                    }
                });
            }
            $items = $items_q->paginate($perPage, ['*'], 'page', $currentpage);
            $html =  view('vendors.stocks.newpage.itemnameform.itemgrouplist',compact('items'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $items,'type'=>1])->render();
            return response()->json(['html'=>$html,'paging'=>$paging]);
        }else{
            return view('vendors.stocks.newpage.itemnameform.itemgroups');
        }
    }

    public function createitem(Request $request,$group=false){
        if($request->ajax()){
            if($group){
                // print_r($request->toArray());
                // exit();
                $rule = [
                    'item_group_cat'=>"required",
                    'item_group_col'=>"nullable",
					"item_group_name"=>['required','string',Rule::unique('stock_item_group','item_group_name')->where(fn($q)=>$q->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id]))],
                    /*'item_group_name'=>"required|string|unique:stock_item_group,item_group_name",*/
                ];
                $msgs = [
                    'item_group_cat.required'=>"Select Group Category !",
                    'item_group_col.required'=>"Select Group Collection or Add New",
                    'item_group_name.required'=>"Enter Item Group Title !",
                    'item_group_name.string'=>"Invalid Item Group Title !",
                    "item_group_name.unique"=>"Item Group Exist !"
                ];
                $validator = Validator::make($request->all(),$rule,$msgs);
                if($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }else{
                    DB::beginTransaction();
                    try{
                        $cat = $col = $cat_data = $coll_data =false;
                        $category = Category::where('category_level',1)->where('name',$request->item_group_cat)->orwhere('id',$request->item_group_cat)->first();
                        if(empty($category)){
                            $cat_input = [
                                "name"=>ucfirst($request->item_group_cat),
                                "category_level"=>1,
                                "slug"=>strtolower(str_replace(" ","-",$request->item_group_cat)),
                                "shop_id"=>auth()->user()->shop_id,
                                "branch_id"=>auth()->user()->branch_id,
                            ];
                            $category = Category::create($cat_input);
                            $cat_data = $category;
                        }
                        $cat = ($category->id)?true:false;
                        if($request->item_group_col){
                            $collection = Category::where('category_level',3)->where('name',$request->item_group_col)->orwhere('id',$request->item_group_col)->first();
                            if(empty($collection)){
                                $col_input = [
                                    "name"=>ucfirst($request->item_group_col),
                                    "category_level"=>3,
                                    "slug"=>strtolower(str_replace(" ","-",$request->item_group_col)),
                                    "shop_id"=>auth()->user()->shop_id,
                                    "branch_id"=>auth()->user()->branch_id,
                                ];
                                $collection = Category::create($col_input);
                                $coll_data = $collection;
                            }
                            $col = ($collection->id)?true:false;
                        }else{
                            $col = true;
                        }
                        if($col && $cat){
                            $input_arr = [
                                "cat_id"=>$category->id,
                                "cat_name"=>$category->name,
                                "item_group_name"=>$request->item_group_name,
                                "shop_id"=>auth()->user()->shop_id,
                                "branch_id"=>auth()->user()->branch_id,
                            ];
                            if($request->item_group_col){
                                $input_arr['coll_id'] = $collection->id;
                                $input_arr['coll_name'] = $collection->name;
                            }
                            $item_group = StockItemGroup::create($input_arr);
                            DB::commit();
                            if(!empty($item_group)){
                                return response()->json(['done'=>"Item group Succesfully Addedd !",'item_group'=>$item_group,"coll"=>$coll_data,"cat"=>$cat_data]);
                            }else{
                                return response()->json(['fail'=>"Failed to Add Item Group !"]);
                            }
                        }else{
                           return response()->json(["fail"=>"Something Went Wrong, Issue With Category/Collection !"]); 
                        }
                    }catch(PDOException $e){
                        DB::rollBack();
                        return response()->json(["fail"=>"Operation Failed ".$e->getMessage()]); 
                    }
                }
            }else{
                $rules = [
                    "item_group"=>'required|numeric',
                    "item_name"=>'required|string',
                    "item_hsn"=>'nullable',
					"method"=>'required|in:both,loose,tag',
                    /*'tag_prefix'=> "nullable|required_if:method,tag|regex:/^(?=.*[A-Za-z])[A-Za-z0-9]+$/",*/
					'tag_prefix' => ['nullable','required_if:method,tag','regex:/^(?=.*[A-Za-z])[A-Za-z0-9]+$/',Rule::unique('stock_items', 'tag_prefix')->where('shop_id',auth()->user()->shop_id)
                                    ],
                    'tag_digit'=> "nullable|required_if:method,tag|integer|min:1",
                    "lbr_value"=>'nullable|numeric',
                    "lbr_unit"=>'nullable|required_with:lbr_value|in:p,w',
                    "tax_value"=>'nullable|numeric',
                    "tax_unit"=>'nullable|required_with:tax_value|in:p,r',
                    "item_tunch"=>"nullable|numeric",
                    "item_wastage"=>"nullable|numeric",
                ];
                $msgs = [
                    "item_group.required"=>"Choose The Item Group !",
                    "item_group.numeric"=>"Invalid Item Group !",
                    "item_name.required"=>"Specify The Item name !",
                    "item_name.string"=>"Invalid Item name !",
                    "lbr_value.numeric"=>'Invalid labour Charge value !',
                    "lbr_unit.required_with"=>'labour Unit Required !',
                    "lbr_unit.in"=>'Invalid labour Unit !',
                    "method.required"=>'Select the Stock Method !',
                    'tag_prefix.required_if'=>'Provide Tag Prefix !',
                    'tag_prefix.regex'=>'Prefix can have alphabates/alphanumeric !',
					'tag_prefix.unique'=>'Prefix already in Use !',
                    'tag_digit.required_if'=>'Provide Number of Tag Digit !',
                    'tag_digit.min'=>'Left Blant or 0 Not Allowed !',
                    "tax_value.numeric"=>'Invalid Tax value !',
                    "tax_unit.required_with"=>'Tax Unit Required !',
                    "tax_unit.in"=>'Invalid Tax Unit !',
                    "item_tunch.numeric"=>"Invalid Tunch Value !",
                    "item_wastage.numeric"=>"Invalid Wastage Value !",
                ];
                $validator = Validator::make($request->all(),$rules,$msgs);
                if($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }else{
                    $input_arr = [
                        "item_name"=>$request->item_name,
                        "group_id"=>$request->item_group,
                        "stock_method"=>$request->method,
                        "shop_id"=>auth()->user()->shop_id,
                        "branch_id"=>auth()->user()->branch_id,
                    ];
                    if($request->item_hsn!=""){
                        $input_arr["hsn_code"]=$request->item_hsn;
                    }
                    if($request->tag_prefix !=""){
                        $input_arr["tag_prefix"]=$request->tag_prefix;
                        $input_arr["tag_digit"]=$request->tag_digit;
                    }
                    if($request->lbr_value !=""){
                        $input_arr["labour_value"]=$request->lbr_value;
                        $input_arr["labour_unit"]=$request->lbr_unit;
                    }
                    if($request->tax_value !=""){
                        $input_arr["tax_value"]=$request->tax_value;
                        $input_arr["tax_unit"]=$request->tax_unit;
                    }
					if($request->item_karet!=""){
                        $input_arr["karet"]=$request->item_karet;
                    }
                    if($request->item_tunch!=""){
                        $input_arr["tounch"]=$request->item_tunch;
                    }
                    if($request->item_wastage!=""){
                        $input_arr["wastage"]=$request->item_wastage;
                    }
                    $item = StockItem::create($input_arr);
                    if($item){
                         return response()->json(['done'=>"Item Succesfully Created !"]);
                    }else{
                        return response()->json(['fail'=>"Item Creation Failed !"]);
                    }
                }
            }
        }else{
            echo "Invalid Operation !";
        }
    }

	public function edititemgroup(Request $request,$section = false,$id=false){
        if($request->ajax()){
            $modal_arr = ['item'=> StockItem::class,'group'=> StockItemGroup::class];
            $relation_arr = ['item'=>'inventorystock','group'=>'stocks'];
            $modal = $modal_arr[$section];
            $data = $modal::withCount(["{$relation_arr[$section]} as exist" => function ($query) {
                        $query->where('avail_net', '!=', 0);
                    }])->find($id);
            if(!empty($data)){
                if($request->isMethod('get')){
                    return response()->json(['status'=>true,'data'=>$data]);
                }elseif($request->isMethod('post')){
                     $update_function = "update{$section}";
                    if(method_exists($this, $update_function) && $request->operation == 'edit'){
                        $response = $this->$update_function($request,$data);
                        return response()->json($response);
                    }else{
                        return response()->json(['update'=>true,'status'=>false,'msg'=>'Invalid Function !']);
                    }
                }
            }else{
                return response()->json(['update'=>true,'status'=>false,'msg'=>'No Record Found !']);
            }
        }else{
            echo "Invalid Operation !";
        }
    }

    private function updateitem(Request $request,$data){
        $rule = [
            "item_hsn"=>'nullable',
            "method"=>'required|in:both,loose,tag',
            'tag_prefix'=> "nullable|required_if:method,tag|regex:/^(?=.*[A-Za-z])[A-Za-z0-9]+$/",
            'tag_digit'=> "nullable|required_if:method,tag|integer|min:1",
            "lbr_value"=>'nullable|numeric',
            "lbr_unit"=>'nullable|required_with:lbr_value|in:p,w',
            "tax_value"=>'nullable|numeric',
            "tax_unit"=>'nullable|required_with:tax_value|in:p,r',
            "item_karet"=>"nullable|numeric",
            "item_tunch"=>"nullable|numeric",
            "item_wastage"=>"nullable|numeric",
        ];
        $msg = [
            "lbr_value.numeric"=>'Invalid labour Charge value !',
            "lbr_unit.required_with"=>'labour Unit Required !',
            "lbr_unit.in"=>'Invalid labour Unit !',
            "method.required"=>'Select the Stock Method !',
            'tag_prefix.required_if'=>'Provide Tag Prefix !',
            'tag_prefix.regex'=>'Prefix can have alphabates/alphanumeric !',
            'tag_digit.required_if'=>'Provide Number of Tag Digit !',
            'tag_digit.min'=>'Left Blant or 0 Not Allowed !',
            "tax_value.numeric"=>'Invalid Tax value !',
            "tax_unit.required_with"=>'Tax Unit Required !',
            "tax_unit.in"=>'Invalid Tax Unit !',
            "item_tunch.numeric"=>"Invalid Tunch Value !",
            "item_wastage.numeric"=>"Invalid Wastage Value !",
        ];
        if($data->exist == 0){
            $rule['item_group'] = 'required|numeric';
            $rule['item_name'] = 'required|string';
            $msg["item_group.required"] = "Choose The Item Group !";
            $msg["item_group.numeric"] = "Invalid Item Group !";
            $msg["item_name.required"] = "Specify The Item name !";
            $msg["item_name.string"] = "Invalid Item name !";
        }
        $validator = Validator::make($request->all(),$rule,$msg);
        if($validator->fails()){
            return ['update'=>true,'status'=>false,'errors'=>$validator->errors()];
        }else{
            if($request->item_name){
                $data->item_name = $request->item_name;
            }
            if($request->item_group){
                $data->group_id = $request->item_group;
            }
            if($request->item_hsn){
                $data->hsn_code = $request->item_hsn;
            }
            if($request->tax_value){
                $data->tax_value = $request->tax_value;
            }
            if($request->tax_unit){
                $data->tax_unit = $request->tax_unit;
            }
            if($request->method){
                $data->stock_method = $request->method;
            }
            if($request->tag_prefix){
                $data->tag_prefix = $request->tag_prefix;
            }
            if($request->tag_digit){
                $data->tag_digit = $request->tag_digit;
            }
            if($request->item_karet){
                $data->karet = $request->item_karet;
            }
            if($request->item_tunch){
                $data->tounch = $request->item_tunch;
            }
            if($request->item_wastage){
                $data->wastage = $request->item_wastage;
            }
            if($request->lbr_value){
                $data->labour_value = $request->lbr_value;
            }
            if($request->lbr_unit){
                $data->labour_unit = $request->lbr_unit;
            }
            if($data->update()){
                return ['update'=>true,"status"=>true,'msg'=>'Item Sucesfully Updated !'];
            }else{
                return ['update'=>true,"status"=>false,'msg'=>'Item Updation Failed !'];
            }
        }
    }
	
	public function deleteitemgroup(Request $request,$section = false,$id=false){
        if($request->ajax()){
            $modal_arr = ['item'=> StockItem::class,'group'=> StockItemGroup::class];
            $relation_arr = ['item'=>'inventorystock','group'=>'stocks'];
            $modal = $modal_arr[$section];
            $data = $modal::withCount(["{$relation_arr[$section]} as exist" => function ($query) {
                        $query->where('avail_net', '!=', 0);
                    }])->find($id);
            if(!empty($data)){
                $target = ($section=='item')?'Item Name':'Group';
                if($data->exist==0 ){
                    if($data->delete()){
                        return response()->json(['status'=>true,'msg'=>"{$target} Deleted !"]); 
                    }else{
                       return response()->json(['status'=>false,'msg'=>"Operatiin Failed !"]); 
                    }
                }else{
                    return response()->json(['status'=>false,'msg'=>"Stock Exist with this {$target}"]);
                }
            }else{
                return response()->json(['status'=>false,'msg'=>'No Record Found !']);
            }
        }else{
            echo "Invalid Operation !";
        }
    }

	public function getmaxtagnumber(Request $request){
        $item = StockItem::find($request->item);
        if(!empty($item)){
            if($item->stock_method!='loose'){
                return response()->json(['status'=>true,'prefix'=>$item->tag_prefix,'length'=>$item->tag_digit,'num'=>$item->curr_max_tag+1]);
            }else{
                return response()->json(['status'=>false,'msg'=>'No Tag Data !']);
            }
        }else{
            return response()->json(['status'=>false,'msg'=>'Not Record !']);
        }
    }

    public function finditems(Request $request){
        if($request->ajax()){
            $items = false;
            if($request->keyword!=""){
                $keyword = $request->keyword;
                $metal = ($request->stock)?str_replace(['-',"_"]," ",$request->stock):null;
                $entry = $request->entry;
				
				//$items_query = StockItem::with('itemgroup')->withMax('inventorystock', 'tag') ;
				/*$items_query = StockItem::with('itemgroup')->withAggregate(['inventorystock'  => function ($query) {
                    $query->select(DB::raw("
                        MAX(
                            CAST(
                               REPLACE(tag, stock_items.tag_prefix, '') 
                                AS UNSIGNED
                            )
                        )
                    "));
                }], 'max_tag');*/
				
				$items_query = StockItem::select('*', DB::raw("
                                CASE
                                    WHEN stock_items.curr_max_tag = 0 THEN (
                                        SELECT MAX(
                                            CAST(
                                                REPLACE(inventory_stocks.tag, stock_items.tag_prefix, '') 
                                                AS UNSIGNED
                                            )
                                        )
                                        FROM  inventory_stocks
                                        WHERE  inventory_stocks.item_id = stock_items.id
                                    )
                                    ELSE stock_items.curr_max_tag
                                END AS max_tag
                            "))->with('itemgroup');
				
                if($metal){
                    $items_query->where(function($sub_query) use ($metal){
                        $sub_query->whereHas('itemgroup',function($itmgrp) use ($metal){
                            $itmgrp->where('cat_name',$metal);
                        });
                    });
                }
				if($entry!='both'){
                    $items_query->whereIn('stock_method',[$entry,'both']);
                }
                /*if($entry!='both'){
                    $items_query->where('stock_method',$entry);
                }*/
                if($keyword){
                    $items_query->where(function($query) use ($keyword){
                        $query->where('item_name','like',"%{$keyword}%")
                        ->orWhereHas('itemgroup',function($group) use ($keyword){
                            $group->where('item_group_name','like',"%{$keyword}%");
                            $group->orwhere('coll_name','like',"%{$keyword}%");
                        });
                    });
                }
				
                //echo $items_query->toSql();
                $items = $items_query->where(['shop_id'=>auth()->user()->shop_id,"branch_id"=>auth()->user()->branch_id])->orderBy('item_name','asc')->get();
            }
            //dd($items);
			$matterial = strtolower($metal);
            //$rate = (in_array($matterial,['gold','silver']))?Rate::selectRaw("{$matterial}_rate as rate")->where('active','1')->first()->rate:false;
			$rate = null;
            if(in_array($matterial,['gold','silver'])){
                $rate_data = Rate::selectRaw("{$matterial}_rate as rate")->where('active','1')->first()->rate;
                if(!empty($rate_data)){
                    $rate = ($matterial=='silver')?($rate_data)/1000:$rate_data;
                }
            }
            return response()->json(['items'=>$items,'rate'=>$rate]);
        }else{
            echo "Invalid Operation !";
        }
    }

    public function newstockcreate(Request $request){
		/*echo '<pre>';
		print_r($request->toArray());
		echo '<pre>';
		exit();*/
        if($request->ajax()){
            $rules = [
                "stock_type"=>'required',
                "entry_type" =>'required|in:loose,tag,both',
                'entry_num'=>'required|numeric'
            ];
            $msgs = [
                "stock_type.required"=>'Select The Stock Type !',
                "entry_type.required" =>'Select The Method To List Stock !',
                "entry_type.in" =>'Invalid Method Selected !',
				'entry_num.required'=>'Entry Number Required !',
				'entry_num.numeric'=>'Entry Number Must Be Number !',
            ];
            $validator = Validator::make($request->all(),$rules,$msgs);
            if($validator->fails()){
				return response()->json(['status'=>false,'errors'=>$validator->errors()]);
            }else{
				
				$tag_arr = array_filter($request->tag);
                $ttl_tags_count = count($tag_arr);
                if($ttl_tags_count > 0){
                    $ttl_tags_uniq = count(array_unique($tag_arr));
                    if($ttl_tags_count !== $ttl_tags_uniq){
                        return response()->json(['status'=>false,'msg'=>"Tags Can't Be Repeat !"]);
                    }
                }
                $existtag = InventoryStock::whereIn('tag', $tag_arr)->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->pluck('tag')->unique()->values()->toArray();
                if(!empty($existtag)){
                    $repeat_tags = implode('|',$existtag);
                    return response()->json(['status'=>false,'msg'=>"Tag already in Use !<br>{$repeat_tags}"]);
                }
				
                $stock = strtolower(str_replace([" ","-","_"],"",$request->stock_type));
                $function = "save{$stock}stock";
                if(method_exists($this, $function)){
					DB::begintransaction();
					try{
						$this->daily_txn_arr['source'] = 'ins';
                        $response = $this->$function($request);
                        DB::commit();

                                                // 🔔 STOCK CREATED NOTIFICATION
                        $data = [
                            'title' => 'New Stock Added',
                            'message' => 'New '.$request->stock_type.' stock added successfully',
                            'link' => route('stock.new.inventory'),
                            'stock_type' => $request->stock_type // gold/silver/artificial/stone
                        ];

                        // jis user ne kaam kiya
                        auth()->user()->notify(new StockNotification($data));

                        return response()->json($response);
					}catch(PDOException $e){
						DB::rollback();
                        return response()->json(['status'=>false,"msg"=>"Operation Failed".$e->getMessage()]);
                    }
                }else{
                    return response()->json(['status'=>false,'msg'=>'Invalid Stock Type !']) ;
                }

            }
        }else{
            return view('vendors.stocks.newpage.newstock');
        }
    }   

    private function savegoldstock(Request $request){
        $rules = $msgs = [];
        if(isset($request->item) && count($request->item) > 0){
            foreach($request->item as $item_key=>$item){
                if($item!=""){
                    $rules["item.{$item_key}"] = 'required';
                    $rules["gross.{$item_key}"] = 'required';
                    $rules["net.{$item_key}"] = 'required';
                    $rules["fine.{$item_key}"] = 'required';

                    $msgs["item.{$item_key}.required"] = "Item Name Required !";
                    $msgs["gross.{$item_key}.required"] = "Gross Weight Required !";
                    $msgs["net.{$item_key}.required"] = "Net Weight Required !";
                    $msgs["fine.{$item_key}.required"] = "Fine Weight required !";
                }
            }
        }
        $validator =Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            //return response()->json(['errors'=>$validator->errors()]);
            return ['status'=>false,'errors'=>$validator->errors()];
        }else{
            $response = null;
            $insert = $item_count = 0;
			$caret_count = count(array_filter($request->caret));
			$item_count = count(array_filter($request->item));
			
			//echo $caret_count.'<br>';
			//echo $item_count.'<br>';
            if($caret_count != $item_count){
                return ['status'=>false,'msg'=>"Karet Can't be Missed !"];
            }
			//exit();
            try{
                DB::beginTransaction();
                foreach($request->item as $item_key=>$item){
                    if($item!=""){
                        $item_count++;
                        $row_data = [
                            "name"=>$item,
                            "item_id"=>$request->type[$item_key],
                            "group_id"=>StockItem::find($request->type[$item_key])->group_id,
                            "gross"=>$request->gross[$item_key],
                            "avail_gross"=>$request->gross[$item_key],
                            "net"=>$request->net[$item_key],
                            "avail_net"=>$request->net[$item_key],
                            "fine"=>$request->fine[$item_key],
                            "avail_fine"=>$request->fine[$item_key],
							"remark"=>$request->remark[$item_key],
                            'entry_num'=>$request->entry_num,
                            'stock_type'=>$request->stock_type,
                            'entry_mode'=>$request->entry_type,
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
						if(($request->net[$item_key] == $request->fine[$item_key]) && $request->caret[$item_key]){
                            $row_data['fine'] = $request->net[$item_key]*($request->caret[$item_key]/24);
                        }
                        if($request->tag[$item_key]){
                            $row_data["tag"] = $request->tag[$item_key];
							
                        }
                        /*if($request->rfid[$item_key]){
                            $row_data["rfid"] = $request->rfid[$item_key];
                        }*/
                        if($request->huid[$item_key]){
                            $row_data["huid"] = $request->huid[$item_key];
                        }
                        if($request->caret[$item_key]){
                            $row_data["caret"] = $request->caret[$item_key];
                        }
                        if($request->tunch[$item_key]){
                            $row_data["tunch"] = $request->tunch[$item_key];
                        } 
						$item_name_data  = StockItem::find($request->type[$item_key]);
                        if($request->piece[$item_key]){
                            $row_data["count"] = $request->piece[$item_key];
                            $row_data['avail_count'] = $request->piece[$item_key];
                        }elseif($request->entry_type=='tag' || $request->tag[$item_key] || $item_name_data->stock_method=='tag') {
							$row_data["count"] = $row_data['avail_count'] = 1;
						}
                        if($request->less[$item_key]){
                            $row_data["less"] = $request->less[$item_key];
                        }
                        if($request->wstg[$item_key]){
                            $row_data["wastage"] = $request->wstg[$item_key];
							if($row_data["fine"]){
                                $row_data["fine"] = $row_data["fine"]*(1+$row_data["wastage"]/100);
                            }
                        }
                        if($request->chrg[$item_key]){
                            $row_data["element_charge"] = $request->chrg[$item_key];
                        }
                        if($request->rate[$item_key]){
                            $row_data["rate"] = $request->rate[$item_key];
                        }
                        if($request->other[$item_key]){
                            $row_data["charge"] = $request->other[$item_key];
                        }
                        if($request->lbr[$item_key]){
                            $row_data["labour"] = $request->lbr[$item_key];
                        }
                        if($request->lbrunit[$item_key]){
                            $row_data["labour_unit"] = $request->lbrunit[$item_key];
                        }
                        if($request->disc[$item_key]){
                            $row_data["discount"] = $request->disc[$item_key];
                        }
                        if($request->discunit[$item_key]){
                            $row_data["discount_unit"] = $request->discunit[$item_key];
                        }
                        if($request->ttl[$item_key]){
                            $row_data["total"] = $request->ttl[$item_key];
                        }
                        $image_url = false;
                        if(isset($request->file('image')[$item_key])){
                            $image = $request->file('image')[$item_key];
                            $filename = str_replace(" ","_",$item)."_".time().".".strtolower($image->getClientOriginalExtension());
                            if($image->move(public_path('assets/images/stocks/'), $filename)){
                                $image_url = asset('assets/images/stocks/' . $filename);
                                $row_data["image"] = $image_url;
                            }
                        }
                        $stock = InventoryStock::create($row_data); 
                        if($stock){
                            $insert++;
                            $response = $this->newstockelementcreate($request,$stock,$item_key);
                        }else{
                            ($image_url)?@unlink($image_url):null;
                        } 
                    }
                }
				$max_tag = false;
                $item_code = [];
                $items = $request->type;
                $tags = $request->tag;
                array_walk($items, function($v, $i) use ($tags, &$item_code) {
                    if($v!="" && $tags[$i]){
                        $item_code[$v][] = $tags[$i];
                    }
                });
                foreach($item_code as $item_id=>$codes){
                    $item_name_data = StockItem::find($item_id);
                    $prefix = $item_name_data->tag_prefix;
                    $max_tag = max(array_map(function($code) use ($prefix) {
                        // remove known prefix
                        $num_str = str_replace("{$prefix}", '', $code);

                        // convert to number
                        return (int)$num_str;
                    }, $codes));
                    $item_name_data->update(['curr_max_tag'=>$max_tag]);
                }
                DB::commit();
                if($insert>0){
                    $msg = ($insert==$item_count)?"Stock Saved Succesfully !":"Only {$insert} Saved To Stock !";
                    $print = ($request->stock=='print')?$request->stock:false;
                    $response = ['status'=>true,'msg'=>$msg,'next'=>route('stock.new.recent',[strtolower($stock->stock_type),$stock->entry_num,$print])];
                }else{
                    $msg = ($item_count > 0)?"Failed to Save Stock !":"Not Item to Save !";
                    $response = ['status'=>false,'msg'=>$msg];
                }
                //return response()->json($response);
                return $response;
            }catch(PDOException $e){
                DB::rollBack();
                //return response()->json(['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
                return ['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()];
            }
        }
    }

    private function savesilverstock(Request $request){
        $rules = $msgs = [];
        if(isset($request->item) && count($request->item) > 0){
            foreach($request->item as $item_key=>$item){
                if($item!=""){
                    $rules["item.{$item_key}"] = 'required';
                    $rules["gross.{$item_key}"] = 'required';
                    $rules["net.{$item_key}"] = 'required';

                    $msgs["item.{$item_key}.required"] = "Item Name Required !";
                    $msgs["gross.{$item_key}.required"] = "Gross Weight Required !";
                    $msgs["net.{$item_key}.required"] = "Net Weight Required !";
                }
            }
        }
        // echo '<pre>';
        // print_r($request->toArray());
        // echo '</pre>';
        $validator =Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            //return response()->json(['errors'=>$validator->errors()]);
            return ['status'=>false,'errors'=>$validator->errors()];
        }else{
            $response = null;
            $insert = $item_count = 0;
			$tag_arr = array_filter($request->tag);
            $ttl_tags_count = count($tag_arr);
			if($ttl_tags_count > 0){
                $ttl_tags_unique = count(array_unique($tag_arr));
                if($ttl_tags_count !== $ttl_tags_unique){
                    return ['status'=>false,'msg'=>"Tags Can't Be Repeat !"];
                }
				$existcount = InventoryStock::whereIn('tag', $tag_arr)->count();
				if($existcount>0){
					return ['status'=>false,'msg'=>"Tag Already Used !"];
				}
            }
            try{
                DB::beginTransaction();
                foreach($request->item as $item_key=>$item){
                    if($item!=""){
                        $item_count++;
                        $row_data = [
                            "name"=>$item,
                            "item_id"=>$request->type[$item_key],
                            "group_id"=>StockItem::find($request->type[$item_key])->group_id,
                            "gross"=>$request->gross[$item_key],
                            "avail_gross"=>$request->gross[$item_key],
                            "net"=>$request->net[$item_key],
                            "avail_net"=>$request->net[$item_key],
                            'entry_num'=>$request->entry_num,
                            'stock_type'=>$request->stock_type,
                            'entry_mode'=>$request->entry_type,
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
                        if($request->tag[$item_key]){
                            $row_data["tag"] = $request->tag[$item_key];
                        }
						if($request->huid[$item_key]){
                            $row_data["huid"] = $request->huid[$item_key];
                        }
                        /*if($request->rfid[$item_key]){
                            $row_data["rfid"] = $request->rfid[$item_key];
                        }*/
						$item_name_data = StockItem::find($request->type[$item_key]);
                        if($request->piece[$item_key]){
                            $row_data["count"] = $request->piece[$item_key];
                            $row_data['avail_count'] = $request->piece[$item_key];
                        }elseif($request->entry_type=='tag' || $request->tag[$item_key] || $item_name_data->stock_method=='tag') {
							$row_data["count"] = $row_data['avail_count'] = 1;
						}
						if($request->tunch[$item_key]){
                            $row_data["tunch"] = $request->tunch[$item_key];
							$row_data['fine'] = $row_data['avail_fine'] = number_format(($row_data['net'] * $row_data["tunch"])/100,3);
                        }
                        if($request->less[$item_key]){
                            $row_data["less"] = $request->less[$item_key];
                        }
						/*if($request->wstg[$item_key]){
                            $row_data["wastage"] = $request->wstg[$item_key];
                        }*/
						if($request->chrg[$item_key]){
                            $row_data["element_charge"] = $request->chrg[$item_key];
                        }
                        if($request->rate[$item_key]){
                            $row_data["rate"] = $request->rate[$item_key];
                        }
                        if($request->other[$item_key]){
                            $row_data["charge"] = $request->other[$item_key];
                        }
                        if($request->lbr[$item_key]){
                            $row_data["labour"] = $request->lbr[$item_key];
                        }
                        if($request->lbrunit[$item_key]){
                            $row_data["labour_unit"] = $request->lbrunit[$item_key];
                        }
                        if($request->disc[$item_key]){
                            $row_data["discount"] = $request->disc[$item_key];
                        }
                        if($request->discunit[$item_key]){
                            $row_data["discount_unit"] = $request->discunit[$item_key];
                        }
                        /*if($request->crt[$item_key]){
                            $row_data["crt"] = $request->crt[$item_key];
                        }*/
                        if($request->ttl[$item_key]){
                            $row_data["total"] = $request->ttl[$item_key];
                        }
                        $image_url = false;
                        if(isset($request->file('image')[$item_key])){
                            $image = $request->file('image')[$item_key];
                            $filename = str_replace(" ","_",$item)."_".time().".".strtolower($image->getClientOriginalExtension());
                            if($image->move(public_path('assets/images/stocks/'), $filename)){
                                $image_url = asset('assets/images/stocks/' . $filename);
                                $row_data["image"] = $image_url;
                            }
                        }
                        $stock = InventoryStock::create($row_data);
                        if($stock){
                            $insert++;
                            $response = $this->newstockelementcreate($request,$stock,$item_key);
                        }else{
                            ($image_url)?@unlink($image_url):null;
                        } 
                    }
                }
				$max_tag = false;
                $item_code = [];
                $items = $request->type;
                $tags = $request->tag;
                array_walk($items, function($v, $i) use ($tags, &$item_code) {
                    if($v!="" && $tags[$i]){
                        $item_code[$v][] = $tags[$i];
                    }
                });
                foreach($item_code as $item_id=>$codes){
                    $item_name_data = StockItem::find($item_id);
                    $prefix = $item_name_data->tag_prefix;
                    $max_tag = max(array_map(function($code) use ($prefix) {
                        // remove known prefix
                        $num_str = str_replace("{$prefix}", '', $code);

                        // convert to number
                        return (int)$num_str;
                    }, $codes));
                    $item_name_data->update(['curr_max_tag'=>$max_tag]);
                }
                DB::commit();
                if($insert>0){
                    $msg = ($insert==$item_count)?"Stock Saved Succesfully !":"Only {$insert} Saved To Stock !";
                    $print = ($request->stock=='print')?$request->stock:false;
                    $response = ['status'=>true,'msg'=>$msg,'next'=>route('stock.new.recent',[strtolower($stock->stock_type),$stock->entry_num,$print])];
                }else{
                    $msg = ($item_count > 0)?"Failed to Save Stock !":"Not Item to Save !";
                    $response = ['status'=>false,'msg'=>$msg];
                }
                //return response()->json($response);
                return $response;
            }catch(PDOException $e){
                DB::rollBack();
                //return response()->json(['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
                return ['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()];
            }
        }
    }

    private function savestonestock(Request $request){
        $rules = $msgs = [];
        if(isset($request->item) && count($request->item) > 0){
            foreach($request->item as $item_key=>$item){
                if($item!=""){
                    $rules["item.{$item_key}"] = 'required';
                    $rules["gross.{$item_key}"] = 'required';
                    $rules["net.{$item_key}"] = 'required';

                    $msgs["item.{$item_key}.required"] = "Item Name Required !";
                    $msgs["gross.{$item_key}.required"] = "Gross Weight Required !";
                    $msgs["net.{$item_key}.required"] = "Net Weight Required !";
                }
            }
        }
        $validator =Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return ['errors'=>$validator->errors()];
        }else{
            $response = null;
            $insert = $item_count = 0;
			$tag_arr = array_filter($request->tag);
            $ttl_tags_count = count($tag_arr);
			if($ttl_tags_count > 0){
                $ttl_tags_unique = count(array_unique($tag_arr));
                if($ttl_tags_count !== $ttl_tags_unique){
                    return ['status'=>false,'msg'=>"Tags Can't Be Repeat !"];
                }
				$existcount = InventoryStock::whereIn('tag', $tag_arr)->count();
				if($existcount>0){
					return ['status'=>false,'msg'=>"Tag Already Used !"];
				}
            }
            try{
                DB::beginTransaction();
                foreach($request->item as $item_key=>$item){
                    if($item!=""){
                        $item_count++;
                        $row_data = [
                            "name"=>$item,
                            "item_id"=>$request->type[$item_key],
                            "group_id"=>StockItem::find($request->type[$item_key])->group_id,
                            "gross"=>$request->gross[$item_key],
                            "avail_gross"=>$request->gross[$item_key],
                            "net"=>$request->net[$item_key],
                            "avail_net"=>$request->net[$item_key],
                            'entry_num'=>$request->entry_num,
                            'stock_type'=>$request->stock_type,
                            'entry_mode'=>$request->entry_type,
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
                        if($request->tag[$item_key]){
                            $row_data["tag"] = $request->tag[$item_key];
                        }
                        /*if($request->rfid[$item_key]){
                            $row_data["rfid"] = $request->rfid[$item_key];
                        }*/ 
                        /*if($request->caret[$item_key]){
                            $row_data["caret"] = $request->caret[$item_key];
                        }*/
                        if($request->remark[$item_key]){
                            $row_data["remark"] = $request->remark[$item_key];
                        }
                        if($request->color[$item_key]){
                            $row_data["color"] = $request->color[$item_key];
                        }
                        if($request->clear[$item_key]){
                            $row_data["clearity"] = $request->clear[$item_key];
                        }
                        if($request->piece[$item_key]){
                            $row_data["count"] = $request->piece[$item_key];
                            $row_data['avail_count'] = $request->piece[$item_key];
                        }elseif($request->entry_type=='tag' || $request->tag[$item_key] || $item_name_data->stock_method=='tag') {
							$row_data["count"] = $row_data['avail_count'] = 1;
						}
                        if($request->less[$item_key]){
                            $row_data["less"] = $request->less[$item_key];
                        }
                        if($request->rate[$item_key]){
                            $row_data["rate"] = $request->rate[$item_key];
                        }
                        if($request->other[$item_key]){
                            $row_data["charge"] = $request->other[$item_key];
                        }
                        if($request->lbr[$item_key]){
                            $row_data["labour"] = $request->lbr[$item_key];
                        }
                        if($request->lbrunit[$item_key]){
                            $row_data["labour_unit"] = $request->lbrunit[$item_key];
                        }
                        if($request->disc[$item_key]){
                            $row_data["discount"] = $request->disc[$item_key];
                        }
                        if($request->discunit[$item_key]){
                            $row_data["discount_unit"] = $request->discunit[$item_key];
                        }
                        if($request->crt[$item_key]){
                            $row_data["crt"] = $request->crt[$item_key];
                        }
                        if($request->ttl[$item_key]){
                            $row_data["total"] = $request->ttl[$item_key];
                        }
                        $image_url = false;
                        if(isset($request->file('image')[$item_key])){
                            $image = $request->file('image')[$item_key];
                            $filename = str_replace(" ","_",$item)."_".time().".".strtolower($image->getClientOriginalExtension());
                            if($image->move(public_path('assets/images/stocks/'), $filename)){
                                $image_url = asset('assets/images/stocks/' . $filename);
                                $row_data["image"] = $image_url;
                            }
                        }
                        $stock = InventoryStock::create($row_data);
                        if($stock){
                            $insert++;
                            $response = $this->newstockelementcreate($request,$stock,$item_key);
                        }else{
                            ($image_url)?@unlink($image_url):null;
                        }
                    }
                }
				$max_tag = false;
                $item_code = [];
                $items = $request->type;
                $tags = $request->tag;
                array_walk($items, function($v, $i) use ($tags, &$item_code) {
                    if($v!="" && $tags[$i]){
                        $item_code[$v][] = $tags[$i];
                    }
                });
                foreach($item_code as $item_id=>$codes){
                    $item_name_data = StockItem::find($item_id);
                    $prefix = $item_name_data->tag_prefix;
                    $max_tag = max(array_map(function($code) use ($prefix) {
                        // remove known prefix
                        $num_str = str_replace("{$prefix}", '', $code);

                        // convert to number
                        return (int)$num_str;
                    }, $codes));
                    $item_name_data->update(['curr_max_tag'=>$max_tag]);
                }
                DB::commit();
                if($insert>0){
                    $msg = ($insert==$item_count)?"Stock Saved Succesfully !":"Only {$insert} Saved To Stock !";
                    $print = ($request->stock=='print')?$request->stock:false;
                    $response = ['status'=>true,'msg'=>$msg,'next'=>route('stock.new.recent',[strtolower($stock->stock_type),$stock->entry_num,$print])];
                }else{
                    $msg = ($item_count > 0)?"Failed to Save Stock !":"Not Item to Save !";
                    $response = ['status'=>false,'msg'=>$msg];
                }
                return $response;
            }catch(PDOException $e){
                DB::rollBack();
                return ['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()];
            }
        }
    }

	private function saveartificialstock(Request $request){
        if(isset($request->item) && count($request->item) > 0){
            foreach($request->item as $item_key=>$item){
                if($item!=""){
                    $rules["type.{$item_key}"] = 'required';
                    $rules["item.{$item_key}"] = 'required';
                    $rules["piece.{$item_key}"] = 'required';
                    $rules["rate.{$item_key}"] = 'required';
                    $rules["ttl.{$item_key}"] = 'required';

                    $msgs["type.{$item_key}.required"] = "Item Type Required !";
                    $msgs["item.{$item_key}.required"] = "Item Name Required !";
                    $msgs["piece.{$item_key}.required"] = "Item Piece Required !";
                    $msgs["rate.{$item_key}.required"] = "Item Rate required !";
                    $msgs["ttl.{$item_key}.required"] = "Item Total required !";
				if($request->entry_type=='tag' && $request->tag[$item_key]==""){
                    $rules["tag.{$item_key}"] = 'required';
                    $msgs["tag.{$item_key}.required"] = "Item Tag required !";
                }
                if($request->disc[$item_key]!="" && $request->discunit[$item_key]==""){
                    $rules["discunit.{$item_key}"] = 'required';
                    $msgs["discunit.{$item_key}.required"] = "Discount Unit required !";
                }
			}    
		}
            $validator =Validator::make($request->all(),$rules,$msgs);
            if($validator->fails()){
                return ['status'=>false,'errors'=>$validator->errors()];
            }else{
                $response = null;
                $insert = $item_count = $ok_count = 0;
                if(isset($request->tag)){
                    $tag_arr = array_filter($request->tag);
                    $ttl_tags_count = count($tag_arr);
                    $ttl_tags_uniq = count(array_unique($tag_arr));
                    if($ttl_tags_count !== $ttl_tags_uniq){
                        return response()->json(['status'=>false,'msg'=>"Tags Can't Be Repeat !"]);
                    }
                }
                $existcount = InventoryStock::whereIn('tag', $tag_arr)->count();
                if($existcount > 0){
                    return response()->json(['status'=>false,'msg'=>"Tags Already in Use !"]);
                }
                try{
                    DB::beginTransaction();
                    foreach($request->item as $item_key=>$item){
                        if($item!=""){
                            $item_count++;
                            $cost = ($request->piece[$item_key] * $request->rate[$item_key]);
                            $cost+= ($request->other[$item_key]??0);
                            $disc = $request->disc[$item_key];

                            if($request->disc[$item_key] && $request->discunit[$item_key]){
                                if($request->discunit[$item_key]=='p'){
                                    $disc = ($cost * $disc)/100;
                                }
                            }else{
                                $disc = 0;
                            }
                            $cost = $cost - $disc;
                            //echo $cost.'<br>';
                            //echo $request->ttl[$item_key].'<br>';
                            //echo "Hello".($cost==$request->ttl[$item_key]).'<br>';
                            //exit();
                            if($cost==$request->ttl[$item_key]){
                                $ok_count++;
                            }else{
                                break;
                            }
                            $row_data = [
                                "name"=>$item,
                                "item_id"=>$request->type[$item_key],
                                "group_id"=>StockItem::find($request->type[$item_key])->group_id,
                                'count'=>$request->piece[$item_key],
                                'avail_count'=>$request->piece[$item_key],
                                'rate'=>$request->rate[$item_key],
                                "remark"=>$request->remark[$item_key],
                                'entry_num'=>$request->entry_num,
                                'stock_type'=>$request->stock_type,
                                'entry_mode'=>$request->entry_type,
                                'total'=>$request->ttl[$item_key],
                                "shop_id"=>auth()->user()->shop_id,
                                "branch_id"=>auth()->user()->branch_id,
                            ];
                            if($request->tag[$item_key]){
                                 $row_data['tag'] = $request->tag[$item_key];
                            }
                            if($request->disc[$item_key] && $request->discunit[$item_key]){
                                $row_data['discount'] = $request->disc[$item_key];
                                $row_data['discount_unit'] = $request->discunit[$item_key];
                            }
                            if($request->other[$item_key]){
                                $row_data['charge'] = $request->other[$item_key];
                            }
                            $image_url = false;
                            if(isset($request->file('image')[$item_key])){
                                $image = $request->file('image')[$item_key];
                                $filename = str_replace(" ","_",$item)."_".time().".".strtolower($image->getClientOriginalExtension());
                                if($image->move(public_path('assets/images/stocks/'), $filename)){
                                    $image_url = asset('assets/images/stocks/' . $filename);
                                    $row_data["image"] = $image_url;
                                }
                            }
                            /*echo '<pre>';
                            print_r($row_data);
                            echo '<pre>';*/
                            $stock = InventoryStock::create($row_data);
                            if($stock){
                                $insert++;
                            }elseif($image_url){
                                @unlink($image_url);
                            } 
                        }
                    }
                    if($ok_count==$item_count){
						$max_tag = false;
						$item_code = [];
						$items = $request->type;
						$tags = $request->tag;
						array_walk($items, function($v, $i) use ($tags, &$item_code) {
							if($v!="" && $tags[$i]){
								$item_code[$v][] = $tags[$i];
							}
						});
						foreach($item_code as $item_id=>$codes){
							$item_name_data = StockItem::find($item_id);
							$prefix = $item_name_data->tag_prefix;
							$max_tag = max(array_map(function($code) use ($prefix) {
								// remove known prefix
								$num_str = str_replace("{$prefix}", '', $code);

								// convert to number
								return (int)$num_str;
							}, $codes));
							$item_name_data->update(['curr_max_tag'=>$max_tag]);
						}
                        DB::commit();
                        if($insert>0){
                            $msg = ($insert==$item_count)?"Stock Saved Succesfully !":"Only {$insert} Saved To Stock !";
                            $print = ($request->stock=='print')?$request->stock:false;
                            $response = ['status'=>true,'msg'=>$msg,'next'=>route('stock.new.recent',[strtolower($stock->stock_type),$stock->entry_num,$print])];
                        }else{
                            $msg = ($item_count > 0)?"Failed to Save Stock !":"Not Item to Save !";
                            $response = ['status'=>false,'msg'=>$msg];
                        }
                    }else{
                        $response = ['status'=>false,'msg'=>"Wrong Calculations at Item Check Manually !"];
                    }
                    //return response()->json($response);
                    return $response;
                }catch(PDOException $e){
                    DB::rollBack();
                //return response()->json(['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()]);
                return ['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()];
                }
            }
        }else{
            return response()->json(['status'=>false,'msg'=>'Nothing to Save !']);
        }
    }

	private function savefranchisejewellerystock(Request $request){
        /*echo '<pre>';
        print_r($request->toArray());
        echo '</pre>';
        exit();*/
        $items = array_filter($request->item);
        if(count($items) > 0){
            foreach($request->item as $item_key=>$item){
                if($item!=""){
                    $rules["type.{$item_key}"] = 'required';
                    $rules["item.{$item_key}"] = 'required';
                    $rules["gross.{$item_key}"] = 'required';
                    $rules["net.{$item_key}"] = 'required';
                    $rules["ele.{$item_key}"] = "required_with:elewt.{$item_key}";
                    $rules["elewt.{$item_key}"] = "required_with:ele.{$item_key}";
                    $rules["rate.{$item_key}"] = 'required';
                    $rules["discunit.{$item_key}"] = "required_with:disc.{$item_key}";

                    $rules["ttl.{$item_key}"] = 'required';

                    $msgs["type.{$item_key}.required"] = "Item Type Required !";
                    $msgs["item.{$item_key}.required"] = "Item Name Required !";
                    $msgs["gross.{$item_key}.required"] = 'Item Gross Weight Required !';
                    $msgs["net.{$item_key}.required"] = 'Item Net Weight Required !';
                    $msgs["ele.{$item_key}.required_with"] = "Element Name Required if Weight Provided !";
                    $msgs["elewt.{$item_key}.required_with"] = "Element Weight Required if Name Provided !";
                    $msgs["rate.{$item_key}.required"] = "Item Rate required !";

                    $msgs["discunit.{$item_key}.required_with"]="Discount Unit Required !";

                    $msgs["ttl.{$item_key}.required"] = "Item Total required !";
                    
                    if($request->entry_type=='tag' && $request->tag[$item_key]==""){
                        $rules["tag.{$item_key}"] = 'required';
                        $msgs["tag.{$item_key}.required"] = "Item Tag required !";
                    }
                }
                
            }
            $validator =Validator::make($request->all(),$rules,$msgs);
            if($validator->fails()){
                return ['status'=>false,'errors'=>$validator->errors()];
            }else{
                $insert = $item_count = $ok_count = 0;
                if(isset($request->tag)){
                    $tag_arr = array_filter($request->tag);
                    $ttl_tags_count = count($tag_arr);
                    $ttl_tags_uniq = count(array_unique($tag_arr));
                    if($ttl_tags_count !== $ttl_tags_uniq){
                        return response()->json(['status'=>false,'msg'=>"Tags Can't Be Repeat !"]);
                    }
                }
                $existcount = InventoryStock::whereIn('tag', $tag_arr)->count();
                if($existcount > 0){
                    return response()->json(['status'=>false,'msg'=>"Tags Already in Use !"]);
                }
                try{
                    DB::beginTransaction();
                    foreach($request->item as $item_key=>$item){
                            if($item!=""){
                            $item_count++;
                            $cost = ($request->piece[$item_key] * $request->rate[$item_key]);
                            $cost+= ($request->other[$item_key]??0);
                            $disc = $request->disc[$item_key];

                            if($request->disc[$item_key] && $request->discunit[$item_key]){
                                if($request->discunit[$item_key]=='p'){
                                    $disc = ($cost * $disc)/100;
                                }
                            }else{
                                $disc = 0;
                            }
                            $cost = $cost - $disc;
                            //echo $cost.'<br>';
                            //echo $request->ttl[$item_key].'<br>';
                            //echo "Hello".($cost==$request->ttl[$item_key]).'<br>';
                            //exit();
                            if($cost==$request->ttl[$item_key]){
                                $ok_count++;
                            }else{
                                break;
                            }
                            $row_data = [
                                "name"=>$item,
                                "item_id"=>$request->type[$item_key],
                                "group_id"=>StockItem::find($request->type[$item_key])->group_id,
                                'gross'=>$request->gross[$item_key],
                                'avail_gross'=>$request->gross[$item_key],
                                'net'=>$request->net[$item_key],
                                'avail_net'=>$request->net[$item_key],
                                'rate'=>$request->rate[$item_key],
                                "remark"=>$request->remark[$item_key],
                                'entry_num'=>$request->entry_num,
                                'stock_type'=>$request->stock_type,
                                'entry_mode'=>$request->entry_type,
                                'total'=>$request->ttl[$item_key],
                                "shop_id"=>auth()->user()->shop_id,
                                "branch_id"=>auth()->user()->branch_id,
                            ];
                            if($request->tag[$item_key] || $request->piece[$item_key]){
                                if($request->tag[$item_key]){
                                    $row_data['tag'] = $request->tag[$item_key];
                                }
                                $row_data['count'] = $row_data['avail_count'] = @$request->piece[$item_key]??1;
                            }
                            if($request->disc[$item_key] && $request->discunit[$item_key]){
                                $row_data['discount'] = $request->disc[$item_key];
                                $row_data['discount_unit'] = $request->discunit[$item_key];
                            }
                            if($request->other[$item_key]){
                                $row_data['charge'] = $request->other[$item_key];
                            }
                            $image_url = false;
                            if(isset($request->file('image')[$item_key])){
                                $image = $request->file('image')[$item_key];
                                $filename = str_replace(" ","_",$item)."_".time().".".strtolower($image->getClientOriginalExtension());
                                if($image->move(public_path('assets/images/stocks/'), $filename)){
                                    $image_url = asset('assets/images/stocks/' . $filename);
                                    $row_data["image"] = $image_url;
                                }
                            }
                            /*echo '<pre>';
                            print_r($row_data);
                            echo '<pre>';*/
                            //$this->daily_txn_arr = $row_data;
                            $stock = InventoryStock::create($row_data);
                            if($stock){
                                $insert++;
                                $this->daily_txn_arr = array_merge($this->daily_txn_arr, $row_data);
                                $this->savestocktransaction();
                                $response = $this->newstockelementcreate($request,$stock,$item_key);
                            }elseif($image_url){
                                @unlink($image_url);
                            } 
                        }
                    }
                    if($ok_count==$item_count){
                        DB::commit();
                        if($insert>0){
                            $msg = ($insert==$item_count)?"Stock Saved Succesfully !":"Only {$insert} Saved To Stock !";
                            $print = ($request->stock=='print')?$request->stock:false;
                            $response = ['status'=>true,'msg'=>$msg,'next'=>route('stock.new.recent',[strtolower($stock->stock_type),$stock->entry_num,$print])];
                        }else{
                            $msg = ($item_count > 0)?"Failed to Save Stock !":"Not Item to Save !";
                            $response = ['status'=>false,'msg'=>$msg];
                        }
                    }else{
                        $response = ['status'=>false,'msg'=>"Wrong Calculations at Item Check Manually !"];
                    }
                    //return response()->json($response);
                    return $response;
                }catch(PDOException $e){
                    DB::rollBack();
                    return ['status'=>false,'msg'=>"Operation Failed ".$e->getMessage()];
                }
            }
        }else{
            return ['status'=>false,'msg'=>'Nothing to Save !'];
        }
    }

    private function newstockelementcreate(Request $request,$stock,$item_key){
        $response = false;
        if(isset($request->ele_name[$item_key]) && count($request->ele_name[$item_key])>0){
            // echo '<pre>';
            // echo "NUM {$item_key}->";
            // print_r($request->ele_name[$item_key]);
            // echo '</pre>';
            foreach($request->ele_name[$item_key] as $elekey=>$ele){
                if($ele!=""){
                    $ele_rules["ele_name.{$item_key}.{$elekey}"] = 'required';
                    $ele_rules["ele_gross.{$item_key}.{$elekey}"] = 'required';
                    $ele_rules["ele_net.{$item_key}.{$elekey}"] = 'required';
                    $ele_rules["ele_fine.{$item_key}.{$elekey}"] = 'required';
                    $ele_rules["ele_cost.{$item_key}.{$elekey}"] = 'required';

                    $ele_msgs["ele_name.{$item_key}.{$elekey}.required"] = "Element/Stone required !";
                    $ele_msgs["ele_gross.{$item_key}.{$elekey}.required"] = "Element/Stone Gross Weight Required !";
                    $ele_msgs["ele_net.{$item_key}.{$elekey}.required"] = "Element/Stone Net Weight Required !";
                    $ele_msgs["ele_fine.{$item_key}.{$elekey}.required"] = "Element/Stone Fine Weight required !";
                    $ele_msgs["ele_cost.{$item_key}.{$elekey}.required"] = "Element/Stone Cost required !";
                }
            }   
            $ele_validator =Validator::make($request->all(),$ele_rules,$ele_msgs);
            if($ele_validator->fails()){
                return response()->json(['errors'=>$ele_validator->errors()]);
            }else{
                $element_insert = $item_element_count = 0;
                // echo "INNER<br>";
                // echo '<pre>';
                // print_r($request->ele_name[$item_key]);
                // echo '</pre>';
                    foreach($request->ele_name[$item_key] as $elekey=>$ele){
                    if($ele!=""){
                        $item_element_count++;
                        $ele_row_data = [
                            "inventory_stock_id"=>$stock->id,
                            "element"=>$ele,
                            "item_id"=>$stock->item_id,
                            "group_id"=>$stock->group_id,
                            "gross"=>$request->ele_gross[$item_key][$elekey],
                            "net"=>$request->ele_net[$item_key][$elekey],
                            "fine"=>$request->ele_fine[$item_key][$elekey],
                            "cost"=>$request->ele_cost[$item_key][$elekey],
                            "shop_id"=>auth()->user()->shop_id,
                            "branch_id"=>auth()->user()->branch_id,
                        ];
                        if($request->ele_caret[$item_key][$elekey]){
                            $ele_row_data['caret'] = $request->ele_fine[$item_key][$elekey];
                        }
                        if($request->ele_part[$item_key][$elekey]){
                            $ele_row_data['part'] = $request->ele_part[$item_key][$elekey];
                        }
                        if($request->ele_color[$item_key][$elekey]){
                            $ele_row_data['colour'] = $request->ele_color[$item_key][$elekey];
                        }
                        if($request->ele_piece[$item_key][$elekey]){
                            $ele_row_data['piece'] = $request->ele_piece[$item_key][$elekey];
                        }
                        if($request->ele_clear[$item_key][$elekey]){
                            $ele_row_data['clarity'] = $request->ele_clear[$item_key][$elekey];
                        }
                        if($request->ele_less[$item_key][$elekey]){
                            $ele_row_data['less'] = $request->ele_less[$item_key][$elekey];
                        }
                        if($request->ele_tunch[$item_key][$elekey]){
                            $ele_row_data['tunch'] = $request->ele_tunch[$item_key][$elekey];
                        }
                        if($request->ele_wstg[$item_key][$elekey]){
                            $ele_row_data['wastage'] = $request->ele_wstg[$item_key][$elekey];
                        }
                        if($request->ele_rate[$item_key][$elekey]){
                            $ele_row_data['rate'] = $request->ele_rate[$item_key][$elekey];
                        }
                        $stock_element = InventoryStockElement::create($ele_row_data);
                        ($stock_element)?$element_insert++:null;
                    }
                }
                if($element_insert>0){
                    $stock->update(['have_element'=>1]);
                    $msg = ($element_insert==$item_element_count)?"Stock Elements Saved Succesfully !":"Only {$element_insert} Items Elements Saved To Stock !";
                    $response = ['status'=>true,'msg'=>$msg];
                }else{
                    $msg = ($item_element_count > 0)?"Failed to Save Stock  Element !":"Not Item Elements to Save !";
                    $response = ['status'=>false,'msg'=>$msg];
                }
            }
        }
        return $response;
    }
    
    public function newrecentstockview(Request $request,$recent_stock=false,$entry_num=false,$print=false){
        $cond = ['entry_num'=>$entry_num,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,"stock_type"=>$recent_stock];
        $entry_data = InventoryStock::select('entry_num','entry_date','entry_mode','stock_type')->where($cond)->first();
        //dd($entry_data);
        $stock_data = InventoryStock::where($cond)->get();
        //dd($stock_data);
        return view("vendors.stocks.newpage.recentnewstock",compact('stock_data','entry_data','print'));
        // if($request->ajax()){
        //     //return view("vendors.stocks.content.recent{$form_name}stock");
        // }else{
        //    return view("vendors.stocks.newpage..recentnewstock",compact('recent_stock','entry_num'));
        // }
    }

    public function newaddstockform(Request $request,$item=false){
        if($request->ajax()){
            $item_form = ($item)?str_replace([' ', '-'],"",strtolower($item)):false;
            if(View::exists("vendors.stocks.newpage.itemforms.{$item_form}items")){
                $num = $request->num??0;
                $entry_num = InventoryStock::where(['stock_type'=>$item,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->max('entry_num');
                $entry_num = ($entry_num)?$entry_num+1:1;
                echo view("vendors.stocks.newpage.itemforms.{$item_form}items",compact('num','entry_num'))->render();
            }else{
                echo "<b class='text-warning'>Invalid Selection !</b>";
            }
        }else{
            echo "Invalid Operation !";
        }
    }

    public function editstock(Request $request){
        if($request->ajax()){
            $items = array_filter($request->item);
            $item_count = count($items);
            if($item_count>0){
				$tags = $request->tag;
                $input_tag_repeat = array_unique(array_diff_assoc($tags, array_unique($tags)));
				if(empty($input_tag_repeat)){
                    $exist_tags = InventoryStock::whereIn('tag', $tags)->whereNotIn('id', $items)->where([
                            'shop_id'   => auth()->user()->shop_id,
                            'branch_id' => auth()->user()->branch_id,
							])->pluck('tag')->unique()->values()->toArray();
					if(empty($exist_tags)){
						$update_count = 0;
						foreach($items as $ik=>$item){
							if($item!=""){
								$item_data = InventoryStock::find($item);
                                $old_item_value = $item_data->toArray();
								/*echo $item_data->entry_mode.'<br>';
								echo $item_data->tag.'<br>';
								echo $item_data->gross.'<br>';
								echo $item_data->avail_gross.'<br>';
								exit();*/
								if((strtolower($item_data->entry_mode) != 'tag' && $item_data->tag=="") || ($item_data->gross == $item_data->avail_gross)){
									$item_data->tag = $request->tag[$ik]??null;
									$item_data->rfid = $request->rfid[$ik]??null;
									$item_data->count = $item_data->avail_count = $request->piece[$ik]??null;
									$item_data->huid = $request->huid[$ik]??null;
									$item_data->caret = $request->caret[$ik]??null;
									$item_data->gross = $item_data->avail_gross = $request->gross[$ik]??false;
									$item_data->less = $request->less[$ik]??null;
									$item_data->net = $item_data->avail_net = $request->net[$ik]??false;
									$item_data->tunch = $request->tunch[$ik]??null;
									$item_data->wastage = $request->wstg[$ik]??null;
									$item_data->fine = $item_data->avail_fine = $request->fine[$ik]??false;
									$item_data->element_charge = $request->chrg[$ik]??null;
									$item_data->charge = $request->other[$ik]??null;
									$item_data->rate = $request->rate[$ik]??null;
									$item_data->total = $request->ttl[$ik]??null;
									$item_data->labour = $request->lbr[$ik]??null;
									$item_data->labour_unit = (!empty($item_data->labour))?$request->lbrunit[$ik]:null;
									$item_data->discount = $request->disc[$ik]??null;
									$item_data->discount_unit = (!empty($item_data->discount))?$request->discunit[$ik]:null;
									if($request->file("image.{$ik}")) {
										$image = $request->file("image.$ik");
										// create folder if missing
										$path = public_path('assets/images/stocks/');
										if (!file_exists($path)) {
											mkdir($path, 0775, true);
										}
										// unique filename (clean name + timestamp)
										$filename = str_replace(" ","_",$item_data->name)."_".time().".".strtolower($image->getClientOriginalExtension());

										// move file
										if ($image->move($path, $filename)) {
											// delete old image safely if exists
											if (!empty($item_data->image) && file_exists(public_path($item_data->image))) {
												@unlink(public_path($item_data->image));
											}
											// save new path relative to public
											$item_data->image = 'assets/images/stocks/' . $filename;
										}
									}
									DB::beginTransaction();
									try{
										if($item_data->save()){
											if(isset($request->ele_name[$ik]) && !empty($request->ele_name[$ik])){
												$this->updateitemelements($request,$ik,$item_data);
											}
											$undo_data = $old_item_value;
											$undo_data['action'] = "E";
											$undo_data['status'] = "0";
											$this->savestocktransaction($undo_data,false);
											$item_data->action = 'U';
											$this->savestocktransaction($item_data);
											$update_count++;
											DB::commit();
										}
									}catch(PDOException $e){
											DB::rollBack();
											return response()->json(['status'=>false,"msg"=>"Operation Failed !".$e->getMessage()]);
									}
								}else{
									return response()->json(['status'=>false,'msg'=>"Item Can't Be Updated !"]);
								}
							}
						}
						if($update_count > 0 ){
							$msg = ($update_count == $item_count)?'Stock Succesfully Updated !':"Only {$update_count} Items Updated !";
							return response()->json(['status'=>true,'msg'=>$msg]);
						}else{
							return response()->json(['status'=>false,'msg'=>"operation Failed !"]);
						}
                         
                        $data = [
                        'title' => 'Stock Updated',
                        'message' => $stock->item_name.' stock updated',
                        'link' => route('stock.new.inventory'),
                        'stock_type' => $stock->stock_type
                    ];

                    auth()->user()->notify(new StockNotification($data));




					}else{
						$db_tags = implode('|',$exist_tags);
						return response()->json(['status'=>false,'msg'=>"Tag already in Use !<br><b>{$db_tags}</b>"]);
					}
				}else{
					$input_tags = implode('|',$input_tag_repeat);
                    return response()->json(['status'=>false,'msg'=>"Tag Can't be Repeat !<br><b>{$input_tags}</b>"]);
				}
                
            }else{
                return response()->json(['status'=>false,'msg'=>"No Item to Update !"]);
            }
        }else{
            $items[] = false;
			//print_r($request->toArray());
            $stock_cat = str_replace([" ","_"],"-",strtolower($request->option_stock));
			//echo $stock_cat;
            if(!is_array($request->item)){
                $items[] = $request->item;
            }else{
                $items = $request->item;
            }
			
            $stocks = InventoryStock::whereIn('id',$items)->where(["stock_type"=>$stock_cat,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->get();
			
			$view_common_path = "vendors.stocks.newpage.";
            if(View::exists("{$view_common_path}itemforms.editform.edit{$stock_cat}stock")){
                return view("{$view_common_path}itemforms.editform.edit{$stock_cat}stock",compact('stock_cat','stocks'));
            }else{
                return view("{$view_common_path}editmetalstock",compact('stock_cat','stocks'));
            }
			
            //return view('vendors.stocks.newpage.editmetalstock',compact('stock_cat','stocks'));
        }
    }

	private function updateitemelements(Request $request,$index,$item){
        $elemens = array_filter($request->ele_name[$index]);
        // echo '<pre>';
        // print_r($elemens);
        // echo '</pre>';
        $rules = [];
        $msgs = [];
        $avail = [];
        foreach($elemens as $ele_k=>$elemen){
            $rules["ele_name.{$index}.{$ele_k}"]='required';
            $msgs["ele_name.{$index}.{$ele_k}.required"] = 'Element Name Required !';
            $rules["ele_cost.{$index}.{$ele_k}"]='required';
            $msgs["ele_cost.{$index}.{$ele_k}.required"] = 'Element Cost Required (Default is 0)!';
            if(isset($request->ele_gross[$index][$ele_k])){
                $rules["ele_net.{$index}.{$ele_k}"]='required';
                $msgs["ele_net.{$index}.{$ele_k}.required"] = 'Element Net Required !';
            }
        }
        $validator = Validator::make($request->all(),$rules,$msgs);
        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=>$validator->errors()]);
        }else{
            foreach($elemens as $elek=>$ele){
                //--The Element Array That will Either Save/Update--//
                $ele_input['inventory_stock_id'] = $item->id;
                $ele_input['item_id'] = $item->item_id;
                $ele_input['group_id'] = $item->group_id;
                $ele_input['element'] = $ele;
                $ele_input['caret'] = $request->ele_caret[$index][$elek];
                $ele_input['part'] = $request->ele_part[$index][$elek];
                $ele_input['colour'] = $request->ele_colour[$index][$elek];
                $ele_input['piece'] = $request->ele_piece[$index][$elek];
                $ele_input['clarity'] = $request->ele_clear[$index][$elek];
                $ele_input['gross'] = $request->ele_gross[$index][$elek];
                $ele_input['less'] = $request->ele_less[$index][$elek];
                $ele_input['net'] = $request->ele_net[$index][$elek];
                $ele_input['wastage'] = $request->ele_wstg[$index][$elek];
                $ele_input['fine'] = $request->ele_fine[$index][$elek];
                $ele_input['tunch'] = $request->ele_tunch[$index][$elek];
                $ele_input['rate'] = $request->ele_rate[$index][$elek];
                $ele_input['cost'] = $request->ele_cost[$index][$elek];
                $ele_input['shop_id'] = auth()->user()->shop_id;
                $ele_input['branch_id'] = auth()->user()->branch_id;
                //--END => The Element Array That will Either Save/Update--//
                $pre_ele_id = $request->ele_ids[$index][$elek]??false;
                $pre_del_ele_id = $request->del_ele_id[$index][$elek]??false;
                if(!$pre_ele_id){
                    InventoryStockElement::create($ele_input);
                }else{
                    $element = InventoryStockElement::find($request->ele_ids[$index][$elek]);
                    if($pre_ele_id == $pre_del_ele_id){
                        $element->delete();
                    }else{
                       $element->update();
                    }
                }
            }
        }
    }

    public function deletestock(Request $request){
        if($request->ajax()){
            $items = [];
            $stock_cat = str_replace(["_"," "],"-",strtolower($request->option_stock));
			if(isset($request->item)){
                if(!is_array($request->item)){
                    //array_push($request->item,$items);
                    $items = [$request->item];
                }else{
                    $items = $request->item;
                }
            }
			if(!empty($items)){
				DB::beginTransaction();
				$response['status'] = false;
				try{
					$del_stock_query = InventoryStock::whereIn('id',$items)->where(["stock_type"=>$stock_cat,'shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->where(function($q){
																				$q->where('avail_net', 0)
																				  ->orWhereColumn('avail_net', 'net');
																			});
						
					$stock_items_data = (clone $del_stock_query)->get();
					$fetched_ids = $stock_items_data->pluck('id')->toArray();
					
					$del_ele = InventoryStockElement::whereIn('inventory_stock_id', $items)->where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->delete();
					
					$del_stock = $del_stock_query->delete();
					if($del_stock){
						$array_diff = array_diff($fetched_ids, $items);
						//$msg_odd_on = implode('~',$array_diff);
						foreach($stock_items_data as $k=>$item_row){
							$image = ($item_row->image !="" && file_exists(public_path($item_row->image)))?public_path($item_row->image):false;
							if($image) {
								@unlink($image);
							}
                            $item_row['action'] = 'D';
                            $item_row['status'] = '0';
                            $this->savestocktransaction($item_row);
                        }
						$response['status'] = true;
                        $response['msg'] = "Stock Item Succesfully Deleted !";
                        $response['done'] = $items;
						$response["skip"] = $array_diff;
						//$response = ['status'=>true,"msg"=>'Stock item Succesfully Deleted !','done'=>$items,"skip"=>$array_diff];
					}else{
						$response['msg'] = "Stock Item Deletion Failed !";
					}
					if($del_stock){
                        DB::commit();
                        $count = $stock_items_data->count();

                        $data = [
                            'title' => 'Stock Deleted',
                            'message' => $count . ' stock items deleted',
                            'link' => route('stock.new.inventory'),
                            'stock_type' => $stock_cat
                        ];

                        auth()->user()->notify(new StockNotification($data));
                    }
                    return response()->json($response);
				}catch(PDOException $e){
					DB::rollBack();
					return response()->json(['status'=>false,"msg"=>"Operation Failed !".$e->getMessage()]);
				}
			}else{
				 return response()->json(['status'=>false,"msg"=>'Select Item to Delete !']);
			}
        }else{
            return response()->json(['status'=>false,"msg"=>'No Item to Delete !']);
        }
    }
    
/**
 * 
 * END OF NEW STOCK CREATE
 * 
 */

}
