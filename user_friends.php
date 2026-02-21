<?php
if (!defined('e107_INIT')) {
    require_once(__DIR__.'/../../class2.php');
}

/*
if (!USER) {
    e107::redirect(e_HTTP);
    exit;
}
*/
require_once(HEADERF);

// --- Executa ---
//try {
/*
PARA FAZER DEPOIS:::::
			if(!check_class($this->pmPrefs['pm_class']))
			{
				return LAN_PM_12;
			}
            */
if (!USER) {
    e107::lan('core', 'membersonly');
    $sc = e107::getScBatch('membersonly');
//    e107::getParser()->parseTemplate(        "{MEMBERSONLY_RESTRICTED_AREA} {LAN=LAN_NO_PERMISSIONS} {MEMBERSONLY_LOGIN}														{MEMBERSONLY_SIGNUP}", true, $sc);

	$mes = e107::getMessage();
//    $mes->addWarning(LAN_MEMBERS_1."<br>".LAN_NO_PERMISSIONS."<br>".LAN_MEMBERS_2."<br>".LAN_MEMBERS_3);
    $mes->addWarning(e107::getParser()->parseTemplate(        "{MEMBERSONLY_RESTRICTED_AREA}<br>{LAN=LAN_NO_PERMISSIONS}<br>{MEMBERSONLY_LOGIN}			{MEMBERSONLY_SIGNUP}", true, $sc));
        e107::getRender()->tablerender(
            LAN_USERFRIENDS_FULLNAME,
            $mes->render());
    }
    else {
    e107::lan('user_friends', 'front', true);

    require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');
    $page = new user_friends_page();
    $page->render();
    }


/*
} catch (Exception $e) {
    e107::getMessage()->adderror($e->getMessage());
    echo e107::getMessage()->render();
    require_once(FOOTERF);
    exit;
}
*/
require_once(FOOTERF);