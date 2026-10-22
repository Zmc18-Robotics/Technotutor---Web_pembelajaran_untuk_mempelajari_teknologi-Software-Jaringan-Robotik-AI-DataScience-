<?php
// Daftar materi awal: Judul|Level(1-5)|Ringkasan, dipisah ';'
$SEED=[
'software'=>"Pengenalan HTML|1|Struktur dasar halaman web dengan tag HTML.;Dasar CSS|1|Mewarnai dan menata tampilan halaman.;Logika & Algoritma|1|Berpikir runtut: urutan, percabangan, perulangan.;Dasar JavaScript|2|Variabel, fungsi, dan interaksi di browser.;Git & GitHub|2|Version control untuk kerja kolaborasi.;Dasar PHP|2|Pemrograman sisi server dengan PHP.;Database MySQL|2|Menyimpan data dengan tabel dan query SQL.;Responsive Design|3|Tampilan web nyaman di HP dan desktop.;PHP & MySQL CRUD|3|Membuat aplikasi tambah-ubah-hapus data.;Pemrograman Berorientasi Objek|3|Class, object, inheritance, polymorphism.;REST API|4|Membangun dan memakai layanan API.;Framework Laravel|4|MVC, routing, dan Eloquent ORM.;Aplikasi Mobile Dasar|4|Konsep pengembangan aplikasi Android/iOS.;Keamanan Aplikasi Web|5|XSS, SQL Injection, CSRF, dan pencegahannya.;Deployment & CI/CD|5|Rilis aplikasi otomatis ke server.",
'jaringan'=>"Pengenalan Jaringan Komputer|1|Apa itu jaringan, LAN, MAN, WAN.;Perangkat Jaringan|1|Router, switch, hub, access point.;Kabel UTP & Crimping|1|Straight dan crossover, urutan warna.;Model OSI & TCP/IP|2|Layer-layer komunikasi data.;IP Address & Subnetting|2|IPv4, kelas IP, dan subnet mask.;Dasar Wi-Fi|2|Standar 802.11, frekuensi 2.4/5 GHz.;Konfigurasi Router|2|Setup dasar router rumah/kantor.;DHCP & DNS|3|Pemberian IP otomatis dan resolusi nama.;VLAN|3|Segmentasi jaringan secara logis.;Routing Statis & Dinamis|3|RIP, OSPF, dan static route.;Mikrotik Dasar|4|RouterOS, Winbox, firewall dasar.;Keamanan Jaringan|4|Firewall, VPN, serangan umum.;Jaringan Nirkabel Lanjut|4|Mesh, point-to-point, antena.;Network Monitoring|5|SNMP, Zabbix, analisis trafik.;Desain Jaringan Enterprise|5|Topologi, redundansi, high availability.",
'robotik'=>"Dasar Elektronika & Solder|1|Teknik menyolder dan alat dasar.;Simbol & Komponen Elektronik|1|Resistor, kapasitor, dioda, transistor.;Hukum Ohm & Rangkaian|1|Tegangan, arus, hambatan.;Pengenalan Arduino|2|Board, pin digital/analog, IDE.;Sensor & Aktuator|2|Sensor ultrasonik, servo, motor.;Pengenalan Mekatronika|2|Gabungan mekanik, elektronik, kontrol.;Desain 3D Model|2|Membuat model di Fusion360/Tinkercad.;3D Printing|3|Slicing, filament, dan cetak objek.;ESP32 & IoT|3|Mengirim data sensor ke internet.;Embedded C & Pin GPIO|3|Memprogram mikrokontroler level register/pin.;Komunikasi I2C, SPI, UART|4|Protokol antar komponen.;Dasar Machine Learning|4|Konsep model, training, inferensi.;AIoT|4|Menggabungkan AI dan IoT.;Computer Vision Robot|5|Deteksi objek dengan kamera di robot.;Robot Otonom|5|Navigasi, PID, dan fusion sensor."
,'data'=>"Pengenalan Data Science|1|Apa itu data science dan alur kerjanya.;Jenis & Sumber Data|1|Kuantitatif, kualitatif, struktur data.;Spreadsheet untuk Data|1|Rumus, filter, pivot table.;Dasar Python|2|Sintaks Python untuk analisis.;Pandas & NumPy|2|Mengolah tabel dan array.;Data Cleaning|2|Missing value, duplikat, outlier.;Statistika Dasar|2|Mean, median, varians, distribusi.;Visualisasi Data|3|Matplotlib, Seaborn, dashboard.;SQL untuk Analisis|3|Query, join, agregasi.;Exploratory Data Analysis|3|Mencari pola dan korelasi.;Regresi & Klasifikasi|4|Model prediktif dasar.;Clustering|4|K-Means dan pengelompokan pola.;Feature Engineering|4|Membuat fitur yang bermakna.;Deep Learning Dasar|5|Neural network dan training.;Big Data & Pipeline|5|Spark, ETL, data warehouse."];

$EXTRA_SEED=[
 'software'=>[
  ['HTML Semantik & Aksesibilitas',1,'Menyusun landmark, label, dan struktur konten agar website lebih mudah digunakan serta dipahami pembaca layar.'],
  ['CSS Flexbox & Grid',2,'Membangun tata letak responsif dengan Flexbox, CSS Grid, dan breakpoint yang sesuai kebutuhan perangkat.'],
  ['JavaScript Asinkron & Integrasi API',3,'Mengelola Promise, async/await, request HTTP, respons JSON, loading, dan kesalahan jaringan pada antarmuka.'],
  ['Autentikasi & Otorisasi Aplikasi Web',4,'Merancang login, pengelolaan sesi, hak akses, validasi input, dan perlindungan data pengguna secara aman.'],
  ['Arsitektur Aplikasi & Deployment Produksi',5,'Menyiapkan aplikasi untuk produksi dengan konfigurasi lingkungan, Docker, proses rilis, logging, dan pemeriksaan kesehatan.'],
 ],
 'jaringan'=>[
  ['Topologi & Simulasi Jaringan Dasar',1,'Menggambar topologi LAN sederhana dan menguji komunikasi antarkomputer melalui simulator jaringan.'],
  ['VLAN, Trunk & Inter-VLAN Routing',2,'Memisahkan broadcast domain, mengonfigurasi trunk, dan menghubungkan VLAN sesuai kebutuhan organisasi.'],
  ['Spanning Tree & Link Aggregation',3,'Mencegah loop Layer 2 serta menggabungkan beberapa koneksi untuk ketahanan dan kapasitas jaringan.'],
  ['Firewall, ACL & Segmentasi Keamanan',4,'Menyusun aturan akses yang terukur, membatasi komunikasi antarzona, dan memeriksa dampaknya pada layanan.'],
  ['Otomasi & High Availability Jaringan',5,'Membuat inventaris, mengotomatiskan konfigurasi, serta merancang failover dan pemantauan infrastruktur.'],
 ],
 'robotik'=>[
  ['Robot Bergerak dengan Arduino',1,'Merangkai robot roda sederhana dan memahami hubungan board mikrokontroler, driver motor, serta catu daya.'],
  ['Motor Driver & Kontrol PWM',2,'Mengatur arah dan kecepatan motor DC serta servo memakai sinyal PWM dan rangkaian driver yang sesuai.'],
  ['PID & Line Follower',3,'Membaca sensor garis dan menyesuaikan kendali PID agar robot bergerak stabil mengikuti lintasan.'],
  ['Robot Operating System (ROS 2)',4,'Menyusun node, topic, dan sensor sederhana untuk mengintegrasikan komponen robot secara modular.'],
  ['Perencanaan Gerak Robot Otonom',5,'Menggabungkan lokalisasi, peta, perencanaan jalur, dan data sensor untuk navigasi di lingkungan terstruktur.'],
 ],
 'data'=>[
  ['SQL & Model Data Relasional',1,'Menyusun tabel, memahami primary key dan foreign key, serta menulis kueri SELECT untuk mengambil data.'],
  ['Eksperimen & Inferensi Statistik',2,'Membuat hipotesis, memahami sampel, dan menafsirkan interval kepercayaan serta uji statistik dasar.'],
  ['Machine Learning Terawasi',3,'Membandingkan regresi dan klasifikasi serta melatih model dasar untuk menjawab pertanyaan berbasis data.'],
  ['Validasi Model & Pencegahan Data Leakage',4,'Membuat pemisahan data yang benar, memilih metrik evaluasi, dan mendiagnosis overfitting serta leakage.'],
  ['MLOps & Deployment Model',5,'Mengelola eksperimen, versi data dan model, pipeline pelatihan, pemantauan, serta rilis model yang dapat diulang.'],
 ],
];
