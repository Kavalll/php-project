73% of storage used … If you run out, you can't create, edit, and upload files.
response.php
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            td{
                padding: 5px;
            }
        body{
            background: #f0f0f0;
        }
        #sty-heading{
            padding-left:7px;
            font-size: 25px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .sty2{
            background:gainsboro;
            max-width:320px;
            border:1px solid cyan;
            box-shadow: 2px 2px 3px black;
        }
        .sty3{
            background:floralwhite;
            max-width:300px;
            border:1px solid red;
            box-shadow: 2px 2px 3px black;
        }

        </style>
    </head>
    <body>
        <table border="1">
            <?php
            $lastelement = "";
            if (isset($_POST['NEWSPAPER'])) {
                $indata = $_POST['NEWSPAPER'];
            } else {
                $indata = "Morning_Edition";
            }

            echo "<div>TYPE: ";
            echo $indata;
            echo "</div>";

            function startElement($parser, $entityname, $attributes)
            {
                global $lastelement;
                if ($entityname == "ARTICLES") {
                    echo "<tr><th>Newspaper</th><th>Subscribers</th><th>Edition</th><th>Articles</th></tr>";
                }
                if ($entityname == "NEWSPAPER") {
                    echo "<tr>";
                    echo "<td id='sty-heading';>" . $attributes['NAME'] . "</td>";
                    echo "<td>" . $attributes['SUBSCRIBERS'] . "</td>";
                    echo "<td>" . $attributes['TYPE'] . "</td>";
                    echo "<td><table>";
                    echo "</tr>";
                } else if ($entityname == "ARTICLE") {
                    echo "<td>";
                    if (isset($attributes['ID'])) {
                        echo "<td>" . $attributes['ID'] . "</td>";
                    } else {
                        echo " ";
                    }
                    if (isset($attributes['TIME'])) {
                        echo "<td>" . $attributes['TIME'] . "</td>";
                    } else {
                        echo " ";
                    }
                    if (isset($attributes['DESCRIPTION'])) {
                        echo "<td>" . $attributes['DESCRIPTION'] . "</td>";
                    } else {
                        echo " ";
                    }
                    if ($attributes['DESCRIPTION'] == "News") {
                        echo "<td class='sty2';>";
                    } else if ($attributes['DESCRIPTION'] == "Review") {
                        echo "<td class='sty3';>";
                    }
                } else if ($entityname == "HEADING") {
                    echo "<h3>";
                } else if ($entityname == "STORY") {
                    echo "<div>";
                }
                if ($entityname != "TEXT") {
                    $lastelement = $entityname;
                }
            }



            function endElement($parser, $entityname)
            {
                if ($entityname == "NEWSPAPER") {
                    echo "</table></td>";
                } else if ($entityname == "ARTICEL") {
                    echo "</td>";
                } else if ($entityname == "HEADING") {
                    echo "</h3>";
                } else if ($entityname == "STORY") {
                    echo "</div>";
                }
            }

            function charData($parser, $chardata)
            {
                global $lastelement;
                $chardata = trim($chardata);
                if ($chardata == "")
                    return;
                if ($lastelement == "STORY") {
                    echo "<p>" . $chardata . "</p>";
                } else {
                    echo $chardata;
                }
            }

            $parser = xml_parser_create();
            xml_set_element_handler($parser, "startElement", "endElement");
            xml_set_character_data_handler($parser, "charData");

            $url = "https://wwwlab.webug.se/examples/XML/articleservice/articles/?paper=Morning_Edition";
            $data = file_get_contents($url);

            if (!xml_parse($parser, $data, true)) {
                printf("<P> Error %s at line %d</P>", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser));
            } else {
                // print "<br>Parsing Complete!</br>";
            }

            xml_parser_free($parser);

            ?>  
        </table>
    </body>
</html>