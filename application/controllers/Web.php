<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        define('IS_TEST', 'FALSE');
        $this->load->helper('my');
        $this->load->helper('form');
    }

    public function dayoff($act = '', $ref = '')
    {
        $page['title'] = 'Data Day Off Dosen';
        $page['active_menu'] = 'Plotting Dosen';
        switch ($act) {
            case 'add':
                $page['dosen'] = $this->db->order_by('nama')->get('dosen');
                $page['hari'] = $this->db->order_by('kode')->get('hari');

                $page['page'] = 'dayoff_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $d_total_row = $this->db->query("SELECT a.kode FROM waktu_tidak_bersedia a JOIN hari b ON a.kode_hari = b.kode JOIN jam c ON a.kode_jam = c.kode JOIN dosen d ON a.kode_dosen = d.nidn WHERE d.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                $q_datanya = $this->db->query("SELECT a.*, b.nama as hari, c.range_jam as jam, c.kode as kdjam, d.nama as nama FROM waktu_tidak_bersedia a JOIN hari b ON a.kode_hari = b.kode JOIN jam c ON a.kode_jam = c.kode JOIN dosen d ON a.kode_dosen = d.nidn WHERE d.nama LIKE '%" . $search['value'] . "%' ORDER BY nama, kdjam LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['nama'];
                    $data_ok[] = $d['hari'];
                    $data_ok[] = $d['jam'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/dayoff/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                $cari_data = $this->db->get_where('waktu_tidak_bersedia', ['kode' => $ref]);
                if ($cari_data->num_rows() > 0) {
                    $cd = $cari_data->row();
                    $page['kode_dosen'] = $cd->kode_dosen;
                    $page['kode_hari'] = $cd->kode_hari;
                    $page['kode'] = $ref;
                    $page['dosen'] = $this->db->order_by('nama')->get('dosen');
                    $page['hari'] = $this->db->order_by('kode')->get('hari');
                    $page['page'] = 'dayoff_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/dayoff'));
                }
                break;
            case 'getdata':
                $res = array();
                $kode_dosen = $this->input->post('dosen');
                $kode_hari = $this->input->post('hari');

                //cari data-nya dulu
                $arr_off = array();
                $get_off = $this->db->get_where('waktu_tidak_bersedia', ['kode_dosen' => $kode_dosen, 'kode_hari' => $kode_hari]);

                if ($get_off->num_rows() > 0) {
                    foreach ($get_off->result() as $go) {
                        $arr_off[] = $go->kode_jam;
                    }
                }

                //Data jam
                $jam = '';
                $list_jam = $this->db->get('jam');
                if ($list_jam->num_rows() > 0) {
                    $i = 1;
                    foreach ($list_jam->result() as $lj) {
                        $kode_jam = $lj->kode;
                        $range_jam = $lj->range_jam;

                        if (in_array($kode_jam, $arr_off)) {
                            $status = ' CHECKED';
                        } else {
                            $status = '';
                        }

                        $jam .= '<div class="checkbox"><label for="checkbox' . $i . '" class="form-check-label"><input type="checkbox" id="checkbox' . $i++ . '" name="arr_tdk_bersedia[]" value="' . $kode_jam . '" class="form-check-input"' . $status . '> ' . $range_jam . '</label></div>';
                    }
                }
                $res['jam'] = $jam;
                echo json_encode($res);
                break;
            case 'hapus':
                $kode = $this->input->post('id');
                $this->db->where(
                    [
                        'kode' => $kode
                    ]
                )->delete('waktu_tidak_bersedia');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Kesalahan Sistem: Data day off dosen gagal dihapus.';
                }
                echo json_encode($res);
                break;
            case 'save':
                $arr_off = $this->input->post('arr_tdk_bersedia');
                if (!empty($arr_off)) {
                    $kode_dosen = $this->input->post('kode_dosen');
                    $kode_hari = $this->input->post('hari');
                    //Kita hapus dulu data yang ada
                    $this->db->where(
                        [
                            'kode_dosen' => $kode_dosen,
                            'kode_hari' => $kode_hari
                        ]
                    )->delete('waktu_tidak_bersedia');

                    if (!is_array($arr_off)) {
                        $arr_off = array($arr_off);
                    }
                    $insert['kode_dosen'] = $kode_dosen;
                    $insert['kode_hari'] = $kode_hari;

                    $sukses = 0;

                    foreach ($arr_off as $jam_off) {
                        $insert['kode_jam'] = $jam_off;
                        $this->db->insert('waktu_tidak_bersedia', $insert);
                        if ($this->db->affected_rows() > 0) {
                            $sukses++;
                        }
                    }

                    $notif = array('icon' => 'success', 'pesan' => "Data day off dosen berhasil diinput. Sebanyak $sukses record disimpan.");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/dayoff'));
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Mohon memastikan setidaknya satu jam terpilih");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/dayoff/add'));
                }
                break;
            default:
                $page['page'] = 'dayoff';
                $this->load->view('template', $page);
        }
    }

    public function dosen($act = '', $ref = '')
    {
        $page['title'] = 'Data Dosen';
        $page['active_menu'] = 'Master Data';

        switch ($act) {
            case 'add':
                $page['page'] = 'dosen_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $d_total_row = $this->db->query("SELECT kode FROM dosen a WHERE a.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                $q_datanya = $this->db->query("SELECT a.* FROM dosen a WHERE a.nama LIKE '%" . $search['value'] . "%' ORDER BY a.kode DESC LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['nidn'];
                    $data_ok[] = $d['nama'];
                    $data_ok[] = $d['telp'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/dosen/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                //cadri dulu dosennya
                $cari = $this->db->get_where('dosen', ['kode' => $ref]);
                if ($cari->num_rows() > 0) {
                    $page['data'] = $cari->row_array();
                    $page['page'] = 'dosen_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data dosen tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/dosen'));
                }
                break;
            case 'hapus':
                $this->db->where(['kode' => $this->input->post('id')])
                    ->delete('dosen');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Data dosen gagal dihapus';
                }
                echo json_encode($res);
                break;
            case 'import':
                $page['page'] = 'dosen_import';
                $this->load->view('template', $page);
                break;
            case 'save':
                $nidn = str_replace(' ', '', $this->input->post('nidn'));
                $cek_nidn = $this->db->get_where('dosen', ['nidn' => $nidn]);
                if ($cek_nidn->num_rows() > 0) {
                    $notif = array('icon' => 'error', 'pesan' => "NIDN sudah ada yang menggunakan. Silakan perbaiki atau edit data");
                    $this->session->set_userdata(array('notif' => $notif));
                } else {
                    $insert = array(
                        'nidn' => $nidn,
                        'nama' => $this->input->post('nama'),
                        'alamat' => $this->input->post('alamat'),
                        'telp' => $this->input->post('telp')
                    );
                    $this->db->insert('dosen', $insert);
                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data dosen berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data dosen gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                }
                redirect(base_url('web/dosen'));
                break;
            case 'update':
                $kode = $this->input->post('kode');
                $cek_kode = $this->db->get_where('dosen', ['kode' => $kode]);
                if ($cek_kode->num_rows() > 0) {
                    $ck = $cek_kode->row();
                    //Ada
                    $nidn_lama = $ck->nidn;
                    $nidn_baru = str_replace(' ', '', $this->input->post('nidn'));

                    if (strcmp($nidn_baru, $nidn_lama) != 0) { //Ganti NIDN
                        $cek_nidn = $this->db->query("SELECT * FROM dosen WHERE nidn='$nidn_baru' AND kode <> '$kode'");
                        if ($cek_nidn->num_rows() > 0) {
                            $notif = array('icon' => 'error', 'pesan' => "NIDN sudah ada yang menggunakan. Silakan perbaiki");
                            $this->session->set_userdata(array('notif' => $notif));
                            redirect(base_url('web/dosen/edit/' . $kode));
                        }
                    }

                    $update = array(
                        'nidn' => $nidn_baru,
                        'nama' => $this->input->post('nama'),
                        'alamat' => $this->input->post('alamat'),
                        'telp' => $this->input->post('telp')
                    );
                    $this->db->where(['kode' => $kode])
                        ->update('dosen', $update);

                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data dosen berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data dosen gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                    redirect(base_url('web/dosen'));
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data dosen tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/dosen'));
                }
                break;
            default:
                $page['page'] = 'dosen';
                $this->load->view('template', $page);
        }
    }

    public function excel_export()
    {
        $smt = $this->input->post('smt');
        $thak = $this->input->post('thak');

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
            $res['status'] = 'success';
        } else {
            $res['status'] = 'error';
        }

        echo json_encode($res);
    }

    public function do_excel_export($smt, $thak)
    {
        $this->load->library('PHPExcel');
        $width = array();
        $width[] = 6; //A
        $width[] = 10; //B
        $width[] = 15; //C
        $width[] = 15; //D
        $width[] = 50; //E
        $width[] = 9; //F
        $width[] = 40; //G
        $width[] = 15; //H

        $cols = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R', 18 => 'S', 19 => 'T', 20 => 'U', 21 => 'V', 22 => 'W', 23 => 'X', 24 => 'Y', 25 => 'Z', 26 => 'AA', 27 => 'AB', 28 => 'AC', 29 => 'AD');
        //Border
        $border = array(
            'borders' => array(
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'up' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("(c) OnnyThea - " . date('Y'))
            ->setLastModifiedBy("(c) OnnyThea " . date('Y'))
            ->setTitle("Hasil Penjadwalan dengan Algoritma Genetika")
            ->setSubject("jadwal, kuliah, algoritma, genetika")
            ->setDescription("Hasil Penjadwalan Perkuliahan menggunakan algoritma genetika dengan platform web dan PHP")
            ->setKeywords("jadwal, kuliah, algoritma, genetika")
            ->setCategory("Export");

        //Active sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //Set Title
        $objPHPExcel->getActiveSheet()->setTitle('JADWAL');

        //Lebar setiap kolom
        for ($i = 0; $i < count($width); $i++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$i])->setWidth($width[$i]);
        }

        $txt_smt = ($smt == 0 ? 'GENAP' : 'GANJIL');

        //JUDUL
        $judul = array();
        $judul[] = 'JADWAL PERKULIAHAN';
        $judul[] = 'SEMESTER ' . $txt_smt . ' TAHUN AKADEMIK ' . str_replace('-', '/', $thak);
        $judul[] = 'TANGGAL CETAK ' . date('d-m-Y H:i:s');

        $col = 0;
        $end_col = count($width) - 1;
        $row = 0;

        for ($i = 0; $i < count($judul); $i++) {
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $judul[$i]);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(false);
            $objPHPExcel->getActiveSheet()->mergeCells($cols[$col] . $row . ':' . $cols[$end_col] . $row);
        }

        $head = array();
        $head[] = 'No';
        $head[] = 'Hari';
        $head[] = 'Jam';
        $head[] = 'Kode MK';
        $head[] = 'Nama MK';
        $head[] = 'Kelas';
        $head[] = 'Nama Dosen';
        $head[] = 'Ruangan';

        $row += 2;
        //Kita bikin kolom ke kanan, mulai dari col 0. Baris di set sama
        for ($col = 0; $col < count($head); $col++) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $head[$col]);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFill()->getStartColor()->setARGB('FF0094FF');
            $objPHPExcel->getActiveSheet()->mergeCells($cols[$col] . $row . ':' . $cols[$col] . ($row + 1));
            $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row . ':' . $cols[$col] . ($row + 1))->applyFromArray($border);
        }

        //Cari datanya
        $qry_cari = $this->db->query(
            "SELECT
              c.nama as nama_dosen,
              b.kode_mk as kode_mk,
              b.kelas as kelas,
              d.nama as nama_mk,
              e.nama as nama_ruang,
              f.range_jam as jam,
              g.nama as hari
          FROM
            jadwalkuliah a
          JOIN
            pengampu b ON a.kode_pengampu = b.kode
          JOIN
            dosen c ON b.kode_dosen = c.nidn
          JOIN
            matakuliah d ON b.kode_mk = d.kode_mk
          JOIN
            ruang e ON a.kode_ruang = e.kode
          JOIN
            jam f ON a.kode_jam = f.kode
          JOIN
            hari g ON a.kode_hari = g.kode
          WHERE 
            a.kode_pengampu IN (SELECT 
                                    kode 
                                 FROM 
                                    pengampu 
                                 WHERE
                                    tahun_akademik = \"$thak\" 
                                 AND 
                                    kode_mk IN (
                                       SELECT 
                                          kode_mk 
                                       FROM 
                                          matakuliah 
                                       WHERE 
                                          MOD(semester,2) = $smt
                                    )
                              )
          ORDER BY
            g.kode, f.kode, b.kode_mk"
        );

        if ($qry_cari->num_rows() > 0) {
            $no = 0;
            foreach ($qry_cari->result() as $qc) {
                $row++;
                //Kol A -> nomor
                $col = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $no++);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol B -> Hari
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->hari);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol C -> Jam
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->jam);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol D -> Kode MK
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->kode_mk);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol E -> Nama MK
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->nama_mk);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol F -> Kelas
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->kelas);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol G -> Nama Dosen
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->nama_dosen);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);

                //Kol H -> Nama Ruang
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qc->nama_ruang);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getFont()->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($cols[$col] . $row)->applyFromArray($border);
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //Header
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename="Jadwal_Kuliah_Semester_' . $txt_smt . '_' . $thak . '_(' . date('d-m-y') . ').xlsx"');
        $objWriter->save("php://output");
    }

    public function generate_jadwal()
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
                $cek_jadwal,
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

    public function hari($act = '', $ref = '')
    {
        $page['title'] = 'Data Hari Kuliah';
        $page['active_menu'] = 'Master Data';

        switch ($act) {
            case 'add':
                $page['page'] = 'hari_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $d_total_row = $this->db->query("SELECT kode FROM hari a WHERE a.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                $q_datanya = $this->db->query("SELECT a.* FROM hari a WHERE a.nama LIKE '%" . $search['value'] . "%' ORDER BY a.kode DESC LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['nama'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/hari/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                //cadri dulu dosennya
                $cari = $this->db->get_where('hari', ['kode' => $ref]);
                if ($cari->num_rows() > 0) {
                    $page['data'] = $cari->row_array();
                    $page['page'] = 'hari_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data hari tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/hari'));
                }
                break;
            case 'hapus':
                $this->db->where(['kode' => $this->input->post('id')])
                    ->delete('hari');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Data hari gagal dihapus';
                }
                echo json_encode($res);
                break;
            case 'save':
                $nama = $this->input->post('nama');
                $cek_kode_mk = $this->db->get_where('hari', ['UPPER(nama)' => strtoupper($nama)]);
                if ($cek_kode_mk->num_rows() > 0) {
                    $notif = array('icon' => 'error', 'pesan' => "Nama hari sudah ada pada data tersimpan. Silakan perbaiki atau edit data");
                    $this->session->set_userdata(array('notif' => $notif));
                } else {
                    $insert = array(
                        'nama' => $this->input->post('nama')
                    );
                    $this->db->insert('hari', $insert);
                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data hari berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data hari gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                }
                redirect(base_url('web/hari'));
                break;
            case 'update':
                $kode = $this->input->post('kode');
                $cek_kode = $this->db->get_where('hari', ['kode' => $kode]);
                if ($cek_kode->num_rows() > 0) {
                    $ck = $cek_kode->row();
                    //Ada
                    $nama_lama = $ck->nama;
                    $nama_baru = $this->input->post('nama');

                    if (strcmp(strtoupper($nama_baru), strtoupper($nama_lama)) != 0) { //Ganti Kode MK
                        $cek_nama = $this->db->query("SELECT * FROM hari WHERE UPPER(nama)=\"" . addQuotes(strtoupper($nama_baru)) . "\" AND kode <> '$kode'");
                        if ($cek_nama->num_rows() > 0) {
                            $notif = array('icon' => 'error', 'pesan' => "Nama hari sudah digunakan. Silakan perbaiki");
                            $this->session->set_userdata(array('notif' => $notif));
                            redirect(base_url('web/hari/edit/' . $kode));
                        }
                    }

                    $update = array(
                        'nama' => $this->input->post('nama')
                    );
                    $this->db->where(['kode' => $kode])
                        ->update('hari', $update);

                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data hari berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data hari gagal disimpan atau tidak ada perubahan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                    redirect(base_url('web/hari'));
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data hari tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/hari'));
                }
                break;
            default:
                $page['page'] = 'hari';
                $this->load->view('template', $page);
        }
    }

    public function index()
    {
        $page['title'] = 'Dashboard';
        $page['page'] = 'dashboard';
        $page['active_menu'] = 'Home';
        $this->load->view('template', $page);
    }

    public function jadwal($act = '')
    {
        $page['title'] = 'Jadwal Perkuliahan';
        $page['active_menu'] = 'Penjadwalan';
        switch ($act) {
            default:
                $page['thak'] = $this->db->group_by('tahun_akademik')->order_by('tahun_akademik')->get('pengampu');
                $page['page'] = 'jadwal';
                $this->load->view('template', $page);
        }
    }

    public function matakuliah($act = '', $ref = '')
    {
        $page['title'] = 'Data Mata Kuliah';
        $page['active_menu'] = 'Master Data';

        switch ($act) {
            case 'add':
                $page['page'] = 'matakuliah_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $d_total_row = $this->db->query("SELECT kode FROM matakuliah a WHERE a.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                $q_datanya = $this->db->query("SELECT a.* FROM matakuliah a WHERE a.nama LIKE '%" . $search['value'] . "%' ORDER BY a.kode_mk DESC LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['kode_mk'];
                    $data_ok[] = $d['nama'];
                    $data_ok[] = $d['sks'];
                    $data_ok[] = $d['semester'];
                    $data_ok[] = $d['jenis'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/matakuliah/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                //cadri dulu dosennya
                $cari = $this->db->get_where('matakuliah', ['kode' => $ref]);
                if ($cari->num_rows() > 0) {
                    $page['data'] = $cari->row_array();
                    $page['page'] = 'matakuliah_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data mata kuliah tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/matakuliah'));
                }
                break;
            case 'hapus':
                $this->db->where(['kode' => $this->input->post('id')])
                    ->delete('matakuliah');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Data matakuliah gagal dihapus';
                }
                echo json_encode($res);
                break;
            case 'import':
                $page['page'] = 'matakuliah_import';
                $this->load->view('template', $page);
                break;
            case 'save':
                $kode_mk = str_replace(' ', '', $this->input->post('kode_mk'));
                $cek_kode_mk = $this->db->get_where('matakuliah', ['kode_mk' => $kode_mk]);
                if ($cek_kode_mk->num_rows() > 0) {
                    $notif = array('icon' => 'error', 'pesan' => "Kode Mata Kuliah sudah ada pada data tersimpan. Silakan perbaiki atau edit data");
                    $this->session->set_userdata(array('notif' => $notif));
                } else {
                    $insert = array(
                        'kode_mk' => $kode_mk,
                        'nama' => $this->input->post('nama'),
                        'sks' => $this->input->post('sks'),
                        'semester' => $this->input->post('semester'),
                        'jenis' => $this->input->post('jenis'),
                        'aktif' => $this->input->post('aktif')
                    );
                    $this->db->insert('matakuliah', $insert);
                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data matakuliah berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data matakuliah gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                }
                redirect(base_url('web/matakuliah'));
                break;
            case 'update':
                $kode = $this->input->post('kode');
                $cek_kode = $this->db->get_where('matakuliah', ['kode' => $kode]);
                if ($cek_kode->num_rows() > 0) {
                    $ck = $cek_kode->row();
                    //Ada
                    $kode_mk_lama = $ck->kode_mk;
                    $kode_mk_baru = str_replace(' ', '', $this->input->post('kode_mk'));

                    if (strcmp($kode_mk_baru, $kode_mk_lama) != 0) { //Ganti Kode MK
                        $cek_kode_mk = $this->db->query("SELECT * FROM matakuliah WHERE kode_mk='$kode_mk_baru' AND kode <> '$kode'");
                        if ($cek_kode_mk->num_rows() > 0) {
                            $notif = array('icon' => 'error', 'pesan' => "Kode MK sudah ada yang menggunakan. Silakan perbaiki");
                            $this->session->set_userdata(array('notif' => $notif));
                            redirect(base_url('web/matakuliah/edit/' . $kode));
                        }
                    }

                    $update = array(
                        'kode_mk' => $kode_mk_baru,
                        'nama' => $this->input->post('nama'),
                        'sks' => $this->input->post('sks'),
                        'semester' => $this->input->post('semester'),
                        'jenis' => $this->input->post('jenis'),
                        'aktif' => $this->input->post('aktif')
                    );
                    $this->db->where(['kode' => $kode])
                        ->update('matakuliah', $update);

                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data matakuliah berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data matakuliah gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                    redirect(base_url('web/matakuliah'));
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data matakuliah tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/matakuliah'));
                }
                break;
            default:
                $page['page'] = 'matakuliah';
                $this->load->view('template', $page);
        }
    }

    public function penjadwalan($act = '', $smt = '', $thak = '')
    {
        switch ($act) {
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');
                /*
                  TABLE LIBRARY
                  ---
                  a jadwalkuliah 
                    a.kode          : id autoincrement
                    a.kode_pengampu : kode pada tabel pengampu (b)
                    a.kode_jam      : kode pada tabel jam (g)
                    a.kode_hari     : kode pada tabel hari (e)
                    a.kode_ruang    : kode pada tabel ruang (f)
                  b pengampu
                    b.kelas         : kelas kuliah -> kelas
                    b.kode_dosen    : nidn dosen (bukan kode pada tabel dosen)
                    b.tahun_akademik: tahun akademik
                    b.kode_mk       : Kode MK (bukan kode pada tabel matakuliah)
                  c matakuliah
                    c.kode          : id autoincrement
                    c.kode_mk       : kode matakuliah
                    c.nama          : Nama MK -> nama_mk
                    c.sks           : bobot SKS MK
                    c.semester      : integer, angka semester (1..8)
                  d dosen
                    d.kode          : id autoincrement
                    d.nidn          : nidn dosen
                    d.nama          : nama dosen -> dosen
                  e hari
                    e.kode          : kode hari
                    e.nama          : nama hari -> hari
                  f ruang
                    f.kode          : kode ruang
                    f.nama          : nama ruang -> ruang
                  g jam
                    g.kode          : kode jam
                    g.range_jam     : nama range jam

                  SELECT LIBRARY
                  ---
                  CONCAT_WS : add separator
                  ex:
                     CONCAT_WS('-','I','LOVE','YOU')
                  result:
                     'I-LOVE-YOU'

                */
                $txt_query = "
                        SELECT
                           e.nama as hari,
                           Concat_WS('-',  concat('(', g.kode), concat((SELECT 
                                                                            kode 
                                                                        FROM 
                                                                            jam 
                                                                        WHERE 
                                                                            kode = (SELECT 
                                                                                       jm.kode 
                                                                                    FROM 
                                                                                       jam jm 
                                                                                    WHERE 
                                                                                       MID(jm.range_jam, 1, 5) = MID(g.range_jam, 1, 5)
                                                                                   ) + (c.sks - 1)
                                                                        ),')')) as sesi, 
                           Concat_WS('-', MID(g.range_jam,1,5), (SELECT 
                                                                     MID(range_jam, 7, 5) 
                                                                 FROM 
                                                                     jam 
                                                                 WHERE
                                                                     kode = (SELECT 
                                                                                 jm.kode 
                                                                             FROM 
                                                                                 jam jm 
                                                                             WHERE 
                                                                                 MID(jm.range_jam, 1, 5) = MID(g.range_jam, 1, 5)
                                                                            ) + (c.sks - 1)
                                                                )) as jam_kuliah, 
                           c.kode_mk as kode_mk,
                           c.nama as nama_mk,
                           c.sks as sks,
                           c.semester as semester,
                           b.kelas as kelas,
                           d.nama as dosen,
                           f.nama as ruang 
                        FROM 
                           jadwalkuliah a 
                        LEFT JOIN 
                           pengampu b 
                              ON a.kode_pengampu = b.kode 
                        LEFT JOIN 
                           matakuliah c 
                             ON b.kode_mk = c.kode_mk 
                        LEFT JOIN 
                             dosen d 
                             ON b.kode_dosen = d.kode 
                        LEFT JOIN 
                             hari e 
                             ON a.kode_hari = e.kode 
                        LEFT JOIN ruang f 
                             ON a.kode_ruang = f.kode 
                        LEFT JOIN jam g 
                             ON a.kode_jam = g.kode ";
                if ($smt != '') {
                    $and_query = " AND MOD(c.semester,2) = $smt";
                }

                if ($thak != '') {
                    $and_query .= " AND b.tahun_akademik = '$thak'";
                }


                $d_total_row = $this->db->query($txt_query . "WHERE d.nama LIKE '%" . $search['value'] . "%' $and_query")->num_rows();

                $q_datanya = $this->db->query($txt_query . "WHERE d.nama LIKE '%" . $search['value'] . "%' $and_query ORDER BY e.kode ASC, Jam_Kuliah ASC LIMIT " . $start . ", " . $length . "")->result_array();
                //echo $this->db->last_query();

                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['hari'];
                    $data_ok[] = $d['jam_kuliah'];
                    $data_ok[] = $d['kode_mk'];
                    $data_ok[] = $d['nama_mk'];
                    $data_ok[] = $d['kelas'];
                    $data_ok[] = $d['dosen'];
                    $data_ok[] = $d['ruang'];

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
        }
    }

    public function plotting($act = '', $smt = '', $thak = '')
    {
        $page['title'] = 'Plotting Mengajar Dosen';
        $page['active_menu'] = 'Plotting Dosen';
        switch ($act) {
            case 'add':
                $page['dosen'] = $this->db->order_by('nama')->get('dosen');
                $page['page'] = 'plotting_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $sql  = "SELECT a.kode as kode, b.kode_mk as `kode_mk`, b.nama as `nama_mk`, b.sks, c.nidn, c.kode as `kode_dosen`, c.nama as `nama_dosen`,";
                $sql .= "a.kelas as kelas, a.tahun_akademik as `tahun_akademik` FROM pengampu a LEFT JOIN matakuliah b ON a.kode_mk = b.kode_mk ";
                $sql .= "LEFT JOIN dosen c ON a.kode_dosen = c.nidn WHERE MOD(b.semester,2)= $smt AND a.tahun_akademik = '$thak' ";

                $d_total_row = $this->db->query($sql . "AND c.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                //echo $this->db->last_query();
                $q_datanya = $this->db->query($sql . "AND c.nama LIKE '%" . $search['value'] . "%' ORDER BY b.kode DESC LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['kode_mk'];
                    $data_ok[] = $d['nama_mk'];
                    $data_ok[] = $d['sks'];
                    $data_ok[] = $d['kelas'];
                    $data_ok[] = $d['nama_dosen'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/plotting/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';
                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                $cari_data = $this->db->get_where('pengampu', ['kode' => $smt]);
                if ($cari_data->num_rows() > 0) {
                    $page['data'] = $cari_data->row_array();
                    //cari semester
                    $cari_smt = $this->db->get_where('matakuliah', ['kode_mk' => $page['data']['kode_mk']]);
                    if ($cari_smt->num_rows() > 0) {
                        $cs = $cari_smt->row();
                        $page['semester'] = ((int) $cs->semester) % 2;
                    } else {
                        $page['semester'] = '';
                    }
                    $page['kode'] = $smt;
                    $page['dosen'] = $this->db->order_by('nama')->get('dosen');
                    $page['page'] = 'plotting_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data plotting mengajar dosen tidak ditemukan.");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/plotting'));
                }
                break;
            case 'getmk':
                $smt = $this->input->post('smt');

                $list_matakuliah = $this->db->get_where('matakuliah', ['MOD(semester,2)' => $smt]);
                if ($list_matakuliah->num_rows() > 0) {
                    $list = "<option value=''>Pilih Mata kuliah</option>";
                    foreach ($list_matakuliah->result() as $lm) {
                        $list .= '<option value="' . $lm->kode_mk . '">' . $lm->kode_mk . ' - SMT ' . $lm->semester . ' - ' . $lm->nama . ' (' . $lm->sks . ' SKS)</option>';
                    }
                } else {
                    $list = "<option value='' selected disabled>Pilih Mata kuliah</option>";
                }
                $res['matakuliah'] = $list;
                echo json_encode($res);
                break;
            case 'hapus':
                $this->db->where(['kode' => $this->input->post('id')])
                    ->delete('pengampu');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Data plotting dosen gagal dihapus';
                }
                echo json_encode($res);
                break;
            case 'import':
                $page['page'] = 'plotting_import';
                $this->load->view('template', $page);
                break;
            case 'save':
                $kode_mk = $this->input->post('matakuliah');
                $kelas = $this->input->post('kelas');
                $thak = $this->input->post('tahun_akademik');
                $kode_dosen = $this->input->post('dosen');
                $smt = $this->input->post('semester');

                $cek = $this->db->get_where(
                    'pengampu',
                    [
                        'kode_mk' => $kode_mk,
                        'kelas' => $kelas,
                        'tahun_akademik' => $thak
                    ]
                );
                if ($cek->num_rows() > 0) {
                    $ck = $cek->row();
                    $kode_dosen_old = $ck->kode_dosen;

                    if (strcmp($kode_dosen, $kode_dosen_old) != 0) {
                        $notif = array('icon' => 'error', 'pesan' => "Mata kuliah tersebut di kelas $kelas sudah diplot ke dosen lain.");
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Dosen tersebut sudah diplot mengajar mata kuliah di kelas $kelas.");
                    }
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/plotting/add'));
                } else {
                    $insert['kode_mk'] = $kode_mk;
                    $insert['kode_dosen'] = $kode_dosen;
                    $insert['kelas'] = $kelas;
                    $insert['tahun_akademik'] = $thak;

                    $this->db->insert('pengampu', $insert);
                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data plotting mengajar dosen berhasil disimpan.");
                        $init = array('semester' => $smt, 'tahun_akademik' => $thak);
                        $this->session->set_userdata(array('notif' => $notif, 'init' => $init));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data plotting mengajar dosen GAGAL disimpan.");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                    redirect(base_url('web/plotting'));
                }
                break;
            case 'update':
                $kode = $this->input->post('kode');
                $cari_data = $this->db->get_where('pengampu', ['kode' => $kode]);
                if ($cari_data->num_rows() > 0) {
                    $kode_mk = $this->input->post('matakuliah');
                    $kelas = $this->input->post('kelas');
                    $thak = $this->input->post('tahun_akademik');
                    $kode_dosen = $this->input->post('dosen');
                    $smt = $this->input->post('semester');

                    //Kita hapus semua data kecuali ID dia
                    $this->db->where(
                        [
                            'kode_mk' => $kode_mk,
                            'kelas' => $kelas,
                            'tahun_akademik' => $thak
                        ]
                    )->where_not_in(
                        'kode',
                        [$kode]
                    )
                        ->delete('pengampu');

                    //Sekarang kita update
                    $this->db->where(
                        [
                            'kode' => $kode
                        ]
                    )
                        ->update(
                            'pengampu',
                            [
                                'kode_mk' => $kode_mk,
                                'kelas' => $kelas,
                                'tahun_akademik' => $thak,
                                'kode_dosen' => $kode_dosen
                            ]
                        );

                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data plotting mengajar dosen berhasil diperbaharui.");
                        $init = array('semester' => $smt, 'tahun_akademik' => $thak);
                        $this->session->set_userdata(array('notif' => $notif, 'init' => $init));
                        redirect(base_url('web/plotting'));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data plotting mengajar dosen GAGAL disimpan.");
                        $this->session->set_userdata(array('notif' => $notif));
                        redirect(base_url('web/plotting/edit/' . $kode));
                    }
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data plotting mengajar dosen tidak ditemukan.");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/plotting'));
                }
                break;
            default:
                $page['thak'] = $this->db->group_by('tahun_akademik')->order_by('tahun_akademik')->get('pengampu');
                $page['page'] = 'plotting';
                $this->load->view('template', $page);
        }
    }

    public function ruang($act = '', $ref = '')
    {
        $page['title'] = 'Data Ruang Kuliah';
        $page['active_menu'] = 'Master Data';

        switch ($act) {
            case 'add':
                $page['page'] = 'ruang_add';
                $this->load->view('template', $page);
                break;
            case 'data':
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $draw = $this->input->post('draw');
                $search = $this->input->post('search');

                $d_total_row = $this->db->query("SELECT kode FROM ruang a WHERE a.nama LIKE '%" . $search['value'] . "%'")->num_rows();

                $q_datanya = $this->db->query("SELECT a.* FROM ruang a WHERE a.nama LIKE '%" . $search['value'] . "%' ORDER BY a.kode DESC LIMIT " . $start . ", " . $length . "")->result_array();
                $data = array();
                $no = ($start + 1);

                foreach ($q_datanya as $d) {
                    $data_ok = array();
                    $data_ok[] = $no++;
                    $data_ok[] = $d['nama'];
                    $data_ok[] = $d['kapasitas'];
                    $data_ok[] = $d['jenis'];

                    $data_ok[] = '<div class="btn-group">
                          <a href="' . base_url('web/ruang/edit/' . $d['kode']) . '"  class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-square" style="margin-left: 0px; color: #fff"></i></a>
                          <a href="#" onclick="hapus(\'' . $d['kode'] . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash" style="margin-left: 0px; color: #fff"></i></a>
                         ';

                    $data[] = $data_ok;
                }

                $json_data = array(
                    "draw" => $draw,
                    "iTotalRecords" => $d_total_row,
                    "iTotalDisplayRecords" => $d_total_row,
                    "data" => $data
                );
                j($json_data);
                break;
            case 'edit':
                //cadri dulu dosennya
                $cari = $this->db->get_where('ruang', ['kode' => $ref]);
                if ($cari->num_rows() > 0) {
                    $page['data'] = $cari->row_array();
                    $page['page'] = 'ruang_edit';
                    $this->load->view('template', $page);
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data ruangan tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/ruang'));
                }
                break;
            case 'hapus':
                $this->db->where(['kode' => $this->input->post('id')])
                    ->delete('ruang');
                if ($this->db->affected_rows() > 0) {
                    $res['sukses'] = 'true';
                } else {
                    $res['sukses'] = 'false';
                    $res['pesan'] = 'Data hari  gagal dihapus';
                }
                echo json_encode($res);
                break;
            case 'import':
                $page['page'] = 'ruang_import';
                $this->load->view('template', $page);
                break;
            case 'save':
                $nama = $this->input->post('nama');
                $cek_kode_mk = $this->db->get_where('ruang', ['UPPER(nama)' => strtoupper($nama)]);
                if ($cek_kode_mk->num_rows() > 0) {
                    $notif = array('icon' => 'error', 'pesan' => "Nama ruangan sudah ada pada data tersimpan. Silakan perbaiki atau edit data");
                    $this->session->set_userdata(array('notif' => $notif));
                } else {
                    $insert = array(
                        'nama' => $this->input->post('nama'),
                        'kapasitas' => $this->input->post('kapasitas'),
                        'jenis' => $this->input->post('jenis')
                    );
                    $this->db->insert('ruang', $insert);
                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data ruangan berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data ruangan gagal disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                }
                redirect(base_url('web/ruang'));
                break;
            case 'update':
                $kode = $this->input->post('kode');
                $cek_kode = $this->db->get_where('ruang', ['kode' => $kode]);
                if ($cek_kode->num_rows() > 0) {
                    $ck = $cek_kode->row();
                    //Ada
                    $nama_lama = $ck->nama;
                    $nama_baru = $this->input->post('nama');

                    if (strcmp(strtoupper($nama_baru), strtoupper($nama_lama)) != 0) { //Ganti Kode MK
                        $cek_nama = $this->db->query("SELECT * FROM ruang WHERE UPPER(nama)='" . strtoupper($nama_baru) . "' AND kode <> '$kode'");
                        if ($cek_nama->num_rows() > 0) {
                            $notif = array('icon' => 'error', 'pesan' => "Nama ruangan sudah digunakan. Silakan perbaiki");
                            $this->session->set_userdata(array('notif' => $notif));
                            redirect(base_url('web/ruang/edit/' . $kode));
                        }
                    }

                    $update = array(
                        'nama' => $this->input->post('nama'),
                        'kapasitas' => $this->input->post('kapasitas'),
                        'jenis' => $this->input->post('jenis')
                    );
                    $this->db->where(['kode' => $kode])
                        ->update('ruang', $update);

                    if ($this->db->affected_rows() > 0) {
                        $notif = array('icon' => 'success', 'pesan' => "Data ruangan berhasil disimpan");
                        $this->session->set_userdata(array('notif' => $notif));
                    } else {
                        $notif = array('icon' => 'error', 'pesan' => "Data ruangan gagal disimpan atau tidak ada perubahan");
                        $this->session->set_userdata(array('notif' => $notif));
                    }
                    redirect(base_url('web/ruang'));
                } else {
                    $notif = array('icon' => 'error', 'pesan' => "Data ruangan tidak ditemukan");
                    $this->session->set_userdata(array('notif' => $notif));
                    redirect(base_url('web/ruang'));
                }
                break;
            default:
                $page['page'] = 'ruang';
                $this->load->view('template', $page);
        }
    }
}
