<?php
/*$tanggal = date("d-m-Y");
$jam = date("H:i:s");
$var_magin_left = 10;
$p = printer_open('\\\\192.168.1.115\\Canon iP2700 series');
printer_set_option($p, PRINTER_MODE, "RAW"); // mode disobek (gak ngegulung kertas)
printer_start_doc($p);
printer_start_page($p);
$font = printer_create_font("Arial", 8, 7, PRINTER_FW_NORMAL, false, false, false, 0);
printer_select_font($p, $font);
printer_draw_text($p, ".: CASMADI LIBRARY :.",130,0);
printer_draw_text($p, date("Y/m/d H:i:s"),255, 40);

printer_draw_text($p, "Kasir", $var_magin_left, 40);
printer_draw_text($p, ":",70, 40);
printer_draw_text($p, 'casmadi',80, 40);

// Header Bon
$pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
printer_select_pen($p, $pen);
printer_draw_line($p, $var_magin_left, 65, 400, 65);
printer_draw_text($p, "TRANSAKSI", $var_magin_left, 70);
printer_draw_text($p, "QTY", 290, 70);
printer_draw_text($p, "PRICE", 350, 70);
printer_draw_line($p, $var_magin_left, 85, 400, 85);

$row +=150;
printer_draw_text($p, "Terima Kasih Atas Kunjungan Anda", 80, $row);

printer_delete_font($font);
printer_end_page($p);
printer_end_doc($p);
printer_close($p);
*/?>