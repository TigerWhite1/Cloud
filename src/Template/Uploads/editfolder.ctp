<?php
$path = explode('/', $file->folder);
$path = end($path);
$path= array('folder' => $path);
echo $this->Form->create($file);
echo $this->Form->create($file,['url' => ['action' => 'newfolder']]);
$rule = ['0' => 'Privé', '1' => 'Limité', '2' => 'Public'];
echo $this->Form->select('choix', $tbl);
$test = $this->Form->select('rule', $rule, ['id' => "rule"]);
echo $test;
echo $this->Form->input('mail1', ['class' => "mail1" ,'type' => 'email']);
?>
<div class="input text" id="div"></div>
<a href="#" id="plus">Plus</a>
<?php
echo $this->Form->input('folder', ['value' => $path]);
echo $this->Form->button(__("Sauvegarder"));
echo $this->Form->end();

?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
	$('.mail1').hide();
	$('#plus').hide();
	$('#newfolder').hide();
	$( "#rule" )
	.change(function () {
		var str = "";
		$( "#rule option:selected" ).each(function() {
			str += $( this ).text();
		});
		if (str == 'Limité') {
			$('.mail1').show();
			$('#plus').show();
		}
		else if(str != 'Limité') {
			$('.mail1').hide();
		}
	})
	var toto = 1;
	$('#plus').click(function(event) {
		toto++;
		$('#div').append('<label for="mail'+toto+'"></label>Mail<input type="email" class="mail ui-autocomplete-input" id="mail" type="text" name="mail'+toto+'">')
		console.log(toto);
		// $('#fo').toggle();
	});
	$('#newsFolder').click(function(event) {
		$('#newfolder').toggle();
	});

</script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.mail1').autocomplete({
			source : 'http://localhost/Cloud/uploads/automail' ,
			open : function(event, ui) {
			},
			messages: {
				noResults: '',
				results: function() {}
			},
			utoFocus:true,
			minLength : 2,
			maxRows : 5,
		});
	});

</script>