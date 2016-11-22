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
      <a href="<?= $this->config->item("base_url"); ?>/packages" class="<?php if($container['page_name'] == "packages") echo "active";?>">View Packages</a>
      <?php
      if(isset($menu_packages)){
         $packages = json_decode($container['menu_packages'],true);
         
         foreach($packages as $package){
          print_r($package);
          echo "<br>";
         }
      }
      ?>
      <a href="<?= $this->config->item("base_url"); ?>/packages/create" class="<?php if($container['page_name'] == "create_packages") echo "active";?>">Create Package</a>

      <a href="<?= $this->config->item("base_url"); ?>/account">Account</a>
    </nav>
    <?php if(isset($flash_message)){
      echo '<div class="flash_message">'.$flash_message.'</div>';
    } ?>
    <?= $contents ?>
   </div>
   
 </body>
</html>