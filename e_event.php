<?php
if (!defined('e107_INIT')) { exit; }

class user_friends_event 
{
	public function config()
	{
		$event = array();		

		// Este evento dispara no usersettings.php quando o botão 'Atualizar' é premido
		$event[] = array(
			'name'		=> 'user_profile_edit', 
			'function'	=> 'save_user_friends_prefs', 
		);

		return $event;
	}

	/**
	 * Grava as preferências na tabela customizada do plugin
	 * @param array $data Contém os dados do utilizador, incluindo 'user_id'
	 */
	public function save_user_friends_prefs($data)
	{
            $prefs = e107::getPlugPref('user_friends');
        if(empty($prefs['allow_frontend_add']))
{
      e107::lan('user_friends', 'front', true);
    e107::getMessage()->addWarning(    LAN_USERFRIEND_16    );
}
	}
}
