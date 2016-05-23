<?php

class  fnama
{

    var $db;

    function __construct()
    {
        $this->db = new mysqli('127.0.0.1', 'root', '123', 'facenama');
        echo $this->db->connect_error;
    }

    function setup()
    {
        foreach (explode(';', file_get_contents('setup.sql')) as $q)
            $this->query(trim($q));
    }

    function query($q)
    {
        if (!$q) return;
        $r = $this->db->query($q);
        echo "<div class='alert alert-info'>Query:<pre>$q</pre></div>";
        if ($this->db->error)
            echo 'Error: ' . $this->db->error . "'$q'";
        return $r;
    }

    function multi_query($q)
    {
        $r = $this->db->multi_query($q);
        $this->db->use_result();
        return $r;
    }

    function new_user($name, $type = 1)
    {
        $this->query("INSERT INTO users (name, user_type) VALUES('$name','$type')");
        return $this->db->insert_id;
    }

    function new_post($uid, $message, $tags)
    {
        $this->query("INSERT INTO posts (uid,message,tags) VALUES('$uid','$message','$tags')");
        return $this->db->insert_id;
    }

    function new_comment($uid, $pid, $comment)
    {
        $this->query("INSERT INTO comments (uid,pid,comment) VALUES('$uid','$pid','$comment')");
        return $this->db->insert_id;
    }

    function follow($uid, $fid)
    {
        $this->query("INSERT INTO follows (uid,fid) VALUES('$uid','$fid')");
        return $this->db->insert_id;
    }

    function like($uid, $pid)
    {
        $this->query("INSERT INTO likes (uid,pid) VALUES('$uid','$pid')");
        return $this->db->insert_id;
    }

}

global $s;
$s = new fnama();


