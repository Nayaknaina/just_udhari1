<?php

namespace App\PDF;

require_once(base_path('vendor/autoload.php'));

class BillPDF extends \FPDF
{
    private $bill;
    private $shop;
    private $bank;
    private $logo_path;
    private $signature_path;
    private $watermark_path; // <-- NEW

    public function __construct($bill = [], $shop = [], $bank = [], $logo_path = null, $signature_path = null, $watermark_path = null) // <-- updated constructor
    {
        parent::__construct('P', 'mm', 'A4');
        $this->bill = $bill;
        $this->shop = $shop;
        $this->bank = $bank;
        $this->logo_path = $logo_path;
        $this->signature_path = $signature_path;
        $this->watermark_path = $watermark_path; // <-- store watermark
    }

    // ✨ ADD DASH HELPER HERE
    function SetDash($black = null, $white = null)
    {
        if ($black !== null)
            $this->_out(sprintf('%.3F %.3F d', $black, $white));
        else
            $this->_out('[] 0 d');
    }

    // Header
    function Header()
    {
        // Watermark Back Image (behind everything)
        if ($this->watermark_path && file_exists($this->watermark_path)) {  
            $this->SetAlpha(0.15); // light transparent effect
            $this->Image($this->watermark_path, 25, 60, 160);
            $this->SetAlpha(1); // reset transparency
        }

        if ($this->logo_path && file_exists($this->logo_path)) {
            $this->Image($this->logo_path, 10, 10, 40);
            $this->SetX(45);
        } else {
            $this->SetX(10);
        }

        $this->SetTextColor(184, 134, 11);

        $this->SetX(0);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 8, $this->shop['name'] ?? '------', 0, 1, 'C');

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, $this->shop['address'] ?? '------', 0, 1, 'C');
        $this->Cell(0, 5, 'Phone: ' . ($this->shop['mobile_no'] ?? '-----') . ' | GSTIN: ' . ($this->shop['gst_num'] ?? '-----'), 0, 1, 'C');

        $this->Ln(5);

        $this->SetY(10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'GST / INVOICE', 0, 1, 'R');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Invoice No: ' . $this->bill['bill_number'], 0, 1, 'R');
        $this->Cell(0, 5, 'Date: ' . date('d/m/Y', strtotime($this->bill['bill_date'])), 0, 1, 'R');

        if ($this->bill['due_date'] && ($this->bill['bill_date'] != $this->bill['due_date'])) {
            $this->Cell(0, 5, 'Due: ' . date('d/m/Y', strtotime($this->bill['due_date'])), 0, 1, 'R');
        }

        $this->Ln(10);
    }

 // Parties Section
function PartiesSection()
{
    $party_title = ['c' => "Customer", 's' => 'Supplier'];
    $party_type = $this->bill['party_type'] ?? 'c';

    $gap = 5;

    // Title Row
    $this->SetFont('Arial', 'B', 10);
    $this->Cell(95, 7, 'Bill To (' . ($party_title[$party_type] ?? 'Customer') . ')', 1, 0, 'L');
    $this->Cell($gap, 7, '', 0, 0);
    $this->Cell(95, 7, 'Bank Details', 1, 1, 'L');

    $this->SetFont('Arial', '', 9);

    // Background Color
    $this->SetFillColor(255, 250, 240);

    // OUTER BOXES (top part)
    $this->Cell(95, 25, '', 'TLR', 0, 'L', true);
    $this->Cell($gap, 25, '', 0, 0);
    $this->Cell(95, 25, '', 'TLR', 1, 'L', true);

    // Reset color
    $this->SetFillColor(255, 255, 255);

    //-------------------------
    // LEFT BOX CONTENT
    //-------------------------
    $startY = $this->GetY() - 25 + 2;
    $this->SetXY(11, $startY);

    $leftText  = "Name: " . $this->bill['party_name'] . "\n";
    $party_address = $this->bill['partydetail']['custo_address'] ?? $this->bill['partydetail']['address'] ?? '-----';
    $leftText .= "Address: " . $party_address . "\n";
    $leftText .= "Mobile: " . $this->bill['party_mobile'];

    $this->MultiCell(93, 5, $leftText, 0, 'L');

    //-------------------------
    // RIGHT BOX CONTENT
    //-------------------------
    $rightX = 10 + 95 + $gap + 2;
    $rightY = $startY;
    $this->SetXY($rightX, $rightY);

    $rightText  = "Bank: " . ($this->bank['name'] ?? 'N/A') . "\n";
    $rightText .= "Branch: " . ($this->bank['branch'] ?? 'N/A') . "\n";
    $rightText .= "A/c No: " . ($this->bank['account'] ?? 'N/A') . "\n";
    $rightText .= "IFSC: " . ($this->bank['ifsc'] ?? 'N/A');

    $this->MultiCell(93, 5, $rightText, 0, 'L');

    //-------------------------
    // DRAW BOTTOM BORDER
    //-------------------------
    $this->SetY($startY + 23);
    $this->Cell(95, 0, '', 'T', 0);
    $this->Cell($gap, 0, '', 0, 0);
    $this->Cell(95, 0, '', 'T', 1);

    $this->Ln(8);
}



    // Items Table
   // Items Table - Expanded version with all columns
function ItemsTable()
{
    // Get selected columns from shop settings or use default
    $billColumns = [];
    if (isset($this->shop['bill_columns']) && !empty($this->shop['bill_columns'])) {
        $billColumns = json_decode($this->shop['bill_columns'], true);
    }
    
    // If no valid columns, use default set
    if (empty($billColumns)) {
        $billColumns = ['item', 'caret', 'gross', 'less', 'net', 'fine', 'rate', 'total'];
    }

    // Define ALL column configurations with adjusted widths

    

    $columnConfigs = [
        'item' => ['width' => 40, 'label' => 'Description', 'align' => 'L'],
        'caret' => ['width' => 15, 'label' => 'Purity', 'align' => 'C'],
        'piece' => ['width' => 15, 'label' => 'Pcs', 'align' => 'R'],
        'gross' => ['width' => 18, 'label' => 'Gross Wt', 'align' => 'R'],
        'less' => ['width' => 18, 'label' => 'Less Wt', 'align' => 'R'],
        'net' => ['width' => 18, 'label' => 'Net Wt', 'align' => 'R'],
        'tunch' => ['width' => 15, 'label' => 'Tunch', 'align' => 'R'],
        'wastage' => ['width' => 15, 'label' => 'Wastage', 'align' => 'R'],
        'fine' => ['width' => 18, 'label' => 'Fine Wt', 'align' => 'R'],
        'st_ch' => ['width' => 18, 'label' => 'Stone Ch.', 'align' => 'R'],
        'rate' => ['width' => 18, 'label' => 'Rate', 'align' => 'R'],
        'labour' => ['width' => 18, 'label' => 'Labour', 'align' => 'R'],
        'other' => ['width' => 18, 'label' => 'Other', 'align' => 'R'],
        'disc' => ['width' => 15, 'label' => 'Disc.', 'align' => 'R'],
        'total' => ['width' => 20, 'label' => 'Amount', 'align' => 'R']
    ];
    
    $this->SetFillColor(253, 247, 227);
    $this->SetFont('Arial', 'B', 9); // Slightly smaller font for more columns

    // Build header row
    $widths = [12]; // Smaller serial number width
    $aligns = ['C'];
    $headerLabels = ['Sno.'];

    // Add selected columns to header
    foreach($billColumns as $column) {
        if(isset($columnConfigs[$column])) {
            $widths[] = $columnConfigs[$column]['width'];
            $aligns[] = $columnConfigs[$column]['align'];
            $headerLabels[] = $columnConfigs[$column]['label'];
        }
    }

    // Draw header
    for($i = 0; $i < count($headerLabels); $i++) {
        $this->Cell($widths[$i], 8, $headerLabels[$i], 1, 0, 'C', true);
    }
    $this->Ln();

    $this->SetFont('Arial', '', 8); // Smaller font for data
    
    // Data rows
    if (isset($this->bill['billitems']) && is_array($this->bill['billitems'])) {
        foreach ($this->bill['billitems'] as $ik => $item) {
            $rowData = [$ik + 1]; // Serial number
            
            foreach($billColumns as $column) {
                switch($column) {
                    case 'item':
                        $rowData[] = $item['item_name'] ?? '';
                        break;
                    case 'caret':
                        $rowData[] = ($item['caret'])?$item['caret']."K":'-';
                        break;
                    case 'piece':
                        $rowData[] = $item['piece'] ?? '-';
                        break;
                    case 'gross':
                        $rowData[] = ($item['gross'])?number_format($item['gross'], 2):'-';
                        break;
                    case 'less':
                        $rowData[] = ($item['less'])?number_format($item['less'], 2):'-';
                        break;
                    case 'net':
                        $rowData[] =  ($item['net'])?number_format($item['net'], 2):'-';
                        break;
                    case 'tunch':
                        $rowData[] = $item['tunch'] ?? '';
                        break;
                    case 'wastage':
                        $rowData[] = $item['wastage'] ?? '';
                        break;
                    case 'fine':
                        $rowData[] =  ($item['fine'])?number_format($item['fine'], 2):'-';
                        break;
                   case 'st_ch':
                        $rowData[] = number_format($item['element'] ?? 0, 2);
                        break;
                    case 'rate':
                        $rowData[] = number_format($item['rate'] ?? 0, 2);
                        break;
                    case 'labour':
                        $rowData[] = number_format($item['labour'] ?? 0, 2);
                        break;
                    case 'other':
                        $rowData[] = number_format($item['other'] ?? 0, 2);
                        break;
                    case 'disc':
                        $rowData[] = number_format($item['discount'] ?? 0, 2);
                        break;
                    case 'total':
                        $rowData[] = number_format($item['total'] ?? 0, 2);
                        break;
                    default:
                        $rowData[] = '';
                }
            }
            
            $this->FancyRow($rowData, $widths, $aligns, 5); // Smaller line height
        }
    }

    $this->Ln(5);
}

    // Totals Section
    function TotalsSection()
    {
        $subtotal = $this->bill['sub'] ?? 0;
        $gst_perc = $this->bill['gst'] ?? 0;
        $gst_amount = ($subtotal * $gst_perc/2) / 100;
        $grand_total = ($gst_amount * 2) + $subtotal;
        $balance = $this->bill['balance'] ?? 0;
        $bal_ttl = ($balance>0)?'Advance':'Remains';

        $this->SetFont('Arial', '', 10);

        $this->SetX(140);
        $this->Cell(35, 7, 'Sub-Total', 1, 0, 'L');
        $this->Cell(25, 7, number_format($subtotal, 2), 1, 1, 'R');
        

        $this->SetX(140);
        $this->Cell(35, 7, 'CGST 1.5%', 1, 0, 'L');
        $this->Cell(25, 7, number_format($gst_amount, 2), 1, 1, 'R');

        $this->SetX(140);
        $this->Cell(35, 7, 'SGST 1.5%', 1, 0, 'L');
        $this->Cell(25, 7, number_format($gst_amount, 2), 1, 1, 'R');

        $this->SetX(140);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(184, 134, 11);
        $this->Cell(35, 7, 'Grand Total', 1, 0, 'L');
        $this->Cell(25, 7, number_format($grand_total, 2), 1, 1, 'R');
        $this->SetTextColor(0, 0, 0);

        if(!empty($this->bill['payment'])){
                $this->SetX(140);
             
                 $this->SetFont('Arial', 'B', 10);
                 $this->SetTextColor(184, 134, 11);
                 $this->Cell(35, 7, 'Payment:', 1, 0, 'L');
                 $this->Cell(25, 7, number_format($this->bill['payment'], 2), 1, 1, 'R');
                 $this->SetTextColor(0, 0, 0);
        }
        if($balance != 0){
            $rd_col = ($balance<0)?'255':'0';
            $gr_col = ($balance<0)?'0':'100';
            $bl_col = ($balance<0)?'0':'0';
            $this->SetX(140);
            $this->SetTextColor($rd_col, $gr_col, $bl_col);
            $this->Cell(35, 7, $bal_ttl, 1, 0, 'L');
            $this->Cell(25, 7, number_format($balance, 2), 1, 1, 'R');
            
            $this->SetTextColor(0, 0, 0);            
        }
        


        

        $this->Ln(3);
    }

    // Terms & Signature Section
    function TermsAndSignatures()
    {
        $this->SetFont('Arial', 'B', 9);
        
        $this->Cell(0, 6, 'Terms & Conditions:', 0, 1);

        $this->SetFont('Arial', '', 8);
       $invoice_terms = $this->shop['invoice_terms'] ?? '';

if ($invoice_terms) {
    $terms = explode("\n", $invoice_terms);
} else {
    // fallback default only if no terms saved
    $terms = [
        '1. Goods once sold will not be taken back or exchanged.',
        '2. All disputes are subject to jurisdiction only.',
        '3. Interest @ 18% p.a. will be charged if payment is delayed.',
        '4. Certified that the particulars given above are true and correct.',
        '5. This is a computer generated invoice.',
    ];
}

foreach ($terms as $term) {
    $this->Cell(0, 4, trim($term), 0, 1);
}


        $this->Ln(5);

        $this->SetX(120);
        $this->Cell(80, 20, 'Customer Signature', 1, 1, 'C');

        $this->SetX(120);

        if ($this->signature_path && file_exists($this->signature_path)) {
            $this->Image($this->signature_path, 150, 228, 15);
            $this->SetY($this->GetY() + 20);
        } else {
            $this->Cell(80, 20, 'For MG Jewelers', 1, 1, 'C');
        }

        $this->SetX(120);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(80, 4, 'Authorised Signatory', 0, 1, 'C');
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Thank you for your purchase!', 0, 0, 'C');
    }

    // Wrap Functions
    function FancyRow($data, $widths, $aligns, $lineHeight = 6)
    {
        $maxLines = 0;
        for ($i = 0; $i < count($data); $i++) {
            $maxLines = max($maxLines, $this->NbLines($widths[$i], $data[$i]));
        }

        $rowHeight = $maxLines * $lineHeight;

        if ($this->GetY() + $rowHeight > $this->PageBreakTrigger)
            $this->AddPage();

        for ($i = 0; $i < count($data); $i++) {

            $x = $this->GetX();
            $y = $this->GetY();
            $a = $aligns[$i] ?? 'L';

            $this->Rect($x, $y, $widths[$i], $rowHeight);
            $this->MultiCell($widths[$i], $lineHeight, $data[$i], 0, $a);

            $this->SetXY($x + $widths[$i], $y);
        }
        $this->Ln($rowHeight);
    }

    function NbLines($w, $txt)
    {
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);

        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];

            if ($c == "\n") {
                $i++; $sep = -1; $j = $i; $l = 0; $nl++;
                continue;
            }

            if ($c == ' ')
                $sep = $i;

            $l += $cw[$c];

            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;

                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    // Add transparency function for watermark
    function SetAlpha($alpha)
    {
        $this->alpha = $alpha * 50;
        $this->_out(sprintf('%.2F ca %.2F CA', $alpha, $alpha));
    }
}
