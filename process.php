<?php
	//start session
	session_start();
	
	$db_host = "localhost";
	$db_username = "root";
	$db_pass = "root";
	$db_name = "tolerancedb";
	
	mysql_connect("$db_host","$db_username","$db_pass") or die(mysql_error());
	mysql_select_db("$db_name") or die("Database Connection Error");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Fault Tolerance Using Forward and Backward Error Recovery</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <style>
		.tb{width:100%;}
		.tb .top{background-color:#eee; font-weight:bold;}
		.tb td{padding:5px; border:1px solid #ddd;}
	</style>
</head>

<body style="width:800px; margin:auto;">
	<header>
    	<h3>Fault Tolerance Using Forward and Backward Error Recovery</h3>
    </header>
    
    <hr />
    
    <?php
		$dir_list = '';
		$rule_list = '';
		$msg = '';
		
		//query rule
		$pull = mysql_query("SELECT * FROM rule");
		if(mysql_num_rows($pull) <= 0){
			$rule_list = 'No Rule Yet';
		} else {
			while($pullr = mysql_fetch_assoc($pull)){
				$rule_list .= '<option value="'.$pullr['id'].'">'.$pullr['rule'].'</option>';	
			}
		}
		
		if($_POST['btnProcess']){
			$process = $_POST['process'];
			$slice = $_POST['slice'];
			
			//get rule details
			$gr = mysql_query("SELECT * FROM rule WHERE id='$process' LIMIT 1");
			if(mysql_num_rows($gr) <= 0){
				
			} else {
				while($grr = mysql_fetch_assoc($gr)){
					$g_slice = $grr['slice'];
					$g_error = $grr['error'];
					$g_recovery = $grr['recovery'];
				}
			}
			
			if($g_slice < $slice){
				$remark = 'BER: '.$g_error.' and FER: '.$g_recovery;
				$mark = 1;
			} else {
				$remark = 'Process completed';
				$mark = 0;
			}
			
			$save = mysql_query("INSERT INTO process (rule_id,slice,mark,msg) VALUES ('$process','$slice','$mark','$remark')");
			if($save){
				$msg = '<div class="alert alert-success">Job Process Completed</div>';
			} else {
				$msg = '<div class="alert alert-error">Error!</div>';
			}
		}
		
		//query
		$query = mysql_query("SELECT * FROM process");
		if(mysql_num_rows($query) <= 0){
			$dir_list = '';
		} else {
			while($qr = mysql_fetch_assoc($query)){
				$rule_id = $qr['rule_id'];
				$gmark = $qr['mark'];
				if($gmark == 1){
					$bg = 'bg-warning';	
				} else {
					$bg = 'bg-success';	
				}
				
				//get rule
				$pr = mysql_query("SELECT * FROM rule WHERE id='$rule_id' LIMIT 1");
				if(mysql_num_rows($pr) <= 0){
					
				} else {
					while($prr = mysql_fetch_assoc($pr)){
						$p_rule = $prr['rule'];
						$p_slice = $prr['slice'];
					}
				}
				
				$dir_list .= '
					<tr class="'.$bg.'">
						<td>'.$p_rule.'</td>
						<td>'.$p_slice.'s Process</td>
						<td>'.$qr['slice'].'s</td>
						<td>'.$qr['msg'].'</td>
					</tr>
				';
			}
		}
    ?>
    <div class="row">
    	<div class="col-lg-4 bg-info">
        	<fieldset>
            	<legend><a href="index.php" class="btn btn-default btn-sm"><< Set Rule</a> Process</legend>
                <form action="process.php" method="post">
                    Select Process:<br /> 
                    <select name="process" class="form-control">
                    	<?php echo $rule_list; ?>
                    </select>
                    <br />
                    Slice (s):<br />
                    <input type="text" name="slice" class="form-control" /><br />
                    <input type="submit" name="btnProcess" value="Process Job" class="btn btn-success" /><br /><br />
                </form>
            </fieldset>
        </div>
        
        <div class="col-lg-8">
        	<fieldset>
            	<legend>Process Block</legend>
                <table class="tb">
                	<tr class="top">
                    	<td>Process</td>
                        <td>Rule</td>
                        <td>Finished</td>
                        <td>Remark</td>
                    </tr>
                    <?php echo $dir_list; ?>
                </table>
                <br /><br />
            </fieldset>
        </div>
    </div>
    
    <hr />
    
    <footer class="text-center">
    	Copyright &copy; 2015
    </footer>
</body>
</html>