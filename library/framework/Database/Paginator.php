<?php

namespace Library\Framework\Database;

class Paginator
{
    /** @var array<object> */
    public array $items;

    public int $total;
    public int $perPage;
    public int $currentPage;
    public int $lastPage;
    public ?int $from;
    public ?int $to;

    /**
     * @param array<object> $items
     */
    public function __construct(array $items, int $total, int $perPage, int $currentPage)
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = max(1, (int)$perPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->lastPage = (int) ceil($this->total / $this->perPage);

        if ($this->total === 0) {
            $this->from = null;
            $this->to = null;
        } else {
            $this->from = ($this->currentPage - 1) * $this->perPage + 1;
            $this->to = min($this->total, $this->currentPage * $this->perPage);
        }
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'total' => $this->total,
            'per_page' => $this->perPage,
            'current_page' => $this->currentPage,
            'last_page' => $this->lastPage,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}