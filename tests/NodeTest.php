<?php

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\Node;

class NodeTest extends TestCase
{
    public function testNodeCreation(): void
    {
        $node = new Node(42);

        $this->assertEquals(42, $node->getData());
        $this->assertNull($node->getNext());
    }

    public function testNodeWithStringData(): void
    {
        $node = new Node("hello");

        $this->assertEquals("hello", $node->getData());
        $this->assertNull($node->getNext());
    }

    public function testNodeLinking(): void
    {
        $node1 = new Node(1);
        $node2 = new Node(2);

        $node1->setNext($node2);

        $this->assertSame($node2, $node1->getNext());
        $this->assertEquals(2, $node1->getNext()->getData());
    }

    public function testNodeChaining(): void
    {
        $node1 = new Node(1);
        $node2 = new Node(2);
        $node3 = new Node(3);

        $node1->setNext($node2);
        $node2->setNext($node3);

        $this->assertEquals(2, $node1->getNext()?->getData());
        $this->assertEquals(3, $node1->getNext()?->getNext()?->getData());
        $this->assertNull($node1->getNext()?->getNext()?->getNext());
    }
}
