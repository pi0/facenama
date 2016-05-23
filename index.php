<style><?php echo file_get_contents('style.css') ?></style>
<?php

/** @var fnama $s */
global $s;
require_once 'fnama.lib.php';

function q($title, $q)
{
    echo "<h1>$title</h1>";
    if (!$q)
        echo "--";
    else {
        global $s;
        $res = $s->query($q)->fetch_all(1);
        echo "<div class='panel panel-default'><table class='table'><thead><tr>";
        foreach (array_keys($res[0]) as $h)
            echo "<th>$h</th>";
        echo "</tr></thead><tbody>\r\n";
        foreach ($res as $r) {
            echo "<tr>\r\n";
            foreach ($r as $a => $b)
                echo "<td>" . $b . "</td>";
            echo "</tr>\r\n";
        }
        echo "</tbody></table></div> ";
    }
    echo "<hr>";
}

?>

<div class="container">

    <?php

    echo "<h1>Init Database</h1>";
    include 'setup.php';

    q('List all users', "
                SELECT * FROM users
    ");
    q('List followers of user 1', "
                SELECT uid,name FROM users NATURAL JOIN follows WHERE fid=1
    ");

    q('Top posts(by like)', "
                SELECT posts.pid,message,count(lid) AS likes FROM posts 
                LEFT JOIN likes ON posts.pid = likes.pid 
                GROUP BY posts.pid
                ORDER BY likes DESC
    ");

    q('Users following user 1 and has no posts', "
                SELECT * FROM users AS u
                NATURAL JOIN follows
                WHERE fid=1 AND (SELECT count(pid) FROM posts WHERE uid=u.uid)=0
    ");

    q('Hottest posts(like+comment)', "
                SELECT posts.pid,posts.message,count(DISTINCT(likes.lid))+count(DISTINCT(comments.cid)) AS likeAndComment
                FROM posts
                LEFT JOIN likes ON posts.pid = likes.pid
                LEFT JOIN comments ON posts.pid = comments.pid
                GROUP BY posts.pid
                ORDER BY likeAndComment DESC 
    ");

    q('SPAM Scores', "
              SELECT *,100*a.interactions/a.all_interactions spam_ratio FROM(
                SELECT users.uid,name,p.pid,p.message,
                count(DISTINCT(likes.lid))+count(DISTINCT(comments.cid)) AS interactions,
                (SELECT count(DISTINCT(likes.lid))+count(DISTINCT(comments.cid)) 
                            FROM posts AS p2
                            LEFT JOIN likes ON p2.pid = likes.pid
                            LEFT JOIN comments  ON p2.pid = comments.pid
                            WHERE p2.pid=p.pid
                ) AS all_interactions
                FROM users
                JOIN posts AS p
                LEFT JOIN likes ON p.pid = likes.pid AND likes.uid=users.uid
                LEFT JOIN comments ON p.pid = comments.pid AND comments.uid=users.uid
                GROUP BY (users.uid+p.pid)
              ) AS a
    ");

    q('Search by tag="tagee" with more than 3 likes', "
                SELECT * FROM (
                    SELECT *,
                    (SELECT count(lid) FROM likes WHERE likes.pid=p.pid) AS num_likes
                    FROM posts AS p
                    WHERE tags LIKE '%tagee%'
                ) AS temp WHERE num_likes > 3
    ");

    q('Users liking only from followers', "
                
                SELECT uid,name FROM users AS user
                WHERE NOT EXISTS(
                  SELECT *
                  FROM likes
                  LEFT JOIN posts ON likes.pid = posts.pid
                  LEFT JOIN follows ON posts.uid<>follows.fid
                  WHERE likes.uid=user.uid
                )
    ");

    q('Users liking posts from uid=1 but he dosen\'t follows them', "
                SELECT u.uid,u.name FROM likes AS l
                LEFT JOIN users AS u ON l.uid = u.uid
                LEFT JOIN posts AS p ON l.pid = p.pid
                WHERE NOT EXISTS(
                  SELECT * FROM follows WHERE follows.uid=1 AND follows.fid=l.uid
                ) AND p.pid=1
    ");

    q('Self likers', "
                SELECT u.uid,u.name FROM likes AS l
                LEFT JOIN users AS u ON l.uid = u.uid
                LEFT JOIN posts AS p ON l.pid = p.pid
                WHERE p.uid=l.uid
    ");

    q('3 top posts with more comments', "
                SELECT posts.pid,posts.message,count(comments.cid) AS comments FROM posts
                LEFT JOIN comments ON posts.pid = comments.pid
                GROUP BY posts.pid
                ORDER BY comments DESC 
                LIMIT 3
    ");

    ?>


</div>

