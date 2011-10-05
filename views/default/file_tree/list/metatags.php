<?php ?>
<script src="<?php echo $vars["url"]; ?>mod/file_tree/vendors/jstree/jquery.tree.min.js"></script>
<script type="text/javascript">
	function file_tree_add_folder(){
		var parent_guid = $("#file_tree_list_tree a.clicked").attr("id");
		var forward_url = "<?php echo $vars["url"]; ?>pg/file_tree/new/<?php echo page_owner();?>"

		if(parent_guid){
			forward_url = forward_url + "?parent_guid=" + parent_guid;
		}
		
		document.location.href = forward_url;
	}

	function file_tree_reorder(folder_guid, parent_guid, order){
		var reorder_url = "<?php echo $vars["url"];?>pg/file_tree/reorder";
		$.post(reorder_url, {"folder_guid": folder_guid, "parent_guid": parent_guid, "order": order});		
	}

	function file_tree_load_folder(folder_guid){
		var folder_url = "<?php echo $vars["url"];?>pg/file_tree/list/<?php echo page_owner();?>?folder_guid=" + folder_guid + "&search_viewtype=<?php echo get_input("search_viewtype", "list"); ?>";
		$("#file_tree_list_files_container").load(folder_url);
	}	

	function file_tree_remove_folder_files(link){
		if(confirm("<?php echo elgg_echo("file_tree:folder:delete:confirm_files");?>")){
			var cur_href = $(link).attr("href"); 
			$(link).attr("href", cur_href + "&files=yes");
		}
		return true;
	}
</script>