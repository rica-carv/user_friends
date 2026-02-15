<?php
if (!defined('e107_INIT')) {
    require_once(__DIR__.'/../../class2.php');
}

if (!USER) {
    e107::redirect(e_HTTP);
    exit;
}

e107::lan('user_friends', 'front', true);

require_once(e_PLUGIN.'user_friends/includes/user_friends_page_class.php');

require_once(HEADERF);

// --- Executa ---
try {
    $page = new user_friends_page();
    $page->render();
} catch (Exception $e) {
    e107::getMessage()->adderror($e->getMessage());
    echo e107::getMessage()->render();
    require_once(FOOTERF);
    exit;
}

require_once(FOOTERF);