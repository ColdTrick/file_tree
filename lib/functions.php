<?php 

	function file_tree_get_folders($container_guid = 0){
		$result = false;
		
		if(empty($container_guid)){
			$container_guid = page_owner();
		}
		
		if(!empty($container_guid)){
			$options = array(
				"type" => "object",
				"subtype" => FILE_TREE_SUBTYPE,
				"container_guid" => $container_guid,
				"limit" => false
			);
			
			if($folders = elgg_get_entities($options)){
				$parents = array();

				foreach($folders as $folder){
					$parent_guid = $folder->parent_guid; 
					
					if(!empty($parent_guid)){
						if($temp = get_entity($parent_guid)){
							if($temp->getSubtype() != FILE_TREE_SUBTYPE){
								$parent_guid = 0;
							}
						} else {
							$parent_guid = 0;
						}
					} else {
						$parent_guid = 0;
					}
					
					if(!array_key_exists($parent_guid, $parents)){
						$parents[$parent_guid] = array();
					}
					
					$parents[$parent_guid][] = $folder;
				}
				
				$result = file_tree_sort_folders($parents, 0);
				
			}
		}
		return $result;
	}
	
	function file_tree_build_select_options($folder, $selected = 0, $niveau = 0){
		$result = "";
		
		if(is_array($folder) && !array_key_exists("children", $folder)){
			foreach($folder as $folder_item){
				$result .= file_tree_build_select_options($folder_item, $selected, $niveau);
			}
		} else {
			$folder_item = $folder["folder"];
			
			if($selected == $folder_item->getGUID()){
				$result .= "<option value='" . $folder_item->getGUID() . "' selected='selected'>" . str_repeat("-", $niveau) . " " .  $folder_item->title . "</option>";
			} else {
				$result .= "<option value='" . $folder_item->getGUID() . "'>" . str_repeat("-", $niveau) . " " .  $folder_item->title . "</option>";
			}
			
			if(!empty($folder["children"])) {
				$result .= file_tree_build_select_options($folder["children"], $selected, $niveau + 1);
			}
		}
		
		return $result;
	}
	
	function file_tree_build_widget_options($folder, $internalname = "", $selected = array()){
		$result = "";
		
		if(is_array($folder) && !array_key_exists("children", $folder)){
			foreach($folder as $folder_item){
				$result .= "<ul>";
				$result .= file_tree_build_widget_options($folder_item, $internalname, $selected);
				$result .= "</ul>";
			}
		} else {
			$folder_item = $folder["folder"];
			
			$result .= "<li>";
			if(in_array($folder_item->getGUID(), $selected)){
				$result .= "<input type='checkbox' name='" . $internalname . "' value='" . $folder_item->getGUID() . "' checked='checked'> " .  $folder_item->title;
			} else {
				$result .= "<input type='checkbox' name='" . $internalname . "' value='" . $folder_item->getGUID() . "'> " .  $folder_item->title;
			}
			
			if(!empty($folder["children"])) {
				$result .= file_tree_build_widget_options($folder["children"], $internalname, $selected);
			}
			$result .= "</li>";
		}
		
		return $result;
	}
	
	function file_tree_sort_folders($folders, $parent_guid = 0){
		$result = false;
		
		if(array_key_exists($parent_guid, $folders)){
			$result = array();
			
			foreach($folders[$parent_guid] as $subfolder){
				
				$children = file_tree_sort_folders($folders, $subfolder->getGUID());
				
				$order = $subfolder->order;
				if(empty($order)){
					$order = 0;
				}
				
				while(array_key_exists($order, $result)){
					$order++;
				}
				
				$result[$order] = array(
					"folder" => $subfolder,
					"children" => $children
				);
			}
			
			ksort($result);
		}
		
		return $result;
	}
	
	function file_tree_display_folders($folder){
		$result = "";
		
		if(is_array($folder) && !array_key_exists("children", $folder)){
			$result .= "<ul>";
			foreach($folder as $folder_item){
				$result .= file_tree_display_folders($folder_item);
			}
			$result .= "</ul>";
		} elseif(!empty($folder["children"])){
			
			$result .= "<li><a id='" . $folder["folder"]->getGUID() . "' title='" . $folder["folder"]->title . "' href='#'>" . $folder["folder"]->title . "</a>";
			$result .= file_tree_display_folders($folder["children"]);
			$result .= "</li>";
		} elseif(array_key_exists("folder", $folder)) {
			$folder = $folder["folder"];
			$result .= "<li><a id='" . $folder->getGUID() . "' title='" . $folder->title . "' href='#'>" . $folder->title . "</a></li>";
		}
		
		return $result;
	}
	
	function file_tree_change_children_access($folder, $change_files = false){
		
		if(!empty($folder) && ($folder instanceof ElggObject)){
			if($folder->getSubtype() == FILE_TREE_SUBTYPE){
				// get children folders
				$options = array(
					"type" => "object",
					"subtype" => FILE_TREE_SUBTYPE,
					"container_guid" => $folder->getContainer(),
					"limit" => false,
					"metadata_name" => "parent_guid",
					"metadata_value" => $folder->getGUID()
				);
				
				if($children = elgg_get_entities_from_metadata($options)){
					foreach($children as $child){
						$child->access_id = $folder->access_id;
						$child->save();
						
						file_tree_change_children_access($child, $change_files);
					}
				}
				
				if($change_files){
					// change access on files in this folder
					file_tree_change_files_access($folder);
				}
			}
		}
	}
	
	function file_tree_change_files_access($folder){
		
		if(!empty($folder) && ($folder instanceof ElggObject)){
			if($folder->getSubtype() == FILE_TREE_SUBTYPE){
				// change access on files in this folder
				$options = array(
					"type" => "object",
					"subtype" => "file",
					"container_guid" => $folder->getContainer(),
					"limit" => false,
					"relationship" => FILE_TREE_RELATIONSHIP,
					"relationship_guid" => $folder->getGUID()
				);
				
				if($files = elgg_get_entities_from_relationship($options)){
					foreach($files as $file){
						$file->access_id = $folder->access_id;
						$file->save();
					}
				}
			}
		}	
	}
	
	function file_tree_replace_page_handler($handler, $function){
		global $CONFIG;
		
		if(!empty($CONFIG->pagehandler)){
			if(array_key_exists($handler, $CONFIG->pagehandler)){
				if(!isset($CONFIG->backup_pagehandler)){
					$CONFIG->backup_pagehandler = array();
				}
				
				$CONFIG->backup_pagehandler[$handler] = $CONFIG->pagehandler[$handler];
			}
		}
		
		return register_page_handler($handler, $function);
	}
	
	function file_tree_fallback_page_handler($page, $handler){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($CONFIG->backup_pagehandler)){
			if(array_key_exists($handler, $CONFIG->backup_pagehandler)){
				$function = $CONFIG->backup_pagehandler[$handler];
				
				if(is_callable($function)){
					$result = $function($page, $handler);
				}
			}
		}
		
		return $result;
	}
