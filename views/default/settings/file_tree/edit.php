<?php 

	$settings = $vars["entity"];

	if($settings->replace_file != "yes"){
		$options = "<option value='no' selected='selected'>" . elgg_echo("option:no") . "</option>\n";
		$options .= "<option value='yes'>" . elgg_echo("option:yes") . "</option>\n";
	} else {
		$options = "<option value='no'>" . elgg_echo("option:no") . "</option>\n";
		$options .= "<option value='yes' selected='selected'>" . elgg_echo("option:yes") . "</option>\n";
	}
?>
<div>
	<div><?php echo elgg_echo("file_tree:settings:replace_file"); ?></div>
	<select name="params[replace_file]">
		<?php echo $options; ?>
	</select>
	
</div>