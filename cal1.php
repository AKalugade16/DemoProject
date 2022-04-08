<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALCULATOR</title>
    <style>
        form { 
        border: 3px solid #f1f1f1; 
        padding: 15px; 
        margin: 50px 200px 200px 200px;
        background-color:#e6f9ff;
        border: solid;
        text-align:center;
    }
    button { 
       background-color: LightGray; 
       width: 20%;
        color: black; 
        padding: 10px; 
        margin: 20px 20px ; 
        border: gray; 
        cursor: pointer; 
         }
    input[type=text]
    {
        width: 50%; 
        margin: 8px 0;
        padding: 12px 20px; 
        display: inline-block; 
        border: 2px solid black; 
        box-sizing: border-box;
    }
    </style>
</head>

<body>

<?php

$result='';
$textVal='';
$message='';
$zeroNum='';
$Negavtive='';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $textVal=$_POST["Val"];
    $operator=" ";
    if(!empty($textVal))
    {
        for($a=0;$a<strlen($textVal);$a++)
        {
            if($textVal[$a]=="+")
            {
                $operator="+";
                break;
            }
            elseif($textVal[$a]=="-")
            {
                $operator="-";
                break;
            }
            elseif($textVal[$a]=="*")
            {
                $operator="*";
                break;
            }
            elseif($textVal[$a]=="/")
            {
                $operator="/";
                break;
            }
            else{
                continue;
            }
        }

        $array=explode($operator,$textVal);
        if(($array[0]<"0")&&($array[1]>"0"))
        {
            $Negavtive="Negative Numbers..";
        }
        else
        {
            if($operator=="+")
            {
            $result=intval($array[0])+intval($array[1]);
            }
            elseif($operator=="-")
            {
                $result=intval($array[0])-intval($array[1]);
            }
            elseif($operator=="*")
            {
                $result=intval($array[0])*intval($array[1]);
            }
            elseif($operator=="/")
            {
                if(($array[0]!="0") &&($array[1]!="0"))
                {
                    $result=intval($array[0])/intval($array[1]);
                }
                else
                {
                    $zeroNum="Invalid Numbers...";
                }
            }
        }
    }
    else
    {
        $message="Null Value.... Enter Valid Value...";
    }
}  
?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
    <div class="container">

        <h1> Enter Values here</h1><br>
        <input type="text" name="Val" value="<?php echo $textVal;?>" >
        <br>
        <button type="submit">Submit</button>    
    
    <?php
    echo "<h2> Output:</h2>";
    echo $result;
    echo $message;
    echo $zeroNum;
    echo $Negavtive;
    ?> 
    
</div>
</form>

</body>

</html>