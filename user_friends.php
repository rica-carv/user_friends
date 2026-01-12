<?php
require_once("../../class2.php");
require_once(e_PLUGIN.'user_friends/includes/user_friend_helpers.php');
e107::lan('user_friends',"front", true);

if (!USER) {
    e107::getMessage()->add('Necessário login');
    e107::redirect(e_HTTP);
}

$uid = USERID;
$db = e107::getDb();

// Lista de amigos ativos
$db->select('user_friends', '*', "(from_user={$uid} OR to_user={$uid}) AND status=2");
$friends = [];
while($row = $db->fetch()) {
    $friendId = ($row['from_user'] == $uid) ? $row['to_user'] : $row['from_user'];
    $friends[] = $friendId;
}

// Lista de pedidos pendentes
$db->select('user_friends', '*', "to_user={$uid} AND status=1");
$pending = [];
while($row = $db->fetch()) {
    $pending[] = $row['from_user'];
}

$text = "<h3>Meus Amigos</h3><ul>";
foreach($friends as $f) {
    $uname = e107::user($f)->getName();
    $text .= "<li>{$uname}</li>";
}
$text .= "</ul>";

$text .= "<h3>Pedidos Pendentes</h3><ul>";
foreach($pending as $f) {
    $uname = e107::user($f)->getName();
    $text .= "<li>{$uname} 
        <a href='".e_PLUGIN_ABS."user_friends/handlers/add_friend.php?accept={$f}' class='btn btn-success btn-sm'>Aceitar</a> 
        <a href='".e_PLUGIN_ABS."user_friends/handlers/add_friend.php?reject={$f}' class='btn btn-danger btn-sm'>Rejeitar</a>
    </li>";
}
$text .= "</ul>";

e107::getRender()->tablerender("Amigos", $text);
