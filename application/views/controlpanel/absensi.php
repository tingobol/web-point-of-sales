<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
link_js('jquery.fixedheader.js,absensi.js','asset/js,'.$path.'/js');
tab_select('');
panel_begin('Kehadiran');
panel_multi('absensiharian','block',false);
if($all_controlpanel__absensi!=''){
	   addText(array('Tanggal'),array("<input type='text' class='w50' id='tgl_absen' name='tgl_absen' value='".date('d/m/Y')."' readonly>"));
	   addText(array('Lokasi ',''),
	   		   array("<select id='userlok' name='userlok'></select>",
			   		 "<input type='button' id='ok' value='OK'>"));
		$zlb->section('Absensi');
		$zlb->aksi(false);
		$zlb->icon();
		$zlb->Header('75%');
		echo _tabel(true);
}else{
	no_auth();
}
panel_multi_end();
panel_end();

?>
<script language="javascript">
	$(document).ready(function(e) {
    $('#userlok')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		.val($('#lok').val()).select()
    $('#ID_Dept')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		
	//disabled jika satu lokasi
	 if($('#jml_area').val()=='1')
	 { 
	 	lock('#userlok');
		lock('#ID_Dept')
	 }else{
		 unlock('#userlok');
		 unlock('#ID_Dept')
	 }
	  $('#ok').click();
    });

</script>
	