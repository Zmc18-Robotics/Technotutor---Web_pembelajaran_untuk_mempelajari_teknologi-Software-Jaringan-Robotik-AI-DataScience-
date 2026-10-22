<div class="wrap" style="max-width:760px"><h2>⚙ Settings</h2><?php if($flash):?><div class="<?=h($flashType??'ok2')?>"><?=h($flash)?></div><?php endif;?>
<div class="card"><p><b>Nama:</b> <?=h($U['name'])?><br><b>Username:</b> <?=h($me)?><br><b>Role:</b> <?=h(ucfirst($U['role']))?><?php if(($U['role']??'')==='mhs'):?><br><b>Paket:</b> <?=h(($U['plan']??'')==='ultra'?'Ultra PRO MAX':(($U['plan']??'')==='pro'?'Pro':'Gratis'))?><?php endif;?></p>
<?php if(($U['role']??'')==='mhs'):?><h3>Progres</h3><?php foreach($F as $k=>$v){[$n,$t]=fprog($k);echo "<p>{$v[1]} {$v[0]}: $n/$t ".($t&&$n==$t?"<a class=btn href='?p=cert&f=$k'>Sertifikat</a>":'')."</p>";}?><?php endif;?>
<h3>Ganti Password</h3><form method="post"><input type="hidden" name="a" value="pw"><input type="password" name="old" placeholder="Password lama"><input type="password" name="new" placeholder="Password baru"><button class=btn>Simpan</button></form></div>
<?php if($adm){?><div class="card" style="margin-top:16px"><h3>Tambah Akun (Admin)</h3><form method="post"><input type="hidden" name="a" value="adduser"><input name="nu" placeholder="Username"><input name="nn" placeholder="Nama"><input name="np" placeholder="Password"><select name="nr"><option value="mhs">Mahasiswa</option><option value="admin">Admin</option></select><button class=btn>Tambah</button></form>
<p class=muted>Akun: <?=h(implode(', ',array_keys($D['users'])))?></p>
<h3>Pengajuan Paket Premium</h3>
<?php $requests=array_filter($D['plan_requests'],fn($request)=>($request['status']??'')==='pending');if(!$requests):?><p class=muted>Tidak ada pengajuan yang menunggu.</p><?php else:foreach($requests as $requestId=>$request):$requestUser=$D['users'][$request['username']]??null;?>
<div class="card plan-request"><b><?=h($requestUser['name']??$request['username'])?></b> · <?=h(strtoupper($request['plan']))?> · <span class=muted><?=h($request['created_at']??'')?></span>
<div class=course-actions><form method="post"><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="reviewplan"><input type="hidden" name="request_id" value="<?=(int)$requestId?>"><input type="hidden" name="decision" value="approve"><button class="btn" type="submit">Aktifkan paket</button></form>
<form method="post" onsubmit="return confirm('Tolak pengajuan paket ini?')"><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="reviewplan"><input type="hidden" name="request_id" value="<?=(int)$requestId?>"><input type="hidden" name="decision" value="reject"><button class="btn r" type="submit">Tolak</button></form></div></div>
<?php endforeach;endif;?>
<h3>Reset Data Mahasiswa</h3>
<p class=muted>Reset akan menghapus progres belajar, status selesai, dan nilai kuis mahasiswa yang dipilih. Akun dan password tidak berubah.</p>
<?php $students=array_filter($D['users'],fn($user)=>($user['role']??'')==='mhs');if(!$students):?><p class=muted>Belum ada akun mahasiswa.</p><?php else:?>
<div class="grid"><?php foreach($students as $username=>$student):?><div class="card">
<b><?=h($student['name']??$username)?></b><br><small class=muted>@<?=h($username)?></small>
<form method="post" onsubmit="return confirm('Reset seluruh progres dan nilai kuis mahasiswa ini? Tindakan ini tidak dapat dibatalkan.');">
<input type="hidden" name="csrf" value="<?=h($csrfToken)?>">
<input type="hidden" name="a" value="resetstudentdata"><input type="hidden" name="student" value="<?=h($username)?>">
<button class="btn r" type="submit">Reset data mahasiswa</button>
</form></div><?php endforeach;?></div><?php endif;?></div><?php }?></div>
