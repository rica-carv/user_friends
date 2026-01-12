<?php
/*
* Copyright (c) e107 Inc 2015 e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
*
* Log Stats shortcode batch class - shortcodes available site-wide. ie. equivalent to multiple .sc files.
*/

if (!defined('e107_INIT')) { exit; }
/////////include_once(e_PLUGIN . "euser/includes/euser_trait.php");
//e107::lan('eforum');
//e107::lan('eforum','',true);  // English_menu.php or {LANGUAGE}_menu.php
//var_dump ($parm);
// Tenho de injectar os icones aqui, senão não dá....
/*if (file_exists(THEME.'templates/icons_template.php')) // Preferred v2.x location.
{
	require_once(THEME.'templates/icons_template.php');
}
elseif (file_exists(THEME.'euser/icons_template.php'))
{
	require_once(THEME.'euser/icons_template.php');
}
elseif (file_exists(THEME.'icons_template.php'))
{
	require_once(THEME.'icons_template.php');
}
else
{
	require_once(e_PLUGIN.'euser/templates/icons_template.php');
}
*/
/////////e107::getTemplate('euser', 'icons');
e107::coreLan('admin', true);
e107::lan('user_friends',"front", true);

if (!defined('USER_FRIENDS_PLUGIN_PATH')) {
    define('USER_FRIENDS_PLUGIN_PATH', e107::getPlug()->load('user_friends')->getFields(true)['plugin_path']);
}

//include_once(e_PLUGIN . "ecore/includes/user_trait.php");

//require_once(e_BASE.'e107_plugins/user_friends/includes/user_friend_helpers.php');
include_once(e_BASE.'e107_plugins/user_friends/includes/user_friends_trait.php');

    class plugin_user_friends_user_friends_shortcodes extends e_shortcode
{
///////////	use Euser_global_info;
	use user_friends_trait;
//	protected $tp;
	protected $sql;
//    protected $template;
//    protected $uinfo;
//    protected $targetId;
    protected $status;
    protected $justadded;

/*
	function __construct()
	{
		$this->sql = e107::getDb();
//		$this->tp = e107::getParser();
//        $this->template = e107::getTemplate('user_friends');

//        $this->uinfo = $this->Ecore_userinfo();
//        $this->targetId = key($this->uinfo);
    }
*/
/*
function sc_userfriend_options__($parm = [])
{
    // nada de $this->status ou $this->justadded
    return "-------".e107::getParser()->parseTemplate(e107::getTemplate('user_friends')['options']);
}
*/
function sc_userfriend_add($parm = '')
{
/*
    if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}

    if ($this->status !== null) {
        return '';
    }
*/
    if ($this->var['status'] !== null) {
        return '';
    }
    
$prefs = e107::getPlugPref('user_friends');
/*
    $uinfo = $this->Ecore_userinfo();
    require_once(e_BASE.'e107_plugins/user_friends/includes/user_friend_helpers.php');
*/
    $targetId = $this->ufTargetId();

    if (!USER || !$targetId || $targetId == USERID) {
        return '';
    }

//    var_dump ($this->status);

//    return $this->status;

    $classes = trim(($parm['class'] ?? ''));

    // Se já existir amizade, devolve o botão correto
/*
    if ($btn = uf_friendship_exists(USERID, $targetId, $classes)) {
        return $btn;
    }
*/
    // Garante que o JS é carregado
    e107::js('user_friends', 'js/user_friends.js', 'footer');
	e107::js('settings', array('userfriendScript' => e_PLUGIN_ABS."user_friends/handlers/friend_handler.php", 'userfriendShowAlert' => (int) $prefs['userfriend_show_alert']));

//        $uinfo = $this->Ecore_userinfo();
//        $targetId = key($uinfo);

    return "
    <button
       class='btn {$classes}'
       data-userfriend-user='{$targetId}'
       data-userfriend-action='add'
       title='".LAN_USERFRIEND_10."'>
        ".LAN_USERFRIEND_10."
    </button>";

/*

    return "
    <a href='#'
       class='btn {$classes}'
       data-userfriend-user='{$targetId}'
       data-userfriend-script='".e_PLUGIN_ABS."user_friends/handlers/add_friend.php'
       title='".LAN_USERFRIEND_20."'>
        ".LAN_USERFRIEND_20."
    </a>";
	*/
}
function sc_userfriend_sent($parm = '')
{
/*
    if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}
*/
//var_dump($this->status);
//var_dump($this->justadded);
//var_dump((($this->status !== 1) || !$this->justadded));
/*
    if (($this->status !== 1) || !$this->justadded) {
        return '';
    }
*/
    if ($this->var['status'] !== 'added') {
        return '';
    }
    $classes = trim(($parm['class'] ?? 'btn-info'));

    return "
    <span class='e-tip' title='".LAN_USERFRIEND_11."'>
    <button
        class='btn {$classes}'
        title='".LAN_USERFRIEND_11."' disabled>
        ".LAN_USERFRIEND_11."
    </button>
    </span>";
}

function sc_userfriend_pending($parm = '')
{
/*
if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}
*/
//var_dump($this->status);
//var_dump($this->justadded);
//var_dump((($this->status !== 1) || $this->justadded));
/*
    if (($this->status !== 1) || $this->justadded) {
        return '';
    }
*/
    if (($this->var['status'] !== 1)) {
        return '';
    }
    $classes = trim(($parm['class'] ?? 'btn-secondary'));

    return "
    <span class='e-tip' title='".LAN_USERFRIEND_12."'>
    <button
        class='btn {$classes}'
        title='".LAN_USERFRIEND_12."' disabled>
        ".LAN_USERFRIEND_12."
    </button>
    </span>";
}
function sc_userfriend_accepted($parm = '')
{
/*
    if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}
*/
/*
    if ($this->status !== 2) {
        return '';
    }
*/
    if ($this->var['status'] !== 2) {
        return '';
    }
    $classes = trim(($parm['class'] ?? 'btn-success'));

    return "
    <span class='e-tip' title='".LAN_USERFRIEND_9."'>
    <button
        class='btn {$classes}'
        title='".LAN_USERFRIEND_9."' disabled>
        ".LAN_USERFRIEND_9."
    </button>
    </span>";
}
function sc_userfriend_remove($parm = '')
{
/*
if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}
*/
/*
    $uinfo = $this->Ecore_userinfo();
    $targetId = key($uinfo);

    if (!USER || !$targetId || $targetId == USERID) {
        return '';
    }
*/
    // Pref controla tudo
    if (!e107::pref('user_friends', 'allow_frontend_unfriend')) {
        return '';
    }

    // Só mostra se forem amigos
/*
    if (uf_friendship_exists(USERID, $targetId) !== 2) {
        return '';
    }
*/
    if ($this->var['status'] !== 2) {
        return '';
    }

    $classes = trim(($parm['class'] ?? 'btn-danger'));

    e107::js('user_friends', 'js/user_friends.js', 'footer');

    return "
    <button
        class='btn {$classes}'
        data-userfriend-user='{$this->ufTargetId()}'
        data-userfriend-action='remove'
        title='".LAN_USERFRIEND_8."'>
        ".LAN_USERFRIEND_8."
    </button>";
}

}