DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS follows;
DROP TABLE IF EXISTS users;

CREATE TABLE users
(
  uid       INT PRIMARY KEY AUTO_INCREMENT,
  name      VARCHAR(255)    DEFAULT 'کاربر',
  user_type INT             DEFAULT 1
);

CREATE TABLE posts
(
  pid     INT PRIMARY KEY AUTO_INCREMENT,
  uid     INT,
  message TEXT,
  tags TEXT,
  FOREIGN KEY (uid) REFERENCES users (uid)
    ON DELETE CASCADE
);


CREATE TABLE follows
(
  uid INT ,
  fid INT,
  FOREIGN KEY (uid) REFERENCES users (uid)
    ON DELETE CASCADE,
  FOREIGN KEY (fid) REFERENCES users (uid)
    ON DELETE CASCADE
);

CREATE TABLE likes
(
  lid INT PRIMARY KEY AUTO_INCREMENT,
  uid INT,
  pid INT,
  FOREIGN KEY (uid) REFERENCES users (uid)
    ON DELETE CASCADE,
  FOREIGN KEY (pid) REFERENCES posts (pid)
    ON DELETE CASCADE
);


CREATE TABLE comments
(
  cid     INT PRIMARY KEY AUTO_INCREMENT,
  uid     INT,
  pid     INT,
  comment TEXT,
  FOREIGN KEY (uid) REFERENCES users (uid)
    ON DELETE CASCADE,
  FOREIGN KEY (pid) REFERENCES posts (pid)
    ON DELETE CASCADE
);
