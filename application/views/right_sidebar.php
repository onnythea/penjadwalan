<aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
    <div class="logo">
        <a href="#">
            <img src="<?= base_url('assets'); ?>/images/icon/logo-gold.png" alt="Cool Admin" />
        </a>
    </div>
    <div class="menu-sidebar2__content js-scrollbar2">
        <nav class="navbar-sidebar2">
            <ul class="list-unstyled navbar__list">
                <?php
                //Kita load jadwal
                $list_menu = $this->db->query("SELECT * FROM menu WHERE (li_class='has-sub' OR li_class='orphan') ORDER BY id");
                if ($list_menu->num_rows() > 0) {

                    foreach ($list_menu->result() as $lm) {
                        $id = $lm->id;
                        $li_class = $lm->li_class;
                        $display = $lm->display;
                        $link = $lm->link;
                        $icon = $lm->icon;

                        $class = '';

                        if ($active_menu == $display) {
                            $class .= ' class="active';
                            if ($li_class == 'has-sub') {
                                $class .= ' has-sub';
                            }
                        }
                        if (!empty($class)) {
                            $class .= '"';
                        }
                ?>
                        <li<?= $class; ?>>
                            <?php
                            if ($li_class == 'has-sub') {
                            ?>
                                <a class="js-arrow" href="#">
                                    <i class="<?= $icon; ?>"></i><?= $display; ?>
                                    <span class="arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">
                                    <?php
                                    //cari sub menu
                                    $list_sub_menu = $this->db->get_where('menu', ['parent' => $id]);
                                    if ($list_sub_menu->num_rows() > 0) {
                                        foreach ($list_sub_menu->result() as $lsm) {
                                            $display_sub = $lsm->display;
                                            $link_sub = $lsm->link;
                                            $icon_sub = $lsm->icon;

                                    ?>
                                            <li>
                                                <a href="<?= base_url($link_sub); ?>">
                                                    <i class="<?= $icon_sub; ?>"></i><?= $display_sub; ?></a>
                                            </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            <?php
                            } else {
                            ?>
                                <a href="<?= base_url($link); ?>">
                                    <i class="<?= $icon; ?>"></i><?= $display; ?></a>
                            <?php
                            }
                            ?>
                            </li>
                    <?php
                    }
                }
                    ?>
            </ul>
        </nav>
    </div>
</aside>