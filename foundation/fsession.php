<?php
//session
function set_session($k,$v){
	global $session_prefix;
	if(is_NULL($v)||$v==='')
	{
		unset($_SESSION[$session_prefix.$k]);
	}
	else
	{
		$_SESSION[$session_prefix.$k] = $v;
	}
}

function get_session($k){
	global $session_prefix;
	if(isset($_SESSION[$session_prefix.$k])) return $_SESSION[$session_prefix.$k];
	return null;
}

function set_sess_admin($v)
{
	set_session('admin_name',$v);
}

function get_sess_admin()
{
	return get_session('admin_name');
}

function set_sess_userid($v)
{
	set_session('user_id',$v);
}

function get_sess_userid()
{
	return get_session('user_id');
}

function set_sess_mypals($v)
{
	set_session('mypals',$v);
}

function get_sess_mypals()
{
	return get_session('mypals');
}

function set_sess_photols($v)
{
	set_session('photo_lis',$v);
}
function get_sess_photols()
{
	return get_session('photo_lis');
}
function set_sess_username($v)
{
	set_session('user_name',$v);
}
function get_sess_username()
{
	return get_session('user_name');
}

function set_sess_usersex($v)
{
	set_session('user_sex',$v);
}
function get_sess_usersex()
{
	return get_session('user_sex');
}
function set_sess_cgroup($v)
{
	set_session('create_group',$v);
}
function get_sess_cgroup()
{
	return get_session('create_group');
}
function set_sess_jgroup($v)
{
	set_session('join_group',$v);
}
function get_sess_jgroup()
{
	return get_session('join_group');
}
function set_sess_userico($v)
{
	set_session('user_ico',$v);
}
function get_sess_userico()
{
	return get_session('user_ico');
}
function set_sess_online($v)
{
	set_session('online',$v);
}
function get_sess_online()
{
	return get_session('online');
}
function set_sess_preloginurl($v)
{
	set_session('pre_login_url',$v);
}
function get_sess_preloginurl()
{
	return get_session('pre_login_url');
}
function set_sess_plugins($v)
{
	set_session('use_plugins',$v);
}
function get_sess_plugins()
{
	return get_session('use_plugins');
}
function set_sess_apps($v)
{
	set_session('use_apps',$v);
}
function get_sess_apps()
{
	return get_session('use_apps');
}
function set_sess_rights($v)
{
	set_session('rights',$v);
}
function get_sess_rights()
{
	return get_session('rights');
}
?>