<?php
require_once("../../../class2.php");
if (!defined('e107_INIT')) {
    exit;
}
//$e107 = e107::getInstance();
//require_once(e_BASE.'e107_plugins/user_friends/includes/_user_friend_helpers.php');

//include_once(e_BASE.'e107_plugins/user_friends/includes/user_friends_trait.php');

// Carrega a classe de shortcodes do plugin
//include_once(e_PLUGIN.'user_friends/e_shortcode.php');

// Instancia a classe
//$scodes = new user_friends_shortcodes();

e107::lan('user_friends', 'front', true);
//    class friends_action 
//{
///////////	use Euser_global_info;
//	use user_friends_trait;
$response = [
    'status' => 'error',
    'msg'    => '',
    'html'   => ''
];

// --- Segurança ---
if (!USER)
{
    $response['msg'] = LAN_NO_PERMISSIONS;
    echo json_encode($response);
    exit;
}

$targetId = (int) ($_POST['user_id'] ?? 0);
//$btnClass = trim($_POST['btn_class'] ?? 'btn-secondary btn-sm');

if (!$targetId || $targetId == USERID)
{
    $response['msg'] = LAN_USERFRIEND_3; // ID inválido
    echo json_encode($response);
    exit;
}

if ($_POST['fr_action'] == 'add') {
//    function add($parm = [])
//{
// --- Estado atual ---
//$status = uf_friendship_exists(USERID, $targetId);
//$status = $this->ufFriendshipStatus();
/*
  uf_friendship_exists devolve:
  false | 0 | 1 | 2
*/
    $db = e107::getDb();

    $sql = "
        SELECT status
        FROM #user_friends
        WHERE (from_user=" . USERID . " AND to_user={$targetId})
           OR (from_user={$targetId} AND to_user=" . USERID . ")
        LIMIT 1
    ";

    $db->gen($sql);
        $row = $db->fetch();
        if (!$row)
        {
/*
            $status = (int) $row['status'];
        }
        else
        {
            $status = null;
        }
*/
// --- Criar novo pedido ---
$ok = e107::getDb()->insert('user_friends', [
    'from_user' => USERID,
    'to_user'   => $targetId,
    'created'   => time(),
    'status'    => 1
]);

if (!$ok)
{
    $response['msg'] = LAN_ERROR;
    echo json_encode($response);
    exit;
}



/*
        $response['status'] = 'ok';
        $response['html'] = e107::getParser()->parseTemplate('{USERFRIEND_OPTIONS}');

        echo json_encode($response);
        exit;
*/
    }
//----    else
//----    {
        $response['status'] = 'ok';
//        $response['html'] = e107::getParser()->parseTemplate('{USERFRIEND_OPTIONS}');
/*
        $scParser = e107::getScParser();
        $scParser->registerShortcode();
*/
/*
        $ufh_sc = e107::getScBatch('user_friends', 'user_friends');
//        require_once(e_PLUGIN.'user_friends/shortcodes/batch/user_friends_shortcodes.php');
//        $uf_sc = new plugin_user_friends_user_friends_shortcodes();
        $tpl = e107::getTemplate('user_friends')['options'] ?? '';
        $response['html'] = "»»»»»»»".e107::getParser()->parseTemplate($tpl, true, $ufh_sc);
//        $response['html'] = $ufh_sc;
*/
// estado calculado AQUI
/*
$status     = 1;     // exemplo
$justadded = true;
*/
// instancia batch
/*
$ufh_sc = e107::getScBatch('user_friends', 'user_friends_ajax');

// INJETAR VARS — ÚNICA FORMA CORRETA
$ufh_sc->setVars([
    'status'     => $status,
    'justadded'  => $justadded
]);
*/
/*
$response['html'] = e107::getParser()->parseTemplate(
    '{USERFRIEND_OPTIONS}',
    false,
    $sc
);
*/
/*
        $tpl = e107::getTemplate('user_friends')['options'] ?? '';
        $response['html'] = "»»»»»»»".e107::getParser()->parseTemplate("blablalblalallalal");
*/
/*
$tp = e107::getParser();
$tp->setScBatch('user_friends', 'user_friends');

$tpl = e107::getTemplate('user_friends')['options'] ?? '';
$response['html'] = $tp->parseTemplate($tpl);
*/
 //       $response['html'] = "testetestete";

 /*
require_once(e_PLUGIN.'user_friends/shortcodes/batch/user_friends_ajax.php');
$ufh_sc = new plugin_user_friends_user_friends_ajax_shortcodes();
*/
// Funciona.... $ufh_sc = e107::getScBatch('user_friends_ajax', 'user_friends');
$ufh_sc = e107::getScBatch('user_friends', 'user_friends');
$ufh_sc->setVars([
    'status'    => 'added'
]);
$tp = e107::getParser();
$tpl = e107::getTemplate('user_friends')['options'] ?? '';
$response['html'] = $tp->parseTemplate($tpl, false,$ufh_sc);

        echo json_encode($response);
        exit;
//        $status = null;
//    }



// Já existe → apenas informativo
//if ($status !== null)
//{
/*--
    switch ((int) $status)
    {
        case 2:
            $text = LAN_USERFRIEND_9; // Amigos
            break;

        case 1:
            $text = LAN_USERFRIEND_11; // Pedido enviado
            break;

        default:
            $text = LAN_USERFRIEND_12; // Rejeitado / bloqueado
    }
---*/
/*
    $response['status'] = 'ok';
/*---
    $response['html']   =
        "<span class='e-tip title='{$text}'><button class='{$btnClass} disabled'>{$text}</button></span>";
---*/
/*
    $response['html'] = e107::getParser()->parseTemplate('{USERFRIEND_CONTROLS}');

    echo json_encode($response);
    exit;
}
*/
// --- Criar novo pedido ---
/*
$ok = e107::getDb()->insert('user_friends', [
    'from_user' => USERID,
    'to_user'   => $targetId,
    'created'   => time(),
    'status'    => 1
]);

if (!$ok)
{
    $response['msg'] = LAN_ERROR;
    echo json_encode($response);
    exit;
}
*/
// --- Resposta final ---
/*
$response['status'] = 'ok';
$response['msg']    = LAN_USERFRIEND_11;
$response['html']   =
    "<span class='e-tip' title='".LAN_USERFRIEND_11."'><button class='{$btnClass} btn-info disabled'>".LAN_USERFRIEND_11."</button></span>";
*/
//$parm = ['justadded' => true]; // ou false, conforme a situação

//----------$response['html'] = e107::getParser()->parseTemplate('{USERFRIEND_OPTIONS}');
/*
$sc = new user_friends_shortcodes();
$status = 1; // ou calcula status
$response['html'] = $sc->sc_userfriend_options([
    'status'    => $status,
    'justadded' => true
]);
*/
//----$response['html'] = e107::getParser()->parseTemplate('{USERFRIEND_OPTIONS:justadded=true}');
//$response['html'] = $scodes->sc_userfriend_options(['justadded' => true]);
//$sc->status = 1;           // ou 2, conforme o teste
//$sc->justadded = true;     // ou false
//$response['html'] = $sc->sc_userfriend_options(['justadded' => true]);
//----echo json_encode($response);
//----exit;
//}
//----}
//}

//$action = $_POST['fr_action'];
// Inicializa a UI
//if ($_POST['fr_action'] == 'add') {
//    new friends_action()->add();
}
else if ($_POST['fr_action'] == 'remove') {
//    new friends_action()->remove();
}

