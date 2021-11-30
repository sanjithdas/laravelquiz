<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1>Your score is:  {{  $total_correct ? $total_correct : 0 }} out of {{ $total_question }}</h1>
   @if (session()->has('total'))
     {{
        session()->forget('total')
     }}
    @endif

</body>
</html>