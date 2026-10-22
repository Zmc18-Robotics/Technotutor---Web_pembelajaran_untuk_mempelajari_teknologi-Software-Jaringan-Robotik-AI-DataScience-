<?php
$canAsk=($U['role']??'')==='mhs'&&has_plan($me,'ultra');
$canReply=in_array($U['role']??'',['admin','tutor'],true);
$questions=$D['tutor_forum'];krsort($questions);
?>
<div class="wrap">
<h2>💬 Chat to Tutor</h2>
<?php if($flash):?><div class="<?=h($flashType??'err')?>"><?=h($flash)?></div><?php endif;?>
<div class="card"><p>Mahasiswa Ultra PRO MAX dapat menghubungi tutor secara langsung melalui WhatsApp atau menulis pertanyaan di forum ini. Hindari mengirim password, data pribadi, atau informasi sensitif.</p>
<?php if($canAsk):?><h3>Hubungi tutor melalui WhatsApp</h3><p class="muted">Pilih salah satu tutor untuk membuka WhatsApp. Nomor akan terlihat di aplikasi WhatsApp setelah Anda melanjutkan.</p>
<div class="tutor-whatsapp-list">
<?php foreach([
 ['Tutor Andi','6289510809179','+62 895-1080-9179'],
 ['Tutor Sari','6282112227425','+62 821-1222-7425'],
] as [$tutorName,$phone,$displayPhone]):
 $message='Halo '.$tutorName.', saya pengguna Technotutor Ultra PRO MAX. Saya ingin bertanya tentang materi: ';
 $whatsappUrl='https://wa.me/'.$phone.'?text='.rawurlencode($message);
?>
<a class="btn whatsapp-button" href="<?=h($whatsappUrl)?>" target="_blank" rel="noopener noreferrer">Chat <?=h($tutorName)?> · <?=h($displayPhone)?> via WhatsApp</a>
<?php endforeach;?>
</div><?php endif;?>
<?php if($canAsk):?><form method="post"><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="asktutor"><label>Pertanyaan Anda<textarea name="question" maxlength="3000" required placeholder="Jelaskan materi yang sedang dipelajari dan bagian yang membingungkan."></textarea></label><button class="btn" type="submit">Kirim pertanyaan</button></form>
<?php elseif(!$canReply):?><p class="muted">Forum tanya tutor tersedia untuk paket Ultra PRO MAX. <a href="?p=plans">Lihat paket</a></p><?php endif;?></div>
<?php if(!$questions):?><div class="card" style="margin-top:16px">Belum ada pertanyaan. Jadilah yang pertama memulai diskusi.</div><?php endif;?>
<?php foreach($questions as $question):?>
<article class="card tutor-question">
<div class="muted"><?=h($question['name']??$question['username'])?> · <?=h($question['created_at']??'')?></div>
<p class="tutor-message"><?=h($question['question'])?></p>
<?php foreach($question['replies']??[] as $reply):?><div class="tutor-reply"><b><?=h($reply['name']??'Tutor')?> · <?=h(ucfirst($reply['role']??'tutor'))?></b><p class="tutor-message"><?=h($reply['text'])?></p><small class="muted"><?=h($reply['created_at']??'')?></small></div><?php endforeach;?>
<?php if($canReply):?><form method="post" class="tutor-reply-form"><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="replytutor"><input type="hidden" name="question_id" value="<?=(int)$question['id']?>"><label>Balas sebagai <?=h($U['name'])?><textarea name="reply" maxlength="3000" required></textarea></label><button class="btn" type="submit">Kirim balasan</button></form><?php endif;?>
</article>
<?php endforeach;?>
</div>
