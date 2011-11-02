<?php 

	define("FILE_TREE_SUBTYPE", "folder");
	define("FILE_TREE_RELATIONSHIP", "folder_of");

	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	
	function file_tree_init(){
		
		// extend CSS
		elgg_extend_view("css", "file_tree/css");
		
		// extend object view of file
		elgg_extend_view("object/file", "file_tree/extend/file", 499);
		
		// register page handler for nice URL's
		register_page_handler("file_tree", "file_tree_page_handler");
		
		// make our own URLs for folders
		register_entity_url_handler("file_tree_folder_url_handler", "object", FILE_TREE_SUBTYPE);
		
		// make our own URLs for folder icons
		register_plugin_hook("entity:icon:url", "object", "file_tree_folder_icon_hook");
		
		// register group option to allow management of file tree structure
		add_group_tool_option("file_tree_structure_management", elgg_echo("file_tree:group_tool_option:structure_management"));
		
		// take over default file view?
		if(get_plugin_setting("replace_file", "file_tree") == "yes"){
			file_tree_replace_page_handler("file", "file_tree_file_page_handler");
		}
		
		// add widget
		add_widget_type("file_tree", elgg_echo("widgets:file_tree:title"), elgg_echo("widgets:file_tree:description"), "dashboard,profile,groups");
		if(is_callable("add_widget_title_link")){
			add_widget_title_link("file_tree", "[BASEURL]pg/file_tree/list/[GUID]");
		}
	}

	function file_tree_pagesetup(){
		global $CONFIG;
		
		$context = get_context();
		$page_owner = page_owner_entity();
		
		if($context == "file"){
			elgg_extend_view("categories", "file_tree/extend/categories");
		}
		
		if(get_plugin_setting("replace_file", "file_tree") != "yes"){
			if($context == "file" && !empty($page_owner)){
				if($page_owner->getGUID() == get_loggedin_userid()) {
					add_submenu_item(elgg_echo("file_tree:menu:mine"), $CONFIG->wwwroot . "pg/file_tree/list/" . $page_owner->getGUID());
				} else {
					add_submenu_item(sprintf(elgg_echo("file_tree:menu:user"), $page_owner->name), $CONFIG->wwwroot . "pg/file_tree/list/" . $page_owner->getGUID());
				}
			}
			
			if(($context == "groups") && ($page_owner instanceof ElggGroup) && ($page_owner->file_enable != "no")){
				if($page_owner instanceof ElggGroup){
					add_submenu_item(elgg_echo("file_tree:menu:group"), $CONFIG->wwwroot . "pg/file_tree/list/" . $page_owner->getGUID());
				}
			}
		}
	}
	
	function file_tree_page_handler($page){
		
		switch($page[0]){
			case "list":
				if(!empty($page[1])){
					set_input("page_owner", $page[1]);
					
					if(get_input("folder_guid", false) !== false){
						set_input("draw_page", false);
					}
					
					if(array_key_exists(2, $page)){
						set_input("folder_guid", $page[2]);
					}
				}
				include(dirname(__FILE__) . "/pages/list.php");
				break;
			case "new":
				if(!empty($page[1])){
					set_input("page_owner", $page[1]);
				}
				include(dirname(__FILE__) . "/pages/new.php");
				break;
			case "reorder":
				include(dirname(__FILE__) . "/procedures/reorder.php");
				break;
			case "edit":
				if(!empty($page[1])){
					set_input("folder_guid", $page[1]);
					
					include(dirname(__FILE__) . "/pages/edit.php");
					break;
				}
			case "file_move":
				include(dirname(__FILE__) . "/procedures/file_move.php");
				break;
			default:
				forward("pg/file_tree/list/" . get_loggedin_userid());
		}
	}
	
	function file_tree_file_page_handler($page){
		
		switch($page[0]){
			case "owner":
				if(!empty($page[1])){
					$username = $page[1];
					
					if(stristr($username, "group:")){
						list($dummy, $guid) = explode(":", $username);
						set_input("page_owner", $guid);
					} elseif($user = get_user_by_username($username)) {
						set_input("page_owner", $user->getGUID());
					}
					
					include(dirname(__FILE__) . "/pages/list.php");
				}
				break;
			default:
				file_tree_fallback_page_handler($page, "file");
				break;
		}
	}
	
	function file_tree_folder_url_handler($entity){
		global $CONFIG;
		
		return $CONFIG->wwwroot . "pg/file_tree/list/" . $entity->getContainer() . "#" . $entity->getGUID();
	}
	
	// register default elgg events
	register_elgg_event_handler("init", "system", "file_tree_init");
	register_elgg_event_handler("pagesetup", "system", "file_tree_pagesetup");
	
	// register events
	register_elgg_event_handler("create", "object", "file_tree_object_handler");
	register_elgg_event_handler("update", "object", "file_tree_object_handler");
	register_elgg_event_handler("delete", "object", "file_tree_object_handler_delete");
	
	// register plugin hooks
	register_plugin_hook("permissions_check:metadata", "object", "file_tree_can_edit_metadata_hook");
	register_plugin_hook("access:collections:write", "all", "file_tree_write_acl_plugin_hook", 550);
	
	// register actions
	register_action("file_tree/edit", false, dirname(__FILE__) . "/actions/edit.php");
	register_action("file_tree/delete", false, dirname(__FILE__) . "/actions/delete.php");
	