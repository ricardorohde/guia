<?php

Class FilterArray
{

    public function map( $arr = array( ) )
    {
        try
        {
            if ( $arr == null )
            {
                throw new Exception( 'ArrayMapNull' );
            }
            else
            {
                foreach ( $arr as $k => $v )
                {
                    if ( !isset( $this->$k ) )
                    {
                        $this->$k = "";
                    }
                    $this->$k = $v;
                    $this->obj[$k] = $v;
                }
            }
        }
        catch ( Exception $e )
        {
            echo $e->getMessage();
            exit;
        }
        return (object) $this->obj;
    }
}
?>