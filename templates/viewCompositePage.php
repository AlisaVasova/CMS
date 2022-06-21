<?php include "templates/include/header.php" ?>
 
<div id="content">
    <h1><?php echo $results['pageTitle']?></h1>

    <?php foreach ( $results['articles'] as $article ) { ?>
        
        <?php 
            if ($article->is_public) {
                include("templates/include/article-mini.php");
            } ?>
 
    <?php } ?>

</div> 
<?php include "templates/include/footer.php" ?>