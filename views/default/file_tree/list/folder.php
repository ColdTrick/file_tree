<?php 

	$folder = $vars["folder"];
	$delete_url = $vars["url"] . "action/file_tree/delete?folder_guid=" . $folder->getGUID();
	$edit_url = $vars["url"] . "pg/file_tree/edit/" . $folder->getGUID();
?>
<div class="contentWrapper" id="file_tree_list_folder">
	<?php if($folder->canEdit()){?>
	<div id="file_tree_list_folder_actions">
		<?php echo elgg_view("output/url", array("href" => $edit_url, "text" => elgg_echo("edit")));?> |
		<?php
			$js = "onclick=\"if(confirm('". elgg_echo('question:areyousure') . "')){ file_tree_remove_folder_files(this); return true;} else { return false; }\""; 
			echo elgg_view("output/url", array("href" => $delete_url, "text" => elgg_echo("delete"), "js" => $js, "is_action" => true));
		?>
	</div>
	<?php }?>
	<h3>
		<?php echo $folder->title;?>
	</h3>
	<div><?php echo $folder->description;?>
	</div>
</div>