<?php include "templates/include/header.php" ?>

<div id="content">
    <h1 class="article-title"><?php echo $results['pageTitle']?></h1>
    <div class="article-date"><?= date_create_from_format('Y-m-d', $results['article']->date)->format('d.m.Y');?></div>
    <?php echo $results['article']->content ?>
</div>
<?php include "templates/include/footer.php" ?>