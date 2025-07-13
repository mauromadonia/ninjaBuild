<p><?= $totalJokes ?> barzellette registrate nel Database.</p>

<?php
foreach ($jokes as $joke) : ?>
    <blockquote class="blockquote">
        <div class="blockquote__left">
            <div class="blockquote__author">
                <p>
                    <?= htmlspecialchars($joke['name'], ENT_QUOTES, 'utf-8') ?> -
                    <span>
                        <?php $date = new DateTime($joke['jokedate']); ?>
                        <?= $date->format('d.m.Y') ?>
                    </span>
                </p>
                <p><?= htmlspecialchars($joke['email'], ENT_QUOTES, 'utf-8') ?></p>
            </div>
            <div class="blockquote__joke">
                <p>
                    <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'utf-8') ?>
                </p>
            </div>
        </div>
        <div class="blockquote__right">
            <a href="/joke/edit?id=<?= $joke['id'] ?>" class="submit">Modifica</a>
            <form action="/joke/delete" method="post">
                <input type="hidden" name="id" value="<?= $joke['id'] ?>">
                <input type="submit" value="Elimina">
            </form>
        </div>
    </blockquote>
<?php endforeach ?>
</body>

</html>