<?php echo $this->load->view('_head_language_editor'); ?>
<div id="content">
<div id="content">
	<div class="inside">
		<div class="container_12">
			<h2>Please login as translator</h2>
			<form action="<?php echo getRelativePath('/language_editor/login');?>" method="post">
				<table>
					<tr>
						<td>User:</td>
						<td><input type="text" name="username" value="enter an username" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password"  /></td>
					</tr>
				</table>
				<button type="submit">login as translator</button>
			</form>
		</div>
	</div>
</div>
<?php echo $this->load->view('_foot_language_editor'); ?>