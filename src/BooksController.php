<?php

namespace Chadicus\Books;

use Chadicus\Books\BookCriteria;
use DominionEnterprises\Util\Arrays;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Stream;

final class BooksController
{
    /**
     * A BookRepositoryInterface instance.
     *
     * @var BookRepositoryInterface
     */
    private $repository;

    /**
     * Construct a new instance of BooksController.
     *
     * @param ContainerInterface $container The DI container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get('books-respository');
        if (!is_a($this->repository, '\\Chadicus\\Books\\BookRepositoryInterface')) {
            throw new \InvalidArgumentException('BookRepositoryInterface not found in $contrainer');
        }
    }

    /**
     * Handle GET /books requests.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $queryParams = $request->getQueryParams();
        $limit = Arrays::get($queryParams, 'limit', 5);
        $offset = Arrays::get($queryParams, 'offset', 0);

        $books = $this->repository->findAll($args);
        $total = count($books);
        $result = [
            'offset' => $offset,
            'limit' => min($limit, $total),
            'total' => $total,
            'books' => array_slice($books, $offset, $limit),
        ];

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, json_encode($result));
        rewind($stream);

        return $response->withHeader('Content-Type', 'application/json')->withBody(new Stream($stream));
    }

    /**
     * Handle GET /books/:id requests.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $id = Arrays::get($args, 'id');

        $book = $this->repository->find($id);
        if ($book === null) {
            return $response->withStatus(404);
        }

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, json_encode($book));
        rewind($stream);

        return $response->withHeader('Content-Type', 'application/json')->withBody(new Stream($stream));
    }

    /**
     * Handle POST /books requests.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $book = json_decode((string)$request->getBody(), true);
        $id = $this->repository->create($book);
        return $response->withStatus(201)->withHeader('Location', "/books/{$id}");
    }

    /**
     * Handle PUT /books/:id requests.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function put(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $id = Arrays::get($args, 'id');
        $book = json_decode((string)$request->getBody(), true);
        $this->repository->update($id, $book);
        return $response;
    }

    /**
     * Handle DELETE /books/:id requests.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $id = Arrays::get($args, 'id');
        $this->repository->delete($id);
        return $response->withStatus(204);
    }
}
