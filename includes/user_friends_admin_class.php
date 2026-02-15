<?php
// plugin_class.php
if (!defined('e107_INIT')) { exit; }

class user_friends_admin_class 
{
public static function syncExtendedFields() 
{
    $sql = e107::getDb();
    $prefObj = e107::getPlugConfig('user_friends');
    $prefs = $prefObj->getPref();
    
    $fieldName = "plugin_user_friends_allow";

    // 1. Obter os dados atuais do campo na BD
    $existing = $sql->retrieve("user_extended_struct", "user_extended_struct_id, user_extended_struct_applicable", "user_extended_struct_name='{$fieldName}'");

    $current_applicable = ($existing) ? intval($existing['user_extended_struct_applicable']) : 253;
    $new_pref_val = intval($prefs['allow_users_disable']);

    if ($new_pref_val == 0) 
    {
        // Vais desativar? Faz backup do valor atual (se não for já 255)
        if($current_applicable != 255) {
            $prefObj->set('applicable_backup', $current_applicable);
            $prefObj->save();
        }
        $applicable = 255;
    } 
    else 
    {
        // Vais ativar? Recupera o backup. Se não houver backup, usa 253.
        $backup = $prefObj->getPref('applicable_backup');
        $applicable = ($backup) ? intval($backup) : 253;
        
        // Se por algum motivo o backup for 255, força para 253 para não ficar invisível
        if($applicable == 255) { $applicable = 253; }
    }

    // 1. Dados base que queres garantir que estão na BD (incluindo o dinâmico)
    $field_data = array(
        "user_extended_struct_name"       => $fieldName,
        "user_extended_struct_text"       => "Permitir Amigos",
        "user_extended_struct_type"       => 2,
        "user_extended_struct_parms"      => "plugin_user_friends^,^^,^^,^0^,^^,^Permitir pedidos de amizade de outros utilizadores.",
        "user_extended_struct_values"     => "Sim,Não",
        "user_extended_struct_default"    => "1",
        "user_extended_struct_read"       => 253,
        "user_extended_struct_write"      => 253,
        "user_extended_struct_required"   => intval($prefs['allow_users_disable']),
        "user_extended_struct_signup"     => 0,
        "user_extended_struct_applicable" => $applicable,
        "user_extended_struct_order"      => 0,
        "user_extended_struct_parent"     => 0,
    );

    if($existing) {
        $field_data['WHERE'] = "user_extended_struct_id = ".intval($existing['user_extended_struct_id']);
        $sql->update("user_extended_struct", $field_data, false);
    } else {
        $sql->insert("user_extended_struct", $field_data);
    }
}

}