<?php

/**
 * Класс для обработки статей
 */

class Article
{
  // Свойства

  public $id = null;
  public $date = null;
  public $title = null;
  public $desc = null;
  public $content = null;
  public $image = null;
  public $is_public = null;
  public $page_id = null;

  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['date'] ) ) $this->date = $data['date'];
    if ( isset( $data['title'] ) ) $this->title = $data['title'];
    if ( isset( $data['desc'] ) ) $this->desc = $data['desc'];
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
    if ( isset( $data['image'] ) ) $this->image = $data['image'];
    if ( isset( $data['is_public'] ) ) $this->is_public = (bool) $data['is_public'];
    if ( isset( $data['page_id'] ) ) $this->page_id = (int) $data['page_id'];
  }


  /**
  * Возвращаем объект статьи соответствующий заданному ID статьи
  *
  * @param int ID статьи
  * @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $sql = "SELECT * FROM articles WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    $row['desc'] = $row['description'];
    if ( $row ) return new Article( $row );
  }


  /**
  * Возвращает все (или диапазон) объектов статей в базе данных
  *
  * @param int Optional Количество строк (по умолчанию все)
  * @param string Optional Столбец по которому производится сортировка  статей (по умолчанию "publicationDate DESC")
  * @return Array|false Двух элементный массив: results => массив, список объектов статей; totalRows => общее количество статей
  */

  public static function getList( $numRows=1000000, $order="date DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM articles
            ORDER BY " . $order . " LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $article = new Article( $row );
      $list[] = $article;
    }

    // Получаем общее количество статей, которые соответствуют критерию
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }

  public static function getListByPage( $pageId, $numRows=1000000, $order="date DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM articles WHERE page_id = :pageId
            ORDER BY " . $order . " LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->bindValue( ":pageId", $pageId, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $row['desc'] = $row['description'];
      $article = new Article( $row );
      $list[] = $article;
    }

    // Получаем общее количество статей, которые соответствуют критерию
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Вставляем текущий объект статьи в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Есть у объекта статьи ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Вставляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $sql = "INSERT INTO articles ( date, title, description, content, image, is_public, page_id ) VALUES ( :date, :title, :desc, :content, :image, :is_public, :page_id )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":date", $this->date, PDO::PARAM_STR );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":desc", $this->desc, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":image", $this->image, PDO::PARAM_STR );
    $st->bindValue( ":is_public", $this->is_public, PDO::PARAM_BOOL );
    $st->bindValue( ":page_id", $this->page_id, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Обновляем текущий объект статьи в базе данных
  */

  public function update() {

    // Есть ли у объекта статьи ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $sql = "UPDATE articles SET date=:date, title=:title, description=:desc, content=:content, image=:image, is_public=:is_public, page_id=:page_id WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":date", $this->date, PDO::PARAM_STR );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":desc", $this->desc, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":image", $this->image, PDO::PARAM_STR );
    $st->bindValue( ":is_public", $this->is_public, PDO::PARAM_BOOL );
    $st->bindValue( ":page_id", $this->page_id, PDO::PARAM_INT );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Удаляем текущий объект статьи из базы данных
  */

  public function delete() {

    // Есть ли у объекта статьи ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME );
    $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>