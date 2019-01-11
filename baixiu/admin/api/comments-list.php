<?php
/**
 * 分页返回评论数据接口（JSON）
 * Font: fira Code Source Code Pro Consolas
 */

// ==========引入所有预定义函数===========
require '../../functions.php';

// ==========设置响应类型为 JSON==========
header('Content-Type: application/json');

// =============分页逻辑==================
// 1、接收客户端传递过来的当前分页页码
$page = empty($_GET['page'])? 1 : intval($_GET['page']);
// 2、设置可见条数
$length = 30;
// 3、越过条数
$offset = ($page - 1) * $length;

// 4、联合查询查询数据总条数
$total_count = xiu_fetch_one('
	select count(1) as count from comments
	inner join posts on comments.post_id = posts.id;')['count'];

// 5、分页查询评论数据
$sql = sprintf('
  	select comments.*,
	posts.title as post_title
	from comments
	inner join posts on comments.post_id = posts.id
	order by comments.created desc
	limit %d,%d;',$offset,$length);

$comments = xiu_fetch_all($sql);

// 6、计算总页数
$total_pages = ceil($total_count / $length);
// => $total_count['datetype'] = 'float';

// ==================响应 JSON======================

$json = json_encode(array(
  // 'success' => true,
  'total_pages' => $total_pages,
  'comments' => $comments  
));

// echo 向客户端返回客户端需要的数据
echo $json;
