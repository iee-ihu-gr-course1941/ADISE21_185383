<?php
class PLaying
{

    public static function getActive($conn)
    {
        $stmt = $conn->prepare("SELECT * FROM playing WHERE active = '1'");

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public static function add($conn, $playing)
    {
        $stmt = $conn->prepare("
            INSERT INTO playing(`active`, `phase`, `player_cnt`)
            VALUES(?,?,?)");

        $stmt->bind_param("iii", $playing['active'], $playing['phase'], $playing['player_cnt']);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }

    public static function update($conn, $playing)
    {
        $stmt = $conn->prepare("
            update playing
            set `active` = ?, 
                `phase` = ?,
                `player_cnt` = ?
            where id = ?");

        $stmt->bind_param("iiii", $playing['active'], $playing['phase'], $playing['player_cnt'], $playing['id']);

        $stmt->execute();
    }

    public static function deal($conn, $playing)
    {
        // Φέρε τους παίκτες

        $players = [];

        $stmt = $conn->prepare("SELECT * FROM player WHERE playing_id = ?");

        $stmt->bind_param("i", $playing['id']);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($player = $result->fetch_assoc()) {
                $players[] = $player;
            }
        }

        // Φέρε τα χαρτιά

        $cards = [];

        $stmt = $conn->prepare("SELECT * FROM `card`");

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($card = $result->fetch_assoc()) {
                $cards[] = $card;
            }
        }

        // Ανακάτεψε τους παίκτες

        shuffle($players);

        // Ανακάτεψε τα χαρτιά

        shuffle($cards);

        // Μοίρασε τα 41 χαρτιά στους παίκτες

        $players_cnt = count($players);

        $i = 0;
        foreach ($cards as $card) { 
            // Αντιστοίχησε κάθε χαρτί με τον επόμενο - κατά σειρά - παίκτη

            $player = $players[$i % $players_cnt];

            $stmt = $conn->prepare("
            update `card`
            set `player_id` = ?, 
                `player_seqno` = ?
            where `id` = ?");

            $stmt->bind_param("iii", $player['id'], $i, $card['id']);

            $stmt->execute();

            $i++;
        }
    }
}
