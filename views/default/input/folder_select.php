<?php 

	$owner_guid = $vars["owner_guid"];

	$name = $vars["internalname"];
	$id = $vars["internalid"];
	$js = $vars["js"];
	$class = $vars["class"];
	
	$value = $vars["value"];
	
	if(empty($owner_guid)){
		$owner_guid = page_owner();
	}

	$folders = file_tree_get_folders($owner_guid);

	$options = "<option value='0'>" . elgg_echo("file_tree:input:folder_select:main") . "</option>\n";
	
	if(!empty($folders)){
		$options .= file_tree_build_select_options($folders, $value);
	}

?>
<select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>" <?php echo $js; ?>>
	<?php echo $options; ?>
</select>