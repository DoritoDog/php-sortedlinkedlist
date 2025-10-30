<?php

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\SortedLinkedList;

class CustomComparatorTest extends TestCase
{
    public function testReverseOrderComparator(): void
    {
        $list = new SortedLinkedList(function($a, $b) {
            return $b - $a; // Reverse order
        });

        $list->insert(1);
        $list->insert(5);
        $list->insert(3);

        $this->assertEquals([5, 3, 1], $list->toArray());
    }

    public function testCaseInsensitiveStringComparator(): void
    {
        $list = new SortedLinkedList(function($a, $b) {
            return strcasecmp($a, $b);
        });

        $list->insert("Zebra");
        $list->insert("apple");
        $list->insert("Banana");
        $list->insert("cherry");

        $this->assertEquals(["apple", "Banana", "cherry", "Zebra"], $list->toArray());
    }

    public function testSearchWithCustomComparator(): void
    {
        $list = new SortedLinkedList(function($a, $b) {
            return $b - $a; // Reverse order
        });

        $list->insert(1);
        $list->insert(5);
        $list->insert(3);

        $this->assertTrue($list->search(5));
        $this->assertTrue($list->search(3));
        $this->assertFalse($list->search(4));
    }

    public function testDeleteWithCustomComparator(): void
    {
        $list = new SortedLinkedList(function($a, $b) {
            return strlen($a) - strlen($b);
        });

        $list->insert("a");
        $list->insert("abc");
        $list->insert("ab");

        $result = $list->delete("ab");

        $this->assertTrue($result);
        $this->assertEquals(["a", "abc"], $list->toArray());
    }
}
