<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Hi there, input your name and you got your data!</h1>

    @if (!empty($name))
        <h3>Greeting for: {{ $name }}</h3>
    @endif

    <form method="POST" action="/">
        @csrf
        @method('PUT')
        <label for="name">Enter your name:</label>
        <input type="text" name="name" id="name" value="{{ $name ?? '' }}">
        <button type="submit">Submit</button>
    </form>

    <hr>

    <h2>Data List:</h2>
    @forelse ($data as $d)
        <h3>{{ $d->name }}</h3>
    @empty
        <p>Data masih kosong.</p>
    @endforelse
</body>
</html>
