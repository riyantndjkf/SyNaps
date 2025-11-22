<?php
require_once("parent.php");

class Event extends classParent {

    public function __construct() {
        parent::__construct();
    }

    public function getEventByGroup($idgrup) {
        $sql = "SELECT * FROM event WHERE idgrup = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idgrup);
        $stmt->execute();
        $res = $stmt->get_result();

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getEvent($idevent) {
        $sql = "SELECT * FROM event WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idevent);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertEvent($arr) {
        $sql = "INSERT INTO event (idgrup, judul, `judul_slug`, tanggal, keterangan, jenis, poster_extension)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param(
            "issssss",
            $arr['idgrup'],
            $arr['judul'],
            $arr['judul_slug'],
            $arr['tanggal'],
            $arr['keterangan'],
            $arr['jenis'],
            $arr['poster_extension']
        );
        return $stmt->execute();
    }

    public function updateEvent($idevent, $arr) {
        $sql = "UPDATE event 
                SET judul=?, `judul_slug`=?, tanggal=?, keterangan=?, jenis=?, poster_extension=? 
                WHERE idevent=?";

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param(
            "ssssssi",
            $arr['judul'],
            $arr['judul_slug'],
            $arr['tanggal'],
            $arr['keterangan'],
            $arr['jenis'],
            $arr['poster_extension'],
            $idevent
        );
        return $stmt->execute();
    }

    public function deleteEvent($idevent) {
        $sql = "DELETE FROM event WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idevent);
        return $stmt->execute();
    }
}
?>
