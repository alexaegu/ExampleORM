CREATE DATABASE IF NOT EXISTS THEBASE
  CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE THEBASE;

CREATE USER IF NOT EXISTS 'admin1'@'localhost'
  IDENTIFIED BY '12345';

/****************************************************************************/

CREATE TABLE IF NOT EXISTS BooksT
(
  /* Названия полей - с маленькой буквы, так как они должны совпадать с названиями переменных в классе */
  numberBook int unsigned auto_increment primary key not null,
  authorBook varchar(20) not null,
  nameBook varchar(255) not null,
  locationBook varchar(255) not null,
  /* Не может быть двух одинаковых книг в одном месте */
  UNIQUE INDEX (authorBook, nameBook, locationBook) COMMENT 'нельзя две одинаковые книги в одном месте'
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/****************************************************************************/

GRANT SELECT, INSERT, UPDATE, DELETE
  ON THEBASE.*
  TO 'admin1'@'localhost' IDENTIFIED BY '12345';
