<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">
				<?php $this->_compileInclude('menu'); ?>
			</div>
			<div class="col-xs-10" id="datacontent">
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
					<div class="col-xs-12">
						<ol class="breadcrumb">
							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a></li>
							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-simulation&page=<?php echo $this->tpl_var['page']; ?><?php echo $this->tpl_var['u']; ?>">试卷管理</a></li>
							<li class="active">帮助中心</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<div class="col-xs-12">
						<h5 class="title">
							试题批量导入
						</h5>
						<p>
							<div class="mn" id="main">
<div class="bm bml pbn">
<span class="y"></span>


<style type="text/css">
<!--
.hbox {padding:10px;background:#F5F7FA;border:1px solid #e5e5e5}
.precode {padding:10px;background:#F5F7FA;border:1px solid #e5e5e5;font-size:12px;margin-top:2px}
.xi1, .onerror {
    color: #F26C4F;
}
-->
</style>

<div class="bm_c cl xs2">
<pre>    本程序支持试题的批量导入, 导入前需将试题按一定格式整理, 试题的导入格式是有一定规律, 与平常试卷相似。

3.1.1 每个试题由4~5个部分组成：题目、选项、图片、答案、解析，其中题目和选项没有指令:
<p class="xs1 hbox">&lt;题目&gt;       ----  必选,在填空题的题目中必须包含下划线“____”或括号“(    )”，其中下划线至少4个，括号中的空格至
                   少4个,在问答题的题目中不能还有这些, 题目内容可换行, 
&lt;选项&gt;       ----  可选,这个是用于单选择题和多选择题,以A-Z大写单字母开头,该字母后跟一个冒号或点号,一行一个选项
图片:&lt;网址&gt;  ----  可选,导入图片指令,必须以“<em class="xi1">图片:</em>”开头(含冒号),后跟图片网址, 不可换行
答案:&lt;内容&gt;  ----  必选,导入答案指令,必须以“<em class="xi1">答案:</em>”开头(含冒号),后跟答案内容, 内容可换行
解析:&lt;内容&gt;  ----  可选,导入解析指令,必须以“<em class="xi1">解析:</em>”开头(含冒号),后跟解析内容, 内容可换行</p>
3.1.2 为了能够一次性导入多个试题组，这里又引入了一个题组指令(或题干,题干可看作是题组的别名)，如下：
<p class="xs1 hbox">题组[题干]:&lt;题组名称&gt;  ----  可选,如果查询到当前试卷中含有该题组,则将该题组切换为当前状态,若无,则创建题组并切换
&lt;题组的内容说明&gt;       ----  可选,紧接下一行或多行为该题组的说明内容</p>
3.1.3 每个试题之间必须至少有一个空行

3.1.4 下面以具体案例来解释<em class="xi1">(红色为注释)</em>:
<p class="xs1 hbox" style="background:url(app/core/styles/images/help_ex.png) 92px 13px no-repeat ">1.AutoCAD无法实现类似Word的文字查找或者替换功能
答案:错误
图片:static/image/common/logo.png
解析:可这样操错打开查找和替换窗口: 菜单--&gt;编辑--&gt;查找

2.搜索引擎是一个用来搜索查询世界各地 Internet 网路资源的
A:网页
B:域名网址
C:Web服务器
D:邮箱
答案:C
 
3.下面那些是图片类型的扩展名?
A:gif
B:jpg
C:bmp
D:exe
答案:ABC

4.下面哪些不是图片类型的扩展名?
A:gif
B:jpg
C:bmp
D:exe
答案:D#

5.我国的首都是____, 我国有____个少数民族.
答案:北京 56
 
6.什么是微商?并简要描述其优点
答案:
    微商目前尚无统一认知的定义。一般是指以“个人”为单位的、利用web3.0时代所衍生的载体渠道，将传统方式与互联网相结合，不存在区域限制，且可移动性地实现销售渠道新突破的小型个体行为。简单的说，微商就是资源整合。
    投入小、门槛低、传播范围广、足不出户便可推广与销售、只需个体行为等特点，满足了大多数有意愿自己做点生意，却不敢轻易尝试实体性创业，亦没有太多资本投入，也不熟悉企业运营的个体。
</p>	
3.1.5 在整理试题的时候,请务必防止试题的内容与指令冲突							
</pre>
</div>
</div>
</div>
						</p>
					</div>
					<div class="col-xs-12">
						<h5 class="title">
							使用帮助
						</h5>
						<p>
							帮助论坛：<a href="http://www.yun386.com/bbs/">http://www.yun386.com/bbs/</a>
						</p>
						<p>
							
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->_compileInclude('footer'); ?>
</body>
</html>