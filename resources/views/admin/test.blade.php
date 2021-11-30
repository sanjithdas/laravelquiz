<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
{{
    $json =  File::get(public_path("data/menu.json"));
   // $menus = json_encode($json);
    }}

    @foreach ( json_encode($json) as $menu)
        {{ $menu }}
    @endforeach
</body>
</html>
