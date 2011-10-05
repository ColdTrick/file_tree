<?php 

	$english = array(
		'file_tree' => "File Tree",
	
		// object name
		'item:object:folder' => "File Folder",
	
		// menu items
		'file_tree:menu:mine' => "Your folders",
		'file_tree:menu:user' => "%s's folders",
		'file_tree:menu:group' => "Group file folders",
		
		// group tool option
		'file_tree:group_tool_option:structure_management' => "Allow management of folders by members",
		
		// views
		// admin settings
		'file_tree:settings:replace_file' => "Replace My Files/Group Files with Folder view",	
	
		// object
		'file_tree:object:files' => "%s file(s) in this folder",
		'file_tree:object:no_files' => "No files in this folder",
	
		// input - folder select
		'file_tree:input:folder_select:main' => "Main folder",
	
		// list
		'file_tree:list:title' => "List file folders",
		
		'file_tree:list:folder:main' => "Main folder",
		'file_tree:list:files:none' => "No files found in this folder",
		
		'file_tree:list:tree:info' => "Did you know?",
		'file_tree:list:tree:info:1' => "You can drag and drop files on to the folders to organize them!",
		'file_tree:list:tree:info:2' => "You can double click on any folder to expand all of its subfolders!",
		'file_tree:list:tree:info:3' => "You can reorder folders by dragging them to their new place in the tree!",
		'file_tree:list:tree:info:4' => "You can move complete folder structures!",
		'file_tree:list:tree:info:5' => "If you delete a folder, you can optionally choose to delete all files!",
		'file_tree:list:tree:info:6' => "When you delete a folder, all subfolders will also be deleted!",
		'file_tree:list:tree:info:7' => "This message is random!",
		'file_tree:list:tree:info:8' => "When you remove a folder, but not it's files, the files will appear at the top level folder!",
		'file_tree:list:tree:info:9' => "A newly added folder can be placed directly in the correct subfolder!",
		'file_tree:list:tree:info:10' => "When uploading or editing a file you can choose in which folder it should appear!",
		'file_tree:list:tree:info:11' => "Dragging of files is only available in the list view, not in the gallery view!",
		'file_tree:list:tree:info:12' => "You can update the access level on all subfolders and even (optional) on all files when editing a folder!",
		
		// new/edit
		'file_tree:new:title' => "New file folder",
		'file_tree:edit:title' => "Edit file folder",
		'file_tree:forms:edit:title' => "Title",
		'file_tree:forms:edit:description' => "Description",
		'file_tree:forms:edit:parent' => "Select a parent folder",
		'file_tree:forms:edit:change_children_access' => "Update access on all subfolders",
		'file_tree:forms:edit:change_files_access' => "Update access on all files in this folder (and all subfolders if selected)",
		
		'file_tree:folder:delete:confirm_files' => "Do you also wish to delete all files in the removed (sub)folders",
	
		// actions
		// edit
		'file_tree:action:edit:error:input' => "Incorrect input to create/edit a file folder",
		'file_tree:action:edit:error:owner' => "Could not find the owner of the file folder",
		'file_tree:action:edit:error:folder' => "No folder to create/edit",
		'file_tree:action:edit:error:save' => "Unknown error occured while saving the file folder",
		'file_tree:action:edit:success' => "File folder successfully created/edited",
		
		// delete
		'file_tree:actions:delete:error:input' => "Invalid input to delete a file folder",
		'file_tree:actions:delete:error:entity' => "The given GUID could not be found",
		'file_tree:actions:delete:error:subtype' => "The given GUID is not a file folder",
		'file_tree:actions:delete:error:delete' => "An unknown error occured while deleting the file folder",
		'file_tree:actions:delete:success' => "The file folder was deleted successfully",
	);

	add_translation("en", $english);
	
	// widget translation
	$widget = array(
		'widgets:file_tree:title' => "File Folders",
		'widgets:file_tree:description' => "Showcase your File folders",
		
		'widgets:file_tree:edit:select' => "Select which folders to display",
		'widgets:file_tree:no_folders' => "No folders configured",
		'widgets:file_tree:more' => "More file folders",
	);
	
	add_translation("en", $widget);
?>