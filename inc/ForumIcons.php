<?php
/*
ForumsIcons V1.0
(c) 2010 by Edson Ordaz
Website: http://www.MyBB-Es.com
*/

if (!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

$plugins->add_hook("admin_forum_menu", "ForumsIcons_admin_nav");
$plugins->add_hook("admin_forum_action_handler", "ForumsIcons_action_handler");
$plugins->add_hook("admin_load", "ForumsIcons_admin");

function ForumsIcons_info()
{
	return array(
		"name"		=> "ForumsIcons",
		"description"	=> "Adds icons to the forums",
		"website"		=> "http://www.mybb-es.com",
		"author"		=> "Edson Ordaz, updated by Dieter Gobbers (@Terran_ulm)",
		"authorsite"	=> "mailto:nicedo_eeos@hotmail.com",
		"version"		=> "1.3",
		"guid"			=> "f92dead66311a7197c5c0038f1bc6737",
		'compatibility' => '18*'
	);
}


function ForumsIcons_activate()
{
	global $mybb, $db,$cache;
	// $db->query("ALTER TABLE `".TABLE_PREFIX."forums` ADD `icon` VARCHAR(120) NOT NULL DEFAULT 'icon.gif' AFTER `defaultsortorder`");
	require "../inc/adminfunctions_templates.php";
	find_replace_templatesets("forumbit_depth1_cat", '#colspan="5"#', 'colspan="6"');
	find_replace_templatesets("forumbit_depth1_cat", '#<td class="tcat" colspan="2">#', '<td class="tcat" colspan="3">');
	find_replace_templatesets("forumbit_depth2_forum", '#'.preg_quote('id="mark_read_{$forum[\'fid\']}"></span></td>').'#', 'id="mark_read_{$forum[\'fid\']}"></span></td><td class="{$bgcolor}" align="center" valign="top" width="1" style="vertical-align: middle;"><img src="Forum_Icons/{$forum[\'icon\']}" alt="{$forum[\'name\']}" class="forumicon"/></td>');
	find_replace_templatesets("forumdisplay_subforums", '#colspan="5"#', 'colspan="6"');
	find_replace_templatesets("forumdisplay_subforums", '#<td class="tcat" width="2%">&nbsp;</td>#', '<td class="tcat" width="2%">&nbsp;</td><td class="tcat" width="2%">&nbsp;</td>');
	find_replace_templatesets("forumbit_depth2_cat", '#'.preg_quote('<td class="{$bgcolor}">').'#', '<td class="{$bgcolor}" align="center" valign="top" width="1" style="vertical-align: middle;"><img src="Forum_Icons/{$forum[\'icon\']}" alt="{$forum[\'name\']}" class="forumicon"/></td><td class="{$bgcolor}">');
	$cache->update_forums();
}

function ForumsIcons_deactivate()
{
	global $mybb, $db, $cache;

	require "../inc/adminfunctions_templates.php";
	find_replace_templatesets("forumbit_depth1_cat", '#'.preg_quote('<td class="thead{$expthead}" colspan="6">').'#', '<td class="thead{$expthead}" colspan="5">',0);
	find_replace_templatesets("forumbit_depth1_cat", '#'.preg_quote('<td class="tcat" colspan="3">').'#', '<td class="tcat" colspan="2">',0);
	find_replace_templatesets("forumbit_depth2_forum", '#'.preg_quote('<td class="{$bgcolor}" align="center" valign="top" width="1" style="vertical-align: middle;"><img src="Forum_Icons/{$forum[\'icon\']}" alt="{$forum[\'name\']}" class="forumicon"/></td>').'#', '',0);
	find_replace_templatesets("forumdisplay_subforums", '#'.preg_quote('colspan="6"').'#', 'colspan="5"',0);
	find_replace_templatesets("forumdisplay_subforums", '#'.preg_quote('<td class="tcat" width="2%">&nbsp;</td><td class="tcat" width="2%">&nbsp;</td>').'#', '<td class="tcat" width="2%">&nbsp;</td>',0);
	find_replace_templatesets("forumbit_depth2_cat", '#'.preg_quote('<td class="{$bgcolor}" align="center" valign="top" width="1" style="vertical-align: middle;"><img src="Forum_Icons/{$forum[\'icon\']}" alt="{$forum[\'name\']}" class="forumicon"/></td>').'#', '',0);
    // $db->query("ALTER TABLE ".TABLE_PREFIX."forums DROP `icon`");
	$cache->update_forums();
}

function ForumsIcons_install()
{
	global $db;
	$db->query("ALTER TABLE `".TABLE_PREFIX."forums` ADD `icon` VARCHAR(120) NOT NULL DEFAULT 'icon.gif' AFTER `defaultsortorder`");
}

function ForumsIcons_is_installed()
{
	global $db;
	if ($db->field_exists("icon", "forums"))
	{
		return true;
	}
	else
	{	
		return false;
	}
		
}

function ForumsIcons_uninstall()
{
	global $db;
	$db->query("ALTER TABLE ".TABLE_PREFIX."forums DROP `icon`");
}



function ForumsIcons_action_handler(&$action)
{
	$action['ForumIcons'] = array('active' => 'ForumIcons', 'file' => '');
}

function ForumsIcons_admin_nav(&$sub_menu)
{
	global $mybb, $lang;
		$lang->load("forum_icons", false, true);
		end($sub_menu);
		$key = (key($sub_menu))+10;
		
		if(!$key)
		{
			$key = '110';
		}
		
		$sub_menu[$key] = array('id' => $lang->url, 'title' => $lang->name, 'link' => "index.php?module=forum/".$lang->url);

}

function ForumsIcons_admin()
{
	global $mybb, $db, $page, $lang, $cache;

	if($page->active_action != $lang->url)
	{
		return;
	}
	$icon_dir = "Forum_Icons";
	$forum_cache = cache_forums();
	$img = "<img src=\"../".$icon_dir."/";
	$img_delete = "<img src=styles/sharepoint/images/icons/delete.gif> ";
	$img_edit = "<img src=styles/sharepoint/images/icons/success.gif> ";
	$page->add_breadcrumb_item($lang->name);
	$page->output_header($lang->name);

if($mybb->input['action'] == "edit") {
	$form = new Form("index.php?module=forum/".$lang->url."&amp;action=save", "post", "save",1);
	echo $form->generate_hidden_field("fid", $mybb->input['fid']);
	$form_container = new FormContainer("Erstelle ein Icon f&uuml;r Forum: ".$forum_cache[$mybb->input['fid']]['name']);

	$form_container->output_row($lang->icon, $lang->icon_des, $form->generate_file_upload_box("upload_icon", array('style' => 'width: 330px;')), 'file');
	$form_container->output_row($lang->used_icon, $lang->used_des, "{$img}".$forum_cache[$mybb->input['fid']]['icon']."\" >", 'icon');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->submit);
	$form->output_submit_wrapper($buttons);
	$form->end();
	$page->output_footer();
}
if($mybb->input['action'] == "save")
{
	$dirpath = MYBB_ROOT."Forum_Icons";
	$file_type = $_FILES['upload_icon']['type'];
	switch(strtolower($file_type))
	{
		case "image/gif":
		case "image/jpeg":
		case "image/x-jpg":
		case "image/x-jpeg":
		case "image/pjpeg":
		case "image/jpg":
		case "image/png":
		case "image/x-png":
			$typeicon =  1;
			break;
		default:
			$typeicon = 0;
	}

	if($typeicon == 0)
	{

		flash_message($lang->no_file, 'error');
		admin_redirect("index.php?module=forum/".$lang->url."&amp;action=edit&amp;fid=".intval($mybb->input['fid']));

	}

        if ($_FILES['upload_icon']['error'] == '0')
        {
                $icono_image = $_FILES['upload_icon']['tmp_name'];
                $newfile = $dirpath . "/" . $_FILES['upload_icon']['name'];
                if (!copy($icono_image, $newfile))
                {

				flash_message($lang->no_file_again, 'error');
				admin_redirect("index.php?module=forum/".$lang->url."&amp;action=edit&amp;fid=".intval($mybb->input['fid']));	
                }

					$update = array( 
						"icon" => $_FILES['upload_icon']['name']
					); 
					$db->update_query("forums", $update, "fid='".$db->escape_string($mybb->input['fid'])."'");

					$cache->update_forums();

					flash_message($lang->file_success, 'success');
					admin_redirect("index.php?module=forum/".$lang->url."");

        }else{

		flash_message($lang->no_file_again, 'error');
		admin_redirect("index.php?module=forum/".$lang->url."&amp;action=edit&amp;fid=".intval($mybb->input['fid']));


	}


}
if($mybb->input['action'] == "delete")
	{
		$query = $db->simple_select("forums", "*", "fid='".intval($mybb->input['fid'])."'");
		$forum = $db->fetch_array($query);

		if(!$forum['fid'])
		{
			flash_message("Error", 'error');
			admin_redirect("index.php?module=forum/".$lang->url);
		}

		// User clicked no
		if($mybb->input['no'])
		{
			admin_redirect("index.php?module=forum/".$lang->url);
		}

		if($mybb->request_method == "post")
		{
			$db->query("UPDATE mybb_forums set icon='' where fid='{$forum['fid']}'");
			$cache->update_forums();
			flash_message($lang->saved, 'success');
			admin_redirect("index.php?module=forum/".$lang->url);
		}
		else
		{
			$page->output_confirm_action("index.php?module=forum/".$lang->url);
		}
	}
		$table = new Table;
		$table->construct_header($lang->forums, array("width" => "50%"));
		$table->construct_header($lang->fid, array("class" => "align_center", "width" => "5%"));
		$table->construct_header($lang->forum_icon, array("class" => "align_center", "width" => "15%"));
		$table->construct_header($lang->controls, array("class" => "align_center", "colspan" => 2, "width" => "20%"));
		$table->construct_row();

		foreach($forum_cache as $forum)
		{
			if($forum['type'] != "c")
			{
				$table->construct_cell("<b>".$forum['name']."</b>");
				$table->construct_cell($forum['fid'], array("class" => "align_center"));
				$table->construct_cell("{$img}".$forum['icon']."\" >", array("class" => "align_center"));
				$table->construct_cell("<a href=\"index.php?module=forum/".$lang->url."&amp;action=edit&amp;fid={$forum['fid']}\">{$img_edit}".$lang->edit."</a>", array("class" => "align_center"));
				$table->construct_cell("<a href=\"index.php?module=forum/".$lang->url."&amp;action=delete&amp;fid={$forum['fid']}&amp;my_post_key={$mybb->post_code}\" onclick=\"return AdminCP.deleteConfirmation(this, '{$lang->delete_onclick}')\">{$img_delete}".$lang->delete."</a>", array("class" => "align_center"));
			}
		$table->construct_row();

		}
		$table->output($lang->forums);
		$page->output_footer();

}
?>
