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
	});
	tglNow('#tgl_service');
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	});
	$('#addservice').click(function(){
		_show_popup('')
	});
	$('#saved-service').click(function(){
		_simpan_data();
	});
	$('#nm_pelanggan')
		.coolautosuggest({
				url		:path+'member/get_anggota?limit=15&str=',
				width	:250,
				showDescription	:true,
				onSelected		:function(result){
					$('#id_member').val(result.ID);
					$('#alm_pelanggan').val(result.Alamat);
					$('#tlp_pelanggan').val(result.Telepon);
					$('#nm_barang').focus().select();
				}
		})
		.keyup(function(){
		$('#stat').val('1');
	});
	$('#nm_barang').keyup(function(){
		$('#stat').val('1')
	});
	$('#ket_service').keyup(function(){
		$('#stat').val('1')
	});
});

function _show_data()
{
	show_indicator('ListTable',9);
	unlock('select');
	$.post('get_list_service',$('#frm1').serialize(),
	function(result){
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({'width':(screen.width-40),'height':(screen.height-350)});
		lock('select')
	})
};
function _show_popup(id)
{
	if(id!=''){
		 _get_detail_service(id);
	}else{
		$('#ID').val('')
		$(':reset').click();
		_generate_nomor('GI','#no_trans');
		tglNow('#tgl_service');
		$('#tgl_service')
			.removeAttr('readonly')
			.dynDateTime();
		$('#id_lokasi').val($('#lok').val()).select();
		$('#no_trans').attr('readonly','readonly');
		unlock('#nm_pelanggan');
		$('nm_barang').removeAttr('readonly');
	};
	$('#pp-addnew')
		.css({'top':'5%','left':'20%'})
		.show('slow');
	$('#nm_pelanggan').focus().select();
	$('#lock').show();
};

function _get_detail_service(id)
{
	$.post('get_detail_service',{'no_trans':id},
	function(result)
	{
		var r=$.parseJSON(result)
		$('#no_trans').val(r.no_trans).attr('readonly','readonly');
		$('#tgl_service').val(tglFromSql(r.tgl_service)).attr('readonly','readonly')
		$('#nm_pelanggan').val(r.nm_pelanggan).attr('disabled','disabled');
		$('#alm_pelanggan').val(r.alm_pelanggan);
		$('#tlp_pelanggan').val(r.tlp_pelanggan);
		$('#nm_barang').val(r.nm_barang).attr('readonly','readonly');
		$('#tp_barang').val(r.merk_barang);
		$('#no_seri_barang').val(r.noser_barang);
		$('#alm_pelanggan').val(r.alm_pelanggan);
		$('#ket_barang').val(r.ket_service);
		$('#ket_service').val(r.masalah_service);
		$('#gr_service').val(r.gr_service).select();
		$('#id_lokasi').val(r.id_lokasi).select();
		
	})
};

	function _generate_nomor(tipe,field){
		$.post('nomor_transaksi',{'tipe':tipe},
		function(result){
			$(field).val(result);
		})
	};

function _simpan_data()
{
	if($('#nm_pelanggan').val()=='')
	{
		jAlert("Nama pelanggan harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	};
	if($('#ket_service').val()=='')
	{
		jAlert("Kerusakan/masalah harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	};
	if($('#nm_barang').val()=='')
	{
		jAlert("Nama barang harus diisi","Alert",function(r){
			 $('#stat').val('0')
			 })
	};
	if($('#stat').val()=='1'){
		unlock('#nm_pelanggan,#id_lokasi');
		$.post('set_service',$('#frm3').serialize(),
		function(result){
			$('#result').html(result).show().fadeOut(5000);
			jConfirm('Print Bukti service?','Confirm',function(r){
				if(r){
					 _print_slip($('#no_trans').val(),$('#tgl_service').val());
				};
				keluar_addnew();
				$(':reset').click();
				_show_data();
			})
		})
	}
};

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
			});
		break;
	}
};

function _print_slip(id,tgl)
{
	$.post(path+'print_slip/print_slip_service',{
		'no_transaksi'	:id,
		'tanggal'		:tgl,
		'lokasi'		:$('#lok').val()
	},function(result){
		buka_wind(result);
	})
};
function buka_wind(id)
{
	 window.open("http://localhost/putrisvn/penjualan_slipt.php?userid="+id,
				  "mediumWindow",
				  "width=550,height=225,left="+((screen.width/2)-(550/2))+", top=150" +
				  "menubar=No,scrollbars=No,addressbar=No,status=No,toolbar=No");
};
