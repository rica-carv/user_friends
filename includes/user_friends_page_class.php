<?php
if (!defined('e107_INIT')) { exit; }
include_once(e_BASE.'e107_plugins/user_friends/includes/user_friends_trait.php');
class UserFriendsPage
{
	use UserFriendsTrait;
    protected int $ownerId;
    protected string $view;
    protected string $layout;
    protected int $perPage;
    protected int $from;
    protected array $counts = [];
    protected int $total = 0;
    protected array $rows = [];
    protected int $lastVisit = 0;

    public function __construct(array $opts = [])
    {
        // Fonte única de verdade
        $get = $opts ?: $_GET;

        $this->view    = isset($get['id']) ? 'friends' : ($get['view'] ?? 'friends');
        $this->layout  = $get['layout'] ?? 'list';
        $this->perPage = (int) e107::pref('user_friends', 'per_page', 10);
        $this->from    = isset($get['from']) ? (int) $get['from'] : 0;
        $this->ownerId = (int) ($get['id'] ?? USERID);

        if ($this->ownerId === USERID) {
            $this->lastVisit = (int) e107::user(USERID)['user_lastvisit'];
        }

        $db = e107::getDb();

        // Contagens
        if ($this->ownerId === USERID) {
            $this->counts = [
                'friends'      => $db->count('user_friends', '(*)', "(from_user={$this->ownerId} OR to_user={$this->ownerId}) AND status=2"),
                'sent'         => $db->count('user_friends', '(*)', "from_user={$this->ownerId} AND status=1"),
                'received'     => $db->count('user_friends', '(*)', "to_user={$this->ownerId} AND status=1"),
                'received_new' => $db->count('user_friends', '(*)', "to_user={$this->ownerId} AND status=1 AND created > {$this->lastVisit}"),
                'friends_new'  => $db->count('user_friends', '(*)', "(from_user={$this->ownerId} OR to_user={$this->ownerId}) AND status=2 AND created > {$this->lastVisit}"),
            ];
        } else {
            $this->counts = [
                'friends' => $db->count('user_friends', '(*)', "(from_user={$this->ownerId} OR to_user={$this->ownerId}) AND status=2"),
            ];
        }

        // Query principal
        switch ($this->view) {
            case 'sent':
                $where = "from_user=" . USERID . " AND status=1";
                break;

            case 'received':
                $where = "to_user=" . USERID . " AND status=1";
                break;

            default:
                $where = "(from_user={$this->ownerId} OR to_user={$this->ownerId}) AND status=2";
        }

        $db->select('user_friends', '*', $where." ORDER BY created DESC LIMIT {$this->from},{$this->perPage}");
        $this->rows  = $db->rows();
        $this->total = $this->counts[$this->view] ?? 0;
    }

    protected function getFriendsVisibility($userId)
{
    $user = e107::user($userId);
    $visibility = trim($user['user_plugin_user_friends_visibility'] ?? '');
//    var_dump($visibility);

    if ($visibility === LAN_USERFRIEND_25) return 'public';
    if ($visibility === LAN_USERFRIEND_26) return 'friends';
    if ($visibility === LAN_USERFRIEND_27) return 'private';
/*
    // fallback leve
    $v = mb_strtolower($visibility);

    if (strpos($v, 'pub') !== false) return 'public';
    if (strpos($v, 'friend') !== false || strpos($v, 'amig') !== false) return 'friends';
    if (strpos($v, 'priv') !== false) return 'private';
*/
    return 'public';
}
    public function render(bool $textonly = false)
    {
            $tp = e107::getParser();
        $sc = e107::getScBatch('user_friends', 'user_friends');


        $visibility = $this->getFriendsVisibility($this->ownerId);
        $isFriend = $this->ufFriendshipStatus();
        if (((int) e107::pref('user_friends', 'allow_private_friends_list') === 1) && (USERID !== $this->ownerId) && ($visibility === 'private' || ($visibility === 'friends' && !$isFriend))) {
        $sc->setVars([
            'view'      => 'private',
            'user_id'   => $this->ownerId,
        ]);
//        var_dump($sc->getVars());
            return $tp->parseTemplate('{USERFRIEND_MESSAGE}', true, $sc);
        }

///            $tp = e107::getParser();
///        $sc = e107::getScBatch('user_friends', 'user_friends');
        $sc->setVars([
            'view'      => $this->view,
            'layout'    => $this->layout,
            'rows'      => $this->rows,
            'total'     => $this->total,
            'user_id'   => $this->ownerId,
            'lastvisit' => $this->lastVisit,
            'counts'    => $this->counts,
            'perPage'   => $this->perPage,
        ]);
        $template = e107::getTemplate('user_friends', 'user_friends');
//var_dump($sc->getVars());
    // ✅ wrapper APÓS template
    $sc->wrapper(
        'user_friends/' . (!empty($_GET['id']) ? 'page' : 'edit_page')
    );
        if ($textonly) {
            return $tp->parseTemplate('{USERFRIEND_MESSAGE}{USERFRIEND_ITEMS}{USERFRIEND_MAIN}', true, $sc);
        }
        $text = $tp->parseTemplate($template['page'], true, $sc);
        $sc->wrapper('user_friends/caption');
        e107::getRender()->tablerender(
            ($template['caption']?$tp->parseTemplate($template['caption'], true, $sc):(!empty($_GET['id']) ? str_replace('[x]', e107::user($_GET['id'])['user_name'], LAN_USERFRIEND_2) : LAN_USERFRIEND_4)),
            $text);
    }
    public function getCount(string $key = null): mixed
{
    return ($key === null) ? $this->counts : $this->counts[$key] ?? 0;
}

}
