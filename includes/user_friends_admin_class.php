<?php
// plugin_class.php
if (!defined('e107_INIT')) { exit; }

class user_friends_admin_class 
{
public static function syncExtendedFields() 
{
    $sql = e107::getDb();
    $prefs = e107::getPlugConfig('user_friends')->getPref();
    
    $fieldName = "plugin_user_friends_allow";

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
        "user_extended_struct_applicable" => 253,
        "user_extended_struct_order"      => 0,
        "user_extended_struct_parent"     => 0,
    );

    // 2. Verificar se o registo já existe
    $id = $sql->retrieve("user_extended_struct", "user_extended_struct_id", "user_extended_struct_name='{$fieldName}'");

    if($id) 
    {
        // No UPDATE, adicionamos a chave WHERE ao array completo
        $field_data['WHERE'] = "user_extended_struct_id = ".intval($id);
        
        // Assinatura correta e107 v2.3.3: update($table, $data_array, $use_tags)
        return $sql->update("user_extended_struct", $field_data, false);
    }
    
    // 3. No INSERT, usamos o array original (o motor ignora chaves inexistentes na tabela, mas o e107 é picuinhas, por isso garantimos que está limpo)
    return $sql->insert("user_extended_struct", $field_data);
}

}