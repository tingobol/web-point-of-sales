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
	
	$('input:checkbox')
		.live('click',function(){
			var id=$(this).attr('id');
			var st=($('input:checkbox#'+id).is(':checked'))?'Y':'N';
			var nik=id.split('-')
			$.post('set_absensi',{
				'id_karyawan'	:nik[1],
				'tgl_absen'		:$('#tgl_absen').val(),
				'on_absen'		:st,
				'id_lokasi'		:$('#userlok').val()
			},function(result){
				$('#result')
					.html(result)
					.show('slow')
					.fadeOut(5000)
			})
		})
})


function _show_data()
{
	show_indicator('ListTable',5)
	$.post('get_daily_absen',{
		'id_lokasi':$('#userlok').val()
	},function(result){
		$('table#ListTable tbody').html(result)
		//$('table#ListTable').fixedHeader({'width':(screen.width-200),'height':(screen.height-325)})
	})
}
