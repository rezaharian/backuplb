
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
     <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
     

 </head>
 <body>
     <div class="input_fields_wrap">
         <button class="add_field_button">Add More Fields</button><br>
         <div><input type="text" class="autoc" name="mytext[]" id="choice0"><input type="text" class="autoc" name="mynext[]" id="prid0"></div>
     </div>
 
 <script>
 $(document).ready(function() {
  var max_fields = 10; //maximum input boxes allowed
  var wrapper = $(".input_fields_wrap"); //Fields wrapper
  var add_button = $(".add_field_button"); //Add button ID

  var x = 1; //initlal text box count
  var availableAttributes = [{
      "label": "zia.khan@abc.net",
      "value": "Business Planning & Supply Chain"
    },
    {
      "label": "mosharraf.hossain@abc.net",
      "value": "Marketing"
    },
    {
      "label": "razib.mahmud@abc.net",
      "value": "Sales"
    },
    {
      "label": "sadek.mohtadin@abc.net",
      "value": "Technology"
    }
  ];


  $(add_button).click(function(e) { //on add input button click
    e.preventDefault();
    if (x < max_fields) { //max input box allowed
      x++; //text box increment
      $(wrapper).append('<div class="added"><input type="text" name="mytext[]" id="choice' + (x - 1) + '"><input type="text" name="mynext[]" id="prid' + (x - 1) + '"><a href="#" class="remove_field">Remove</a></div>');
      
      $('.added input').autocomplete({
        source: availableAttributes,
        select: function(event, ui) {
          event.preventDefault();
          $('#choice' + (x - 1)).val(ui.item.label);
          $('#prid' + (x - 1)).val(ui.item.value);
        }
      });
      //add input box
    }
  });

  $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
    e.preventDefault();
    $(this).parent('div').remove();
    x--;
  })

  $(".autoc:first-child").autocomplete({
    source: availableAttributes,
    select: function(event, ui) {
      event.preventDefault();
      $('#choice' + (x - 1)).val(ui.item.label);
      $('#prid' + (x - 1)).val(ui.item.value);
    }
  });

});
 </script>
 
 </body>
 </html>