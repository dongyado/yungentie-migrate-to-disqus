<?php
/**
 * 之前因为 disqus 被q，就使用了多说，然后多说挂了，
 * 就使用了网易云跟帖，然后几个月不到，云跟帖也挂了，
 * 然后突然觉得还是 disqus 靠谱，毕竟程序员都会fq。
 *
 * 网易云跟帖导出的文件格式无法导入 disqus, 所以写个脚本
 * 把评论转换到 disqus 支持的 xml格式。
 *
 * @author dongyado<dongyado@gmail.com>
 * */

$gentie_file_path = '';
$disqus_file_path = 'disqus_comment.xml';


if ($gentie_file_path == '' || !file_exists($gentie_file_path)) {
    echo "+-- Invalid gentie file path --+\n";
    exit();
}


$comment = file_get_contents($gentie_file_path);



?>
