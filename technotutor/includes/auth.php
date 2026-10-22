<?php
// Session, login, logout, proteksi halaman
$D=json_decode(file_get_contents($DB),true);
upgrade_course_data($D);
$csrfToken=$_SESSION['csrf']??'';
if($csrfToken===''){$_SESSION['csrf']=bin2hex(random_bytes(32));$csrfToken=$_SESSION['csrf'];}
$me=$_SESSION['u']??null;$U=$me?($D['users'][$me]??null):null;
$p=$_GET['p']??'home';$flash='';$flashType='err';
if($p=='logout'){session_destroy();header('Location:?p=login');exit;}
if($_SERVER['REQUEST_METHOD']=='POST'&&($_POST['a']??'')=='login'){
 $u=trim($_POST['u']);if(isset($D['users'][$u])&&password_verify($_POST['pw'],$D['users'][$u]['pw'])){$_SESSION['u']=$u;header('Location:'.(($D['users'][$u]['role']??'')==='tutor'?'?p=tutor':'?'));exit;}$flash='Username/password salah.';$p='login';}
if(!$U&&$p!='login'){header('Location:?p=login');exit;}
$adm=$U&&$U['role']=='admin';
if(in_array($p,['mat','quiz'],true)){
 $requestedMaterial=$D['mat'][(int)($_GET['id']??0)]??null;
 if($requestedMaterial&&course_locked($requestedMaterial,$me))$p='plans';
}
if($p==='games'||$p==='game'){
 if(!has_plan($me,'ultra'))$p='plans';
}
if($p==='tutor'&&!in_array($U['role'],['admin','tutor'],true)&&!has_plan($me,'ultra'))$p='plans';
