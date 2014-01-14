<html>
<head>
  <title>jVectorMap demo</title>
  <link rel="stylesheet" href="jquery-jvectormap-1.0.css" type="text/css" media="screen"/>
  <script src="jquery.js"></script>
  <script src="jquery-jvectormap-1.0.min.js"></script>
  <script src="jquery-jvectormap-world-mill-en.js"></script>
</head>
<body>
  <div id="world-map" style="width: 600px; height: 400px"></div>
  <script>
    $(function(){
      $('#world-map').vectorMap();
    });
  </script>
</body>
</html>