<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newPage':
    newPage();
    break;
  case 'editPage':
    editPage();
    break;
  case 'deletePage':
    deletePage();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  default:
    listPages();
}


function login() {

  $results = array();
  $results['pageTitle'] = "Войти";

  if ( isset( $_POST['login'] ) ) {

    // Пользователь получает форму входа: попытка авторизировать пользователя

    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

      // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );

    } else {

      // Ошибка входа: выводим сообщение об ошибке для пользователя
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }

  } else {

    // Пользователь еще не получил форму: выводим форму
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }

}


function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}


function newPage() {

  $results = array();
  $results['pageTitle'] = "Добавить страницу";
  $results['formAction'] = "newPage";

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получает форму редактирования страницы: сохраняем новую страницу
    unset( $_POST['id']);
    $page = new Page ( $_POST );
    $page->insert();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь сбросил результаты редактирования: возвращаемся к списку страниц
    header( "Location: admin.php" );
  } else {

    // Пользователь еще не получил форму редактирования: выводим форму
    $results['page'] = new Page;
    require( TEMPLATE_PATH . "/admin/editPage.php" );
  }

}


function deletePage() {

  if ( !$page = Page::getById( (int)$_GET['pageId'] ) ) {
    header( "Location: admin.php?error=pageNotFound" );
    return;
  }
  
  $page->delete();
  header( "Location: admin.php?status=pageDeleted" );
}

function editPage() {

  $results = array();
  $results['pageTitle'] = "Редактировать страницу";
  $results['formAction'] = "editPage";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "pageNotFound" ) $results['errorMessage'] = "Ошибка: страница не найдена";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Изменения сохранены";
    if ( $_GET['status'] == "pageDeleted" ) $results['statusMessage'] = "Страница удалена";
  }

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получил форму редактирования статьи: сохраняем изменения

    if ( !$page = Page::getById( (int)$_POST['id'] ) ) {
      header( "Location: admin.php?error=pageNotFound" );
      return;
    }

    $page = new Page ( $_POST );
    $page->update();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
    header( "Location: admin.php" );
  } else {

    // Пользвоатель еще не получил форму редактирования: выводим форму
    $results['page'] = Page::getById( (int)$_GET['pageId'] );
    if ($results['page']->type === 1) {
	    $data = Article::getListByPage($results['page']->id);
	    $results['articles'] = $data['results'];
    }
    require( TEMPLATE_PATH . "/admin/editPage.php" );
  }
}

function listPages() {
  $results = array();
  $data = Page::getList();
  $results['pages'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Страницы";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "pageNotFound" ) $results['errorMessage'] = "Ошибка: страница не найдена";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Изменения сохранены";
    if ( $_GET['status'] == "pageDeleted" ) $results['statusMessage'] = "Страница удалена";
  }

  require( TEMPLATE_PATH . "/admin/listPages.php" );
}

function newArticle() {

  $results = array();
  $results['pageTitle'] = "Добавить статью";
  $results['formAction'] = "newArticle"; 

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получает форму редактирования страницы: сохраняем новую страницу
    unset( $_POST['id']);
    if ( !isset( $_POST['is_public'] ) ) {$_POST['is_public'] = "0";}
    $article = new Article ( $_POST );
    $article->insert();

    header( "Location: admin.php?action=editPage&pageId=".$_POST['page_id']."&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь сбросил результаты редактирования: возвращаемся к списку страниц
    header( "Location: admin.php?action=editPage&pageId=".$_POST['page_id']);
  } else {

    // Пользователь еще не получил форму редактирования: выводим форму
    $results['article'] = new Article;
    $results['article']->page_id = (int)$_GET['pageId'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}

function editArticle() {

  $results = array();
  $results['pageTitle'] = "Редактировать статью";
  $results['formAction'] = "editArticle";

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получил форму редактирования статьи: сохраняем изменения
    if ( !isset( $_POST['is_public'] ) ) {$_POST['is_public'] = "0";}
    if ( !$article = Article::getById( (int)$_POST['id'] ) ) {
      header( "Location: admin.php?action=editPage&amp;pageId=".$_POST['page_id']."&amp;error=pageNotFound" );
      return;
    }

    $article = new Article ( $_POST );
    $article->update();
    header( "Location: admin.php?action=editPage&pageId=".$_POST['page_id']."&amp;status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
    header( "Location: admin.php?action=editPage&pageId=".$_POST['page_id'] );
  } else {

    // Пользвоатель еще не получил форму редактирования: выводим форму
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }
}

function deleteArticle() {

  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?action=editPage&pageId=".$_GET['pageId']."&error=pageNotFound" );
    return;
  }
  $article->delete();
  header( "Location: admin.php?action=editPage&pageId=".$_GET['pageId']."&status=pageDeleted" );
}

?>