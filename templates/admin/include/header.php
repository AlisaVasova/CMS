<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
    <link rel="stylesheet" type="text/css" href="templates/admin/style.css" />
    <script src="https://kit.fontawesome.com/f956b6a0cb.js" crossorigin="anonymous"></script>
  </head>
  <body>
   
    <?php if (isset($_SESSION['username'])): ?>
      <div id="container">
        <div id="adminHeader">
          <h1 style="margin: 0">Панель администрирования</h1>
          <p id="logout"><b><?php echo htmlspecialchars( $_SESSION['username']) ?></b> (<a href="admin.php?action=logout"?>выйти</a>)</p>
        </div>
        <div id="sidebar">
          <p><b><a href="admin.php?action=listPages"?>Страницы</a></b></p>
          <?php $pages = Page::getList()['results'];
            foreach ( $pages as $page ) {
              echo '<a class="page-link" href="admin.php?action=editPage&pageId='.$page->id.'"?>'.$page->title.'</a>';
            }
          ?>
        </div>
    <?php endif; ?>