<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <?php
        // put your code here
        $strMsg = "I build PHP";
        $intX = 1;
        $floY = 1.7; 
        echo "Now loading $strMsg " . $intX . $floY ." i dont know <t> one <br>";
        
        function myTest() {
            static $x = 0;            
            echo $x;
            $x++;
        }
        echo "<h4> Chữ in đậm </h4> <br>";
        myTest();
        echo "<br>";
        myTest();
        echo "<br>";
        myTest();
        
        $var = 3;
        echo "Result: " . ($var + 3);
        
        ?>
    </body>
</html>
