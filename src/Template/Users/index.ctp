<?php 
// debug($this->Auth->user('role'));
if ($role === 1) {
	echo $this->Html->link('Admin', ['controller' => 'admin', 'action' => 'index'])."<br/>";
}

?>
<?= $this->Html->link('Uploads', ['controller' => 'Uploads', 'action' => 'uploads'])."<br/>" ?>
<?= $this->Html->link('Parrainage', ['controller' => 'users', 'action' => 'parrainage'])."<br/>" ?>
<?= $this->Html->link('Contact', ['controller' => 'users', 'action' => 'contact'])."<br/>" ?>