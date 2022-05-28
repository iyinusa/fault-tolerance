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
		$msg = '';
		
		if($_POST['btnRule']){
			$rule = $_POST['rule'];
			$slice = $_POST['slice'];
			$error = $_POST['error'];
			$recovery = $_POST['recovery'];
			
			$chk_rule = mysql_query("SELECT * FROM rule WHERE rule='$rule' LIMIT 1");
			if(mysql_num_rows($chk_rule) > 0){
				$msg = '<div class="alert alert-info">Already exist</div>';
			} else {
				$save = mysql_query("INSERT INTO rule (rule,slice,error,recovery) VALUES ('$rule','$slice','$error','$recovery')");
				if($save){
					$msg = '<div class="alert alert-success">Rule set</div>';
				} else {
					$msg = '<div class="alert alert-error">Error!</div>';
				}
			}
		}
		
		//query
		$query = mysql_query("SELECT * FROM rule");
		if(mysql_num_rows($query) <= 0){
			$dir_list = '';
		} else {
			while($qr = mysql_fetch_assoc($query)){
				$dir_list .= '
					<tr>
						<td>'.$qr['rule'].'</td>
						<td>'.$qr['slice'].'s</td>
						<td>'.$qr['error'].'</td>
						<td>'.$qr['recovery'].'</td>
					</tr>
				';
			}
		}
    ?>
    <div class="row">
    	<div class="col-lg-4 bg-info">
        	<fieldset>
            	<legend>Set Rules <a href="process.php" class="btn btn-default btn-sm">Process >></a></legend>
                <form action="index.php" method="post">
                    Rule:<br /> 
                    <input type="text" name="rule" class="form-control" /><br />
                    Slice (s):<br />
                    <input type="text" name="slice" class="form-control" /><br />
                    Error Terms:<br />
                    <input type="text" name="error" class="form-control" /><br />
                    Recovery Terms:<br />
                    <input type="text" name="recovery" class="form-control" /><br />
                    <input type="submit" name="btnRule" value="Set Process Rule" class="btn btn-success" /><br /><br />
                </form>
            </fieldset>
        </div>
        
        <div class="col-lg-8 bg-warning">
        	<fieldset>
            	<legend>Process Rules</legend>
                <table class="tb">
                	<tr class="top">
                    	<td>Rule</td>
                        <td>Slice</td>
                        <td>Error</td>
                        <td>Recovery</td>
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