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
    
}
