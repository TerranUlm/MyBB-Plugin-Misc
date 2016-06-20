<?php
/*****************************************\
#										 #
# 		Jsnippets For MyBB plugin 		 #
#  		   Author: Jitendra M			 #
#  	  Copyright: © 2010-2011 Audentio	 #
# 										 #
#	    Website: http://audentio.com	 #
#  			License: license.txt		 #
# 		 		Version 1.0				 #
#										 #
\*****************************************/


if(!defined("IN_MYBB"))
{
	die("This file cannot be accessed directly.");
}
// add hooks
$plugins->add_hook("admin_style_menu", "jsnips_admin_nav");
$plugins->add_hook("admin_style_action_handler", "jsnips_action_handler");
$plugins->add_hook("global_start", "jsnips_show_snips");

function jsnips_info()
{
	return array(
		"name"			=> "jSnippets For MyBB",
		"description"	=> "Allow Snippets to be added and Managed Effectively",
		"website"		=> "http://audentio.com",
		"author"		=> "Jitendra M",
		"authorsite"	=> "http://audentio.com",
		"version"		=> "1.00",
		"guid" 			=> "",
		"compatibility"	=> "18*"
	);
}
function jsnips_install()
{
	global $mybb, $db;
	
	$db->write_query("CREATE TABLE ".TABLE_PREFIX."jsnips (".
				"jid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL DEFAULT NULL, ".
				"name VARCHAR(255), ".
				"descp TEXT, ".
				"type varchar(3),".
				"content TEXT, ".
				"attached_to VARCHAR(100), ".
				"disp_order INT(3) UNSIGNED NOT NULL DEFAULT 0, ".
				"version varchar(10) DEFAULT 0, ".
				"status INT(1) UNSIGNED NOT NULL DEFAULT 1)");
	// create settings group
	$insertarray = array(
		'name' => 'jsnips', 
		'title' => 'jSnippet Settings', 
		'description' => "Settings for Jsnippet", 
		'disporder' => 100, 
		'isdefault' => 0
	);
	$gid = $db->insert_query("settinggroups", $insertarray);
	// add settings
	$settings_array = array(
		"name"           => "jsnips_dir",
		"title"          => "Jsnips Directory",
		"description"    => "Directory where Jsnip files are uploaded to relative to the Index of the forum.No trailing Slash.",
		"optionscode"    => "text",
		"value"          => 'jscripts/jsnips',
		"disporder"      => '1',
		"gid"            => intval($gid),
	);
	$db->insert_query("settings", $settings_array);
	
	$settings_array = array(
		"name"           => "jsnips_file_h",
		"title"          => "Jsnips File Handling",
		"description"    => "How are file type jsnippets handled.(Added to main file OR linked seperately)",
		"optionscode"    => "radio
added=Added to single file
linked=Linked Seperatly.",
		"value"          => "linked",
		"disporder"      => '2',
		"gid"            => intval($gid),
	);

	$db->insert_query("settings", $settings_array);
	
	// add settings
	$settings_array = array(
		"name"           => "jsnips_comments",
		"title"          => "Comments For Jsnips",
		"description"    => "Do you want to show comments for the seperate jsnips.",
		"optionscode"    => "yesno",
		"value"          => 1,
		"disporder"      => '3',
		"gid"            => intval($gid),
	);
	$db->insert_query("settings", $settings_array);
	
	$master_array = array(
		"jid"			=> "1",
		"name"			=> "jQuery",
		"descp"			=> "The write less, do more javascript library.",
		"content"		=> "https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js",
		"type"			=> "0,3",
		"attached_to"	=> "0",
		"disp_order"	=> "0",
		"status"		=> "1"
	);
	$db->insert_query("jsnips", $master_array);	
	
	//setup our default arrays
	$easing = <<<EOF
/*
* jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
*
*/
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});
EOF;
	$cookie = <<<EOF
/**
* Cookie plugin
*
* Copyright (c) 2006 Klaus Hartl (stilbuero.de)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*
*/
jQuery.cookie=function(name,value,options){if(typeof value!="undefined"){options=options||{};if(value===null){value="";options.expires=-1;}var expires="";if(options.expires&&(typeof options.expires=="number"||options.expires.toUTCString)){var date;if(typeof options.expires=="number"){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;}expires="; expires="+date.toUTCString();}var path=options.path?"; path="+(options.path):"";var domain=options.domain?"; domain="+(options.domain):"";var secure=options.secure?"; secure":"";document.cookie=[name,"=",encodeURIComponent(value),expires,path,domain,secure].join("");}else{var cookieValue=null;if(document.cookie&&document.cookie!=""){var cookies=document.cookie.split(";");for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+"=")){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}}return cookieValue;}};
EOF;
	$fancy_collapse = <<<EOF
/*
* fancyCollapses 1.0 - jSnippet by Jorge Lainfiesta
* Copyright 2010 Audentio Design
* http://audentio.com/
*
*/

expandables=null;(function(a){if(jQuery.cookie){jQuery(document).ready(function(){var b="fancyCollapses_collapsed_elmnts",g="|",h=",";var c=a.cookie(b);if(c!=""&&c!=null){var l=c.split(g);for(i=0;i<l.length;i++){var j=l[i].split(h);if(typeof j[0]!="undefined"&&j[0]!=""){if(j[2]=="m"){var d=a(j[1]).attr("src");if(typeof d!="undefined"){d=d.replace("collapse","collapse_collapsed");a(j[1]).attr("src",d);a(j[1]).css("cursor","pointer");var e=a(j[0]);var k=e.closest("table").attr("cellpadding");var f=e.closest("table").attr("cellspacing");e.closest("table").attr("cellspacing","0");e.addClass("fancyCollapses_modded").wrapInner("<tr><td style='padding: 0; margin: 0; width: 100%'><div><table width='100%' border='0' cellspacing='"+f+"' cellpadding='"+k+"'></table></div></td></tr>");e.children().children().children().hide()}}else{a(j[0]).hide()}a(j[1]).addClass("fancyCollapses_collapser_collapsed");a(j[0]).addClass("fancyCollapses_collapsed")}}}})}a.fn.fancyCollapses=function(b){var c=a.extend({},a.fn.fancyCollapses.defaults,b);return this.each(function(){var f="fancyCollapses_collapsed_elmnts",k="|",l=",";function s(o,v,x){var w=a.cookie(f);if(w==null){w=""}var u=w+k+o+l+v+l+x;a.cookie(f,u)}function t(o,u,w){var v=a.cookie(f);var x=k+o+l+u+l+w;v=v.replace(x,"");a.cookie(f,v)}var m=a(this);var e=a.meta?a.extend({},c,m.data()):c;var r="",p="",g,q,d=false;r=m.attr("id");if(e.collapser){p=e.collapser}if(!r){if(!e.collapser){var h=new Error();h.name="fancyCollapses error";h.message="No collapser specified";throw (h)}g=m}else{d=true;r="#"+r;g=a(r);if(e.isTable){p=r.replace("_e","_img");if(!g.hasClass("fancyCollapses_modded")){var n=g.closest("table").attr("cellpadding");var j=g.closest("table").attr("cellspacing");g.closest("table").attr("cellspacing","0");g.wrapInner("<tr><td style='padding: 0; margin: 0; width: 100%'><div><table width='100%' border='0' cellspacing='"+j+"' cellpadding='"+n+"'></table></div></td></tr>")}g=a(r).children().children().children()}else{if(!e.collapser){p=r+"_btn"}}}q=a(p);q.css("cursor","pointer");g.width("100%");q.click(function(){if(!q.hasClass("fancyCollapses_collapser_collapsed")){g.slideUp(e.speed,e.easing);if(e.isTable){var o=q.attr("src");o=o.replace("collapse","collapse_collapsed");q.attr("src",o)}q.addClass("fancyCollapses_collapser_collapsed");g.addClass("fancyCollapses_collapsed");if(d){if(jQuery.cookie){var u="n";if(e.isTable){u="m"}s(r,p,u)}}}else{g.slideDown(e.speed,e.easing);if(e.isTable){var o=q.attr("src");o=o.replace("_collapsed","");q.attr("src",o)}q.removeClass("fancyCollapses_collapser_collapsed");g.removeClass("fancyCollapses_collapsed");if(d){if(jQuery.cookie){var u="n";if(e.isTable){u="m"}t(r,p,u)}}}})})};a.fn.fancyCollapses.defaults={easing:"linear",speed:"normal",collapser:"",isTable:true}})(jQuery);
EOF;
	$trigger = <<<EOF
	jQuery.noConflict();
jQuery(document).ready(function($){       
    
    //You can place all your jQuery instructions here, it won't have conflicts with Prototype.
    
    //Example of a snippet usage
    if(jQuery().fancyCollapses){
        $(".tborder  tbody[id$='_e']").fancyCollapses({easing: "easeInOutQuart", speed: "normal"});
    }
});
EOF;
	$insert_array = array(
		"jid"			=> "2",
		"name"			=> "jQuery Easing",
		"descp"			=> "Expands the available easing options for your animations. <br />This is a jQuery plugin by <a href=\"http://gsgd.co.uk/sandbox/jquery/easing/\">GSGD</a> (follow the link for more info).",
		"content"		=> $db->escape_string($easing),
		"type"			=> "1",
		"attached_to"	=> "0",
		"disp_order"	=> "1",
		"status"		=> "1"
	);
	$db->insert_query("jsnips", $insert_array);	
	$insert_array = array(
		"jid"			=> "3",
		"name"			=> "jQuery Cookie",
		"descp"			=> "Makes it easy to handle cookies with jQuery.",
		"content"		=> $db->escape_string($cookie),
		"type"			=> "1",
		"attached_to"	=> "0",
		"disp_order"	=> "2",
		"status"		=> "1"
	);
	$db->insert_query("jsnips", $insert_array);	
	$insert_array = array(
		"jid"			=> "4",
		"name"			=> "Fancy Collapse",
		"descp"			=> "Magically spice up your collapses with smooth animations.",
		"content"		=> $db->escape_string($fancy_collapse),
		"type"			=> "1",
		"attached_to"	=> "0",
		"disp_order"	=> "3",
		"status"		=> "1"
	);	
	$db->insert_query("jsnips", $insert_array);	
	$insert_array = array(
		"jid"			=> "5",
		"name"			=> "General Instructions",
		"descp"			=> "You can trigger all snippets and control how they behave here.",
		"content"		=> $db->escape_string($trigger),
		"type"			=> "1",
		"attached_to"	=> "0",
		"disp_order"	=> "4",
		"status"		=> "1"
	);	
	$db->insert_query("jsnips", $insert_array);	
	rebuild_settings();
}
function jsnips_is_installed()
 {
	global $db;
	
	if($db->table_exists("jsnips"))
	{
		return true;
	}
    return false;
}
	
function jsnips_activate()
{
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("headerinclude", '#{\$stylesheets}#', "{\$stylesheets}\n{\$retrn['master_snippet']}\n{\$retrn['sniplist']}\n{\$retrn['last_snippet']}");
}
function jsnips_deactivate()
{
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("headerinclude", '#\n'.preg_quote('{$retrn[\'master_snippet\']}').'#', "");
	find_replace_templatesets("headerinclude", '#\n'.preg_quote('{$retrn[\'sniplist\']}').'#', "");
	find_replace_templatesets("headerinclude", '#\n'.preg_quote('{$retrn[\'last_snippet\']}').'#', "");
}
function jsnips_uninstall()
{
	global $db, $mybb;
	// delete settings group
	$db->delete_query("settinggroups", "name = 'jsnips'");
	// remove settings
	$db->delete_query("settings", "name = 'jsnips_dir'");
	$db->delete_query("settings", "name = 'jsnips_file_h'");
	$db->delete_query("settings", "name = 'jsnips_comments'");
	// remove templates
	$db->write_query("DROP TABLE ".TABLE_PREFIX."jsnips");
	rebuild_settings();
}
function jsnips_action_handler(&$action)
{
	$action['jsnips'] = array('active' => 'jsnips', 'file' => 'jsnips.php');
}
function jsnips_admin_nav(&$sub_menu)
{
	global $mybb;
	
		end($sub_menu);
		$key = (key($sub_menu))+10;
		if(!$key)
		{
			$key = '30';
		}
		$sub_menu[$key] = array('id' => 'jsnips', 'title' => "jSnippets", 'link' => "index.php?module=style-jsnips");
}
function jsnips_show_snips()
{
	global $mybb, $db, $retrn;

	$retrn['sniplist'] .= "<script type=\"text/javascript\" src=\"{$mybb->settings['bburl']}/jsnips.php\" ></script>\n";
	$condition = "type = 3";
	if($mybb->settings['jsnips_file_h'] != "added")
	{
		$condition = "type <> '1'";
	}
	$query = $db->simple_select("jsnips", "content, type", " status = '1' AND $condition", array("order_by" => "disp_order"));
	while($snips = $db->fetch_array($query))
	{                       
		$type = explode(",", $snips['type']);
		if(count($type) < 2)
		{
			$type[0] = $snips['type'];
		}
		if($type[0] == 0) //masterfile
		{
			switch($type[1])
			{
				case 1:
				$retrn['master_snippet'] = "<script type=\"text/javascript\">\n<!--\n".$snips['content']."\n // -->\n</script>";
				break;
				case 2:
				case 3:
				$retrn['master_snippet'] = "<script type=\"text/javascript\" src=\"".$snips['content']."\"></script>";
				break;
			}	
		}
		elseif($type[0] == 4) //lastfile
		{
			switch($type[1])
			{
				case 1:
				$retrn['last_snippet'] = "<script type=\"text/javascript\">\n<!--\n".$snips['content']."\n // -->\n</script>";
				break;
				case 2:
				case 3:
				$retrn['last_snippet'] = "<script type=\"text/javascript\" src=\"".$snips['content']."\"></script>";
				break;
			}	
		}		
		else
		{
			$dir = "";
			$type = "remote";
			if($type[0] == 2) //localfile
			{
				$dir = $mybb->settings['jsnips_dir']."/";
				$type = "local";
			}
			$allowed_themes = explode(",", $snips['attached_to']);
			if($snips['attached_to'] == 0 || in_array($theme['tid'], $allowed_themes))
			{
				$retrn['sniplist'] .= "<script type=\"text/javascript\" src=\"".$dir.$snips['content']."\" /></script>\n";
			}
		}   
	}
	
}
?>
