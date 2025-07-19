<h2>Categorie</h2>

<a href="/category/edit">Aggiungi nuova categoria</a>

<?php foreach ($categories as $category) : ?>
    <blockquote class="blockquote">
        <div class="blockquote__left">
            <p>
                <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
            </p>

        </div>
        <div class="blockquote__right">
            <a href="/category/edit?id=<?= $category->id ?>" class="submit">Modifica</a>
            <form action="/category/delete" method="post">
                <input type="hidden" name="id" value="<?= $category->id ?>">
                <input type="submit" value="Elimina">
            </form>
        </div>
    </blockquote>
<?php endforeach; ?>