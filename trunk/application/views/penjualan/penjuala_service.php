<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$file='asset/bin/zetro_beli.frm';
$zfm=new zetro_frmBuilder($file);
$zlb=new zetro_buildlist();
$zlb->config_file($file);
$path='application/views/penjualan';
calender();
AutoCompleted();
link_js('jquery.fixedheader.js,penjualan_service.js','asset/js,'.$path.'/js');
tab_select('');
panel_begin('Service');
panel_multi('listservice','block',false);
if($all_listservice!=''){
	   ($c_listservice!='')?addText(array("<input type='button' id='addservice' value='Service Baru'/>"),array('')):'';
	  echo "<form id='frm1' name='frm1' action='' method='post'>";
	   addText(array('Lokasi',''),
	   		   array("<select id='userlok' name='userlok'></select>",
			   		 "<input type='button' id='ok' value='OK'>"));
	  echo "</form>";
		$zlb->section('service');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
		echo _tabel(true);
}else{
	no_auth();
}
panel_multi_end();
popup_start('addnew','Penerimaan Service Baru',500,800);
	$zfm->Addinput("<input type='hidden' id='ID' name='ID' value=''/>");
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('service',true,'100%');
	$zfm->BuildFormButton('Simpan','service');
popup_end();
panel_end();
?>
<script language="javascript">

$(document).ready(function(e){$('#userlok').html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().")order by id",$this->session->userdata('gudang'));?>").val($('#lok').val()).select();$('#ID_Dept').html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().")order by id",$this->session->userdata('gudang'));?>");if($('#jml_area').val()=='1'){lock('#userlok');lock('#ID_Dept');}else{unlock('#userlok');unlock('#ID_Dept');};$('#ok').click();});
</script>
<input type='hidden' id='stat' val='1' />
<input type='hidden' id='id_member' value='' />