<?php
include_once(e_PLUGIN . "ecore/includes/user_trait.php");
trait user_friends_trait
{
    use Ecore_user;

    /**
     * Devolve o ID do utilizador alvo (perfil atual)
     */
    protected function ufTargetId(): ?int
    {
        $uinfo = $this->Ecore_userinfo();
        $targetId = key($uinfo);

        if (!$targetId || $targetId == USERID) {
            return null;
        }

        return (int) $targetId;
    }

    /**
     * Devolve o estado da amizade com o utilizador atual
     *
     * @return int|null
     *  null → sem relação
     *  0    → rejeitado
     *  1    → pendente
     *  2    → amigos
     */
    protected function ufFriendshipStatus(): ?int
    {
        if (!USER) {
            return null;
        }

        $targetId = $this->ufTargetId();
        if (!$targetId) {
            return null;
        }

        $db = e107::getDb();

        $sql = "
            SELECT status
            FROM #user_friends
            WHERE (from_user=" . USERID . " AND to_user={$targetId})
               OR (from_user={$targetId} AND to_user=" . USERID . ")
            LIMIT 1
        ";

        if (!$db->gen($sql)) {
            return null;
        }

        $row = $db->fetch();
        if (!$row) {
            return null;
        }

        return (int) $row['status'];
    }
}
