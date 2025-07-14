<?php if (isset($error)) : ?>
    <div class="errors"><?= $error ?></div>
<?php endif; ?>

<form method="post" action="">
    <label for='email'>Email</label>
    <input type="text" id="email" name="email">

    <label for="password">Password</label>
    <input type="password" id="password" name="password">

    <input type="submit" name="login" value="Accedi">
</form>

<p>Non hai un account? <a href="author/register">Registrati</a></p>