

DELETE FROM user WHERE userid = (SELECT userid WHERE username='admin2')

SELECT * FROM `user` ORDER BY userid DESC LIMIT 3

select username from `article` INNER JOIN `user` ON article.userid = `user`.userid LIMIT 0,5

SELECT * FROM article LIMIT 0,5

SELECT username FROM user WHERE userid = 10

-- 删除语句
DELETE FROM user WHERE userid = (SELECT userid FROM article WHERE articleid = 9)

INSERT INTO user(username,userpassword,register_date) VALUES("user1", "123456789","2020-04-27 15:16")

UPDATE user SET username = "11111111",register_date = "2020-04-27 15:16:48" WHERE username LIKE "接口测试%"

-- 批量替换 修改
update `user` SET username = REPLACE(username,"admin","user")

-- LIKE 模糊查询
-- '%a'     //以a结尾的数据
-- 'a%'     //以a开头的数据
-- '%a%'    //含有a的数据
-- '_a_'    //三位且中间字母是a的
-- '_a'     //两位且结尾字母是a的
-- 'a_'     //两位且开头字母是a的

-- MySQL UNIIO 操作符
-- UNION 操作符用户连接两个以上的SELECT语句的结果组合到一个结果集合中，多个SELECT语句会删除重复的数据

SELECT userid FROM user UNION SELECT userid FROM article ORDER BY userid ASC

-- GROUP BY
-- 此语句根据一个或者多个列对结果集进行分组
-- 在分组的列上我们可以使用 COUNT、SUM、AVG 等函数

SELECT username , COUNT(*) FROM user GROUP BY username

-- 多表查询 
-- INNER JOIN  获取两个表中字段匹配关系的记录
-- LEFT JOIN 左连接    获取坐标所有记录，即使游标没有对应匹配的记录
-- RIGHT JOIN 右连接   与LEFT JOIN 相反，用于获取右表的所有记录，即使左表没有对应匹配的记录 

SELECT * FROM article WHERE userid = (SELECT userid FROM `user` WHERE username = '11111111')

SELECT * FROM article 
SELECT * FROM user

SELECT a.userid,a.userpassword,b.title,b.content FROM user a INNER JOIN article b ON a.userid = b.userid

// 下面的语句和上条等价
SELECT a.userid,a.userpassword,b.title,b.content FROM user a,article b WHERE a.userid = b.userid

-- LEFT JOIN  会根据条件读取选取的字段 会返回左表的所有数据
SELECT a.userid,a.userpassword,b.title,b.content FROM user a LEFT JOIN article b on a.userid = b.userid

-- RIGHT JOIN 会读取右边表的全部数据，即便左边表无对应数据
SELECT a.userid,a.userpassword,b.title,b.content FROM user a RIGHT JOIN article b on a.userid = b.userid


-- MySQL 的NULL值处理
-- IS NULL 当列的值是NULL，此运算符返回为true 
-- IS NOT NULL 当列的值不为NULL 运算符返回为true 
-- <=> 两个值相等或者为NULL 时返回true
SELECT * FROM user WHERE user_mobile is NULL

-- 正则表达式
-- 获取1 开头的用户名称
SELECT * FROM `user` WHERE username REGEXP '^1'

-- 获取 4 结尾的用户名称
SELECT * FROM user WHERE username REGEXP '1$'

-- 获取包含 user 字符串的所有数据
SELECT * FROM user WHERE username REGEXP 'user'

-- 获取以 1 开头或结尾的用户名称 （和 用+）
SELECT * FROM user WHERE username REGEXP '^1|1$'

-- MySQL 事务
-- 事务主要处理操作量大，复杂度高的操作。比如在人员操作系统中，删除一个人员，既需要删除用户的基本资料，也需要删除用户的相关信息，这些sql 操作语句就构成了一个事务
-- 事务必须满足四个条件： 原子性、一致性、隔离性（独立性）、持久性

-- 原子性： 事务中的所有操作 要么全部完成，要么全部不完成
-- 一致性: 在事务开始和事务结束之后，数据库的完整性没有被破坏。写入的数据必须完全符合所有的预设规则，这包含资料的精确度，串联性以及后续数据库可以自发性的完成预定的工作
-- 隔离性：数据库允许多个并发事务同时对其数据进行读写和修改的能力，隔离性可以防止多个事务并发执行时由于交叉执行而导致的不一致。
-- 持久性：事务处理结束后，对数据的修改就是永久的，即便系统故障也不会丢失

BEGIN; 
INSERT INTO userDB.user(username,userpassword,register_date) VALUES('事件用户1','1234567889','2020 04-28 15:19:20');
INSERT INTO userDB.user(username,userpassword,register_date) VALUES('事件用户2','1234567889','2020 04-28 15:19:20');
COMMIT;

-- MySQL ALTER 命令
-- 当我们需要修改数据表名 或者 数据表字段的时候，需要 ALTER 命令
ALTER TABLE user MODIFY user_mobile INT(30)


-- MySQL 索引
-- 索引的建立对于 MySQL 的高效运行是很重要的，索引可以大大提高MySQL 的检索速度
-- 索引分单列索引和组合索引。单列索引一个索引只包含单个列，一个表有多个单列索引，但这不是组合索引。组合索引一个索引包含多个列



















