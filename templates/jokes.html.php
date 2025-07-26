<div class="jokeList">
    <ul class="jokeList__categories">
        <li><a href="/joke/list">Tutte</a>
        </li>
        <?php foreach ($categories as $category) : ?>
            <li><a href="/joke/list?category=<?= $category->id ?>">
                    <?= $category->name ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<p><?= $totalJokes ?> barzellette registrate nel Database.</p>
<?php
foreach ($jokes as $joke) : ?>
    <blockquote class="blockquote">
        <div class="blockquote__left">
            <div class="blockquote__author">
                <p>
                    <?= htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES, 'utf-8') ?> -
                    <span>
                        <?php $date = new DateTime($joke->jokedate); ?>
                        <?= $date->format('d.m.Y') ?>
                    </span>
                </p>
                <p><?= htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES, 'utf-8') ?></p>
            </div>
            <div class="blockquote__joke">
                <p class="blockquote__joke__text">
                    <?= htmlspecialchars($joke->joketext, ENT_QUOTES, 'utf-8') ?>
                </p>
                <?php foreach ($categories as $category) : ?>
                    <p class="blockquote__joke__cat"><?= $joke->getCategory($category->id)->name ?? '' ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="blockquote__right">
            <?php if ($userId == $joke->authorid) : ?>
                <a href="/joke/edit?id=<?= $joke->id ?>" class="submit">Modifica</a>
                <form action="/joke/delete" method="post">
                    <input type="hidden" name="id" value="<?= $joke->id ?>">
                    <input type="submit" value="Elimina">
                </form>
            <?php endif; ?>
        </div>
    </blockquote>
<?php endforeach ?>
</body>

</html>