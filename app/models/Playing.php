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

    public static function add($conn, $active, $phase, $player_cnt)
    {
        $stmt = $conn->prepare("
            INSERT INTO playing(`active`, `phase`, `player_cnt`)
            VALUES(?,?,?)");

        $stmt->bind_param("iii", $active, $phase, $player_cnt);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }

        return 0;
    }
}
