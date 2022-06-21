<?php

/**
 * Класс для обработки страниц
 */

class Page
{
  // Свойства
  public $id = null;
  public $title = null;
  public $adress = null;
  public $type = null;
  public $content = null;

  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['title'] ) ) $this->title = $data['title'];
    if ( isset( $data['adress'] ) ) $this->adress = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['adress'] );
    if ( isset( $data['type'] ) ) $this->type = (int) $data['type'];
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
  }


  /**
  * Возвращаем объект статьи соответствующий заданному ID статьи
  *
  * @param int ID статьи
  * @return Page|false Объект страницы или false, если запись не найдена или возникли проблемы
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME);
    $sql = "SELECT * FROM pages WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Page( $row );
  }

  /**
  * Возвращает все (или диапазон) объектов страниц в базе данных
  *
  * @param int Optional Количество строк (по умолчанию все)
  * @param string Optional Столбец по которому производится сортировка страниц (по умолчанию "adress ASC")
  * @return Array|false Двух элементный массив: results => массив, список объектов статей; totalRows => общее количество статей
  */

  public static function getList( $numRows=1000000, $order="title ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME);
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM pages
            ORDER BY " . $order . " LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $page = new Page( $row );
      $list[] = $page;
    }

    // Получаем общее количество страниц, которые соответствуют критерию
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }

  /**
  * Вставляем текущий объект страницы в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Есть у объекта страницы ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Page::insert(): Attempt to insert an Page object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Вставляем страницу
    $conn = new PDO( DB_DSN, DB_USERNAME);
    $sql = "INSERT INTO pages ( title, adress, type) VALUES ( :title, :adress, :type )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":adress", $this->adress, PDO::PARAM_STR );
    $st->bindValue( ":type", $this->type, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Обновляем текущий объект страницы в базе данных
  */

  public function update() {

    // Есть ли у объекта страницы ID?
    if ( is_null( $this->id ) ) trigger_error ( "Page::update(): Attempt to update an Page object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем страницы
    $conn = new PDO( DB_DSN, DB_USERNAME);
    $sql = "UPDATE pages SET title=:title, adress=:adress, type=:type, content=:content WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":adress", $this->adress, PDO::PARAM_STR );
    $st->bindValue( ":type", $this->type, PDO::PARAM_INT );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Удаляем текущий объект статьи из базы данных
  */

  public function delete() {

    // Есть ли у объекта страницы ID?
    if ( is_null( $this->id ) ) trigger_error ( "Page::delete(): Attempt to delete an Page object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем страницу
    $conn = new PDO( DB_DSN, DB_USERNAME);
    $st = $conn->prepare ( "DELETE FROM pages WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>