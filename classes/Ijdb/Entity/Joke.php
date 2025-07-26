<?php

namespace Ijdb\Entity;

class Joke
{
    public $id;
    public $authorid;
    public $jokedate;
    public $joketext;
    private $authorsTable;
    private $author;
    private $jokeCategoriesTable;
    private $categoryTable;



    public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $jokeCategoriesTable, \Ninja\DatabaseTable $categoryTable)
    {
        $this->authorsTable = $authorsTable;
        $this->jokeCategoriesTable = $jokeCategoriesTable;
        $this->categoryTable = $categoryTable;
    }

    public function getAuthor()
    {
        if (empty($this->author)) {
            $this->author = $this->authorsTable->findById($this->authorid);
        }
        return $this->author;
    }

    public function addCategory($categoryId)
    {
        $jokeCategory = ['jokeid' => $this->id, 'categoryid' => $categoryId];

        $this->jokeCategoriesTable->save($jokeCategory);
    }

    public function hasCategory($categoryId)
    {
        $jokeCategories = $this->jokeCategoriesTable->find('jokeid', $this->id);

        foreach ($jokeCategories as $jokeCategory) {
            if ($jokeCategory->categoryid == $categoryId) {
                return true;
            }
        }
    }

    public function getCategory($categoryId)
    {
        $jokeCategories = $this->jokeCategoriesTable->find('jokeid', $this->id);


        foreach ($jokeCategories as $jokeCategory) {
            if ($jokeCategory->categoryid == $categoryId) {
                return $this->categoryTable->findById($categoryId);
            }
        }
    }

    public function clearCategories()
    {
        $this->jokeCategoriesTable->deleteWhere('jokeid', $this->id);
    }
}
