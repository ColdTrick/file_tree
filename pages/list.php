<?php 

	global $CONFIG;

	$page_owner_guid = get_input("page_owner", get_loggedin_userid());
	$page_owner = get_entity($page_owner_guid);
	$folder_guid = get_input("folder_guid", false);
	$draw_page = get_input("draw_page", true);
	
	if(!empty($page_owner) && (($page_owner instanceof ElggUser) || ($page_owner instanceof ElggGroup))){
		// set page owner & context
		set_page_owner($page_owner_guid);
		set_context("file");
		
		group_gatekeeper();
		
		$wheres = array();
		$wheres[] = "NOT EXISTS (
				SELECT 1 FROM {$CONFIG->dbprefix}entity_relationships r 
				WHERE r.guid_two = e.guid AND
				r.relationship = '" . FILE_TREE_RELATIONSHIP . "')";
		
		$files_options = array(
				"type" => "object",
				"subtype" => "file",
				"limit" => false,
				"container_guid" => $page_owner_guid
			);
		
		if($folder_guid !== false){
			if($folder_guid == 0){
				$files_options["wheres"] = $wheres;
				$files = elgg_get_entities($files_options);	
			} else {
				$folder = get_entity($folder_guid);
				
				$files_options["relationship"] = FILE_TREE_RELATIONSHIP;
				$files_options["relationship_guid"] = $folder_guid;
				$files_options["inverse_relationship"] = false;
					
				$files = elgg_get_entities_from_relationship($files_options);	
			}	
		}
		
		if(!$draw_page){
			echo elgg_view("file_tree/list/files", array("folder" => $folder, "files" => $files));
		} else {

			// add js functionality
			elgg_extend_view("metatags", "file_tree/list/metatags");
			
			// get data for tree
			$folders = file_tree_get_folders($page_owner_guid);
			
			// default lists all unsorted files
			if($folder_guid === false){
				$files_options["wheres"] = $wheres;
				$files = elgg_get_entities($files_options);
			}
			
			// build page elements
			$tree = elgg_view("file_tree/list/tree", array("folder" => $folder, "folders" => $folders)); 
			$body = "<div id='file_tree_list_files_container'>" . elgg_view("ajax/loader") . "</div>";
			
			if(get_plugin_setting("replace_file", "file_tree") != "yes"){
				if(get_loggedin_userid() == $page_owner_guid){
					$title_text = elgg_echo("file_tree:menu:mine");
				} else {
					$title_text = sprintf(elgg_echo("file_tree:menu:user"), $page_owner->name);
				}
				
				// remove menu items
				unset($CONFIG->submenu);
				
				if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
					add_submenu_item(sprintf(elgg_echo("file:yours"),$page_owner->name), $CONFIG->wwwroot . "pg/file/owner/" . $page_owner->username);
				} else if (page_owner()) {
					add_submenu_item(sprintf(elgg_echo("file:user"),$page_owner->name), $CONFIG->wwwroot . "pg/file/owner/" . $page_owner->username);
				}
				
				if (can_write_to_container($_SESSION['guid'], page_owner()) && isloggedin()){
					add_submenu_item(elgg_echo('file:upload'), $CONFIG->wwwroot . "pg/file/new/". $page_owner->username);
				}
			} else {
				if(get_loggedin_userid() == $page_owner_guid){
					$title_text = elgg_echo("file:yours");
				} else {
					$title_text = sprintf(elgg_echo("file:user"), $page_owner->name);
				}
			}
			
			// build title
			$title = elgg_view_title($title_text);
			
			// draw page
			page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $title . $body, $tree));
		}
	} else {
		forward();
	}

?>