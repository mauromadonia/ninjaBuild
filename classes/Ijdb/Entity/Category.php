<?php

namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Category
{
    public $id;
    public $name;
    private $jokesTable;
    private $jokesCategoriesTable;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $jokesCategoriesTable)
    {
        $this->jokesTable = $jokesTable;
        $this->jokesCategoriesTable = $jokesCategoriesTable;
    }

    public function getJokes()
    {
        $jokesCategories = $this->jokesCategoriesTable->find('categoryid', $this->id);
        $jokes = [];
        foreach ($jokesCategories as $jokesCategory) {
            $joke = $this->jokesTable->findById($jokesCategory->jokeid);
            if ($joke) {
                $jokes[] = $joke;
            }
        }
        return $jokes;
    }

}
