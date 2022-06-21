<?php include "templates/admin/include/header.php" ?>
	<div id="content">
  
      	<h1><?php echo $results['pageTitle']?>
		<?php if ( $results['article']->id ) {echo ' "'.$results['article']->title.'"';} ?></h1>
 
      	<form action="admin.php?action=<?php echo $results['formAction']?>" id="myForm" method="post" >
        	<input type="hidden" name="id" value="<?php echo $results['article']->id ?>"/>
        	<input type="hidden" name="page_id" value="<?php echo $results['article']->page_id ?>"/>
 
			<?php if ( isset( $results['errorMessage'] ) ) { ?>
        		<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
			<?php } ?>
 
            <label for="title">Заголовок статьи</label>
            <input type="text" name="title" id="title" required autofocus maxlength="65" value="<?php echo htmlspecialchars( $results['article']->title )?>" />

            <label for="date">Дата</label>
	    	<input id="date" name="date" type="date" required value="<?php echo htmlspecialchars( $results['article']->date )?>">

            <label for="desc">Краткое содержание</label>
            <textarea type="text" name="desc" id="desc" maxlength="65535" value=""><?php echo htmlspecialchars( $results['article']->desc )?></textarea>

			<label for="editor">Содержание статьи</label>
			<div class="editor-div">
                <textarea id="editor" name="content"><?php echo $results['article']->content ?></textarea>
			</div>

			<label for="ckfinder-input">Миниатюра</label>
			<div id="two-items">
 				<input id="ckfinder-input" name="image" required style="margin-left: 0" type="text" value="<?php echo $results['article']->image ?>">
 				<button id="ckfinder-popup" type="button">Загрузить</button>
			</div>
				
			
			<input type="checkbox" id="is_public" name="is_public" value="1" <?php echo ($results['article']->is_public) ? "checked" : ""; ?>>
			<label for="is_public" id="label-check">Опубликовать</label>

        	<div class="buttons">
				<?php if ( $results['article']->id ) { ?>
	  				<a id="my-button" href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>&amp;pageId=<?php echo $results['article']->page_id?>'" onclick="return confirm('Удалить эту статью?')">Удалить статью</a>
				<?php } ?>
          		<input type="submit" name="saveChanges" value="Сохранить" />
          		<input type="submit" formnovalidate name="cancel" value="Отмена" />
        	</div>
 
     	</form>
	</div>
	
    <script src=<?=SCRIPT_PATH . "/ckeditor4/ckeditor.js"?>></script>
    <script src=<?=SCRIPT_PATH . "/ckfinder/ckfinder.js"?>></script>
<script language="JavaScript" type="text/javascript">
var myEditor = null;
window.onload = function(){
	var editor = CKEDITOR.replace('editor', {
	filebrowserBrowseUrl: 'scripts/ckfinder/ckfinder.html',
	filebrowserUploadUrl: 'scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserWindowWidth: '800',
	filebrowserWindowHeight: '500'
     });
     CKFinder.setupCKEditor( editor );
     
}

var button = document.getElementById( 'ckfinder-popup' );

button.onclick = function() {
	selectFileWithCKFinder( 'ckfinder-input' );
};
function selectFileWithCKFinder( elementId ) {
	CKFinder.popup( {
		chooseFiles: true,
		width: 800,
		height: 600,
		onInit: function( finder ) {
			finder.on( 'files:choose', function( evt ) {
				var file = evt.data.files.first();
				var output = document.getElementById( elementId );
				output.value = file.getUrl();
			} );

			finder.on( 'file:choose:resizedImage', function( evt ) {
				var output = document.getElementById( elementId );
				output.value = evt.data.resizedUrl;
			} );
		}
	} );
}
</script>
<?php include "templates/admin/include/footer.php" ?>