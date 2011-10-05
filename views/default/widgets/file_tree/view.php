<?php 

	$widget = $vars["entity"];
	
	if($folder_guids = $widget->getMetadata("folder_guids")){
		if(!is_array($folder_guids)){
			$folder_guids = array($folder_guids);
		}
		
		$folder_guids = array_map("sanitise_int", $folder_guids);
		
		foreach($folder_guids as $guid){
			if(($folder = get_entity($guid)) && ($folder->getSubtype() == FILE_TREE_SUBTYPE)){
				$folders .= elgg_view_entity($folder);
			}
		}
		
		if(!empty($folders)){
			echo $folders;
			
			echo "<div class='widget_more_wrapper'>";
			echo elgg_view("output/url", array("href" => $vars["url"] . "pg/file_tree/list/" . $widget->getOwner(), "text" => elgg_echo("widgets:file_tree:more")));
			echo "</div>";
		} else {
			echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widgets:file_tree:no_folders")));
		}
	} else {
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widgets:file_tree:no_folders")));
	}

?>