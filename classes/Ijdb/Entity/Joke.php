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

    public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $jokeCategoriesTable)
    {
        $this->authorsTable = $authorsTable;
        $this->jokeCategoriesTable = $jokeCategoriesTable;
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
}
