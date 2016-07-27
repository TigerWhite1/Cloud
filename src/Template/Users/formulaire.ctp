<?php 
echo $this->Flash->render('auth');
echo $this->Form->create('users');
echo $this->Form->input('username');
echo $this->Form->input('password',array('type' => 'password'));
echo $this->Form->input('name');
echo $this->Form->input('lastname');
echo $this->Form->input('birthdate',array('type' => 'date'));
echo $this->Form->input('email',array('type' => 'email'));
if (!empty($parrainage))
	echo $this->Form->input('parrainage', ['value' => $parrainage]);
echo $this->Form->button('Finish', ['type' => 'submit']);
echo $this->Form->end();

?>