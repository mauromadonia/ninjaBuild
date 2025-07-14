<?php

namespace Ijdb\Entity;

class Joke
{
    public $id;
    public $authorid;
    public $jokedate;
    public $joketext;
    public $authorsTable;

    public function __construct(\Ninja\DatabaseTable $authorsTable)
    {
        $this->authorsTable = $authorsTable;
    }

    public function getAuthor()
    {
        return $this->authorsTable->findById($this->authorid);
    }
}
