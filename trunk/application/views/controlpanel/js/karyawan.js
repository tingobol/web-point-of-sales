// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#listkaryawan').removeClass('tab_button');
	$('#listkaryawan').addClass('tab_select');
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
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	})
	//add new pressed
	$('#addkaryawan').click(function(){
		$('#Nama').removeAttr('readonly');
		$('#ID').val('')
		$(':reset').click();
		_show_popup('')
	})
	//simpan karyawan baru
	$('#saved-person').click(function(){
		_simpan_data();
	})
})

function _show_popup(id)
{
	if(id!=''){
		 _get_detail_karyawan(id);
	}else{
		$('#Nama').removeAttr('readonly');
		$('#ID_Dept').val($('#lok').val()).select()
	}
	$('#pp-addnew')
		.css({'top':'20%','left':'20%'})
		.show('slow')
	$('#lock').show();
}

function _show_data()
{
	show_indicator('ListTable',5)
	$.post('get_list_karyawan',{
		'id_lokasi':$('#userlok').val()
	},function(result){
		$('table#ListTable tbody').html(result)
		$('table#ListTable').fixedHeader({'width':(screen.width-200),'height':(screen.height-325)})
	})
}

function _simpan_data()
{
	if($('#Nama').val()!=''){
		$.post('set_karyawan',$('#frm3').serialize(),
		function(result){
			$(':reset').click()
			keluar_addnew()
			_show_data();
		})
	}else{
		jAlert('Field Nama Wajib di isi');
	}
}
function _get_detail_karyawan(id)
{
		$.post('get_detail_karyawan',{'id':id},
		function(result){
			var r=$.parseJSON(result);
			$('#ID').val(r.ID);
			$('#NoUrut').val(r.NIP);
			$('#Nama').val(r.Nama);
			$('#ID_Dept').val(r.ID_Dept).select();
			$('#ID_Kelamin').val(r.ID_Kelamin).select();
			$('#Nama').attr('readonly','readonly');
		})
}

function images_click(id,aksi)
{
	switch(aksi)
	{
		case 'edit':
		_show_popup(id);
		break;
		case 'del':
		jConfirm('Yakin nama karyawan ini akan dihapus?','Warning',function(r){
			if(r){
				$.post('del_karyawan',{'id':id},function(result){
					_show_data();
				})
			}
		})
	}
}