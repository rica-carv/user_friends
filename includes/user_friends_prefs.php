<?php
class UserFriendsPrefs
{
    protected static $cache = [];

    /**
     * Obtem todas as prefs de um utilizador
     */
    public static function get(int $userId): array
    {
        if(isset(self::$cache[$userId])) {
            return self::$cache[$userId];
        }

        $sql = e107::getDb();
        $prefs = [];

        if($sql->select('user_friends_prefs', '*', 'user_id='.intval($userId))) {
            $prefs = $sql->fetch();
        } 

        // fallback para defaults se não existir
        if(empty($prefs)) {
            $prefs = [
                'user_id' => $userId,
                'allow_requests' => 1,
                'auto_accept' => 0,
                'notify_email' => 1,
                'notify_pm' => 1,
                'visibility' => 0,
            ];
        }

        self::$cache[$userId] = $prefs;
        return $prefs;
    }

    /**
     * Obtem apenas uma pref específica
     */
    public static function getValue(int $userId, string $key, $default = null)
    {
        $prefs = self::get($userId);
        return $prefs[$key] ?? $default;
    }

    /**
     * Define/atualiza prefs de um utilizador
     */
    public static function set(int $userId, array $data): bool
    {
        $sql = e107::getDb();

        $exists = $sql->select('user_friends_prefs', 'user_id', 'user_id='.intval($userId));

        if($exists) {
            $result = $sql->update('user_friends_prefs', $data, 'user_id='.intval($userId));
        } else {
            $data['user_id'] = $userId;
            $result = $sql->insert('user_friends_prefs', $data);
        }

        if($result) {
            self::$cache[$userId] = array_merge(['user_id' => $userId], $data);
        }

        return (bool) $result;
    }

    /**
     * Limpa cache de um utilizador específico ou global
     */
    public static function clearCache(int $userId = null)
    {
        if($userId !== null) {
            unset(self::$cache[$userId]);
        } else {
            self::$cache = [];
        }
    }
}