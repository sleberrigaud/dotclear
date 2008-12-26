<?php
# On lit la version du plugin
$m_version = $core->plugins->moduleInfo('DotFreeWidget','1');
 
# On lit la version du plugin dans la table des versions
$i_version = $core->getVersion('1');
 
# La version dans la table est supérieure ou égale à
# celle du module, on ne fait rien puisque celui-ci
# est installé
if (version_compare($i_version,$m_version,'>=')) {
	return;
}
 
# La procédure d'installation commence vraiment là
$core->setVersion('DoTwit',$m_version);
?>