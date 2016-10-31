function validateCheckbox(...formName) {
	for (var i = 0; i < formName.length; i++) {
		var temp = $("[name='" + formName[i] + "[]']");

		for (var j = 0; j < temp.length; j++) {
			if (temp[j].checked) {
				return true;
			}
		}

	}

	alert("Please check at least one box.");
	return false;
	
};

function requireOne(...formName) {
  var counter = 0;
  for (var i = 0; i < formName.length; i++) {
    var temp = $("[name='" + formName[i] + "[]']");

    for (var j = 0; j < temp.length; j++) {
      if (temp[j].checked) {
        counter++;
      }
    };

  };

  if (counter!=1){
    alert("Please select one box.");
    return false;
  }
  else 
    return true;
  
};
/*
var options = [];

$( '.dropdown-menu a' ).on( 'click', function( event ) {

   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
      
   console.log( options );
   return false;
});
*/


$(document).ready(function() {
	$('.dropdown-menu li').click(function(event){
    	// The event won't be propagated up to the document NODE and 
    	// therefore delegated events won't be fired
    	event.stopPropagation();
   }); 
});
