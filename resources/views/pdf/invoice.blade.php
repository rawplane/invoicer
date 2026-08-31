<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }
        .header-table, .items-table, .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .invoice-title {
            font-size: 24px;
            color: #4f46e5;
            font-weight: bold;
            margin: 0;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 10px;
            text-transform: uppercase;
            background-color: #e5e7eb;
        }
        .items-table {
            margin-top: 20px;
        }
        .items-table th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-container {
            width: 40%;
            float: right;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 4px 8px;
        }
        .clear {
            clear: both;
        }
        .notes {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <h2>{{ $user->business_name }}</h2>
                <p style="margin: 0;">{{ $user->address }}</p>
                <p style="margin: 0;">Telp: {{ $user->phone }}</p>
            </td>
            <td class="text-right">
                <h1 class="invoice-title">INVOICE</h1>
                <p class="font-bold" style="margin: 5px 0;">{{ $invoice->invoice_number }}</p>
                <span class="badge">{{ $invoice->status }}</span>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

    <table class="header-table">
        <tr>
            <td>
                <span style="font-size: 11px; color: #6b7280; font-weight: bold;">DITAGIHKAN KEPADA:</span><br>
                <strong>{{ $invoice->client->name }}</strong><br>
                @if($invoice->client->company_name)
                    {{ $invoice->client->company_name }}<br>
                @endif
                {{ $invoice->client->address }}<br>
                {{ $invoice->client->phone }}
            </td>
            <td class="text-right">
                <p style="margin: 2px 0;"><strong>Tanggal Invoice:</strong> {{ $invoice->issue_date->format('d/m/Y') }}</p>
                <p style="margin: 2px 0;"><strong>Jatuh Tempo:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th class="text-center">QTY</th>
                <th class="text-right">HARGA SATUAN</th>
                <th class="text-right">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($invoice->tax > 0)
                <tr>
                    <td>Pajak:</td>
                    <td class="text-right">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($invoice->discount > 0)
                <tr>
                    <td>Diskon:</td>
                    <td class="text-right">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr style="border-top: 2px solid #e5e7eb;">
                <td class="font-bold" style="font-size: 14px; padding-top: 8px;">Total:</td>
                <td class="text-right font-bold" style="font-size: 14px; padding-top: 8px; color: #4f46e5;">
                    Rp {{ number_format($invoice->total, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    @if($invoice->notes)
        <div class="notes">
            <strong>Catatan:</strong><br>
            {!! nl2br(e($invoice->notes)) !!}
        </div>
    @endif
</body>
</html>