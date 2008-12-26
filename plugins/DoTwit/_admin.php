<?php
# ***** BEGIN LICENSE BLOCK *****
# This is Contact, a plugin for DotClear. 
# Copyright (c) 2007 Valentin VAN MEEUWEN - Exilius.net. All rights reserved.
#
# DoTwit is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
# 
# DoTwit is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with DotClear; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

$core->addBehavior('initWidgets',array('doTwitBehaviors','initWidgets'));

class doTwitBehaviors
{
	public static function initWidgets(&$widgets)
    {
		$widgets->create('dotwit',__('DoTwit'),array('DoTwit','dotwitWidget'));
		$widgets->dotwit->setting('title',__('Titre (facultatif):'),'');
		$widgets->dotwit->setting('idTwitter',__('Identifiant Twitter :'),'');
		$widgets->dotwit->setting('pwdTwitter',__('Mot de passe Twitter :'),'');
		$widgets->dotwit->setting('limit',__('Nombre de twit à afficher :'),1);
		$widgets->dotwit->setting('homeonly',__('Home page only'),1,'check');
		$widgets->dotwit->setting('timeline_friends',__('Timeline amis (Timeline perso par défaut)'),1,'check');
		$widgets->dotwit->setting('display_timeout',__('Affichage du temps écoulé'),1,'check');
		$widgets->dotwit->setting('display_profil_image',__('Affichage des profils image'),1,'check');
	}
}

?>