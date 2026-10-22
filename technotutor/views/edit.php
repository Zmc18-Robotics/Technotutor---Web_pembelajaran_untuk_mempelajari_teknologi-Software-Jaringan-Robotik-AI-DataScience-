<?php $id=(int)($_GET['id']??0);$m=$D['mat'][$id]??['id'=>0,'field'=>$_GET['f']??'software','title'=>'','level'=>1,'slides'=>[],'quiz'=>[]];
$st=implode("\n",array_map(fn($s)=>$s['title'].'|'.str_replace("\n",'\n',$s['body']).'||'.$s['yt'],$m['slides']));
$qt=implode("\n",array_map(fn($q)=>$q['q'].'|'.implode('|',$q['o']).'|'.$q['a'],$m['quiz']));
if(($p??'')==='edit'&&($_POST['a']??'')==='savemat'){
 $m['title']=$_POST['title']??$m['title'];$m['field']=$_POST['field']??$m['field'];$m['level']=$_POST['level']??$m['level'];
 $m['premium']=isset($_POST['premium'])?1:0;
 $st=$_POST['slides']??$st;$qt=$_POST['quiz']??$qt;
}
?>
<div class="wrap" style="max-width:860px"><div class="course-actions"><a class="btn g" href="?">← Kembali ke menu utama</a><h2><?=$id?'Edit':'Tambah'?> Materi</h2></div>
<?php if($flash): ?><div class="<?=h($flashType??'err')?>"><?=h($flash)?></div><?php endif ?>
<form method="post"><input type="hidden" name="a" value="savemat"><input type="hidden" name="id" value="<?=$id?>">
<label>Judul materi<input name="title" value="<?=h($m['title'])?>" required></label><label>Bidang<select name="field"><?php foreach($F as $k=>$v)echo "<option value='".h($k)."' ".($k==$m['field']?'selected':'').">".h($v[0])."</option>";?></select></label>
<label>Tingkat kesulitan (1 = mudah, 5 = sangat sulit)<input type="number" name="level" min="1" max="5" value="<?=h($m['level'])?>" required></label>
<label class="check-label"><input type="checkbox" name="premium" value="1" <?=!empty($m['premium'])?'checked':''?>> Tandai sebagai materi premium (terkunci untuk akun Gratis)</label>
<p class="muted">Deskripsi materi dapat diubah pada isi slide di bawah. Judul, tingkat kesulitan, seluruh slide, tautan YouTube, gambar, soal, dan status premium dapat diedit admin.</p>
<label>Slide — minimal 8 baris (1 baris 1 slide); isi wajib memiliki 2 paragraf. Format: <code>judul|isi (\n = baris baru)|nama gambar (boleh kosong)|link YouTube (opsional)</code>
<textarea name="slides" required><?=h($st)?></textarea></label>
<p class="muted">Gambar slide dikelola pada panel terpisah di bawah. Tautan YouTube bebas dikosongkan.</p>
<label>Soal — minimal 1 soal; 1 baris per soal: <code>pertanyaan|A|B|C|D|indeks benar (0-3)</code>
<textarea name="quiz" required><?=h($qt)?></textarea></label>
<button class="btn">Simpan materi</button> <a class="btn g" href="?p=field&amp;f=<?=h($m['field'])?>">Batal</a></form>
<?php if($id&&isset($D['mat'][$id])):$imageField=$D['mat'][$id]['field'];?>
<section class="slide-image-manager">
<h2>Gambar per slide</h2>
<p class="muted">Folder gambar materi ini: <code>assets/img/materials/<?=h($imageField)?>/<?=$id?>/</code>. Beri nama berkas sesuai nomor slide, seperti <code>1.jpg</code> atau <code>8.png</code>. Anda dapat memilih file, menyeretnya ke kotak, mengganti gambar, atau menghapusnya. File maksimum 8 MB per gambar.</p>
<div class="slide-image-grid">
<?php foreach($m['slides'] as $index=>$slide):
 $slideNumber=$index+1;
 $image=course_slide_image($imageField,$id,$slideNumber);
 $preview=$image['url']??hero_gallery_fallback();
?>
<article class="slide-image-card">
<div class="slide-image-preview"><?php if($preview):?><img src="<?=h($preview)?>" alt="Pratinjau gambar slide <?=$slideNumber?>" loading="lazy"><?php else:?><span class="muted">Belum ada gambar galeri.</span><?php endif;?></div>
<div class="slide-image-card-content">
<b>Slide <?=$slideNumber?>: <?=h($slide['title'])?></b>
<p class="muted"><?=$image?'Gambar khusus: '.$image['name']:'Belum ada gambar khusus; gambar galeri digunakan.'?></p>
<form class="slide-image-upload" method="post" enctype="multipart/form-data" action="?p=edit&amp;id=<?=$id?>" data-slide-upload>
<input type="hidden" name="a" value="uploadslideimage"><input type="hidden" name="id" value="<?=$id?>"><input type="hidden" name="slide" value="<?=$slideNumber?>">
<label class="slide-image-dropzone" data-dropzone>
<input type="file" name="slide_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
<span>Seret gambar ke sini atau <u>pilih file</u></span>
<small data-file-name>JPG, PNG, atau WebP · Maks. 8 MB</small>
</label>
<button class="btn" type="submit">Unggah / ganti gambar</button>
</form>
<?php if($image):?><form method="post" action="?p=edit&amp;id=<?=$id?>" onsubmit="return confirm('Hapus gambar slide <?=$slideNumber?>? Foto galeri akan digunakan sebagai pengganti.')" class="slide-image-delete"><input type="hidden" name="a" value="deleteslideimage"><input type="hidden" name="id" value="<?=$id?>"><input type="hidden" name="slide" value="<?=$slideNumber?>"><button class="btn r" type="submit">Hapus gambar</button></form><?php endif;?>
</div>
</article>
<?php endforeach;?>
</div>
</section>
<script>
document.querySelectorAll('[data-slide-upload]').forEach((form) => {
    const input = form.querySelector('input[type="file"]');
    const zone = form.querySelector('[data-dropzone]');
    const name = form.querySelector('[data-file-name]');
    input.addEventListener('change', () => {
        if (!input.files.length) return;
        name.textContent = input.files[0].name;
        form.requestSubmit();
    });
    ['dragenter', 'dragover'].forEach((eventName) => zone.addEventListener(eventName, (event) => {
        event.preventDefault();
        zone.classList.add('is-dragging');
    }));
    ['dragleave', 'drop'].forEach((eventName) => zone.addEventListener(eventName, (event) => {
        event.preventDefault();
        zone.classList.remove('is-dragging');
    }));
    zone.addEventListener('drop', (event) => {
        const files = event.dataTransfer.files;
        if (!files.length) return;
        const transfer = new DataTransfer();
        transfer.items.add(files[0]);
        input.files = transfer.files;
        name.textContent = files[0].name;
        form.requestSubmit();
    });
});
</script>
<?php else:?>
<section class="card slide-image-manager"><h2>Gambar per slide</h2><p class="muted">Simpan materi terlebih dahulu sebelum menambahkan gambar. Folder materi akan dibuat otomatis, dan gambar dapat diunggah dengan memilih file atau drag-and-drop.</p></section>
<?php endif;?>
<?php if($id):?><form method="post" onsubmit="return confirm('Hapus materi?')" style="margin-top:14px"><input type="hidden" name="a" value="delmat"><input type="hidden" name="id" value="<?=$id?>"><button class="btn r">Hapus materi</button></form><?php endif;?></div>
