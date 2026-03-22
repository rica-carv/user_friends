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

    class user_friends_shortcodes extends e_shortcode
{
///////////	use Euser_global_info;
	use UserFriendsTrait;
//	protected $tp;
	protected $sql;
//    protected $template;
//    protected $uinfo;
//    protected $targetId;
    protected $status;
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
function sc_userfriend_options($parm = [])
{
//    var_dump (USERID);
    if (!USERID) {return false;}
//    var_dump (USERID);
e107::css('user_friends', 'user_friends.css');
    $uf_sc = e107::getScBatch('user_friends', 'user_friends');

//    $context = $parm['context'] ?? 'public';
// Também pode ser addvars
/*
    $uf_sc->addVars([
//        'status'    => $this->ufFriendshipStatus()['sta'],
        'justadded' => !empty($parm['justadded']),
//        'context'   => $context,
    ]);
    */
//    var_dump ($this->ufFriendshipStatus());
    $uf_sc->setVars($this->ufFriendshipStatus()); // Isto tem de ser addvars senão limpa as vars todas.....

//    $templates = e107::getTemplate('user_friends');

    // Mapeamento simples e explícito
/*    $map = [
        'public' => 'options_public',
        'self'   => 'options_self',
        'edit'   => 'options_edit',
    ];
*/
//var_dump($uf_sc->getVars());
//echo "<hr>";//var_dump($parm);
//var_dump(!empty($parm['edit']));
// contexto visual
/*
if (!empty($parm['edit'])) {
    $context = 'edit';
}
elseif (USERID) {
    $context = 'self';
}
else {
    $context = 'public';
}
*/
//    $tplKey = $map[$context] ?? 'options_public';
//    $tpl    = $templates[$tplKey] ?? '';
//    $tpl    = "options_".($context ?? 'public');
//    $context = (strpos(e_PAGE, "user_friends") !== false) ? 'main' : null;
//    var_dump( "options".((strpos(e_PAGE, "user_friends") !== false) ? '_main' : ''));
//    $templ = "options".((strpos(e_PAGE, "user_friends") !== false) ? '_main' : '');
    $tpl = e107::getTemplate('user_friends', 'user_friends', "options".((strpos(e_PAGE, "user_friends") !== false) ? '_main' : ''));
//var_dump ($templ);
//var_dump ($tpl);
    if (!$tpl) { return '';}
	$uf_sc->wrapper('user_friends/options');
//    $uf_sc->wrapper('user_friends/'.(!$_GET['id']?'edit_':'').'page'); // Mudar para ambas as versões: normal e edit??

    $class = trim($parm['class'] ?? '');
//var_dump($this->ufFriendshipStatus());
    return "<div data-userfriend-controls class='{$class}'>"
        . e107::getParser()->parseTemplate($tpl, true, $uf_sc)
        . "</div>";
}
/*
function sc_userfriend_options__($parm = [])
{
    // nada de $this->status ou $this->justadded
    return "-------".e107::getParser()->parseTemplate(e107::getTemplate('user_friends')['options']);
}
*/



function sc_userfriend_tab_title()
{
    return LAN_USERFRIENDS_FULLNAME; // ex: "Amigos"
}
/*

function sc_USERFRIENDS_TAB_CONTENT()
{
    $userId = (int) e107::getRegistry('core/profile/user_id');

    if (!$userId) {
        return '';
    }

    // aqui podes:
    // - listar amigos
    // - mostrar contadores
    // - mostrar link para página dedicada
    // - ou tudo isso combinado

    return e107::getParser()->parseTemplate(
        e107::getTemplate('user_friends', 'profile'),
        true,
        e107::getScBatch('user_friends', 'user_friends')->setVars([
            'user_id' => $userId
        ])
    );
}
*/
    // Mostra a lista de amigos (tab ou página)
public function sc_userfriend_list()
{
    require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');

    $page = new UserFriendsPage([
        'id'     => $this->ufTargetId()
    ]);

    return $page->render(true);
}

    // Mostra apenas o número total de amigos
    public function sc_userfriend_count()
    {
//        var_dump ($this->ufTargetId());
        require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');

    $page = new UserFriendsPage([
        'id'     => $this->ufTargetId()
    ]);
        return $page->getCount('friends');
    }

    // Mostra apenas o número total de amigos
    public function sc_userfriend_main($parm = null)
    {
//        var_dump ($this->ufTargetId());
//        var_dump (USERID);
//        require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');

/*
    $page = new UserFriendsPage([
        'id'     => $this->ufTargetId()
    ]);
*/
//        $text = e_PLUGIN.'/user_friends/user_friends.php';
        $text = e107::url('user_friends', 'index');
        $id = $this->ufTargetId();

                require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');
    $page = new UserFriendsPage([
        'id'     => $id
    ]);
//        var_dump($page->getCount('friends'));
        if ($parm['admin'] == 'forced'){
            if(!$id && USERID) {
                return $text;
            } else {
                return '';
            }
        }

            if($page->getCount('friends')>0) {
                return $text.($id ? '?id='.$id : '');
            } else {
                return '';
            }
    }

	function sc_userfriend_nav($parm=null)
{
//	echo "<hr><hr><hr><hr><hr><hr><hr>";
//	var_dump(e107::isInstalled('user_friends'));
    if(!e107::isInstalled('user_friends'))
    {
        return null;
    }

    if(!USERID) // apenas utilizadores logados
    {
        return null;
    }

    $tp = e107::getParser();
/*
    require_once(e_PLUGIN."user_friends/user_friends_class.php");

    $friends = new user_friends;

    // Número de pedidos pendentes
    $pending = $friends->getPendingCount(USERID);

    $count = '';
    if(!empty($pending))
    {
        $count = "<span class='badge bg-danger'>".$pending."</span>";
    }
*/
    $icon = $tp->toGlyph('fa-solid fa-user-group');

// IMPLEMENTAR QUANDO TIVER SEF A FUNCIONAR
    /*
    $urlFriends   = e107::url('user_friends','friends');
    $urlRequests  = e107::url('user_friends','requests');
    $urlFind      = e107::url('user_friends','find');
*/
/////$urlFriends   = e_SELF . '?view=' . $parm . '&layout=' . ($this->var['layout'] ?? 'list');
//$urllayout   = ($this->var['layout'] ?? 'list');
/*
$urlFriends   =         '<a class="dropdown-item icon-link" href="{USERFRIEND_URL=friends}">
            i class="fa-solid fa-user-group"></i>{LAN=LAN_USERFRIEND_4}
                <small><span class="badge">
        {USERFRIEND_COUNT=friends}{USERFRIEND_NEW_COUNT=type:friends_new}
    </span></small>
        </a>
    {USERFRIEND_NEW_ICON=type:friends_new}';
$urlSent   = e_SELF . '?view=sent&layout=' . $urllayout;
$urlReceived   = e_SELF . '?view=received&layout=' . $urllayout;
*/
    $uf_sc = e107::getScBatch('user_friends', 'user_friends');

//    $context = $parm['context'] ?? 'public';
// Também pode ser addvars
/*
    $uf_sc->addVars([
//        'status'    => $this->ufFriendshipStatus()['sta'],
        'justadded' => !empty($parm['justadded']),
//        'context'   => $context,
    ]);
    */
//    var_dump ($this->ufFriendshipStatus());
    $uf_sc->setVars($this->ufFriendshipStatus()); // Isto tem de ser addvars senão limpa as vars todas.....
    require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');
    $page = new UserFriendsPage();
//    $counts['friends'] = $page->getCount();
    $uf_sc->addVars(array( 'counts' => $page->getCount()));

    return $tp->parseTemplate('
        <a class="uf-nav nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
    '.$icon.
    ($new || $unread ? 
      '<span class="position-absolute top-0 translate-middle p-1 bg-'.$badgeClass.' border border-light rounded-circle"></span>'
      :
      ""
    )
    .'</a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
           <a class="dropdown-item icon-link" href="{USERFRIEND_URL=friends}">
            <i class="fa-solid fa-user-group"></i>{LAN=LAN_USERFRIEND_4}
                <span class="badge">
        {USERFRIEND_COUNT=friends}{USERFRIEND_NEW_COUNT=type:friends_new}
    </span>
        </a>
    {USERFRIEND_NEW_ICON=type:friends_new}

        <a class="dropdown-item icon-link" href="{USERFRIEND_URL=sent}">
            <i class="fa-solid fa-user-clock"></i>{LAN=LAN_USERFRIEND_5}
                        <span class="badge">
        {USERFRIEND_COUNT=sent}
    </span>
        </a>

        <a class="dropdown-item icon-link" href="{USERFRIEND_URL=received}">
            <i class="fa-solid fa-user-plus"></i>{LAN=LAN_USERFRIEND_6}
                           <span class="badge">
        {USERFRIEND_COUNT=received}{USERFRIEND_NEW_COUNT=type:received_new}
    </span>
        </a>
    {USERFRIEND_NEW_ICON=type:received_new}

        </li>
    </ul>', true, $uf_sc);
}

}