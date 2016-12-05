<include file="Public/min-header"/>
<div class="wrapper">
  <include file="Public/breadcrumb"/>
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 拆分订单</h3>
                </div>
                <div class="panel-body">
                    <!--表单数据-->
                    <form method="post" action="{:U('Shop/Order/split_order')}" id="split_order">
                        <div class="tab-pane">
                            <table class="table table-bordered">
                                <tbody>
                                <tr><td>费用信息:</td>
                                	<td>
                                		<div class="col-xs-9">
                                		<input type="hidden" name="order_id" value="{$order.order_id}">
                                		 商品总价：{$order.goods_price}+运费:{$order.shipping_price}-积分：{$order.integral}-优惠:{$order.discount}                                        
                                		</div>
                                	</td>
                                </tr>
                                <tr>
                                    <td>收货人:</td>
                                    <td>
                                    <div class="form-group">
	                                    <div class="col-xs-2"> {$order.consignee}</div>
                                        <div class="col-xs-1">手机：</div>
                                        <div class="col-xs-2">{$order.mobile}</div>
                                        <div class="col-xs-3"><p class="text-warning">温馨提示：原单商品不可全部移除</p></div>
                                        <div class="col-xs-2 pull-right">
                                        	<button type="button" class="btn btn-default pull-left" onclick="window.location.reload();">重置</button>
                                        	<button type="button" class="btn btn-primary pull-right" onclick="add_split()">添加拆单</button>
                                        </div>
                                    </div>    
                                    </td>
                                </tr>                                                                                      
                                <tr>
                                    <td>原单商品列表:</td>
                                    <td id="origin" style="border:2px orange solid;"> 
                                       <div class="form-group">
                                       		<div class="col-xs-10">
	                                       		<table class="table table-bordered">
	                                       			<thead>
	                                       			<tr>
										                <td class="text-left">商品名称</td>
										                <td class="text-left">规格</td>
										                <td class="text-left">价格</td>
										                <td class="text-left">原购数</td>								                
										                <td class="text-left">数量</td>
										                <td class="text-left">操作</td>
										            </tr>
										            </thead>
										            <tbody>
										            <foreach name="orderGoods" item="vo">
										            	<tr>
										                <td class="text-left">{$vo.goods_name}</td>            
										                <td class="text-left">{$vo.spec_key_name}</td>
										                <td class="text-left">{$vo.goods_price}</td>
										                <td class="text-left">{$vo.goods_num}</td>
										                <td class="text-left">
										                	<input type="text" name="old_goods[{$vo.rec_id}]" rel="{$vo.rec_id}" class="input-sm" style="width:40px;" value="{$vo.goods_num}">
										               	</td>
										                <td class="text-left">
										                	<a href="javascript:void(0)" onclick="javascript:$(this).parent().parent().remove()">移除</a>
										                </td>
										           		</tr>
										           </foreach>
										           </tbody>
	                                       		</table>
                                       	   </div>
                                       </div>                                       
                                    </td>
                                </tr>                               
                                <tr id="last_tr">
                                    <td>管理员备注:</td>
                                    <td>
                                    <div class="form-group ">
	                                    <div class="col-xs-4">
                                        	<textarea style="width:450px; height:100px;" name="admin_note">{$order.admin_note|htmlspecialchars_decode}</textarea>
                                        </div>
                                    </div>    
                                    </td>
                                </tr>                                  
                             </tbody>
                          </table>
                          <div class="col-xs-12">
                          	<div class="pull-left">
                          		<p class="text-danger" id="error_log"></p>
                          	</div>
	                        <div class="pull-right">
		                        <button class="btn btn-info" type="button" onclick="checkSubmit()">
		                            <i class="ace-icon fa fa-check bigger-110"></i>保存
		                        </button>
	                        </div>
                        </div>
                      </div>
                    </form> 
                </div>
            </div>
        </div> 
    </section>
</div>
<script>
var no = 1;
$(function(){
	add_split();
});

function add_split(){
	var new_order = '';
	new_order += '<tr id="new_'+no+'" class="new_split"><td>新单商品列表:</td><td>'                      
	new_order += $('#origin').html();
	new_order += '<div class="col-xs-1 pull-right"><button type="button" class="btn btn-danger pull-right" onclick="$(this).parent().parent().parent().remove();">删除</button></div>'
	new_order += '</td></tr>';
	$('#last_tr').before(new_order);
	$('#new_'+no+' .input-sm').each(function(i,o){
		var name = $(this).attr('name');
		$(this).attr('name',no+'_'+name);
	});
	no++;
}

var b = {$goods_num_arr};

function checkSubmit(){
	var a = [],g = [];
	$('input[name*=old_goods]').each(function(i,o){
		var rec_id = $(o).attr('rel');
		if(!a[rec_id]){
			a[rec_id] = 0;
		}
		a[rec_id] = a[rec_id] + parseInt($(o).val());
	});
	
	$('#origin .input-sm').each(function(){
		g.push($(this).val());
	});
	if($('.new_split').length == 0){
		$('#error_log').empty().html('请至少拆分一单');
		return false;
	}
	if(g.length == 0){
		$('#error_log').empty().html('原单商品不可全部移除');
		return false;
	}
	
	for(var k in b){
		if(a[k] > parseInt(b[k]['goods_num'])){
			var lt = a[k] - parseInt(b[k]['goods_num']);
			$('#error_log').empty().html(b[k]['goods_name']+',数量大于原商单购买数'+lt+'件');
			return false;
		}
		if(a[k] < parseInt(b[k]['goods_num'])){
			var lt = parseInt(b[k]['goods_num']) - a[k];
			$('#error_log').empty().html(b[k]['goods_name']+',数量少于原商单购买数'+lt+'件');
			return false;
		}else{
			$('#error_log').empty();
		}
	}
	
	$('#split_order').submit();
}
</script>
</body>
</html>