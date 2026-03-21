<?php
// plugin_class.php
if (!defined('e107_INIT')) { exit; }
//var_dump("USER FRIENDS CLASS ADMIN CARREGADO");
class user_friends_admin_class 
{
public static function syncExtendedFields() 
{
    $sql = e107::getDb();
    $prefObj = e107::getPlugConfig('user_friends');
    $prefs = $prefObj->getPref();

    // ----------------------------
    // 1️⃣ Determinar aplicável (lógica existente)
    // ----------------------------
    $existing = $sql->retrieve(
        "user_extended_struct",
        "user_extended_struct_id, user_extended_struct_applicable",
        "user_extended_struct_name='plugin_user_friends_allow_requests'"
    );

    $current_applicable = ($existing) ? intval($existing['user_extended_struct_applicable']) : 253;
    $new_pref_val = intval($prefs['allow_users_disable']);

    if ($new_pref_val == 0) 
    {
        if($current_applicable != 255) {
            $prefObj->set('applicable_backup', $current_applicable);
            $prefObj->save();
        }
        $applicable = 255;
    } 
    else 
    {
        $backup = $prefObj->getPref('applicable_backup');
        $applicable = ($backup) ? intval($backup) : 253;
        if($applicable == 255) { $applicable = 253; }
    }

    e107::lan('user_friends', 'front', true);

    // ----------------------------
    // 2️⃣ Categoria do plugin
    // ----------------------------
    $catName = LAN_USERFRIEND_26;
    $catID = $sql->retrieve(
        "user_extended_struct", 
        "user_extended_struct_id", 
        "user_extended_struct_name='{$catName}'"
    );

    if (!$catID) {
        $catID = $sql->insert("user_extended_struct", [
            "user_extended_struct_name" => $catName,
            "user_extended_struct_type" => 0
        ]);
    }

    // ----------------------------
    // 3️⃣ Campos do plugin
    // ----------------------------
    $fields = [
        'allow_requests' => [
            'text'       => LAN_USERFRIEND_20,
            'type'       => 2,
            'values'     => LAN_YES.','.LAN_NO,
            'default'    => "1",
            'required'   => intval($prefs['allow_users_disable']),
            'applicable' => $applicable,
            'order'      => 1
        ],
        'auto_accept' => [
            'text'    => LAN_USERFRIEND_21,
            'type'    => 2,
            'values'  => LAN_YES.','.LAN_NO,
            'default' => "1",
            'order'   => 2
        ],
        'notify_mode' => [
            'text'    => LAN_USERFRIEND_22."<br><small>(".LAN_USERFRIEND_23.")</small>",
            'type'    => 10,
            'values'  => LAN_USERFRIEND_28.','.LAN_USERFRIEND_29,
            'default' => null,
            'order'   => 3
        ],
        'visibility' => [
            'text'    => LAN_USERFRIEND_24,
            'type'    => 2,
            'values'  => LAN_USERFRIEND_25.','.LAN_USERFRIEND_26.','.LAN_USERFRIEND_27,
            'default' => LAN_USERFRIEND_25,
            'order'   => 4
        ]
    ];

    // ----------------------------
    // 4️⃣ Atualizar ou criar campos
    // ----------------------------
    foreach ($fields as $shortName => $data) 
    {
        $fieldName = "plugin_user_friends_" . $shortName;

        // Verificar se o campo já existe pelo name
        $existingField = $sql->retrieve(
            "user_extended_struct", 
            "user_extended_struct_id", 
            "user_extended_struct_name='{$fieldName}'"
        );

        $fieldData = [
            "user_extended_struct_name"       => $fieldName,
            "user_extended_struct_text"       => $data['text'],
            "user_extended_struct_type"       => $data['type'],
            "user_extended_struct_parms"      => "plugin_user_friends^,^^,^^,^0^,^^,^{$data['text']}",
            "user_extended_struct_values"     => $data['values'] ?? '',
            "user_extended_struct_default"    => $data['default'] ?? 0,
            "user_extended_struct_read"       => 253,
            "user_extended_struct_write"      => 253,
            "user_extended_struct_required"   => $data['required'] ?? 0,
            "user_extended_struct_signup"     => 0,
            "user_extended_struct_applicable" => $data['applicable'] ?? 253,
            "user_extended_struct_order"      => $data['order'],
            "user_extended_struct_parent"     => $catID
        ];

        if ($existingField) {
            // Atualizar campo existente
            $fieldData['WHERE'] = "user_extended_struct_id=".intval($existingField);
            $sql->update("user_extended_struct", $fieldData);
        } else {
            // Inserir novo campo
            $sql->insert("user_extended_struct", $fieldData);
        }
    }

    // ----------------------------
    // 5️⃣ Limpar cache do sistema
    // ----------------------------
    e107::getCache()->clear('system');
}

}