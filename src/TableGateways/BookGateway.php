<?php
namespace Src\TableGateways;

use mysql_xdevapi\Result;

class BookGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, title, author, pages, start_date, end_date
            FROM
                book;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, title, author, pages, start_date, end_date
            FROM
                book
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO book 
                (title, author, pages, start_date, end_date)
            VALUES
                (:title, :author, :pages, :start_date, :end_date);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'title'  => $input['title'],
                'author' => $input['author'] ?? null,
                'pages' => $input['pages'] ?? null,
                'start_date' => $input['start_date'] ?? null,
                'end_date' => $input['end_date'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, array $input)
    {
        $statement = "
            UPDATE book
            SET 
                title = :title,
                author  = :author,
                pages = :pages,
                start_date = :start_date,
                end_date = :end_date
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'title'  => $input['title'],
                'author' => $input['author'] ?? null,
                'pages' => $input['pages'] ?? null,
                'start_date' => $input['start_date'] ?? null,
                'end_date' => $input['end_date'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM book
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}