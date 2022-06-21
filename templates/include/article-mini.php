<div class="article-mini" onclick="location='index.php?action=viewArticle&amp;articleId=<?php echo $article->id ?>'">
<div class="image-mini-div"><img src="<?php echo $article->image; ?>" class="image-mini"></div>
<h2 class="title-mini"><?php echo htmlspecialchars($article->title); ?></h2>
<p class="date-mini"><?= date_create_from_format('Y-m-d', $article->date)->format('d.m.Y');?></p>
<p class="desc-mini"><?php echo $article->desc; ?></p>
</div>