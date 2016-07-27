<?php 

echo $this->Form->create($file);
echo $this->Form->input('file');
$rule = ['0' => 'Privé', '1' => 'Limité', '2' => 'Public'];
?>
<a href="#" id="move">Déplacer fichier</a><br/><br/>
<a href="#" id="move_droit">Modifier droit</a><br/><br/>
<?php
echo $this->Form->select('folder', $tbl, ['id' => "folder",'default' => $file->folder]);
echo $this->Form->select('rule', $rule, ['id' => "rule"]);
echo $this->Form->input('mail1', ['class' => "mail1" ,'type' => 'email']);
?>
<div class="input text" id="div"></div>
<a href="#" id="plus">Plus</a><br/><br/>
<?php
echo $this->Form->button(__("Sauvegarder"));
echo $this->Form->end();
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
	$('#folder').hide();
	$('#move').click(function(event) {
		$('#folder').toggle();
	});
	$('#rule').hide();
	$('#move_droit').click(function(event) {
		$('#rule').toggle();
	});
	$('.mail1').hide();
	$('#plus').hide();
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
		$('#div').append('<label for="mail1'+toto+'"></label>Mail<input type="email" class="mail2" id="mail'+toto+'" type="text" name="mail'+toto+'" autocomplete="off">')
		console.log(toto);
		// $('#fo').toggle();
	});

</script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.mail1').on("focus", function(){
			console.log(this);
			$(this).autocomplete({
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
	});

</script>