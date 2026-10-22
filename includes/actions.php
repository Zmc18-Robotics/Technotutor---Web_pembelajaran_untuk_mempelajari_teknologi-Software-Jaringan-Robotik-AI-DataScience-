<?php
// Aksi POST: progres, kuis, password, CRUD materi & akun
// POST actions
if($_SERVER['REQUEST_METHOD']=='POST'&&$U){$a=$_POST['a']??'';
 $csrfActions=['requestplan','reviewplan','resetstudentdata','asktutor','replytutor','savegamescore'];
 $postedCsrf=is_string($_POST['csrf']??null)?$_POST['csrf']:'';
 if(in_array($a,$csrfActions,true)&&!hash_equals($_SESSION['csrf']??'',$postedCsrf)){
  $flash='Sesi formulir tidak valid atau kedaluwarsa. Muat ulang halaman lalu coba lagi.';
  $p=in_array($a,['reviewplan','resetstudentdata'],true)?'settings':(in_array($a,['asktutor','replytutor'],true)?'tutor':($a==='requestplan'?'plans':($a==='savegamescore'?'game':'home')));
  $a='';
 }
 if($a=='done'){$id=(int)$_POST['id'];if(isset($D['mat'][$id])&&!course_locked($D['mat'][$id],$me)){$D['prog'][$me][$id]=['done'=>1,'score'=>$D['prog'][$me][$id]['score']??null];save($D);}header("Location:?p=mat&id=$id");exit;}
 if($a=='quiz'){$id=(int)$_POST['id'];if(!isset($D['mat'][$id])||course_locked($D['mat'][$id],$me)){header('Location:?p=plans');exit;}$qs=$D['mat'][$id]['quiz'];$s=0;foreach($qs as $i=>$q)if(isset($_POST["q$i"])&&(int)$_POST["q$i"]==$q['a'])$s++;
  $pass=$s>=ceil(count($qs)*0.7);$old=$D['prog'][$me][$id]['score']??0;
  $D['prog'][$me][$id]=['done'=>$pass||isdone($id)?1:0,'score'=>max($old,$s)];save($D);header("Location:?p=quiz&id=$id&r=$s");exit;}
 if($a==='requestplan'&&($U['role']??'')==='mhs'){
  $plan=$_POST['plan']??'';
  if(!in_array($plan,['pro','ultra'],true)||has_plan($me,$plan)){
   $flash='Paket yang dipilih tidak valid atau sudah aktif.';$p='plans';
  }else{
   $pending=false;foreach($D['plan_requests'] as $request)if(($request['username']??'')===$me&&($request['status']??'')==='pending'){$pending=true;break;}
   if($pending){$flash='Pengajuan paket Anda masih menunggu keputusan admin.';$p='plans';}
   else{$id=empty($D['plan_requests'])?1:max(array_map('intval',array_keys($D['plan_requests'])))+1;
    $D['plan_requests'][$id]=['id'=>$id,'username'=>$me,'plan'=>$plan,'status'=>'pending','created_at'=>date(DATE_ATOM)];
    save($D);$flash='Pengajuan paket berhasil dikirim. Admin akan memproses aktivasi.';$flashType='ok2';$p='plans';
   }
  }
 }
 if($adm&&$a==='reviewplan'){
  $id=(int)($_POST['request_id']??0);$decision=$_POST['decision']??'';$request=$D['plan_requests'][$id]??null;
  if(!$request||($request['status']??'')!=='pending'||!in_array($decision,['approve','reject'],true)){
   $flash='Pengajuan tidak ditemukan atau sudah diproses.';$p='settings';
  }elseif(!isset($D['users'][$request['username']])||($D['users'][$request['username']]['role']??'')!=='mhs'){
   $flash='Akun mahasiswa untuk pengajuan ini tidak valid.';$p='settings';
  }else{
   $D['plan_requests'][$id]['status']=$decision==='approve'?'approved':'rejected';
   $D['plan_requests'][$id]['reviewed_by']=$me;$D['plan_requests'][$id]['reviewed_at']=date(DATE_ATOM);
   if($decision==='approve')$D['users'][$request['username']]['plan']=$request['plan'];
   save($D);$flash=$decision==='approve'?'Paket berhasil diaktifkan.':'Pengajuan paket ditolak.';$flashType='ok2';$p='settings';
  }
 }
 if($a==='asktutor'&&($U['role']??'')==='mhs'&&has_plan($me,'ultra')){
  $rawQuestion=$_POST['question']??'';$text=is_string($rawQuestion)?trim($rawQuestion):'';$length=preg_match_all('/./us',$text,$characters);
  if($text===''||$length===false||$length>3000){$flash='Pertanyaan wajib diisi dan maksimal 3000 karakter.';$p='tutor';}
  else{$id=empty($D['tutor_forum'])?1:max(array_map('intval',array_keys($D['tutor_forum'])))+1;
   $D['tutor_forum'][$id]=['id'=>$id,'username'=>$me,'name'=>$U['name'],'question'=>$text,'created_at'=>date(DATE_ATOM),'replies'=>[]];
   save($D);header('Location:?p=tutor');exit;
  }
 }
 if($a==='replytutor'&&in_array($U['role']??'',['admin','tutor'],true)){
  $id=(int)($_POST['question_id']??0);$rawReply=$_POST['reply']??'';$text=is_string($rawReply)?trim($rawReply):'';$length=preg_match_all('/./us',$text,$characters);
  if(!isset($D['tutor_forum'][$id])||$text===''||$length===false||$length>3000){
   $flash='Pertanyaan tidak valid atau balasan kosong/melebihi 3000 karakter.';$p='tutor';
  }else{$D['tutor_forum'][$id]['replies'][]=['username'=>$me,'name'=>$U['name'],'role'=>$U['role'],'text'=>$text,'created_at'=>date(DATE_ATOM)];
   save($D);header('Location:?p=tutor');exit;
  }
 }
 if($a==='savegamescore'&&has_plan($me,'ultra')){
  $game=$_POST['game']??'';$score=filter_var($_POST['score']??null,FILTER_VALIDATE_INT);
  if(!in_array($game,['color','math','dots'],true)||$score===false||$score<0||$score>1000000){
   $flash='Skor permainan tidak valid.';$p='games';
  }else{$previous=(int)($D['game_scores'][$me][$game]??0);
   if($score>$previous)$D['game_scores'][$me][$game]=$score;
   save($D);header('Location:?p=game&name='.rawurlencode($game).'&score='.$score);exit;
  }
 }
 if($a=='pw'){if(password_verify($_POST['old'],$U['pw'])&&strlen($_POST['new'])>=5){$D['users'][$me]['pw']=password_hash($_POST['new'],PASSWORD_DEFAULT);save($D);$flash='Password diganti.';}else $flash='Password lama salah / baru < 5 karakter.';$p='settings';}
 if($adm&&$a=='resetstudentdata'){
  $student=trim($_POST['student']??'');
  if(!isset($D['users'][$student])||($D['users'][$student]['role']??'')!=='mhs'){
   $flash='Akun mahasiswa tidak ditemukan.';$flashType='err';
  }else{
   unset($D['prog'][$student]);
   save($D);
   $flash='Progres dan nilai kuis mahasiswa '.($D['users'][$student]['name']??$student).' berhasil direset.';$flashType='ok2';
  }
  $p='settings';
 }
 if($adm&&$a=='savemat'){
  $id=(int)($_POST['id']??0);$f=$_POST['field']??'';
  $title=trim($_POST['title']??'');$slides=parse_slides($_POST['slides']??'',$id,$f);$quiz=[];
  foreach(preg_split('/\r?\n/',trim($_POST['quiz']??'')) as $line){
   if(trim($line)==='')continue;
   $parts=array_map('trim',explode('|',$line));
   if(count($parts)>=6)$quiz[]=['q'=>$parts[0],'o'=>array_slice($parts,1,4),'a'=>(int)$parts[5]];
  }
  $error='';
  if($title==='')$error='Judul materi wajib diisi.';
  elseif(!isset($F[$f]))$error='Pilih bidang materi yang valid.';
  elseif(count($slides)<8)$error='Setiap materi wajib memiliki minimal 8 slide.';
  else foreach($slides as $index=>$slide){
   $paragraphs=preg_split('/\n\s*\n/',trim($slide['body']));
   if(trim($slide['title'])===''||count($paragraphs)<2||trim($paragraphs[0])===''||trim($paragraphs[1])===''){
    $error='Slide '.($index+1).' membutuhkan judul dan minimal dua paragraf deskripsi.';
    break;
   }
  }
  if(!$error&&count($quiz)<1)$error='Tambahkan minimal satu soal untuk latihan di akhir materi.';
  if(!$error)foreach($quiz as $index=>$question){
   if(trim($question['q'])===''||count($question['o'])!==4||in_array('', $question['o'],true)||$question['a']<0||$question['a']>3){
    $error='Periksa format pertanyaan '.($index+1).'; isi empat pilihan jawaban dan indeks benar 0-3.';
    break;
   }
  }
  if($error!==''){$flash=$error;$p='edit';$_GET['id']=$id;}
  else{
   if(!$id)$id=empty($D['mat'])?1:max(array_map('intval',array_keys($D['mat'])))+1;
   $previousField=$D['mat'][$id]['field']??$f;
   if(!course_image_directory($f,$id,true)||!copy_course_images_to_field($id,$previousField,$f,count($slides))){
    $flash='Folder gambar materi tidak dapat dibuat atau gambar lama gagal dipindahkan.';$p='edit';$_GET['id']=$id;
   }
   else{$seedKey=$D['mat'][$id]['seed_key']??null;
    $material=['id'=>$id,'field'=>$f,'title'=>$title,'level'=>max(1,min(5,(int)($_POST['level']??1))),'premium'=>isset($_POST['premium'])?1:0,'slides'=>$slides,'quiz'=>$quiz];
    if($seedKey!==null)$material['seed_key']=$seedKey;
    $D['mat'][$id]=$material;
    save($D);header("Location:?p=field&f=$f");exit;}
  }
 }
 if($adm&&$a=='uploadslideimage'){
  $id=(int)($_POST['id']??0);$slideNumber=(int)($_POST['slide']??0);
  if(!isset($D['mat'][$id])||$slideNumber<1||$slideNumber>count($D['mat'][$id]['slides']??[])){
   $flash='Materi atau nomor slide tidak valid.';$p='edit';
  }elseif(!isset($_FILES['slide_image'])||$_FILES['slide_image']['error']!==UPLOAD_ERR_OK){
   $flash='Pilih gambar yang berhasil diunggah. Maksimum ukuran gambar 8 MB.';$p='edit';
  }else{
   $upload=$_FILES['slide_image'];$mime=(new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
   $extensions=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
   $imageInfo=@getimagesize($upload['tmp_name']);
   if($upload['size']<1||$upload['size']>8*1024*1024||!isset($extensions[$mime])||!$imageInfo||$imageInfo['mime']!==$mime){
    $flash='Format gambar tidak didukung. Gunakan JPG, PNG, atau WebP hingga 8 MB.';$p='edit';
   }elseif($imageInfo[0]>6000||$imageInfo[1]>6000||$imageInfo[0]*$imageInfo[1]>20000000){
    $flash='Dimensi gambar terlalu besar; batas maksimum 6000 × 6000 piksel.';$p='edit';
   }else{
    $material=$D['mat'][$id];$directory=course_image_directory($material['field'],$id,true);
    $extension=$extensions[$mime];$destination=$directory?($directory.'/'.$slideNumber.'.'.$extension):false;
    if(!$destination||!move_uploaded_file($upload['tmp_name'],$destination)){
     $flash='Gambar tidak berhasil disimpan. Periksa izin tulis folder aset.';$p='edit';
    }else{
     foreach(['jpg','jpeg','png','webp'] as $oldExtension){
      $oldImage=$directory.'/'.$slideNumber.'.'.$oldExtension;
      if($oldExtension!==$extension&&is_file($oldImage)&&!unlink($oldImage))error_log('Unable to remove replaced course image: '.$oldImage);
     }
     $flash='Gambar slide '.$slideNumber.' berhasil diperbarui.';$flashType='ok2';$p='edit';
    }
   }
  }
  if($p==='edit')$_GET['id']=$id;
 }
 if($adm&&$a=='deleteslideimage'){
  $id=(int)($_POST['id']??0);$slideNumber=(int)($_POST['slide']??0);$material=$D['mat'][$id]??null;
  if(!$material||$slideNumber<1||$slideNumber>count($material['slides']??[])){
   $flash='Materi atau nomor slide tidak valid.';$p='edit';
  }else{
   $image=course_slide_image($material['field'],$id,$slideNumber);
   if(!$image)$flash='Slide ini belum memiliki gambar khusus.';
   elseif(!unlink($image['path']))$flash='Gambar tidak dapat dihapus; periksa izin folder.';
   else{$flash='Gambar slide dihapus. Foto galeri beranda akan digunakan sebagai pengganti.';$flashType='ok2';}
   $p='edit';$_GET['id']=$id;
  }
 }
 if($adm&&$a=='savecompetition'){
  $title=trim($_POST['title']??'');$description=trim($_POST['description']??'');$url=trim($_POST['url']??'');
  $image=trim($_POST['image']??'');$scheme=strtolower(parse_url($url,PHP_URL_SCHEME)??'');
  if($title===''||$description===''||!filter_var($url,FILTER_VALIDATE_URL)||!in_array($scheme,['http','https'],true)){
   $flash='Lengkapi judul, deskripsi, dan tautan lomba yang valid (http/https).';$p='competitions';
  }elseif($image!==''&&!preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]*\.(jpg|jpeg|png|webp)$/i',$image)){
   $flash='Nama gambar harus berupa nama file JPG, PNG, atau WebP tanpa folder.';$p='competitions';
  }else{
   $id=(int)($_POST['id']??0);
   if(!$id)$id=empty($D['competitions'])?1:max(array_keys($D['competitions']))+1;
   $D['competitions'][$id]=['id'=>$id,'title'=>$title,'category'=>trim($_POST['category']??''),'organizer'=>trim($_POST['organizer']??''),'deadline'=>trim($_POST['deadline']??''),'description'=>$description,'url'=>$url,'image'=>$image];
   save($D);header('Location:?p=competitions');exit;
  }
 }
 if($adm&&$a=='delcompetition'){$id=(int)($_POST['id']??0);unset($D['competitions'][$id]);save($D);header('Location:?p=competitions');exit;}
 if($adm&&$a=='delmat'){$f=$D['mat'][(int)$_POST['id']]['field'];unset($D['mat'][(int)$_POST['id']]);save($D);header("Location:?p=field&f=$f");exit;}
 if($adm&&$a=='adduser'){$u=trim($_POST['nu']);if($u&&!isset($D['users'][$u])){$D['users'][$u]=['name'=>$_POST['nn'],'role'=>$_POST['nr']=='admin'?'admin':'mhs','pw'=>password_hash($_POST['np'],PASSWORD_DEFAULT)];save($D);}header('Location:?p=settings');exit;}
}
