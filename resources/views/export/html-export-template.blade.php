<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>GPS Coordinates Export</title>
    <style>
        table,
        td {
            border: 1px solid #333;
        }
    </style>
</head>
<body>
<table>
    <tbody>
    @foreach ($data as $fields)
        <tr>
            @foreach ($fields as $field)
            <td> {{$field}} </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
