<?php
$m=$D['mat'][(int)($_GET['id']??0)]??null;
if(!$m)die('Materi tidak ditemukan.');
$slides=$m['slides']??[];
$s=max(0,min(count($slides)-1,(int)($_GET['s']??0)));
$sl=$slides[$s];
$last=$s===count($slides)-1;
$videoId=yt($sl['yt']??'');
$slideImage=course_slide_image($m['field'],$m['id'],$s+1);
$imageUrl=$slideImage['url']??hero_gallery_fallback();
?>
<div class="wrap">
<div class="course-actions">
<a class="btn g" href="?">← Kembali ke menu utama</a>
<a class="btn g" href="?p=field&amp;f=<?=h($m['field'])?>">← <?=h($F[$m['field']][0])?></a>
</div>
<h2 style="margin-top:0"><?=h($m['title'])?> <span class="stars"><?=stars($m['level'])?></span> <?=isdone($m['id'])?'✅':''?></h2>
<div class="muted">Slide <?=$s+1?> dari <?=count($slides)?></div>
<div class="bar"><div style="width:<?=($s+1)/count($slides)*100?>%"></div></div>
<div class="slide">
<div class="img">
<?php if($imageUrl):?><img src="<?=h($imageUrl)?>" alt="Gambar <?=h($m['title'])?> — slide <?=$s+1?>" loading="lazy"><?php else:?><span class="muted">🖼 Tambahkan foto ke folder galeri beranda untuk mengaktifkan gambar cadangan.</span><?php endif;?>
</div>
<h3><?=h($sl['title'])?></h3>
<div class="body"><?=h($sl['body'])?></div>
<div class="youtube-resource">
<b>Video YouTube untuk slide ini</b>
<?php if($videoId):?>
<a href="https://www.youtube.com/watch?v=<?=h($videoId)?>" target="_blank" rel="noopener noreferrer">▶ Tonton video di YouTube</a>
<iframe src="https://www.youtube.com/embed/<?=h($videoId)?>" title="Video YouTube: <?=h($sl['title'])?>" allowfullscreen loading="lazy"></iframe>
<?php else:?>
<span class="muted">-</span>
<?php endif;?>
</div>
</div>
<div class="nv">
<?php if($s>0):?>
<a class="btn g" href="?p=mat&amp;id=<?=$m['id']?>&amp;s=<?=$s-1?>">← Sebelumnya</a>
<?php else:?>
<span></span>
<?php endif;?>
<?php if(!$last):?>
<a class="btn" href="?p=mat&amp;id=<?=$m['id']?>&amp;s=<?=$s+1?>">Slide berikutnya →</a>
<?php elseif(!empty($m['quiz'])):?>
<a class="btn" href="?p=quiz&amp;id=<?=$m['id']?>">📝 Kerjakan <?=count($m['quiz'])?> soal</a>
<?php else:?>
<form method="post"><input type="hidden" name="a" value="done"><input type="hidden" name="id" value="<?=$m['id']?>"><button class="btn">✓ Tandai selesai</button></form>
<?php endif;?>
</div>
<?php if($adm):?><p><a class="btn g" href="?p=edit&amp;id=<?=$m['id']?>">✎ Edit materi</a></p><?php endif;?>
</div>
