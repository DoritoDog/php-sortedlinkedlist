<?php

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\SortedLinkedList;

class SortedLinkedListTest extends TestCase
{
    public function testEmptyListCreation(): void
    {
        $list = new SortedLinkedList();

        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->size());
        $this->assertNull($list->first());
        $this->assertNull($list->last());
    }

    public function testInsertSingleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $this->assertFalse($list->isEmpty());
        $this->assertEquals(1, $list->size());
        $this->assertEquals(5, $list->first());
        $this->assertEquals(5, $list->last());
    }

    public function testInsertMultipleElementsInOrder(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertEquals(3, $list->size());
        $this->assertEquals([1, 2, 3], $list->toArray());
    }

    public function testInsertMultipleElementsOutOfOrder(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);
        $list->insert(1);
        $list->insert(9);

        $this->assertEquals(5, $list->size());
        $this->assertEquals([1, 2, 5, 8, 9], $list->toArray());
        $this->assertEquals(1, $list->first());
        $this->assertEquals(9, $list->last());
    }

    public function testInsertDuplicates(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(3);
        $list->insert(5);
        $list->insert(3);

        $this->assertEquals(4, $list->size());
        $this->assertEquals([3, 3, 5, 5], $list->toArray());
    }

    public function testSearchExistingElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertTrue($list->search(5));
        $this->assertTrue($list->search(2));
        $this->assertTrue($list->search(8));
    }

    public function testSearchNonExistingElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertFalse($list->search(3));
        $this->assertFalse($list->search(10));
        $this->assertFalse($list->search(0));
    }

    public function testSearchInEmptyList(): void
    {
        $list = new SortedLinkedList();

        $this->assertFalse($list->search(5));
    }

    public function testDeleteFirstElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $result = $list->delete(1);

        $this->assertTrue($result);
        $this->assertEquals(2, $list->size());
        $this->assertEquals([2, 3], $list->toArray());
    }

    public function testDeleteMiddleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $result = $list->delete(2);

        $this->assertTrue($result);
        $this->assertEquals(2, $list->size());
        $this->assertEquals([1, 3], $list->toArray());
    }

    public function testDeleteLastElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $result = $list->delete(3);

        $this->assertTrue($result);
        $this->assertEquals(2, $list->size());
        $this->assertEquals([1, 2], $list->toArray());
    }

    public function testDeleteNonExistingElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $result = $list->delete(5);

        $this->assertFalse($result);
        $this->assertEquals(3, $list->size());
    }

    public function testDeleteFromEmptyList(): void
    {
        $list = new SortedLinkedList();

        $result = $list->delete(5);

        $this->assertFalse($result);
        $this->assertEquals(0, $list->size());
    }

    public function testDeleteOnlyElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $result = $list->delete(5);

        $this->assertTrue($result);
        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->size());
    }

    public function testDeleteFirstOccurrenceOfDuplicate(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(5);
        $list->insert(5);

        $result = $list->delete(5);

        $this->assertTrue($result);
        $this->assertEquals(2, $list->size());
        $this->assertEquals([5, 5], $list->toArray());
    }

    public function testClearList(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $list->clear();

        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->size());
        $this->assertEquals([], $list->toArray());
    }

    public function testToString(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $this->assertEquals('[1, 2, 3]', (string) $list);
    }

    public function testToStringEmptyList(): void
    {
        $list = new SortedLinkedList();

        $this->assertEquals('[]', (string) $list);
    }

    public function testGetIterator(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $values = [];
        foreach ($list->getIterator() as $value) {
            $values[] = $value;
        }

        $this->assertEquals([1, 2, 3], $values);
    }

    public function testInsertStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("banana");
        $list->insert("apple");
        $list->insert("cherry");

        $this->assertEquals(["apple", "banana", "cherry"], $list->toArray());
    }

    public function testInsertNegativeNumbers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(-3);
        $list->insert(0);
        $list->insert(-10);

        $this->assertEquals([-10, -3, 0, 5], $list->toArray());
    }
}
