<?php

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\SortedLinkedList;

class EdgeCasesTest extends TestCase
{
    public function testInsertZero(): void
    {
        $list = new SortedLinkedList();
        $list->insert(0);
        $list->insert(1);
        $list->insert(-1);

        $this->assertEquals([-1, 0, 1], $list->toArray());
    }

    public function testInsertEmptyString(): void
    {
        $list = new SortedLinkedList();
        $list->insert("");
        $list->insert("a");
        $list->insert("");

        $this->assertEquals(["", "", "a"], $list->toArray());
    }

    public function testInsertLargeNumbers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(PHP_INT_MAX);
        $list->insert(PHP_INT_MAX - 1);
        $list->insert(0);

        $this->assertEquals([0, PHP_INT_MAX - 1, PHP_INT_MAX], $list->toArray());
    }

    public function testInsertManyElements(): void
    {
        $list = new SortedLinkedList();
        $count = 1000;

        for ($i = $count; $i > 0; $i--) {
            $list->insert($i);
        }

        $this->assertEquals($count, $list->size());
        $this->assertEquals(1, $list->first());
        $this->assertEquals($count, $list->last());

        // Verify sorted order
        $array = $list->toArray();
        for ($i = 0; $i < $count - 1; $i++) {
            $this->assertLessThan($array[$i + 1], $array[$i]);
        }
    }

    public function testDeleteAllElements(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $list->delete(1);
        $list->delete(2);
        $list->delete(3);

        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->size());
    }

    public function testAlternateInsertAndDelete(): void
    {
        $list = new SortedLinkedList();

        $list->insert(5);
        $this->assertEquals(1, $list->size());

        $list->delete(5);
        $this->assertEquals(0, $list->size());

        $list->insert(10);
        $this->assertEquals(1, $list->size());

        $list->insert(3);
        $this->assertEquals([3, 10], $list->toArray());
    }

    public function testSearchAfterDelete(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->search(2));

        $list->delete(2);

        $this->assertFalse($list->search(2));
        $this->assertTrue($list->search(1));
        $this->assertTrue($list->search(3));
    }

    public function testClearAndReuse(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);

        $list->clear();

        $list->insert(5);
        $list->insert(3);

        $this->assertEquals([3, 5], $list->toArray());
        $this->assertEquals(2, $list->size());
    }

    public function testFirstAndLastSingleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(42);

        $this->assertEquals(42, $list->first());
        $this->assertEquals(42, $list->last());
    }

    public function testToArrayEmptyList(): void
    {
        $list = new SortedLinkedList();

        $this->assertEquals([], $list->toArray());
    }

    public function testGetIteratorEmptyList(): void
    {
        $list = new SortedLinkedList();

        $values = [];
        foreach ($list->getIterator() as $value) {
            $values[] = $value;
        }

        $this->assertEquals([], $values);
    }

    public function testInsertSameValueMultipleTimes(): void
    {
        $list = new SortedLinkedList();

        for ($i = 0; $i < 10; $i++) {
            $list->insert(5);
        }

        $this->assertEquals(10, $list->size());
        $this->assertEquals(array_fill(0, 10, 5), $list->toArray());
    }

    public function testDeleteWhenMultipleDuplicatesExist(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(5);
        $list->insert(5);
        $list->insert(5);

        $list->delete(5);

        $this->assertEquals(3, $list->size());
        $this->assertTrue($list->search(5));
    }

    public function testInsertAtBeginning(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(2);
        $list->insert(1);

        $this->assertEquals([1, 2, 3], $list->toArray());
        $this->assertEquals(1, $list->first());
    }

    public function testInsertAtEnd(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertEquals([1, 2, 3], $list->toArray());
        $this->assertEquals(3, $list->last());
    }

    public function testSearchEarlyTermination(): void
    {
        // This tests that search stops early when it encounters a larger value
        $list = new SortedLinkedList();

        // Insert many elements
        for ($i = 0; $i < 100; $i++) {
            $list->insert($i * 10);
        }

        // Search for a value that doesn't exist but is less than the largest
        // The search should terminate early
        $this->assertFalse($list->search(5));
    }

    public function testSpecialCharactersInStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("hello");
        $list->insert("hello!");
        $list->insert("hello?");
        $list->insert("hello ");

        $this->assertEquals(4, $list->size());
        // Verifying they're all stored
        $this->assertTrue($list->search("hello"));
        $this->assertTrue($list->search("hello!"));
    }

    public function testUnicodeStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("café");
        $list->insert("apple");
        $list->insert("über");

        $this->assertEquals(3, $list->size());
        $this->assertTrue($list->search("café"));
        $this->assertTrue($list->search("über"));
    }
}
