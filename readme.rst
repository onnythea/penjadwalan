###################
Penjadwalan Algoritma Genetika
###################

Penjadwalan didefinisikan sebagai proses, cara, perbuatan menjadwalkan atau memasukkan dalam jadwal. Penjadwalan dapat dimaknai sebagai proses membagi kegiatan ke dalam waktu-waktu yang sudah ditentukan secara terperinci. Secara teknis, penjadwalan sering menemui kendala atau permasalahan seperti terbatasnya waktu, subjek, dan tempat.

Salah satu aktivitas penjadwalan di perguruan tinggi adalah penjadwalan perkuliahan. Penjadwalan perkuliahan adalah aktivitas penempatan mata kuliah yang diajar oleh dosen yang sudah ditentukan ke satu sarana dan pra sarana yang sesuai pada satu waktu tertentu agar mahasiswa dapat menghadiri aktivitas pada mata kuliah tersebut (Khairunnisa, 2015). Pernyataan ini mengandung konsekuensi bahwa apabila jadwal perkuliahan belum ada atau memuat potensi masalah, maka aktivitas perkuliahan tidak dapat dilakukan. Dengan demikian, keberlangsungan perkuliahan bergantung pada proses penjadwalan atau pembuatan jadwal kuliah (Radliya, 2016).

Penjadwalan secara manual memerlukan waktu yang relatif lama untuk memastikan tidak ada satu pun kendala yang dilanggar (konflik). Apabila dalam jadwal tersebut ditemukan kegiatan yang konflik, maka ada kemungkinan semua jadwal harus ditelurusi kembali untuk memperbaiki konflik tersebut.

Penjadwalan perkuliahan termasuk dalam golongan jenis timetabling (Darmawan and Hasibuan, 2014). Masalah timetabling tergolong ke dalam NP-Hard Problem (nondeterministic polynomial time). Untuk itu, metode optimasi konvensional sangat sulit dilakukan untuk menyelesaikan permasalahan optimasi seperti ini (Kanoh and Sakamoto, 2008). Untuk permasalahan seperti ini, Alghamdi menyebutkan bahwa sampai saat ini belum ada algoritma yang menguji semua kemungkinan untuk menemukan solusi optimal pada waktu yang tepat (Alghamdi et al., 2020). Oleh karena itu, pendekatan penyelesaian permasalahan seperti ini melalui pendekatan heuristik dan baru-baru ini oleh meta-heuristik. Lebih lanjut, Alghamdi juga menyebutkan bahwa saat ini salah satu pendekatan yang paling populer adalah Algoritma Genetika (AG) karena dapat melakukan paralelisasi komputasi tingkat tinggi dan peningkatan kinerja komputasi. Selain itu, menurutnya AG juga merupakan salah satu metode terbaik untuk mengatasi masalah NP-complete. Untuk itu, aplikasi ini merupakan sistem penjadwalan perkuliahan yang dikembangkan dengan menggunakan algoritma genetika. Aplikasi ini memungkinkan sistem dapat diakses secara online kapan pun dan di mana pun menggunakan peramba jaringan melalui perangkat komputer, tablet, atau handphone.

*******************
Metode & Algoritma
*******************

Algoritma genetika (AG) adalah sebuah teknik optimalisasi dan pencarian yang berdasar pada prinsip genetika dan seleksi alami (evolusi biologi). Metode ini dikembangkan pertama kali oleh John Holland (1975) dan muridnya yang bernama DeJong (1975) (Haupt and Haupt, 2004). Algoritma genetika adalah algoritma pencarian heuristik yang didasarkan atas mekanisme evolusi biologis. Keberagaman pada evolusi biologis adalah variasi dari kromosom antar individu organisme. Variasi kromosom ini akan mempengaruhi laju reproduksi dan tingkat kemampuan organisme untuk tetap hidup.

Menurut Sam'ani (2012), AG adalah sebuah algoritma yang berbasis tentang mekanisme seleksi alam dan genetika. AG ini menggunakan teori-teori dalam ilmu biologi, sehingga di dalam AF terdapat istilah-istilah dan kosep biologi yang digunakan dalam AG. Karena sesuai dengan namanya, proses-proses yang terjadi yang terjadi di dalam algoritma sama dengan yang terjadi pada evaluasi biologi.

Di dalam AG terdapat beberapa struktur umum yaitu solusi, kromosom, pindah silang, mutasi, elitisme, kondisi seleksi. Ada 3 keuntungan utama dalam mengaplikasikan AG pada masalah-masalah optimasi (Sam'ani, 2012):

1. AG tidak memerlukan kebutuhan matematis banyak mengenai masalah optimasi.
2. Kemudahan dan kenyamanan pada operator-operator evolusi membuat AG sangat efektif dalam melakukan pencarian global.
3. AG menyediakan banyak fleksibel untuk digabungkan dengan metode heuristic yang tergantung domain, untuk membuat implementasi yang efisien pada masalah-masalah khusus.

Sifat algoritma genetika mencari kemungkinan dari calon solusi untuk mendapatkan solusi optimal dalam penyelesaian masalah. Ruang cakupan dari semua solusi yang layak, yaitu berbagai obyek diantara solusi yang sesuai, yang dinamakan ruang pencarian. Tiap titik didalam ruang pencarian mempresentasikan satu solusi yang layak.

Tiap solusi yang layak ditandai dengan nilai fitnessnya. Solusi yang dicari adalah titik (satu/lebih) diantara solusi yang layak dalam ruang pencarian. Aplikasi ini merupakan Implementasi Algoritma Genetika dalam Penjadwalan Perkuliahan pada Fakultas Tarbiyah dan Keguruan UIN Sultan Maulana Hasanuddin Banten.

**************************
Instalasi
**************************

Download dan simpan pada server

*******************
Server Requirements
*******************

PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

************
Database
************

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions`  (
  `id` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ci_sessions_timestamp`(`timestamp`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for dosen
-- ----------------------------
DROP TABLE IF EXISTS `dosen`;
CREATE TABLE `dosen`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `nidn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telp` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 102 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hari
-- ----------------------------
DROP TABLE IF EXISTS `hari`;
CREATE TABLE `hari`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jadwalkuliah
-- ----------------------------
DROP TABLE IF EXISTS `jadwalkuliah`;
CREATE TABLE `jadwalkuliah`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `kode_pengampu` int NULL DEFAULT NULL,
  `kode_jam` int NULL DEFAULT NULL,
  `kode_hari` int NULL DEFAULT NULL,
  `kode_ruang` int NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 522 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = 'hasil proses' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jadwalkuliah_copy1
-- ----------------------------
DROP TABLE IF EXISTS `jadwalkuliah_copy1`;
CREATE TABLE `jadwalkuliah_copy1`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `kode_pengampu` int NULL DEFAULT NULL,
  `kode_jam` int NULL DEFAULT NULL,
  `kode_hari` int NULL DEFAULT NULL,
  `kode_ruang` int NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = 'hasil proses' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jam
-- ----------------------------
DROP TABLE IF EXISTS `jam`;
CREATE TABLE `jam`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `range_jam` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `aktif` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for matakuliah
-- ----------------------------
DROP TABLE IF EXISTS `matakuliah`;
CREATE TABLE `matakuliah`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `kode_mk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sks` int NULL DEFAULT NULL,
  `semester` int NULL DEFAULT NULL,
  `aktif` enum('True','False') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'True',
  `jenis` enum('TEORI','PRAKTIKUM') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'TEORI',
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = 'example kode_mk = 0765109 ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `li_class` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `display` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `link` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `icon` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pengampu
-- ----------------------------
DROP TABLE IF EXISTS `pengampu`;
CREATE TABLE `pengampu`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `kode_mk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_dosen` int NULL DEFAULT NULL,
  `kelas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tahun_akademik` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 190 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ruang
-- ----------------------------
DROP TABLE IF EXISTS `ruang`;
CREATE TABLE `ruang`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kapasitas` int NULL DEFAULT NULL,
  `jenis` enum('TEORI','LABORATORIUM') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NULL DEFAULT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pass` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for waktu_tidak_bersedia
-- ----------------------------
DROP TABLE IF EXISTS `waktu_tidak_bersedia`;
CREATE TABLE `waktu_tidak_bersedia`  (
  `kode` int NOT NULL AUTO_INCREMENT,
  `kode_dosen` int NULL DEFAULT NULL,
  `kode_hari` int NULL DEFAULT NULL,
  `kode_jam` int NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
