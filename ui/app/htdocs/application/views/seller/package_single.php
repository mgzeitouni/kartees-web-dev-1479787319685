<?php
$packages = json_decode($response,true);

foreach($packages as $package){
?>

<div class="listing_container" style="border: 1px solid black">
        <pre><?php print_r($package); ?></pre> <br> 
</div>

<?php
}
?>