<?php

class Ald_Lib_Page{
    private $totalPage;
    private $currPage = 1;
    private $pageVar = 'page';
    private $startPage;
    private $endPage;
    private $linkNum = 7;
    public function __construct($totalRows, $pageSize = 20){
        $this->totalPage = ceil($totalRows / $pageSize);
        if(isset($_REQUEST[$this->pageVar]) && !empty($_REQUEST[$this->pageVar])){
            $this->currPage = intval($_REQUEST[$this->pageVar]);
        }
        $num = ceil($this->linkNum / 2);
        $this->startPage = $this->currPage - $num;
        $this->endPage = $this->currPage + $num;
        if($this->startPage < 0){
            $left = 0 - $this->startPage;
            $this->endPage += $left;
            $this->startPage = 1;
            if($this->endPage > $this->totalPage){
                $this->endPage = $this->totalPage;
            }
        }
        if($this->endPage > $this->totalPage){
            $left = $this->endPage - $this->totalPage;
            $this->startPage -= $left;
            $this->endPage = $this->totalPage;
            if($this->startPage < 1){
                $this->startPage = 1;
            }
        }
    }

    private function buildUrl($page){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $fragment = parse_url($_SERVER['REQUEST_URI'], PHP_URL_FRAGMENT);
        $queryArr = array();
        parse_str($queryString, $queryArr);
        $queryArr[$this->pageVar] = $page;
        $url = $uri . '?' . http_build_query($queryArr);
        if(!empty($fragment)){
            $url .=  '#' . $fragment;
        }
        return $url;
    }

    public function genHtml(){
        $html = '';
        if(1 == $this->currPage){
            $html .= '<a href="javascript:void(0);" class="disabled">首页</a>';
            $html .= '<a href="javascript:void(0);" class="disabled">上一页</a>';
        }else{
            $html .= '<a href="' . $this->buildUrl(1). '">首页</a>';
            $html .= '<a href="' . $this->buildUrl($this->currPage - 1). '">上一页</a>';
        }
        for($i=$this->startPage; $i<=$this->endPage; $i++){
            if($i == $this->currPage){
                $html .= '<a href="' . $this->buildUrl($i). '" class="current">' . $i . '</a>';
            }else{
                $html .= '<a href="' . $this->buildUrl($i). '">' . $i . '</a>';
            }
        }
        if($this->currPage == $this->totalPage){
            $html .= '<a href="javascript:void(0);" class="disabled">下一页</a>';
            $html .= '<a href="javascript:void(0);" class="disabled">尾页</a>';
        }else{
            $html .= '<a href="' . $this->buildUrl($this->currPage + 1). '">下一页</a>';
            $html .= '<a href="' . $this->buildUrl($this->totalPage). '">尾页</a>';
        }
        return $html;
    }
}