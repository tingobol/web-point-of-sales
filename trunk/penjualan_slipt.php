<?
$user=$_GET['userid'];
$file=fopen("c:\\app\\".$user."_slip_putri.txt",'r');
$data=fread($file,1000000);
fclose($file);
echo "<pre>";
echo $data;
echo "</pre>";
unlink("c:\\app\\".$user."_slip_putri.txt");
?>
<script language="javascript">
window.print(true) ;
window.onafterprint=function(){
	window.close();
}
</script>