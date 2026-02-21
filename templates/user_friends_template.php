<?php
if (!defined('e107_INIT')) { exit; }

/* --------------------
 * Page Caption
 * -------------------- */
$USER_FRIENDS_WRAPPER['caption']['USERFRIEND_TAB_ACTIVE:type=friends'] = "
        {LAN=LAN_USERFRIEND_4}<span class='badge'>
        {USERFRIEND_COUNT=friends}{USERFRIEND_NEW_COUNT=type:friends_new}
    </span>
    <span hidden>{---}</span>

";
$USER_FRIENDS_WRAPPER['caption']['USERFRIEND_TAB_ACTIVE:type=sent'] = "
        {LAN=LAN_USERFRIEND_5}  <span class='badge'>
        {USERFRIEND_COUNT=sent}
    </span>
    <span hidden>{---}</span>

";
$USER_FRIENDS_WRAPPER['caption']['USERFRIEND_TAB_ACTIVE:type=received'] = "
        {LAN=LAN_USERFRIEND_6}<span class='badge'>
        {USERFRIEND_COUNT=received}{USERFRIEND_NEW_COUNT=type:received_new}
    </span>
    <span hidden>{---}</span>
";

$USER_FRIENDS_TEMPLATE['caption'] = '
{USERFRIEND_TAB_ACTIVE:type=friends}
{USERFRIEND_TAB_ACTIVE:type=sent}
{USERFRIEND_TAB_ACTIVE:type=received}
';

/* --------------------
 * Options
 * -------------------- */
$USER_FRIENDS_WRAPPER['options']['USERFRIEND_REMOVE:class=btn-sm btn-outline-danger icon-removefriend'] = "
    <button class='btn btn-sm dropdown-toggle dropdown-toggle-split'
        data-bs-toggle='dropdown'
        aria-expanded='false'>
        <span class='visually-hidden'>Toggle Dropdown</span>
    </button>
    <ul class='dropdown-menu dropdown-menu-end ps-2 pe-2'>
        <li>{---}</li>
    </ul>
";
$USER_FRIENDS_WRAPPER['options']['USERFRIEND_DECLINE:class=btn-sm btn-danger icon-removefriend'] = "
    <button class='btn btn-sm dropdown-toggle dropdown-toggle-split'
        data-bs-toggle='dropdown'
        aria-expanded='false'>
        <span class='visually-hidden'>Toggle Dropdown</span>
    </button>
    <ul class='dropdown-menu dropdown-menu-end ps-2 pe-2'>
        <span class='d-flex'>{---}
";
$USER_FRIENDS_WRAPPER['options']['USERFRIEND_ACCEPT:class=btn-sm btn-success icon-addfriend'] = "
        &nbsp;{---}</span>
    </ul>
";

$USER_FRIENDS_TEMPLATE['options'] =  "
<span class='btn-group'>
{USERFRIEND_ADD:class=btn-sm btn-default icon-addfriend}
{USERFRIEND_PENDING:class=btn-sm btn-secondary icon-pendingfriend}
{USERFRIEND_ACCEPTED:class=btn-sm btn-success icon-friends}
{USERFRIEND_DECLINE:class=btn-sm btn-danger icon-removefriend}
{USERFRIEND_ACCEPT:class=btn-sm btn-success icon-addfriend}
{USERFRIEND_REMOVE:class=btn-sm btn-outline-danger icon-removefriend}
</span>
";

$USER_FRIENDS_TEMPLATE['options_main'] =  "
{USERFRIEND_ACCEPT:class=btn-sm btn-success icon-addfriend list-button}
{USERFRIEND_DECLINE:class=btn-sm btn-danger icon-removefriend list-button}
{USERFRIEND_REMOVE:class=btn-sm btn-outline-danger icon-removefriend list-button}
";

$USER_FRIENDS_TEMPLATE['options_action'] =  "
{USERFRIEND_SENT:class=btn-sm btn-info icon-sentfriend}
{USERFRIEND_CANCELED:class=btn-sm btn-warning icon-removefriend}
{USERFRIEND_REMOVED:class=btn-sm btn-warning icon-removefriend}
{USERFRIEND_OK:class=btn-sm btn-success icon-friends}
{USERFRIEND_NOTOK:class=btn-sm btn-danger icon-refusedfriend}
";

/* --------------------
 * Page
 * -------------------- */

$USER_FRIENDS_WRAPPER['page']['USERFRIEND_ITEMS'] = '
<ul class="userfriends row list-unstyled g-2">
    {---}
</ul>

<div class="pt-3">
    {USERFRIEND_PAGINATION}
</div>
';

$USER_FRIENDS_WRAPPER['edit_page']['USERFRIEND_ITEMS'] = '
  <table class="table table-hover user-list table-striped table-condensed align-middle table-responsive border">
<colgroup>
    <col>
    <col style="width: 10%">
    <col style="width: 20%">
    <col style="width: 40%">
</colgroup>
<thead>
    <tr>
        <th>{LAN=LAN_USER}</th>
        <th>{LAN=LAN_DATE}</th>
        <th class="text-center">{LAN=LAN_STATUS}<br>
        <small>({LAN=USER_65})</small></th>
        <th class="text-end">&nbsp;</th>
    </tr>
</thead>
<tbody class="userfriends layout-{USERFRIEND_LAYOUT}">
{---}
</tbody>

</table>

<div>
    {USERFRIEND_PAGINATION}
</div>
';

$USER_FRIENDS_WRAPPER['page']['USERFRIEND_TABS'] = '
        <div class="card-header pb-0">
{---}
        </div>
';

$USER_FRIENDS_TEMPLATE['page'] = '
<div class="user_friends tabs-wrapper">
    <div class="card spt_rowuborders with-nav-tabs pt-2 bg-dark">
        {USERFRIEND_TABS}
        {USERFRIEND_MESSAGE}
        {USERFRIEND_ITEMS}
    </div>
</div>';

/* --------------------
 * Tabs
 * -------------------- */
$USER_FRIENDS_WRAPPER['tabs']['USERFRIEND_NEW_COUNT'] = ' / <span class="text-danger fw-bold ms-1">{---}</span>';

$USER_FRIENDS_TEMPLATE['tabs'] = '
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE:type=friends}" href="{USERFRIEND_URL=friends}">
            {LAN=LAN_USERFRIEND_4}
                <small><span class="badge">
        {USERFRIEND_COUNT=friends}{USERFRIEND_NEW_COUNT=type:friends_new}
    </span></small>
        </a>
    {USERFRIEND_NEW_ICON=type:friends_new}
    </li>
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE:type=sent}" href="{USERFRIEND_URL=sent}">
            {LAN=LAN_USERFRIEND_5}
                            <small><span class="badge">
        {USERFRIEND_COUNT=sent}
    </span></small>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {USERFRIEND_TAB_ACTIVE:type=received}" href="{USERFRIEND_URL=received}">
            {LAN=LAN_USERFRIEND_6}
                            <small><span class="badge">
        {USERFRIEND_COUNT=received}{USERFRIEND_NEW_COUNT=type:received_new}
    </span></small>
        </a>
    {USERFRIEND_NEW_ICON=type:received_new}
    </li>
</ul>';

/* --------------------
 * Item
 * -------------------- */
$USER_FRIENDS_TEMPLATE['normal_item'] = '
<li class="col-12 col-sm-6 col-md-4 col-lg-3">
        {EUSER_INFOCARD=doubleinline}
</li>
';

$USER_FRIENDS_TEMPLATE['edit_item'] = '
<tr data-userfriend-container>
    <td class="position-relative">
        {EUSER_INFOCARD=doubleinline}
        {USERFRIEND_ITEM_NEW_ICON}
    </td>
    <td>
        {USERFRIEND_CREATED}
    </td>
    <td class="text-center">
        {USERFRIEND_STATUS_LABEL}<br>
        <span class="text-muted small mt-1">({USER_LASTVISIT})</span>
    </td>
    <td class="text-end text-nowrap">
        {USER_SENDPM:class=sendpm btn btn-sm btn-light icon-sendmessage&glyph=none}
        <div class="btn-group btn-group-sm">
            {USERFRIEND_OPTIONS}
        </div>
    </td>
</tr>
';
