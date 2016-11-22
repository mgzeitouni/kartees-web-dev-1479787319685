<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Dashboard</title>
 </head>
 <body>
   <div class="container">
    <nav class="dashboard-menu">
     <?php $this->user->getToken(); ?>
      <a href="<?= $this->config->item("base_url"); ?>/dashboard">Dashboard</a>
      <a href="<?= $this->config->item("base_url"); ?>/packages" class="<?php if($container['page_name'] == "packages") echo "active";?>">Packages</a>
      <?php
         $packages = json_decode($container['menu_packages'],true);
         
         foreach($packages as $package){
          print_r($package);
          echo "<br>";
         }
      ?>
      <a href="<?= $this->config->item("base_url"); ?>/account">Account</a>
    </nav>
    <?= $contents ?>
   </div>
   
 </body>
</html>