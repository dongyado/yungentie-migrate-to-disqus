# gentie-migrate-to-disqus

将网易云跟帖评论转换并导入到 disqus。


### 使用方法
 
1. 编辑 converter.php, 指定 $gentie_file_path 为导出的网易云跟帖评论文件，一般是 data.json
2. 执行
    
~~~shell
$ php converter.php 

+-- convert 11 comments success --+
~~~

结果会显示转换成功的评论数目，转换的评论默认写入当前文件夹的 disqus_comment.xml 


### 网易云跟帖导出的评论格式

~~~json
[
    {
        "title": "令人惊喜的jekyll",
        "url": "dongyado.com/funny/2015/05/10/awesome-jekeyll/",
        "sourceId": "",
        "ctime": 1480309718000,
        "comments": [{
            "cid": "2661849758",
            "ctime": 1493707799000,
            "content": "很赞",
            "pid": "0",
            "ip": "202.120.60.85",
            "port": 0,
            "sc": "web",
            "vote": 0,
            "against": 0,
            "anonymous": false,
            "user": {
                "userId": "107829716",
                "nickname": "有态度网友06rlDk",
                "avatar": "",
                "anonymous": false
            }
        }]
    }
]

~~~

### disqus 用户自定义评论导入的 xml 格式

参考 https://help.disqus.com/customer/portal/articles/472150

~~~xml
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:dsq="http://www.disqus.com/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:wp="http://wordpress.org/export/1.0/"
>
<channel>
<item>
<!-- title of article -->
<title>Foo bar</title>
<!-- absolute URI to article -->
<link>http://foo.com/example</link>
<!-- body of the page or post; use cdata; html allowed (though will be formatted to DISQUS specs) -->
<content:encoded><![CDATA[Hello world]]></content:encoded>
<!-- value used within disqus_identifier; usually internal identifier of article -->
<dsq:thread_identifier>disqus_identifier</dsq:thread_identifier>
<!-- creation date of thread (article), in GMT. Must be YYYY-MM-DD HH:MM:SS 24-hour format. -->
<wp:post_date_gmt>2010-09-20 09:13:44</wp:post_date_gmt>
<!-- open/closed values are acceptable -->
<wp:comment_status>open</wp:comment_status>
<wp:comment>
<!-- sso only; see docs -->
<dsq:remote>
<!-- unique internal identifier; username, user id, etc. -->
<dsq:id>user id</dsq:id>
<!-- avatar -->
<dsq:avatar>http://url.to/avatar.png</dsq:avatar>
</dsq:remote>
<!-- internal id of comment -->
<wp:comment_id>65</wp:comment_id>
<!-- author display name -->
<wp:comment_author>Foo Bar</wp:comment_author>
<!-- author email address -->
<wp:comment_author_email>foo@bar.com</wp:comment_author_email>
<!-- author url, optional -->
<wp:comment_author_url>http://www.foo.bar/</wp:comment_author_url>
<!-- author ip address -->
<wp:comment_author_IP>93.48.67.119</wp:comment_author_IP>
<!-- comment datetime, in GMT. Must be YYYY-MM-DD HH:MM:SS 24-hour format. -->
<wp:comment_date_gmt>2010-09-20 13:19:10</wp:comment_date_gmt>
<!-- comment body; use cdata; html allowed (though will be formatted to DISQUS specs) -->
<wp:comment_content><![CDATA[Hello world]]></wp:comment_content>
<!-- is this comment approved? 0/1 -->
<wp:comment_approved>1</wp:comment_approved>
<!-- parent id (match up with wp:comment_id) -->
<wp:comment_parent>0</wp:comment_parent>
</wp:comment>
</item>
</channel>
</rss>
~~~

### 转换成功的数据如下：

~~~xml
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dsq="http://www.disqus.com/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:wp="http://wordpress.org/export/1.0/"
>
<channel>

<item>
    <title>令人惊喜的jekyll</title>
    <link>http://dongyado.com/funny/2015/05/10/awesome-jekeyll/</link>
    <content:encoded><![CDATA[1503115007]]></content:encoded>
    <dsq:thread_identifier>1503115007586</dsq:thread_identifier>
    <wp:post_date_gmt>2016-11-28 13:08:38</wp:post_date_gmt>
    <wp:comment_status>open</wp:comment_status>

    <wp:comment>
        <wp:comment_id>266184974</wp:comment_id>
        <wp:comment_author>有态度网友06rlDk</wp:comment_author>
        <wp:comment_author_email>266184975845@example.com</wp:comment_author_email>
        <wp:comment_author_url></wp:comment_author_url>
        <wp:comment_author_IP>202.120.60.85</wp:comment_author_IP>
        <wp:comment_date_gmt>2017-05-02 14:49:59</wp:comment_date_gmt>
        <wp:comment_content><![CDATA[很赞]]></wp:comment_content>
        <wp:comment_approved>1</wp:comment_approved>
        <wp:comment_parent>0</wp:comment_parent>
    </wp:comment>

</item>
</channel>
</rss>
~~~


### 导入到 disqus

评论导入页面： 

https://{站点名称}.disqus.com/admin/discussions/import/platform/generic/

选择生成好的文件，点击上传即可。 再到下面的链接查看评论是否导入成功:
 
https://{站点名称}.disqus.com/admin/moderate/#/all