<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\UdharAccount;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrollCustomer;
use App\Models\ShopScheme;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillTransaction;
use App\Models\UdharTransaction;
use App\Models\InventoryStock;
use App\Models\AnjumanScheme;
use App\Models\AnjumanSchemeEnroll;
use App\Models\AnjumanSchemeTxns;
use App\Models\NewBillPayment;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HomeController extends Controller {

    /*public function index() {

        return view('vendors.dashboard.home') ;

    }*/

    public function index() {
        $vendorId = auth()->id();
        $shopId = auth()->user()->shop_id; // आपके current shop
        $branchId = auth()->user()->branch_id; // आपके current branch
        
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Top Tiles Data
        $totalCustomers = Customer::where('shop_id', $shopId)
                                ->where('branch_id', $branchId)
                                ->count();
        // dd( $totalCustomers);
        // Girvi data - आपके system में Girvi के लिए अलग table नहीं है, bills से लेते हैं
        /*$activeLoans = Bill::where('shop_id', $shopId)
                         ->where('branch_id', $branchId)
                         ->where('balance', '>', 0)
                         ->count();*/
        $activeLoans = null;

        // 2. Inventory Stock Data
        $goldStock = InventoryStock::where('shop_id', $shopId)
                        ->where('branch_id', $branchId)
                        ->where('stock_type', 'gold')
                        ->where('avail_net','>',0)
                        ->sum('avail_net');

        $silverStock = InventoryStock::where('shop_id', $shopId)
                          ->where('branch_id', $branchId)
                          ->where('stock_type', 'silver')
                          ->where('avail_net','>',0)
                          ->sum('avail_net');

        $artificialJewelry = InventoryStock::where('shop_id', $shopId)
                                ->where('branch_id', $branchId)
                                ->where('stock_type', 'artificial')
                                ->where('avail_count','>',0)
                                ->sum('avail_count');

        $stones = InventoryStock::where('shop_id', $shopId)
                     ->where('branch_id', $branchId)
                     ->where('stock_type', 'stone')
                     ->where('avail_net','>',0)
                     ->count();

        $frenchise =InventoryStock::where('shop_id', $shopId)
                     ->where('branch_id', $branchId)
                     ->where('stock_type', 'Franchise-Jewellery')
                     ->where('avail_net','>',0)
                     ->count();
        
        // 3. Billing & Sales Data
        $totalSellToday = Bill::where('shop_id', $shopId)
                            ->where('branch_id', $branchId)
                            ->where('bill_type', 's')
                            ->whereDate('bill_date', $today)
                            ->sum('final');

        $transactionCountToday = Bill::where('shop_id', $shopId)
                                   ->where('branch_id', $branchId)
                                   ->where('bill_type', 's')
                                   ->whereDate('bill_date', $today)
                                   ->count();

        $paymentReceivedToday = NewBillPayment::where('shop_id', $shopId)
                                            ->where('branch_id', $branchId)
                                            ->whereDate('created_at', $today)
                                            ->where('pay_effect', '1')
                                            ->where('pay_source','!=' ,'metal')
                                            ->sum('pay_value');
        //dd($paymentReceivedToday);
        $pendingBills = Bill::where('shop_id', $shopId)
                          ->where('branch_id', $branchId)
                          ->where('bill_type', 's')
                          /*->where('balance', '<', 0)*/
                          ->where('status','!=','r')
                          ->sum('balance');

        $udhar_query =  UdharAccount::where(['shop_id'=>$shopId,'branch_id'=>$branchId]);

        // 4. Udhari Data - Bills से लेते हैं जहाँ balance > 0
        $totalUdhari = (clone $udhar_query)
                          ->selectRaw("SUM(CASE WHEN custo_amount_status = '1' THEN ABS(custo_amount) ELSE -ABS(custo_amount) END) as amnt_udhar")
                          ->value('amnt_udhar');

        $totalcashUdhari = (clone $udhar_query)
                          ->selectRaw("SUM(CASE WHEN custo_amount_status = '1' THEN ABS(custo_amount) ELSE -ABS(custo_amount) END) as amnt_udhar")
                         /* ->whereIn('source',['D','C'])
                          ->whereIn('action',['A','U'])*/
                          ->value('amnt_udhar');

        // Gold/Silver Udhari के लिए bill_items से calculate करते हैं
        $goldUdhariItems = (clone $udhar_query)
                            ->selectRaw("SUM(CASE WHEN custo_gold_status = '1' THEN ABS(custo_gold) ELSE -ABS(custo_gold) END) as gld_udhar")
                            /*->whereIn('source',['D','C'])
                            ->whereIn('action',['A','U'])*/
                           ->value('gld_udhar');

        $silverUdhariItems = (clone $udhar_query)
                            ->selectRaw("SUM(CASE WHEN custo_silver_status = '1' THEN ABS(custo_silver) ELSE -ABS(custo_silver) END) as slvr_udhar")
                            /*->whereIn('source',['D','C'])
                            ->whereIn('action',['A','U']) */
                           ->value('slvr_udhar');
        /*$silverUdhariItems = DB::table('bill_items')
                             ->join('bills', 'bill_items.bill_id', '=', 'bills.id')
                             ->where('bills.shop_id', $shopId)
                             ->where('bills.branch_id', $branchId)
                             ->where('bills.balance', '>', 0)
                             ->where('bill_items.stock_type', 'silver')
                             ->sum('bill_items.net');*/

        $anjactiveSchemes = AnjumanScheme::where('shop_id', $shopId)
                                    ->where('branch_id', $branchId)
                                    ->where('status', '1')
                                    ->count();

        $schmactivescheme = ShopScheme::where('shop_id', $shopId)
                                    ->where('ss_status', '1')
                                    ->count();

        // 5. Schemes Data
        $activeSchemes = $anjactiveSchemes + $schmactivescheme;
        
        $anjschemeCustomers = AnjumanSchemeEnroll::where('shop_id', $shopId)
                                            ->where('branch_id', $branchId)
                                            ->where('status', '1')
                                            ->count('custo_id');
        $schschemeCustomers = EnrollCustomer::where('shop_id', $shopId)
                                            ->where('branch_id', $branchId)
                                            ->whereHas('schemes', function ($q) {
                                                $q->where('ss_status', 1);
                                            })->count('customer_id');

        $schemeCustomers = $anjschemeCustomers + $schschemeCustomers;
        $dueThisMonth = 0; // आपके logic के according calculate करें

        $collectedTodayScheme = AnjumanSchemeTxns::where('shop_id', $shopId)
                                              ->where('branch_id', $branchId)
                                              ->where('txn_status', '1')
                                              ->whereDate('txn_date', $today)
                                              ->sum('txn_quant');

        // 6. Monthly Earnings for Chart (Last 6 months)
        $monthlyEarnings = Bill::where('shop_id', $shopId)
                             ->where('branch_id', $branchId)
                             ->where('bill_type', 's')
                             ->whereYear('bill_date', $currentYear)
                             ->selectRaw('MONTH(bill_date) as month, SUM(payment) as total')
                             ->groupBy('month')
                             ->orderBy('month')
                             ->pluck('total', 'month')
                             ->toArray();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyEarnings[$i] ?? 0;
        }

        // 7. Additional calculations
        $overdueAmount = Bill::where('shop_id', $shopId)
                           ->where('branch_id', $branchId)
                           ->where('balance', '>', 0)
                           ->where('due_date', '<', $today)
                           ->sum('balance');

        $collectedThisMonth = NewBillPayment::where('shop_id', $shopId)
                                          ->where('branch_id', $branchId)
                                          ->whereMonth('created_at', $currentMonth)
                                          ->whereYear('created_at', $currentYear)
                                          ->where('pay_source','!=','metal')
                                          ->sum('pay_value');

        // 8. Current Rates - ab DB se live + 24h stats
        $ratesData = $this->getCurrentRates($shopId, $branchId);

        $rates            = $ratesData['rates'];
        $change           = $ratesData['change'];
        $goldHigh24h      = $ratesData['gold_high_24h'];
        $goldLow24h       = $ratesData['gold_low_24h'];
        $silverHigh24h    = $ratesData['silver_high_24h'];
        $silverLow24h     = $ratesData['silver_low_24h'];
        $ratesLastUpdated = $ratesData['last_updated']; 

        return view('vendors.dashboard.home', compact(
            'totalCustomers',
            'activeLoans',
            'goldStock',
            'silverStock', 
            'artificialJewelry',
            'stones',
            'frenchise',
            'totalSellToday',
            'transactionCountToday',
            'paymentReceivedToday',
            'pendingBills',
            'totalUdhari',
            'goldUdhariItems',
            'silverUdhariItems',
            'totalcashUdhari',
            'activeSchemes',
            'schemeCustomers',
            'dueThisMonth',
            'collectedTodayScheme',
            'chartData',
            'overdueAmount',
            'collectedThisMonth',
            'rates',
            'goldHigh24h',
            'goldLow24h',
            'silverHigh24h',
            'silverLow24h',
            'ratesLastUpdated',
            'change'
        ));
    
        //return view('vendors.dashboard.home') ;
    }

    /**
     * Current rates + 24h high/low + last updated
     */
    private function getCurrentRates($shopId, $branchId)
    {
        // Active/latest rate row pick karenge is shop + branch ka
        $rate = Rate::where('shop_id', $shopId)
                    ->where('branch_id', $branchId)
                    ->where('active', '1')
                    ->orderByDesc('id') // latest active row, just in case
                    ->first();

        // Agar kuch nahi mila toh fallback hardcoded (taaki system toot na jaye)
        if (!$rate) {
            return [
                'rates' => [
                    'gold_24k' => 6865,
                    'gold_22k' => 6295,
                    'gold_20k' => 6295,
                    'gold_18k' => 5855,
                    'silver'   => 81.5,
                ],
                'change' => [
                    'gold_24k' => 0,
                    'silver'   => 0,
                ],
                'gold_high_24h'   => 6865,
                'gold_low_24h'    => 6865,
                'silver_high_24h' => 81.5,
                'silver_low_24h'  => 81.5,
                'last_updated'    => null,
            ];
        }

        // Previous record (for percentage change)
        $prev = Rate::where('shop_id', $shopId)
            ->where('branch_id', $branchId)
            ->where('id', '<', $rate->id)
            ->orderByDesc('id')
            ->first();

        // Current main values from DB
        $gold24 = $rate->gold_rate;    // 24K base rate
        $silver = $rate->silver_rate;

        // Simple conversion: 22k = 24k * 22/24, 20k = 24k * 20/24, 18k = 24k * 18/24
        $gold22 = round($gold24 * 22 / 24);
        $gold20 = round($gold24 * 20 / 24);
        $gold18 = round($gold24 * 18 / 24);

        // Percentage change calc (current vs previous)
        $prevGold   = $prev->gold_rate   ?? $gold24;
        $prevSilver = $prev->silver_rate ?? $silver;

        $goldChange = $prevGold > 0
            ? round((($gold24 - $prevGold) / $prevGold) * 100, 2)
            : 0;

        $silverChange = $prevSilver > 0
            ? round((($silver - $prevSilver) / $prevSilver) * 100, 2)
            : 0;

        // Last 24 hours window for high/low
        $since = Carbon::now()->subDay();

        $windowQuery = Rate::where('shop_id', $shopId)
            ->where('branch_id', $branchId)
            ->where('created_at', '>=', $since);

        $goldHigh24h   = $windowQuery->max('gold_rate')   ?? $gold24;
        $goldLow24h    = $windowQuery->min('gold_rate')   ?? $gold24;
        $silverHigh24h = $windowQuery->max('silver_rate') ?? $silver;
        $silverLow24h  = $windowQuery->min('silver_rate') ?? $silver;

        return [
            'rates' => [
                'gold_24k' => $gold24,
                'gold_22k' => $gold22,
                'gold_20k' => $gold20,
                'gold_18k' => $gold18,
                'silver'   => $silver,
            ],
            'change' => [
                'gold_24k' => $goldChange,
                'silver'   => $silverChange,
            ],
            'gold_high_24h'   => $goldHigh24h,
            'gold_low_24h'    => $goldLow24h,
            'silver_high_24h' => $silverHigh24h,
            'silver_low_24h'  => $silverLow24h,
            'last_updated'    => optional($rate->updated_at ?? $rate->created_at)->format('d M Y, h:i A'),
        ];
    }

    /**
     * AJAX endpoint: latest rates + formatted values
     */
    public function getRatesAjax(Request $request)
    {
        $shopId   = 31; // ya auth()->user()->shop_id
        $branchId = 33; // ya auth()->user()->branch_id

        $data   = $this->getCurrentRates($shopId, $branchId);
        $rates  = $data['rates'];
        $change = $data['change'];

        return response()->json([
            'success' => true,

            'gold_24k' => $rates['gold_24k'],
            'gold_22k' => $rates['gold_22k'],
            'gold_20k' => $rates['gold_20k'],
            'gold_18k' => $rates['gold_18k'],
            'silver'   => $rates['silver'],

            'gold_24k_formatted' => number_format($rates['gold_24k']),
            'gold_22k_formatted' => number_format($rates['gold_22k']),
            'gold_20k_formatted' => number_format($rates['gold_20k']),
            'gold_18k_formatted' => number_format($rates['gold_18k']),
            'silver_formatted_1' => number_format($rates['silver'], 1),
            'silver_kg_formatted'=> number_format($rates['silver'] * 1000, 0),

            'gold_high_24h'      => number_format($data['gold_high_24h']),
            'gold_low_24h'       => number_format($data['gold_low_24h']),
            'silver_high_24h'    => number_format($data['silver_high_24h']),
            'silver_low_24h'     => number_format($data['silver_low_24h']),

            'gold_change'        => $change['gold_24k'],
            'silver_change'      => $change['silver'],
            'gold_change_formatted'   => ($change['gold_24k'] >= 0 ? '+' : '') . number_format($change['gold_24k'], 2) . '%',
            'silver_change_formatted' => ($change['silver']   >= 0 ? '+' : '') . number_format($change['silver'],   2) . '%',

            'last_updated_human' => $data['last_updated'],
        ]);
    }

    public function comingsoon() {

        return view('vendors.dashboard.comingsoon') ;

    }

    public function subscription_timer() {

        return view('vendors.settings.subscriptions.subscription_timer') ;

    }



}
