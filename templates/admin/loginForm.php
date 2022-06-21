<?php include "templates/include/header.php" ?>
<h1 style="margin-left: 15px;"><?php echo $results['pageTitle']?></h1>
<form action="admin.php?action=login" method="post">
  <input type="hidden" name="login" value="true" />
 
  <?php if ( isset( $results['errorMessage'] ) ) { ?>
    <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
  <?php } ?>
 
  <label for="username">Имя пользователя</label>
  <input type="text" name="username" id="username" placeholder="Your admin username" required autofocus maxlength="20" />

  <label for="password">Пароль</label>
  <input type="password" name="password" id="password" placeholder="Your admin password" required maxlength="20" />

  <div class="buttons">
    <input type="submit" name="login" value="Login" />
  </div>
 
</form>
 
<?php include "templates/include/footer.php" ?>