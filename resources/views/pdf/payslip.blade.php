<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
</head>
<body>
    <h1>Payslip</h1>
    <p>{{ $employee->name }} ({{ $employee->employee_code }})</p>
    <p>Period: {{ $run->period_start?->format('Y-m-d') }} — {{ $run->period_end?->format('Y-m-d') }}</p>
    <table>
        <tr><td>Basic</td><td>{{ money_format($item->basic_minor) }}</td></tr>
        <tr><td>Allowances</td><td>{{ money_format($item->allowances_minor) }}</td></tr>
        <tr><td>Deductions</td><td>{{ money_format($item->deductions_minor) }}</td></tr>
        <tr><td><strong>Net</strong></td><td><strong>{{ money_format($item->net_minor) }}</strong></td></tr>
    </table>
</body>
</html>
