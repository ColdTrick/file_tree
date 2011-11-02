<?php

	function file_tree_object_handler($event, $type, $object){
	
		if(!empty($object) && ($object instanceof ElggObject)){
			if($object->getSubtype() == "file"){
				$folder_guid = get_input("folder_guid", false);
	
				if(!empty($folder_guid)){
					if($folder = get_entity($folder_guid)){
						if($folder->getSubtype() != FILE_TREE_SUBTYPE){
							unset($folder_guid);
						}
					} else {
						unset($folder_guid);
					}
				}
	
				if($folder_guid !== false){
					// remove old relationships
					remove_entity_relationships($object->getGUID(), FILE_TREE_RELATIONSHIP, true);
						
					if(!empty($folder_guid)){
						add_entity_relationship($folder_guid, FILE_TREE_RELATIONSHIP, $object->getGUID());
					}
				}
			}
		}
	}
	
	function file_tree_object_handler_delete($event, $type, $object){
	
		if(!empty($object) && ($object instanceof ElggObject)){
			if($object->getSubtype() == FILE_TREE_SUBTYPE){
				// find subfolders
				$options = array(
						"type" => "object",
						"subtype" => FILE_TREE_SUBTYPE,
						"owner_guid" => $object->getOwner(),
						"limit" => false,
						"metadata_name" => "parent_guid",
						"metadata_value" => $object->getGUID()
				);
	
				if($subfolders = elgg_get_entities_from_metadata($options)){
					// delete subfolders
					foreach($subfolders as $subfolder){
						$subfolder->delete();
					}
				}
	
				// should we remove files?
				if(get_input("files") == "yes"){
					// find file in this folder
					$options = array(
							"type" => "object",
							"subtype" => "file",
							"container_guid" => $object->getOwner(),
							"limit" => false,
							"relationship" => FILE_TREE_RELATIONSHIP,
							"relationship_guid" => $object->getGUID()
					);
						
					if($files = elgg_get_entities_from_relationship($options)){
						// delete files in folder
						foreach($files as $file){
							$file->delete();
						}
					}
				}
			}
		}
	}