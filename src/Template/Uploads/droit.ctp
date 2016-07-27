</table>
<H2>Droit</H2>
<hr/>
<table>
	<tr>
		<th>Droit pour l'user</th>
		<th>Folder</th>
		<th>File</th>
		<th>Size</th>
		<th>Created</th>
		<th>Action</th>
	</tr>
	<?php foreach ($uploads as $query): ?>
		<tr>
			<td><?= $query->users['username'] ?></td>
				<td><?php 
					if ($query->rule != 0)
						echo $query->folder; 
					?></td>
			<td><?php 
				if ($query->rule != 0)
					echo $query->file; 
				?></td>
					
					<td><?= $this->Number->toReadableSize($query->size) ?></td>
					<td><?= $query->created ?></td>
					<td><?= $this->Form->postLink(
						' | Supprimer droit',
						['action' => 'deletedroit', $query->id],
						['confirm' => 'Etes-vous sûr?']); ?>

					</td>
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