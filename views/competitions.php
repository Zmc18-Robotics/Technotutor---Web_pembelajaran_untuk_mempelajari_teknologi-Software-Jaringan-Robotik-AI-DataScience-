<?php
$competitions=$D['competitions']??[];
$editing=$adm?($competitions[(int)($_GET['edit']??0)]??null):null;
usort($competitions,fn($a,$b)=>(int)$b['id']<=>(int)$a['id']);
?>
<div class="wrap">
<div class="course-actions">
<a class="btn g" href="?">← Kembali ke menu utama</a>
<h2 style="margin:0">🏆 Informasi Lomba</h2>
</div>
<p class="muted">Temukan kompetisi teknologi, baca ringkasan informasinya, lalu kunjungi tautan penyelenggara untuk memastikan syarat dan jadwal terbaru.</p>

<?php if($adm):?>
<div class="card competition-editor">
<h3><?= $editing?'Ubah informasi lomba':'Tambah informasi lomba' ?></h3>
<?php if($flash):?><div class="err"><?=h($flash)?></div><?php endif;?>
<form method="post">
<input type="hidden" name="a" value="savecompetition">
<input type="hidden" name="id" value="<?=h($editing['id']??0)?>">
<label>Nama lomba<input name="title" required value="<?=h($editing['title']??'')?>"></label>
<div class="competition-fields">
<label>Bidang/kategori<input name="category" value="<?=h($editing['category']??'')?>" placeholder="Contoh: Pemrograman, robotika, data"></label>
<label>Penyelenggara<input name="organizer" value="<?=h($editing['organizer']??'')?>"></label>
<label>Batas pendaftaran/jadwal<input name="deadline" value="<?=h($editing['deadline']??'')?>" placeholder="Isi tanggal atau status jadwal"></label>
</div>
<label>Deskripsi<textarea name="description" required><?=h($editing['description']??'')?></textarea></label>
<label>Tautan informasi resmi<input type="url" name="url" required value="<?=h($editing['url']??'')?>" placeholder="https://..."></label>
<label>Nama gambar (opsional)<input name="image" value="<?=h($editing['image']??'')?>" placeholder="contoh-lomba.jpg"></label>
<p class="muted">Simpan gambar lomba di <code>assets/img/competitions/</code>, lalu isi nama filenya di sini. Format: JPG, PNG, atau WebP.</p>
<button class="btn">Simpan lomba</button>
<?php if($editing):?><a class="btn g" href="?p=competitions">Batal</a><?php endif;?>
</form>
</div>
<?php endif;?>

<?php if(!$competitions):?>
<div class="card competition-empty"><div class="ic">🏆</div><h3>Belum ada informasi lomba</h3><p class="muted"><?php if($adm):?>Tambahkan informasi lomba melalui formulir di atas.<?php else:?>Informasi lomba akan ditampilkan di sini setelah ditambahkan oleh pengelola.<?php endif;?></p></div>
<?php else:?>
<div class="competition-grid">
<?php foreach($competitions as $event):
 $image=$event['image']??'';
 $hasImage=$image!==''&&preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]*\.(jpg|jpeg|png|webp)$/i',$image)&&is_file(ROOT.'/assets/img/competitions/'.$image);
 $url=$event['url']??'';$scheme=strtolower(parse_url($url,PHP_URL_SCHEME)??'');
 $safeUrl=filter_var($url,FILTER_VALIDATE_URL)&&in_array($scheme,['http','https'],true)?$url:'#';
?>
<article class="card competition-card">
<?php if($hasImage):?><img class="competition-cover" src="assets/img/competitions/<?=rawurlencode($image)?>" alt="Gambar <?=h($event['title'])?>" loading="lazy"><?php else:?><div class="competition-cover competition-placeholder">🏆</div><?php endif;?>
<div class="competition-content">
<?php if(!empty($event['category'])):?><span class="tag"><?=h($event['category'])?></span><?php endif;?>
<h3><?=h($event['title'])?></h3>
<?php if(!empty($event['organizer'])):?><p class="muted">Penyelenggara: <?=h($event['organizer'])?></p><?php endif;?>
<?php if(!empty($event['deadline'])):?><p class="competition-deadline">📅 <?=h($event['deadline'])?></p><?php endif;?>
<p><?=nl2br(h($event['description']))?></p>
<a class="btn" href="<?=h($safeUrl)?>" target="_blank" rel="noopener noreferrer">Lihat informasi lomba ↗</a>
<?php if($adm):?>
<div class="competition-admin">
<a class="btn g" href="?p=competitions&amp;edit=<?=(int)$event['id']?>">Edit</a>
<form method="post" onsubmit="return confirm('Hapus informasi lomba ini?')"><input type="hidden" name="a" value="delcompetition"><input type="hidden" name="id" value="<?=(int)$event['id']?>"><button class="btn r">Hapus</button></form>
</div>
<?php endif;?>
</div>
</article>
<?php endforeach;?>
</div>
<?php endif;?>
</div>
