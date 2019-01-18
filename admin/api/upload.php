<?php 

 // var_dump($_FILES['avatar']);

 // 接收文件
 // 保存文件
 // 保存这个文件的访问URL
 
 if (empty($_FILES['avatar'])) {
 	exit('必须上传文件');
 	# code...
 }
 $avatar = $_FILES['avatar'];
 if ($avatar['error'] !== UPLOAD_ERR_OK) {
 	exit('上传失败');
 	# code...
 }
 // 校验类型大小格式等
 // 移动文件到网站范围之内
 $ext = pathinfo($avatar['name'], PATHINFO_EXTENSION);
 $target = '../../static/uploads/img-' . uniqid() . '.' . $ext;
 if (!move_uploaded_file($avatar['tmp_name'], $target)) {
 	exit('上传失败');
 	# code...
 }
 // 上传成功
 echo substr($target, 5);