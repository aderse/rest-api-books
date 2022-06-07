<?php
namespace Src\Controller;

use Src\TableGateways\BookGateway;

class BookController {

    private $db;
    private $requestMethod;
    private $bookId;
    private $bookGateway;

    public function __construct($db, $requestMethod, $bookId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->bookId = $bookId;

        $this->bookGateway = new BookGateway($this->db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->bookId) {
                    $response = $this->getBook($this->bookId);
                } else {
                    $response = $this->getAllBooks();
                };
                break;
            case 'POST':
                $response = $this->createBookFromRequest();
                break;
            case 'PUT':
                $response = $this->updateBookFromRequest($this->bookId);
                break;
            case 'DELETE':
                $response = $this->deleteBook($this->bookId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllBooks(): array
    {
        $result = $this->bookGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getBook($id): array
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createBookFromRequest(): array
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateBook($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->bookGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateBookFromRequest($id): array
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateBook($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->bookGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteBook($id): array
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->bookGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateBook($input): bool
    {
        if (! isset($input['title'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}