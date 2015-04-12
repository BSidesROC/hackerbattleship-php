$(document).ready(function() {

  // tweak 'hits'
  $('.hit').html('&nbsp;&nbsp;');
  $('.hit').css('border', 'none');
  $('.hit').css('background-color', 'inherit');

  // tweak 'misses'
  $('.miss').html('&nbsp;&nbsp;');
  $('.miss').css('border', 'none');
  $('.miss').css('background-color', 'inherit');

  document.addEventListener("customerFrustrated", function(e) {
    alert('Y U MAD? ;-)');
  }, false);

  $('.grid_space').on('click', function() {
    $(location).attr('href', '/?mth=sub&chal='+$(this).data('chal'));
  });
});
