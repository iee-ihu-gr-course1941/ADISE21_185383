<?php
class Card
{

    public static function init($conn)
    {
        $stmt = $conn->prepare("
            update card
            set `player_id` = null, 
                `player_seqno` = null");

        $stmt->execute();
    }

    public static function clear($conn, $id)
    {
        $stmt = $conn->prepare("
            update card
            set `player_id` = null, 
                `player_seqno` = null 
            where id = ? ");

        $stmt->bind_param("i", $id);

        $stmt->execute();
    }
    
}
