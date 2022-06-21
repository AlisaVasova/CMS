<?php include "templates/admin/include/header.php" ?>
<div id="content">
  
  <h1 class="class97"><?php echo $results['pageTitle']?>
	<?php if ( $results['page']->id ) {echo ' "'.$results['page']->title.'"';} ?></h1>
 
  <h2 class="class97">Свойства</h2>
  <form action="admin.php?action=<?php echo $results['formAction']?>" id="myForm" method="post" >
    <input type="hidden" name="id" id="page_id" value="<?php echo $results['page']->id ?>"/>
 
    <div <?php if ( $results['page']->id ) {echo 'id="three-items"';} ?>>
      <label for="title">Заголовок страницы</label>
      <input type="text" name="title" id="title" required autofocus maxlength="65" value="<?php echo htmlspecialchars( $results['page']->title )?>" />

      <label for="adress">Текст для отображения в адресной строке</label>
      <input type="text" name="adress" id="adress" required pattern="^[a-z0-9_-]+$" maxlength="65" value="<?php echo htmlspecialchars( $results['page']->adress )?>" />

      <label for="type">Тип страницы</label>
	    <select name="type" id="type">
    		<option value="0"<?php echo ($results['page']->type==0?' selected':''); ?>>Простая</option>
    		<option value="1"<?php echo ($results['page']->type==1?' selected':''); ?>>Составная</option>
	    </select>
    </div>           
 
    <?php if ( $results['page']->type === 0 ) { ?>
    <h2 class="class97">Содержимое</h2>
	  <div class="editor-div">
      <textarea id="editor" name="content"><?php echo $results['page']->content ?></textarea>
	  </div>
    <?php } ?>        

    <div class="buttons">
	    <?php if ( $results['page']->id ) { ?>
	      <a id="my-button" href="admin.php?action=deletePage&amp;pageId=<?php echo $results['page']->id ?>" onclick="return confirm('Удалить эту страницу?')">Удалить страницу</a>
	    <?php } ?>
      <input type="submit" name="saveChanges" value="Сохранить" />
      <input type="submit" formnovalidate name="cancel" value="Отмена" />
    </div>
 
  </form>

  <?php if ( $results['page']->type === 1 ) { ?>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
      <div class="errorMessage class97"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if ( isset( $results['statusMessage'] ) ) { ?>
    <div class="statusMessage class97"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

    <div id="two-items">
      <h2>Статьи</h2>
      <p style="text-align: right;">
        <a href="admin.php?action=newArticle&amp;pageId=<?php echo $results['page']->id ?>" id="my-button">Добавить статью</a>
      </p>
    </div>
        
    <table>
      <tr>
        <th>Заголовок</th>
        <th>Дата</th>
      </tr>
  
      <?php foreach ( $results['articles'] as $article ) { ?>
        <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
          <td>
            <?php echo $article->title?>
          </td>
          <td>
          <?= date_create_from_format('Y-m-d', $article->date)->format('d.m.Y');?>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } ?>    

</div>
    
<script src=<?=SCRIPT_PATH . "/ckeditor4/ckeditor.js"?>></script>
<script src=<?=SCRIPT_PATH . "/ckfinder/ckfinder.js"?>></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
  var myEditor = null;
  window.onload = function(){
    if (document.getElementById('type').value == 0 && document.getElementById('page_id').value) {
      var editor = CKEDITOR.replace('editor', {
        filebrowserBrowseUrl: 'scripts/ckfinder/ckfinder.html',
        filebrowserUploadUrl: 'scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserWindowWidth: '800',
        filebrowserWindowHeight: '500'
      });
      CKFinder.setupCKEditor( editor );
    }     
  }

  $('input').on('input invalid', function() {
      this.setCustomValidity('')
      if (this.validity.patternMismatch) {
        this.setCustomValidity("Может включать в себя только латинские буквы, цифры и символы \"-\" и \"_\"")
      }
  })
</script>
<?php include "templates/admin/include/footer.php" ?>