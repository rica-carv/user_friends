<?php
if (defined('ADMIN_AREA')) {
 // No teu e_module.php
//$currentLan = e107::getLanguage(); // A língua ativa no site agora

// Se a língua guardada nas tuas prefs for diferente da atual do site...
//if ($prefObj->getPref('last_synced_lan') !== $currentLan) 
//{
        $existinglan = $sql->retrieve("user_extended_struct", "user_extended_struct_text", "user_extended_struct_name=plugin_user_friends_allow_requests");
       e107::lan('user_friends', 'front', true);
        if ($existinglan <> LAN_USERFRIEND_20) {
    require_once(e_PLUGIN . "user_friends/includes/user_friends_admin_class.php");
    
    // 1. Marretamos a BD com os novos textos da língua atual
    user_friends_admin_class::syncExtendedFields();
    
            }
//}   // Código específico para a área de administração
}
//var_dump (e_PAGE);
/*
if (e_PAGE === "usersettings.php"){
            $prefs = e107::getPlugPref('user_friends');
        if(empty($prefs['allow_frontend_add']))
{
      e107::lan('user_friends', 'front', true);
    e107::getMessage()->addWarning(    LAN_USERFRIEND_16    );
}
}
*/