<?php
class Card
{

    public static function init($conn)
    {
        $stmt = $conn->prepare("
            update card
            set player_id = null, 
                playing_id = null, 
                player_seqno = null");

        $stmt->execute();
    }

    public static function update($conn, $card)
    {
        $stmt = $conn->prepare("
            update card
            set player_id = ?, 
                playing_id = ?,
                player_seqno = ? 
            where id = ? ");

        $stmt->bind_param("iiii", 
                            $card['player_id'], 
                            $card['playing_id'], 
                            $card['player_seqno'], 
                            $card['id']);

        $stmt->execute();
    }

    public static function clear($conn, $id)
    {
        $stmt = $conn->prepare("
            update card
            set player_id = null, 
                playing_id = null,             
                player_seqno = null 
            where id = ? ");

        $stmt->bind_param("i", $id);

        $stmt->execute();
    }

    public static function getPlayerCards($conn, $playing_id, $player_id) {
        $stmt = $conn->prepare("SELECT * 
                                FROM card 
                                WHERE playing_id = ? 
                                    and player_id = ?
                                order by player_seqno");

        $stmt->bind_param("ii", $playing_id, $player_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $cards = [];

        while ($card = $result->fetch_assoc()) {
            $cards[] = $card;
        }

        return $cards;
    }

    
}
