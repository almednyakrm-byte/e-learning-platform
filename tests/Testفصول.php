<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;
use PDOStatement;

class Testفصول extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->statement);
    }

    public function testGetفصول(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode([]));

        $فصول = new فصول($this->pdo);
        $فصول->get($request, $response);
    }

    public function testPostفصول(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request->method('getParsedBody')
            ->willReturn(['name' => 'new فصول']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['name' => 'new فصول']);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'فصول created successfully']));

        $فصول = new فصول($this->pdo);
        $فصول->post($request, $response);
    }

    public function testPutفصول(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'updated فصول']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['id' => 1, 'name' => 'updated فصول']);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'فصول updated successfully']));

        $فصول = new فصول($this->pdo);
        $فصول->put($request, $response);
    }

    public function testDeleteفصول(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request->method('getAttribute')
            ->willReturn(1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'فصول deleted successfully']));

        $فصول = new فصول($this->pdo);
        $فصول->delete($request, $response);
    }
}