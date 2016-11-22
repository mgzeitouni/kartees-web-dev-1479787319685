<section class="content-3" style="padding: 50px;">

<?php
//$packages = json_decode($response,true);
$packages = array();
usort($response, function($a, $b) {
    return explode("_",$a['_id'])[1] < explode("_",$b['_id'])[1];
});

foreach($response as $package){
?>
		<div class="col-sm-6" >
            <div class="img ticket-image" id="<?= $package['_id'] ?>">
				<a href="packages/id/<?= $package['_id'] ?>" >
				<img src="images/ticket@2x.png" alt="">
				</a>
				<span class="sport"><?= $package['Sport'] ?></span>
				<span class="year"><?= $package['Year'] ?></span>
				<img class="edit"  data-jq-dropdown="#jq-dropdown-<?= $package['_id'] ?>" src="images/edit.png" >
				<span class="team"><?= $package['Team_Name'] ?><br><small>Team</small></span>
            </div>
		</div>
		<div id="jq-dropdown-<?= $package['_id'] ?>" class="jq-dropdown jq-dropdown-tip">
			<ul class="jq-dropdown-menu">
				<li>
					<form action="<?= $this->config->item('base_url') ?>/packages/delete" id="delete_<?= $package['_id'] ?>" method="POST">
						<input type="hidden" id="delete_id_<?= $package['_id'] ?>" name="Package_id" value="<?= $package['_id'] ?>">
						<a href="#" onclick="deletePackage('<?= $package['_id'] ?>')">Delete Package</a>
					</form>
				</li>
				<li class="jq-dropdown-divider"></li>
				<li><a href="#">Edit Package</a></li>
			</ul>
		</div>
<?php
}
?>
</section>

<link type="text/css" rel="stylesheet" href="<?= $this->config->item('base_url') ?>/common-files/dropdown/jquery.dropdown.css" />
<script type="text/javascript" src="<?= $this->config->item('base_url') ?>/common-files/dropdown/jquery.dropdown.js"></script>

<script>
	function deletePackage(id) {
		document.getElementById("delete_id_"+id).value = id;
        document.getElementById("delete_"+id).submit();
    }
</script>