<?php 

	$entity = $vars["entity"];
	$full_view = $vars["full"];
	
	if(!$full_view){
		// count file in folder
		$options = array(
			"type" => "object",
			"subtype" => "file",
			"relationship" => FILE_TREE_RELATIONSHIP,
			"relationship_guid" => $entity->getGUID(),
			"count" => true
		);
		$count = elgg_get_entities_from_relationship($options);
		
		$icon = elgg_view("file_tree/icon", array("entity" => $entity, "size" => "small"));
		
		$info = elgg_view("output/url", array("href" => $entity->getURL(), "text" => $entity->title));
		$info .= "<br />";
		if(!empty($count)){
			$info .= sprintf(elgg_echo("file_tree:object:files"), $count);
		} else {
			$info .= elgg_echo("file_tree:object:no_files");
		}
		
		
		echo elgg_view_listing($icon, $info);
	}

?>