<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for blank plugin
**
*/


if(!class_exists("user_friends_setup"))
{
	require_once(e_PLUGIN . "user_friends/includes/user_friends_admin_class.php");
	class user_friends_setup
	{

	    function install_pre($var)
		{
			// print_a($var);
			// echo "custom install 'pre' function<br /><br />";
		}

		/**
		 * For inserting default database content during install after table has been created by the blank_sql.php file.
		 */
		function install_post($var)
		{
    		user_friends_admin_class::syncExtendedFields();
		}

		function uninstall_options()
		{

		}


		function uninstall_post($var)
		{
			// print_a($var);
		}


		/*
		 * Call During Upgrade Check. May be used to check for existance of tables etc and if not found return TRUE to call for an upgrade.
		 *
		 * @return bool true = upgrade required; false = upgrade not required
		 */
		function upgrade_required()
		{

		}


		function upgrade_post($var)
		{
			// $sql = e107::getDb();
    		user_friends_admin_class::syncExtendedFields();
		}

	}

}