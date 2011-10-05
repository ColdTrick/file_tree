<?php 

	gatekeeper();
	
	$folder_guid = get_input("folder_guid");
	$forward = true;
	
	if(!empty($folder_guid)){
		if($folder = get_entity($folder_guid)){
			if(($folder->getSubtype() == FILE_TREE_SUBTYPE) && $folder->canEdit()){
				$forward = false;
				
				// set context and page_owner
				set_context("file");
				set_page_owner($folder->getContainer());
				
				// build page elements
				$title_text = elgg_echo("file_tree:edit:title");
				$title = elgg_view_title($title_text);
				
				$edit = elgg_view("file_tree/forms/edit", array("folder" => $folder, "page_owner_entity" => page_owner_entity()));
				
				// build page
				$page_data = $title . $edit;
			}
		}
	}

	if(!$forward){
		page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));
	} else {
		forward(REFERER);
	}

?>