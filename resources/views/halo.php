<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sapaan</title>
</head>
<body>

    <!-- Menampilkan variabel $nama dengan tag PHP native -->
    <h1>Halo, <?php echo($nama) ?>!</h1>

    <!-- Form untuk input nama lain -->
    <form method="GET" action="/halo">
        <input type="text" name="name" placeholder="Ketik nama di sini...">
        <button type="submit">Sapa Saya</button>
    </form>

</body>
</html>
