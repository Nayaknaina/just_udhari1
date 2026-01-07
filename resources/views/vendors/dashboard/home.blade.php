{{-- resources/views/vendors/dashboard.blade.php --}}
@extends('layouts.vendors.app')

@section('content')
  <style>
    .border-top-custom {
      border: none;
      border-top: 1px solid lightgray !important;
    }

    .border-top-custom {
      border: none;
      border-top: 1px solid lightgray !important;
    }

    .rate-section-card {
      border-radius: 16px;
      padding: 16px 18px;
      background: radial-gradient(circle at top left, #ffe1c4 0, #fff7f0 32%, #ffffff 80%);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.04);
    }

    .rate-heading {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .rate-subtitle {
      font-size: 0.78rem;
      color: #888;
    }

    .rates-wrap {
      gap: 10px;
    }

    .metal-card {
      border-radius: 12px;
      padding: 10px 12px;
      background: rgba(255, 255, 255, 0.85);
      border: 1px solid #f1e2d5;
      min-width: 210px;
    }

    .metal-title {
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #ff7a1a;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .metal-title i {
      font-size: 0.9rem;
    }

    .metal-main-rate {
      font-size: 1.1rem;
      font-weight: 700;
      margin-top: 4px;
      display: flex;
      align-items: baseline;
      gap: 6px;
    }

    .metal-main-rate small {
      font-size: 0.7rem;
      color: #777;
    }

    .rate-badges {
      margin-top: 6px;
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }

    .rate-chip {
      font-size: 0.72rem;
      padding: 3px 8px;
      border-radius: 999px;
      background: #fff7f0;
      border: 1px solid #ffe0c4;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .rate-chip span.value {
      font-weight: 600;
    }

    .silver-card {
      border-radius: 12px;
      padding: 10px 12px;
      background: #f5f9ff;
      border: 1px solid #d9e4ff;
      min-width: 180px;
    }

    .silver-card .metal-title {
      color: #4f6ddf;
    }

    .silver-card .rate-chip {
      background: #eef3ff;
      border-color: #d9e4ff;
    }

    .btn-update {
      border-radius: 999px;
      font-size: 0.78rem;
      padding: 6px 12px;
      display: flex;
      align-items: center;
      gap: 6px;
      background: #ff7a1a;
      border-color: #ff7a1a;
      color: #fff;
      box-shadow: 0 6px 12px rgba(255, 122, 26, 0.25);
    }

    .btn-update i {
      font-size: 0.8rem;
    }


    /* ===== ULTRA PREMIUM WELCOME ===== */
#welcome-overlay {
  position: fixed;
  inset: 0;
  /* background:
    radial-gradient(circle at center, rgba(255,122,26,0.15), transparent 40%),
    linear-gradient(180deg, #0c0c0c, #000); */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999999;
  backdrop-filter: blur(8px);
}

/* Main Circle */
.lux-circle {
  position: relative;
  width: 320px;
  height: 320px;
  border-radius: 50%;
  background: linear-gradient(145deg, #ff7a1a, #6b3fa0);
  box-shadow:
    0 0 80px rgba(255,122,26,0.55),
    inset 0 0 30px rgba(255,255,255,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: luxPulse 3.5s ease-in-out infinite;
}

/* Rings */
.lux-ring {
  position: absolute;
  inset: -18px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.25);
  pointer-events: none;
}

.lux-ring.slow {
  animation: luxRotate 18s linear infinite;
}

.lux-ring.fast {
  inset: -30px;
  border-style: dashed;
  opacity: 0.6;
  animation: luxRotateReverse 10s linear infinite;
}

/* Core */
.lux-core {
  width: 82%;
  height: 82%;
  background: rgba(0,0,0,0.45);
  border-radius: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #fff;
  text-align: center;
  backdrop-filter: blur(12px);
  box-shadow: inset 0 0 40px rgba(0,0,0,0.6);
}

/* Text */
.lux-tag {
  font-size: 0.7rem;
  letter-spacing: 3px;
  opacity: 0.7;
  margin-bottom: 8px;
}

.lux-core h1 {
  font-size: 2.2rem;
  font-weight: 900;
  background: linear-gradient(90deg, #fff, #ffe2c4);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.lux-core p {
  font-size: 0.85rem;
  opacity: 0.8;
  margin-top: 6px;
  color: white !important;
}

/* Animations */
@keyframes luxRotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes luxRotateReverse {
  from { transform: rotate(360deg); }
  to { transform: rotate(0deg); }
}

@keyframes luxPulse {
  0%,100% { transform: scale(1); }
  50% { transform: scale(1.06); }
}

#welcome-overlay.hide {
  animation: luxFadeOut 1s ease forwards;
}

@keyframes luxFadeOut {
  to {
    opacity: 0;
    transform: scale(1.1);
  }
}

   /* ===== ROYAL NAMASKAR ===== */
.royal-namaskar {
  position: relative;
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: center;
  margin-bottom: 10px;
}

/* Aura behind hands */
.royal-aura {
  position: absolute;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background:
    radial-gradient(circle, rgba(255,193,125,0.6), transparent 65%);
  animation: auraRotate 6s linear infinite;
  z-index: 0;
}

/* Hands */
.namaskar-icon {
  font-size: 2.05rem;
  z-index: 1;
  background: linear-gradient(135deg, #ffd9a0, #ff9f43, #e68a00);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  filter:
    drop-shadow(0 0 6px rgba(255, 159, 67, 0.55))
    drop-shadow(0 0 14px rgba(255, 159, 67, 0.35));
  transform-origin: center bottom;
  animation: royalBow 10.2s ease-out forwards;
  opacity: 0;
}

/* Text */
.namaskar-text {
  font-size: 1rem;
  letter-spacing: 3px;
  opacity: 0.75;
  font-weight: 900;
}

/* Animations */
@keyframes royalBow {
  0% {
    opacity: 0;
    transform: translateY(-6px) scale(0.9);
  }
  30% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
  55% {
    transform: translateY(3px);
  }
  75% {
    transform: translateY(-1px);
  }
  100% {
    transform: translateY(0);
  }
}

@keyframes auraRotate {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}



    @media (max-width: 768px) {
      .rate-section-card {
        padding: 12px 12px;
      }

      .rate-heading {
        margin-bottom: 6px;
      }
    }

    .rate-heading {
      font-size: 1.35rem;
      font-weight: 700;
      letter-spacing: .5px;
      background: linear-gradient(90deg, #ff7a1a, #ffba4b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .rate-subtitle {
      font-size: .95rem;
      font-weight: 600;
      color: #333;
      margin-top: 4px;
      display: flex;
      align-items: center;
      gap: 4px;
      min-height: 1.5rem;
      letter-spacing: .2px;
      text-shadow: 0 0 1px rgba(0, 0, 0, .12);
    }

    .animated-heading {
      animation: fadeSlideIn 0.6s ease-out;
    }

    .typing-cursor {
      display: inline-block;
      font-weight: 700;
      opacity: 1;
      animation: blinkCursor 0.9s steps(1) infinite;
    }

    @keyframes fadeSlideIn {
      from {
        opacity: 0;
        transform: translateY(6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes blinkCursor {

      0%,
      50% {
        opacity: 1;
      }

      50.01%,
      100% {
        opacity: 0;
      }
    }

    .rate-chip-row {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 6px;
    }

    .mini-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 9px;
      border-radius: 999px;
      font-size: 0.72rem;
      background: #f5f5f5;
      border: 1px solid #e0e0e0;
      color: #444;
    }

    .mini-chip i {
      font-size: 0.75rem;
    }

    /* Trend states */
    .mini-chip.trend-up {
      background: #e6f7ec;
      border-color: #b2e2c4;
      color: #1e8a4a;
    }

    .mini-chip.trend-down {
      background: #ffefef;
      border-color: #ffc4c4;
      color: #c0392b;
    }

    .mini-chip.trend-stable {
      background: #eef4ff;
      border-color: #c6d5ff;
      color: #34495e;
    }


    /* LOGO BEHIND EVERYTHING */
.lux-logo-bg {
  position: absolute;
  inset: 0;
  margin: auto;
  width: 140px;           /* adjust size */
  opacity: 0.5;          /* watermark feel */
  /* filter: blur(0.3px) saturate(1.2); */
  z-index: 0;
  pointer-events: none;
  width: 100%;
  height: 100%;
  opacity: 0.1;
}

/* rings above logo */
.lux-ring {
  position: absolute;
  z-index: 1;
}

/* content topmost */
.lux-core {
  position: relative;
  z-index: 2;
  overflow: hidden;
}

.lux-logo-bg {
  filter:
    drop-shadow(0 0 20px rgba(255,140,60,0.35))
    drop-shadow(0 0 40px rgba(255,140,60,0.15));
}


.stock_block {
    transition: 0.25s ease;
}

.stock_block:hover {
    transform: translateY(-6px) scale(1.02);
}

.stock_block:hover .card {
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
/* =====================================
   INVENTORY STOCK – STABLE RESPONSIVE GRID
   ===================================== */

/* Desktop (≥ 992px) → Bootstrap auto */
.inventory-grid {
  display: flex;
  flex-wrap: wrap;
}

/* Tablet (577px – 991px) → 3 per row */
@media (min-width: 820px) and (max-width: 1180px) {
  .inventory-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
  }

 .inventory-grid > .col-md-2 {
  flex: 1 1 auto !important;
  max-width: 100% !important;
}
.inventory-grid > .col-md-4 {
  flex: 1 1 auto !important;
  max-width: 100% !important;
}
.inventory-grid > .col-md-3 {
  flex: 1 1 auto !important;
  max-width: 100% !important;
}
}

/* Phone (≤ 576px) → 2 per row */
@media (max-width: 576px) {
  .inventory-grid {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .inventory-grid .stock_block {
    margin: 0 !important;
    width: 100% !important;
  }

  .inventory-grid .card {
    padding: 12px 10px;
    border-radius: 14px;
  }

  .inventory-grid .card-title {
    font-size: 0.8rem;
  }

  .inventory-grid .card-value {
    font-size: 0.95rem;
    font-weight: 700;
  }
}


  </style>



  @php
    $data = new_component_array('newbreadcrumb', 'Dashboard');  
  @endphp
  <x-new-bread-crumb :data=$data />

  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

 <!-- Premium Welcome Overlay -->
  <div id="welcome-overlay">
    <div class="lux-circle">
       <!-- BACKGROUND LOGO -->
  
      <div class="lux-ring slow"></div>
      <div class="lux-ring fast"></div>

      <div class="lux-core">
        <img src="{{  asset('assets/logo.png') }}" class="lux-logo-bg" alt="Just Udhari Logo">
        <span class="lux-tag royal-namaskar">
          <span class="royal-aura"></span>

          <span class="namaskar-icon">
            <i class="fa-solid fa-hands-praying"></i>
          </span>

          <span class="namaskar-text">WELCOME TO</span>
        </span>

        <h1>Just Udhari</h1>
        <p>Smart Jewellery Management</p>
      </div>
    </div>
  </div>

  <div class="container-fluid dashboard_default my-4">
    <!-- Top Rates -->
    <div class="rate-section-card mb-3 d-flex align-items-start justify-content-between flex-wrap">

      {{-- LEFT SIDE TEXT --}}
      <div class="mb-2 mb-md-0">
        <div class="rate-heading animated-heading">
          Just Udhari ✨ Bullion Desk
        </div>

        <div class="rate-subtitle">
          <span id="rate-welcome-text" data-lines='[
                   "Live gold & silver rate, ek hi screen par.",
                  "Dekho rate, note karo, customer ko turant quote do.",
                  "Daily bullion snapshot · Just Udhari ke saath always ready."
                ]'>
          </span>
          <span class="typing-cursor">|</span>
        </div>

        @php
          $goldCurrent = $rates['gold_24k'] ?? 0;
          $high = $goldHigh24h ?? $goldCurrent;
          $low = $goldLow24h ?? $goldCurrent;
          $mid = $high && $low ? ($high + $low) / 2 : $goldCurrent;

          $trendLabel = 'Stable';
          $trendClass = 'trend-stable';
          $trendIcon = 'fa-minus';

          if ($goldCurrent > $mid * 1.02) {
            $trendLabel = 'Rising';
            $trendClass = 'trend-up';
            $trendIcon = 'fa-arrow-trend-up';
          } elseif ($goldCurrent < $mid * 0.98) {
            $trendLabel = 'Falling';
            $trendClass = 'trend-down';
            $trendIcon = 'fa-arrow-trend-down';
          }
        @endphp

        <div class="rate-chip-row mt-1">
          {{-- Last updated chip --}}
          <span class="mini-chip" id="rates-last-updated">
            <i class="fa fa-clock"></i>
            Last updated:
            <strong>{{ $ratesLastUpdated ?? '—' }}</strong>
          </span>

          {{-- Market trend chip --}}
          <span class="mini-chip {{ $trendClass }}" id="market-trend-chip">
            <i class="fa {{ $trendIcon }}"></i>
            Market:
            <strong>{{ $trendLabel }}</strong>
          </span>
        </div>
      </div>

      {{-- RIGHT SIDE RATES --}}
      <div class="rates-wrap d-flex align-items-center flex-wrap">

        {{-- GOLD CARD --}}
        <div class="metal-card me-2">
          <div class="metal-title">
            <i class="fa fa-coins"></i> Gold Rates
          </div>

          @php
            $goldChange = $change['gold_24k'] ?? 0;
            $goldChangeClass = $goldChange >= 0 ? 'up' : 'down';
          @endphp

          {{-- Main highlight 24K --}}
          <div class="metal-main-rate">
            ₹{{ number_format($rates['gold_24k']) }}
            <small>/gm · 24K</small>
            <span class="rate-change {{ $goldChangeClass }}">
              {{ $goldChange >= 0 ? '+' : '' }}{{ number_format($goldChange, 2) }}%
            </span>
          </div>

          {{-- Other karats as small chips --}}
          <div class="rate-badges">
            <div class="rate-chip">
              <span>22K</span>
              <span class="value">₹{{ number_format($rates['gold_22k']) }}</span>
            </div>
            <div class="rate-chip">
              <span>20K</span>
              <span class="value">₹{{ number_format($rates['gold_20k']) }}</span>
            </div>
            <div class="rate-chip">
              <span>18K</span>
              <span class="value">₹{{ number_format($rates['gold_18k']) }}</span>
            </div>
          </div>
        </div>

        {{-- SILVER CARD --}}
        <div class="silver-card me-2">
          <div class="metal-title">
            <i class="fa fa-gem"></i> Silver
          </div>

          @php
            $silverChange = $change['silver'] ?? 0;
            $silverChangeClass = $silverChange >= 0 ? 'up' : 'down';
          @endphp

          <div class="metal-main-rate">
            ₹{{ number_format($rates['silver'] / 1000, 3) }}
            <small>/gm</small>
            <span class="rate-change {{ $silverChangeClass }}">
              {{ $silverChange >= 0 ? '+' : '' }}{{ number_format($silverChange, 2) }}%
            </span>
          </div>

          <div class="rate-badges">
            <div class="rate-chip">
              <span>Kg</span>
              <span class="value">
                ₹{{ number_format($rates['silver'] , 3) }}
              </span>
            </div>
          </div>
        </div>

        {{-- UPDATE BUTTON --}}
      <button class="btn btn-sm btn-update" id="btn-refresh-rates">
  <i class="fa fa-sync-alt"></i> Update
</button>

      </div>
    </div>

    <!-- Top Tiles -->
    <div class="row gy-3">
      <div class="col-md-6">
        
        <x-card-stat title="Total Customers" value="{{ number_format($totalCustomers) }}" change="↗ +12% vs last month"
          icon="fa-users">
          @php
            $actions = '<button class="btn btn-sm btn-outline-primary me-2" data-toggle="modal" data-target="#custo_modal">+ New</button><a href="'.route('customers.index').'" class="btn btn-sm btn-dark">All</a><div class="icon-box m-auto"><i class="fa fa-users"></i></div>';
          @endphp
          <x-slot name="actions">{!! $actions !!}</x-slot>
        </x-card-stat>
      </a>
      </div>

      <div class="col-md-6">
        <x-card-stat title="Active Girvi" value="{{ number_format($activeLoans) }}" change="↗ +8% vs last month"
          icon="fa-users">
          @php
            $actions = '<a href="#"  class="btn btn-sm btn-outline-warning me-2">+ New</a><button class="btn btn-sm btn-success">Pay</button><div class="icon-box m-auto"><i class="fa fa-money-bill-wave"></i></div>';
          @endphp
          <x-slot name="actions">{!! $actions !!}</x-slot>
        </x-card-stat>
      </div>
    </div>

    <!-- Inventory Stock -->
    <div class="card dash-card mt-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0"><i class="fa fa-boxes me-2"></i>Inventory Stock</h6>
            <small class="muted">Current available stock</small>
          </div>
          <div>
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal"
              data-bs-target="#importStockModal">
              <i class="fa fa-file-import me-1"></i> Import
            </button>

            <!-- <button class="btn btn-sm btn-primary">+ Add New</button> -->
            <a class="btn btn-sm btn-primary" href="{{ route('stock.new.create') }}">+ Add New</a>
          </div>
        </div>

       <div class="row gy-3 inventory-grid">

          <div class="col-md-2 m-auto stock_block">
              <a href="{{ route('stock.new.dashboard',['gold']) }}" class="text-decoration-none">
                  <x-card-stock-item 
                      title="Gold Stock" 
                      value="{{ number_format($goldStock, 2) }} gm" 
                      icon="fa-cube"
                      iconColor="#28c76f" 
                  />
              </a>
          </div>

        <div class="col-md-2 m-auto stock_block">
       <a href="{{ route('stock.new.dashboard',['silver']) }}" class="text-decoration-none">
                <x-card-stock-item 
                    title="Silver Stock" 
                    value="{{ number_format($silverStock / 1000, 2) }} kg" 
                    icon="fa-gem"
                    iconColor="#7367f0" 
                />
            </a>
        </div>

        <div class="col-md-2 m-auto stock_block">
        <a href="{{ route('stock.new.dashboard',['artificial']) }}" class="text-decoration-none">
                <x-card-stock-item 
                    title="Artificial Jewelry" 
                    value="{{ number_format($artificialJewelry) }} item"
                    icon="fa-ring"
                    low="true"
                    iconColor="#ff3b30"
                />
            </a>
        </div>

          <div class="col-md-2 m-auto stock_block">
              <a href="{{ route('stock.new.dashboard',['stone']) }}" class="text-decoration-none">         
                    <x-card-stock-item 
                        title="Stones" 
                        value="{{ number_format($stones) }} item"
                        icon="fa-gem"
                        iconColor="#ff7a1a"
                    />
                </a>
            </div>

            <div class="col-md-2 m-auto stock_block">
               <a href="{{ route('stock.new.dashboard',['franchise']) }}" class="text-decoration-none">
                    <x-card-stock-item 
                        title="Franchise" 
                        value="{{ number_format($frenchise) }} item"
                        icon="fa-crown"
                        iconColor="#ff7a1a"
                    />
                </a>
            </div>


     
        </div>

        <div class="mt-3 border-top-custom pt-2 text-muted">
          Total Stock Value
        </div>
      </div>
    </div>

    <!-- Import Stock Modal -->
<div class="modal fade" id="importStockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title">
          <i class="fa fa-file-import me-2"></i> Import Stock
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&cross;</button>
      </div>

      <div class="modal-body p-0">
        <iframe
  src="{{ route('stock.new.inventory.import', ['popup' => 1]) }}"
  style="border:0; width:100%; height:75vh;">
</iframe>

      </div>
    </div>
  </div>
</div>


    <!-- Billing & Sales -->
    <div class="card dash-card mt-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0"><i class="fa fa-receipt me-2"></i>Billing & Sales</h6>
            <small class="muted">Today's sales summary</small>
          </div>
          <!-- <button class="btn btn-sm btn-outline-danger">Create Bill</button> -->
          <a class="btn btn-sm btn-outline-danger" href="{{ route('billing','sale') }}">Create Bill</a>
        </div>

         <div class="row gy-3 inventory-grid">
          <div class="col-md-4">
            <x-card-billing-item title="Total Sell (Today)" icon="fa-bag-shopping" iconColor="#28c76f"
              value="₹{{ number_format($totalSellToday) }}" sub="{{ $transactionCountToday }} transaction" />
          </div>

          <div class="col-md-4">
            <x-card-billing-item title="Payment Received" icon="fa-credit-card" iconColor="#ff7a1a"
              value="₹{{ number_format($paymentReceivedToday) }}" sub="Today's collection" />
          </div>
          @php #
          $pndngbll = number_format($pendingBills);
          $blk_ttl = ($pndngbll < 0)?'Pending Bills':'Bills Advance';
          $txt_color =  ($pndngbll < 0)?'red':'green';
          @endphp
          <div class="col-md-4">
            <x-card-billing-item title="{{ $blk_ttl }}" color="{{ $txt_color }}" icon="fa-file-invoice-dollar" iconColor="#ff3b30"
              value="₹{{ number_format($pendingBills) }}" sub="Bill Pending" />
          </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <div class="muted">This Month</div>
          <div class="fw-bold">₹{{ number_format($collectedThisMonth) }}</div>
          <div class="fw-bold">This Year ₹{{ number_format(array_sum($chartData)) }}</div>
        </div>
      </div>
    </div>

    <!-- Udhari Overview -->
    <div class="card dash-card mt-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0"><i class="fa fa-wallet me-2"></i>Udhari Overview</h6>
            <small class="muted">Outstanding credit & loans</small>
          </div>
          <!-- <button class="btn btn-sm btn-success">New Txn</button> -->
           <a class="btn btn-sm btn-success" href="{{ route('udhar.create') }}">New Txn</a>
        </div>

       <div class="row gy-3 inventory-grid">
          <!-- <div class="col-md-3">
            <x-card-udhari-item label="Total Udhari" icon="fa-solid fa-wallet" value="₹{{ number_format($totalUdhari) }}"
              sub="{{-- @Bill::where('shop_id', 31)->where('branch_id', 33)->where('balance', '>', 0)->count() --}} customers" />
          </div> -->
          <div class="col-md-4 m-auto">
            <x-card-udhari-item label="Gold Udhari" icon="fa-coins" value="{{ number_format($goldUdhariItems, 2) }} gm"
              sub="Gold items pending" />
          </div>
          <div class="col-md-4 m-auto">
            <x-card-udhari-item label="Silver Udhari" icon="fa-coins"
              value="{{ number_format($silverUdhariItems, 2) }} gm" sub="Silver items pending" />
          </div>
          <div class="col-md-4 m-auto">
            <x-card-udhari-item label="Cash Udhari" icon="fa-money-bill-wave"
              value="₹{{ number_format($totalcashUdhari) }}" sub="Cash pending" />
          </div>
        </div>

        <div class="d-flex justify-content-between mt-3 border-top-custom pt-2">
          <div class="text-danger fw-bold">Overdue Amount ₹{{ number_format($overdueAmount) }}</div>
          <div class="text-success fw-bold">Collected This Month ₹{{ number_format($collectedThisMonth) }}</div>
        </div>
      </div>
    </div>

    <!-- Schemes Overview -->
    <div class="card dash-card mt-4 schemes-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0"><i class="fa fa-award me-2"></i>Schemes Overview</h6>
            <small class="muted">Active schemes and enrollments</small>
          </div>
          <!-- <button class="btn btn-sm btn-purple">Collect Payment</button> -->
          <a class="btn btn-sm btn-purple" href="{{ route('shopscheme.pay') }}">Collect Payment</a>
         
        </div>

       <div class="row gy-3 inventory-grid">
          <div class="col-md-3">
            <x-card-scheme-stat title="Active Schemes" value="{{ number_format($activeSchemes) }}" icon="fa-award"
              iconColor="#28c76f" />
          </div>

          <div class="col-md-3">
            <x-card-scheme-stat title="Total Customers" value="{{ number_format($schemeCustomers) }}" icon="fa-users"
              iconColor="#7367f0" />
          </div>

          <div class="col-md-3">
            <x-card-scheme-stat title="Due This Month" value="₹{{ number_format($dueThisMonth) }}" icon="fa-calendar-day"
              iconColor="#ff7a1a" />
          </div>

          <div class="col-md-3">
            <x-card-scheme-stat title="Collected Today" value="₹{{ number_format($collectedTodayScheme) }}"
              icon="fa-coins" iconColor="#ff3b30" />
          </div>
        </div>

        <!-- Top Schemes List -->
        <div class="col-12">
          <div class="top-schemes mt-3 row" style="font-style: italic; font-weight: 400;">
            @php
              $topshopSchemes = App\Models\ShopScheme::where('shop_id', auth()->user()->shop_id)
                /*->where('branch_id', 33)*/
                ->where('ss_status', '1')
                ->withCount('members as enrolls_count')
                ->withSum('members', 'emi_amnt')
                ->orderBy('enrolls_count', 'desc')
                // ->limit(3)
                ->get();

            @endphp
             <h5 class="col-12"> Scheme</h5>
            @foreach($topshopSchemes as $sscheme)
              <x-card-scheme-row title="{{ $sscheme->scheme_head }}" badge="{{ $sscheme->enrolls_count }} customers"
                meta="Duration: {{ $sscheme->scheme_validity }} Months · ₹{{ number_format($sscheme->members_sum_emi_amnt) }}/month" />
            @endforeach
            @php
              $topSchemes = App\Models\AnjumanScheme::where('shop_id', auth()->user()->shop_id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('status', '1')
                ->withCount(['enrolls'])
                ->orderBy('enrolls_count', 'desc')
                // ->limit(3)
                ->get();
            @endphp
            <h5 class="col-12">Anjumam Scheme</h5>
            @foreach($topSchemes as $scheme)
              <x-card-scheme-row title="{{ $scheme->title }}" badge="{{ $scheme->enrolls_count }} customers"
                meta="Duration: {{ $scheme->validity }} Months · ₹{{ number_format($scheme->emi_quant) }}/month" />
            @endforeach
            
          </div>

          <div class="d-flex justify-content-between mt-3 border-top-custom pt-2 pb-2">
            <div class="fw-bold text-success">Total Collected (This Month) ₹{{ number_format($collectedTodayScheme) }}
            </div>
            <div class="fw-bold text-danger">Pending Payments ₹{{ number_format($dueThisMonth) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Earnings Chart -->
    <div class="card dash-card mt-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0"><i class="fa fa-chart-line me-2"></i>Monthly Earnings</h6>
          </div>
          <select id="chartRange" class="form-select form-select-sm" style="width:120px;">
            <option>Month</option>
            <option>Quarter</option>
          </select>
        </div>

        <div>
          <canvas id="earningsChart" height="120"></canvas>
        </div>
      </div>
    </div>

    @include('vendors.commonpages.newcustomerwithcategory')
@endsection

  @section('styles')
    {{-- if layout already loads FontAwesome/Bootstrap you can skip these --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @endsection

  @section('javascript')
    {{-- JS libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('earningsChart');
        if (!canvas) {
          console.warn('earningsChart canvas not found');
          return;
        }

        const ctx = canvas.getContext('2d');

        // PHP data -> JS
        const monthlyData = @json($chartData);

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const currentMonth = new Date().getMonth();

        // Last 6 months labels + data
        const last6Months = [];
        const last6MonthsData = [];

        for (let i = 5; i >= 0; i--) {
          const monthIndex = (currentMonth - i + 12) % 12;
          last6Months.push(months[monthIndex]);
          last6MonthsData.push(monthlyData[monthIndex] || 0);
        }

        const data = {
          labels: last6Months,
          datasets: [{
            label: 'Earnings',
            data: last6MonthsData,
            borderColor: '#FF7A1A',
            backgroundColor: 'rgba(255,122,26,0.06)',
            tension: 0.35,
            pointBackgroundColor: '#FF7A1A',
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 3,
            fill: true
          }]
        };

        new Chart(ctx, {
          type: 'line',
          data,
          options: {
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false }
            },
            scales: {
              x: {
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { color: '#6b6b6b' }
              },
              y: {
                grid: { color: 'rgba(0,0,0,0.03)', borderDash: [4, 6] },
                ticks: {
                  callback: function (v) {
                    return '₹' + (v / 1000) + 'K';
                  },
                  color: '#6b6b6b'
                }
              }
            }
          }
        });

        // Agar jQuery loaded hai to ye chalega, warna ignore ho jayega
        if (window.jQuery) {
          $(document).ready(function () {
            // alert('Chart ready'); // Debug ke liye chahiye to uncomment kar lena
          });
        }
      });

      // Existing customerformsubmit listener same hi rakha hai
      $(document).on('customerformsubmit', function (e) {
        let data = e.originalEvent.detail;
        $("#custo_plus_form").find("button[type='submit']").prop('disabled', false);
        $("#process_wait").hide();
        if (data.errors) {
          errors = data.errors;
          $.each(errors, function (i, v) {
            let err = '';
            $.each(v, function (ei, ev) {
              if (err != '') {
                err += '\n';
              }
              err += ev;
            });
            $("#" + i).addClass('is-invalid');
            $("#" + i + "_error").html(err);
            toastr.error(err)
          });
        } else if (data.error) {
          toastr.error(data.error);
        } else {
          success_sweettoatr("Customer succesfully Added !");
          $("#custo_modal").modal('hide');
          resetcustoform(true);
        }
      });
    </script>

    <script>
      // ===== RATES AJAX REFRESH =====
      $(document).on('click', '#btn-refresh-rates', function () {
        let $btn = $(this);
        let originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa fa-sync-alt fa-spin"></i> Updating');

        $.ajax({
          url: "{{ route('vendor.rates.current') }}",
          method: "GET",
          success: function (res) {
            if (!res.success) return;

            // Update main rates
            $('#rate-gold-24k').text(res.gold_24k_formatted);
            $('#rate-gold-22k').text(res.gold_22k_formatted);
            $('#rate-gold-20k').text(res.gold_20k_formatted);
            $('#rate-gold-18k').text(res.gold_18k_formatted);

            $('#rate-silver').text(res.silver_formatted_1);
            $('#rate-silver-kg').text(res.silver_kg_formatted);

            // Update 24h high/low
            $('#gold-high-24h').text(res.gold_high_24h);
            $('#gold-low-24h').text(res.gold_low_24h);
            $('#silver-high-24h').text(res.silver_high_24h);
            $('#silver-low-24h').text(res.silver_low_24h);

            // Last updated
            if (res.last_updated_human) {
              $('#rates-last-updated strong').text(res.last_updated_human);
            }

          },
          error: function () {
            console.error('Rate refresh failed');
          },
          complete: function () {
            $btn.prop('disabled', false).html(originalHtml);
          }
        });
      });
    </script>

    <script>
      // ===== TYPEWRITER WELCOME TEXT =====
      document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('rate-welcome-text');
        if (!el) return;

        let lines;
        try {
          lines = JSON.parse(el.getAttribute('data-lines'));
        } catch (e) {
          lines = ["Live gold & silver feed — updated in real-time."];
        }

        let lineIndex = 0;
        let charIndex = 0;
        let deleting = false;
        let current = '';
        const typingSpeed = 45;   // millisec per char
        const pauseEnd = 1600;  // pause after finishing line
        const pauseStart = 400;   // pause before starting next line

        function typeLoop() {
          const fullText = lines[lineIndex] || '';

          if (!deleting) {
            // typing forward
            current = fullText.substring(0, charIndex + 1);
            charIndex++;

            if (charIndex === fullText.length) {
              // line finished
              setTimeout(() => {
                deleting = true;
                typeLoop();
              }, pauseEnd);
              el.textContent = current;
              return;
            }
          } else {
            // deleting backward
            current = fullText.substring(0, charIndex - 1);
            charIndex--;

            if (charIndex === 0) {
              deleting = false;
              lineIndex = (lineIndex + 1) % lines.length;
              setTimeout(typeLoop, pauseStart);
              el.textContent = current;
              return;
            }
          }

          el.textContent = current;
          setTimeout(typeLoop, typingSpeed);
        }

        typeLoop();
      });
    </script>

     <script>
  document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('welcome-overlay');

    setTimeout(() => {
      overlay.classList.add('hide');

      setTimeout(() => {
        overlay.remove();
      }, 1000);

    }, 3000); // 5 seconds
  });
</script>
  @endsection