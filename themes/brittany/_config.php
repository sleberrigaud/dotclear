<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear.
# Copyright (c) 2003-2006 Olivier Meunier and contributors. All rights
# reserved.
#
# DotClear is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
# 
# DotClear is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with DotClear; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/main');
$css_file = path::real($core->blog->themes_path).'/'.$core->blog->settings->theme.'/style.css';

if (!is_file($css_file) && !is_writable(dirname($css_file))) {
	throw new Exception(
		sprintf(__('File %s does not exist and directory %s is not writable.'),
		$css_file,dirname($css_file))
	);
}

if (isset($_POST['css']))
{
	@$fp = fopen($css_file,'wb');
	fwrite($fp,$_POST['css']);
	fclose($fp);
	
	echo
	'<div class="message"><p>'.
	__('Style sheet upgraded.').
	'</p></div>';
}

$css_content = is_file($css_file) ? file_get_contents($css_file) : '';

echo
'<p class="area"><label>'.__('Style sheet:').' '.
form::textarea('css',60,20,html::escapeHTML($css_content)).'</label></p>';
?>