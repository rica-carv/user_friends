<?php
/*
* Copyright (c) e107 Inc 2015 e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
*
* Log Stats shortcode batch class - shortcodes available site-wide. ie. equivalent to multiple .sc files.
*/
///////////////////////////////////    require_once(__DIR__ . '/../../../../class2.php');
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
e107::coreLan('users', true);

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
//	protected $sql;
//    protected $template;
//    protected $uinfo;
//    protected $targetId;
//    protected $status;
//    protected $justadded;

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
 
///    protected $uid;
protected $userfriend_data = [];
protected $userfriend_uid  = 0;

private function ufJS()
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;
    
    $prefs = e107::getPlugPref('user_friends');

    e107::js('user_friends', 'js/user_friends.js', 'footer');

    e107::js('settings', [
        // handler
        'userfriendScript' => e_PLUGIN_ABS . 'user_friends/handlers/friend_handler.php',
        'userfriendPage'   => e_SELF, // <--- isto envia a página atual
        // UI
/*
        'userfriendShowAlert' => !empty($prefs['show_alert']),
        'userfriendAutoReset' => !empty($prefs['autoreset']),
        'userfriendResetDelay'=> (int) ($prefs['reset_delay'] ?? 0),
        'userfriendCounter'   => !empty($prefs['reset_counter']),
*/
        // permissões frontend
        'allowAdd'      => !isset($prefs['allow_frontend_add']) 
                            || !empty($prefs['allow_frontend_add']),

        'allowUnsend'   => !empty($prefs['allow_frontend_unsend']),
        'allowUnfriend' => !empty($prefs['allow_frontend_unfriend']),
        'allowAccept'   => !empty($prefs['allow_frontend_accept']),
        'allowDecline'  => !empty($prefs['allow_frontend_decline']),
    ]);
}

function sc_userfriend_add($parm = '')
{
if (!e107::pref('user_friends', 'allow_frontend_add', true)) {
    return '';
}
/*
    if ($this->status === null) {
    $this->status = $this->ufFriendshipStatus();
}

    if ($this->status !== null) {
        return '';
    }
*/
//    var_dump($this->var);
//    var_dump ($this->var && $this->var['status']);
//var_dump($this->var);

    if ($this->var['status']) {
        return '';
//        return $this->var['status'];
    }
    
//$prefs = e107::getPlugPref('user_friends');
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
//    e107::js('user_friends', 'js/user_friends.js', 'footer');
//	e107::js('settings', array('userfriendScript' => e_PLUGIN_ABS."user_friends/handlers/friend_handler.php", 'userfriendShowAlert' => (int) $prefs['show_alert']));
    $this->ufJS();
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
//var_dump($this->var);
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
//var_dump($this->var['status']);
//var_dump($this->var['status'] !== 2);
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
$status = (int) ($this->var['status'] ?? 0);

if ($status === 1 && !e107::pref('user_friends', 'allow_frontend_unsend')) {
    return '';
}
if ($status === 2 && !e107::pref('user_friends', 'allow_frontend_unfriend')) {
    return '';
}

if ( $this->var['view'] && $this->var['view'] === 'received')
    {
        return '';
    }
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

//var_dump ($this->var['row'] );
//var_dump ($this->var['status'] );



// só estados válidos
if (!in_array($status, [1, 2], true)) {
    return '';
}
    // Pref controla tudo
    //var_dump((!e107::pref('user_friends', 'allow_frontend_unfriend')) || (!e107::pref('user_friends', 'allow_frontend_unsend')));
    //var_dump(!e107::pref('user_friends', 'allow_frontend_unfriend'));
    //var_dump(!e107::pref('user_friends', 'allow_frontend_unsend'));
/*
    var_dump ((!e107::pref('user_friends', 'allow_frontend_unfriend') && ($this->var['status'] == 1)));
    var_dump (((!e107::pref('user_friends', 'allow_frontend_unsend')) && ($this->var['status'] == 2)));
    if ((!e107::pref('user_friends', 'allow_frontend_unfriend') && ($this->var['status'] === 1))
     ||
     ((!e107::pref('user_friends', 'allow_frontend_unsend')) && ($this->var['status'] === 2))) {
        return '';
    }
*/
//var_dump ($status);
//var_dump (!e107::pref('user_friends', 'allow_frontend_unsend') && ($status === 1));
//var_dump(!e107::pref('user_friends', 'allow_frontend_unfriend') && ($status === 2));
//    if (!e107::pref('user_friends', 'allow_frontend_unsend') && ($status === 1)) {return '';}
//    if (!e107::pref('user_friends', 'allow_frontend_unfriend') && ($status === 2)){ return '';}

//    var_dump($this->var);
    // Só mostra se o primeiro id for o utilizador actual
/*
    if ($this->var['user_id'] != USERID) {
        return '';
    }
*/
/*        $db = e107::getDb();

        $id = $this->var['friends_id']??$this->var['row']['friends_id'];
        $sql = "
            SELECT from_user
            FROM #user_friends
            WHERE friends_id={$id}
        ";
*/
/*
    var_dump($db->retrieve($sql));
    var_dump(USERID);
    var_dump ($db->retrieve($sql) <> USERID);
*/
/*
        if ((int) $db->retrieve($sql) <> USERID) {
            return '';
        }
        */
        if ((int) $this->var['from_user'] <> USERID) {
            return '';
        }
    // Só mostra se forem amigos
/*
    if (uf_friendship_exists(USERID, $targetId) !== 2) {
        return '';
    }
*/

    if ($this->var['status'] == 1) {
        $text = LAN_USERFRIEND_7;
        $action = 'req';
    } else {
        $text = LAN_USERFRIEND_8;
        $action = 'fr';
    }

    $classes = trim(($parm['class'] ?? 'btn-danger'));

//    e107::js('user_friends', 'js/user_friends.js', 'footer');
//    e107::js('settings', array('userfriendScript' => e_PLUGIN_ABS."user_friends/handlers/friend_handler.php", 'userfriendShowAlert' => (int) $prefs['show_alert'], 'userfriendPage' => e_PAGE));
    $this->ufJS();
//var_dump ($this->var);
//        data-userfriend-user='{$this->var['to_user']}'
    return "
    <button
        class='btn {$classes}'
        data-userfriend-id='{$this->var['friends_id']}'
        data-userfriend-action='remove_{$action}'
        title='{$text}'>
        {$text}
    </button>";
}

function sc_userfriend_accept($parm = '')
{
if (!e107::pref('user_friends', 'allow_frontend_accept')) {
    return '';
}

if ( $this->var['view'] && $this->var['view'] !== 'received')
//    if (($this->var['view'] ?? '') !== 'received')
    {
        return '';
    }

    /*
    if ($this->var['user_id'] != USERID)
    {
        return '';
    }
    */
    /*
            $db = e107::getDb();

        $id = $this->var['friends_id']??$this->var['row']['friends_id'];
        $sql = "
            SELECT to_user
            FROM #user_friends
            WHERE friends_id={$id}
        ";
        */
/*
    var_dump($db->retrieve($sql));
    var_dump(USERID);
    var_dump ($db->retrieve($sql) <> USERID);
*/
/*        if ((int) $db->retrieve($sql) <> USERID) {
            return '';
        }
*/
        if ((int) $this->var['to_user'] <> USERID) {
            return '';
        }

    if (($this->var['status'] !== 1)) {
        return '';
    }

    $classes = trim(($parm['class'] ?? 'btn-success'));

//        e107::js('user_friends', 'js/user_friends.js', 'footer');
//    e107::js('settings', array('userfriendScript' => e_PLUGIN_ABS."user_friends/handlers/friend_handler.php", 'userfriendShowAlert' => (int) $prefs['show_alert'], 'userfriendPage' => e_PAGE));
    $this->ufJS();

    return "<button class='btn {$classes}'
            data-userfriend-id='{$this->var['friends_id']}'
        data-userfriend-action='accept'
        title='".LAN_USERFRIEND_14."'
        >".LAN_USERFRIEND_14."</button>";
}

function sc_userfriend_decline($parm = '')
{
if (!e107::pref('user_friends', 'allow_frontend_decline')) {
    return '';
}
//    var_dump($this->var);
    if ( $this->var['view'] && $this->var['view'] !== 'received')
//    if (($this->var['view'] ?? '') !== 'received')
    {
        return '';
    }
    
/*
    if ($this->var['user_id'] != USERID)
    {
        return '';
    }
*/
/*
            $db = e107::getDb();

        $id = $this->var['friends_id']??$this->var['row']['friends_id'];
        $sql = "
            SELECT to_user
            FROM #user_friends
            WHERE friends_id={$id}
        ";
        */
/*
    var_dump($db->retrieve($sql));
    var_dump(USERID);
    var_dump ($db->retrieve($sql) <> USERID);
*/
/*
        if ((int) $db->retrieve($sql) <> USERID) {
            return '';
        }
*/
        if ((int) $this->var['to_user'] <> USERID) {
            return '';
        }

            if (($this->var['status'] !== 1)) {
        return '';
    }

    $classes = trim(($parm['class'] ?? 'btn-danger'));
//    e107::js('user_friends', 'js/user_friends.js', 'footer');
//    e107::js('settings', array('userfriendScript' => e_PLUGIN_ABS."user_friends/handlers/friend_handler.php", 'userfriendShowAlert' => (int) $prefs['show_alert'], 'userfriendPage' => e_PAGE));
    $this->ufJS();

    return "<button class='btn {$classes}'
                data-userfriend-id='{$this->var['friends_id']}'
        data-userfriend-action='decline'
        title='".LAN_USERFRIEND_15."'
        >".LAN_USERFRIEND_15."</button>";
}

/*------------------------
* ACTION STATUS
* ---------------------*/
protected function renderStatusButton($expectedStatus, $lan, $defaultClass, $parm)
{
    if (($this->var['status'] ?? '') !== $expectedStatus) {
        return '';
    }

    $classes = trim($parm['class'] ?? $defaultClass);

    return "
        <span class='e-tip' title='{$lan}'>
            <button class='btn {$classes} userfriend-btn" .
                (
                    e107::pref('user_friends', 'reset_counter') & (e107::pref('user_friends', 'reset_delay') >0)
                    ? " uf-pending"
                    : ""
                ) .
            "'
                title='{$lan}'
                data-userfriend-id='{$this->var['friends_id']}'
                data-action='{$expectedStatus}'
                aria-disabled='true'
                disabled
                >
                {$lan}
            </button>
        </span>
    ";
}

function sc_userfriend_sent($parm = '')
{
    return $this->renderStatusButton(
        'added',
        LAN_USERFRIEND_11,
        'btn-info',
        $parm
    );
}
function sc_userfriend_canceled($parm = '')
{
    return $this->renderStatusButton(
        'canceled',
        LAN_USERFRIEND_32,
        'btn-warning',
        $parm
    );
}

function sc_userfriend_removed($parm = '')
{
    return $this->renderStatusButton(
        'removed',
        LAN_USERFRIEND_31,
        'btn-warning',
        $parm
    );
}

function sc_userfriend_ok($parm = '')
{
    return $this->renderStatusButton(
        'accepted',
        LAN_USERFRIEND_30,
        'btn-success',
        $parm
    );
}

function sc_userfriend_notok($parm = '')
{
    return $this->renderStatusButton(
        'refused',
        LAN_USERFRIEND_33,
        'btn-danger',
        $parm
    );
}
    /* --------------------
     * Page helpers
     * -------------------- */

    function sc_userfriend_layout()
    {
//        var_dump();
        return $this->var['layout'] ?? 'list';
    }

    function sc_userfriend_tabs()
    {
        if ($this->var['user_id'] <> USERID)
        {
            return false;
        }
        $this->wrapper('user_friends/tabs');

        return e107::getParser()->parseTemplate(
            e107::getTemplate('user_friends', 'user_friends', 'tabs'),
            true,
            $this
        );
    }

    function sc_userfriend_tab_active($parm)
    {
        return ($this->var['view'] === $parm['type']) ? 'active' : '';
    }

    function sc_userfriend_url($parm)
    {
        return e_SELF . '?view=' . $parm . '&layout=' . ($this->var['layout'] ?? 'list');
    }

function sc_userfriend_pagination()
{
//echo "<hr>";
//var_dump($this->var['counts']);
    if (empty($this->var['total'])) {
        return '';
    }

 //   $amount = (int) ($this->var['perPage'] ?? e107::pref('user_friends', 'per_page', 10));

		$opts = [
			'tmpl_prefix' => 'default',
			'total'       => (int) $this->var['total'],
			'amount'      => (int) ($this->var['perPage'] ?? e107::pref('user_friends', 'per_page', 10)),
			'current'     => (int) (varset($_GET['from'],0)),
//			'url'         => e107::getUrl()->create($this->route, $this->newsUrlparms),
			'url'         => 'user_friends.php?view='.$this->var['view'].'&layout='.$this->var['layout']."&from=--FROM--",
		];
/*        
        $parms = 
        'tmpl_prefix=default'.
        '&total='.$this->var['total'].
        '&amount='.(int) ($this->var['perPage'] ?? e107::pref('user_friends', 'per_page', 10)).
        '&current=--FROM--'.
        '&url=?view='.$this->var['view'].'&layout='.$this->var['layout'].".--FROM--";
*/
//		$parms = http_build_query($opts);
    return e107::getParser()->parseTemplate("{NEXTPREV=".http_build_query($opts)."}");
}


    /* --------------------
     * MAIN LOOP (philcat-style)
     * -------------------- */

    function sc_userfriend_items()
    {
        /*
        if (empty($this->var['rows'])) {
            return LAN_NO_RESULTS_FOUND;
        }
        */
//echo "<pre>";
//var_dump ($this->var);
//echo "</pre>";

        if (empty($this->var['rows'])) {
//             $this->sc_message=e107::getMessage()->addInfo(LAN_NO_RESULTS_FOUND);
             return '';
        }
        $tp   = e107::getParser();
        $text = '';
//        $uid = $this->var['user_id'];
        $tmpl_ini = ($this->var['user_id'] <> USERID)?"normal":"edit";
        $tmpvar = $this->var;
        unset($tmpvar['rows']);
        foreach ($this->var['rows'] as $row)
        {
//var_dump ($this->var);
            $this->addVars([
                'row'    => $row,
//                'status' => $this->ufFriendshipStatus($row),
            ]);
//echo "<hr><hr><hr><hr>";
//var_dump ($this->var);
//echo "<hr><hr><hr><hr>";
//            var_dump($this->var['user_id']);
            if (e107::isInstalled('euser')) {
                $GLOBALS['euser_vars']['user_id'] = $this->ufRowUserId($this->var['user_id']);
            }          

            $text .= $tp->parseTemplate(
                e107::getTemplate('user_friends', 'user_friends', $tmpl_ini.'_item'), // Mudar para ambas as versões: normal e edit
                true,
                $this
            );
        }
        $this->var = $tmpvar;
//        unset($tmpvar['rows']);
//var_dump ($this->var);
        //Não é melhor limpar a var rows? Já não preciso delas...
//        $this->var['rows'] = null;
//echo "<hr><hr><hr><hr>";
//var_dump ($this->getvars());

//        var_dump ($this->var);

        return $text;
    }

    function sc_userfriend_message()
    {
        /*
        if (empty($this->var['rows'])) {
            return LAN_NO_RESULTS_FOUND;
        }
        */
//        var_dump(strpos(e_PAGE, "user_friends"));

        if (empty($this->var['rows'])) {
    		$mtext = array(
                'default' => LAN_USERFRIEND_1,
                'friends' => LAN_USERFRIEND_34,
                'sent' => LAN_USERFRIEND_35,        
                'received' => LAN_USERFRIEND_36
            );            
/*
var_dump($this->var['user_id']);
var_dump(USERID);
var_dump($this->var['user_id']==USERID);
var_dump($this->var['user_id']==USERID?$this->var['view']:"default");
*/
            $msg = e107::getMessage();
            $msg->setClose(false, E_MESSAGE_INFO);
            if (($this->var['user_id']==USERID) && strpos(e_PAGE, "user_friends") === false) {
                $sent = $this->var['counts']['sent'];
                $text="<br>".($sent>0?str_replace("[x]", $sent, LAN_USERFRIEND_37):$mtext['sent']);
                $received = $this->var['counts']['received'];
                $text.="<br>".($received>0?str_replace("[x]", $received, LAN_USERFRIEND_38):$mtext['received']);
            }
            $msg->addInfo($mtext[$this->var['user_id']==USERID?$this->var['view']:"default"].$text);
//            return e107::getMessage()->render();
            }

            return e107::getMessage()->render();
    }

    function sc_userfriend_created()
{
    return e107::getDate()->convert_date(
        $this->var['row']['created'],
        'short'
    );
}

function sc_userfriend_status_label()
{
    if(!$this->loadUserFriend())
    {
        return false;
    }
    //    var_dump($this->var);
/*
    $status = $this->var['row']['status'];
    var_dump($this->var);

    switch ($status) {
        case 2:
            return "<span class='badge bg-success'>".LAN_USERFRIEND_9."</span>";
        case 1:
            return "<span class='badge bg-warning'>".LAN_USERFRIEND_12."</span>";
        default:
            return "<span class='badge bg-secondary'>—</span>";
    }
    */
//    var_dump(e107::user($this->ufRowUserId()));
		$bo = array(
			'<span class="bg-success-subtle text-success-emphasis p-1 d-inline-block">'.LAN_ACTIVE.'</span>',
			"<span class='bg-warning-subtle text-warning-emphasis p-1 d-inline-block'>".LAN_BANNED."</span>",
			"<span class='bg-default-subtle text-default-emphasis p-1 d-inline-block'>".LAN_NOTVERIFIED."</span>",
			"<span class='bg-info-subtle text-info-emphasis p-1 d-inline-block'>".LAN_BOUNCED."</span>",
			"<span class='bg-danger-subtle text-danger-emphasis p-1 d-inline-block'>".USRLAN_56."</span>", // Deleted
		);
// var_dump($this->userfriend_data);
	
        return vartrue($bo[$this->userfriend_data['user_ban']],' ');
}

function sc_userfriend_count($type)
{
    return (int) ($this->var['counts'][$type] ?? 0);
}

function sc_userfriend_new_icon($parm = [])
{
    $type = $parm['type'] ?? null;
    $counts = $this->var['counts'] ?? [];

    if (!$type || empty($counts[$type])) {
        return '';
    }

//    return '<i class="bi bi-dot text-danger ms-1" title="'.(int)$counts[$type].' new"></i>';
        return '<span class="uf-badge-new bg-danger border border-light rounded-circle" title="'.(int)$counts[$type].' new"></span>';
}

function sc_userfriend_item_new_icon()
{
    $row = $this->var['row'] ?? [];
    $lastVisit = (int) ($this->var['lastvisit'] ?? 0);

    if (empty($row['created']) || $row['created'] <= $lastVisit) {
        return '';
    }

//    return '<i class="bi bi-dot text-danger ms-1" title="New">*</i>';
        return '<span class="uf-badge-new bg-danger border border-light rounded-circle" title="New"></span>';
}

function sc_userfriend_new_count($parm = [])
{
    $type = $parm['type'] ?? null;
    $counts = $this->var['counts'] ?? [];

////return 10000;
    if (!$type || empty($counts[$type])) {
        return '';
    }

    return (int) $counts[$type];
}

    /* --------------------
     * User helpers
     * -------------------- */

    function sc_user_name()
    {
    if(!$this->loadUserFriend())
    {
        return null;
    }
//        $uid = $this->ufRowUserId();
        return $this->userfriend_data['user_name'] ?? '';
    }

    function sc_user_avatar()
    {
    if(!$this->loadUserFriend())
    {
        return null;
    }
        return e107::getParser()->toAvatar(
            $this->userfriend_data
        );
    }

    function sc_user_lastvisit()
{
    if(!$this->loadUserFriend())
    {
        return null;
    }
/*
    if(empty($this->userfriend_data))
    {
        return '—';
    }
*/
    $ts = $this->userfriend_data['user_currentvisit'] ?? $this->userfriend_data['user_lastvisit'] ?? 0;

    return $ts
        ? e107::getParser()->toDate($ts, 'short')
        : '—';
}

}