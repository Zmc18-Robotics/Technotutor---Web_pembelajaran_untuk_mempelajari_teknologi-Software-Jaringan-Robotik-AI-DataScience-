<?php $m=$D['mat'][(int)$_GET['id']];$r=$_GET['r']??null;$tot=count($m['quiz']); ?>
<div class="wrap" style="max-width:760px"><div class="course-actions"><a class="btn g" href="?">← Kembali ke menu utama</a><a class="btn g" href="?p=mat&amp;id=<?=$m['id']?>&amp;s=<?=max(0,count($m['slides'])-1)?>">← Kembali ke materi</a></div><h2>Soal: <?=h($m['title'])?></h2>
<?php if($r!==null){$ok=$r>=ceil($tot*.7);echo "<div class='".($ok?'ok2':'err')."'>Skor Anda: $r / $tot — ".($ok?'LULUS, materi tercentang ✓':'Belum lulus (minimal 70%). Silakan ulangi.')."</div>";}
if(!$tot)echo "<p class=muted>Belum ada soal.</p>";else{?>
<form method="post"><input type="hidden" name="a" value="quiz"><input type="hidden" name="id" value="<?=$m['id']?>">
<?php foreach($m['quiz'] as $i=>$q){echo "<div class=card style='margin-bottom:14px'><b>".($i+1).". ".h($q['q'])."</b>";foreach($q['o'] as $j=>$o)echo "<label class=opt><input type=radio name=q$i value=$j required>".chr(65+$j).". ".h($o)."</label>";echo "</div>";}?>
<button class=btn><?=$r!==null?'Ulangi & Kumpulkan':'Kumpulkan Jawaban'?></button> <a class="btn g" href="?p=mat&id=<?=$m['id']?>">Review materi</a></form><?php }?></div>
