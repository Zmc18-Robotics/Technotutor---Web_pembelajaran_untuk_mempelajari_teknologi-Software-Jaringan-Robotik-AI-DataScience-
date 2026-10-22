<?php
$currentPlan=$U['plan']??'';
$pendingRequest=null;
foreach($D['plan_requests'] as $request)if(($request['username']??'')===$me&&($request['status']??'')==='pending'){$pendingRequest=$request;break;}
?>
<div class="wrap">
<h2>Pilih Paket Premium</h2>
<?php if($flash):?><div class="<?=h($flashType??'err')?>"><?=h($flash)?></div><?php endif;?>
<div class="card plan-note"><b>Penawaran aktivasi</b><p>Harga berikut adalah harga penawaran. Belum ada pembayaran online di aplikasi ini: kirim pengajuan, lalu admin akan memeriksa dan mengaktifkan paket secara manual. Jangan mengirim data kartu atau password melalui forum.</p>
<p>Status paket Anda: <b><?=h($currentPlan==='ultra'?'Ultra PRO MAX':($currentPlan==='pro'?'Pro':'Gratis'))?></b>
<?php if($pendingRequest):?> · Pengajuan <b><?=h(strtoupper($pendingRequest['plan']))?></b> sedang menunggu admin.<?php endif;?></p></div>
<div class="plan-grid">
<?php foreach([
 ['pro','Pro','Rp129.000','Rp79.000','Diskon 39%','Akses ke seluruh 30 materi premium tambahan di setiap bidang, termasuk kuis dan progres belajar.'],
 ['ultra','Ultra PRO MAX','Rp249.000','Rp159.000','Diskon 36%','Semua akses Pro, chat to Tutor dengan dua tutor, serta tiga game edukasi premium.'],
] as [$key,$title,$normal,$price,$discount,$description]):
 $active=$key==='pro'?has_plan($me,'pro'):has_plan($me,'ultra');
 $blocked=$pendingRequest||$active;
?>
<article class="card plan-card <?=$key==='ultra'?'plan-featured':''?>">
<span class="tag"><?=h($discount)?></span><h3><?=h($title)?></h3>
<p><del class="muted"><?=h($normal)?></del> <strong class="plan-price"><?=h($price)?></strong></p>
<p><?=h($description)?></p>
<?php if($active):?><span class="btn g">Paket aktif</span>
<?php elseif($blocked):?><button class="btn g" disabled>Pengajuan menunggu</button>
<?php else:?><form method="post"><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="requestplan"><input type="hidden" name="plan" value="<?=h($key)?>"><button class="btn" type="submit">Ajukan aktivasi <?=h($title)?></button></form><?php endif;?>
</article>
<?php endforeach;?>
</div>
<p class="muted">Harga diskon adalah harga penawaran yang ditampilkan aplikasi. Pembayaran dan verifikasi transaksi belum terhubung ke payment gateway.</p>
</div>
