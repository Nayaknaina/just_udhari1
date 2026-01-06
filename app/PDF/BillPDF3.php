<?php

namespace App\PDF;

require_once(base_path('vendor/autoload.php'));

class BillPDF3 extends \FPDF
{
    private $bill;
    private $shop;
    private $bank;
    private $logo_path;
    private $signature_path;
    private $watermark_path;

    // Color scheme - KEEPING ALL COLORS
    private $primary_color = [41, 128, 185];    // Blue
    private $secondary_color = [52, 73, 94];    // Dark Blue
    private $accent_color = [230, 126, 34];     // Orange
    private $light_bg = [245, 245, 245];        // Light Gray
    private $dark_text = [44, 62, 80];          // Dark Text
    private $border_color = [220, 220, 220];    // Border Gray

    public function __construct($bill = [], $shop = [], $bank = [], $logo_path = null, $signature_path = null, $watermark_path = null)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->bill = $bill;
        $this->shop = $shop;
        $this->bank = $bank;
        $this->logo_path = $logo_path;
        $this->signature_path = $signature_path;
        $this->watermark_path = $watermark_path;
    }

    // ================================
    // HEADER - FIXED POSITIONING
    // ================================
    function Header()
    {
        // Watermark - POSITION FIXED
        if ($this->watermark_path && file_exists($this->watermark_path)) {
            $this->SetAlpha(0.08);
            $this->Image($this->watermark_path, 25, 60, 160); // Adjusted position
            $this->SetAlpha(1);
        }

        // Main header with gradient effect - KEEP COLORS
        $this->SetFillColor($this->primary_color[0], $this->primary_color[1], $this->primary_color[2]);
        $this->Rect(0, 0, 210, 35, 'F'); // Reduced height for better positioning
        
        // Secondary accent strip
        $this->SetFillColor($this->accent_color[0], $this->accent_color[1], $this->accent_color[2]);
        $this->Rect(0, 35, 210, 3, 'F');

        // Logo - POSITION FIXED
        if ($this->logo_path && file_exists($this->logo_path)) {
            $this->Image($this->logo_path, 15, 5, 35); // Smaller logo for better fit
        }

        // Shop Name - Centered and prominent - POSITION FIXED
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 18); // Slightly smaller font
        $this->SetXY(0, 8);
        $this->Cell(210, 8, strtoupper($this->shop['name'] ?? 'LAXMI'), 0, 1, 'C');

        // Shop Tagline - POSITION FIXED
        $this->SetFont('Arial', 'I', 9);
        $this->SetXY(0, 16);
        $this->Cell(210, 6, 'Trusted Gold & Jewellery Merchants', 0, 1, 'C');

        // Contact info WITHOUT ICONS - POSITION FIXED
        $this->SetFont('Arial', '', 8);
        $this->SetXY(0, 22);
        $this->Cell(210, 4, 'Phone: ' . ($this->shop['mobile_no'] ?? '9109561833'), 0, 1, 'C');
        
        $this->SetXY(0, 26);
        $this->Cell(210, 4, ($this->shop['address'] ?? 'Adamgadh w'), 0, 1, 'C');
        
        $this->SetXY(0, 30);
        $this->Cell(210, 4, 'GST: ' . ($this->shop['gst_num'] ?? 'GSTINXXXXXXXXXXXX'), 0, 1, 'C');

        $this->Ln(12); // Adjusted spacing

        // Invoice title with modern design - KEEP COLORS
        $this->SetFillColor($this->secondary_color[0], $this->secondary_color[1], $this->secondary_color[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'TAX INVOICE', 0, 1, 'C', true);

        // Invoice details - POSITION FIXED
        $this->SetFillColor($this->light_bg[0], $this->light_bg[1], $this->light_bg[2]);
        $this->SetTextColor($this->dark_text[0], $this->dark_text[1], $this->dark_text[2]);
        $this->SetFont('Arial', '', 10);
        
        $this->Cell(95, 6, 'Invoice No: ' . ($this->bill['bill_number'] ?? '324324'), 0, 0, 'L', true);
        $this->Cell(95, 6, 'Date: ' . date('d-M-Y', strtotime($this->bill['bill_date'] ?? '13-Nov-2025')), 0, 1, 'R', true);

        if (!empty($this->bill['due_date']) && $this->bill['due_date'] != $this->bill['bill_date']) {
            $this->Cell(105, 6, 'Due Date: ' . date('d-M-Y', strtotime($this->bill['due_date'])), 0, 1, 'L', true);
        }

        $this->Ln(5);
    }

    // ================================
    // PARTIES SECTION - WITH BANK DETAILS LIKE BILLPDF2
    // ================================
    function PartiesSection()
    {
        $party_title = ['c' => "Customer", 's' => 'Supplier'];
        $party_type = $this->bill['party_type'] ?? 'c';

        $gap = 5;

        // Title Row - USING BILLPDF3 COLORS
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColor($this->secondary_color[0], $this->secondary_color[1], $this->secondary_color[2]);

        $this->Cell(95, 7, 'Bill To (' . ($party_title[$party_type] ?? 'Customer') . ')', 1, 0, 'L', true);
        $this->Cell($gap, 7, '', 0, 0);
        $this->Cell(95, 7, 'Bank Details', 1, 1, 'L', true);

        $this->SetFont('Arial', '', 9);
        
        // Background Color - USING BILLPDF3 LIGHT BG COLOR
        $this->SetFillColor($this->light_bg[0], $this->light_bg[1], $this->light_bg[2]);

        // OUTER BOXES (top part)
        $this->Cell(95, 25, '', 'TLR', 0, 'L', true);
        $this->Cell($gap, 25, '', 0, 0);
        $this->Cell(95, 25, '', 'TLR', 1, 'L', true);

        // Reset color
        $this->SetFillColor(255, 255, 255);

        // LEFT BOX CONTENT - Customer Details (FROM BILLPDF2)
        $startY = $this->GetY() - 25 + 2;
        $this->SetXY(11, $startY);
        $this->SetTextColor($this->dark_text[0], $this->dark_text[1], $this->dark_text[2]);

        $party_name = $this->bill['party_name'] ?? 'developer retest';
        $party_address = $this->bill['partydetail']['custo_address'] ?? $this->bill['partydetail']['address'] ?? 'Address';
        $party_mobile = $this->bill['party_mobile'] ?? '4564564564';
        $party_gstin = $this->bill['partydetail']['gst_number'] ?? '---';

        $leftText  = "Name: " . $party_name . "\n";
        $leftText .= "Address: " . $party_address . "\n";
        $leftText .= "Mobile: " . $party_mobile . "\n";
        $leftText .= "GSTIN: " . $party_gstin;

        $this->MultiCell(93, 4, $leftText, 0, 'L');

        // RIGHT BOX CONTENT - Bank Details (FROM BILLPDF2)
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

    // ================================
    // ITEMS TABLE - DYNAMIC COLUMNS
    // ================================
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

        // Table header with modern colors - KEEP COLORS
        $this->SetFillColor($this->primary_color[0], $this->primary_color[1], $this->primary_color[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetDrawColor($this->border_color[0], $this->border_color[1], $this->border_color[2]);

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

        // Table rows with alternating colors - KEEP COLORS
        $this->SetTextColor($this->dark_text[0], $this->dark_text[1], $this->dark_text[2]);
        $this->SetFont('Arial', '', 9);

        // Data rows
        if (isset($this->bill['billitems']) && is_array($this->bill['billitems'])) {
            foreach ($this->bill['billitems'] as $i => $item) {
                // Alternate row background
                if ($i % 2 == 0) {
                    $this->SetFillColor(250, 250, 250);
                } else {
                    $this->SetFillColor(255, 255, 255);
                }

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
                
                $this->FancyRow($rowData, $widths, $aligns, 6, true);
            }
        }

        $this->Ln(5);
    }

    // ================================
    // TOTALS SECTION - COMPREHENSIVE LIKE BILLPDF2
    // ================================
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

        // Total Items count (FROM BILLPDF2)
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor($this->dark_text[0], $this->dark_text[1], $this->dark_text[2]);
        $this->Cell(100, 6, 'Total Items: ' . count($this->bill['billitems'] ?? []), 0, 1);

        // Totals box - USING BILLPDF3 STYLE BUT BILLPDF2 DATA
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.3);
        $this->SetFillColor(250, 250, 250);
        
        $y = $this->GetY();
        $this->Rect(120, $y, 85, 50, 'DF');
        
        $this->SetDrawColor(180, 180, 180);
        $this->Rect(120, $y, 85, 50);

        $this->SetXY(125, $y + 4);

        // Subtotal
        $this->Cell(40, 6, 'Sub Total:', 0, 0);
        $this->Cell(35, 6, number_format($subtotal, 2), 0, 1, 'R');

        // Discount (FROM BILLPDF2)
        if ($discount > 0) {
            $this->SetX(125);
            $text = ($this->bill['discount_unit'] ?? 'r') === 'p' ? "Discount ($discount%)" : "Discount";
            $amt = ($this->bill['discount_unit'] ?? 'r') === 'p' ? ($subtotal * $discount) / 100 : $discount;
            $this->Cell(40, 6, $text . ':', 0, 0);
            $this->Cell(35, 6, '-' . number_format($amt, 2), 0, 1, 'R');
        }

        // GST (FROM BILLPDF2)
        if ($gst > 0) {
            $this->SetX(125);
            $this->Cell(40, 6, 'CGST ' . ($gst / 2) . '%:', 0, 0);
            $this->Cell(35, 6, number_format($cgst, 2), 0, 1, 'R');

            $this->SetX(125);
            $this->Cell(40, 6, 'SGST ' . ($gst / 2) . '%:', 0, 0);
            $this->Cell(35, 6, number_format($sgst, 2), 0, 1, 'R');
        }

        // Round Off
        if ($round != 0) {
            $this->SetX(125);
            $this->Cell(40, 6, 'Round Off:', 0, 0);
            $this->Cell(35, 6, number_format($round, 2), 0, 1, 'R');
        }

        // Separator line
        $this->SetX(125);
        $this->Cell(40, 1, '', 'T', 1);
        $this->Ln(1);

        // Grand Total - Highlighted WITH BILLPDF3 COLORS
            $this->SetFont('Arial', 'B', 11);

            // Background color (accent)
            $this->SetFillColor($this->accent_color[0], $this->accent_color[1], $this->accent_color[2]);

            // *** "Grand Total:" text is gray ***
            $this->SetTextColor(120, 120, 120); // your gray RGB

            $this->SetX(125);
            $this->Cell(40, 7, 'Grand Total:', 0, 0);

            // *** Amount text is WHITE ***
            $this->SetTextColor(255, 255, 255); // white

            $this->Cell(35, 7, number_format($final, 2), 0, 1, 'R', true);

            // Reset text color back to default dark
            if($balance != 0){
                $rd_col = ($balance<0)?'255':'0';
                $gr_col = ($balance<0)?'0':'255';
                $bl_col = ($balance<0)?'0':'0';
                $this->SetX(125);
                $this->SetTextColor($rd_col, $gr_col, $bl_col);
                $this->Cell(40, 7, $bal_ttl, 0, 0);
                $this->Cell(35, 7, number_format($balance, 2), 0, 1, 'R');
            }
            $this->SetTextColor(
                $this->dark_text[0],
                $this->dark_text[1],
                $this->dark_text[2]
            );

            $this->Ln(8);

    }

    // ================================
    // TERMS & SIGNATURE - WITH BOTH SIGNATURES LIKE BILLPDF2
    // ================================
    function TermsAndSignatures()
    {
        // Left side - Terms & Conditions - KEEP COLORS
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor($this->secondary_color[0], $this->secondary_color[1], $this->secondary_color[2]);
        $this->Cell(0, 8, 'Terms & Conditions:', 0, 1);

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
        $this->SetTextColor($this->dark_text[0], $this->dark_text[1], $this->dark_text[2]);
        
        foreach ($terms as $t) {
            $this->MultiCell(0, 4, trim($t), '', 'L');
        }

        $this->Ln(8);

        // Signature section - both customer and company LIKE BILLPDF2
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
        
        if ($this->signature_path && file_exists($this->signature_path)) {
            $this->Image($this->signature_path, 150,  $currentY + 8, 20);
        }
        
        $this->SetX(120);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(80, 5, 'For ' . ($this->shop['name'] ?? 'LAXMI JEWELLERS'), 0, 1, 'C');

        $this->SetX(120);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(80, 4, 'Authorised Signatory', 0, 1, 'C');

        $this->Ln(10);
    }

    // ================================
    // FOOTER - KEEP DESIGN
    // ================================
    function Footer()
    {
        $this->SetY(-15);
        
        // Footer background - KEEP COLORS
        $this->SetFillColor($this->secondary_color[0], $this->secondary_color[1], $this->secondary_color[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'I', 9);
        
        $this->Cell(210, 8, 'Thank you for your business!', 0, 0, 'C', true);
    }

    // ================================
    // REQUIRED: FancyRow() - UNCHANGED
    // ================================
    public function FancyRow($data, $widths, $aligns, $lineHeight = 6, $fill = false)
    {
        $maxLines = 0;

        for ($i = 0; $i < count($data); $i++) {
            $maxLines = max($maxLines, $this->NbLines($widths[$i], $data[$i]));
        }

        $rowHeight = $lineHeight * $maxLines;

        if ($this->GetY() + $rowHeight > $this->PageBreakTrigger) {
            $this->AddPage();
        }

        $this->SetDrawColor($this->border_color[0], $this->border_color[1], $this->border_color[2]);

        for ($i = 0; $i < count($data); $i++) {
            $x = $this->GetX();
            $y = $this->GetY();

            // Draw cell border
            $this->Rect($x, $y, $widths[$i], $rowHeight);
            
            // Fill background if needed
            if ($fill) {
                $this->SetXY($x, $y);
                $this->Cell($widths[$i], $rowHeight, '', 0, 0, '', true);
            }

            $this->SetXY($x, $y);
            $this->MultiCell($widths[$i], $lineHeight, $data[$i], 0, $aligns[$i]);

            $this->SetXY($x + $widths[$i], $y);
        }

        $this->Ln($rowHeight);
    }

    // ================================
    // REQUIRED: NbLines() - UNCHANGED
    // ================================
    public function NbLines($w, $txt)
    {
        $cw = $this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);

        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;

        $sep = -1;
        $i = 0; $j = 0; $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];

            if ($c == "\n") {
                $i++; $sep = -1; $j = $i; $l = 0; $nl++;
                continue;
            }

            if ($c == ' ') $sep = $i;

            $l += $cw[$c];

            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else {
                    $i = $sep + 1;
                }
                $sep = -1; $j = $i; $l = 0; $nl++;
            } else {
                $i++;
            }
        }

        return $nl;
    }

    // ================================
    // OPACITY - UNCHANGED
    // ================================
    function SetAlpha($alpha)
    {
        $this->_out(sprintf('%.2F ca %.2F CA', $alpha, $alpha));
    }
}