<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/style.min.css" />
    <title><?= $title ?></title>
</head>

<body>

    <header>
        <h1>Database Barzellette</h1>
    </header>
    <nav>
        <ul>
            <li><a class="navLink" href="/">Home</a></li>
            <li><a class="navLink" href="/joke/list">Barzellette</a></li>
            <li><a class="navLink" href="/joke/edit">Aggiungi Barzelletta</a></li>
        </ul>
        <ul>
            <?php if ($loggedIn) : ?>
                <li><a class="navLink" href="/logout">Esci</a></li>
            <?php else : ?>
                <li><a class="navLink" href="/login">Accedi</a></li>
                <li><a class="navLink" href="/author/register">Nuovo Account</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>

<main>
    <div class="container">
        <?= $output ?>
    </div>
</main>

<footer>&copy; IJDB 2025</footer>


</html>