<?php

namespace App\ORM\Tools\Pagination;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Traversable;
use ArrayIterator;

class Paginator
{
    private DoctrinePaginator $paginator;

    public function __construct($query, $fetchJoinCollection = true)
    {
        $this->paginator = new DoctrinePaginator($query, $fetchJoinCollection);
    }

    public function getCount(): int
    {
        return $this->paginator->count();
    }

    public function getResult($hydrationMode = AbstractQuery::HYDRATE_OBJECT): mixed
    {
        return $this->paginator->getQuery()->getResult($hydrationMode);
    }

    /**
     * @throws \Exception
     */
    public function getIterator(): Traversable|ArrayIterator
    {
        return $this->paginator->getIterator();
    }
}
