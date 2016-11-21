<?php

namespace ChadicusTest;

use Chadicus\Books\BookEntity;

/**
 * @coversDefaultClass \Chadicus\Books\BookEntity
 * @covers ::__construct
 */
final class BookEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getId().
     *
     * @test
     * @covers ::getId
     *
     * @return void
     */
    public function getId()
    {
		$id = uniqid();
        $entity = $this->getEntity($id);
		$this->assertSame($id, $entity->getId());
    }

    /**
     * Verify basic behavior of getAuthor().
     *
     * @test
     * @covers ::getAuthor
     *
     * @return void
     */
    public function getAuthor()
    {
		$this->assertSame('an author', $this->getEntity()->getAuthor());
    }

    /**
     * Verify basic behavior of getTitle().
     *
     * @test
     * @covers ::getTitle
     *
     * @return void
     */
    public function getTitle()
    {
		$this->assertSame('a title', $this->getEntity()->getTitle());
    }

    /**
     * Verify basic behavior of getGenre().
     *
     * @test
     * @covers ::getGenre
     *
     * @return void
     */
    public function getGenre()
    {
		$this->assertSame('a genre', $this->getEntity()->getGenre());
    }

    /**
     * Verify basic behavior of getPrice().
     *
     * @test
     * @covers ::getPrice
     *
     * @return void
     */
    public function getPrice()
    {
		$this->assertSame(1.0, $this->getEntity()->getPrice());
    }

    /**
     * Verify basic behavior of getPublished().
     *
     * @test
     * @covers ::getPublished
     *
     * @return void
     */
    public function getPublished()
    {
        $now = new \DateTimeImmutable();
		$this->assertSame($now, $this->getEntity('', '', '', '', 1.0, $now)->getPublished());
    }

    /**
     * Verify basic behavior of getDescription().
     *
     * @test
     * @covers ::getDescription
     *
     * @return void
     */
    public function getDescription()
    {
		$this->assertSame('a description', $this->getEntity()->getDescription());
    }

    /**
     * Helper method to construct a book entity for testing.
     *
     * @param string                  $id          The id of the book.
     * @param string                  $author      The author of the book.
     * @param string                  $title       The title of the book.
     * @param string                  $genre       The genre of the book.
     * @param float                   $price       The price of the book.
     * @param \DateTimeImmutable|null $published   The published date of the book.
     * @param string                  $description The description of the book.
     *
     * @return BookEntity
     */
    private function getEntity(
        string $id = 'an id',
        string $author = 'an author',
        string $title = 'a title',
        string $genre = 'a genre',
        float $price = 1.0,
        \DateTimeImmutable $published = null,
        string $description = 'a description'
	) {
		return new BookEntity(
			$id,
            $author,
            $title,
            $genre,
            $price,
            $published ?: new \DateTimeImmutable(),
            $description
        );
	}
}
