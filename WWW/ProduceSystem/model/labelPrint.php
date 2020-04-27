<?php
     include ("../model/pdo_config.php");
     include '../phpqrcode/phpqrcode.php';
     header("Content-type:image/png");
     header("Content-Type:text/html;charset=utf-8");
    //获取变量
    $branchNo = isset($_GET['id']) ? $_GET['id'] :'';
    $detailsNo = isset($_GET['detailsNo']) ? $_GET['detailsNo'] :'';
    $produceNo= isset($_GET['produceNo']) ? $_GET['produceNo'] :'';
    $customer = isset($_GET['customer']) ? $_GET['customer'] : '';
    //分页打印
    $s_page= isset($_GET['sId']) ? $_GET['sId'] : 0;
    $d_page = isset($_GET['eId']) ? $_GET['eId'] : 0;
    //查询语句拼接
    $query_str="";
    if(!empty($branchNo)){
      $query_str=$query_str." AND  批次号 = '$branchNo'";

    }
    if(!empty($detailsNo)){
      $query_str=$query_str." AND  详单编号 like '$detailsNo%'";

    }
    if(!empty($produceNo)){
      $query_str=$query_str." AND  生产单号 like '$produceNo%'";
    }

    if(!empty($customer)){
      $query_str=$query_str." AND  客户姓名 like '$customer%'";

    }
    $query_all = "select count(详单编号) from 详单表
                where 1=1 ".$query_str; 
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
    order by 序号,详单编号 ASC limit {$d_page} offset {$s_page} ";
    $res2 = $pdo->prepare($query);
    $res2->execute();
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layuiAdmin 网站用户 iframe 框</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="../static/layui/css/layui.css" media="all">
</head>
<body>
<script src="../static/layui/layui.js" ></script>  
  <script src="../static/jquery-1.4.1.min.js" ></script>  
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
      str +='{"scdh":"'+$("#scdh"+i).text().replace(/\s+/g, "");
      str +='","xdbh":"'+$("#xdbh"+i).text().replace(/\s+/g, "");
      str +='","cpcc":"'+$("#cpcc"+i).text().replace(/\s+/g, "");
      str +='","fblx":"'+$("#fblx"+i).text().replace(/\s+/g, "");
      str +='","xh":"'+$("#xh"+i).text().replace(/\s+/g, "");
      str +='","yqys":"'+$("#yqys"+i).text().replace(/\s+/g, "");
      str +='","msk":"'+$("#msk"+i).text().replace(/\s+/g, "");
      str +='","sz":"'+$("#sz"+i).text().replace(/\s+/g, "");
      str +='","hyc":"'+$("#hyc"+i).text().replace(/\s+/g, "");
      str +='","fblxCode":"'+$("#fblxCode"+i).text().replace(/\s+/g, "");
      str +='","ccCode":"'+$("#ccCode"+i).text().replace(/\s+/g, "");
      str +='","ID":"'+$("#ID"+i).text().replace(/\s+/g, "");
      str +='","xdtm":"'+$("#xdtm"+i).text().replace(/\s+/g, "");
      str +='","qglxCode":"'+$("#qglxCode"+i).text().replace(/\s+/g, "");
      str +='"},';
    }
    str+=']';


	if(ip==""||port==""){alert("ip,port不能为空"); return false;}

    //$.post("http://127.0.0.1:12345/getprinterlist",
    $.post("http://"+ip+":"+port+"/printreport",
    {
	  "ReportType": "gridreport",     /*报表类型 gridreport fastreport 为空 将默认为gridreport  */	
	  "ReportName": "生产标签打印.grf",     /*报表文件名 条形码 */
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
    +'{"type": "ftString", "name": "scdh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xdbh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "cpcc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "fblx","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "yqys","size": 255,"required": true},'
    +'{"type": "ftString", "name": "msk","size": 255,"required": true},'
    +'{"type": "ftString", "name": "sz","size": 255,"required": true},'
    +'{"type": "ftString", "name": "hyc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ccCode","size": 255,"required": true},'
    +'{"type": "ftString", "name": "fblxCode","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ID","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xdtm","size": 255,"required": true},'
    +'{"type": "ftString", "name": "qglxCode","size": 255,"required": true},'
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
			//	alert("打印成功");
				 
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
      str +='{"scdh":"'+$("#scdh"+i).text().replace(/\s+/g, "");
      str +='","xdbh":"'+$("#xdbh"+i).text().replace(/\s+/g, "");
      str +='","cpcc":"'+$("#cpcc"+i).text().replace(/\s+/g, "");
      str +='","fblx":"'+$("#fblx"+i).text().replace(/\s+/g, "");
      str +='","xh":"'+$("#xh"+i).text().replace(/\s+/g, "");
      str +='","yqys":"'+$("#yqys"+i).text().replace(/\s+/g, "");
      str +='","msk":"'+$("#msk"+i).text().replace(/\s+/g, "");
      str +='","sz":"'+$("#sz"+i).text().replace(/\s+/g, "");
      str +='","hyc":"'+$("#hyc"+i).text().replace(/\s+/g, "");
      str +='","fblxCode":"'+$("#fblxCode"+i).text().replace(/\s+/g, "");
      str +='","ccCode":"'+$("#ccCode"+i).text().replace(/\s+/g, "");
      str +='","ID":"'+$("#ID"+i).text().replace(/\s+/g, "");
      str +='","xdtm":"'+$("#xdtm"+i).text().replace(/\s+/g, "");
      str +='","qglxCode":"'+$("#qglxCode"+i).text().replace(/\s+/g, "");
      str +='"},';
      str+=']';


  if(ip==""||port==""){alert("ip,port不能为空"); return false;}

    //$.post("http://127.0.0.1:12345/getprinterlist",
    $.post("http://"+ip+":"+port+"/printreport",
    {
    "ReportType": "gridreport",     /*报表类型 gridreport fastreport 为空 将默认为gridreport  */	
    "ReportName": "生产标签打印.grf",     /*报表文件名 条形码 */
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
    +'{"type": "ftString", "name": "scdh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xdbh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "cpcc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "fblx","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xh","size": 255,"required": true},'
    +'{"type": "ftString", "name": "yqys","size": 255,"required": true},'
    +'{"type": "ftString", "name": "msk","size": 255,"required": true},'
    +'{"type": "ftString", "name": "sz","size": 255,"required": true},'
    +'{"type": "ftString", "name": "hyc","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ccCode","size": 255,"required": true},'
    +'{"type": "ftString", "name": "fblxCode","size": 255,"required": true},'
    +'{"type": "ftString", "name": "ID","size": 255,"required": true},'
    +'{"type": "ftString", "name": "xdtm","size": 255,"required": true},'
    +'{"type": "ftString", "name": "qglxCode","size": 255,"required": true},'
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

  <input type="text" name="ip" id="ip" value="127.0.0.1" /><br>
   <input type="text" name="port" id="port" value="12345"/><br>
  <div class="panel-body">
    <tbody>
    <div align="center" >
      <?php
        if($total<50){
        
      ?>
        <a href="../model/labelPrint.php?id=<?php echo $branchNo ?>&detailsNo=<?php echo $detailsNo ?>&customer=<?php echo $customer ?>&produceNo=<?php echo $produceNo ?>&sId=0&eId=<?php echo $total ?>" > 显示1-<?php echo $total ?>页 </a>
      <?php
        }
        if($total>=50){
            ?>
            <a href="../model/labelPrint.php?id=<?php echo $branchNo ?>&detailsNo=<?php echo $detailsNo ?>&customer=<?php echo $customer ?>&produceNo=<?php echo $produceNo ?>&sId=0&eId=50" > 显示1-50 页 </a>
          <?php
            $i=0;
            $Snum=0;
            $Enum=50;
            for($i=1;$i<intval($total/50);$i++){
              $Snum+=50;
              $Enum+=50;
            ?>
              <a href="../model/labelPrint.php?id=<?php echo $branchNo ?>&detailsNo=<?php echo $detailsNo ?>&customer=<?php echo $customer ?>&produceNo=<?php echo $produceNo ?>&sId=<?php echo $Snum ?>&eId=<?php echo $Enum ?>" > 显示<?php echo $Snum+1 ?>-<?php echo $Enum ?>页</a>    
            <?php  
            }

               if( $total%50 !=0){
                $Snum+=50;
                $Enum=$Enum+$total%50;
              ?>
              <a href="../model/labelPrint.php?id=<?php echo $branchNo ?>&detailsNo=<?php echo $detailsNo ?>&customer=<?php echo $customer ?>&produceNo=<?php echo $produceNo ?>&sId=<?php echo $Snum ?>&eId=<?php echo $Enum ?>" > 显示<?php echo $Snum+1 ?>-<?php echo $Enum ?>页</a>    
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
      <button onclick="function_oneDy(<?php echo $number ?>)">打印(<?php echo $row2['序号'] ?>)</button>
        </div>
    <br>
    <table id="dyId" border ="1px solid #ccc" cellspacing="0" cellpadding="0" align="center" width="600px" height="200px" style="text-align:center;vertical-align:middle;">
      <tr>
      <td>订单号</td> <td Id="scdh<?php echo $number ?>"><?php echo $row2['生产单号'] ?></td>
      <td>详单号</td> <td colspan="2" Id="xdbh<?php echo $number ?>"><?php echo $row2['详单编号'] ?></td>  
      </tr>
      <tr>
        <td>尺寸</td> <td Id="cpcc<?php echo $number ?>" ><?php echo $row2['门长'].'*'.$row2['门宽'].'*'.$row2['门厚'] ?></td>
        <td>封边</td>  <td  Id="fblx<?php echo $number ?>" ><?php echo $row2['封边类型']  ?></td>
        <td rowspan="4" width="150 px"></td>
      </tr>
      <tr>
        <td>型号</td>  <td Id="xh<?php echo $number ?>" ><?php echo $row2['型号'] ?></td>
        <td>门锁孔</td>  <td Id="msk<?php echo $number ?>" ><?php echo $row2['门锁孔'] ?></td>
      </tr>
      <tr>
      <td>颜色</td> <td Id="yqys<?php echo $number ?>" ><?php echo $row2['油漆颜色']?></td> 
      <td>合页槽 </td> <td Id="hyc<?php echo $number ?>"  ><?php echo $row2['合页槽'] ?></td>
      </tr>
      <tr>
      <td>树种</td> <td Id="sz<?php echo $number ?>" ><?php echo $row2['树种']?></td>
        <td>备注</td><td></td> 
      </tr>
      <td  Id="ccCode<?php echo $number ?>"  style="display:none"  > <?php 
        echo substr(strval($row2['门长']+10000),1,4).'/'.substr(strval($row2['门宽']+10000),1,4).'/'.substr(strval($row2['门厚']+10000),1,4)
      ?></td>
      <td  Id="fblxCode<?php echo $number ?>"  style="display:none"  > <?php 
       if('两头'== $row2['封边类型'] ){
        echo "0001";
       }else if('四面'== $row2['封边类型']){
        echo "0000";
       }else{
        echo "errer";
       }
      ?></td>
       <td  Id="qglxCode<?php echo $number ?>"  style="display:none"  > <?php 
       if('两头'== $row2['封边类型'] ){
        echo "0002";
       }else if('四面'== $row2['封边类型']){
        echo "0003";
       }else{
        echo "errer";
        
       }
      ?></td>
        <td  Id="xdtm<?php echo $number ?>"  style="display:none"  > <?php echo $row2['详单条码'] ?></td>
        <td  Id="ID<?php echo $number ?>"  style="display:none"  > <?php echo $row2['机器码'] ?></td>
    </table>     
    </br>
    </br>
  <?php 
    } 
  }  
  ?> 
  </tbody>
  

</body>
</html>
