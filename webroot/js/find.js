$(function () {

  var $folders = $('.folders'),
      $files = $('.files'),
      $filesList = $('.files__list');
  
  $folders.on('click', 'a', function () {
    // Hide the rocket
    $('.placeholder').removeClass('active');
    // Reveal the actions toolbar
    $('.files__actions').addClass('active');
    // Reveal the list of files
    $filesList.addClass('active');
    // Hightlight only the clicked folder
    $('a', $folders).removeClass('active');
    $(this).addClass('active');

    return false;
  });
  
  $files.on('click', '.files__list a', function () {
    // Hide thumbnails and settings toolbar for
    // previously clicked files
    $('.settings', $filesList).removeClass('active');
    $('li', $filesList).removeClass('active');
    // Reveal thumbnail and settings toolbar
    $(this)
      .closest('li')
      .addClass('active')
      .find('.settings')
      .addClass('active');
    
    return false;
  });
  
  $('.show-thumbnails').on('click', function () {
    var $this = $(this),
        $li = $('li', $filesList);
    
    if ($this.hasClass('active')) {
      // Hide thumbnails
      $li.removeClass('active');
      $this.removeClass('active');
    } else {
      // Show thumbnails
      $li.addClass('active');
      $this.addClass('active');
    }
    
    return false;
  });

});

try {
  Typekit.load({
     loading: function() {
       // Javascript to execute when fonts start loading
     },
     active: function() {
       // Javascript to execute when fonts become active
       $('.container').addClass('tk-loaded');
     },
     inactive: function() {
       // Javascript to execute when fonts become inactive
       $('.container').addClass('tk-loaded');
     }
   });
} catch (e) {
  // Do something
}