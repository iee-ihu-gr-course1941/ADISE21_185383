<?php
class Player
{
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

    public static function add($conn, $id, $playing_id, $playing_iscurrent, $state)
    {
        $stmt = $conn->prepare("
            INSERT INTO player(`id`, `playing_id`, `playing_iscurrent`, `state`)
            VALUES(?,?,?,?)");

        $stmt->bind_param("iiii", $id, $playing_id, $playing_iscurrent, $state);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }
}
