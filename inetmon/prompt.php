<html>
<head>
<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
<script type="text/javascript">
 function saveReason()
{

    var reason=prompt("I'm saving this because","");
    if (reason!=null && reason!="")
    {
        $.post("save.php", { reason: reason});
    }
}


</script>
</head>
<body>

<input type="button" onclick="saveReason()" value="Show alert box">

</body>
</html>

		
		