//Document javascript
var path=$('#path').val();
$(document).ready(function(e) {
    $('#listservice').removeClass('tab_button');
	$('#listservice').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
	})
	tglNow('#tgl_service')
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	})
	$('#addservice').click(function(){
		_show_popup('')
	})
	$('#saved-service').click(function(){
		_simpan_data();
	})
	$('#nm_pelanggan').keyup(function(){
		$('#stat').val('1')
	})
	$('#nm_barang').keyup(function(){
		$('#stat').val('1')
	})
	$('#ket_service').keyup(function(){
		$('#stat').val('1')
	})
})

function _show_data()
{
	show_indicator('ListTable',9)
	$.post('get_list_service',$('#frm1').serialize(),
	function(result){
		$('table#ListTable tbody').html(result)
		$('table#ListTable').fixedHeader({'width':(screen.width-40),'height':(screen.height-350)})
	})
}
function _show_popup(id)
{
	if(id!=''){
		 _get_detail_service(id);
	}else{
		$('#ID').val('')
		$(':reset').click();
		_generate_nomor('GI','#no_trans');
		tglNow('#tgl_service')
		$('#tgl_service')
			.removeAttr('readonly')
			.dynDateTime()
		$('#id_lokasi').val($('#lok').val()).select()
		$('#no_trans').attr('readonly','readonly');
	}
	$('#pp-addnew')
		.css({'top':'20%','left':'20%'})
		.show('slow')
	$('#nm_pelanggan').focus().select()
	$('#lock').show();
}

function _get_detail_service(id)
{
	$.post('get_detail_service',{'no_trans':id},
	function(result)
	{
		var r=$.parseJSON(result)
		$('#no_trans').val(r.no_trans).attr('readonly','readonly');
		$('#tgl_service').val(tglFromSql(r.tgl_service)).attr('readonly','readonly')
		$('#nm_pelanggan').val(r.nm_pelanggan).attr('readonly','readonly');
		$('#alm_pelanggan').val(r.alm_pelanggan)
		$('#nm_barang').val(r.nm_barang).attr('readonly','readonly');
		$('#ket_service').val(r.ket_service)
		$('#gr_service').val(r.gr_service).select()
		$('#id_lokasi').val(r.id_lokasi).select()
		
	})
}

	function _generate_nomor(tipe,field){
		$.post('nomor_transaksi',{'tipe':tipe},
		function(result){
			$(field).val(result);
		})
	}

function _simpan_data()
{
	if($('#nm_pelanggan').val()=='')
	{
		jAlert("Nama pelanggan harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	}
	if($('#ket_service').val()=='')
	{
		jAlert("Kerusakan/masalah harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	}
	if($('#nm_barang').val()=='')
	{
		jAlert("Nama barang harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	}
	if($('#stat').val()=='1'){
		$.post('set_service',$('#frm3').serialize(),
		function(result){
			$('#result').html(result).show().fadeOut(5000)
			jConfirm('Print Bukti service?','Confirm',function(r){
				if(r){
					 _print_slip($('#no_trans').val())
					 }
				keluar_addnew()
				$(':reset').click()
				_show_data()
			})
		})
	}
}

function images_click(id,aksi)
{
	switch(aksi)
	{
		case 'edit':
		 _show_popup(id);
		break;
		case 'del':
			jConfirm('Yakin data ini akan dihapus?',"Alert",function(r){
				if(r){
					$.post('del_service',{'id':id},
					function(result){
						_show_data();
					})
				}
			})
		break;
	}
}

function _print_slip(id)
{
	jAlert('Print in progres....');
}