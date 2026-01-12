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
    function sc_userfriend_options($parm = [])
{
$uf_sc = e107::getScBatch('user_friends', 'user_friends');
/*
    // Inicializa status se não estiver setado
    if ($this->status === null) {
        $this->status = $this->ufFriendshipStatus();
    }

    // Inicializa justadded de forma explícita
    $this->justadded = !empty($parm['justadded']); // pega valor correto

//    var_dump($this->var);

    // Garante template carregado
    if (!$this->template || empty($this->template['options'])) {
        $this->template = e107::getTemplate('user_friends');
    }

    // Processa o template com a própria instância do SC
    return "<div class='userfriend-controls'>"
        . e107::getParser()->parseTemplate($this->template['options'])
        . "</div>";
*/
    // Passa status e justadded explicitamente via $parm, mas vai sair daqui....
/*
    $status = $parm['status'] ?? null;
    $justadded = $parm['justadded'] ?? false;
*/
    $uf_sc->setVars([
        'status'     => $this->ufFriendshipStatus(),
        'justadded'  => $parm['justadded'] ?? false, // normal render
    ]);

    $tpl = e107::getTemplate('user_friends')['options'] ?? '';
    // Carrega template de forma segura
/*
    $template = e107::getTemplate('user_friends');
    $tpl = $template['options'] ?? '';
*/
    // Evita mexer em $this->status ou $this->template
    $class = trim($parm['class'] ?? '');

    return "<div class='userfriend-controls {$class}'>"
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

}