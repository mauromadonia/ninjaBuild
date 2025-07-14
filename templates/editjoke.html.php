<form action="" method="post">
    <input type="hidden" name="joke[id]" value="<?= $joke['id'] ?? ''?>">
    <label for="joketext">Modifica Barzelletta:</label>
    <textarea id="joketext" name="joke[joketext]"><?= $joke['joketext'] ?? '' ?></textarea>
    <input type="submit" name="submit" value="Salva">
</form>