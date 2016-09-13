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
    private $repository;

    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get('books-respository');
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $queryParams = $request->getQueryParams();
        $limit = Arrays::get($queryParams, 'limit', 5);
        $offset = Arrays::get($queryParams, 'offset', 0);

        $books = $this->repository->findAll(new BookCriteria());
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
}
