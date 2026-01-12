<?php
require_once("../../class2.php");
if (!getperms("P")) { e107::redirect("admin"); exit; }
//require_once(e_ADMIN."auth.php");
//require_once(e_BASE.'e107_plugins/user_friends/includes/_user_friend_helpers.php');
e107::lan('user_friends', 'admin', true);
e107::css('user_friends', 'admin.css');

class user_friends_adminArea extends e_admin_dispatcher
{

	protected $modes = array(	
		'main'	=> array(
			'controller' 	=> 'user_friends_main_ui',
			'path' 			=> null,
			'ui' 			=> 'user_friends_main_form_ui',
			'uipath' 		=> null
		),
/*
		'sections'	=> array(
			'controller' 	=> 'euser_sections_ui',
			'path' 			=> null,
			'ui' 			=> 'euser_sections_form_ui',
			'uipath' 		=> null
		),
*/
		

	);	
	
	
	protected $adminMenu = array(

		'main/list'			=> array('caption'=> LAN_MANAGE, 'perm' => 'P'),
//		'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),
		'main/prefs'			=> array('caption'=> LAN_PREFS, 'perm' => 'P'),
//		'sections/prefs'			=> array('caption'=> LANAD_EUSER_1, 'perm' => 'P'),
//		'maint/opt1'              => array('divider'=>true),

//		'profile/prefs'			=> array('caption'=> LAN_USER_50, 'perm' => 'P'), 
//		'memberlist/prefs'			=> array('caption'=> LAN_EUSER_500, 'perm' => 'P'),
//		'main/opt1'              => array('divider'=>true),
//		'main/opt2'              => array('divider'=>true),
//		'opt2'              => array('header'=> LAN_EUSER_ADMIN_3),
//		'whatsnew/prefs'			=> array('caption'=> LAN_EUSER_501, 'perm' => 'P'),
//		'main/opt2'              => array('divider'=>true),
//		'online/prefs'			=> array('caption'=> LAN_EUSER_502, 'perm' => 'P'),
//		'main/opt3'              => array('divider'=>true),
//		'color/prefs'			=> array('caption'=> LANAD_EUSER_40, 'perm' => 'P'),
//		'color/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),


		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P')
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = LAN_USERFRIENDS_FULLNAME;

}
// Cria interface com e_admin_ui
class user_friends_main_ui extends e_admin_ui
{
    protected $pluginTitle = LAN_USERFRIENDS_FULLNAME;
    protected $pluginName  = "user_friends";

    protected $table      = "user_friends";
    protected $pid        = 'friends_id';
    protected $perPage    = 20;
    protected $batchDelete = true;
    protected $checkboxes = true;

/*
protected $edit = true;
protected $create = false;
*/
    protected $fields = [
                'checkboxes' => [
            'title' => '',
            'type'  => null,
            'width' => '3%',
            'toggle' => 'friends_selected', 'forced' => TRUE
        ],
		'friends_id'				=> array('title' => LAN_ID, 	    'type' => 'text', 	    'width' => '5%', 	'thclass' => 'center', 			'class' => 'center',  	'nosort' => false, 'readParms'=>'link=sef&target=blank'),
        'from_user' => ['title'=>LANAD_USERFRIENDS_18, 'type'=>'user', 'width'=>'20%'],
        'to_user'   => ['title'=>LANAD_USERFRIENDS_19, 'type'=>'user', 'width'=>'20%'],
        'created'   => ['title'=>LAN_DATE, 'type'=>'datestamp', 'width'=>'20%'],
'status' => [
    'title' => LAN_STATUS,
          'type' => 'dropdown',   'data'=>'int', 'inline'=>true,	'width' => 'auto', 	'thclass' => '', 				'class' => null, 		'nosort' => false, 'batch'=>true, 'filter'=>true
],
        'options' => [
            'title' => LAN_OPTIONS,
            'type'  => null,
            'forced' => true,
            'readParms'=>'edit=false',
//            'class' => 'left btn-sm',
            'width' => '10%'
        ]
    ];

    protected $fieldpref = array('checkboxes','friends_id', 'from_user', 'to_user', 'created', 'status', 'options');

    protected $prefs = [
/*
		'news_default_template'   => ['title' => NWSLAN_127, 'type'     => 'dropdown', 'data'=>'safestr', 'help'   => LAN_NEWS_88, 'tab'  => 'general'],
		'newsposts'               => ['title' => NWSLAN_88, 'type'      => 'dropdown', 'data'=>'int', 'tab'    => 'general'],
		'news_list_limit'         => ['title' => LAN_NEWS_91, 'type'    => 'dropdown',  'data'=>'int', 'help'   => LAN_NEWS_92, 'tab'  => 'general'],
		'news_list_templates'     => ['title' => LAN_NEWS_93, 'type'    => 'checkboxes', 'help' => LAN_NEWS_94, 'tab'  => 'general'],
		'news_pagination'         => ['title' => LAN_PAGINATION, 'type' => 'dropdown', 'data'=>'safestr', 'help'   => LAN_NEWS_112, 'tab' => 'general'],
		'news_cache_timeout'      => ['title' => LAN_NEWS_110, 'type'   => 'number', 'data'=>'int', 'help'     => LAN_NEWS_111, 'tab' => 'general'],
		'news_cats'               => ['title' => NWSLAN_86, 'type'      => 'boolean', 'data'=>'int', 'tab'     => 'general'],
		'nbr_cols'                => ['title' => NWSLAN_87, 'type'      => 'dropdown', 'data'=>'int', 'tab'    => 'general'],
		'newsposts_archive'       => ['title' => NWSLAN_115, 'type'     => 'dropdown', 'data'=>'int', 'help'   => NWSLAN_116, 'tab'   => 'general'],
		'newsposts_archive_title' => ['title' => NWSLAN_117, 'type'     => 'text', 'data'=>'safestr', 'tab'        => 'general'],
*/
		'userfriend_show_alert'      => ['title' => LANAD_USERFRIENDS_10, 'type'     => 'boolean', 'data'=>'int', 'tab'   => 'general', 'writeParms' => [
    'label' => 'yesno'
]],
		'allow_frontend_unfriend'      => ['title' => LANAD_USERFRIENDS_11, 'type'     => 'boolean', 'data'=>'int', 'tab'   => 'general', 'writeParms' => [
    'label' => 'yesno'
]],
		'show_frontend_reject'      => ['title' => LANAD_USERFRIENDS_12, 'type'     => 'boolean', 'data'=>'int', 'tab'   => 'general', 'writeParms' => [
    'label' => 'yesno'
]],
/*
		'news_unstemplate'        => ['title' => NWSLAN_113, 'type'     => 'boolean', 'data'=>'int', 'help'    => NWSLAN_114, 'tab'   =>'general'],

		'news_editauthor'         => ['title' => LAN_NEWS_51, 'type'    => 'userclass', 'data'=>'int', 'tab'   => 'admin', 'writeParms'=>['classlist'=>'nobody,main,admin,classes']],
		'news_limit_to_self'      => ['title' => LAN_NEWS_113, 'type'   => 'userclass', 'data'=>'int', 'help'=>LAN_NEWS_114, 'tab'   => 'admin', 'writeParms'=>['classlist'=>'nobody,classes,no-excludes']],

		'subnews_class'           => ['title' => NWSLAN_106, 'type'     => 'userclass','data'=>'int', 'tab'   => 'subnews', 'writeParms'=>['classlist'=>'nobody,public,guest,member,admin,classes'] ],
		'subnews_htmlarea'        => ['title' => NWSLAN_107, 'type'     => 'boolean', 'data'=>'int', 'tab'     => 'subnews'],
		'subnews_attach'          => ['title' => NWSLAN_100, 'type'     => 'boolean', 'data'=>'int', 'tab'     => 'subnews'],
		'subnews_attach_minsize'  => ['title' => LAN_NEWS_99, 'type'    => 'dropdown', 'tab'    => 'subnews'],
		'subnews_resize'          => ['title' => NWSLAN_101, 'type'     => 'number', 'data'=>'int', 'tab'      => 'subnews', 'writeParms' => ['maxlength'=>5] ],
		'news_subheader'          => ['title' => NWSLAN_120, 'type'     => 'bbarea', 'tab'      => 'subnews']
*/
	];
	function init()
	{
		/*
		$sql = e107::getDb();
		$sql->gen("SELECT category_id,category_name FROM #news_category");
		while($row = $sql->fetch())
		{
			$cat = $row['category_id'];
			$this->cats[$cat] = $row['category_name'];
		}
		asort($this->cats);
        */
		$this->fields['status']['writeParms']['optArray'] = array('0'=>LANAD_USERFRIENDS_20, '1'=>LANAD_USERFRIENDS_21, '2'=>LANAD_USERFRIENDS_22);
		$this->fields['status']['writeParms']['size'] = 'xlarge';
 
	}

    		// ------- Customize Create --------
	
		public function beforeCreate($new_data, $old_data)
		{
		}
	
		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something		
		}		
		
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id)
		{
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something	
		}
		
		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something		
		}		

        /**
     * Depois de atualizar uma linha individual
     */
/*
    public function afterUpdate($newData, $oldData, $id)
    {
        // Se o status mudou
        if((int)$newData['status'] !== (int)$oldData['status'])
        {
            $this->processStatusChange($newData);
        }
        var_dump($newData);
    }
*/
    /**
     * Depois de atualizar várias linhas via batch
     */
    /*
    public function afterBatchUpdate($ids, $updateValues)
    {
        $db = e107::getDb();

        foreach($ids as $id)
        {
            // Busca linha atualizada
            $db->select('user_friends', '*', "created=".intval($id));
            if($row = $db->fetch())
            {
                $this->processStatusChange($row);
            }
        }
    }
*/
    /**
     * Lógica central de alteração de status
     */
/*    protected function processStatusChange($data)
    {
        $from = $data['from_user'];
        $to   = $data['to_user'];
        $status = (int)$data['status'];
*/
/*        switch($status)
        {
            case 0, 2: // Rejeitado ou aceite
                uf_set_friendship_status($from, $to, $status);
//                uf_reject_friend($from, $to);
                break;
//            case 2: // Aceito
//                uf_accept_friend($from, $to);
//                break;
            case 1: // Pendente → nada a fazer
            default:
                break;
        }
*/
/*
        if ($status === 0 || $status === 2)
        {
                uf_set_friendship_status($from, $to, $status);
        }            
            
    }
*/
}

class user_friends_main_form_ui extends e_admin_form_ui
{
/*
	function options($parms, $value, $id, $attributes)
	{

	//	return $this->renderValue('options',$value,$att,$id);;
		$tp = e107::getParser();
//		$mode = $this->getController()->getMode();

//		if($mode == 'inbox')
//		{
//			$text = "";
			$pmData = $this->getController()->getListModel()->getData();

/*
			if($pmData['pm_from'] != USERID)
			{
				$link = e_SELF."?";
				$link .= (!empty($_GET['iframe'])) ? 'mode=inbox&iframe=1' : 'mode=outbox';


				$link .= "&action=create&to=".intval($pmData['pm_from'])."&subject=".base64_encode($pmData['pm_subject']);



				$text .= "<a href='".$link."' class='btn' title='Reply'>".$tp->toGlyph('fa-reply', array('size'=>'1x'))."</a>";
			}
*/
		//	$text .= $this->renderValue('options',$value,$attr,$id);

//			return $text;
//		}
///----------    $id     = $pmData['created']; // o teu pid
//------    $status = (int) $pmData['status'];
//    $status = (int) $parms['status'];

//-------    $buttons = [];

    // Aceitar → só se pendente               redundante e desnecessário
//    if ($status === 1)
//--------    {
//--------        $buttons[] = "<a href='".e_SELF."?mode=main&action=accept&id=".$id."' class='btn btn-success btn-sm' title='".LAN_ACCEPT."'>".
//--------        $tp->toGlyph('fa-check', array('size'=>'1x')).
//--------        "</a>";
        /*
        e107::getParser()->toGlyph(
            'check',
            [
                'title' => LAN_ACCEPT,
                'href'  => e_SELF."?mode=main&action=accept&id=".$id,
                'class' => 'btn btn-success btn-sm'
            ]
        );
        */
//-----    }

    // Rejeitar → se pendente ou amigos
//    if ($status !== 0)
//    {
//var_dump($attributes);
/*---------        $buttons[] = "<a href='".e_SELF."?mode=main&action=reject&id=".$id."' class='btn btn-danger btn-sm' title='".LAN_DELETE."' etrigger_delete['.$id.']'>".
        $tp->toGlyph('fa-trash', array('size'=>'1x')).
        "</a>";
/*
        $buttons[] = e107::getParser()->toGlyph(
            'times',
            [
                'title' => LAN_DELETE,
                'href'  => e_SELF."?mode=main&action=reject&id=".$id,
                'class' => 'btn btn-danger btn-sm'
            ]
        );
*/
//    }
/*--------------
    return implode(' ', $buttons);

}
*/
/*
public function status($curVal, $row)
{
    switch ((int)$curVal) {
        case 2:
            return "<span class='badge badge-success'>".LAN_USERFRIEND_10."</span>";
        case 1:
            return "<span class='badge badge-warning'>".LAN_USERFRIEND_22."</span>";
        case 0:
            return "<span class='badge badge-secondary'>Rejeitado</span>";
        default:
            return '';
    }
}

    // Aceitar pedidos → status = 2 (amigos)
    public function actionAccept($ids)
    {
        foreach ($ids as $id) {
//            e107::getDb()->update('user_friends', ['status' => 2], "from_user={$id} OR to_user={$id}");
    uf_set_friendship_status($id, USERID, 2);
        }
        e107::getMessage()->add('Pedidos aceites.');
    }
*/
    // Rejeitar pedidos → status = 0 (rejeitado)
/*--
    public function actionReject($ids)
    {
        foreach ($ids as $id) {
--*/
            // Se já existir, atualiza para status = 0
/*
            if(e107::getDb()->count('user_friends', 'from_user', "from_user={$id} AND to_user=".USERID)) {
                e107::getDb()->update('user_friends', ['status' => 0], "from_user={$id} AND to_user=".USERID);
            } else {
                // Se não existir, insere como rejeitado
                e107::getDb()->insert('user_friends', [
                    'from_user' => $id,
                    'to_user'   => USERID,
                    'created'   => time(),
                    'status'    => 0
                ]);
            }
*/
/*--
            uf_set_friendship_status($id, USERID, 0);
        }
        e107::getMessage()->add('Pedidos rejeitados.');
    }
--*/
    /*
    public function actionPending($ids)
    {
        foreach ($ids as $id) {
            // Voltar a pendente = status 1
    uf_set_friendship_status($from, $to, 1);
        }

        e107::getMessage()->add('Pedidos marcados como pendentes.');
    }
*/
    /*
public function options($row)
{
    return "
        <a class='btn btn-success'>Aceitar</a>
        <a class='btn btn-warning'>Pendente</a>
        <a class='btn btn-danger'>Rejeitar</a>
    ";
}
*/

}

// Inicializa a UI
new user_friends_Adminarea();

// Renderiza

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
