<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 20px; margin-bottom: 2px; text-align: center; }
        h2 { font-size: 14px; margin-top: 0; text-align: center; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        td { padding: 5px 8px; vertical-align: top; }
        .label { font-weight: bold; width: 180px; }
        .box { border: 1px solid #999; padding: 10px; min-height: 80px; margin-bottom: 14px; }
        .sig-row td { width: 50%; }
        .sig-box { border: 1px solid #999; height: 60px; }
    </style>
</head>
<body>
    <h1>GRACE SUPPORT SERVICES</h1>
    <h2>Shift Report</h2>

    <table>
        <tr>
            <td class="label">Support Date</td>
            <td>{{ $shiftReport->support_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Participant's Name</td>
            <td>{{ $shiftReport->participant_name }}</td>
        </tr>
        <tr>
            <td class="label">Staff Name</td>
            <td>{{ $shiftReport->staff->full_name }}</td>
        </tr>
        <tr>
            <td class="label">Shift Start</td>
            <td>{{ $shiftReport->shift_start }}</td>
        </tr>
        <tr>
            <td class="label">Shift End</td>
            <td>{{ $shiftReport->shift_end }}</td>
        </tr>
        <tr>
            <td class="label">Roster Hours</td>
            <td>{{ $shiftReport->roster_hours ?? '-' }}</td>
        </tr>
    </table>

    <p><strong>Progress Report</strong></p>
    <div class="box">{{ $shiftReport->progress_report }}</div>

    <table>
        <tr>
            <td class="label">Reimbursement Amount</td>
            <td>{{ $shiftReport->reimbursement_amount ? '$'.number_format($shiftReport->reimbursement_amount, 2) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kilometre</td>
            <td>{{ $shiftReport->kilometre ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kilometre Description</td>
            <td>{{ $shiftReport->kilometre_description ?? '-' }}</td>
        </tr>
    </table>

    <table class="sig-row">
        <tr>
            <td>
                <strong>Client Signature</strong><br><br>
                @if($shiftReport->client_signature_path)
                    <img src="{{ storage_path('app/public/'.$shiftReport->client_signature_path) }}" height="55">
                @else
                    <div class="sig-box"></div>
                @endif
            </td>
            <td>
                <strong>Staff Signature</strong><br><br>
                <img src="{{ storage_path('app/public/'.$shiftReport->staff_signature_path) }}" height="55">
            </td>
        </tr>
    </table>
</body>
</html>