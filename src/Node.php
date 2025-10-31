<?php

namespace SortedLinkedList;

use InvalidArgumentException;

/**
 * Node class represents a single node in the linked list
 */
class Node
{
    private int|string $data;

    private Node|null $next;

    /**
     * Node constructor
     *
     * @param int|string $data The data to store in this node (must be int or string)
     * @throws InvalidArgumentException If data is not int or string
     */
    public function __construct($data)
    {
        if (!is_int($data) && !is_string($data)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Node data must be int or string, %s given',
                    gettype($data)
                )
            );
        }

        $this->data = $data;
        $this->next = null;
    }

    /**
     * Get the data stored in this node
     *
     * @return int|string The data
     */
    public function getData(): int|string
    {
        return $this->data;
    }

    /**
     * Set the data for this node
     *
     * @param int|string $data The data to store
     * @return void
     * @throws InvalidArgumentException If data is not int or string
     */
    public function setData($data): void
    {
        if (!is_int($data) && !is_string($data)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Node data must be int or string, %s given',
                    gettype($data)
                )
            );
        }

        $this->data = $data;
    }

    /**
     * Get the next node
     *
     * @return Node|null The next node or null
     */
    public function getNext(): ?Node
    {
        return $this->next;
    }

    /**
     * Set the next node
     *
     * @param Node|null $next The next node
     * @return void
     */
    public function setNext(?Node $next): void
    {
        $this->next = $next;
    }
}
