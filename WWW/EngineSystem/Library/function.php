<?php
/**
 * 调试函数
 * @param $var 变量名
 */
function dd($var){
    if($var){
        echo "<pre>";
        var_dump($var);
    }else{
        echo "变量不存在";
    }
}
/**
 * 消息提示
 * @param int $type 1:成功 2:失败
 * @param null $msg
 * @param null $url
 */
function msg($type, $msg = null, $url = null)
{
    $toUrl = "Location:msg.php?type={$type}";
    //当msg为空时 url不写入
    $toUrl .= $msg ? "&msg={$msg}" : '';
    //当url为空 toUrl不写入
    $toUrl .= $url ? "&url={$url}" : '';
    header($toUrl);
    exit;
}


/**
 * 检查用户是否登录
 *
 */
function checkLogin()
{
    //开启session
    session_start();
    //用户未登录
    if(!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        return false;
    }
    return true;

}

/**
 * 获取当前url
 * @return string
 */
function getUrl()
{
    $url = '';
    $url .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}


/**
 * 根据page生成url
 * @param $page
 * @param string $url
 * @return string
 */
function pageUrl($page, $url = '')
{
    $url = empty($url) ? getUrl() : $url;
    //查询url中是否存在?
    $pos = strpos($url, '?');
    if($pos === false)
    {
        $url .= '?page=' . $page;
    }
    else
    {
        $queryString = substr($url, $pos + 1);
        //解析querystring为数组
        parse_str($queryString, $queryArr);
        if(isset($queryArr['page']))
        {
            unset($queryArr['page']);
        }
        $queryArr['page'] = $page;

        //将queryArr重新拼接成queryString
        $queryStr = http_build_query($queryArr);

        $url = substr($url, 0, $pos) . '?' . $queryStr;

    }
    return $url;

}


/**
 * 分页显示
 * @param  int $total 数据总数
 * @param  int $currentPage 当前页
 * @param  int $pageSize 每页显示条数
 * @param  int $show 显示按钮数
 * @return string
 */
function pages($total, $currentPage, $pageSize, $show = 6)
{
    $pageStr = '';
    //仅当总数大于每页显示条数 才进行分页处理
    if($total > $pageSize)
    {
        //总页数
        $totalPage = ceil($total / $pageSize);//向上取整 获取总页数

        //对当前页进行处理
        $currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
        //分页起始页
        $from = max(1, ($currentPage - intval($show / 2)));
        //分页结束页
        $to = $from + $show - 1;



        $pageStr .= '<ul class="pagination">';

        //仅当 当前页大于1的时候 存在 首页和上一页按钮
        if($currentPage > 1)
        {
            $pageStr .= "<li><a href='" . pageUrl(1) . "'>首页</a></li>";
            $pageStr .= "<li><a href='" . pageUrl($currentPage - 1) . "'>上一页</a></li>";
        }


        //当结束页大于总页
        if($to > $totalPage)
        {
            $to = $totalPage;
            $from = max(1, $to - $show + 1);
        }


        if($from > 1)
        {
            $pageStr .= '<li><a>...</a></li>';
        }


        for($i = $from; $i <= $to; $i++)
        {
            if($i != $currentPage)
            {
                $pageStr .= "<li><a href='" . pageUrl($i) . "'>{$i}</a></li>";
            }
            else
            {
                $pageStr .= "<li><span class='curr-page'>{$i}</span></li>";
            }
        }


        if($to < $totalPage)
        {
            $pageStr .= '<li><a>...</a></li>';

        }

        if($currentPage < $totalPage)
        {
            $pageStr .= "<li><a href='" . pageUrl($currentPage + 1) . "'>下一页</a></li>";
            $pageStr .= "<li><a href='" . pageUrl($totalPage) . "'>尾页</a></li>";
        }

        $pageStr .= '</ul>';

    }

    return $pageStr;

}

