<?php

namespace SortedLinkedList;

/**
 * SortedLinkedList maintains elements in sorted order
 */
class SortedLinkedList
{
    /**
     * @var Node|null The head of the linked list
     */
    private $head;

    /**
     * @var int The number of elements in the list
     */
    private $size;

    /**
     * @var callable|null Custom comparison function
     */
    private $comparator;

    /**
     * SortedLinkedList constructor
     *
     * @param callable|null $comparator Optional comparison function.
     *                                  Should return negative if $a < $b, 0 if equal, positive if $a > $b
     */
    public function __construct(?callable $comparator = null)
    {
        $this->head = null;
        $this->size = 0;
        $this->comparator = $comparator;
    }

    /**
     * Insert a value into the sorted linked list
     *
     * @param mixed $data The data to insert
     * @return void
     */
    public function insert($data): void
    {
        $newNode = new Node($data);

        // If list is empty or new node should be at the head
        if ($this->head === null || $this->compare($data, $this->head->data) < 0) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            $this->size++;
            return;
        }

        // Find the correct position to insert
        $current = $this->head;
        while ($current->next !== null && $this->compare($current->next->data, $data) < 0) {
            $current = $current->next;
        }

        // Insert the new node
        $newNode->next = $current->next;
        $current->next = $newNode;
        $this->size++;
    }

    /**
     * Delete the first occurrence of a value from the list
     *
     * @param mixed $data The data to delete
     * @return bool True if the value was found and deleted, false otherwise
     */
    public function delete($data): bool
    {
        if ($this->head === null) {
            return false;
        }

        // If the head needs to be deleted
        if ($this->compare($this->head->data, $data) === 0) {
            $this->head = $this->head->next;
            $this->size--;
            return true;
        }

        // Search for the node to delete
        $current = $this->head;
        while ($current->next !== null) {
            if ($this->compare($current->next->data, $data) === 0) {
                $current->next = $current->next->next;
                $this->size--;
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * Search for a value in the list
     *
     * @param mixed $data The data to search for
     * @return bool True if found, false otherwise
     */
    public function search($data): bool
    {
        $current = $this->head;
        while ($current !== null) {
            $comparison = $this->compare($current->data, $data);
            if ($comparison === 0) {
                return true;
            }
            // Since the list is sorted, we can stop early
            if ($comparison > 0) {
                return false;
            }
            $current = $current->next;
        }
        return false;
    }

    /**
     * Get the size of the list
     *
     * @return int The number of elements in the list
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Check if the list is empty
     *
     * @return bool True if the list is empty, false otherwise
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * Get the first element in the list
     *
     * @return mixed|null The first element or null if the list is empty
     */
    public function first()
    {
        return $this->head !== null ? $this->head->data : null;
    }

    /**
     * Get the last element in the list
     *
     * @return mixed|null The last element or null if the list is empty
     */
    public function last()
    {
        if ($this->head === null) {
            return null;
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;
        }
        return $current->data;
    }

    /**
     * Clear all elements from the list
     *
     * @return void
     */
    public function clear(): void
    {
        $this->head = null;
        $this->size = 0;
    }

    /**
     * Convert the list to an array
     *
     * @return array An array of all elements in the list
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->next;
        }
        return $result;
    }

    /**
     * Get an iterator for the list
     *
     * @return \Generator A generator that yields each element
     */
    public function getIterator(): \Generator
    {
        $current = $this->head;
        while ($current !== null) {
            yield $current->data;
            $current = $current->next;
        }
    }

    /**
     * Compare two values using the comparator or default comparison
     *
     * @param mixed $a First value
     * @param mixed $b Second value
     * @return int Negative if $a < $b, 0 if equal, positive if $a > $b
     */
    private function compare($a, $b): int
    {
        if ($this->comparator !== null) {
            return ($this->comparator)($a, $b);
        }

        // Default comparison
        if ($a < $b) {
            return -1;
        } elseif ($a > $b) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Magic method to convert the list to a string
     *
     * @return string String representation of the list
     */
    public function __toString(): string
    {
        return '[' . implode(', ', $this->toArray()) . ']';
    }
}
