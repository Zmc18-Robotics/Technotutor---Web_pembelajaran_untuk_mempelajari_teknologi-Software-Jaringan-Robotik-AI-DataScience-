<?php
// Membuat data awal (akun, materi, soal contoh) saat pertama dijalankan
if(!file_exists($DB)){
 $d=['users'=>[],'mat'=>[],'prog'=>[],'competitions'=>[]];
 foreach(['admin1'=>['Admin Satu','admin'],'admin2'=>['Admin Dua','admin'],'mhs1'=>['Mahasiswa Satu','mhs'],'mhs2'=>['Mahasiswa Dua','mhs'],'mhs3'=>['Mahasiswa Tiga','mhs']] as $u=>[$n,$r])
  $d['users'][$u]=['name'=>$n,'role'=>$r,'pw'=>password_hash($r=='admin'?'admin123':'mhs123',PASSWORD_DEFAULT)];
 foreach($EXTRA_ACCOUNTS as $u=>[$n,$pw])$d['users'][$u]=['name'=>$n,'role'=>'mhs','pw'=>$pw];
 $i=1;foreach($SEED as $f=>$str)foreach(explode(';',$str) as $row){[$t,$l,$s]=explode('|',$row);
  $d['mat'][$i]=['id'=>$i,'field'=>$f,'title'=>$t,'level'=>(int)$l,'slides'=>mkslides($i,$f,$t,$s),'quiz'=>make_quiz($t,$s)];$i++;}
 $q=[['Singkatan HTML adalah?',['HyperText Markup Language','High Text Machine Language','HyperTool Multi Language','Home Text Markup Language'],0],['Tag untuk judul terbesar?',['<h6>','<head>','<h1>','<title>'],2],['Tag untuk membuat link?',['<a>','<link>','<href>','<p>'],0],['Tag untuk gambar?',['<picture>','<img>','<src>','<image>'],1],['Tag paragraf?',['<para>','<text>','<p>','<pg>'],2],['Tag daftar tak berurutan?',['<ol>','<ul>','<li>','<dl>'],1],['Atribut alamat gambar?',['href','alt','src','link'],2],['Tag baris baru?',['<br>','<nl>','<lb>','<break>'],0],['Elemen root dokumen HTML?',['<body>','<head>','<html>','<main>'],2],['Tag untuk tabel?',['<tab>','<table>','<grid>','<tr>'],1]];
 foreach($q as $x)$d['mat'][1]['quiz'][]=['q'=>$x[0],'o'=>$x[1],'a'=>$x[2]];
 save($d);}
