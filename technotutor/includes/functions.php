<?php
// Fungsi bantu (escape, simpan, progres, bintang, YouTube, parser slide)
function h($s){return htmlspecialchars($s??'',ENT_QUOTES);}
function save($d){global $DB;file_put_contents($DB,json_encode($d,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));}
function course_image_directory($field,$id,$create=false){
 global $F;
 $field=(string)$field;$id=(int)$id;
 if(!isset($F[$field])||$id<1)return false;
 $path=ROOT.'/assets/img/materials/'.$field.'/'.$id;
 if($create&&!is_dir($path)&&!mkdir($path,0775,true)&&!is_dir($path)){
  error_log('Unable to create course image directory: '.$path);
  return false;
 }
 return $path;
}
function course_slide_image($field,$id,$slideNumber){
 $directory=course_image_directory($field,$id);
 $slideNumber=(int)$slideNumber;
 if(!$directory||$slideNumber<1)return null;
 foreach(['jpg','jpeg','png','webp'] as $extension){
  $name=$slideNumber.'.'.$extension;$path=$directory.'/'.$name;
  if(is_file($path))return ['name'=>$name,'path'=>$path,'url'=>'assets/img/materials/'.rawurlencode($field).'/'.(int)$id.'/'.$name];
 }
 return null;
}
function copy_course_images_to_field($id,$sourceField,$destinationField,$slideCount){
 if($sourceField===$destinationField)return true;
 if(!course_image_directory($destinationField,$id,true))return false;
 for($slideNumber=1;$slideNumber<=$slideCount;$slideNumber++){
  $image=course_slide_image($sourceField,$id,$slideNumber);
  if(!$image)continue;
  $extension=strtolower(pathinfo($image['name'],PATHINFO_EXTENSION));
  $destination=course_image_directory($destinationField,$id).'/'.$slideNumber.'.'.$extension;
  if(!copy($image['path'],$destination)){
   error_log('Unable to move course image to the new field folder: '.$destination);
   return false;
  }
  foreach(['jpg','jpeg','png','webp'] as $oldExtension){
   $oldImage=course_image_directory($destinationField,$id).'/'.$slideNumber.'.'.$oldExtension;
   if($oldExtension!==$extension&&is_file($oldImage)&&!unlink($oldImage))
    error_log('Unable to remove superseded course image: '.$oldImage);
  }
 }
 return true;
}
function hero_gallery_fallback(){
 $files=glob(ROOT.'/assets/img/hero-gallery/*')?:[];
 $files=array_values(array_filter($files,fn($file)=>is_file($file)&&in_array(strtolower(pathinfo($file,PATHINFO_EXTENSION)),['jpg','jpeg','png','webp'],true)));
 if(!$files)return null;
 $name=basename($files[random_int(0,count($files)-1)]);
 return 'assets/img/hero-gallery/'.rawurlencode($name);
}
function mkslides($id,$f,$t,$s){
 $sections=[
  ['Gambaran dan tujuan',"Materi ini membahas $t. $s\n\nSetelah mempelajarinya, Anda diharapkan mampu menjelaskan gagasan utamanya dengan kata-kata sendiri. Anda juga akan berlatih mengenali kapan konsep ini digunakan dan apa hasil yang perlu diperiksa."],
  ['Fondasi konsep',"Mulailah dengan memahami tujuan $t: $s Pecah ringkasan tersebut menjadi masalah yang ingin diselesaikan dan hasil yang diharapkan.\n\nJangan hanya menghafal istilah; hubungkan setiap istilah dengan contoh atau kebutuhan nyata yang Anda temui. Hubungan ini membuat konsep lebih mudah diingat dan diterapkan."],
  ['Komponen yang perlu dikenali',"Pecah $t menjadi bagian-bagian yang membantu mencapai tujuan ini: $s Catat fungsi setiap bagian, informasi yang dibutuhkan, dan hubungan di antaranya.\n\nPeta sederhana, tabel, atau sketsa dapat membantu Anda melihat alur tanpa kehilangan konteks. Pastikan Anda dapat menjelaskan mengapa setiap bagian dibutuhkan."],
  ['Alur kerja yang terarah',"Gunakan tujuan dari materi ini sebagai titik awal: $s Tentukan hasil yang ingin diamati dan siapkan alat atau informasi yang diperlukan untuk $t.\n\nKerjakan satu tahap pada satu waktu, periksa hasil sementara, dan lanjutkan hanya setelah tahap sebelumnya berjalan sesuai harapan. Cara ini memudahkan Anda menemukan letak masalah."],
  ['Menganalisis contoh',"Bayangkan sebuah kebutuhan sederhana yang dapat dijelaskan dengan $t: $s Mulailah dengan mengidentifikasi kebutuhan, lalu uraikan langkah yang menghubungkan kebutuhan itu dengan hasil.\n\nSetelah contoh selesai, coba ubah satu kondisi lalu amati perbedaannya. Membandingkan dua hasil membantu Anda membedakan aturan utama dari kebetulan pada contoh."],
  ['Praktik mandiri',"Buat latihan kecil yang menunjukkan pemahaman Anda terhadap $t dan tujuan berikut: $s Pilih satu kasus sederhana, tuliskan rencana, lalu kerjakan dengan urutan yang dapat dijelaskan.\n\nCatat hasil, alasan setiap langkah, dan bagian yang masih membingungkan; catatan ini menjadi dasar untuk memperbaiki percobaan berikutnya. Jika menemui kendala, ulangi dengan perubahan kecil yang terukur."],
  ['Pemeriksaan dan kesalahan umum',"Saat memeriksa $t, cocokkan hasil praktik dengan tujuan yang dijelaskan di sini: $s Jika hasil berbeda, periksa kembali asumsi, masukan, konfigurasi, dan urutan kerja.\n\nUbah satu hal setiap kali agar penyebab masalah dapat ditemukan dengan lebih jelas. Setelah perbaikan, ulangi pemeriksaan untuk memastikan bahwa perubahan tersebut benar-benar menyelesaikan masalah."],
  ['Rangkuman dan langkah berikutnya',"Inti materi ini adalah memahami $t dan mampu menjelaskan tujuan utamanya: $s Rangkum tiga hal yang paling penting dari penjelasan dan praktik yang sudah Anda lakukan.\n\nPastikan Anda dapat menerangkan hubungan antara konsep, langkah kerja, dan hasil. Lanjutkan dengan soal latihan, lalu gunakan konsep ini pada kasus baru yang sederhana."],
 ];
 $slides=[];
 foreach($sections as $n=>$section)$slides[]=['title'=>$section[0],'body'=>$section[1],'img'=>'','yt'=>''];
 return $slides;
}
function make_quiz($title,$summary){
 $questions=[
  ["Apa fokus utama materi $title?",[$summary,'Mendesain jaringan sosial untuk hiburan','Mengganti seluruh perangkat tanpa analisis','Menghafal istilah tanpa memahami kegunaannya'],0],
  ["Sebelum mempraktikkan $title, apa langkah awal yang paling tepat?",['Menentukan tujuan dan menyiapkan kebutuhan praktik','Mengubah banyak pengaturan sekaligus','Melewati konsep dasar dan langsung menebak hasil','Menggunakan alat tanpa memahami fungsinya'],0],
  ["Urutan belajar yang paling membantu untuk memahami $title adalah...",['Memahami tujuan, mempelajari konsep, mencoba, lalu memeriksa hasil','Mencoba secara acak, mengabaikan hasil, lalu berhenti','Menghafal semua istilah sebelum mengetahui konteksnya','Mengganti metode setiap kali tanpa mencatat perubahan'],0],
  ["Bagaimana cara memeriksa apakah praktik $title berhasil?",['Membandingkan hasil dengan tujuan dan kriteria yang ditentukan','Menganggap berhasil jika langkahnya terasa cepat','Menilai hanya dari tampilan tanpa memeriksa fungsi','Mengabaikan hasil karena sudah mengikuti contoh'],0],
  ["Jika hasil praktik $title tidak sesuai harapan, apa tindakan yang baik?",['Periksa bukti dan ubah satu faktor secara terukur','Ulangi semua langkah dengan perubahan acak','Hapus catatan agar dapat memulai tanpa informasi','Langsung menyimpulkan bahwa konsepnya tidak berguna'],0],
  ["Mengapa mencatat langkah dan hasil saat mempelajari $title penting?",['Agar proses dapat ditinjau, diperbaiki, dan diulangi','Agar tidak perlu menguji hasil praktik','Agar kesalahan tidak terlihat oleh siapa pun','Agar semua kasus selalu memakai langkah identik'],0],
  ["Saat memilih cara menerapkan $title, pertimbangan yang paling tepat adalah...",['Tujuan, kondisi, batasan, dan bukti hasil yang dibutuhkan','Metode yang paling rumit meskipun tidak diperlukan','Pilihan pertama tanpa memeriksa kebutuhan','Mengikuti contoh tanpa menilai kesesuaiannya'],0],
  ["Apa yang sebaiknya dilakukan ketika menemukan istilah baru dalam materi $title?",['Cari maknanya lalu hubungkan dengan contoh dan konteksnya','Lewati istilah itu dan menebak seluruh proses','Menghafal bunyinya tanpa mengetahui artinya','Mengganti istilah dengan istilah lain secara sembarang'],0],
  ["Tanda pemahaman yang lebih kuat terhadap $title adalah...",['Mampu menjelaskan alasan langkah dan menerapkannya pada kasus sederhana','Mampu menyalin contoh tanpa mengetahui hasilnya','Mampu mengingat judul tetapi tidak konsepnya','Mampu menyelesaikan praktik hanya dengan menebak'],0],
  ["Setelah menyelesaikan materi $title, langkah lanjutan yang paling bermanfaat adalah...",['Mengerjakan kasus kecil baru dan mengevaluasi hasilnya','Berhenti mencoba setelah membaca rangkuman','Mengubah banyak hal sekaligus tanpa tujuan','Mengulang teks tanpa melakukan latihan'],0],
 ];
 $quiz=[];
 foreach($questions as $i=>$item){
  [$question,$options,$correct]=$item;
  $shift=$i%count($options);
  $options=array_merge(array_slice($options,$shift),array_slice($options,0,$shift));
  $quiz[]=['q'=>$question,'o'=>$options,'a'=>($correct-$shift+count($options))%count($options)];
 }
 return $quiz;
}
function has_plan($username,$minimum='pro'){
 global $D;
 $user=$D['users'][$username]??[];
 if(($user['role']??'')==='admin')return true;
 $rank=[''=>0,'pro'=>1,'ultra'=>2];
 return ($rank[$user['plan']??'']??0)>=($rank[$minimum]??1);
}
function course_locked($material,$username=null){
 global $me;
 $username=$username??$me;
 return !empty($material['premium'])&&!has_plan($username,'pro');
}
function upgrade_course_data(&$data){
 global $EXTRA_SEED,$EXTRA_ACCOUNTS,$PREMIUM_SEED,$TUTOR_ACCOUNTS,$PREMIUM_DEMO_ACCOUNTS;
 $changed=false;
 foreach(['users','mat','prog','competitions'] as $key){
  if(!isset($data[$key])||!is_array($data[$key])){$data[$key]=[];$changed=true;}
 }
 foreach($data['mat'] as &$material){
  course_image_directory($material['field']??'',$material['id']??0,true);
  $summary=$material['slides'][0]['body']??'';
  $slideTitles=['Gambaran dan tujuan','Fondasi konsep','Komponen yang perlu dikenali','Alur kerja yang terarah','Menganalisis contoh','Praktik mandiri','Pemeriksaan dan kesalahan umum','Rangkuman dan langkah berikutnya'];
  $generatedV1=count($material['slides']??[])===8
   &&array_column($material['slides'],'title')===$slideTitles
   &&str_starts_with($material['slides'][1]['body']??'',"Mulailah dengan memahami arti {$material['title']} dan masalah yang ingin diselesaikannya.");
  if($generatedV1){
   $firstParagraph=explode("\n\n",$summary,2)[0];
   $summaryPrefix="Materi ini membahas {$material['title']}. ";
   if(str_starts_with($firstParagraph,$summaryPrefix))$summary=substr($firstParagraph,strlen($summaryPrefix));
  }
  $legacy=count($material['slides']??[])===4
   &&($material['slides'][0]['title']??'')==="Apa itu {$material['title']}?"
   &&($material['slides'][1]['title']??'')==='Konsep Utama'
   &&($material['slides'][2]['title']??'')==='Langkah Praktik'
   &&($material['slides'][3]['title']??'')==='Rangkuman';
  if($legacy||$generatedV1){
   if($legacy)$summary=$material['slides'][0]['body']??'';
   $material['slides']=mkslides($material['id'],$material['field'],$material['title'],$summary);
   $changed=true;
  }
  if(!isset($material['slides'])||!is_array($material['slides']))$material['slides']=[];
  foreach($material['slides'] as $index=>&$slide){
   $image=basename((string)($slide['img']??''));
   if($image!==''&&$image!=='auto-illustration.svg'&&is_file(ROOT.'/assets/img/'.$image)){
    $extension=strtolower(pathinfo($image,PATHINFO_EXTENSION));
    $directory=course_image_directory($material['field'],$material['id'],true);
    $destination=$directory&&in_array($extension,['jpg','jpeg','png','webp'],true)
     ?$directory.'/'.($index+1).'.'.$extension:null;
    if($destination&&!is_file($destination)&&!copy(ROOT.'/assets/img/'.$image,$destination))
     error_log('Unable to migrate course image: '.ROOT.'/assets/img/'.$image);
   }
   if(($slide['img']??'')!==''){$slide['img']='';$changed=true;}
  }
  unset($slide);
  if(empty($material['quiz'])){
   $material['quiz']=make_quiz($material['title'],$summary);
   $changed=true;
  }
  if(count($material['slides']??[])<8){
   $summary=$material['slides'][0]['body']??$material['title'];
   $expanded=mkslides($material['id'],$material['field'],$material['title'],$summary);
   while(count($material['slides'])<8)$material['slides'][]=$expanded[count($material['slides'])];
   $changed=true;
  }
 }
 unset($material);
 foreach($EXTRA_SEED as $field=>$courses){
  foreach($courses as $seedIndex=>[$title,$level,$summary]){
   $seedKey='extra:'.$field.':'.$seedIndex;
   $exists=false;
   foreach($data['mat'] as &$material){
    if(($material['seed_key']??'')===$seedKey||(($material['field']??'')===$field&&($material['title']??'')===$title)){
     if(empty($material['seed_key'])){$material['seed_key']=$seedKey;$changed=true;}
     $exists=true;break;
    }
   }
   unset($material);
   if($exists)continue;
   $id=empty($data['mat'])?1:max(array_map('intval',array_keys($data['mat'])))+1;
   $data['mat'][$id]=['id'=>$id,'field'=>$field,'title'=>$title,'level'=>$level,'seed_key'=>$seedKey,'slides'=>mkslides($id,$field,$title,$summary),'quiz'=>make_quiz($title,$summary)];
   course_image_directory($field,$id,true);
   $changed=true;
  }
 }
 foreach($PREMIUM_SEED as $field=>$courses){
  foreach($courses as $seedIndex=>[$title,$level,$summary]){
   $seedKey='premium:'.$field.':'.$seedIndex;
   $exists=false;
   foreach($data['mat'] as &$material){
    if(($material['seed_key']??'')===$seedKey||(($material['field']??'')===$field&&($material['title']??'')===$title)){
     if(empty($material['seed_key'])){$material['seed_key']=$seedKey;$changed=true;}
     $exists=true;break;
    }
   }
   unset($material);
   if($exists)continue;
   $id=empty($data['mat'])?1:max(array_map('intval',array_keys($data['mat'])))+1;
   $data['mat'][$id]=['id'=>$id,'field'=>$field,'title'=>$title,'level'=>$level,'premium'=>1,'seed_key'=>$seedKey,'slides'=>mkslides($id,$field,$title,$summary),'quiz'=>make_quiz($title,$summary)];
   course_image_directory($field,$id,true);
   $changed=true;
  }
 }
 foreach($EXTRA_ACCOUNTS as $username=>[$name,$passwordHash]){
  if(isset($data['users'][$username]))continue;
  $data['users'][$username]=['name'=>$name,'role'=>'mhs','pw'=>$passwordHash];
  $changed=true;
 }
 foreach($TUTOR_ACCOUNTS as $username=>[$name,$passwordHash]){
  if(isset($data['users'][$username]))continue;
  $data['users'][$username]=['name'=>$name,'role'=>'tutor','pw'=>$passwordHash];
  $changed=true;
 }
 foreach($PREMIUM_DEMO_ACCOUNTS as $username=>[$name,$plan,$passwordHash]){
  if(isset($data['users'][$username]))continue;
  $data['users'][$username]=['name'=>$name,'role'=>'mhs','pw'=>$passwordHash,'plan'=>$plan];
  $changed=true;
 }
 foreach(['plan_requests','tutor_forum','game_scores'] as $key){
  if(!isset($data[$key])||!is_array($data[$key])){$data[$key]=[];$changed=true;}
 }
 if(!isset($data['competitions'])||!is_array($data['competitions'])){$data['competitions']=[];$changed=true;}
 if($changed)save($data);
}
function isdone($id){global $D,$me;return !empty($D['prog'][$me][$id]['done']);}
function fprog($f){global $D,$me;$a=array_filter($D['mat'],fn($m)=>$m['field']===$f&&!course_locked($m,$me));$n=count(array_filter($a,fn($m)=>isdone($m['id'])));return [$n,count($a)];}
function stars($n){return str_repeat('★',$n).'<span class=dim>'.str_repeat('★',5-$n).'</span>';}
function yt($u){return preg_match('~(?:v=|youtu\.be/|embed/)([\w-]{11})~',$u,$m)?$m[1]:'';}
function parse_slides($txt,$id,$f){$o=[];foreach(preg_split('/\r?\n/',trim($txt)) as $l){if(trim($l)==='')continue;$x=explode('|',$l,4)+['','','',''];
 $o[]=['title'=>trim($x[0]),'body'=>str_replace('\n',"\n",trim($x[1])),'img'=>'','yt'=>trim($x[3])];}return $o;}
