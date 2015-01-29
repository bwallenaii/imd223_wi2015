<?php

function example($arg1, $arg2 = "!**!")
{
    return $arg1.$arg2;
}

function mult($num1, $num2 = 1)
{
    return $num1 * $num2;
}

function spacer(array $array)
{
    $ret = "";
    foreach($array as $value)
    {
        $ret .= $value." ";
    }
    return $ret;
    //return implode(" ", $array);
}

function outputArray(array $ar)
{
    echo "<pre>";
    print_r($ar);
    echo "</pre>";
}

function createLink($href)
{
    return "<a href=\"$href\">$href</a>";
}

/**
 * Creates an anchor tag around the href provided.
 * @author  Brent Allen
 * @param   String    href    the url to browse to
 * @param   Boolean   blank   will set the target to blank if true
 * @return  String  The resulting tag
**/
function createLink2($href, $blank = true)
{
    $ret = $blank ? "<a href=\"$href\" target=\"_blank\">$href</a>":"<a href=\"$href\">$href</a>";
    return $ret;
}
?>
<html>
    <head>
        <title>Week 2 examples</title>
    </head>
    <body>
        <div>
        <?php
            echo example("Wahoo!")."<br />";
            echo mult(5,6)."<br />";
            
            $words = array("dictionary", "studebaker", "ranch", "house", "carbunkle");
            echo spacer($words);
            //echo spacer(5); //ERROR!!!
            
            outputArray($words);
        ?>
        </div>
        <h2>Do this:</h2>
        <p>
            Write a function that takes a URL (as a string) as an argument and 
            generates an anchor "a" tag, placing the url in the href attribute as
            well as the tag content.
        </p>
        <p>
            So, if I were to pass "http://www.google.com" into the function, it would create
            the following link: <a href="http://www.google.com">http://www.google.com</a>
        </p>
        <p>
        <?php echo createLink2("http://userfriendly.org", false); ?>
        </p>
        <?php
            echo date("m/d/Y H:i:s");
        ?>
    </body>
</html>



