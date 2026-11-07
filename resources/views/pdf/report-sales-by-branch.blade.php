<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Sales by Branch</title></head>
<body>
<h1>Sales by Branch</h1>
<table border="1" cellpadding="4">
    <tr><th>Branch</th><th>Sales</th><th>Total</th></tr>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row['branch'] }}</td>
            <td>{{ $row['sale_count'] }}</td>
            <td>{{ money_format($row['total_minor']) }}</td>
        </tr>
    @endforeach
</table>
</body></html>
