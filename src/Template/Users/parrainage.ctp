<h1>Parrainage</h1><br/>
	<p>Vous pouvez parrainer vos amis pour obtenir un bonus de 50% sur le stockage total et l'uploades par fichier, ainsi que vos amis.</p><br/>
<?php 

echo $this->Form->create('mail');
echo $this->Form->input('mail1', ['class' => "mail1", 'type' => 'email']);
?>
<div class="input text" id="div"></div>
<a href="#" id="plus">Plus</a><br/><br/>
<?php
echo $this->Form->button(__("Envoyer"));
echo $this->Form->end();
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
	// $('.mail1').hide();
	// $('#plus').hide();

	var toto = 1;
	$('#plus').click(function(event) {
		toto++;
		$('#div').append('<label for="mail1'+toto+'"></label>Mail<input type="email" class="mail2" id="mail'+toto+'" type="text" name="mail'+toto+'" autocomplete="off">')
		console.log(toto);
	});

</script>