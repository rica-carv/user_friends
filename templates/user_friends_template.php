<?php
if (!defined('e107_INIT')) { exit; }

/* --------------------
 * Options
 * -------------------- */
$USER_FRIENDS_TEMPLATE['options'] =  "
{USERFRIEND_ADD:class=btn-sm btn-default icon-addfriend}
{USERFRIEND_SENT:class=btn-sm btn-info icon-sentfriend}
{USERFRIEND_PENDING:class=btn-sm btn-secondary icon-pendingfriend}
{USERFRIEND_ACCEPTED:class=btn-sm btn-success icon-friends}
{USERFRIEND_REMOVE:class=btn-sm btn-danger icon-removefriend}
";
/*
$USER_FRIENDS_TEMPLATE['options_forum'] =  "
{USERFRIEND_ADD:class=btn-sm btn-default icon-addfriend btn-icon-only ps-3 pe-3}
{USERFRIEND_SENT:class=btn-sm btn-info icon-sentfriend btn-icon-only ps-3 pe-3}
{USERFRIEND_PENDING:class=btn-sm btn-secondary icon-pendingfriend btn-icon-only ps-3 pe-3}
{USERFRIEND_ACCEPTED:class=btn-sm btn-success icon-friends btn-icon-only ps-3 pe-3}
{USERFRIEND_REMOVE:class=btn-sm btn-danger icon-removefriend btn-icon-only ps-3 pe-3}
";
*/

/* --------------------
 * Page
 * -------------------- */
$USER_FRIENDS_TEMPLATE['page'] = '
<div class="user_friends">
<div class="card spt_rowuborders with-nav-tabs bg-secondary">
    <div class="card-header pb-0">
        {USERFRIEND_TABS}
    </div>
 <table class="table table-hover user-list align-middle table-responsive">

<thead>
    <tr>
        <th>{LAN=LAN_USER}</th>
        <th>{LAN=LAN_CREATED}</th>
        <th class="text-center">{LAN=LAN_STATUS}</th>
        <th>{LAN=LAN_EMAIL}</th>
        <th class="text-end">&nbsp;</th>
    </tr>
</thead>
<tbody class="userfriends layout-{USERFRIEND_LAYOUT}">
                {USERFRIEND_ITEMS}
</tbody>

</table>

            <div class="pt-3">
                {USERFRIEND_PAGINATION}
            </div>
</div>
</div>';


/* --------------------
 * Tabs
 * -------------------- */
$USER_FRIENDS_TEMPLATE['tabs'] = '
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE=friends}" href="{USERFRIEND_URL=friends}">
            {LAN=LAN_USERFRIEND_4}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE=sent}" href="{USERFRIEND_URL=sent}">
            {LAN=LAN_USERFRIEND_5}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE=received}" href="{USERFRIEND_URL=received}">
            {LAN=LAN_USERFRIEND_6}
        </a>
    </li>
</ul>';

/* --------------------
 * Item
 * -------------------- */
$USER_FRIENDS_TEMPLATE['item'] = '
<tr>
    <td>
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">
                {USER_AVATAR}
            </div>
            <div>
                <a href="{USER_URL}" class="user-link">{USER_NAME}</a>
                <div class="user-subhead text-muted small">
                    {USER_TITLE}
                </div>
            </div>
        </div>
    </td>

    <td>
        {USERFRIEND_CREATED}
    </td>

    <td class="text-center">
        {USERFRIEND_STATUS_LABEL}
    </td>

    <td>
        <a href="mailto:{USER_EMAIL}">{USER_EMAIL}</a>
    </td>

    <td class="text-end">
        <div class="btn-group btn-group-sm">
            {USERFRIEND_OPTIONS}
        </div>
    </td>
</tr>
';




/*

<div class='list-group-item'>
    <div class='d-flex justify-content-between align-items-center'>
        <div>
            {EUSER_INFOCARD}
            <div class='small text-muted'>{USER_FRIEND_STATUS}</div>
        </div>
        <div class='btn-group btn-group-sm'>
            {USERFRIEND_OPTIONS}
        </div>
    </div>
</div>
";
*/