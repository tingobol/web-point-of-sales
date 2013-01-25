<?
class Print_slip extends CI_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->model("kasir_model");
		$this->load->library('zetro_auth');
		$this->load->library('zetro_slip');
		$this->load->model("report_model");
		$this->load->helper("print_report");
	}
	function print_slip(){
		$this->zetro_slip->path=$this->session->userdata('userid');
		$this->zetro_slip->modele('wb');
		$this->zetro_slip->newline();
		$this->no_transaksi($_POST['no_transaksi']);
		$this->tanggal(tgltoSql($_POST['tanggal']));
		$this->zetro_slip->content($this->struk_header());
		$this->zetro_slip->create_file();
		//send user id to ajax output
		echo $this->session->userdata('userid');
	}
	function struk_header(){
		$data=array();
		$slip="S L I P  P E N J U A L A N";
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$lokasi	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$gudang=rdb('user_lokasi','lokasi','lokasi',"where id='".$lokasi."'");
		$coy	=$this->zetro_manager->rContent($gudang,'Name',$nfile);	
		$address=$this->zetro_manager->rContent($gudang,'Address',$nfile);
		$city	=$this->zetro_manager->rContent($gudang,'Kota',$nfile);
		$phone	=$this->zetro_manager->rContent($gudang,'Telp',$nfile);
		$fax	=$this->zetro_manager->rContent($gudang,'Fax',$nfile);
		$tgl	=rdb('inv_penjualan','Tanggal','Tanggal',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$Jenis	=rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$nJenis	=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".$Jenis."'");
		$no_faktur=rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$isine	=array(
					$slip.newline(),
					sepasi(80).newline(),
					$coy.sepasi((79-strlen($coy)-strlen('Tanggal :'.tglfromSql($tgl)))).'Tanggal :'.tglfromSql($tgl).newline(),
					$address.sepasi((79-strlen($address)-strlen('No. Faktur :'.$no_faktur))).'No. Faktur :'.$no_faktur.newline(),
					$phone.sepasi((79-strlen($phone)-strlen('Pembelian :'.$nJenis)-(10-strlen($nJenis)))).'Pembelian :'.$nJenis.newline(),
					str_repeat('-',79).newline(),
					'| No.|'.sepasi(((32-strlen('Nama Barang'))/2)).'Nama Barang'.sepasi(((32-strlen('Nama Barang'))/2)).
					'|'.sepasi(((10-strlen('Banyaknya'))/2)).'Banyaknya'.sepasi((((10-strlen('Banyaknya'))/2)-1)).'|'.
					sepasi(((14-strlen('Harga Satuan'))/2)).'Harga Satuan'.sepasi((((14-strlen('Harga Satuan'))/2)-1)).'|'.
					sepasi(((18-strlen('Total Harga'))/2)).'Total Harga'.sepasi((((17-strlen('Total Harga'))/2)-1)).'|'.newline(),
					str_repeat('-',79).newline(),
					$this->isi_slip(),
					($Jenis==1)?$this->struk_data_footer():$this->struk_data_footer_kredit()
					);
		return $isine;			
	}
	function isi_slip(){
		$data=array();$content="";$n=0;
		$this->inv_model->tabel('inv_penjualan_rekap');
		$data=$this->kasir_model->get_trans_jual($this->no_trans,$this->tgl);
		 foreach($data as $row){
			 $n++;
			 $satuan=rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
			 		 rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$row->ID_Barang."'")."'");
			$nama_barang=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->ID_Barang."'");
			$kategori=rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".
					 rdb('inv_barang','ID_Kategori','ID_Kategori',"where ID='".$row->ID_Barang."'")."'");
			$content .=sepasi(((6-strlen($n))/2)).$n.sepasi(3).substr($kategori,0,10).sepasi().substr(ucwords(($nama_barang)),0,31).
					 sepasi(((30-strlen($kategori))-strlen($nama_barang))).
					 sepasi((10-strlen($row->Jumlah)-strlen($satuan)-2)).round($row->Jumlah,0).sepasi(1).$satuan.
					 sepasi((14-strlen(number_format($row->Harga)))).number_format($row->Harga).
					 sepasi((16-strlen(number_format(($row->Jumlah *$row->Harga),2)))).number_format(($row->Jumlah *$row->Harga),2).newline();
		 }
		 if($n<8){
			 $content .=newline((8-$n));
		 }
		 return $content;
		 
	}
	function struk_data_footer(){
		$data=array();$bawah="";
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','NoUrut','NoUrut',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_departemen','Departemen','Departemen',"where ID='".
				rdb('mst_anggota','Nama','Nama',"where ID='".
				rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'")."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',79).newline().
				sepasi((61-strlen('Sub Total'))).'Sub Total :'.sepasi((14-strlen(number_format($row->total_belanja,2)))).number_format($row->total_belanja,2).newline(2).
				sepasi((61-strlen('Diskon'))).'Diskon :'.sepasi((14-strlen(number_format($row->ppn,2)))).number_format($row->ppn,2).newline().
				sepasi(61).str_repeat('-',18).'+'.newline().
				sepasi((61-strlen('Total'))).'Total :'.sepasi((14-strlen(number_format($row->total_bayar,2)))).number_format($row->total_bayar,2).newline(2).
				sepasi((61-strlen('Cash'))).'Cash :'.sepasi((14-strlen(number_format($row->jml_dibayar,2)))).number_format($row->jml_dibayar,2).newline(2).
				sepasi(61).str_repeat('-',17).'-'.newline().
				sepasi((61-strlen('Kembali'))).'Kembali :'.sepasi((14-strlen(number_format($row->kembalian,2)))).number_format($row->kembalian,2).newline(2).
				str_repeat('-',79).newline().'Terima Kasih.   doc :'.$this->no_trans.' '.date('d-m-Y H:i').newline();
				
/*				'No. Anggota'.sepasi((14-strlen('No.Anggota'))).':'.$urut.newline().
				'Nama Anggota'.sepasi((14-strlen('Nama Anggota'))).':'.$nama.newline().
				'Departemen'.sepasi((16-strlen('Departemen'))).':'.$alm.newline(2);
*/			}
		return $bawah;
	}
	function struk_data_footer_kredit(){
		$data=array();$bawah="";
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','No_Agt','No_Agt',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_anggota','Catatan','Catatan',"where ID='".
			  rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$ncby=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$bank=rdb('inv_penjualan','Deskripsi','Deskripsi',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$rek =rdb('inv_penjualan','ID_Post','ID_Post',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$tgir=rdb('inv_penjualan','Tgl_Cicilan','Tgl_Cicilan',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',79).newline().
				sepasi((61-strlen('Sub Total'))).'Sub Total :'.sepasi((14-strlen(number_format($row->total_belanja,2)))).number_format($row->total_belanja,2).newline(2).
				sepasi((61-strlen('Diskon'))).'Diskon :'.sepasi((14-strlen(number_format($row->ppn,2)))).number_format($row->ppn,2).newline().
				sepasi(61).str_repeat('-',18).'+'.newline().
				sepasi((61-strlen('Total'))).'Total :'.sepasi((14-strlen(number_format($row->total_bayar,2)))).number_format($row->total_bayar,2).newline(2).
				sepasi((61-strlen('Uang Muka'))).'Uang Muka :'.sepasi((14-strlen(number_format($row->jml_dibayar,2)))).number_format($row->jml_dibayar,2).newline(2).
				sepasi(61).str_repeat('-',17).'-'.newline().
				sepasi((61-strlen('Sisa'))).'Sisa :'.sepasi((14-strlen(number_format($row->kembalian,2)))).number_format($row->kembalian,2).newline(2).
				str_repeat('-',79).newline().
				'Pembayaran '.sepasi((15-strlen('Pembayaran :'))).':'.$ncby.' No.:'.$rek.' '.$bank .' [ '.tglfromSql($tgir).' ]'.newline().
				'Nama Konsumen'.sepasi((15-strlen('Nama Konsumen :'))).' :'.$nama.'[ '.$urut.' ]'.newline().
				'Alamat'.sepasi((15-strlen('Alamat :'))).' :'.$alm.newline().
				'Terima Kasih.   doc :'.$this->no_trans.' '.date('d-m-Y H:i').newline();
			}
		return $bawah;
	}
	function re_print(){
		system("print ".$this->session->userdata('userid')."_slip.txt");
		system("close");
		$tmpdir=sys_get_temp_dir();
		
		//copy($file, "//localhost/xprinter");  # Lakukan cetak
	}
//end of Print_slip class
}?>