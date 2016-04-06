<?php

class HTML
{

    public function header( $title )
    {
        $header = "
        <!DOCTYPE html>
        <html>
            <head>
                <title>$title</title>
            </head>        
        ";
        echo $header;
    }
    
    public function body( $content = '' )
    {
        $body = "
            <body>\n
            $content 
        ";
        echo $body;
    }  
    
    public function footer( $content = '' )
    {
        $footer = "
            $content
            </body>\n    
            </html>\n    
        ";
        echo $footer;
    }
    
}
?>
