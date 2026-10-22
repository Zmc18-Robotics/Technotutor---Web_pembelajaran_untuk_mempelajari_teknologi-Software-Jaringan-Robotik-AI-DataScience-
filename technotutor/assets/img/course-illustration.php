<?php
$fields=[
 'software'=>['Software','Membangun dan menguji aplikasi','#ffad48','<rect x="286" y="112" width="390" height="218" rx="18" fill="#111c36" stroke="#92a6ce" stroke-width="3"/><path d="M286 151h390" stroke="#92a6ce" stroke-width="3"/><circle cx="310" cy="132" r="7" fill="#ff7c83"/><circle cx="333" cy="132" r="7" fill="#ffd168"/><circle cx="356" cy="132" r="7" fill="#69dbb0"/><rect x="306" y="173" width="204" height="136" rx="8" fill="#091224"/><path d="M322 193h76M322 211h132M338 229h106M322 247h68M338 265h124M322 283h89" stroke="#74d7f3" stroke-width="6" stroke-linecap="round"/><rect x="530" y="179" width="124" height="112" rx="10" fill="#192746"/><path d="M548 211h88M548 233h74M548 255h58" stroke="#ffc36b" stroke-width="8" stroke-linecap="round"/><path d="M430 347h106m-74-17v17m43-17v17" stroke="#92a6ce" stroke-width="8" stroke-linecap="round"/>'],
 'jaringan'=>['Jaringan','Menghubungkan perangkat dengan aman','#4dd0e1','<path d="M480 222 325 139M480 222 637 139M480 222 323 305M480 222 638 305" stroke="#4dd0e1" stroke-width="5" opacity=".75"/><rect x="412" y="175" width="136" height="94" rx="20" fill="#17314b" stroke="#8fe9fa" stroke-width="4"/><path d="M436 199h88M436 218h88M436 237h88" stroke="#4dd0e1" stroke-width="7" stroke-linecap="round"/><circle cx="448" cy="247" r="4" fill="#d5f9ff"/><circle cx="464" cy="247" r="4" fill="#d5f9ff"/><rect x="279" y="103" width="92" height="68" rx="12" fill="#1a2740" stroke="#7c8eb6" stroke-width="3"/><rect x="591" y="103" width="92" height="68" rx="12" fill="#1a2740" stroke="#7c8eb6" stroke-width="3"/><rect x="278" y="272" width="92" height="68" rx="12" fill="#1a2740" stroke="#7c8eb6" stroke-width="3"/><rect x="592" y="272" width="92" height="68" rx="12" fill="#1a2740" stroke="#7c8eb6" stroke-width="3"/><path d="M302 148h44m269 0h44m-357 166h44m269 0h44" stroke="#ffca77" stroke-width="6" stroke-linecap="round"/>'],
 'robotik'=>['Robotik & AI','Merancang robot yang dapat merasakan dan bertindak','#76ddb6','<path d="M480 155v32m-61 28-34-31m156 31 34-31" stroke="#76ddb6" stroke-width="17" stroke-linecap="round"/><rect x="421" y="119" width="118" height="114" rx="31" fill="#172941" stroke="#76ddb6" stroke-width="4"/><circle cx="457" cy="166" r="10" fill="#baf8df"/><circle cx="503" cy="166" r="10" fill="#baf8df"/><path d="M453 197h54" stroke="#ffc574" stroke-width="8" stroke-linecap="round"/><path d="m372 179-16-17m264 17 16-17" stroke="#ffc574" stroke-width="7" stroke-linecap="round"/><path d="M418 248h124l32 52h-188z" fill="#21324d" stroke="#94a7cc" stroke-width="4"/><circle cx="411" cy="305" r="25" fill="#10182a" stroke="#8fa0be" stroke-width="7"/><circle cx="549" cy="305" r="25" fill="#10182a" stroke="#8fa0be" stroke-width="7"/><path d="M480 233v44m-31-44v34m62-34v34" stroke="#ffca77" stroke-width="6" stroke-linecap="round"/><path d="M454 282h52" stroke="#76ddb6" stroke-width="7" stroke-linecap="round"/>'],
 'data'=>['Data Science','Mengubah data menjadi insight dan keputusan','#a6a0ff','<rect x="286" y="114" width="196" height="212" rx="14" fill="#17243c" stroke="#8697c4" stroke-width="3"/><path d="M310 156h144M310 183h114M310 210h140M310 237h96M310 264h124M310 291h82" stroke="#8796b9" stroke-width="9" stroke-linecap="round"/><circle cx="446" cy="182" r="7" fill="#a6a0ff"/><circle cx="422" cy="236" r="7" fill="#67ded1"/><circle cx="432" cy="290" r="7" fill="#ffbf69"/><path d="M520 318V231h37v87zm57 0V193h37v125zm57 0V151h37v167z" fill="#a6a0ff" opacity=".84"/><path d="M511 319h181M529 182l59-31 47 18 43-43" fill="none" stroke="#74e1d7" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="529" cy="182" r="7" fill="#74e1d7"/><circle cx="588" cy="151" r="7" fill="#74e1d7"/><circle cx="635" cy="169" r="7" fill="#74e1d7"/><circle cx="678" cy="126" r="7" fill="#74e1d7"/>'],
];
$field=$_GET['field']??'software';
if(!isset($fields[$field]))$field='software';
[$fieldTitle,$tagline,$accent,$illustration]=$fields[$field];
$topic=trim(substr((string)($_GET['topic']??''),0,100));
$lesson=trim(substr((string)($_GET['lesson']??''),0,100));
$step=max(1,min(8,(int)($_GET['step']??1)));
$steps=['Mengenal masalah','Memahami konsep','Mengenali komponen','Menyusun langkah','Menganalisis contoh','Mencoba sendiri','Memeriksa hasil','Rangkuman'];
$escape=fn($value)=>htmlspecialchars($value,ENT_QUOTES|ENT_XML1,'UTF-8');
$stepName=$steps[$step-1];
header('Content-Type: image/svg+xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 960 440" role="img" aria-labelledby="title desc">
<title id="title"><?=$escape($topic?:$fieldTitle)?>: <?=$escape($stepName)?></title>
<desc id="desc"><?=$escape($fieldTitle)?> — <?=$escape($lesson?:$tagline)?>. Ilustrasi konsep pada slide <?=$step?>.</desc>
<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#18233c"/><stop offset="1" stop-color="#10152c"/></linearGradient><radialGradient id="glow"><stop stop-color="<?=$accent?>" stop-opacity=".22"/><stop offset="1" stop-color="<?=$accent?>" stop-opacity="0"/></radialGradient><filter id="shadow" x="-.3" y="-.3" width="1.6" height="1.6"><feDropShadow dx="0" dy="12" stdDeviation="12" flood-color="#050812" flood-opacity=".32"/></filter></defs>
<rect x="0" y="0" width="960" height="440" rx="24" fill="url(#bg)"/><circle cx="496" cy="232" r="288" fill="url(#glow)"/><path d="M0 350 270 263l220 97 215-117 255 85v112H0z" fill="#101a31" opacity=".57"/><g fill="none" stroke="<?=$accent?>" stroke-opacity=".14"><circle cx="480" cy="217" r="194"/><circle cx="480" cy="217" r="156"/></g>
<g filter="url(#shadow)"><?=$illustration?></g>
<text x="52" y="57" fill="<?=$accent?>" font-family="Segoe UI,Arial,sans-serif" font-size="15" font-weight="700" letter-spacing="3"><?=$escape(strtoupper($fieldTitle))?></text>
<text x="52" y="98" fill="#f2f5ff" font-family="Segoe UI,Arial,sans-serif" font-size="24" font-weight="700"><?=$escape($topic?:$tagline)?></text>
<text x="52" y="126" fill="#a9b5d2" font-family="Segoe UI,Arial,sans-serif" font-size="14"><?=$escape($stepName)?> · <?=$escape($lesson?:$tagline)?></text>
<rect x="52" y="370" width="856" height="1" fill="#8090b2" opacity=".35"/>
<?php foreach($steps as $index=>$label):$center=72+$index*116;?>
<circle cx="<?=$center?>" cy="402" r="<?=$index+1===$step?'12':'8'?>" fill="<?=$index+1===$step?$accent:'#415071'?>"/>
<text x="<?=$center?>" y="429" fill="<?=$index+1===$step?'#f2f5ff':'#8995b0'?>" font-family="Segoe UI,Arial,sans-serif" font-size="10" text-anchor="middle"><?=$escape($label)?></text>
<?php endforeach;?>
</svg>
