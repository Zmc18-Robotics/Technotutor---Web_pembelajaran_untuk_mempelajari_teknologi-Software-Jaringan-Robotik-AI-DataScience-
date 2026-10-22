<?php
$galleryFiles=[];
$galleryFiles=glob(ROOT.'/assets/img/hero-gallery/*')?:[];
$galleryFiles=array_values(array_filter($galleryFiles,fn($file)=>is_file($file)&&in_array(strtolower(pathinfo($file,PATHINFO_EXTENSION)),['jpg','jpeg','png','webp'],true)));
$galleryImages=array_map(fn($file)=>'assets/img/hero-gallery/'.rawurlencode(basename($file)),$galleryFiles);
?>
<section class="home-hero">
<div class="home-hero-inner">
<div class="home-copy">
<small>TECHNOTUTOR.AC.ID</small>
<h1>Pelajari Teknologi,<br>Bangun Masa Depan Anda</h1>
<p>Software · Jaringan · Robotik &amp; AI · Data Science</p>
<a class="btn" href="#bidang">MULAI BELAJAR</a>
</div>
<div class="hero-gallery" aria-label="Galeri foto teknologi">
<?php if($galleryImages):?>
<img id="hero-gallery-image" src="<?=h($galleryImages[0])?>" alt="Foto teknologi pilihan" fetchpriority="high">
<div class="hero-gallery-caption"><span>Teknologi untuk masa depan</span><span class="hero-gallery-count"><?=count($galleryImages)?> foto</span></div>
<?php else:?>
<div class="hero-gallery-empty"><span>💡</span><b>Galeri Teknologi</b><small>Tambahkan foto ke folder <code>assets/img/hero-gallery/</code></small></div>
<?php endif;?>
</div>
</div>
</section>
<div class="wrap">
<div class="home-section-heading"><div><span class="tag">MULAI DARI SINI</span><h2 id="bidang">Pilih bidang belajar</h2></div><a class="btn g" href="?p=competitions">🏆 Lihat informasi lomba</a></div>
<div class="grid">
<?php foreach($F as $k=>$v){[$n,$t]=fprog($k);echo "<a class=card href='?p=field&f=$k'><div class=ic>{$v[1]}</div><h3>{$v[0]}</h3><div class=muted>{$v[2]}</div><div class=bar><div style='width:".($t?$n/$t*100:0)."%'></div></div><div class=muted style='margin-top:6px'>$n / $t materi selesai</div></a>";}?>
</div>
<div class="grid home-benefits"><?php foreach([['🎓','Materi Bertahap','Delapan slide atau lebih dengan uraian yang membantu belajar mandiri.'],['▶️','Video YouTube','Setiap slide menampilkan tautan video jika tersedia.'],['📝','Soal Latihan','Uji pemahaman dengan kuis di akhir setiap materi.'],['🏅','Sertifikat','Selesaikan satu bidang untuk membuka sertifikat.']] as $x)echo "<div class=card><div class=ic>$x[0]</div><h3>$x[1]</h3><div class=muted>$x[2]</div></div>";?></div>
</div>
<?php if(count($galleryImages)>1):?>
<script>
(() => {
    const images = <?=json_encode($galleryImages,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
    const image = document.getElementById('hero-gallery-image');
    if (!image || images.length < 2) return;
    let current = 0;
    window.setInterval(() => {
        let next;
        do { next = Math.floor(Math.random() * images.length); } while (next === current);
        current = next;
        image.classList.add('is-fading');
        window.setTimeout(() => {
            image.src = images[current];
            image.onload = () => image.classList.remove('is-fading');
            image.onerror = () => image.classList.remove('is-fading');
        }, 350);
    }, 1000);
})();
</script>
<?php endif;?>
