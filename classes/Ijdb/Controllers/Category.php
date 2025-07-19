<?php

namespace Ijdb\Controllers;

class Category
{
    private $categoryTable;

    public function __construct(\Ninja\DatabaseTable $categoryTable)
    {
        $this->categoryTable = $categoryTable;
    }

    public function edit()
    {
        if (isset($_GET['id'])) {
            $category = $this->categoryTable->findById($_GET['id']);
        }

        $title = 'Modifica Categoria';

        return [
            'template' => 'editcategory.html.php',
            'title' => $title,
            'variables' => [
                'category' => $category ?? null
            ],
        ];
    }

    public function saveEdit()
    {
        $category = $_POST['category'];

        $this->categoryTable->save($category);

        header('location: /category/list');
    }

    public function delete()
    {
        $this->categoryTable->delete($_POST['id']);

        header('location: /category/list');
    }

    public function list()
    {
        $categories = $this->categoryTable->findAll();

        return [
            'template' => 'categories.html.php',
            'title' => 'Categorie',
            'variables' => [
                'categories' => $categories,
            ],

        ];
    }
}
