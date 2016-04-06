<?php

class scaffold{
    
    
    public function scaffold( $conf = array( 'url' => '' ) )
    {
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";
        echo "<head>\n";
        try
        {
            echo "<link href=\"$this->baseUri/app/assets/js/jquery/bootstrap/css/bootstrap.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
            echo "<link href=\"$this->baseUri/app/assets/css/default/scaff.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
            echo "</head>\n";
            echo "<body>\n";
            echo "<div id=\"scaff\">\n";
            $table = null;
            if ( isset( $this->uri[1] ) )
            {
                $table = $this->uri[1];
            }
            $url = $conf['url'];
            $fields = array( );
            if ( $table == null )
            {
                //throw new Exception( 'Informe o nome da tabela' );
                $this->query = "select * FROM information_schema.tables WHERE  TABLE_SCHEMA = '$this->dbname'";
                $this->run();
                $data = $this->data;

                foreach ( $data as $item )
                {
                    $tbl = $item['TABLE_NAME'];
                    echo "<p><a href=\"$url/$tbl/\">$tbl</a></p>\n";
                }
            }
            else
            {
                $this->query = "select * FROM information_schema.columns WHERE table_name = '$table' and TABLE_SCHEMA = '$this->dbname'";
                $this->run();
                $data = $this->data;
                echo "<p><a href=\"$url/\" >HOME / TABELAS </a></p>\n";
                $pkey = "id";
                echo "<table class='table table-striped'>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                foreach ( $data as $key => $val )
                {
                    echo "<th>";
                    $key = $val['COLUMN_NAME'];
                    array_push( $fields, $key );
                    if ( $val['COLUMN_KEY'] == 'PRI' )
                    {
                        $pkey = $val['COLUMN_NAME'];
                    }
                    echo ucwords( preg_replace( '/_/', ' ', $key ) );
                    echo "</th>\n";
                }
                echo "<th>Action</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";

                //add and update
                if ( isset( $_POST ) && !empty( $_POST ) )
                {
                    $this->post2Query( $_POST );

                    if ( !isset( $_GET['update'] ) && !empty( $_POST["$pkey"] ) )
                    {
                        $this->insert( "$table" )
                                ->fields( $this->post_fields )
                                ->values( $this->post_values )
                                ->run();
                    }
                    elseif ( isset( $_GET['update'] ) )
                    {
                        $id = $_GET['update'];
                        $this->update( "$table" )
                                ->set( $this->post_fields, $this->post_values )
                                ->where( "$pkey = '$id'" )
                                ->run();
                        $this->redirect( "$url/$table/" );
                    }
                }
                //delete
                if ( isset( $_GET['delete'] ) && !empty( $_GET['delete'] ) )
                {
                    $id = $_GET['delete'];
                    $this->delete()->from( "$table" )->where( "$pkey = '$id'" )->run();
                    $this->redirect( "$url/$table/" );
                }

                $this->select()->from( "$table" )->run();
                if ( $this->result() )
                {
                    echo "<tbody>\n";
                    foreach ( $this->data as $item )
                    {
                        echo "<tr>\n";
                        $k = 0;
                        foreach ( $item as $v )
                        {
                            echo "<td>\n";
                            $pkeyv = $item["$pkey"];
                            $f = array_values( $item );
                            echo $f[$k] . "\n";
                            echo "</td>\n";

                            $k++;
                        }
                        echo "<td><a href=\"?delete=$pkeyv\" class=\"btn\">delete</a> ";
                        echo "<a href=\"?edit=$pkeyv\" class=\"btn\">edit</a></td>";
                        echo "</tr>\n";
                    }
                    echo "</tbody>\n";
                }
                echo "</table>\n";

                //edit
                if ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) )
                {

                    $id = $_GET['edit'];
                    $this->select()->from( "$table" )->where( "$pkey = '$id'" )->run();
                    if ( $this->result() )
                    {
                        $data = $this->data[0];
                        $scaff = '<form name="f" action="?update=' . $id . '" method="post" class="form">' . "\n";
                        foreach ( $fields as $key )
                        {
                            $label = ucwords( preg_replace( '/_/', ' ', $key ) );
                            $scaff .= '<label for="' . $key . '">' . $label . '</label>' . "\n";
                            $scaff .= '<input type="text" name="' . $key . '" id="' . $key . '" value="' . $data[$key] . '"  />' . "\n\n";
                        }
                        $scaff .= '<br /><br /><button class="btn">Atualizar Registro</button>' . "\n";
                        $scaff .= '</form>' . "\n";
                        echo $scaff;
                        exit;
                    }
                }

                $scaff = '<form name="f"  method="post" class="form">' . "\n";
                foreach ( $data as $key => $val )
                {
                    $key = $val['COLUMN_NAME'];
                    $type = $val['COLUMN_TYPE'];
                    $label = ucwords( preg_replace( '/_/', ' ', $key ) );
                    $scaff .= '<label for="' . $key . '">' . $label . '</label>' . "\n";
                    $scaff .= '<input type="text" name="' . $key . '" id="' . $key . '" placeholder="' . $type . '" />' . "\n\n";
                }
                $scaff .= '<br /><br /><button class="btn">Incluir Registro</button>' . "\n";
                $scaff .= '</form>' . "\n";
                echo $scaff;
            }
            echo "</div>\n";
            echo "</body>\n";
            echo "</html>";
            if ( $this->response != null )
            {
                echo $this->response;
            }
        }
        catch ( Exception $e )
        {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }      
}
?>
