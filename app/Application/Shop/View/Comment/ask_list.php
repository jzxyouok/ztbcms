<include file="Public/min-header"/>
<div class="wrapper">
    <include file="Public/breadcrumb"/>
    <section class="content">
        <div class="row">
           <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<i class="fa fa-list"></i>&nbsp;商品咨询列表
                    </h3>
                </div>
                <div class="panel-body">
                <nav class="navbar navbar-default">	     
			        <div class="collapse navbar-collapse">
			          <form action="{:U('Comment/ask_list')}" id="search-form2" class="navbar-form form-inline" role="search" method="post">
			            <div class="form-group">
			              	<input type="text" class="form-control" name="content" placeholder="搜索评论内容">
			            </div>
                          <div class="form-group">
                              <input type="text" class="form-control" name="nickname" placeholder="搜索用户">
                          </div>
                          <button type="button" onclick="ajax_get_table('search-form2',1)" class="btn btn-info"><i class="fa fa-search"></i> 筛选</button>
			          </form>		
			      </div>
    			</nav>
                    <div id="ajax_return">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                    </td>
                                    <td class="text-center">
                                        用户
                                    </td>
                                    <td class="text-center">
                                        咨询内容
                                    </td>
                                    <td class="text-center">
                                        商品
                                    </td>
                                    <td class="text-center">
                                        显示
                                    </td>
                                    <td class="text-center">
                                        咨询时间
                                    </td>
                                    <td class="text-center">
                                        ip地址
                                    </td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>

                                <volist name="comment_list" id="list">
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected[]" value="{$list.comment_id}">
                                        </td>
                                        <td class="text-center">{$list.username}</td>
                                        <td class="text-left">{$list.content}</td>
                                        <td class="text-left"><a target="_blank" href="{:U('Home/Goods/goodsInfo',array('id'=>$list[goods_id]))}">{$goods_list[$list[goods_id]]}</a></td>
                                        <td class="text-center">
                                            <img width="20" height="20" src="__PUBLIC__/images/<if condition='$list[is_show] eq 1'>yes.png<else />cancel.png</if>" onclick="changeTableVal('goods_consult','id','{$list.id}','is_show',this)"/>
                                        </td>
                                        <td class="text-center">{$list.add_time|date='Y-m-d H:i:s',###}</td>
                                        <td class="text-center">{$list.ip_address}</td>

                                        <td class="text-center">
                                            <a href="{:U('Comment/consult_info',array('id'=>$list[id]))}" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-eye"></i></a>
                                            <a href="javascript:void(0);" data-href="{:U('Comment/ask_handle',array('id'=>$list[id]))}" onclick="del('{$list[id]}',this)" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                </volist>

                                </tbody>
                            </table>
                            <select name="operate" id="operate">
                                <option value="0">操作选择</option>
                                <option value="show">显示</option>
                                <option value="hide">隐藏</option>
                                <option value="del">删除</option>
                            </select>
                            <button onclick="op()">确定</button>
                            <form id="op" action="{:U('Comment/op')}" method="post">
                                <input type="hidden" name="selected">
                                <input type="hidden" name="type">
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-left"></div>
                            <div class="col-sm-6 text-right">{$page}</div>
                        </div>
                    </div>
                </div>
            </div>
           </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    // 删除操作
    function del(id,t)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        location.href = $(t).data('href');
    }

    function op(){
        //获取操作
        var op_type = $('#operate').find('option:selected').val();
        if(op_type == 0){
			layer.msg('请选择操作', {icon: 1,time: 1000});   //alert('请选择操作');
            return;
        }
        //获取选择的id
        var selected = $('input[name*="selected"]:checked');
        var selected_id = [];
        if(selected.length < 1){

			layer.msg('请选择项目', {icon: 1,time: 1000}); //            alert('请选择项目');
            return;
        }
        $(selected).each(function(){
            selected_id.push($(this).val());
        })
        $('#op').find('input[name="selected"]').val(selected_id);
        $('#op').find('input[name="type"]').val(op_type);
        $('#op').submit();
    }

    $(document).ready(function(){
        ajax_get_table('search-form2',1);
    });


    // ajax 抓取页面
    function ajax_get_table(tab,page){
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type : "POST",
            url:"{:U('Comment/ajax_ask_list')}&p="+page,//+tab,
            data : $('#'+tab).serialize(),// 你的formid
            success: function(data){
                $("#ajax_return").html('');
                $("#ajax_return").append(data);
            }
        });
    }

</script>

</body>
</html>