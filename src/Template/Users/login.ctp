<?php 
echo $this->Flash->render('auth');
echo $this->Form->create('users');
echo $this->Form->input('username');
echo $this->Form->input('password',array('type' => 'password'));
echo $this->Form->button('Finish', ['type' => 'submit']);
echo $this->Form->end();



?>