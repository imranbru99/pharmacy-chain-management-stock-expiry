<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Expiry Report</title></head>
<body>
<h1>Expiry Report</h1>
<table border="1" cellpadding="4">
    <tr><th>Branch</th><th>Product</th><th>Batch</th><th>Qty</th><th>Expires</th></tr>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row['branch'] }}</td>
            <td>{{ $row['product'] }}</td>
            <td>{{ $row['batch_number'] }}</td>
            <td>{{ $row['quantity'] }}</td>
            <td>{{ $row['expires_at'] }}</td>
        </tr>
    @endforeach
</table>
</body></html>
