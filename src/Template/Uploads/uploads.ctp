<!-- <H1>Uploads</H1> -->
<?php echo $this->Html->css('/webroot/css/upload_style.css') ?>
<link href='http://fonts.googleapis.com/css?family=Boogaloo' rel='stylesheet' type='text/css'>
<div class="row-fluid">
	<center><h1 class="title">Uploads</h1></center>
	<select class="selectpicker" id="select">
		<?php 
		foreach ($tbl as $key => $value) {
			echo "<option value='$value'>".$value.'</option>';
		}

		?>
	</select>
	<input type="text" id='email' name="mail1">
	<div class="input text" id="div1"></div>
	<a href="#" id="plus1">Plus</a>

	<div class="upload-status"></div>
	<div id="dragAndDropFiles" class="uploadArea">
		<h1>Drop Images Here</h1>
	</div>
	<form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
		<input type="file" name="multiUpload" id="multiUpload" multiple />
		<input type="submit" name="submitHandler" id="submitHandler" value="Upload" class="buttonUpload" />
	</form>
	<div class="progressBar">
		<div class="status"></div>
	</div>

</div>
<br/>
<br/>
<a href="#" id="newsFolder">Nouveaux dossier</a>
<div id="newfolder">
	<?php
	echo $this->Form->create('folder',['url' => ['action' => 'newfolder']]);
	echo $this->Form->select('choix', $tbl);

	echo $this->Form->input('folder');

	echo $this->Form->button(__("Sauvegarder"));
	echo $this->Form->end();
	?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<?php echo $this->Html->script('/webroot/js/multiupload') ?>
<script type="text/javascript">
	var config = {
		baseUrl: '<?php echo $this->params->webroot ?>drag_drop_upload/',
	support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif,application/pdf,text/plain,application/msword,video/mp4,audio/mpeg,application/zip",		// Valid file formats
	form: "demoFiler",					// Form ID
	dragArea: "dragAndDropFiles",		// Upload Area ID
	uploadUrl: "<?php echo $this->params->webroot ?>uploads" // Server side upload url
}
$(document).ready(function(){
	initMultiUploader(config);
});
</script>
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
		$('#div').append('<label for="mail'+toto+'"></label>Mail<input class="mail ui-autocomplete-input" id="mail" type="text" name="mail'+toto+'">')
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