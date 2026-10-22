<?php $f=$_GET['f'];[$n,$t]=fprog($f);if($n<$t||!$t)die('<div class=wrap>Belum selesai semua materi.</div>'); ?>
<div class="wrap"><div class="cert"><p>SERTIFIKAT PENYELESAIAN</p><h1>TECHNOTUTOR</h1><p>diberikan kepada</p><h2><?=h($U['name'])?></h2><p>yang telah menyelesaikan seluruh materi bidang</p><h2><?=h($F[$f][0])?></h2><p><?=date('d F Y')?></p></div>
<p style="text-align:center" class="noprint"><button class=btn onclick="print()">🖨 Cetak / Simpan PDF</button></p></div>
