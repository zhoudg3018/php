<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    header("Content-Type:text/html;charset=utf-8");
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    $订单流水号 = isset($_GET['id']) ? $_GET['id'] : 0;
    $s_page= isset($_GET['sId']) ? $_GET['sId'] : 0;
    $d_page = isset($_GET['eId']) ? $_GET['eId'] : 0;
    $标签颜色 = isset($_GET['bqys']) ? $_GET['bqys'] : '';
    $query_str="";
    if(!empty($标签颜色)){
      $query_str=" AND 标签颜色 = '$标签颜色'";

    }
    $query_all = "select count(*) from 详单表
                where 1=1".$query_str."
                AND 订单流水号 = '$订单流水号'";                   
    $result = $pdo->prepare($query_all);
    $result->execute();
    $total = $result->fetchColumn();
      if( $total<50){
        $d_page=$total;

      }else{
        $d_page=50;
      }

    /** 筛选数据 **/
    $query = "select * from 详单表
    where 1=1".$query_str."
    AND 订单流水号= '$订单流水号' 
    ORDER BY cast(详单编号 AS INTEGER) ASC  limit {$d_page} offset {$s_page} ";
    $res2 = $pdo->prepare($query);
    $res2->execute();

    ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="Scripts/jquery-1.4.1.min.js" type="text/javascript"></script>
<script>
 $(document).ready(function(){
	
    //获取打印机列表 begin
    $("#btn_getprintlist").click(function(){
      var ip = $("#ip").val();
      var port = $("#port").val();
  
      if(ip==""||port==""){alert("ip,port不能为空"); return false;}
  
      //$.post("http://127.0.0.1:12345/getprinterlist",
      $.post("http://"+ip+":"+port+"/getprinterlist",
      {
        //method:"Donald Duck",
        //city:"Duckburg"
      },
      function(data){
          data = decodeURIComponent(data);
          //alert(data);
          
          
          if(data==""){
              alert("连接HttpPrinter失败");
          }else{
              //alert(data);
              var obj = JSON.parse(data);
              //alert(obj.status);
              
              $("#PrinterS").empty();
              
              if(obj.status=="ok"){
                  alert("获取成功");
                   
                  //
                  
                  //$("#PrinterS").append("<option value='"+obj.sites[1].name +"'>"+obj.sites[1].name +"</option>"); 
                  for(var o in obj.data){  
                      //alert(o);  
                      //alert(obj.data[o]);  
                      var printname = obj.data[o].name;
                      //alert(printname);
                      $("#PrinterS").append("<option value='"+printname +"'>"+printname +"</option>"); 
                   }  
                   
              }else{
                  alert("获取失败:"+obj.data);
              }
              //console.log(data);
              
          }
      });
    });
    //获取打印机列表 end

    //打印条形码 begin
  $("#btn_barcode").click(function(){
    var ip = $("#ip").val();
    var port = $("#port").val();
    var len=$('[id=dyId]').length;
    var str='[';
    for(var i=1;i<=len;i++){
       str +='{"ddbh":"'+$("#ddbh"+i).text();
       str +='","xmmc":"'+$("#xmmc"+i).text();
       str +='","cpmc":"'+$("#cpmc"+i).text();
       str +='","ccgg":"'+$("#ccgg"+i).text();
       str +='","wz":"'+$("#wz"+i).text();
       str +='","ys":"'+$("#ys"+i).text();
       str +='","sl":"'+$("#sl"+i).text();
       str +='"},';
    }
    str+=']';


	if(ip==""||port==""){alert("ip,port不能为空"); return false;}

    //$.post("http://127.0.0.1:12345/getprinterlist",
    $.post("http://"+ip+":"+port+"/printreport",
    {
	  "ReportType": "gridreport",     /*报表类型 gridreport fastreport 为空 将默认为gridreport  */	
	  "ReportName": "标签打印.grf",     /*报表文件名 条形码 */
	  "ReportVersion": 1,              /*可选。报表版本, 为空则默认1  如果本地报表的版本过低 将从 ReportUrl 地址进行下载更新*/
	  //"ReportUrl": "http://111.67.202.157:9099/report/barcode.grf",                  /*可选。为空 将不更新本地报表 , 如果本地报表不存在可以从该地址自动下载*/
	  "ReportUrl": "",                  /*可选。为空 将不更新本地报表 , 如果本地报表不存在可以从该地址自动下载*/
	  "Copies": 1,                  /*可选。打印份数，支持指定打印份数。默认1份,如果为零,不打印,只返回报表生成的pdf,jpg等文件*/
	  "PrinterName": $("#PrinterS option:selected").text(),      /*可选。指定打印机，为空的话 使用默认打印机, 请在 控制面板 -> 设备和打印机 中查看您的打印机的名称 */
	  "PrintOffsetX": 0,                 /*可选。打印右偏移，单位厘米。报表的水平方向上的偏移量，向右为正，向左为负。*/
	  "PrintOffsetY": 0,                /*可选。打印下偏移，单位厘米。 报表的垂直方向上的偏移量，向下为正，向上为负。*/
	  "token": "aa",      /*可选。只要token值在列表中 方可打印*/
	  "taskId": "1234567",     /*可选。多个打印任务同时打印时，根据该id确定返回的是哪个打印任务。 */ 
	  "exportfilename": "",      /*可选。自定义 导出 文件名称 为空 或者 自定义名称 如 test */ 
	  "exportfiletype": "",      /*可选。自定义 导出 文件格式 为空 或者 自定义名称 如 pdf  */ 

	  "Field": '['  ///*字段， type ftBlob (base64格式) ,ftString ftInteger ftBoolean, ftFloat, ftCurrency,ftDateTime,  size (ftString 设置为实际长度,其他的设置为0,例如 ftInteger ftBlob 等设置为0 ) 
		+'{"type": "ftString", "name": "ddbh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xmmc","size": 255,"required": false},'
    +'{"type": "ftString", "name": "cpmc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ccgg","size": 255,"required": false},'
    +'{"type": "ftString", "name": "wz","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ys","size": 255,"required": false},'
    +'{"type": "ftString", "name": "sl","size": 255,"required": true},'
		+']',
		
	  "Data": str, ///*数据行      
    },
    function(data){
		data = decodeURIComponent(data);
		//alert(data);
		
		
		if(data==""){
			alert("连接HttpPrinter失败");
		}else{
			//alert(data);
			var obj = JSON.parse(data);
			//alert(obj.status);
		
			if(obj.status=="ok"){
				alert("打印成功");
				 
				//
				
			
				 
			}else{
				alert("打印失败:"+obj.data);
			}
			//console.log(data);
			
		}
    });
  });
  //打印条形码 end	
  });

   //单个打印条形码 begin
   function function_oneDy(num){
      var ip = $("#ip").val();
      var port = $("#port").val();
      var i=num;
      var str='[';
      str +='{"ddbh":"'+$("#ddbh"+i).text();
      str +='","xmmc":"'+$("#xmmc"+i).text();
      str +='","cpmc":"'+$("#cpmc"+i).text();
      str +='","ccgg":"'+$("#ccgg"+i).text();
      str +='","wz":"'+$("#wz"+i).text();
      str +='","ys":"'+$("#ys"+i).text();
      str +='","sl":"'+$("#sl"+i).text();
      str +='"},';
      str+=']';


  if(ip==""||port==""){alert("ip,port不能为空"); return false;}

    //$.post("http://127.0.0.1:12345/getprinterlist",
    $.post("http://"+ip+":"+port+"/printreport",
    {
    "ReportType": "gridreport",     /*报表类型 gridreport fastreport 为空 将默认为gridreport  */	
    "ReportName": "标签打印.grf",     /*报表文件名 条形码 */
    "ReportVersion": 1,              /*可选。报表版本, 为空则默认1  如果本地报表的版本过低 将从 ReportUrl 地址进行下载更新*/
    //"ReportUrl": "http://111.67.202.157:9099/report/barcode.grf",                  /*可选。为空 将不更新本地报表 , 如果本地报表不存在可以从该地址自动下载*/
    "ReportUrl": "",                  /*可选。为空 将不更新本地报表 , 如果本地报表不存在可以从该地址自动下载*/
    "Copies": 1,                  /*可选。打印份数，支持指定打印份数。默认1份,如果为零,不打印,只返回报表生成的pdf,jpg等文件*/
    "PrinterName": $("#PrinterS option:selected").text(),      /*可选。指定打印机，为空的话 使用默认打印机, 请在 控制面板 -> 设备和打印机 中查看您的打印机的名称 */
    "PrintOffsetX": 0,                 /*可选。打印右偏移，单位厘米。报表的水平方向上的偏移量，向右为正，向左为负。*/
    "PrintOffsetY": 0,                /*可选。打印下偏移，单位厘米。 报表的垂直方向上的偏移量，向下为正，向上为负。*/
    "token": "aa",      /*可选。只要token值在列表中 方可打印*/
    "taskId": "1234567",     /*可选。多个打印任务同时打印时，根据该id确定返回的是哪个打印任务。 */ 
    "exportfilename": "",      /*可选。自定义 导出 文件名称 为空 或者 自定义名称 如 test */ 
    "exportfiletype": "",      /*可选。自定义 导出 文件格式 为空 或者 自定义名称 如 pdf  */ 

    "Field": '['  ///*字段， type ftBlob (base64格式) ,ftString ftInteger ftBoolean, ftFloat, ftCurrency,ftDateTime,  size (ftString 设置为实际长度,其他的设置为0,例如 ftInteger ftBlob 等设置为0 ) 
    +'{"type": "ftString", "name": "ddbh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xmmc","size": 255,"required": false},'
    +'{"type": "ftString", "name": "cpmc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ccgg","size": 255,"required": false},'
    +'{"type": "ftString", "name": "wz","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ys","size": 255,"required": false},'
    +'{"type": "ftString", "name": "sl","size": 255,"required": true},'
    +']',
    
    "Data": str, ///*数据行      
    },
    function(data){
    data = decodeURIComponent(data);
    //alert(data);
    
    
    if(data==""){
      alert("连接HttpPrinter失败");
    }else{
      //alert(data);
      var obj = JSON.parse(data);
      //alert(obj.status);
    
      if(obj.status=="ok"){
       // alert("打印成功");
        
        //
        
      
        
      }else{
        alert("打印失败:"+obj.data);
      }
      //console.log(data);
      
    }
    });
  }
  //打印条形码 end
</script>
</head>
<body>
  <input type="text" name="ip" id="ip" value="127.0.0.1" /><br>
   <input type="text" name="port" id="port" value="12345"/><br>
  <div class="panel-body">
    <tbody>
    <div align="center" >
      <?php
        if($total <= 50){
      ?>
        <a href="dyym.php?id=<?php echo $订单流水号 ?>&sId=0&eId=<?php echo $total ?>&bqys=<?php echo $标签颜色 ?> "> 显示1-<?php echo $total ?>页 </a>
      <?php
        }
        if($total>50){
            ?>
            <a href="dyym.php?id=<?php echo $订单流水号 ?>&sId=0&eId=50&bqys=<?php echo $标签颜色 ?>" > 显示1-50 页 </a>
          <?php
            $i=0;
            $Snum=0;
            $Enum=50;
            for($i=1;$i<intval($total/50);$i++){
              $Snum+=50;
              $Enum+=50;
            ?>
              <a href="dyym.php?id=<?php echo $订单流水号 ?>&sId=<?php echo $Snum ?>&eId=<?php echo $Enum ?>&bqys=<?php echo $标签颜色 ?>" > 显示<?php echo $Snum+1 ?>-<?php echo $Enum ?>页</a>    
            <?php  
            }

               if( $total%50 !=0){
                $Snum+=50;
                $Enum=$Enum+$total%50;
              ?>
              <a href="dyym.php?id=<?php echo $订单流水号 ?>&sId=<?php echo $Snum ?>&eId=<?php echo $Enum ?>&bqys=<?php echo $标签颜色 ?>" > 显示<?php echo $Snum+1 ?>-<?php echo $Enum ?>页</a>    
              <?php 
            }

        }
      ?>
      </br>
     
      </br>
        <button id="btn_barcode">打印条形码</button>
        <br>
    </div>  
    <br>
    <?php 
        if($res2->rowCount()>0){
          $number=0;
        while($row2 = $res2->fetch(PDO::FETCH_ASSOC)){
          $number=$number+1;
    ?>
    <div  align="center" >
      <button onclick="function_oneDy(<?php echo $number ?>)">打印 (<?php echo $row2['详单编号'] ?>)</button>
      <br>
        </div>
    <br>
    <table id="dyId" border ="1px solid #ccc" cellspacing="0" cellpadding="0" align="center" width="600px" height="200px" style="text-align:center;vertical-align:middle;">
      <tr>
      <td>订单编号</td> <td colspan="2" Id="ddbh<?php echo $number ?>"><?php echo $row2['订单编号'] ?></td>    <td Id="xmmc<?php echo $number ?>"><?php echo $row2['项目名称'] ?></td>  
      </tr>

      <tr>
        <td>项目名称</td> <td Id="xmmc<?php echo $number ?>" ><?php echo $row2['项目名称'] ?></td>     <td>位置</td>  <td Id="wz<?php echo $number ?>" ><?php echo $row2['位置'] ?></td>
      </tr>
      <tr>
        <td>产品名称</td> <td Id="cpmc<?php echo $number ?>" ><?php 
        $tmparray = explode('套',$row2['类型']);
        if(count($tmparray)>1){

          echo $row2['类型'];
        }else{
          echo $row2['产品名称'];
        }
         ?></td>     <td>颜色</td>  <td Id="ys<?php echo $number ?>" ><?php echo $row2['颜色'] ?></td>
      </tr>
      <tr>
      <td>尺寸规格</td> <td Id="ccgg<?php echo $number ?>"  ><?php echo $row2['尺寸规格'] ?></td>     <td>开向</td>  <td Id="sl<?php echo $number ?>" ><?php echo $row2['开向'] ?></td>
      </tr>
    </table>     
    </br>
    </br>
  <?php 
    } 
  }  
  ?> 
  </tbody>
  <div>          
</body>
</html>
