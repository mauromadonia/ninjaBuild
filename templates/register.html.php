<?php if (!empty($errors)) : ?>
    <div class="errors">
        <p>Il tuo account non può essere creato. Controlla quanto segue:</p>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" method="post">

    <label for="author">Nome</label>
    <input type="text"  id="author" name="author[name]" value="<?=$author['name'] ?? ''?>">
    <label for="email">Email</label>
    <input type="text" id="email" name="author[email]" value="<?=$author['email'] ?? ''?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="author[password]" value="<?=$author['password'] ?? ''?>">

    <input type="submit" name="submit" value="Registra account">
</form>