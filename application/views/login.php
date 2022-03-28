<?php
?>
<!DOCTYPE hmtl>
	<html>
 	   <head>
       </head>
		<body>
			<form method="post" action="<?php echo base_url()?>task_app/login">
				<center>
					<fieldset style="position:relative;top:-0px;left:0px;width:200px;">
							<div style="color:green;float:center;"><?php  echo $this->session->flashdata('register') ?></div>
					<h3>LOGIN</h3>									
						<table cellspacing='15'  width = '0px'>
							<tr>
								<td>Phone</td>
								<td> <input type="tel" name="phone" placeholder="phone number"></td>
							</tr>
							<tr>
								<td>Password</td>
								<td> <input type="password" name="password"></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="submit"></td>
						    </tr>
						</table>
						</fieldset>
				</center>
			</form>
		</body>
</html>