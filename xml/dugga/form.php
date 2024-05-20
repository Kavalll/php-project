73% of storage used … If you run out, you can't create, edit, and upload files.
form.php
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dugga 4 SAX</title>

        <style>
        .skicka:hover{
            background-color: green;
            color: white;
        }
        
        </style>
    </head>
    <body>
        <form method="POST" action="response.php">
            <select name='NEWSPAPER'>     
            <option value='Morning_Edition'>Inget valt</option>                                                                                                                                
            <?php                                                                                                                                                 
                
            function startElement($parser, $entityname, $attributes) {
                if($entityname == "NEWSPAPER"){
                    echo "<option value='".$attributes['TYPE']."'>";
                    echo $attributes['NAME'];

                }
            }

            function endElement($parser, $entityname) {
                if($entityname == "NEWSPAPER"){
                    echo "</option>";

                }
            }

            function charData($parser, $chardata) {
            }

            $parser = xml_parser_create();
            xml_set_element_handler($parser, "startElement", "endElement");
            xml_set_character_data_handler($parser, "charData");
            
            $url="https://wwwlab.webug.se/examples/XML/articleservice/papers/";
            $data = file_get_contents($url);
            if(!xml_parse($parser, $data, true)){
               printf("<P> Error %s at line %d</P>",
                         xml_error_string(xml_get_error_code($parser)),
                         xml_get_current_line_number($parser)
            
                     );
            }else{
                // print "<br>Parsing Complete!</br>";
            }

            xml_parser_free($parser);
            
            ?>
            </select>
            <button class='skicka'>Skicka</button>                                                                                                                                                    
        </form>
       
    </body>
</html>