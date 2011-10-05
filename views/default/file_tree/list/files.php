<?php 
	$files = $vars["files"];
	$folder = $vars["folder"];
	
	if(!empty($folder)){
		$folder_content = elgg_view("file_tree/list/folder", array("folder" => $folder));
	}
	
	if(!empty($files)){
		$old_context = get_context();
		set_context('search');
		//$files_content = elgg_view_entity_list($files, count($files), 0, 0, false, true, false);
		
		$url = parse_url($_SERVER['REQUEST_URI']);
		$baseurl = $url["path"];
		if(!empty($folder)){
			$baseurl .= "/" . $folder->guid;
		}
		
		$files_content = elgg_view('entities/entity_list',array(
      		'entities' => $files,
			'count' => count($files),
			'offset' => 0,
			'limit' => 0,
			'baseurl' => $baseurl,
			'fullview' => false,
			'context' => get_context(),
			'viewtypetoggle' => true,
			'viewtype' => get_input('search_viewtype','list'),
			'pagination' => false
		));
		
		set_context($old_context);
	} else {
		$files_content = elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("file_tree:list:files:none")));
	}
	
	
?>
<div id="file_tree_list_files">
	<div id="file_tree_list_files_overlay"></div>
	<?php echo $folder_content; ?>
	<?php echo $files_content; ?>
</div>
<?php if(page_owner_entity()->canEdit() || (page_owner_entity() instanceof ElggGroup && page_owner_entity()->isMember())){?>
<script type="text/javascript">

	$(function(){
		$("#file_tree_list_files .search_listing").draggable({
			"revert": "invalid",
			"opacity": 0.7,
			"cursor": "move"
		}).css("cursor", "move");
	});

</script>
<?php } ?>