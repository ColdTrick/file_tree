<?php 

	global $CONFIG;
	
	gatekeeper();

	$guid = get_input("guid");
	$title = get_input("title");
	$owner_guid = get_input("page_owner");
	$description = get_input("description");
	$parent_guid = get_input("parent_guid", 0); // 0 is top_level
	$access_id = get_input("access_id", ACCESS_DEFAULT);
	$change_children_access = get_input("change_children_access", false);
	$change_files_access = get_input("change_files_access", false);
	
	if(!empty($title) && !empty($owner_guid)){
		$owner = get_entity($owner_guid);
		if($owner && ($owner instanceof ElggUser || $owner instanceof ElggGroup)){
			if(!empty($guid)){
				// check if editing existing
				if($folder = get_entity($guid)){
					if($folder->getSubtype() != FILE_TREE_SUBTYPE){
						unset($folder);
					}
				}
			} else {
				// create a new folder
				$folder = new ElggObject();
				$folder->subtype = FILE_TREE_SUBTYPE;
				$folder->owner_guid = get_loggedin_userid();
				$folder->container_guid = $owner_guid;
				$folder->access_id = $access_id;
				
				$order = elgg_get_entities_from_metadata(array(
					"type" => "object",
					"subtype" => FILE_TREE_SUBTYPE,
					"metadata_name" => "parent_guid",
					"metadata_value" => $parent_guid,
					"count" => true
				));
				
				$folder->order = $order;
				
				if(!$folder->save()){
					unset($folder);
				} 
			}

			if(!empty($folder)){
				$folder->title = $title;
				$folder->description = $description;
				
				$folder->access_id = $access_id;
				
				if(!empty($change_children_access)){
					$folder->save();
					file_tree_change_children_access($folder, !empty($change_files_access));
				} elseif(!empty($change_files_access)){
					$folder->save();
					file_tree_change_files_access($folder);
				}
				
				$folder->parent_guid = $parent_guid;
				
				if($folder->save()){
					$post_fix = "/" . $folder->getGUID();
					system_message(elgg_echo("file_tree:action:edit:success"));
				} else {
					register_error(elgg_echo("file_tree:action:edit:error:save"));
				}
			} else {
				register_error(elgg_echo("file_tree:action:edit:error:folder"));
			}
		} else {
			register_error(elgg_echo("file_tree:action:edit:error:owner"));
		}
	} else {
		register_error(elgg_echo("file_tree:action:edit:error:input"));
	}
	
	forward($CONFIG->wwwroot . "pg/file_tree/list/" . $owner_guid . $post_fix);
?>