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

$gentie_file_path = 'data.json';
$disqus_file_path = 'disqus_comment.xml';


if ($gentie_file_path == '' || !file_exists($gentie_file_path)) {
    echo "+-- Invalid gentie file path --+\n";
    exit();
}

$comment = file_get_contents($gentie_file_path);

$comments = json_decode($comment, true);

if (empty($comments)) {
    echo "+-- Empty comments --+\n";
    exit();
}

$xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dsq="http://www.disqus.com/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:wp="http://wordpress.org/export/1.0/"
>
<channel>

EOF;


$handle = fopen($disqus_file_path, "w");

if (!$handle)  {
    exit("+-- failed to open {$disqus_file_path} --+\n");
}

fwrite($handle, $xml);


$old_domain     = "dongyado.com";
$dist_domain    = "dongyado.com";
$comment_count  = 0;

foreach($comments as $item ) {
    $item_xml = "\n<item>\n";
    $item_xml .= "<title>{$item['title']}</title>\n";
    $item_xml .= "<link>http://".str_replace($old_domain, $dist_domain, $item['url']) ."</link>\n";
    $item_xml .= "<content:encoded><![CDATA[".time()."]]></content:encoded>\n";
    $item_xml .= "<dsq:thread_identifier>". (time() . mt_rand(1, 1000)) ."</dsq:thread_identifier>\n";

    $item_xml .= "<wp:post_date_gmt>".date('Y-m-d H:i:s', ((int) ($item['ctime'] / 1000)))."</wp:post_date_gmt>\n";
    $item_xml .= "<wp:comment_status>open</wp:comment_status>\n";

    foreach($item['comments'] as $comment ) {

        $item_xml .= "
            <wp:comment>
            <wp:comment_id>".$comment['cid']."</wp:comment_id>
            <wp:comment_author>".$comment['user']['nickname']."</wp:comment_author>
            <wp:comment_author_email>".$comment['cid'] . mt_rand(1, 100) ."@example.com</wp:comment_author_email>
            <wp:comment_author_url></wp:comment_author_url>
            <wp:comment_author_IP>".$comment['ip']."</wp:comment_author_IP>
            <wp:comment_date_gmt>".date('Y-m-d H:i:s',( (int) ($comment['ctime']/1000)))."</wp:comment_date_gmt>
            <wp:comment_content><![CDATA[".$comment['content']."]]></wp:comment_content>
            <wp:comment_approved>1</wp:comment_approved>
            <wp:comment_parent>".$comment['pid']."</wp:comment_parent>
            </wp:comment>\n";
        
        $comment_count++;
    }

    $item_xml .= "\n</item>\n";
    fwrite($handle, $item_xml);
}


fwrite($handle, "\n</channel>\n</rss>\n");
fclose($handle);

exit("+-- convert {$comment_count} comments success --+\n");
?>
