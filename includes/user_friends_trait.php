<?php
include_once(e_PLUGIN . "ecore/includes/user_trait.php");
trait user_friends_trait
{
    use Ecore_user;

//    protected int $userfriend_uid = 0;
//    protected array $userfriend_data = [];
    /**
     * Devolve o ID do utilizador alvo (perfil atual)
     */
    protected function ufTargetId(): ?int
    {
//        $uinfo = $this->Ecore_userinfo();
        if ($uinfo = $this->Ecore_userinfo()) {$targetId = key($uinfo);}

        if (!$targetId || $targetId == USERID) {
            return null;
        }

        return $targetId;
    }


        protected function ufRowUserId($uid = null)
    {
        $row = $this->var['row'];
//        var_dump($row);
/*
        var_dump($row['to_user']);
        var_dump($row['from_user']);
        var_dump($uid);
        var_dump(USERID);
*/
/*
        var_dump(($row['from_user'] == USERID)
            ? $row['to_user']
            : $row['from_user']);
*/
//        $uid = $this->var['user_id']??USERID;
        return ($row['from_user'] == ($uid ?? USERID))
            ? $row['to_user']
            : $row['from_user'];

//       return "11";    
    }

    /**
     * Devolve o estado da amizade com o utilizador atual
     *
     * @return int|null array com friend_id + status:
     *  null → sem relação
     *  0    → rejeitado
     *  1    → pendente
     *  2    → amigos
     */
//    protected function ufFriendshipStatus(): ?int
    protected function ufFriendshipStatus()
    {
        if (!USERID) {
            return [];
        }
//var_dump ("++++");
        $targetId = $this->ufTargetId();
//var_dump ($targetId);

        if (!$targetId) {
            return [];
        }

        $db = e107::getDb();

        $sql = "
            SELECT friends_id, status, from_user, to_user
            FROM #user_friends
            WHERE (from_user=" . USERID . " AND to_user={$targetId})
               OR (from_user={$targetId} AND to_user=" . USERID . ")
            LIMIT 1
        ";

        if (!$db->gen($sql)) {
            return [];
        }

        $row = $db->fetch();
        if (!$row) {
            return [];
        }

//        return (int) $row['status'];
    return [
        'friends_id' => (int) $row['friends_id'],
        'status'     => (int) $row['status'],
        'from_user'  => (int) $row['from_user'],
        'to_user'    => (int) $row['to_user'],
    ];
    }

    protected function loadUserFriend()
{
    $uid = $this->ufRowUserId();

    if(!$uid)
    {
        return false;
    }

    if($this->userfriend_uid === $uid && !empty($this->userfriend_data))
    {
        return true;
    }

    $this->userfriend_uid  = $uid;
    $this->userfriend_data = e107::user($uid);

    return !empty($this->userfriend_data);
}

    /**
     * Lista de amigos de um utilizador
     */
/*
    protected function ufFriendsList(int $uid, int $status = 2): array
    {
        $db = e107::getDb();

        $sql = "
            SELECT *
            FROM #user_friends
            WHERE (from_user={$uid} OR to_user={$uid})
              AND status={$status}
            ORDER BY created DESC
        ";

        return $db->retrieve($sql, true) ?: [];
    }
*/
/*
protected function ufGetList(string $mode, ?int $uid = null): array
{
    $uid = $uid ?? $this->ufTargetId() ?? USERID;

    if (!$uid) {
        return [];
    }

    $db = e107::getDb();

    switch ($mode) {
        case 'friends':
            $where = "status=2 AND (from_user={$uid} OR to_user={$uid})";
            break;

        case 'pending_in':
            $where = "status=1 AND to_user={$uid}";
            break;

        case 'pending_out':
            $where = "status=1 AND from_user={$uid}";
            break;

        default:
            return [];
    }

    $sql = "
        SELECT *
        FROM #user_friends
        WHERE {$where}
        ORDER BY created DESC
    ";

    if (!$db->gen($sql)) {
        return [];
    }

    $rows = [];
    while ($row = $db->fetch()) {
        $rows[] = $row;
    }

    return $rows;
}
*/

}