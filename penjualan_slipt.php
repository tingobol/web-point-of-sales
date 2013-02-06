<?
$user=$_GET['userid'];
$tmpfname="c:\\app\\".$user."_slip_putri.txt";
$file=fopen("c:\\app\\".$user."_slip_putri.txt",'r');
$data=fread($file,1000000);
fclose($file);
echo "<pre>";
echo $data;
echo "</pre>";
/*
$text = exec("c:\\app\\w3m.exe -dump \"$tmpfname\"");
unlink($tmpfname);

echo "<pre>$text</pre>";
//*/
unlink("c:\\app\\".$user."_slip_putri.txt");
?>
<script language="javascript">
window.print(true).return(false);
window.onafterprint=function(){
	window.close();
}
</script>