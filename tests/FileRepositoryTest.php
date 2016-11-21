<?php

namespace ChadicusTest\Books;

use Chadicus\Books\FileRepository;

/**
 * @coversDefaultClass \Chadicus\Books\FileRepository
 * @covers ::__construct
 * @covers ::__destruct
 * @covers ::<private>
 */
final class FileRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $dataFile;

    public function setUp()
    {
        $this->dataFile = tempnam(sys_get_temp_dir(), 'books-tests');
        copy(__DIR__ . '/_files/books.json', $this->dataFile);
    }

    public function tearDown()
    {
        unlink($this->dataFile);
    }

    /**
     * @test
     * @covers ::find
     *
     * @return void
     */
    public function find()
    {
        $repository = new FileRepository($this->dataFile);
        $this->assertSame('58339e95d5200', $repository->find('58339e95d5200')->getId());
    }

    /**
     * @test
     * @covers ::findAll
     *
     * @return void
     */
    public function findAll()
    {
        $repository = new FileRepository($this->dataFile);
        $books = $repository->findAll(['genre' => 'Horror']);
        $this->assertSame(
            json_encode(
                [
					[
						'id' => '58339e95d5378',
						'author' => 'Knorr, Stefan',
						'title' => 'Creepy Crawlies',
						'genre' => 'Horror',
						'price' => 4.95,
						'published' => 976078800,
						'description' => 'An anthology of horror stories about roaches, centipedes, scorpions  and other insects.',
					],
                ]
            ),
            json_encode($books)
        );

        $this->assertSame('58339e95d5200', $repository->find('58339e95d5200')->getId());
    }

    /**
     * @test
     * @covers ::create
     *
     * @return void
     */
    public function create()
    {
        $dataFile = tempnam(sys_get_temp_dir(), 'books-tests');
		try {
        	$repository = new FileRepository($dataFile);
			$this->assertCount(0, $repository->findAll([]));
        	$id = $repository->create(
				[
					'author' => 'An author',
					'title' => 'a title',
					'genre' => 'a genre',
					'price' => 1.1,
					'published' => time(),
					'description' => 'a description',
				]
			);

			$books = $repository->findAll([]);
			$this->assertCount(1, $books);
			$this->assertSame($id, $books[0]->getId());
		} finally {
			unlink($dataFile);
		}
    }

    /**
     * @test
     * @covers ::update
     *
     * @return void
     */
    public function update()
    {
		$id = '58339e95d5378';
    	$repository = new FileRepository($this->dataFile);
        $this->assertSame(
            json_encode(
                [
					'id' => '58339e95d5378',
					'author' => 'Knorr, Stefan',
					'title' => 'Creepy Crawlies',
					'genre' => 'Horror',
					'price' => 4.95,
					'published' => 976078800,
					'description' => 'An anthology of horror stories about roaches, centipedes, scorpions  and other insects.',
                ]
            ),
            json_encode($repository->find($id))
        );

		$now = time();

		$repository->update(
			$id,
			[
				'author' => 'An author',
				'title' => 'A title',
				'genre' => 'a genre',
				'price' => 1.1,
				'published' => $now,
				'description' => 'a new description',
			]
		);

        $this->assertSame(
            json_encode(
                [
					'id' => '58339e95d5378',
					'author' => 'An author',
					'title' => 'A title',
					'genre' => 'a genre',
					'price' => 1.1,
					'published' => $now,
					'description' => 'a new description',
                ]
            ),
            json_encode($repository->find($id))
        );
    }

    /**
     * @test
     * @covers ::delete
     *
     * @return void
     */
    public function delete()
    {
		$id = '58339e95d5378';
    	$repository = new FileRepository($this->dataFile);
		$repository->delete($id);
		$this->assertNull($repository->find($id));
    }
}
