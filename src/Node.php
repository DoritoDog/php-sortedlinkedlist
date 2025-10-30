<?php

namespace SortedLinkedList;

/**
 * Node class represents a single node in the linked list
 */
class Node
{
    /**
     * @var mixed The data stored in this node
     */
    public $data;

    /**
     * @var Node|null Reference to the next node
     */
    public $next;

    /**
     * Node constructor
     *
     * @param mixed $data The data to store in this node
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->next = null;
    }
}
