// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
    	$('#frm2 input#nm_barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=20&str=',
				width:370,
				showDescription	:true,
				onSelected:function(rst){
					$.post(path+'inventory/detail_material',{'ID':rst.data},
						function(r){
							var result=$.parseJSON(r);
							$('#frm2 #sn').val(result.sn)
							$('#frm2 #hpp').val(to_number(result.hpp))
							$('#frm2 #harga_toko').val(to_number(result.harga_toko));
							$('#frm2 #harga_partai').val(to_number(result.harga_partai))
							$('#frm2 #harga_cabang').val(to_number(result.harga_cabang));
							$('#frm2 #mata_uang').val(result.mata_uang).select();
							$('#frm2 #garansi').val(result.garansi);
							$('#frm2 #panjang').val(result.ukuran);
							$('#frm2 #warna').val(result.warna);
							$('#frm2 #berat').val(result.berat);
						})
				}
		})
		$('#frm2 #hpp')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#harga_toko').focus().select()})
			.keypress(function(e){if(e.which==13){ $(this).focusout();}})
		$('#frm2 #harga_toko')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#harga_partai').focus().select()})
			.keypress(function(e){if(e.which==13){ $(this).focusout();}})
		$('#frm2 #harga_partai')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#harga_cabang').focus().select()})
			.keypress(function(e){if(e.which==13){ $(this).focusout();}})
		$('#frm2 #harga_cabang')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#frm2 #garansi').focus().select()})
			.keypress(function(e){if(e.which==13){ $(this).focusout();}})
});