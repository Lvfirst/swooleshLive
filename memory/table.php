<?php 
// 创建内存表
$table=new swoole\table(1024);

// 设定列，类似创建字段
// 常用常量 TYPE_INT TYPE_STRING TYPE_FLOAT

$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,4);
$table->create();

$table->set('lvzhiwei1',['id'=>'1','name'=>'lvzhiwei','age'=>'123']);
$table->set('lvzhiwei2',['id'=>'2','name'=>'lvl','age'=>'21']);
$table->incr('lvzhiwei2','age',3);
$r=$table->get('lvzhiwei2');

var_dump($r);

