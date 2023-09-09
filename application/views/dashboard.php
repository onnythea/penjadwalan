<section>
    <div class="section__content section__content--p30 m-t-90">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Selamat datang
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-3">Penjadwalan didefinisikan sebagai proses, cara, perbuatan menjadwalkan atau memasukkan dalam jadwal. Penjadwalan dapat dimaknai sebagai proses membagi kegiatan ke dalam waktu-waktu yang sudah ditentukan secara terperinci. Secara teknis, penjadwalan sering menemui kendala atau permasalahan seperti terbatasnya waktu, subjek, dan tempat.</p>

                                    <p class="mb-3">Salah satu aktivitas penjadwalan di perguruan tinggi adalah penjadwalan perkuliahan. Penjadwalan perkuliahan adalah aktivitas penempatan mata kuliah yang diajar oleh dosen yang sudah ditentukan ke satu sarana dan pra sarana yang sesuai pada satu waktu tertentu agar mahasiswa dapat menghadiri aktivitas pada mata kuliah tersebut (<a href='https://jurnal.umj.ac.id/index.php/fbc/article/view/1623' target='_blank'>Khairunnisa, 2015</a>). Pernyataan ini mengandung konsekuensi bahwa apabila jadwal perkuliahan belum ada atau memuat potensi masalah, maka aktivitas perkuliahan tidak dapat dilakukan. Dengan demikian, keberlangsungan perkuliahan bergantung pada proses penjadwalan atau pembuatan jadwal kuliah (<a href='https://ojs.unikom.ac.id/index.php/jamika/article/view/629' target='_blank'>Radliya, 2016</a>).
                                    </p>

                                    <p class="mb-3">Penjadwalan secara manual memerlukan waktu yang relatif lama untuk memastikan tidak ada satu pun kendala yang dilanggar (konflik). Apabila dalam jadwal tersebut ditemukan kegiatan yang konflik, maka ada kemungkinan semua jadwal harus ditelurusi kembali untuk memperbaiki konflik tersebut.</p>

                                    <p class="mb-3">Penjadwalan perkuliahan termasuk dalam golongan jenis <em>timetabling</em> (<a href='https://publikasiilmiah.ums.ac.id/bitstream/handle/11617/5490/19.Agus Darmawan.pdf?sequence=1' target='_blank'>Darmawan and Hasibuan, 2014</a>). Masalah timetabling tergolong ke dalam NP-Hard Problem (nondeterministic polynomial time). Untuk itu, metode optimasi konvensional sangat sulit dilakukan untuk menyelesaikan permasalahan optimasi seperti ini (<a href='https://doi.org/10.3233/KES-2008-12403' target='_blank'>Kanoh and Sakamoto, 2008</a>). Untuk permasalahan seperti ini, Alghamdi menyebutkan bahwa sampai saat ini belum ada algoritma yang menguji semua kemungkinan untuk menemukan solusi optimal pada waktu yang tepat (<a href='https://doi.org/10.48084/etasr.3832' target='_blank'>Alghamdi et al., 2020</a>). Oleh karena itu, pendekatan penyelesaian permasalahan seperti ini melalui pendekatan heuristik dan baru-baru ini oleh meta-heuristik. Lebih lanjut, Alghamdi juga menyebutkan bahwa saat ini salah satu pendekatan yang paling populer adalah Algoritma Genetika (AG) karena dapat melakukan paralelisasi komputasi tingkat tinggi dan peningkatan kinerja komputasi. Selain itu, menurutnya AG juga merupakan salah satu metode terbaik untuk mengatasi masalah NP-complete. Untuk itu, aplikasi ini merupakan sistem penjadwalan perkuliahan yang dikembangkan dengan menggunakan algoritma genetika. Aplikasi ini memungkinkan sistem dapat diakses secara online kapan pun dan di mana pun menggunakan peramba jaringan melalui perangkat komputer, tablet, atau handphone.</p>
                                    <p class="mb-3"></p>
                                    <strong>Metode & Algoritma</strong>
                                    <p class="mb-3">Algoritma genetika (AG) adalah sebuah teknik optimalisasi dan pencarian yang berdasar pada prinsip genetika dan seleksi alami (evolusi biologi). Metode ini dikembangkan pertama kali oleh John Holland (1975) dan muridnya yang bernama DeJong (1975) (<a href='https://cdn.manesht.ir/8593___Practical%20Genetic%20Algorithms.pdf' target='_blank'>Haupt and Haupt, 2004</a>). Algoritma genetika adalah algoritma pencarian heuristik yang didasarkan atas mekanisme evolusi biologis. Keberagaman pada evolusi biologis adalah variasi dari kromosom antar individu organisme. Variasi kromosom ini akan mempengaruhi laju reproduksi dan tingkat kemampuan organisme untuk tetap hidup.</p>
                                    <p class="mb-3">Menurut Sam'ani (<a href='http://eprints.undip.ac.id/36015/' target='_blank'>2012</a>), AG adalah sebuah algoritma yang berbasis tentang mekanisme seleksi alam dan genetika. AG ini menggunakan teori-teori dalam ilmu biologi, sehingga di dalam AF terdapat istilah-istilah dan kosep biologi yang digunakan dalam AG. Karena sesuai dengan namanya, proses-proses yang terjadi yang terjadi di dalam algoritma sama dengan yang terjadi pada evaluasi biologi.</p>
                                    <p class="mb-3">Di dalam AG terdapat beberapa struktur umum yaitu solusi, kromosom, pindah silang, mutasi, elitisme, kondisi seleksi. Ada 3 keuntungan utama dalam mengaplikasikan AG pada masalah-masalah optimasi (<a href='http://eprints.undip.ac.id/36015/' target='_blank'>Sam'ani, 2012</a>):</p>
                                    <div class="vue-lists">
                                        <ol class="vue-ordered" type='a'>
                                            <li>
                                                AG tidak memerlukan kebutuhan matematis banyak mengenai masalah optimasi.
                                            </li>
                                            <li>
                                                Kemudahan dan kenyamanan pada operator-operator evolusi membuat AG sangat efektif dalam melakukan pencarian global.
                                            </li>
                                            <li>
                                                AG menyediakan banyak fleksibel untuk digabungkan dengan metode heuristic yang tergantung domain, untuk membuat implementasi yang efisien pada masalah-masalah khusus.
                                            </li>
                                        </ol>
                                    </div>

                                    <p class="mb-3">Sifat algoritma genetika mencari kemungkinan dari calon solusi untuk mendapatkan solusi optimal dalam penyelesaian masalah. Ruang cakupan dari semua solusi yang layak, yaitu berbagai obyek diantara solusi yang sesuai, yang dinamakan ruang pencarian. Tiap titik didalam ruang pencarian mempresentasikan satu solusi yang layak.</p>

                                    <p class="mb-3">Tiap solusi yang layak ditandai dengan nilai fitnessnya. Solusi yang dicari adalah titik (satu/lebih) diantara solusi yang layak dalam ruang pencarian. Aplikasi ini merupakan Implementasi Algoritma Genetika dalam Penjadwalan Perkuliahan pada Fakultas Tarbiyah dan Keguruan UIN Sultan Maulana Hasanuddin Banten.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Jquery JS-->
<script src="<?= base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>