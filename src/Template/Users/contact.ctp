<h1>Contact</h1>
<?php 
echo $this->Form->create('email');
echo $this->Form->input('Demande');
echo $this->Form->input('Message', ['rows' => '3']);
echo $this->Form->button(__("Envoyer votre message"));
echo $this->Form->end();


?>