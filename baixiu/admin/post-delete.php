<?php

/**
 * 根据客户端传递过来的ID删除对应数据
 */

require_once '../functions.php';

if (empty($_GET['id'])) {
  exit('缺少必要参数');
}

// $id = (int)$_GET['id'];
$id = $_GET['id'];
// => '1 or 1 = 1'
// sql 注入
// 1,2,3,4

$rows = xiu_execute('delete from posts where id in (' . $id . ');');


// $_SERVER['HTTP_REFERER'] 获取页面来源
header('Location:' .$_SERVER['HTTP_REFERER']);
