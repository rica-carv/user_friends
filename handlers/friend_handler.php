<?php
require_once("../../../class2.php");
if (!defined('e107_INIT')) {
    exit;
}


e107::lan('user_friends', 'front', true);

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

$db     = e107::getDb();

/* -------------------------
   MAP DE AÇÕES
--------------------------*/
/* -------------------------
const UF_STATUS_PENDING  = 1;
const UF_STATUS_ACCEPTED = 2;
const UF_STATUS_REMOVED  = 0;
const UF_STATUS_BLOCKED  = 3;
--------------------------*/

$map = [
    'add' => [
        'db_status'=> 1,
        'ui_status'    => 'added',
    ],
    'remove_req' => [
//        'delete'    => true,
        'ui_status' => 'canceled',
        'lan'       => LAN_USERFRIEND_32,
    ],
    'remove_fr' => [
//        'delete'    => false,
        'ui_status' => 'removed',
        'lan'       => LAN_USERFRIEND_31,
    ],
    'accept' => [
        'db_status' => 2,
        'ui_status' => 'accepted',
        'lan'       => LAN_USERFRIEND_30,
    ],
    'decline' => [
        'db_status' => 0,
        'ui_status' => 'refused',
        'lan'       => LAN_USERFRIEND_33,
    ],
];

$action = $_POST['fr_action'] ?? '';

// Ação inválida
if (!isset($map[$action]))
{
    $response['msg'] = LAN_USERFRIEND_3;
    echo json_encode($response);
    exit;
}

$cfg = $map[$action];

$pref = function(string $key, bool $default = false) {
    return (bool) e107::pref('user_friends', $key, $default);
};

$permMap = [
    'add'        => ['allow_frontend_add', true],
    'remove_req'=> ['allow_frontend_unsend', false],
    'remove_fr' => ['allow_frontend_unfriend', false],
    'accept'    => ['allow_frontend_accept', false],
    'decline'   => ['allow_frontend_decline', false],
];

if (isset($permMap[$action]))
{
    [$prefKey, $def] = $permMap[$action];

    if (!$pref($prefKey, $def))
    {
        $response['msg'] = LAN_NO_PERMISSIONS;
        echo json_encode($response);
        exit;
    }
}


/* -------------------------
   ADD FRIEND
--------------------------*/
if ($action === 'add')
{
           //    $targetId = $cfg['uid'];
    $targetId = (int) ($_POST['user_id'] ?? 0);
    if (!$targetId || $targetId === USERID)
    {
        $response['msg'] = LAN_USERFRIEND_3;
        echo json_encode($response);
        exit;
    }

    $row = $db->retrieve(
        'user_friends',
        'friends_id',
        "
        (from_user=" . USERID . " AND to_user={$targetId})
        OR
        (from_user={$targetId} AND to_user=" . USERID . ")
        "
    );

    if (!$row)
    {
        $friendsId = $db->insert('user_friends', [
            'from_user' => USERID,
            'to_user'   => $targetId,
            'created'   => time(),
            'status'    => (int) $cfg['db_status']
        ]);

        if (!$friendsId)
        {
            $response['msg'] = LAN_ERROR;
        }
    }
    else
{
    $response['msg'] = LAN_USERFRIEND_12; // já existe / pendente
    echo json_encode($response);
    exit;
}

}

if (($action !== 'add'))
{
    $friendsId = (int) ($_POST['friends_id'] ?? 0);

    if (!$friendsId)
    {
        $response['msg'] = LAN_USERFRIEND_3;
        echo json_encode($response);
        exit;
    }
}

/* -------------------------
   DELETE (remove)
--------------------------*/
if ($action === 'remove_req')
{
    $result = $db->delete(
        'user_friends',
        "friends_id={$friendsId} AND (from_user=" . USERID . " OR to_user=" . USERID . ") AND status=1"
    );

    if ($result === false)
    {
        e107::getLog()->addError('UserFriends: delete failed (DB error)');
        $response['msg'] = LAN_ERROR;
    }

    if ($result === 0)
    {
        $response['msg'] = LAN_USERFRIEND_3;
    }
}
/* -------------------------
   REMOVER AMIZADE (remover/marcar)
--------------------------*/
if ($action === 'remove_fr')
{
    $delete = (bool) e107::pref('user_friends', 'delete_on_unfriend', false);
    $block  = e107::pref('user_friends', 'block_after_remove', false) ? 3 : 0;

    $where = "friends_id={$friendsId} AND (from_user=" . USERID . " OR to_user=" . USERID . ") AND status=2";

    $result = $delete 
        ? $db->delete('user_friends', $where)
        : $db->update('user_friends', ['status' => $block, 'WHERE' => $where]);

    if ($result === false)
    {
        e107::getLog()->addError('UserFriends: remove_fr failed');
        $response['msg'] = LAN_ERROR;
    }
    elseif ($result === 0)
    {
        $response['msg'] = LAN_USERFRIEND_3;
    }
}


/* -------------------------
   UPDATE (accept / decline)
--------------------------*/
if ($action === 'accept' || $action === 'decline')
{
    $result = $db->update('user_friends', [
        'status' => (int) $cfg['db_status'],
        'WHERE' => 'friends_id=' . $friendsId . '
           AND to_user=' . USERID . '
           AND status=1'
    ]);

    if ($result === false)
    {
        e107::getLog()->addError('UserFriends: update failed (DB error)');
        $response['msg'] = LAN_ERROR;
    }
}

if ($response['msg']) {
// 1. Mensagens frontend (msg / alerts)
if (!e107::pref('user_friends', 'show_frontend_messages', true))
{
    unset($response['msg']);
}
        echo json_encode($response);
        exit;
}
// ---------------------------------
// CONTROLO FINAL DE PREFS
// ---------------------------------

// 2. Logging
if (e107::pref('user_friends', 'log_actions'))
{
    e107::getLog()->add('USER_FRIEND', [
        'action'     => $action,
        'friends_id'=> $friendsId ?? null,
        'user'       => USERID,
    ]);
}/* -------------------------
   RESPOSTA FINAL
--------------------------*/
$response['status'] = 'ok';
$page = $_POST['act_page'] ?? '';

//$response['debug'] = $page;
//$response['debug'] .= (str_contains($page, 'user_friends.php'));

if (str_contains($page, 'user_friends.php'))
{
    $msg = e107::getMessage();
    if (!empty($cfg['lan'])) {
        $msg->addSuccess($cfg['lan']);
    }
    $containerHtml = '<div data-userfriend-container="">';
$containerHtml .= $msg->render(); // a mensagem de feedback
$containerHtml .= '</div>';
$response['html'] = $containerHtml;
}
else
{
$tpl    = e107::getTemplate('user_friends')['options_action'] ?? '';
$ufh_sc = e107::getScBatch('user_friends', 'user_friends');

       $ufh_sc->setVars([
            'status' => $cfg['ui_status'],
            'friends_id' => $friendsId
        ]);

        $response['html'] = e107::getParser()->parseTemplate($tpl, false, $ufh_sc);
    }

// 3. Auto reset
$delay     = (int) e107::pref('user_friends', 'reset_delay', 0);

if (!empty(e107::pref('user_friends', 'autoreset')) && $delay > 0)
{
    $response['reset'] = $delay;
}
echo json_encode($response);
exit;