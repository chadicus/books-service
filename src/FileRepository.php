<?php

namespace Chadicus\Books;

use DominionEnterprises\Util;

/**
 * File storage implementation of a BookRepository.
 */
final class FileRepository implements BookRepositoryInterface
{
    /**
     * The file to which the books are written.
     *
     * @var string
     */
    private $path;

    /**
     * Array of book objects.
     *
     * @var array
     */
    private $books = [];

    /**
     * Construct a new instance of FileRepository.
     *
     * @var string $path The file to which the books are written.
     */
    public function __construct($path = null)
    {
        $this->path = $path;
        if (!file_exists($this->path)) {
			return;
		}

		$array = (array)json_decode(file_get_contents($this->path), true);
		foreach ($array as $data) {
			$book = self::make($data);
			$this->books[$book->getId()] = $book;
        }
    }

    /**
     * Returns a book entity with the given id.
     *
     * @param string $id The id of the book to find.
     *
     * @return BookEntity|null The book entity or null if not found.
     */
    public function find($id)
    {
        return Util\Arrays::get($this->books, $id);
    }

    /**
     * Returns all books matching the given criteria.
     *
     * @param BookCriteria $criteria The criteria for the search.
     *
     * @return BookEntity[] Array of book entities.
     */
    public function findAll(array $criteria)
    {
        $searchable = json_decode(json_encode($this->books), true);
        $books = [];
        foreach (Util\Arrays::where($searchable, $criteria) as $book) {
            $books[] = self::make($book);
        }

        return $books;
    }

    /**
     * Creates a new book entity with the given data.
     *
     * @param array $data The data for the new entity.
     *
     * @return string $id The id of the created entity.
     */
    public function create(array $data)
    {
        $id = uniqid();
        $this->books[$id] = self::make(['id' => $id] + $data);
		return "{$id}";
	}

    /**
     * Updates an existing book entity with the given data.
     *
     * @param string $id   The id of the entity to update.
     * @param array  $data The data to update for the entity.
     *
     * @return boolean
     */
    public function update($id, array $data)
    {
        $book = Util\Arrays::get($this->books, $id);
		if ($book === null) {
			return false;
		}

		$this->books[$id] = self::make($data + $book->jsonSerialize());
    }

    /**
     * Deletes an existing book entity with the given id.
     *
     * @param string $id The id of the entity to delete.
     *
     * @return void
     */
    public function delete($id)
    {
        unset($this->books[$id]);
    }

    /**
     * Persist the books to the file path
     *
     * @return void
     */
    public function __destruct()
    {
        file_put_contents(
            $this->path,
            json_encode($this->books)
        );
    }

	/**
     * Helper method to construct a BookEntity.
     *
     * @param array $data The data for the entity.
     *
     * @return BookEntity
     */
    private static function make(array $data)
	{
		$published = Util\Arrays::get($data, 'published');
		if (!is_a($published, '\DateTimeImmutableInterface')) {
			if (ctype_digit($published)) {
				$published = "@{$published}";
			}

			$published = new \DateTimeImmutable($published);
		}

		return new BookEntity(
			Util\Arrays::get($data, 'id'),
			Util\Arrays::get($data, 'author'),
			Util\Arrays::get($data, 'title'),
			Util\Arrays::get($data, 'genre'),
			(float)Util\Arrays::get($data, 'price'),
			$published,
			Util\Arrays::get($data, 'description')
        );
	}
}
