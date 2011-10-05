<?php ?>

#file_tree_edit_form_access_extra label {
	font-size: 100%;
    font-weight: normal;
}

#file_tree_list_folder p{
	margin: 0px;
}

#file_tree_list_folder_actions {
	float: right;
} 

#file_tree_list_tree_container {	
	overflow: auto;
}

#file_tree_list_tree_info {
	color: grey;
}

#file_tree_list_tree_info>div {
	background: url(<?php echo $vars["url"]; ?>_graphics/icon_customise_info.gif) top left no-repeat;
	padding-left: 16px; 
	color: #333333;
	font-weight: bold;
}

/* loading overlay */

#file_tree_list_files {
	position: relative;
	
}

#file_tree_list_files_overlay {
	display: none;
	background: white;
	height: 100%;
	position: absolute;
	opacity: 0.6;
	filter: alpha(opacity=60);
	z-index: 100;
	background: url("<?php echo $vars["url"]; ?>_graphics/ajax_loader.gif") no-repeat scroll center center white;
	padding: auto;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}

/* breadcrumb */

#file_tree_breadcrumbs_file_title {
	float: right;
}

#file_tree_breadcrumbs ul{
	border: 1px solid #DEDEDE;
    height: 2.3em;
}

#file_tree_breadcrumbs ul,
#file_tree_breadcrumbs li {
	list-style-type:none;
	padding:0;
	margin:0
}

#file_tree_breadcrumbs li {
	float:left;
	line-height:2.3em;
	padding-left:.75em;
	color:#777;
}
#file_tree_breadcrumbs li a {
	display:block;
	padding:0 15px 0 0;
	background:url(<?php echo $vars["url"]; ?>mod/file_tree/_graphics/crumbs.gif) no-repeat right center;
}

#file_tree_breadcrumbs li a:link, 
#file_tree_breadcrumbs li a:visited {
	text-decoration:none;
   	color:#777;
}

#file_tree_breadcrumbs li a:hover,
#file_tree_breadcrumbs li a:focus {
	color:#333;
}


/* extending file tree classic theme */

#file_tree_list_tree.tree li {
	line-height: 20px;
}
 
#file_tree_list_tree.tree li span {
	padding: 1px 0px;
}

#file_tree_list_tree.tree-classic li a {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border: 1px solid transparent;	
}

#file_tree_list_tree.tree-classic li a:hover {
	border: 1px solid #CCCCCC;
}

#file_tree_list_tree.tree-classic li a.clicked {
	background: #DEDEDE;
    border: 1px solid #CCCCCC;
    color: #999999;
}

#file_tree_list_tree.tree-classic li a.clicked:hover {
	background: #CCCCCC;
    border: 1px solid #CCCCCC;
    color: white;
}

#file_tree_list_tree.tree-classic li a.ui-state-hover{
	background: #0054A7;
	border: 1px solid #0054A7;	
	color: white;
}

/* **************************
File tree widget
**************************** */
.file_tree_widget_edit_folder_wrapper ul {
	list-style: none outside none;
	margin: 0;
	padding: 0;
}

.file_tree_widget_edit_folder_wrapper ul ul {
	padding-left: 10px;
}

.file_tree_widget_edit_folder_wrapper li {

}