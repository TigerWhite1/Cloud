<?php 
use Cake\Utility\Text;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

?>
<link rel="stylesheet" href="webroot/css/popup.css">
<h1>Files</h1>
<br/>
<table>
	<tr>
		<th>Folder</th>
		<th>Name</th>
		<th>Size</th>
		<th>Acion</th>
	</tr>
	<?php foreach ($uploads as $key => $value): ?>
		<?php ?>
		<tr>
			<td><?= $value->folder ?></td>
			<td><?php 
				$path = explode('/', $value->folder);
				unset($path[0]);
				$string = implode('/', $path);
				$stringf = '/'.$string;
				
				if (empty($path)) {	
					if ($value->mime == 'image/jpeg' || $value->mime == 'image/jpg' || $value->mime == 'image/png') {

						echo "<a href=\"http://localhost/Cloud/webroot/cloud/$value->id_user$string/$value->file\" title=\"add a caption to title attribute / or leave blank\" class=\"thickbox\">$value->file</a>"; 
					} elseif($value->mime == 'audio/mpeg' || $value->mime == 'audio/mp3') {
						debug('tototo');	
						echo "<audio controls=\"controls\">
						Votre navigateur ne supporte pas l'élément <code>audio</code>.
						<source src=\"http://localhost/Cloud/webroot/cloud/$value->id_user$string/$value->file\" type=\"audio/wav\">
						</audio>";

					} elseif ($value->mime == 'video/mp4') {
						echo "<a class=\"popup-player\" href=\"http://localhost/Cloud/webroot/cloud/$value->id_user$string/$value->file\">$value->file</a>";

					}

				} elseif (!empty($path)) {
					if ($value->mime == 'image/jpeg' || $value->mime == 'image/jpg' || $value->mime == 'image/png') {

						echo "<a href=\"http://localhost/Cloud/webroot/cloud/$value->id_user/$string/$value->file\" title=\"add a caption to title attribute / or leave blank\" class=\"thickbox\">$value->file</a>";
					} elseif($value->mime == 'audio/mpeg' || $value->mime == 'audio/mp3') {
						echo "<audio controls=\"controls\">
						Votre navigateur ne supporte pas l'élément <code>audio</code>.
						<source src=\"http://localhost/Cloud/webroot/cloud/$value->id_user/$string/$value->file\" type=\"audio/wav\">
						</audio>";

					} elseif ($value->mime == 'video/mp4') {
						echo "<a class=\"popup-player\" href=\"http://localhost/Cloud/webroot/cloud/$value->id_user/$string/$value->file\">$value->file</a>";

					}
				}


				?></td>
				<td><?= $this->Number->toReadableSize($value->size) ?></td>
				<?php if (!empty($value->file)) { ?>
				<td>
					<?php 
					if (empty($path))
						echo "<a href=\"http://localhost/Cloud/webroot/cloud/$value->id_user$string/$value->file\"
					download=\"$value->file\">Télécharger</a>";
					else 
						echo "<a href=\"http://localhost/Cloud/webroot/cloud/$value->id_user/$string/$value->file\"
					download=\"$value->file\">Télécharger</a>";

					?>

				</td>

				<?php } ?>

			</tr>
			<?php  ?>
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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

