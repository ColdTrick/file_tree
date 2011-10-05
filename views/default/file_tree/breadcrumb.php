<?php 

	$file = $vars["entity"];
	
	$folder_options = array(
			"type" => "object",
			"subtype" => FILE_TREE_SUBTYPE,
			"owner_guid" => page_owner(),
			"relationship" => FILE_TREE_RELATIONSHIP,
			"relationship_guid" => $file->getGUID(),
			"inverse_relationship" => true,
			"limit" => 1
		);
	
	$folders = elgg_get_entities_from_relationship($folder_options);
	$folder = $folders[0];
	
	if($folder){
		echo "<div id='file_tree_breadcrumbs' class='contentWrapper filerepo_file'>";
		
		echo "<ul>";
		echo "<li><a href='" . $vars["url"] . "pg/file_tree/list/" . page_owner() . "'>" . elgg_echo("file_tree:list:folder:main") . "</a></li>";

		if($folder->parent_guid && get_entity($folder->parent_guid)){
			// if parent folder for folder available
			echo "<li><a href='#'>...</a></li>";
		}
		
		echo "<li><a href='" . $vars["url"] . "pg/file_tree/list/" . page_owner() . "/" . $folder->guid . "'>" . $folder->title . "</a></li>";
		
		$file_title = $file->title;
		if(!$file_title){
			$file_title = elgg_echo("untitled");
		}
		echo "<li>" . $file_title . "</li>";
		echo "</ul>";
		echo "<div class='clearfloat'></div>";
		echo "</div>";
	}

?>