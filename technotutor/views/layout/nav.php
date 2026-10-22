
<nav><a href="?" title="Kembali ke menu utama"><b>TECHNO<i>TUTOR</i></b></a><a class="l" href="?">Menu utama</a>
<?php if(in_array($U['role']??'',['admin','mhs'],true)):?><a class="l <?=($p=='competitions'?'on':'')?>" href="?p=competitions">Info lomba</a>
<?php foreach($F as $k=>$v)echo "<a class='l ".(($_GET['f']??'')==$k?'on':'')."' href='?p=field&f=$k'>{$v[0]}</a>";endif;?>
<?php if(($U['role']??'')==='mhs'):?><a class="l <?=($p==='plans'?'on':'')?>" href="?p=plans">⭐ Paket Premium</a><?php endif;?>
<?php if(in_array($U['role']??'',['admin','tutor'],true)||has_plan($me,'ultra')):?><a class="l <?=($p==='tutor'?'on':'')?>" href="?p=tutor">💬 Chat Tutor</a><?php endif;?>
<?php if(($U['role']??'')==='mhs'&&has_plan($me,'ultra')):?><a class="l <?=in_array($p,['games','game'],true)?'on':''?>" href="?p=games">🎮 Game Edukasi</a><?php endif;?>
<span class="sp"></span><a class="l" href="?p=settings">⚙ <?=h($U['name'])?> (<?=h(ucfirst($U['role']))?>)</a><a class="btn g nav-logout" href="?p=logout">Logout</a></nav>
