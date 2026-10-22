<?php $f=$_GET['f']??'software';if(!isset($F[$f]))$f='software';[$n,$t]=fprog($f); ?>
<div class="wrap"><h2><?=$F[$f][1].' '.$F[$f][0]?> <span class="muted">— <?=$F[$f][2]?></span></h2>
<div class="card" style="margin-bottom:20px"><b><?=$n?> / <?=$t?> materi selesai</b><div class=bar><div style="width:<?=$t?$n/$t*100:0?>%"></div></div>
<?php if($t&&$n===$t):?><p><a class=btn href="?p=cert&amp;f=<?=h($f)?>">🏅 Lihat Sertifikat</a></p><?php endif;?></div>
<?php if($adm):?><a class=btn href="?p=edit&amp;f=<?=h($f)?>" style="margin-bottom:16px">+ Tambah Materi</a><?php endif;?>
<?php $ms=array_filter($D['mat'],fn($m)=>($m['field']??'')===$f);usort($ms,fn($a,$b)=>($a['level']<=>$b['level'])?:($a['id']<=>$b['id']));?>
<?php foreach($ms as $m):$locked=course_locked($m,$me);$href=$adm?'?p=mat&amp;id='.(int)$m['id']:($locked?'?p=plans':'?p=mat&amp;id='.(int)$m['id']);?>
<a class="row" href="<?=$href?>">
<span class="chk <?=isdone($m['id'])?'ok':''?>"><?=isdone($m['id'])?'✓':($locked?'🔒':'')?></span>
<div style="flex:1"><b><?=h($m['title'])?></b><div class="muted"><?=count($m['slides'])?> slide · <?=count($m['quiz'])?> soal<?=!empty($m['premium'])?' · ⭐ Premium':''?><?=$locked?' · Buka paket Pro untuk mengakses':''?></div></div>
<span class="stars"><?=stars($m['level'])?></span></a>
<?php endforeach;?></div>
