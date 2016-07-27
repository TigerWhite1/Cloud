<?= $this->Html->link('Accueil', ['controller' => 'admin', 'action' => 'index'])."<br/>" ?>
<?= $this->Html->link('Files', ['controller' => 'admin', 'action' => 'files'])."<br/>" ?>

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
		<th>Actif</th>
		<th>Rule</th>
		<th>Action</th>
	</tr>
	<?php foreach ($query as $query): ?>
		<tr>
			<td><?= $query->id ?></td>
			<td><?= $this->Form->postLink($query->username, ['action' => 'user', $query->username]) ?></td>
			<td><?= $query->name ?></td>
			<td><?= $query->lastname ?></td>
			<td><?= $query->email ?></td>
			<td><?= $query->created ?></td>
			<td><?= $query->actif ?></td>
			<td><?= $query->role ?></td>
			<td><?php 
				$name = 'Débloquer';
				$nbr = 1;
				if ($query->actif === 1) {
					$name = 'Bloquer';
					$nbr = 0;
				}
				echo $this->Form->postLink($name, ['action' => 'users', $query->id, $nbr])."<br/>";
				echo $this->Form->postLink('Admin', ['action' => 'rule', $query->id, 1]);
				echo $this->Form->postLink('- Users', ['action' => 'rule', $query->id, 2]);

					?></td>
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