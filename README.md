# REST API - BOOKS

This is a simple REST API made with PHP that allows you to interact with the database to do basic CRUD against your database.

## Getting started

```text
git clone git@github.com:aderse/rest-api-books.git
```

## Setting up the Database 
### Creation

```sql
CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `author` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pages` int(6) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Population
```sql
INSERT INTO `book` (`id`, `title`, `author`, `pages`, `start_date`, `end_date`, `last_updated`) VALUES
(1, 'The 5 Second Rule', 'Mel Robbins', 238, '2022-01-01', '2022-01-31', '2022-06-07 01:34:03'),
(2, 'Atomic Habits', 'James Clear', 264, '2022-02-01', '2022-02-28', '2022-06-07 02:19:15'),
(3, 'Give and Take', 'Adam Grant', 268, '2022-03-01', '2022-03-31', '2022-06-07 09:21:56');
```

### Alter for PK
```sql
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `book`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
```

## Setting up the project
### Env variables
```text
cp .env-example .env
```
Fill out .env with db connection information and API credentials desired.


## Testing
To run a local dev server for testing:
```text
php -S 127.0.0.1:8000 -t public
```

Now, leverage postman for these endpoints:
(Authenticate using Basic Auth)
```text
// Get all books
GET /book

// Get a specific book
GET /book/{id}

// Create a new book
POST /book

// Update an existing book
PUT /book/{id}

// Delete an existing book
DELETE /book/{id}
```
