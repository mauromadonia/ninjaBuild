    <form action="" method="post">
        <input type="hidden" name="category[id]" value="<?= $category->id ?? '' ?>">
        <label for="joketext">Nome Categoria:</label>
        <input type="text" id="categoryname" name="category[name]" value="<?= $category->name ?? '' ?>"></input>
        <input type="submit" name="submit" value="Salva">
    </form>
