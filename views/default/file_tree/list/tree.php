<?php 

	$folders = $vars["folders"]; 
	$folder = $vars["folder"];
	
	$selected_id = "file_tree_list_tree_main";
	if($folder instanceof ElggObject){
		$selected_id = $folder->guid;
	}
	
	$page_owner = page_owner_entity();
?>
<script type="text/javascript">
	$(function () {

		var requested_id = window.location.hash.substring(1);
		
		$("#file_tree_list_tree").tree({ 
			
			"ui": {
				"theme_name": "classic"
			},
			"rules": {
				"multiple": false,
				"drag_copy": false,
				// only nodes of type root can be top level nodes
				"valid_children" : [ "root" ] 
			},
			"callback": {
				"onload" : function (TREE_OBJ) { 
					if(requested_id && ($("#" + requested_id).length > 0)){
						TREE_OBJ.select_branch($("#" + requested_id));
						TREE_OBJ.open_branch($("#" + requested_id));
					} else {
						TREE_OBJ.select_branch($("#<?php echo $selected_id; ?>"));
						TREE_OBJ.open_branch($("#<?php echo $selected_id; ?>"));
					}						 
				},
				"ondblclk": function(NODE, TREE_OBJ) {
				 	 TREE_OBJ.open_branch(NODE);
					 TREE_OBJ.open_all(NODE);
				},	
				"onmove": function (NODE, REF_NODE, TYPE, TREE_OBJ, RB){
					var folder_guid = TREE_OBJ.get_node(NODE).find("a").attr("id");
					var parent_guid = TREE_OBJ.parent(NODE).find("a:first").attr("id");
					var order = TREE_OBJ.parent(NODE).children("ul").children("li").children("a").makeDelimitedList("id");
					
					file_tree_reorder(folder_guid, parent_guid, order);
				},
				"onselect": function(NODE, TREE_OBJ){
					var folder_guid = TREE_OBJ.get_node(NODE).find("a").attr("id");
					if(folder_guid){
						file_tree_load_folder(folder_guid);
						if(folder_guid > 0){
							window.location.hash = folder_guid;
						} else {
							window.location.hash = "#";
						}
					}
				}
			},
			types : {
				// all node types inherit the "default" node type
				"default" : {
					deletable : false,
					renameable : false
					<?php if(!($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember() && $page_owner->file_tree_structure_management_enable != "no"))){ ?>
					,draggable : false
					<?php } ?>
				},
				"root" : {
					draggable : false
				}
			}
		});
		<?php if($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember())){ ?>
		$("#file_tree_list_tree a").droppable({
			"accept": ".search_listing",
			"hoverClass": "ui-state-hover",
			"tolerance": "pointer",
			"drop": function(event, ui) {
	
				var file_move_url = "<?php echo $vars["url"];?>pg/file_tree/file_move";
				var file_guid = $(ui.draggable).prev("input").val();
				var folder_guid = $(this).attr("id");
				var selected_folder_guid = $("#file_tree_list_tree a.clicked").attr("id");
				var overlay_width = $(ui.draggable).outerWidth();
				var margin_left = $(ui.draggable).css("margin-left");
				
				$(ui.draggable).hide();
				
				$("#file_tree_list_files_overlay").css("width", overlay_width).css("left", margin_left).show();
				$.post(file_move_url, {"file_guid": file_guid, "folder_guid": folder_guid}, function(data){
					file_tree_load_folder(selected_folder_guid);
				});
			},
			"greedy": true
		});
		<?php } ?>
	});

</script>

<div class="contentWrapper" id="file_tree_list_tree_container">
	<div id="file_tree_list_tree">
		<ul>
			<li id="file_tree_list_tree_main" rel="root">
				<a id="0" href="#"><?php echo elgg_echo("file_tree:list:folder:main"); ?></a>
			
			<?php 
				echo file_tree_display_folders($folders);
			?>
		
			</li>
		</ul>
	</div>
	<div class="clearfloat"></div>	
	
	<?php 
		
		if($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember() && $page_owner->file_tree_structure_management_enable != "no")){ ?>
	<div>
		<?php
			$js = "onclick='file_tree_add_folder()'"; 
			echo elgg_view("input/button", array("value" => elgg_echo("file_tree:new:title"), "js" => $js)); 
		?>
	</div>
	<?php } ?>
</div>

<?php if(isloggedin()){ ?>
<div class="contentWrapper">
	<div id="file_tree_list_tree_info">
		<div><?php echo elgg_echo("file_tree:list:tree:info"); ?></div>
		<?php 
			echo elgg_echo("file_tree:list:tree:info:" . rand(1,12));
		?>
	</div>
</div>
<?php } ?>
