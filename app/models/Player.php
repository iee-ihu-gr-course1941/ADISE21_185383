<?php
include_once 'Card.php';

class Player
{
    public static function getById($conn, $playing_id, $player_id)
    {
        $stmt = $conn->prepare("SELECT player.*, user.name
                                FROM player inner join user on player.id = user.id 
                                WHERE player.playing_id = ? 
                                    and player.id = ?");
        $stmt->bind_param("ii", $playing_id, $player_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $player = null;

        if ($result->num_rows > 0) {
            $player = $result->fetch_assoc();

            $player['cards'] = Card::getPlayerCards($conn, $playing_id, $player_id);
        }

        return $player;
    }

    public static function getPlayingCurrent($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT id 
                                FROM player 
                                WHERE playing_iscurrent = 1 
                                    and playing_id = ?");
        $stmt->bind_param("i", $playing_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $player = null;

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            $player = self::getById($conn, $playing_id, $data['id']);
        }

        return $player;
    }

    public static function getPlayingFirstPlayerId($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT min(id) id 
                                FROM player 
                                WHERE playing_id = ?");
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
        $stmt = $conn->prepare("SELECT max(id) id 
                                FROM player 
                                WHERE playing_id = ?");
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
            return self::getById($conn, $playing_id, self::getPlayingFirstPlayerId($conn, $playing_id));
        } else {
            $stmt = $conn->prepare("SELECT id FROM player 
                                    WHERE playing_id = ? 
                                        and id > ? 
                                    order by id");
            $stmt->bind_param("ii", $playing_id, $current_id);

            $stmt->execute();

            $result = $stmt->get_result();

            $player = null;

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();

                $player = self::getById($conn, $playing_id, $data['id']);
            }

            return $player;
        }
    }

    public static function getPlayingPrev($conn, $playing_id, $current_id)
    {
        if ($current_id == self::getPlayingFirstPlayerId($conn, $playing_id)) {
            return self::getById($conn, $playing_id, self::getPlayingLastPlayerId($conn, $playing_id));
        } else {
            $stmt = $conn->prepare("SELECT id 
                                    FROM player 
                                    WHERE playing_id = ? 
                                        and id < ? 
                                    order by id");
            $stmt->bind_param("ii", $playing_id, $current_id);

            $stmt->execute();

            $result = $stmt->get_result();

            $player = null;

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();

                $player = self::getById($conn, $playing_id, $data['id']);
            }

            return $player;
        }
    }

    public static function getByPlayingAndUser($conn, $playing_id, $user_id)
    {
        $stmt = $conn->prepare("SELECT id 
                                FROM player 
                                WHERE playing_id = ? 
                                    and id = ?");
        $stmt->bind_param("ii", $playing_id, $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $player = null;

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            $player = self::getById($conn, $playing_id, $data['id']);
        }

        return $player;
    }

    public static function getActualPlayerCnt($conn, $playing_id)
    {
        $stmt = $conn->prepare("SELECT count(*) player_cnt 
                                FROM player 
                                WHERE playing_id = ?");
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

    public static function getCardCnt($conn, $playing_id, $player_id)
    {
        $stmt = $conn->prepare("SELECT count(*) card_cnt 
                                FROM card 
                                WHERE playing_id = ? 
                                    and player_id = ?");
        $stmt->bind_param("ii", $playing_id, $player_id);

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
            INSERT INTO player(id, playing_id, playing_iscurrent, state)
            VALUES(?,?,?,?)");

        $stmt->bind_param("iiii", 
                            $player['id'], 
                            $player['playing_id'], 
                            $player['playing_iscurrent'], 
                            $player['state']);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }

    public static function update($conn, $player)
    {
        $stmt = $conn->prepare("
            update player
            set playing_iscurrent = ?,
                state = ?
            where playing_id = ? and id = ?");

        $stmt->bind_param("iiii", 
                            $player['playing_iscurrent'], 
                            $player['state'], 
                            $player['playing_id'], 
                            $player['id']);

        $stmt->execute();
    }
}
