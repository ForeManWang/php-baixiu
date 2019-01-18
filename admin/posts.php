<?php

require_once '../functions.php';

xiu_get_current_user();

//////////////////////////////////////////////
// 登录过后                                     //
//                                          //
// ===============处理筛选功能=================== //
// 接收筛选参数                                   //
$where = '1 = 1';
$search = '';
// 分类筛选
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
  $where .= ' and posts.category_id = ' . $_GET['category'];
  $search .= '&category=' . $_GET['category'];
}
// 状态筛选
if (isset($_GET['status']) && $_GET['status'] !== 'all') {
  $where .= " and posts.status = '{$_GET['status']}'";
  $search .= '&status=' . $_GET['status'];
}
// where => '1 = 1' and posts.category_id = 1 and posts.status = 'drafted';
// $search => &category = 1&status=drafted  可以在html和php混编中拼接到page参数后面

// ===============处理分页参数===================
// 当前页码
$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
// if ($page < 1) {
//   // 不可能有小于1的情况 跳转
//   header('Location: /admin/posts.php?page=1');
//   # code...
// }
if ($page < 1) {
  // 不可能有小于1的情况 跳转
  header('Location: /admin/posts.php?page=1'.$search);
  # code...
}
// 每页展示条数
$size = 10;
// 总页数 一般取数据库 加别名 后来操作时会更加明确
// 只要涉及到分页功能一定会用到最大页码数
$total_count = (int)xiu_fetch_one("
    select count(1) as count from posts
    inner join categories on posts.category_id = categories.id
    inner join users on posts.user_id = users.id
    where {$where};
  ")['count'];
// => $end < ceil(总数据/$size)  因为198条需要20页 则向上取整
// => ceil() 得到的是一个 float 类型数字
$total_pages = (int)ceil($total_count / $size);
// var_dump($total_pages) => 101;
// if ($page > total_pages) {
//   header('Location: /admin/posts.php?page='.$total_pages);
//   # code...
// }
// $page = $page > $total_pages ? $total_pages : $page;
if ($page > $total_pages) {
  // 跳转到第最后页
  header('Location: /admin/posts.php?page=' . $total_pages . $search);
}
// 越过多少条查询
$offset = ($page - 1) * $size;
// 根据页码就算出来
// 获取全部数据
// 数据库可以做联合查询 这样避免重复查询数据库多次 避免响应时间太长
$posts = xiu_fetch_all("select 
    posts.id,
    posts.title,
    users.nickname as user_name,
    categories.`name` as category_name,
    posts.created,
    posts.`status`
    from posts
    inner join categories on posts.category_id = categories.id
    inner join users on posts.user_id = users.id
    where {$where}
    order by posts.created desc
    limit {$offset}, {$size};");
// var_dump(($posts));
// posts => [0] => 关联数组,
//          [1] => 关联数组,
//          [2] => 关联数组


// ===============查询全部分类=======================
$categories = xiu_fetch_all('select * from categories;');


// ===============处理分页页码=======================
// 当前页码 => $page

// 可见页码 5 页
$visiables = 5;
// 当前页码前后区间
$region = $visiables % 2 == 0 ? ($visiables / 2) : ($visiables - 1) / 2;
// 可见开始页码
$begin = $page - $region;
// 可见结束页码
$end = $begin + $visiables - 1;
// 可能出现 $begin 和 $end 越界（0-...）
// => $begin > 0 确保最小为 1
$begin = $begin < 1 ? 1 : $begin;
// 同步 $begin 变化之后的 $end 值
$end = $begin + $visiables - 1;
// => $end < ceil(总数据/$size)  因为198条需要20页 则向上取整
$end = $end > $total_pages ? $total_pages : $end;
// 同步 $end 变化之后的 $begin 值
$begin = $end - $visiables + 1;
// 确保 $begin 不能小于 1 
$begin = $begin < 1 ? 1 : $begin;

// ===============处理数据格式转换===================
// 
/**
 * 转换状态显示
 * @param  [string] $status [English]
 * @return [string]         [Chinese]
 */
function convert_status ($status) {
  $dict = array(
    'published' => '已发布',
    'drafted' => '草稿',
    'trashed' => '回收站'
  );
  return isset($dict[$status]) ? $dict[$status] : '未知';
}

// ===============处理时间格式转换==============
// 
/**
 * 转换时间格式
 * @param  [type] $created [description]
 * @return [type]          [description]
 */
function convert_data ($created) {
  // => '2018-11-20 08:08:08'
  // 如果时间文件没有配置时区
  // data_default_timezone_set('PRC');
  $timestamp = strtotime($created);
  return date('Y年m月d日<b\r>H:i:s', $timestamp);
}

// 每次提取值都要查询一下数据库  不合理
// function get_category ($category_id) {
//   return xiu_fetch_one("select name from categories where id = {$category_id};")['name'];
// }
// function get_user ($user_id) {
//   return xiu_fetch_one("select nickname from users where id = {$user_id};")['nickname'];
// }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>

    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['category']) && $_GET['category'] === $item['id']? ' selected' : '' ?>><?php echo $item['name']; ?>
              </option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] == 'drafted' ? ' selected' : '' ?>>草稿</option>
            <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] == 'published' ? ' selected' : '' ?>>已发布</option>
            <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] == 'trashed' ? ' selected' : '' ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="?page=<?php echo $page-1; ?>">上一页</a></li>
          <?php for ($i = $begin; $i <= $end; $i++): ?>
            <li<?php echo $i === $page ? ' class = "active"' : ''; ?>><a href="?page=<?php echo $i . $search; ?>"><?php echo $i; ?></a></li>
          <?php endfor ?>
          <li><a href="?page=<?php echo $page+1; ?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $item): ?>
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $item['title']; ?></td>
<!--               <td><?php //echo get_user($item['user_id']); ?></td>
              <td><?php //echo get_category($item['category_id']); ?></td> -->
              <td><?php echo $item['user_name']; ?></td>
              <td><?php echo $item['category_name']; ?></td>
              <td class="text-center"><?php echo convert_data($item['created']); ?></td>
              <!-- 一旦当输出的判断逻辑或者转换逻辑过于复杂 不建议直接写在混编的位置-->
              <td class="text-center"><?php echo convert_status($item['status']); ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="/admin/post-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'posts'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
