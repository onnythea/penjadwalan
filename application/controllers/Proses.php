<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proses extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        die('Invalid request');
    }

    public function proses()
    {
        $smt = $this->input->post('semester_tipe');
        $thak = $this->input->post('tahun_akademik');

        //cari jadwal
        $cek_jadwal = $this->db->where(
            [
                'MOD(b.semester, 2)=' => $smt,
                'a.tahun_akademik' => $thak
            ]
        )
            ->join(
                'matakuliah b',
                'a.kode_mk = b.kode_mk'
            )
            ->get('pengampu a');

        if ($cek_jadwal->num_rows() > 0) {
            /*
               HERE WE GO....
            */

            $jenis_semester = $this->input->post('semester_tipe');
            $tahun_akademik = $this->input->post('tahun_akademik');
            $jumlah_populasi = $this->input->post('jumlah_populasi');
            $crossOver = $this->input->post('probabilitas_crossover');
            $mutasi = $this->input->post('probabilitas_mutasi');
            $jumlah_generasi = $this->input->post('jumlah_generasi');

            $data['semester_tipe'] = $jenis_semester;
            $data['tahun_akademik'] = $tahun_akademik;
            $data['jumlah_populasi'] = $jumlah_populasi;
            $data['probabilitas_crossover'] = $crossOver;
            $data['probabilitas_mutasi'] = $mutasi;
            $data['jumlah_generasi'] = $jumlah_generasi;
            require_once('Genetik.php');

            $genetik = new genetik(
                $jenis_semester,
                $tahun_akademik,
                $jumlah_populasi,
                $crossOver,
                $mutasi,
                5,
                '4-5-6',
                6
            );
            $genetik->AmbilData();
            $genetik->Inisialisai();

            $found = false;
            $qry_hapus =
                "DELETE FROM 
                    jadwalkuliah 
                 WHERE 
                    kode_pengampu IN (
                       SELECT 
                          kode 
                       FROM 
                          pengampu 
                       WHERE
                          tahun_akademik = \"$tahun_akademik\" 
                       AND 
                          kode_mk IN (
                             SELECT 
                                kode_mk 
                             FROM 
                                matakuliah 
                             WHERE 
                                MOD(semester,2) = $jenis_semester
                          )
                    )
               ";

            for ($i = 0; $i < $jumlah_generasi; $i++) {
                $fitness = $genetik->HitungFitness();

                $genetik->Seleksi($fitness);
                $genetik->StartCrossOver();

                $fitnessAfterMutation = $genetik->Mutasi();

                for ($j = 0; $j < count($fitnessAfterMutation); $j++) {
                    //test here
                    if ($fitnessAfterMutation[$j] == 1) {
                        //Jangan dihapus
                        //$this->db->query("TRUNCATE TABLE jadwalkuliah");

                        //Kita hapus jadwal yg sudah ada
                        $this->db->query($qry_hapus);

                        $jadwal_kuliah = array(array());
                        $jadwal_kuliah = $genetik->GetIndividu($j);

                        for ($k = 0; $k < count($jadwal_kuliah); $k++) {
                            $kode_pengampu = intval($jadwal_kuliah[$k][0]);
                            $kode_jam = intval($jadwal_kuliah[$k][1]);
                            $kode_hari = intval($jadwal_kuliah[$k][2]);
                            $kode_ruang = intval($jadwal_kuliah[$k][3]);
                            $this->db->insert(
                                'jadwalkuliah',
                                [
                                    'kode_pengampu' => $kode_pengampu,
                                    'kode_jam' => $kode_jam,
                                    'kode_hari' => $kode_hari,
                                    'kode_ruang' => $kode_ruang
                                ]
                            );
                        }

                        $found = true;
                    }

                    if ($found) {
                        break;
                    }
                }

                if ($found) {
                    break;
                }
            }

            if (!$found) {
                $res['status'] = 'error';
                $res['pesan'] = 'Tidak Ditemukan Solusi Optimal';
            }
            $res['status'] = 'success';
            $res['pesan'] = 'Solusi fisibel ditemukan. Penjadwalan berhasil dilakukan dan sudah disimpan';
        } else {
            $res['status'] = 'error';
            $res['pesan'] = 'Tidak ditemukan jadwal pada Semester ' . ($smt == 0 ? 'Genap' : 'Ganjil') . ' Tahun Akademik ' . str_replace('-', '/', $thak);
        }
        echo json_encode($res);
    }
}
