<?php if (empty($joke->id) || $userId == $joke->authorid) : ?>
    <form action="" method="post">
        <input type="hidden" name="joke[id]" value="<?= $joke->id ?? '' ?>">
        <label for="joketext">Modifica Barzelletta:</label>
        <textarea id="joketext" name="joke[joketext]"><?= $joke->joketext ?? '' ?></textarea>
        <p>Seleziona Categoria:</p>
        <?php foreach ($categories as $category) : ?>
            <?php if ($joke && $joke->hasCategory($category->id)) : ?>
                <span>
                    <input type="checkbox" checked name="category[]" value="<?= $category->id ?>" id="category" />
                    <label id="category"><?= $category->name ?></label>
                </span>
            <?php else : ?>
                <span>
                    <input type="checkbox" name="category[]" value="<?= $category->id ?>" id="category" />
                    <label id="category"><?= $category->name ?></label>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
        <input type="submit" name="submit" value="Salva">
    </form>
<?php else : ?>
    <p>Puoi modificare solo le tue barzellette</p>
<?php endif; ?>