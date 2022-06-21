<?php
 
require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
 
switch ( $action ) {
  case 'viewPage':
    viewPage();
    break;
  case 'viewArticle':
    viewArticle();
    break;
  default:
    homepage();
}

function viewPage() {
  if ( !isset($_GET["pageId"]) || !$_GET["pageId"] ) {
    homepage();
    return;
  }
 
  $results = array();
  $results['page'] = Page::getById( (int)$_GET["pageId"] );

  if ($results['page']) {
    $results['pageTitle'] = $results['page']->title;
    if ($results['page']->type == 0) {
      require( TEMPLATE_PATH . "/viewSimplePage.php" );
    } elseif ($results['page']->type == 1) {
      $data = Article::getListByPage($results['page']->id);
      $results['articles'] = $data['results'];
      require( TEMPLATE_PATH . "/viewCompositePage.php" );
    }
  } else {
    homepage();
    return;
  }
}

function viewArticle() {
  if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
    homepage();
    return;
  }
 
  $results = array();
  $results['article'] = Article::getById( (int)$_GET["articleId"] );
  if ($results['article']) {
    $results['pageTitle'] = $results['article']->title;
    require( TEMPLATE_PATH . "/viewArticle.php" );
  } else {
    homepage();
    return;
  }
}
 
function homepage() {
  $pages = Page::getList()['results'];
  if ($pages) {
    $page = Page::getById($pages[0]->id);
    if ($page->type == 0) {
      header( "Location: index.php?action=viewPage&pageId=".$page->id );
      return;
    } elseif ($page->type == 1) {
      header( "Location: index.php?action=viewPage&pageId=".$page->id );
      return;
    }
  }
  $results = array();
  $results['pageTitle'] = "Домашняя страница";
  require( TEMPLATE_PATH . "/homepage.php" );
}
 
?>