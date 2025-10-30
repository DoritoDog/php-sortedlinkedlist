<?php

namespace SortedLinkedList\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SortedLinkedList\Node;
use SortedLinkedList\SortedLinkedList;

class TypeEnforcementTest extends TestCase
{
    public function testNodeAcceptsInteger(): void
    {
        $node = new Node(42);
        $this->assertEquals(42, $node->getData());
    }

    public function testNodeAcceptsString(): void
    {
        $node = new Node("hello");
        $this->assertEquals("hello", $node->getData());
    }

    public function testNodeRejectsFloat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, double given');

        new Node(3.14);
    }

    public function testNodeRejectsArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, array given');

        new Node([1, 2, 3]);
    }

    public function testNodeRejectsBoolean(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, boolean given');

        new Node(true);
    }

    public function testNodeRejectsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, NULL given');

        new Node(null);
    }

    public function testNodeRejectsObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, object given');

        new Node((object) ['key' => 'value']);
    }

    public function testSetDataRejectsInvalidType(): void
    {
        $node = new Node(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Node data must be int or string, array given');

        $node->setData([1, 2, 3]);
    }

    public function testListAcceptsOnlyIntegers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(3);
        $list->insert(7);

        $this->assertEquals([3, 5, 7], $list->toArray());
    }

    public function testListAcceptsOnlyStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("banana");
        $list->insert("apple");
        $list->insert("cherry");

        $this->assertEquals(["apple", "banana", "cherry"], $list->toArray());
    }

    public function testListRejectsMixedIntegerAndString(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot insert string into list of integer');

        $list->insert("hello");
    }

    public function testListRejectsMixedStringAndInteger(): void
    {
        $list = new SortedLinkedList();
        $list->insert("hello");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot insert integer into list of string');

        $list->insert(42);
    }

    public function testListRejectsFloatInIntegerList(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot insert double into list of integer. All elements must be of the same type.');

        $list->insert(3.14);
    }

    public function testListRejectsArrayInStringList(): void
    {
        $list = new SortedLinkedList();
        $list->insert("hello");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot insert array into list of string. All elements must be of the same type.');

        $list->insert([1, 2, 3]);
    }

    public function testClearResetsTypeConstraint(): void
    {
        $list = new SortedLinkedList();

        // First, insert integers
        $list->insert(5);
        $list->insert(3);

        // Clear the list
        $list->clear();

        // Now we should be able to insert strings
        $list->insert("hello");
        $list->insert("world");

        $this->assertEquals(["hello", "world"], $list->toArray());
    }

    public function testEmptyListAcceptsFirstElementOfAnyValidType(): void
    {
        $intList = new SortedLinkedList();
        $intList->insert(42);
        $this->assertEquals([42], $intList->toArray());

        $stringList = new SortedLinkedList();
        $stringList->insert("test");
        $this->assertEquals(["test"], $stringList->toArray());
    }

    public function testTypeEnforcementWithCustomComparator(): void
    {
        // Reverse order comparator for integers
        $list = new SortedLinkedList(function ($a, $b) {
            return $b - $a;
        });

        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        $this->assertEquals([10, 5, 3], $list->toArray());

        // Should still reject strings
        $this->expectException(InvalidArgumentException::class);
        $list->insert("hello");
    }
}
