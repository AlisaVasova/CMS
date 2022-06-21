<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
    <link rel="stylesheet" type="text/css" href="templates/style.css" />
  </head>
  <body>
	  <div id="container">
	  	<div id="menu">
			<h3>Первый Петрозаводский общественный приют для животных</h3>
			<div id="links">
			<?php $pages = Page::getList()['results'];
				foreach ( $pages as $page ) {
					if ($page->type == 0) {
						echo '<a class="menu-link" href="index.php?action=viewPage&pageId='.$page->id.'"?>'.$page->title.'</a>';
					} elseif ($page->type == 1) {
						echo '<a class="menu-link" href="index.php?action=viewPage&pageId='.$page->id.'"?>'.$page->title.'</a>';
					}
				}
			?>
			</div>
		</div>
		<div>