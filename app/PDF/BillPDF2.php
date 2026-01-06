<?php

namespace App\PDF;

require_once(base_path('vendor/autoload.php'));

class BillPDF2 extends \FPDF
{
    private $bill;
    private $shop;
    private $bank;
    private $logo_path;
    private $signature_path;
    private $watermark_path;

    public function __construct($bill = [], $shop = [], $bank = [], $logo_path = null, $signature_path = null, $watermark_path = null)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->bill = $bill;
        $this->shop = $shop;
        $this->bank = $bank;
        $this->logo_path = $logo_path;
        $this->signature_path = $signature_path;
        $this->watermark_path = $watermark_path;
        //dd($this->bill['payments']);
    }

    // HEADER - IMPROVED POSITIONING
    function Header()
    {
        // Watermark behind everything
        if ($this->watermark_path && file_exists($this->watermark_path)) {
            $this->SetAlpha(0.10);
            $this->Image($this->watermark_path, 25, 60, 160); // Better positioned
            $this->SetAlpha(1);
        }

        // Logo - Left side
        if ($this->logo_path && file_exists($this->logo_path)) {
            $this->Image($this->logo_path, 10, 15, 40); // Adjusted size and position
        }

        // Shop Name and Details - Center aligned
        $this->SetTextColor(0, 0, 128);
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(10, 10);
        $this->MultiCell(195, 8, $this->shop['name']. ' ', ''  ,'C');

        // Shop Details
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->SetX(10);
        $this->Cell(195 , 5, $this->shop['address'] ?? 'Main Road, City - 123456', 0, 1, 'C');
        
        $this->SetX(10);
        $this->Cell(195 , 5, 'Phone: ' . ($this->shop['mobile_no'] ?? '9109561833'), 0, 1, 'C');
        
        $this->SetX(10);
        $this->Cell(195 , 5, 'Email: ' . ($this->shop['email'] ?? 'info@laxmijewellers.com'), 0, 1, 'C');
        
        $this->SetX(10);
        $this->Cell(195 , 5, 'GSTIN: ' . ($this->shop['gst_num'] ?? 'GSTINXXXXXXXXXXXX'), 0, 1, 'C');

        $this->Ln(5);

        // Title Box - TAX INVOICE
        $this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'TAX INVOICE', 0, 1, 'C', true);

        // Invoice Info - Better positioning
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        
        $this->Cell(95, 6, 'Invoice No: ' . ($this->bill['bill_number'] ?? '324324'), 0, 0, 'L');
        $this->Cell(95, 6, 'Date: ' . date('d-M-Y', strtotime($this->bill['bill_date'] ?? '13-Nov-2025')), 0, 1, 'R');

        if (!empty($this->bill['due_date']) && $this->bill['bill_date'] != $this->bill['due_date']) {
            $this->Cell(100, 6, 'Due Date: ' . date('d-M-Y', strtotime($this->bill['due_date'])), 0, 1, 'L');
        }

        $this->Ln(8);
    }

    // PARTIES SECTION - WITH BANK DETAILS LIKE FIRST FORMAT
    function PartiesSection()
    {
        $party_title = ['c' => "Customer", 's' => 'Supplier'];
        $party_type = $this->bill['party_type'] ?? 'c';

        $gap = 5;

        // Title Row
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);

       $this->SetFillColor(40, 40, 180);

        $this->Cell(95, 7, 'Bill To (' . ($party_title[$party_type] ?? 'Customer') . ')', 1, 0, 'L', true);
        $this->Cell($gap, 7, '', 0, 0);
        $this->Cell(95, 7, 'Bank Details', 1, 1, 'L', true);

        $this->SetFont('Arial', '', 9);
        

        // Background Color
        $this->SetFillColor(255, 250, 240);

        // OUTER BOXES (top part)
        $this->Cell(95, 25, '', 'TLR', 0, 'L', true);
        $this->Cell($gap, 25, '', 0, 0);
        $this->Cell(95, 25, '', 'TLR', 1, 'L', true);

        // Reset color
        $this->SetFillColor(255, 255, 255);

        // LEFT BOX CONTENT - Customer Details
        $startY = $this->GetY() - 25 + 2;
        $this->SetXY(11, $startY);
        $this->SetTextColor(0, 0, 0);

        $party_name = $this->bill['party_name'] ?? 'developer retest';
        $party_address = $this->bill['partydetail']['custo_address'] ?? $this->bill['partydetail']['address'] ?? 'Address';
        $party_mobile = $this->bill['party_mobile'] ?? '4564564564';
        $party_gstin = $this->bill['partydetail']['gst_number'] ?? '---';

        $leftText  = "Name: " . $party_name . "\n";
        $leftText .= "Address: " . $party_address . "\n";
        $leftText .= "Mobile: " . $party_mobile . "\n";
        $leftText .= "GSTIN: " . $party_gstin;
        

        $this->MultiCell(93, 4, $leftText, 0, 'L');

        // RIGHT BOX CONTENT - Bank Details
        $rightX = 10 + 95 + $gap + 2;
        $rightY = $startY;
        $this->SetXY($rightX, $rightY);

        $bankText  = "Bank: " . ($this->bank['name'] ?? 'N/A') . "\n";
        $bankText .= "Branch: " . ($this->bank['branch'] ?? 'N/A') . "\n";
        $bankText .= "A/c No: " . ($this->bank['account'] ?? 'N/A') . "\n";
        $bankText .= "IFSC: " . ($this->bank['ifsc'] ?? 'N/A') . "\n";
        $bankText .= "Account Type: " . ($this->bank['account_type'] ?? 'Current');

        $this->MultiCell(93, 4, $bankText, 0, 'L');

        // DRAW BOTTOM BORDER
        $this->SetY($startY + 23);
        $this->Cell(95, 0, '', 'T', 0);
        $this->Cell($gap, 0, '', 0, 0);
        $this->Cell(95, 0, '', 'T', 1);

        $this->Ln(10);
    }

    // ITEMS TABLE - DYNAMIC COLUMNS BASED ON SETTINGS
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

        // Define ALL column configurations
        $columnConfigs = [
            'item' => ['width' => 60, 'label' => 'Item Description', 'align' => 'L'],
            'caret' => ['width' => 15, 'label' => 'Purity', 'align' => 'C'],
            'piece' => ['width' => 15, 'label' => 'Pcs', 'align' => 'R'],
            'gross' => ['width' => 16, 'label' => 'Gross', 'align' => 'R'],
            'less' => ['width' => 16, 'label' => 'Less', 'align' => 'R'],
            'net' => ['width' => 16, 'label' => 'Net', 'align' => 'R'],
            'tunch' => ['width' => 15, 'label' => 'Tunch', 'align' => 'R'],
            'wastage' => ['width' => 15, 'label' => 'Wastage', 'align' => 'R'],
            'fine' => ['width' => 16, 'label' => 'Fine', 'align' => 'R'],
            'st_ch' => ['width' => 18, 'label' => 'Stone Ch.', 'align' => 'R'],
            'rate' => ['width' => 20, 'label' => 'Rate', 'align' => 'R'],
            'labour' => ['width' => 18, 'label' => 'Labour', 'align' => 'R'],
            'other' => ['width' => 18, 'label' => 'Other', 'align' => 'R'],
            'disc' => ['width' => 15, 'label' => 'Disc.', 'align' => 'R'],
            'total' => ['width' => 25, 'label' => 'Amount', 'align' => 'R']
        ];

        $this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);

        // Build header row
        $widths = [10]; // Serial number width
        $aligns = ['C']; // Serial number alignment
        $headerLabels = ['SN.'];

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

        // Table rows
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 9);
        
        // Data rows
        if (isset($this->bill['billitems']) && is_array($this->bill['billitems'])) {
            foreach ($this->bill['billitems'] as $i => $item) {
                $rowData = [$i + 1]; // Serial number
                
                foreach($billColumns as $column) {
                    switch($column) {
                        case 'item':
                            $rowData[] = $item['item_name'] ?? 'Item';
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
                
                $this->FancyRow($rowData, $widths, $aligns);
            }
        }

        $this->Ln(5);
    }

    // TOTALS SECTION - COMPREHENSIVE LIKE BOTH FORMATS
    function TotalsSection()
    {
        $subtotal = $this->bill['sub'] ?? 638400.00;
        $discount = $this->bill['discount'] ?? 0;
        $gst = $this->bill['gst'] ?? 0;
        $round = $this->bill['round'] ?? 0;
        $final = $this->bill['final'] ?? 638400.00;

        $cgst = $sgst = ($gst > 0) ? (($subtotal * $gst) / 100) / 2 : 0;
         $balance = $this->bill['balance'] ?? 0;
        $bal_ttl = ($balance>0)?'Advance':'Remains';

        // Total Items count
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 6, 'Total Items: ' . count($this->bill['billitems'] ?? []), 0, 1);

        // Totals box
        $this->SetX(120);
        $this->Cell(35, 6, 'Sub Total:', 0, 0);
        $this->Cell(25, 6, number_format($subtotal, 2), 0, 1, 'R');

        // Discount
        if ($discount > 0) {
            $this->SetX(120);
            $text = ($this->bill['discount_unit'] ?? 'r') === 'p' ? "Discount ($discount%)" : "Discount";
            $amt = ($this->bill['discount_unit'] ?? 'r') === 'p' ? ($subtotal * $discount) / 100 : $discount;
            $this->Cell(35, 6, $text . ':', 0, 0);
            $this->Cell(25, 6, '-' . number_format($amt, 2), 0, 1, 'R');
        }

        // GST
        if ($gst > 0) {
            $this->SetX(120);
            $this->Cell(35, 6, 'CGST ' . ($gst / 2) . '%:', 0, 0);
            $this->Cell(25, 6, number_format($cgst, 2), 0, 1, 'R');

            $this->SetX(120);
            $this->Cell(35, 6, 'SGST ' . ($gst / 2) . '%:', 0, 0);
            $this->Cell(25, 6, number_format($sgst, 2), 0, 1, 'R');
        }

        // Round Off
        if ($round != 0) {
            $this->SetX(120);
            $this->Cell(35, 6, 'Round Off:', 0, 0);
            $this->Cell(25, 6, number_format($round, 2), 0, 1, 'R');
        }

        // Grand Total
        $this->SetX(120);
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(0, 0, 128);
        $this->Cell(35, 7, 'Grand Total:', 0, 0);
        $this->Cell(25, 7, number_format($final, 2), 0, 1, 'R');
        $this->SetTextColor(0, 0, 0);

        if(!empty($this->bill['payment'])){
                $this->SetX(120);
                $this->Cell(35, 7, 'Payment:', 0, 0);
                $this->Cell(25, 7, number_format($this->bill['payment'], 2), 0, 1, 'R');
                $this->SetTextColor(0, 0, 0);
        }

        if($balance != 0){
            $rd_col = ($balance<0)?'255':'0';
            $gr_col = ($balance<0)?'0':'100';
            $bl_col = ($balance<0)?'0':'0';
            $this->SetX(120);
            $this->SetTextColor($rd_col, $gr_col, $bl_col);
            $this->Cell(35, 7, $bal_ttl, 0, 0);
            $this->Cell(25, 7, number_format($balance, 2), 0, 1, 'R');
            $this->SetTextColor(0, 0, 0);
        }

        $this->Ln(8);
    }

    // TERMS & SIGNATURES - COMBINED FROM BOTH FORMATS
    function TermsAndSignatures()
    {
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 6, 'Terms & Conditions:', 0, 1);

        $invoice_terms = $this->shop['invoice_terms'] ?? "";

        if (!empty($invoice_terms)) {
            $terms = explode("\n", $invoice_terms);
        } else {
            $terms = [
                '1. Goods once sold will not be taken back or exchanged.',
                '2. All disputes are subject to jurisdiction only.',
                '3. Interest @ 18% p.a. will be charged if payment is delayed.',
                '4. Certified that the particulars given above are true and correct.',
                '5. This is a computer-generated invoice.'
            ];
        }

        $this->SetFont('Arial', '', 8);
        foreach ($terms as $t) {
            $this->MultiCell(0, 4, trim($t), '', 'L');
        }

        $this->Ln(8);

        // Signature section - both customer and company
        $currentY = $this->GetY();
        
        // Customer Signature - Left side
        $this->SetX(20);
        $this->Cell(80, 20, '', 'T', 0, 'C');
        $this->SetX(20);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(80, 5, 'Customer Signature', 0, 0, 'C');

        // Company Signature - Right side
        $this->SetX(120);
        $this->Cell(80, 20, '', 'T', 0, 'C');
        
        $this->SetX(120);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(80, 5, 'For ' . ($this->shop['name'] ?? 'LAXMI JEWELLERS'), 0, 1, 'C');

        $this->SetX(120);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(80, 4, 'Authorised Signatory', 0, 1, 'C');
        $this->Ln(5) ;

        $img_y = $this->GetY() ;
        
        if ($this->signature_path && file_exists($this->signature_path)) {
            $this->Image($this->signature_path, 155, $img_y + 2, 20);
        }
        

    }

    // FOOTER
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(0, 128, 0);
        $this->Cell(0, 10, 'Thank you for your business!', 0, 0, 'C');
    }

    // TABLE WRAP HELPERS - UNCHANGED
    public function FancyRow($data, $widths, $aligns, $lineHeight = 6)
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

    public function NbLines($w, $txt)
    {
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);

        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;

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

                $sep = -1; $j = $i; $l = 0; $nl++;
            } else
                $i++;
        }

        return $nl;
    }

    // Transparency
    function SetAlpha($alpha)
    {
        $this->_out(sprintf('%.2F ca %.2F CA', $alpha, $alpha));
    }
}