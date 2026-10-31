<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @php
        $filenamePart = static function ($value, $fallback) {
            $value = preg_replace('/[<>:"\/\\\\|?*\x00-\x1F\x7F]/u', '', (string) $value);
            $value = preg_replace('/\s+/u', '-', trim($value));
            return trim($value, '.- ') ?: (string) $fallback;
        };
        $printFilename = $filenamePart($productionItemMaster->purchaseOrder->party->first_name ?? '', 'Customer')
            . '-' . $filenamePart($productionItemMaster->purchaseOrder->purchase_order_id ?? '', $productionItemMaster->id);
    @endphp
    <title>{{ $printFilename }}</title>
    <style>
        @page { size: 210mm 148mm; margin: 8mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }
        .container { width: 194mm; max-width: 100%; }
        h1 { margin: 0 0 7mm; text-align: center; font-size: 20px; text-decoration: underline; }
        .details { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .details td {
            width: 50%;
            padding: 0 6mm 3mm 0;
            vertical-align: top;
            overflow-wrap: anywhere;
        }
        .details td:last-child { padding-right: 0; padding-left: 6mm; }
        .field { display: flex; align-items: flex-start; }
        .label { flex: 0 0 27mm; font-weight: bold; }
        .value { flex: 1; min-width: 0; overflow-wrap: anywhere; }
        .remarks { margin-top: 4mm; }
        .remarks-label { margin-bottom: 1mm; font-weight: bold; break-after: avoid; }
        .remarks-value { white-space: pre-wrap; overflow-wrap: anywhere; }
        @media screen {
            body { width: 210mm; margin: 0 auto; padding: 8mm; }
        }
    </style>
</head>
<body onload="window.focus(); window.print()" onafterprint="window.close()">
    <main class="container">
        <h1>Work Order</h1>
        <table class="details">
            <tbody>
                <tr>
                    <td><div class="field"><span class="label">Date:</span><span class="value">{{ optional($productionItemMaster->purchaseOrder)->po_date ? \Carbon\Carbon::parse($productionItemMaster->purchaseOrder->po_date)->format('d M Y') : 'N/A' }}</span></div></td>
                    <td><div class="field"><span class="label">Brand:</span><span class="value">{{ $productionItemMaster->item->brand->name ?? 'N/A' }}</span></div></td>
                </tr>
                <tr>
                    <td><div class="field"><span class="label">Customer:</span><span class="value">{{ $productionItemMaster->purchaseOrder->party->first_name ?? 'N/A' }}</span></div></td>
                    <td><div class="field"><span class="label">Category:</span><span class="value">{{ $productionItemMaster->item->category->name ?? 'N/A' }}</span></div></td>
                </tr>
                <tr>
                    <td><div class="field"><span class="label">W.O. No.:</span><span class="value">{{ $productionItemMaster->purchaseOrder->purchase_order_id ?? 'N/A' }}</span></div></td>
                    <td><div class="field"><span class="label">Product Type:</span><span class="value">{{ $productionItemMaster->production_type ?? 'N/A' }}</span></div></td>
                </tr>
                <tr>
                    <td><div class="field"><span class="label">Item:</span><span class="value">{{ $productionItemMaster->item->name ?? 'N/A' }}</span></div></td>
                    <td><div class="field"><span class="label">Machine:</span><span class="value">{{ $productionItemMaster->assignedMachine->machine_name ?? 'N/A' }}</span></div></td>
                </tr>
                <tr>
                    <td><div class="field"><span class="label">Quantity:</span><span class="value">{{ $productionItemMaster->requested_qty ?? 'N/A' }}</span></div></td>
                    <td><div class="field"><span class="label">Operator:</span><span class="value">{{ $productionItemMaster->assignedProductionUser->full_name ?? 'N/A' }}</span></div></td>
                </tr>
            </tbody>
        </table>
        <div class="remarks">
            <div class="remarks-label">Production Remarks:</div>
            <div class="remarks-value">{{ $productionItemMaster->production_remarks ?: 'N/A' }}</div>
        </div>
        <div class="remarks">
            <div class="remarks-label">Packing Remarks:</div>
            <div class="remarks-value">{{ $productionItemMaster->packing_remarks ?: 'N/A' }}</div>
        </div>
    </main>
</body>
</html>
