<?php
		  //$xx=new Kasir_Model;
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("LIST PENJUALAN BARANG");
		  $a->setSection("detailbeli");
		  $a->setFilter(array($dari ." s/d ".$sampai,$lokasi));
		  $a->setReferer(array('Periode','Lokasi'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage(($detail=='')?"P":"P","A4");
	
		  $a->SetFont('Arial','',9);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  $a->SetWidths(array(10,18,22,50,25,18,25,25,30));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(array("R","C","C","L","R","L","R","R","R"));
		  $a->SetFont('Arial','B',9);
		  $a->SetFont('Arial','',8);
		  $dataz=array();$datax=array();
		  $n=0;$harga=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;$nn=0;$hgb=0;
			$a->SetFont('Arial','B',8);
			$a->SetFillColor(210,210,010);	
			$a->Cell(10,7,$n,1,0,'C',true);
			$a->Cell(90,7,($r->Nama!='')?$r->Nama:'PELANGGAN TUNAI',1,0,'L',true);
			$a->Cell(93,7,$r->Catatan." ".$r->Alamat." ".$r->Kota,1,1,"L",true);
			$a->SetFont('Arial','',8);
			$ID_P=" and p.ID_Anggota='".$r->ID_Anggota."' group by dt.ID_Jual";
			$orderby.=",p.Tanggal";
			//$a->Cell(193,8,$where.' '.$ID_P.' '.$orderby,1,1,'L',true);
			$dataz=$this->kasir_model->detail_trans_jual($where,$ID_P,$orderby);
				foreach($dataz as $r2){
					$nn++;$x=0;
					$a->SetFont('Arial','I',8);
					$a->SetFillColor(247,240,213);	
					$a->Cell(10,7,$nn,1,0,'R',true);
					$a->cell(18,7,tglfromSql($r2->Tanggal),1,0,'C',true);
					$a->cell(22,7,$r2->Nomor,1,0,'C',true);
					$a->Cell(75,7,($r2->ID_Jenis=='1' || $r2->ID_Jenis=='5')?$r2->Jenis_Jual:$r2->Jenis_Jual." : ".$r2->ID_Post." - ".$r2->Deskripsi,1,0,"L",true);
					$a->Cell(68,7,($r2->ID_Jenis=='1' || $r2->ID_Jenis=='5')?"":"Jatuh Tempo  Tanggal : ".tglfromSql($r2->Tgl_Cicilan),1,1,'L',true);
					
					$a->SetFont('Arial','',8);

					$Grp=" and p.ID_Anggota='".$r2->ID_Anggota."' and dt.ID_Jual='".$r2->ID_Jual."'";
					$dataz=$this->kasir_model->detail_trans_jual($where,$Grp,$orderby);
						foreach($dataz as $rr2){
							$x++;
							$a->Row(array('',$x,$rr2->Kode,
										  ucwords(substr($rr2->Nama_Barang,0,25)),
										  number_format($rr2->Jumlah,2),
										  $r2->Satuan,
										  number_format(($rr2->Harga),2),
										  number_format(($rr2->Harga*$rr2->Jumlah),2)
										  )) ;
						$hgb= ($r2->ID_Jenis=='5')?($hgb-($rr2->Harga*$rr2->Jumlah)):($hgb+($rr2->Harga*$rr2->Jumlah));
						}
				}
			//sub tlot
			$hargaj=rdb('inv_pembayaran','ppn','sum(ppn) as ppn',"where no_transaksi='".$r->NoUrut."' and year(doc_date)='".$r->Tahun."'");
			$harga =($harga+($hgb-$hargaj));
			if ($hargaj!=0){
			  $a->SetFont('Arial','I',9);
			  $a->SetFillColor(242,239,219);
			  $a->Cell(143,7,"Potongan",1,0,'R',true);
			  $a->Cell(50,7,number_format($hargaj,2),1,1,'R',true);
			}
			  $a->SetFont('Arial','B',9);
			  $a->SetFillColor(242,239,219);
			  $a->Cell(143,7,"Sub Total",1,0,'R',true);
			  $a->Cell(50,7,number_format(($hgb-$hargaj),2),1,1,'R',true);
			  //grand total
			}
			
		  $a->SetFont('Arial','B',9);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(($detail=='')?143:143,8,"GRAND TOTAL",1,0,'R',true);
		  $a->Cell(($detail=='')?50:50,8,number_format($harga,2),1,0,'R',true);
/*		  ($detail!='')?'':
		  $a->Cell(40,8,'',1,0,'R',true);
*/		  $a->Output('application/logs/'.$this->session->userdata('userid').'_detail_penjualan.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_detail_penjualan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1" style="z-index:100"></iframe>
<?
panel_end();

?>