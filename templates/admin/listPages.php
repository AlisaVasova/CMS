<?php include "templates/admin/include/header.php" ?>
<div id="content">
  <div id="two-items">
    <h1><?php echo $results['pageTitle']?></h1>
    <p style="text-align: right;">
      <a href="admin.php?action=newPage" id="my-button">Добавить страницу</a>
    </p>
  </div>
  
  <?php if ( isset( $results['errorMessage'] ) ) { ?>
    <div class="errorMessage class97"><?php echo $results['errorMessage'] ?></div>
  <?php } ?>
  
  <?php if ( isset( $results['statusMessage'] ) ) { ?>
    <div class="statusMessage class97"><?php echo $results['statusMessage'] ?></div>
  <?php } ?>
  
  <table>
    <tr>
      <th>Заголовок</th>
      <th>Адрес</th>
      <th>Тип</th>
    </tr>
  
    <?php foreach ( $results['pages'] as $page ) { ?>
      <tr onclick="location='admin.php?action=editPage&amp;pageId=<?php echo $page->id?>'">
        <td>
          <?php echo $page->title?>
        </td>
        <td>
          <?php echo $page->adress?>
        </td>
        <td>
          <?php if ($page->type == 0) { echo "Статическая";} elseif ($page->type == 1) {echo "Динамическая";}?>
        </td>
      </tr>
    <?php } ?>
  </table>
</div> 
<?php include "templates/admin/include/footer.php" ?>