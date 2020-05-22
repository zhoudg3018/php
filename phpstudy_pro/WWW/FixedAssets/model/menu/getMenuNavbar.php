<?php
include "../conn/pdo_config.php";
$pid = empty($_POST['id'] ) ? null : $_POST['id']; // 前端发来的请求，表明了现在要请求哪一个节点的孩子，请求根节点时，这个参数为空
$role = empty($_POST['role'] ) ? null : $_POST['role'];
$arrParams = array();
if( empty( $pid ))
        $whereParent = 'pi IS NULL';
else {
        $whereParent = 'pi=:pi';
        $arrParams[':pi'] = $pid;
}
$stm = $pdo->prepare('SELECT id,nm AS text,ic AS icon,"isMenuHasChild"(id) AS haschildren,hr AS href FROM sy_menu WHERE 1=1 AND '.$whereParent.' ORDER BY st');
$stm->execute($arrParams);
$result = $stm->fetchAll(PDO::FETCH_ASSOC);
$arr = array();
for( $i=0;$i<count($result);$i++){
        if($result[$i]['haschildren'] == 0 ){
                if(in_array($result[$i]['id'], explode(',',$role))) {
                                array_push($arr,$result[$i]);
                }
                
        }else{
                array_push($arr,$result[$i]);
        }
}

echo json_encode( $arr, JSON_UNESCAPED_UNICODE );
?>