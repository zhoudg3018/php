@echo off
chcp 936
echo 正在初始化环境变量...
echo.

:: 对于路径中有空格的路径字符串，需要加上英文双引号包裹！否则就会出现命令错误！
set POSTGRESQL_DIR=D:\"Program Files"\PostgreSQL\12\bin\

:: 数据库管理系统名
set DBMS_NAME=PostgreSQL
set DBBAK_DIR=%1
set DB_NAME=%2
set USER=%3
:: PostgreSQL的pg_dump命令行工具没有带密码的参数，但是可以设置一个PGPASSWORD的环境变量来提供密码
set PGPASSWORD=%4
@echo off
echo 正在备份数据库...
echo.
:: 以下是获得当前系统时间的命令，e.g. 20120503101305
:: 年
set myyy=%date:~0,4%
:: 月
set mymm=%date:~5,2%
:: 日
set mydd=%date:~8,2%

echo %myyy% %mymm% %mydd%

set /a TODAY=%date:~0,4%%date:~5,2%%date:~8,2%
set _TIME=%time:~0,8%
::echo %_TIME%
set CURRENTTIME=%_time::=%
set CURRENTTIME=%CURRENTTIME: =0%
set MYDATETIME=%TODAY%%CURRENTTIME%
::echo %MYDATETIME%
::g_dump命令将具体数据库导出为.bak文件
chcp 65001
call %POSTGRESQL_DIR%pg_dump.exe -h localhost -p 5432 -U %USER% %DB_NAME% > %DBBAK_DIR%\%DB_NAME%_%MYDATETIME%_%DBMS_NAME%.bak
::echo %POSTGRESQL_DIR%pg_dump.exe -h localhost -p 5432 -U %USER% %DB_NAME% > %DBBAK_DIR%\%DB_NAME%_%MYDATETIME%_%DBMS_NAME%.bak
if %errorlevel% == 0 (
　echo 0
) else (
　echo 1
)
exit
