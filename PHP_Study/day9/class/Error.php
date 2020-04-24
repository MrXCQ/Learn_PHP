<?php


class IMp_Error
{

    // 用户模块
    const USERNAME_CANNOT_NULL = 001;
    const USERPASS_CANNOT_NULL = 002;
    const USERNAME_EXISTS = 003;
    const REGISTER_FAIL = 004;
    const LOGIN_FAIL = 005;
    const LOGIN_USERNAME_OR_PWDERROR = 005;


    // 文章模块
    const ARTICLE_TITLE_NOT_NULL = 101;
    const ARTICLE_CONTENT_NOT_NULL = 102;
    const ARTICLE_CREATE_FAIL = 103;
    const ARTICLEID_CANNOTNULL = 104;
    const ARTICLEID_GET_FAIL = 105;
    const ARTICLEID_NOTEXISTS = 106;
    const ARTICLEID_CANNOT_EDIT = 107;
    const ARTICLEID_EDIT_FAIL = 108;

}


?>