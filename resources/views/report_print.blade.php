<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Report</title>
</head>
<body onload="print()">
    <div>
        <h1>Miftahul 'Ilmi Samarinda</h1>
    </div>
    <div>
        <h5>Nilai Rapor</h5>
    </div>
    <div>
        <h4>Tajwid Online</h4>
    </div>
    <div>
        <ul>
            <li>Nama peserta : {{$quizStudent->name_student}}</li>
            <li>Jumlah tidak hadir :
                <ul>
                    @foreach ($countAbsen as $item)
                        <li>{{$item->absen_type_name}} : {{$item->count}}</li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</body>
</html>
