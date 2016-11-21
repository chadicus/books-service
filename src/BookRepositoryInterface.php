<?php

namespace Chadicus\Books;

/**
 * Contract for a book repository instance.
 */
interface BookRepositoryInterface
{
    /**
     * Returns a book entity with the given id.
     *
     * @param string $id The id of the book to find.
     *
     * @return BookEntity|null The book entity or null if not found.
     */
    public function find($id);

    /**
     * Returns all books matching the given criteria.
     *
     * @param BookCriteria $criteria The criteria for the search.
     *
     * @return BookEntity[] Array of book entities.
     */
    public function findAll(array $criteria);

    /**
     * Creates a new book entity with the given data.
     *
     * @param array $data The data for the new entity.
     *
     * @return string $id The id of the created entity.
     */
    public function create(array $data);

    /**
     * Updates an existing book entity with the given data.
     *
     * @param string $id   The id of the entity to update.
     * @param array  $data The data to update for the entity.
     *
     * @return void
     */
    public function update($id, array $data);

    /**
     * Deletes an existing book entity with the given id.
     *
     * @param string $id   The id of the entity to delete.
     *
     * @return void
     */
    public function delete($id);
}
