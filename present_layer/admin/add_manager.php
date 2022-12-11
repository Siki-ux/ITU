<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // ---> root
    include_once('./bussiness_layer/admin/check_admin.php');
    enforce_admin();

?>

<html>

    <head>
            <link rel="stylesheet" type="text/css" href="../../present_layer/authentication/register.css"/>
        	<script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
			<script type="text/javascript" src="../../bussiness_layer/admin/alter_user.js"></script>
        	<script type="text/javascript" src="./add_user.js"></script>
			<title>Chytni závadu: Pridať užívateľa</title>
    </head>




	<div>
        <nav>
            <h2>
				<a class="back-but ico-hover" id="back-but" href="./user_list.php">
                    <i class="ico fa-2xl fa-solid fa-arrow-left"></i>
				</a>
				<div class="headline">Pridať nového užívateľa</div>
				<br>
            </h2>
        </nav>
    </div>
	<table>
		<tbody>
			<tr>
				<td><label for="email" class="req">Email: *</label></td>
				<td><input type="text" name="email" id="email" value=<?php echo(isset($_SESSION['filled_email'])?$_SESSION['filled_email']:"" );?>></td>
			</tr>
			<tr>
				<td><label for="password" class="req">Heslo: *</label></td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td><label for="role">Rola:</label></td>
				<td><select name="role" id="role">
					<option value=2>Správca mesta</option>
					<option value=3>Technik</option>
					<option value=1>Administrátor</option>
					<option value=0>Obyčajný užívateľ</option>
				</select></td>
			</tr>
			<tr>
				<td><label for="f_name">Krstné meno:</label></td>
				<td><input type="text" name="f_name" id="f_name" value=<?php echo(isset($_SESSION['filled_f_name'])?$_SESSION['filled_f_name']:"" );?>></td>
			</tr>
			<tr>
				<td><label for="l_name">Priezvisko:</label></td>
				<td><input type="text" name="l_name" id="l_name" value=<?php echo(isset($_SESSION['filled_l_name'])?$_SESSION['filled_l_name']:"" );?>></td>
			</tr>
			<tr>
				<td><label for="phone">Telefónne číslo:</label></td>
				<td><input type="tel" name="phone" id="phone" value=<?php echo(isset($_SESSION['filled_phone'])?$_SESSION['filled_phone']:"" );?>></td>
			</tr>
		</tbody>
	</table>
	<br>
	<div class="err-msg hidden" id="err-msg">Chyba</div>
	<br>
	<input type="submit" class="submit" value="Pridať" onclick="submit();"> 

</html>

