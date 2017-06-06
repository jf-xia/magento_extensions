if (typeof Prototype != 'undefined') { // check that Prototype is loaded
  Event.observe(window, 'load', function(e) {
    var commentsStack = $$('.order-comments dl.order-about');
    if (commentsStack.length != 0) {
      var dl = commentsStack[0];
      dl.addClassName('hide');
      var comments = dl.select('dd');
      comments.each(function(comm) {
        var cont = comm.innerHTML;
        var br = /&lt;br\s*?\/?&gt;/;
        cont = cont.gsub(br, '<br/>');
        var costumer_regex = /\.\.\.customer\s*/;
        if (cont.match(costumer_regex)) {
          cont = cont.replace(costumer_regex, '');
          comm.addClassName('customer-comment').update(cont).insert({top: '<strong>You:</strong><br/>'});
        }else{
          comm.update(cont).insert({top: '<strong>Admin:</strong><br/>'});
        }
      });
      dl.removeClassName('hide');
    }
    var backLink = $$('div.order-details div.buttons-set');
    if (backLink.length) {
      var commentDiv = $('order_comment_block').remove();
      backLink.last().insert({before: commentDiv});
    }
  });
}
