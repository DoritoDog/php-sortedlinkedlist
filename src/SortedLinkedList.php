<?php

namespace SortedLinkedList;

use InvalidArgumentException;

/**
 * SortedLinkedList maintains elements in sorted order
 */
class SortedLinkedList
{
    /**
     * The head of the linked list
     */
    private Node|null $head;

    /**
     * The number of elements in the list
     */
    private int $size;

    /**
     * @var callable|null Custom comparison function
     */
    private $comparator;

    /**
     * The type of data stored in the list ('integer' or 'string')
     */
    private string|null $dataType = null;

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
        $this->dataType = null;
    }

    /**
     * Insert a value into the sorted linked list
     *
     * @param int|string $data The data to insert
     * @return void
     * @throws InvalidArgumentException If data type doesn't match the list's type
     */
    public function insert($data): void
    {
        // Enforce type consistency across the list
        $currentType = gettype($data);

        if ($this->dataType === null) {
            // First insertion - set the type
            $this->dataType = $currentType;
        } elseif ($this->dataType !== $currentType) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot insert %s into list of %s. All elements must be of the same type.',
                    $currentType,
                    $this->dataType
                )
            );
        }

        $newNode = new Node($data);

        // If list is empty or new node should be at the head
        if ($this->head === null || $this->compare($data, $this->head->getData()) < 0) {
            $newNode->setNext($this->head);
            $this->head = $newNode;
            $this->size++;
            return;
        }

        // Find the correct position to insert
        $current = $this->head;
        while ($current->getNext() !== null && $this->compare($current->getNext()->getData(), $data) < 0) {
            $current = $current->getNext();
        }

        // Insert the new node
        $newNode->setNext($current->getNext());
        $current->setNext($newNode);
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
        if ($this->compare($this->head->getData(), $data) === 0) {
            $this->head = $this->head->getNext();
            $this->size--;
            return true;
        }

        // Search for the node to delete
        $current = $this->head;
        while ($current->getNext() !== null) {
            if ($this->compare($current->getNext()->getData(), $data) === 0) {
                $current->setNext($current->getNext()->getNext());
                $this->size--;
                return true;
            }
            $current = $current->getNext();
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
            $comparison = $this->compare($current->getData(), $data);
            if ($comparison === 0) {
                return true;
            }
            // Since the list is sorted, we can stop early
            if ($comparison > 0) {
                return false;
            }
            $current = $current->getNext();
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
        return $this->head !== null ? $this->head->getData() : null;
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
        while ($current->getNext() !== null) {
            $current = $current->getNext();
        }
        return $current->getData();
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
        $this->dataType = null;
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
            $result[] = $current->getData();
            $current = $current->getNext();
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
            yield $current->getData();
            $current = $current->getNext();
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
