<?php
class Player
{
    public static function getById($conn, $player_id)
    {
        $stmt = $conn->prepare("SELECT * FROM player WHERE id = ?");
        $stmt->bind_param("i", $player_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public static function getPlayingCurrent($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT * FROM player WHERE playing_iscurrent = 1 and playing_id = ?");
        $stmt->bind_param("i", $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public static function getPlayingFirstPlayerId($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT min(id) id FROM player WHERE playing_id = ?");
        $stmt->bind_param("i", $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            return $data['id'];
        } else {
            return 0;
        }
    }

    public static function getPlayingLastPlayerId($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT max(id) id FROM player WHERE playing_id = ?");
        $stmt->bind_param("i", $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            return $data['id'];
        } else {
            return 0;
        }
    }

    public static function getPlayingNext($conn, $playing_id, $current_id)
    {
        if ($current_id == self::getPlayingLastPlayerId($conn, $playing_id)) {
            return self::getById($conn, self::getPlayingFirstPlayerId($conn, $playing_id));
        } else {
            $stmt = $conn->prepare("SELECT * FROM player WHERE playing_id = ? and id > ? order by id");
            $stmt->bind_param("ii", $playing_id, $current_id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }
    }

    public static function getByPlayingAndUser($conn, $playing_id, $user_id)
    {
        $stmt = $conn->prepare("SELECT * FROM player WHERE id = ? and playing_id = ?");
        $stmt->bind_param("ii", $user_id, $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public static function getActualPlayerCnt($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT count(*) player_cnt FROM player WHERE playing_id = ?");
        $stmt->bind_param("i", $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            return $data['player_cnt'];
        } else {
            return 0;
        }
    }

    public static function getCardCnt($conn, $player_id)
    {
        $stmt = $conn->prepare("SELECT count(*) card_cnt FROM card WHERE player_id = ?");
        $stmt->bind_param("i", $player_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            return $data['card_cnt'];
        } else {
            return 0;
        }
    }

    public static function add($conn, $player)
    {
        $stmt = $conn->prepare("
            INSERT INTO player(`id`, `playing_id`, `playing_iscurrent`, `state`)
            VALUES(?,?,?,?)");

        $stmt->bind_param("iiii", $player['id'], $player['playing_id'], $player['playing_iscurrent'], $player['state']);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }

    public static function update($conn, $player)
    {
        $stmt = $conn->prepare("
            update player
            set `playing_id` = ?, 
                `playing_iscurrent` = ?,
                `state` = ?
            where id = ?");

        $stmt->bind_param("iiii", $player['playing_id'], $player['playing_iscurrent'], $player['state'], $player['id']);

        $stmt->execute();
    }
}
