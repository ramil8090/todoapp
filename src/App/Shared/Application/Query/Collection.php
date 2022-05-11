<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;


use App\Shared\Infrastructure\Exception\NotFoundException;

class Collection
{
    public int $page;

    public int $limit;

    public int $total;

    /**
     * @var Item[]
     */
    public array $data;

    /**
     * @param Item[]|array $data
     *
     * @throws NotFoundException
     */
    public function __construct(int $page, int $limit, int $total, array $data)
    {
        $this->exists($page, $limit, $total);
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->data = $data;
    }

    /**
     * @throws NotFoundException
     */
    private function exists(int $page, int $limit, int $total): void
    {
        if (($limit * ($page - 1)) >= $total) {
            throw new NotFoundException();
        }
    }
}
