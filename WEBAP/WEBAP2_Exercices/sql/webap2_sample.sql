-- Sample tables for the WEBAP2 exercises (chapters 6 and 7)
-- Imported automatically by Docker on the very first start (see docker-compose.yml),
-- otherwise import it with phpMyAdmin (http://localhost:8081) into the database webap2_db.

SET NAMES utf8mb4;
USE webap2_db;

DROP TABLE IF EXISTS tblTodo;
CREATE TABLE tblTodo (
    idTodo    INT AUTO_INCREMENT PRIMARY KEY,
    dtTitle   VARCHAR(200) NOT NULL,
    dtDone    TINYINT(1) NOT NULL DEFAULT 0,
    dtCreated DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblTodo (dtTitle, dtDone, dtCreated) VALUES
    ('Read chapter 6 of the script', 1, '2026-11-20 08:15:00'),
    ('Write the helper functions', 0, '2026-11-21 09:30:00'),
    ('Test the API with the API tester', 0, '2026-11-22 10:45:00');

DROP TABLE IF EXISTS tblFavorite;
CREATE TABLE tblFavorite (
    idFavorite  INT AUTO_INCREMENT PRIMARY KEY,
    dtName      VARCHAR(100) NOT NULL,
    dtCountry   VARCHAR(100) NOT NULL DEFAULT '',
    dtLatitude  DECIMAL(8,5) NOT NULL,
    dtLongitude DECIMAL(8,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblFavorite (dtName, dtCountry, dtLatitude, dtLongitude) VALUES
    ('Luxembourg', 'Luxembourg', 49.61167, 6.13000),
    ('Trier', 'Germany', 49.75565, 6.63935);

DROP TABLE IF EXISTS tblBook;
CREATE TABLE tblBook (
    idBook    INT AUTO_INCREMENT PRIMARY KEY,
    dtKey     VARCHAR(40) NOT NULL UNIQUE,
    dtTitle   VARCHAR(255) NOT NULL,
    dtAuthor  VARCHAR(255) NOT NULL DEFAULT '',
    dtYear    INT NULL,
    dtCoverId INT NULL,
    dtStatus  ENUM('want', 'reading', 'read') NOT NULL DEFAULT 'want',
    dtRating  TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblBook (dtKey, dtTitle, dtAuthor, dtYear, dtCoverId, dtStatus, dtRating) VALUES
    ('/works/OL262758W', 'The Hobbit', 'J.R.R. Tolkien', 1937, 14627509, 'read', 5);
