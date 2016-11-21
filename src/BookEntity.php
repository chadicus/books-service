<?php

namespace Chadicus\Books;

/**
 * Immutable book entity object
 */
final class BookEntity implements \JsonSerializable
{
    /**
     * The id of the book.
     *
     * @var string
     */
    private $id;

    /**
     * The author of the book.
     *
     * @var string
     */
    private $author;

    /**
     * The title of the book.
     *
     * @var string
     */
    private $title;

    /**
     * The genre of the book.
     *
     * @var string
     */
    private $genre;

    /**
     * The price of the book.
     *
     * @var float
     */
    private $price;

    /**
     * The published of the book.
     *
     * @var \DateTimeImmutable
     */
    private $published;

    /**
     * The description of the book.
     *
     * @var string
     */
    private $description;

    /**
     * Construct a new Book instance.
     *
     * @param string             $id          The id of the book.
     * @param string             $author      The author of the book.
     * @param string             $title       The title of the book.
     * @param string             $genre       The genre of the book.
     * @param float              $price       The price of the book.
     * @param \DateTimeImmutable $published   The published date of the book.
     * @param string             $description The description of the book.
     */
    public function __construct(
        string $id,
        string $author,
        string $title,
        string $genre,
        float $price,
        \DateTimeImmutable $published,
        string $description
    ) {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->genre = $genre;
        $this->price = $price;
        $this->published = $published;
        $this->description = $description;
    }

    /**
     * Returns the id of the book.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the author of the book.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the title of the book.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the genre of the book.
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Returns the price of the book.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Returns the published of the book.
     *
     * @return \DateTimeImmutable
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Returns the description of the book.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the data for this object which can be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'genre' => $this->genre,
            'price' => $this->price,
            'published' => $this->published->getTimestamp(),
            'description' => $this->description,
        ];
    }
}
