<?php
class PLaying
{

    public static function getActive($conn)
    {
        // Φέρε το ενεργό παίξιμο

        $stmt = $conn->prepare("SELECT * 
                                FROM playing 
                                WHERE active = 1");

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $playing = $result->fetch_assoc();

            // Φέρε τους παίκτες του ενεργού παιξίματος

            $stmt = $conn->prepare("SELECT player.id
                                    FROM player
                                    WHERE playing_id = ? 
                                    order by player.id");

            $stmt->bind_param("i", $playing['id']);

            $stmt->execute();

            $result = $stmt->get_result();

            $players = [];

            if ($result->num_rows > 0) {
                while ($p = $result->fetch_assoc()) {
                    $players[] = Player::getById($conn, $playing['id'], $p['id']);
                }
            }

            $playing['players'] = $players;

            return $playing;
        } else {
            return null;
        }
    }

    public static function history($conn)
    {
        // Φέρε το ενεργό παίξιμο

        $stmt = $conn->prepare("SELECT * 
                                FROM playing 
                                WHERE phase = 4");

        $stmt->execute();

        $result = $stmt->get_result();

        $playings = [];

        if ($result->num_rows > 0) {
            while ($playing = $result->fetch_assoc()) {
                // Φέρε τους παίκτες του παιξίματος

                $stmtPlayers = $conn->prepare("SELECT player.id, user.name, player.final_card_cnt
                                    FROM player inner join user on player.id = user.id
                                    WHERE playing_id = ?
                                    order by final_card_cnt");

                $stmtPlayers->bind_param("i", $playing['id']);

                $stmtPlayers->execute();

                $resultPlayers = $stmtPlayers->get_result();

                $players = [];

                if ($resultPlayers->num_rows > 0) {
                    while ($p = $resultPlayers->fetch_assoc()) {
                        $players[] = $p;
                    }
                }

                $playing['players'] = $players;

                $playings[] = $playing;
            }
        }

        return $playings;
    }

    public static function add($conn, $playing)
    {
        $stmt = $conn->prepare("
            INSERT INTO playing(active, phase, player_cnt)
            VALUES(?,?,?)");

        $stmt->bind_param(
            "iii",
            $playing['active'],
            $playing['phase'],
            $playing['player_cnt']
        );

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }

    public static function update($conn, $playing)
    {
        $stmt = $conn->prepare("
            update playing
            set active = ?, 
                phase = ?,
                player_cnt = ?
            where id = ?");

        $stmt->bind_param(
            "iiii",
            $playing['active'],
            $playing['phase'],
            $playing['player_cnt'],
            $playing['id']
        );

        $stmt->execute();
    }

    public static function clearActive($conn)
    {
        $stmt = $conn->prepare("
            update playing
            set active = 0");

        $stmt->execute();
    }

    public static function deal($conn, $playing)
    {
        // Φέρε τους παίκτες

        $players = [];

        $stmt = $conn->prepare("SELECT * 
                                FROM player 
                                WHERE playing_id = ?");

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

        $stmt = $conn->prepare("SELECT * 
                                FROM card");

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
            update card
            set player_id = ?, 
                playing_id = ?,
                player_seqno = ?
            where id = ?");

            $stmt->bind_param(
                "iiii",
                $player['id'],
                $playing['id'],
                $i,
                $card['id']
            );

            $stmt->execute();

            $i++;
        }
    }

    public static function areAllPlayersInState($conn, $state, $playing_id, $playing_player_cnt)
    {
        $stmt = $conn->prepare("SELECT count(*) state_player_cnt 
                               FROM player  
                               where playing_id = ? 
                                    and `state` >= ?");

        $stmt->bind_param("ii", $playing_id, $state);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();

            if ($result['state_player_cnt'] == $playing_player_cnt) {
                return true;
            }
        }

        return false;
    }
}
