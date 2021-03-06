<?php if (!defined('THINK_PATH')) exit();?>共计： <?php echo ($count); ?> 条数据
<button class="sui-btn btn-lg search-limit-status" style="margin-left:80px;margin-bottom:5px;" data-search="status=0">未确认</button>
<button class="sui-btn btn-lg search-limit-status" style="margin-left:10px;margin-bottom:5px;"data-search="status=1">已确认</button>
<button class="sui-btn btn-lg search-limit-status" style="margin-left:10px;margin-bottom:5px;"data-search="status=2">已完成</button>
<button class="sui-btn btn-lg search-limit-status" style="margin-left:10px;margin-bottom:5px;"data-search="">全部</button>
<br>
<table class="sui-table table-bordered">
  <thead>
    <tr>
      <th>座位预定编号</th>
      <th>座位id</th>
      <th>顾客姓名</th>
      <th>顾客手机号</th>
      <th>顾客备注信息</th>
      <th>预订座位时是否下单</th>
      <th>预订时间</th>
      <th>状态</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if(is_array($data)): foreach($data as $key=>$data): ?><tr>
        <td>
          <?php echo ($data["id"]); ?>
        </td>
        <td>
          <?php echo ($data["seatid"]); ?>
        </td>
        <td>
          <?php echo ($data["gkname"]); ?>
        </td>

        <td>
          <?php echo ($data["gkphone"]); ?>
        </td>
        <td>
          <?php echo ($data["gkbz"]); ?>
        </td>
        <td class="isorder" data-orderid="<?php echo ($data["orderid"]); ?>">
          <?php echo ($data["orderid"]); ?>
        </td>
        <td>
          <?php echo ($data["ordertime"]); ?>
        </td>
        <td class="orderseat-status" data-status="<?php echo ($data["status"]); ?>">
          <?php echo ($data["status"]); ?>
        </td>
        <td>
          <button class="sui-btn btn-lg orderfood-btn" data-id="<?php echo ($data["id"]); ?>" data-seatid="<?php echo ($data["seatid"]); ?>" data-status="<?php echo ($data["status"]); ?>">点餐</button>
          <button class="sui-btn btn-lg gukeconfirm-btn" data-id="<?php echo ($data["id"]); ?>" data-status="<?php echo ($data["status"]); ?>">确认</button>
          <button class="sui-btn btn-lg orderseatok-btn" data-id="<?php echo ($data["id"]); ?>" data-status="<?php echo ($data["status"]); ?>" data-seatid="<?php echo ($data["seatid"]); ?>">完成</button>
          <button class="sui-btn btn-lg delorderseat-btn" data-id="<?php echo ($data["id"]); ?>" data-seatid="<?php echo ($data["seatid"]); ?>" data-status="<?php echo ($data["status"]); ?>">删除</button>
        </td>
      </tr><?php endforeach; endif; ?>
  </tbody>
</table>
<!-- Modal-->
<div id="gukeconfirm-modal" tabindex="-1" role="dialog" data-hasfoot="false" class="sui-modal hide fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-hidden="true" class="sui-close">×</button>
        <h4 id="myModalLabel" class="modal-title">添加备注信息</h4>
      </div>
      <div class="modal-body">
        <!-- 弹窗内容 -->
        <form class="sui-form form-horizontal sui-validate myform form-margin">
          <div class="control-group">
            <label for="mobile" class="control-label">备注</label>
            <div class="controls">
              <input type="text" class="input-large input-fat zl-phone" placeholder="备注" data-rules="required" name="people" value="" id="addgukeBz">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button"class="sui-btn btn-primary btn-large" id="addgukeBz-ok">确定</button>
        <button type="button" data-dismiss="modal" class="sui-btn btn-default btn-large">取消</button>
      </div>
    </div>
  </div>
</div>
<script>
$(".orderseat-status").each(function() {
  if ($(this).attr("data-status") == 0) {
    $(this).html("未确认");
  } else if ($(this).attr("data-status") == 1) {
    $(this).html("已确认");
    $(this).parent().find(".gukeconfirm-btn").addClass("disabled");
  } else {
    $(this).parent().find(".gukeconfirm-btn").addClass("disabled");
    $(this).parent().find(".orderseatok-btn").addClass("disabled");
    $(this).parent().find(".orderfood-btn").addClass("disabled");
    $(this).html("已完成");
  }
});
//
$(".isorder").each(function() {
  if ($(this).attr("data-orderid") == 0) {
    $(this).html("未下单");
  } else if ($(this).attr("data-orderid") == 1) {
    $(this).html("已下单");
  }
});
$("body").on("click",".see-order-btn",function(){

  var order = $(this).attr("data-orderid");

  if( order == 1 ){
     window.location.href="order.html";
  }
})
$("body").on("click",".orderfood-btn",function(){
  if($(this).hasClass("disabled")){
    return;
  }else{
    var id = $(this).attr("data-id");
    var seatid = $(this).attr("data-seatid");
    sessionStorage.setItem("orderseatid",id);
    sessionStorage.setItem("seatid",seatid);
    window.location.href = "http://www.wlx.com/food/index.php/orderfood.html";
  }
})
//确认订座
$("body").off("click", ".gukeconfirm-btn");
$("body").on("click", ".gukeconfirm-btn", function() {
  var that = $(this);
  var id = $(this).attr("data-id");
  var status = $(this).attr("data-status");
  if (status == 0) {
    $.ajax({
      url: "Dcan/statusOrderSeat",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        "id": id,
        "status": 1,
      },
      beforeSend: function() {
        $("#loading").show();
      },
      success: function(data) {
        that.attr("data-status") == 1;
        that.parent().parent().find(".orderseat-status").html("已确认");
        $("#loading").hide();
      }
    });

  } else if (status == 1) {
    return;
  }
});
//顾客结束
$("body").on("click", ".orderseatok-btn", function() {
  var that = $(this);
  var id = $(this).attr("data-id");
  var status = $(this).attr("data-status");
  var seatid = $(this).attr("data-seatid");

  if (status == 1) {
    $.ajax({
      url: "Dcan/statusOrderSeat",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        "id": id,
        "status": 2,
        "seatid": seatid,
      },
      beforeSend: function() {
        $("#loading").show();
      },
      success: function(data) {
        that.attr("data-status") == 2;
        that.parent().parent().find(".orderseat-status").html("已确认");
        $("#loading").hide();
        fleshAjax("#slist","Dcan/slist");
      }
    });

  } else if (status == 2) {
    return;
  }
});
//顾客订座删除

$("body").on("click", ".delorderseat-btn", function() {
  var that = $(this);
  var id = $(this).attr("data-id");
  var seatid = $(this).attr("data-seatid");

  $.ajax({
    url: "Dcan/delOrderSeat",
    type: "POST",
    dataType: "json",
    async: true,
    data: {
      "id": id,
      "seatid": seatid,
    },
    beforeSend: function() {
      $("#loading").show();
    },
    success: function(data) {
      that.parent().parent().remove();
      $("#loading").hide();
      fleshAjax("#slist","Dcan/slist");
    }
  });
});
$("body").on("click",".search-limit-status",function(){
  var search = $(this).attr("data-search");
  $.ajax({
        url: "Dcan/slist",
        type: "GET",
        data: {
          "search": search,
        },
        success:function(data) {
          $("#slist").html(data);
        },
        error: function() {

        }
  })
})
</script>