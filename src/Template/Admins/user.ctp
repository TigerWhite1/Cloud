<h1>user</h1>
<?= $this->Html->link('Accueil', ['controller' => 'admin', 'action' => 'index'])."<br/>" ?>
<?= $this->Html->link('Users', ['controller' => 'admin', 'action' => 'users'])."<br/>" ?>
</table>
<H3>Billets</H3>
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
	<?php foreach ($user->toArray() as $query): ?>
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
	<?php endforeach; ?> 
</table>
<div class="pagination pagination-large">
	<ul class="pagination">
		<?= $this->Paginator->prev('« Previous') ?>
		<?= $this->Paginator->numbers() ?>

		<?= $this->Paginator->next('Next »') ?>

		<?= $this->Paginator->counter() ?>
	</ul>
</div>