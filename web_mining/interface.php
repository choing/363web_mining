


<html>
	<head>
  <meta charset="utf-8">
  <title>interface</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--<link rel="stylesheet" href="/resources/demos/style.css"> -->
  <script>
  $(function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });
  </script>
</head>

<body>
    <img src="search1.jpg"/>
    <form action="interface2.php" method="post">
	    <div class="ui-widget">
			 <input id="tags" name="search" size="40" type="text" placeholder="Type your search here">
			<input type="submit" name="go" value="Search" class="btn" style="width:150;height:30">
			
<br><br>

		</div>

<?php

    echo " <span class='label label-default'>From:</span>";
    $i = 0;

    print "<form action=\"interface2.php\" method=\"post\">

    <select name= \"day\" style=\"width:40;height:30\">";

    for($i = 1; $i<=31; $i++)
    {
	    print"<option value=\"$i\">$i</option>";

    }
    print"</select>";

    print"<select name= \"month\" style=\"width:40;height:30\">";

    for($i = 1; $i<=12; $i++)
    {
	    print"<option value=\"$i\">$i</option>";

    }
    print"</select>";

    print"<select name= \"year\" style=\"width:53;height:30\">";
    for($i = 2014; $i<=2014; $i++)
    {
	    print"<option value=\"$i\">$i</option>";
    }
    print"</select>";
    echo "To:";
    print"<select name= \"day1\" style=\"width:40;height:30\">";
    for($i = 1; $i<=31; $i++)
    {
	    print"<option value=\"$i\">$i</option>";
    }
    print"</select>";

    print"<select name= \"month1\" style=\"width:40;height:30\">";
    for($i = 1; $i<=12; $i++)
    {
	    print"<option value=\"$i\">$i</option>";
    }
    print"</select>";

    print"<select name= \"year1\" style=\"width:53;height:30\">";
    for($i = 2014; $i<=2014; $i++)
    {
    	print"<option value=\"$i\">$i</option>";

    }
    print"</form></select>";

?>
<input type="checkbox" name="temporal">Temporal Search<br>
    </form>

    <script src="./bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
