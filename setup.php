<?php
global $s;
require_once  'fnama.lib.php';

$s->setup();

$s->new_user('Pooya');
$s->new_user('Sepehr');
$s->new_user('Mahdi');
$s->new_user('Simin');

$s->new_post('1', 'Post 1', 'tagee');
$s->new_post('2', 'Post 2', 'tagee');
$s->new_post('1', 'Post 3', 'foo');
$s->new_post('1', 'Post 4', 'bar');
$s->new_post('1', 'Post 5', 'goo');

$s->follow(1, 2);
$s->follow(2,1);
$s->follow(3,1);
$s->follow(1,3);
$s->follow(3,2);
$s->follow(2,3);

$s->like(4,1);
$s->like(1,1);
$s->like(1,2);
$s->like(1,2);
$s->like(1,2);
$s->like(1,2);
$s->like(3,2);


$s->new_comment(1, 2, 'تست');
$s->new_comment(1, 2, 'کامنت بعدی');
$s->new_comment(1, 1, 'هویج');