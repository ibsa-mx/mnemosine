<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mnemosine</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Styles -->
    <link href="{{ asset('admin/css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <style>
    body {margin: 20px}
    </style>
</head>
<body>
    <table class="table table-bordered table-condensed table-striped">
        @foreach($data as $row)
            @if ($row == reset($data))
                <tr>
                    @foreach($row as $key => $value)
                        <th>{!! $key !!}</th>
                    @endforeach
                </tr>
            @endif
            <tr>
                @foreach($row as $key => $value)
                    @if(is_string($value) || is_numeric($value))
                        <td>{!! $value !!}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>
    <script>
        window.print();
        // setTimeout(function () {
        //     window.close();
        // }, 500);
    </script>
</body>
</html>
