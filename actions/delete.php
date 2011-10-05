<?php 

	gatekeeper();
	
	$folder_guid = get_input("folder_guid");
	
	if(!empty($folder_guid)){
		if($folder = get_entity($folder_guid)){
			if(($folder->getSubtype() == FILE_TREE_SUBTYPE) && $folder->canEdit()){
				if($folder->delete()){
					system_message(elgg_echo("file_tree:actions:delete:success"));
				} else {
					register_error(elgg_echo("file_tree:actions:delete:error:delete"));
				}
			} else {
				register_error(elgg_echo("file_tree:actions:delete:error:subtype"));
			}
		} else {
			register_error(elgg_echo("file_tree:actions:delete:error:entity"));
		}
	} else {
		register_error(elgg_echo("file_tree:actions:delete:error:input"));
	}

	forward(REFERER);
?>