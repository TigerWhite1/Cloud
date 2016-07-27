<?= $this->Html->link('Users', ['controller' => 'admin', 'action' => 'users'])."<br/>" ?>
<?= $this->Html->link('Files', ['controller' => 'admin', 'action' => 'files'])."<br/>" ?>


<h1>Admin</h1>
<H3>Users</H3>
<hr/>
<table>
	<tr>
		<th>Id</th>
		<th>Username</th>
		<th>Name</th>
		<th>Lastname</th>
		<th>Email</th>
		<th>Created</th>
	</tr>
	<?php foreach ($query as $query): ?>
		<tr>
			<td><?= $query->id ?></td>
			<td><?= $query->username ?></td>
			<td><?= $query->name ?></td>
			<td><?= $query->lastname ?></td>
			<td><?= $query->email ?></td>
			<td><?= $query->created ?></td>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<H3>Dernier uploads</H3>
<hr/>
<table>
	<tr>
		<th>Id</th>
		<th>User id</th>
		<th>File</th>
		<th>Size</th>
		<th>Created</th>
		<th>Updated</th>
	</tr>
	<?php foreach ($billets as $query): ?>
		<?php if (!empty($query->file)) { ?>
		<tr>
			<td><?= $query->id ?></td>
			<td><?= $query->id_user ?></td>
			<td><?php 
				if ($query->rule == 0)
					echo $query->file; 
				?></td>
				<td><?= $this->Number->toReadableSize($query->size) ?></td>
				<td><?= $query->created ?></td>
				<td><?= $query->updated ?></td>
			</td>
		</tr>
		<?php } ?>
	<?php endforeach; ?> 
</table>

