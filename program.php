<?php

/* Дана таблица некоторой базы данных. Таблица содержит: порядковый номер записи (ключевое поле), автор книги, название книги, местоположение книги (например: "Отделение 1, полка 2"). С помощью технологии ORM ввести данные этой таблицы в набор объектов класса, вывести затем эту таблицу на экран */

/* ORM - Object Relational Mapping (объектно-реляционное отображение). Позволяет работать с базой данных как с объектами: класс соответствует таблице, свойства класса - полям таблицы */

/* Обратим внимание, что если объекты забираются с помощью PDO::FETCH_CLASS, сначала присваиваются свойства объекта, а следом вызывается конструктор объекта */

class OneBook
{
    const HOSTNAME = 'localhost';
    const DBNAME = 'THEBASE';
    const USERNAME = 'admin1';
    const PASSW = '12345';
    const CHARSET = 'utf8';
    
    private $authorBook;
    private $nameBook;
    private $locationBook;

    public function __construct()
    {
    }
    
    public function showData()
    {
        echo $this->authorBook."<br>";
        echo $this->nameBook."<br>";
        echo $this->locationBook."<br>";
    }
}

/* Поскольку конструктор вызывается после присвоения объектов, то есть после PDO, а для выполнения PDO необходимо обратиться к значениям хоста, имени базы данных и так далее, то применим для этой цели константы класса. Константы класса, в отличие от переменных, принадлежат не объекту, а самому классу. Следовательно, обратиться к этим константам можно, во-первых, по имени класса, а, во-вторых, минуя стадию создания объекта класса. Итак, обращаемся к константам класса, после этого запускаем выполнение PDO и затем только создаём объекты класса и присваиваем им значения полей нашей таблицы */

$variable1 = OneBook::HOSTNAME;
$variable2 = OneBook::DBNAME;
$variable3 = OneBook::CHARSET;

$dsn = "mysql:host=$variable1;dbname=$variable2;charset=$variable3";
$pdoVar = new PDO($dsn, OneBook::USERNAME, OneBook::PASSW);
    
// Прежде всего выведем информацию просто на экран (сделаем это для сравнения)

// Первый вариант создания запроса PDO
$statement = $pdoVar->prepare("SELECT * FROM BooksT ORDER BY NumberBook ASC");
$statement->execute();

// Или второй вариант создания запроса PDO (какой-то один из вариантов)
//$statement = $pdoVar->query("SELECT * FROM BooksT ORDER BY NumberBook ASC");
    
while (($stroka = $statement->fetch()) !== false) {
    echo $stroka['numberBook']." ".$stroka['authorBook']." ".$stroka['nameBook']." ".$stroka['locationBook']."<br>";
}

echo "<br>---------------------------------<br>";
// Теперь выведем информацию в объекты класса

// Сделаем выборку записей любым из двух вариантов (смотрите выше)
$statement = $pdoVar->prepare("SELECT * FROM BooksT ORDER BY NumberBook ASC");
$statement->execute();

// Теперь установим режим выборки для нашего класса OneBook
$statement->setFetchMode(PDO::FETCH_CLASS, 'OneBook');

/* А теперь произведём запись данных в объекты классов, то есть сделаем фактически объектно-реляционное отображение ORM */
$i = 0;
while (($stroka[$i] = $statement->fetch()) !== false) {
    $i++;
}
/* Так как получилось в массиве на 1 элемент больше (последний элемент - false), то последний элемент здесь надо удалить */
unset($stroka[$i]);

/* Проверим, что получилось. Получилось, что каждый элемент $stroka[$i] является объектом. Если бы в классе OneBook переменные были бы не private, а public, к ним можно было бы обратиться, например, так:
$stroka[2]->authorBook; */
echo "<pre>";
    var_dump($stroka);
echo "</pre>";

for ($j = 0; $j < 5; $j++) {
    echo $stroka[$j]->showData();
}

$pdoVar = null;
