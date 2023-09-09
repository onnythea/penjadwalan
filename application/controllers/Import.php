<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set("Asia/Jakarta");
class Import extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->db->query("SET NAMES 'utf8'");
        $this->db->query("SET CHARACTER SET utf8");
        $this->db->query("SET time_zone='+7:00'");

        $this->kolom_xl = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    }

    public function dosen()
    {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 100;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            $n_skip = 0;
            $j = $idx_baris_mulai;
            do {
                $nidn = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $nama = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $alamat = $_sheet->getCell("C" . $j)->getCalculatedValue();
                $telp = $_sheet->getCell("D" . $j)->getCalculatedValue();

                $nidn = str_replace(' ', '', $nidn);

                if ($nidn != "") {
                    $cek_nidn = $this->db->get_where('dosen', ['nidn' => $nidn]);
                    if ($cek_nidn->num_rows() == 0) {
                        if ($nama != "" && $alamat != "" && $telp != "") {
                            $data[] = "(\"" . $nidn . "\", \"" . $nama . "\", \"" . $alamat . "\", \"" . $telp . "\")";
                        }
                    } else {
                        $n_skip++;
                    }
                } else {
                    $n_skip++;
                }
                $j++;
            } while ($nidn != "" || $nama != "" || $alamat != "" || $telp != "");
            $n_skip--;

            $n_sukses = 0;
            if (count($data) > 0) {
                $strq = "INSERT INTO dosen (nidn, nama, alamat, telp) VALUES ";
                $strq .= implode(",", $data) . ";";
                $this->db->query($strq);
                $n_sukses = $this->db->affected_rows();
            }

            $notif = array('icon' => 'success', 'pesan' => "Data dosen berhasil diimport. Sebanyak $n_sukses record tersimpan dan $n_skip record dilewati.");
            $this->session->set_userdata(array('notif' => $notif));
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('web/dosen');
    }

    private function escapeString($val)
    {
        $db = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
        return $val;
    }

    public function get_jur($id = '')
    {
        $this->db->where(array('kode' => $id));
        $list = $this->db->get('m_guru');
        if ($list->num_rows() > 0) {
            foreach ($list->result() as $ls) {
                $nama_jur = $ls->nama;
            }
        } else {
            $nama_jur = '';
        }
        return $nama_jur;
    }

    public function matakuliah()
    {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 100;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            $n_skip = 0;
            $j = $idx_baris_mulai;
            do {
                $kode_mk = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $nama = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $sks = $_sheet->getCell("C" . $j)->getCalculatedValue();
                $semester = $_sheet->getCell("D" . $j)->getCalculatedValue();
                $jenis = $_sheet->getCell("E" . $j)->getCalculatedValue();
                $aktif = $_sheet->getCell("F" . $j)->getCalculatedValue();

                $kode_mk = str_replace(' ', '', $kode_mk);
                $aktif = proper($aktif);

                if ($kode_mk != "") {
                    $cek_kode_mk = $this->db->get_where('matakuliah', ['kode_mk' => $kode_mk]);
                    if ($cek_kode_mk->num_rows() == 0) {
                        if ($nama != "" && $sks != "" && $semester != "" && $jenis != "" && $aktif != "") {
                            $data[] = "(\"" . $kode_mk . "\", \"" . $nama . "\", \"" . $sks . "\", \"" . $semester . "\", \"" . $jenis . "\", \"" . $aktif . "\")";
                        }
                    } else {
                        $n_skip++;
                    }
                } else {
                    $n_skip++;
                }
                $j++;
            } while ($kode_mk != "" || $sks != "" || $semester != "" || $jenis != "" || $aktif != "");
            $n_skip--;

            $n_sukses = 0;
            if (count($data) > 0) {
                $strq = "INSERT INTO matakuliah (kode_mk, nama, sks, semester,jenis,aktif) VALUES ";
                $strq .= implode(",", $data) . ";";
                $this->db->query($strq);
                $n_sukses = $this->db->affected_rows();
            }

            $notif = array('icon' => 'success', 'pesan' => "Data matakuliah berhasil diimport. Sebanyak $n_sukses record tersimpan dan $n_skip record dilewati.");
            $this->session->set_userdata(array('notif' => $notif));
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('web/matakuliah');
    }

    public function plotting()
    {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 100;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            $n_skip = 0;
            $n_mk_skip = 0;
            $n_ds_skip = 0;
            $n_skip = 0;
            $j = $idx_baris_mulai;
            $n_rows = 0;
            $n_ada = 0;

            do {
                $kode_mk = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $nidn = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $kelas = $_sheet->getCell("C" . $j)->getCalculatedValue();
                $thak = $_sheet->getCell("D" . $j)->getCalculatedValue();

                $thak = str_replace('/', '-', $thak);
                $kelas = strtoupper($kelas);

                //Cek data MK, apakah ada di database
                if ($kode_mk != "") {
                    $cek_kode_mk = $this->db->get_where('matakuliah', ['kode_mk' => $kode_mk]);
                    if ($cek_kode_mk->num_rows() > 0) {
                        //Cek NIDN
                        if ($nidn != "") {
                            $cek_nidn = $this->db->get_where('dosen', ['nidn' => $nidn]);
                            if ($cek_nidn->num_rows() > 0) {
                                //Cek keberadaannya di mapping
                                $cek = $this->db->get_where(
                                    'pengampu',
                                    [
                                        'kode_mk' => $kode_mk,
                                        'kelas' => $kelas,
                                        'tahun_akademik' => $thak
                                    ]
                                );
                                if ($cek->num_rows() > 0) { //Sudah ada yg diplot
                                    $n_ada++;
                                } else {
                                    if ($kelas != "" && $thak != "") {
                                        $data[] = "(\"" . $kode_mk . "\", \"" . $nidn . "\", \"" . $kelas . "\", \"" . $thak . "\")";
                                    } else {
                                        $n_skip++;
                                    }
                                }
                            } else {
                                $n_ds_skip++;
                            }
                        } else {
                            $n_skip++;
                        }
                    } else {
                        $n_mk_skip++;
                    }
                } else {
                    $n_skip++;
                }
                $j++;
                $n_rows++;
            } while ($kode_mk != "" || $nidn != "" || $kelas != "" || $thak != "");
            $n_skip--;
            $n_rows--;

            $n_sukses = 0;
            if (count($data) > 0) {
                $strq = "INSERT INTO pengampu (kode_mk, kode_dosen, kelas, tahun_akademik) VALUES ";
                $strq .= implode(",", $data) . ";";
                $this->db->query($strq);
                $n_sukses = $this->db->affected_rows();
            }

            $pesan = "Data matakuliah berhasil diimport. Sebanyak $n_rows baris terimport";
            if ($n_sukses > 0) {
                $pesan .= ", $n_sukses record tersimpan";
            }
            if ($n_ada > 0) {
                $pesan .= ", $n_ada record dilewati karena sudah ada";
            }
            if ($n_skip > 0) {
                $pesan .= ", $n_skip record dilewati karena data tidak valid";
            }
            if ($n_ds_skip > 0) {
                $pesan .= ", $n_ds_skip record dilewati karena data dosen tidak ditemukan";
            }
            if ($n_mk_skip > 0) {
                $pesan .= ", $n_mk_skip record dilewati karena data matakuliah tidak ditemukan";
            }
            $pesan .= ".";

            $notif = array('icon' => 'success', 'pesan' => $pesan);
            $this->session->set_userdata(array('notif' => $notif));
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('web/plotting');
    }

    public function ruang()
    {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 100;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            $n_skip = 0;
            $j = $idx_baris_mulai;
            do {
                $nama = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $kapasitas = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $jenis = $_sheet->getCell("C" . $j)->getCalculatedValue();

                $jenis = strtoupper($jenis);

                if ($nama != "") {
                    $cek_nama = $this->db->get_where('matakuliah', ['UPPER(nama)' => strtoupper($nama)]);
                    if ($cek_nama->num_rows() == 0) {
                        if ($kapasitas != "" && $jenis != "") {
                            $data[] = "(\"" . $nama . "\", \"" . $kapasitas . "\", \"" . $jenis . "\")";
                        }
                    } else {
                        $n_skip++;
                    }
                } else {
                    $n_skip++;
                }
                $j++;
            } while ($nama != "" || $kapasitas != "" || $jenis != "");
            $n_skip--;

            $n_sukses = 0;
            if (count($data) > 0) {
                $strq = "INSERT INTO ruang (nama, kapasitas,jenis) VALUES ";
                $strq .= implode(",", $data) . ";";
                $this->db->query($strq);
                $n_sukses = $this->db->affected_rows();
            }

            $notif = array('icon' => 'success', 'pesan' => "Data ruangan berhasil diimport. Sebanyak $n_sukses record tersimpan dan $n_skip record dilewati.");
            $this->session->set_userdata(array('notif' => $notif));
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('web/ruang');
    }

    public function siswa()
    {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 5000;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            for ($j = $idx_baris_mulai; $j <= $idx_baris_selesai; $j++) {
                $nuptk = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $nama = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $kode_jurusan = $_sheet->getCell("C" . $j)->getCalculatedValue();
                $instansi = $_sheet->getCell("D" . $j)->getCalculatedValue();

                if ($nuptk != "" || $nama != "" || $kode_jurusan != "") {
                    //cari kode jurusan
                    $qry = $this->db->get_where('m_admin', array('username' => $kode_jurusan));
                    if ($qry->num_rows() > 0) {
                        $jurusan = $this->get_jur($kode_jurusan);
                        $data[] = "(\"$nuptk\", \"$nama\", \"$kode_jurusan\", \"$jurusan\", \"$instansi\", now())";
                    }
                }
            }

            $strq = "INSERT INTO m_siswa (nuptk, nama, kode_jurusan, jurusan, instansi, tgl_insert) VALUES ";

            $strq .= implode(",", $data) . ";";

            $this->db->query($strq);

            //echo "<script>alert('".$this->db->last_query()."');</script>";
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('adm/mahasiswa');
    }

    public function soal()
    {
        $p = $this->input->post();

        $idx_baris_mulai = 3;
        $idx_baris_selesai = 200;
        $jml_opsi = $this->config->item('jml_opsi');
        $arr_col = array(1 => 'C', 2 => 'D', 3 => 'E', 4 => 'F', 5 => 'G');

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;

        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file . $_FILES['import_excel']['name']);

        $file   = explode('.', $_FILES['import_excel']['name']);
        $length = count($file);

        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {

            $tmp    = './upload/temp/' . $_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p

            $this->load->library('excel'); //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);

            $_sheet = $excel->setActiveSheetIndexByName('data');

            $data = array();
            for ($j = $idx_baris_mulai; $j <= $idx_baris_selesai; $j++) {
                $opsi_1 = '';
                $opsi_2 = '';
                $opsi_3 = '';
                $opsi_4 = '';
                $opsi_5 = '';

                $bobot = $_sheet->getCell("A" . $j)->getCalculatedValue();
                $soal = $_sheet->getCell("B" . $j)->getCalculatedValue();
                $soal = str_replace("'", "\'", $soal);
                for ($i = 1; $i <= $jml_opsi; $i++) {
                    eval("\$opsi_$i = \$_sheet->getCell('" . $arr_col[$i] . "$j')->getCalculatedValue();");
                }
                /*
                $opsi_1 = $_sheet->getCell("C".$j)->getCalculatedValue();
                $opsi_2 = $_sheet->getCell("D".$j)->getCalculatedValue();
                $opsi_3 = $_sheet->getCell("E".$j)->getCalculatedValue();
                $opsi_4 = $_sheet->getCell("F".$j)->getCalculatedValue();
                $opsi_5 = $_sheet->getCell("G".$j)->getCalculatedValue();
                */
                $kunci = $_sheet->getCell("H" . $j)->getCalculatedValue();

                if ($soal != "") {
                    $data[] = "('" . $p['id_guru'] . "', '" . $p['id_mapel'] . "', '" . $bobot . "', '" . $soal . "', '#####" . $opsi_1 . "', '#####" . $opsi_2 . "', '#####" . $opsi_3 . "', '#####" . $opsi_4 . "', '#####" . $opsi_5 . "', '" . $kunci . "', NOW(), 0, 0)";
                }
            }

            $strq = "INSERT INTO m_soal (id_guru, id_mapel, bobot, soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban, tgl_input, jml_benar, jml_salah) VALUES ";

            $strq .= implode(",", $data) . ";";
            //echo $strq;
            //exit;
            //$strq = $this->escapeString($strq);

            $this->db->query($strq);
        } else {
            exit('Bukan File Excel...'); //pesan error tipe file tidak tepat
        }
        redirect('adm/m_soal');
    }
}
