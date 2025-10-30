# PHP Sorted Linked List

A simple and efficient sorted linked list implementation in PHP that maintains elements in sorted order automatically.

## Features

- Automatic sorting on insertion
- Custom comparator support
- Common operations: insert, delete, search
- Utility methods: size, isEmpty, first, last, clear
- Array conversion and iteration support
- O(n) insertion and deletion
- O(n) search with early termination

## Installation

```bash
composer require kareembelgharbi/php-sortedlinkedlist
```

## Usage

### Basic Usage with Numbers

```php
<?php

use SortedLinkedList\SortedLinkedList;

// Create a new sorted linked list
$list = new SortedLinkedList();

// Insert elements (they will be automatically sorted)
$list->insert(5);
$list->insert(2);
$list->insert(8);
$list->insert(1);
$list->insert(9);

// Display the list
echo $list; // Output: [1, 2, 5, 8, 9]

// Get the size
echo $list->size(); // Output: 5

// Search for an element
var_dump($list->search(5)); // Output: bool(true)
var_dump($list->search(3)); // Output: bool(false)

// Get first and last elements
echo $list->first(); // Output: 1
echo $list->last();  // Output: 9

// Delete an element
$list->delete(5);
echo $list; // Output: [1, 2, 8, 9]
```

### Using a Custom Comparator

You can provide a custom comparison function for complex sorting logic:

```php
<?php

use SortedLinkedList\SortedLinkedList;

// Sort strings by length (shorter strings first)
$list = new SortedLinkedList(function($a, $b) {
    $lenDiff = strlen($a) - strlen($b);
    if ($lenDiff !== 0) {
        return $lenDiff;
    }
    return strcmp($a, $b); // If same length, sort alphabetically
});

$list->insert("apple");
$list->insert("pie");
$list->insert("a");
$list->insert("banana");

echo $list; // Output: [a, pie, apple, banana]
```

### Sorting Objects

```php
<?php

use SortedLinkedList\SortedLinkedList;

class Person {
    public $name;
    public $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

// Sort people by age
$list = new SortedLinkedList(function($a, $b) {
    return $a->age - $b->age;
});

$list->insert(new Person("Alice", 30));
$list->insert(new Person("Bob", 25));
$list->insert(new Person("Charlie", 35));

foreach ($list->getIterator() as $person) {
    echo "{$person->name}: {$person->age}\n";
}
// Output:
// Bob: 25
// Alice: 30
// Charlie: 35
```

### Iteration

```php
<?php

use SortedLinkedList\SortedLinkedList;

$list = new SortedLinkedList();
$list->insert(3);
$list->insert(1);
$list->insert(4);

// Using foreach with generator
foreach ($list->getIterator() as $value) {
    echo $value . " ";
}
// Output: 1 3 4

// Convert to array
$array = $list->toArray();
print_r($array); // Output: Array ( [0] => 1 [1] => 3 [2] => 4 )
```

## API Reference

### Constructor

- `__construct(?callable $comparator = null)`: Create a new sorted linked list with an optional custom comparator

### Methods

- `insert($data): void`: Insert a value into the list (maintains sort order)
- `delete($data): bool`: Delete the first occurrence of a value
- `search($data): bool`: Search for a value in the list
- `size(): int`: Get the number of elements
- `isEmpty(): bool`: Check if the list is empty
- `first()`: Get the first element (smallest)
- `last()`: Get the last element (largest)
- `clear(): void`: Remove all elements
- `toArray(): array`: Convert the list to an array
- `getIterator(): \Generator`: Get an iterator for foreach loops

## Time Complexity

- Insert: O(n)
- Delete: O(n)
- Search: O(n) with early termination
- First: O(1)
- Last: O(n)
- Size: O(1)

## Requirements

- PHP >= 7.4

## Development with Docker

This project includes Docker support for easy development and testing.

### Prerequisites

- Docker
- Docker Compose
- Make (optional, for convenience commands)

### Quick Start

Build and start the containers:

```bash
make build
make up
```

Or without Make:

```bash
docker-compose build
docker-compose up -d
```

### Running Tests

Run the full test suite:

```bash
make test
```

Or without Make:

```bash
docker-compose run --rm test
```

### Available Make Commands

```bash
make help           # Show all available commands
make build          # Build Docker images
make up             # Start containers
make down           # Stop containers
make restart        # Restart containers
make shell          # Open shell in PHP container
make test           # Run PHPUnit tests
make coverage       # Generate HTML coverage report
make coverage-text  # Show coverage in terminal
make install        # Install composer dependencies
make update         # Update composer dependencies
make clean          # Clean up containers and generated files
make rebuild        # Clean and rebuild everything
make logs           # Show container logs
```

### Manual Docker Commands

Run tests:
```bash
docker-compose run --rm test
```

Generate coverage report:
```bash
docker-compose run --rm test vendor/bin/phpunit --coverage-html coverage
```

Access PHP shell:
```bash
docker-compose exec php sh
```

Install dependencies:
```bash
docker-compose run --rm php composer install
```

Run a specific test:
```bash
docker-compose run --rm test vendor/bin/phpunit --filter testInsertMultipleElements
```

### Docker Architecture

- **Dockerfile**: Production-ready image with minimal dependencies
- **Dockerfile.dev**: Development image with PHPUnit and Xdebug for testing and coverage
- **docker-compose.yml**: Service orchestration for development environment

## License

MIT
