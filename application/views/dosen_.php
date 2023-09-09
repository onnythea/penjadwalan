<div class="content">
   <div class="header">
      <h1 class="page-title"><?= $page_title; ?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?= base_url(); ?>">Beranda</a> <span class="divider">/</span></li>
      <li class="active"><?= $page_title; ?></li>
   </ul>

   <div class="container-fluid">
      <?php if ($this->session->flashdata('msg')) { ?>
         <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?= $this->session->flashdata('msg'); ?>
         </div>
      <?php } ?>

      <div class="row-fluid">
         <a href="<?= base_url() . 'web/dosen_add'; ?>"> <button class="btn btn-primary pull-right"><i class="icon-plus"></i> Tambah Data</button></a>

         <form class="form-inline" method="POST" action="<?= base_url() . 'web/dosen_search' ?>">
            <input type="text" placeholder="Nama" name="search_query" value="<?= isset($search_query) ? $search_query : ''; ?>">
            <button type="submit" class="btn">Cari</button>
            <a href="<?= base_url() . 'web/dosen'; ?>"><button type="button" class="btn">Clear</button> </a>
         </form>

         <?php if ($rs_dosen->num_rows() === 0) : ?>
            <div class="alert alert-error">
               <button type="button" class="close" data-dismiss="alert">�</button>
               Tidak ada data.
            </div>
         <?php else : ?>
            <div id="content_ajax">

               <div class="pagination" id="ajax_paging">
                  <ul>
                     <?= $this->pagination->create_links(); ?>
                  </ul>
               </div>

               <div class="widget-content">
                  <table class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>No</th>
                           <th>NIDN</th>
                           <th>Nama</th>
                           <th>Telp</th>
                           <th style="width: 65px;">Aksi</th>
                        </tr>
                     </thead>
                     <tbody>

                        <?php
                        $i =  intval($start_number) + 1;
                        foreach ($rs_dosen->result() as $dosen) { ?>
                           <tr>
                              <td><?= str_pad((int)$i, 2, 0, STR_PAD_LEFT); ?></td>
                              <td><?= $dosen->nidn; ?></td>
                              <td><?= $dosen->nama; ?></td>
                              <td><?= $dosen->telp; ?></td>

                              <td>
                                 <a href="<?= base_url() . 'web/dosen_edit/' . $dosen->kode; ?>" class="btn btn-small"><i class="icon-pencil"></i></a>
                                 <a href="<?= base_url() . 'web/dosen_delete/' . $dosen->kode; ?>" class="btn btn-small" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="icon-trash"></i></a>
                              </td>
                           </tr>
                        <?php $i++;
                        } ?>

                     </tbody>
                  </table>
               </div>


               <div class="pagination" id="ajax_paging">
                  <ul>
                     <?= $this->pagination->create_links(); ?>
                  </ul>
               </div>
            </div>
         <?php endif; ?>
         <footer>
            <hr />
            <p class="pull-right">Developed by <a href="http://www.mycoding.net" target="_blank">My Coding</a></p>
            <p>&copy;2020 Portnine</p>
         </footer>
      </div>
   </div>
</div>