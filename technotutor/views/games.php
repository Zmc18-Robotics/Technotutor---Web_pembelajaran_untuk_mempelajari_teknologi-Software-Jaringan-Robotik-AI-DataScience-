<div class="wrap">
<h2>🎮 Game Edukasi Premium</h2>
<p class="muted">Latih fokus, hitung cepat, dan memori kerja. Skor terbaik disimpan per akun.</p>
<div class="grid game-grid">
<?php foreach([
 ['color','🎨','Cari Warna Berbeda','Pilih kotak yang warnanya paling berbeda. Tantangan meningkat setiap ronde; satu kesalahan mengakhiri permainan.'],
 ['math','➗','Hitung Kilat','Jawab soal hitungan yang terus dibuat secara acak sebelum waktu 60 detik habis.'],
 ['dots','🔵','Hitung Titik','Amati titik yang berkedip sekali, lalu jawab jumlahnya. Kesalahan mengurangi satu dari tiga hati.'],
] as [$name,$icon,$title,$description]):?>
<a class="card game-card" href="?p=game&amp;name=<?=h($name)?>"><div class="ic"><?=$icon?></div><h3><?=h($title)?></h3><p class="muted"><?=h($description)?></p><b>Skor terbaik: <?=(int)($D['game_scores'][$me][$name]??0)?></b></a>
<?php endforeach;?>
</div>
</div>
