<?php
namespace app\breakfast\controller;

use app\common\model\CacheTool;
use app\breakfast\model\IndexModel;
use think\Db;
use think\Request;

class Index extends Base
{
    public function index()
    {
    	$model      = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $path = $model . '/' . $controller . '/' . $action;
        session('homeUrl', $path);
    	//先判断是否提交了store
	    $indexModel = new IndexModel();
	    $stores = $this->getStores();
	    $this->assign("stores",$stores);
	    $integral = $indexModel->getIntegralByUid( $this->uid );
	    if(Request::instance()->post("store")){
		    $store = Request::instance()->post("store");
	    }else{
	    	//取用户常用店铺，作为默认，没有就选择第一家作为默认
		    $storeid = $indexModel->getDeaStore( $this->uid);
		    if($storeid){
			    $store = $storeid;
		    }else{
			    $store = $stores[0]['id'];
		    }
	    }
	    //查看店铺的营业时间
	    $storedata = $indexModel->getStoredata( $store );
	    $this->assign("storedata",$storedata);
	    $indexCacheData = CacheTool::getCache("redis","store"."-".$store);
	    if($indexCacheData){
		    $data = json_decode($indexCacheData,true);
	    }else{
		    $data = $indexModel->getIndexData($store );
		    //存到缓存中
		    CacheTool::setCache("redis","store"."-".$store,json_encode($data));
	    }
	    if (!session('?times')) {
	    session('times', 1);
	    $announcement = $indexModel->getAnnouncement($store);
	    if($announcement!=null){
		    $this->assign("announcement",$announcement);
	    }
    } else {
	    session('times', session('times') + 1);
    }
	    $this->assign("store",$store);
	    $this->assign( "data", $data );
	    $this->assign( "integral", $integral );
	    $this->assign("lowspend",config("lowspend"));
    	return $this->fetch();
    }

    private function getStores(){
    	$res = Db::table("store")
		    ->where("district",1)
		    ->select();
	    return $res ? $res : null;
    }

    public function checkChose(){
	    $data = Request::instance()->post();
	    foreach ( $data as $k => $v ){
		    if($v == 0 ){
			    unset( $data[$k] );
		    }
	    }
	    $cate =array();
	    $foodids = array();
	    $store = null;
	    foreach ($data as $k=>$v){
	    	$exploarr = explode("-",$k);
		    if(count($exploarr) == 3){
			    $cate[]= $exploarr[1];
			    $foodids[] = $exploarr[2];
			    $store = $exploarr[0];
		    }
	    }
	    $cate = array_unique($cate);
	    $mastchosecate = array_intersect_assoc(config("chosecate"),$cate);
	    if(count( $mastchosecate) > 0 ){
	    	$indexmodel = new IndexModel();
	    	foreach ( $mastchosecate as $k=>$v ){
	    		$foodresult = $indexmodel->getMastChoseFoodsByCidSid( $v,$store );
			    if( $foodresult ){
			    	$chosearr = array();
			    	foreach ($foodresult as $kk=>$vv ){
			    		if(!in_array($vv['id'],$foodids)){
						    $chosearr[] = $vv['name'];
					    }
				    }
				    if(count($chosearr) > 0 ){
					    $res['code'] = 100;
					    $res['msg'] = implode(",",$chosearr)."为必选哦";
					    echo json_encode($res);return;
				    }else{
					    $res['code'] = 200;
					    $res['msg'] = '验证通过';
					    echo json_encode($res);return;
				    }
			    }else{ //必选分类里边没有必须选择的产品
				    $res['code'] = 200;
				    $res['msg'] = '验证通过';
				    echo json_encode($res);return;
			    }
		    }
	    }else{//选择的分类里边没有
		    $res['code'] = 200;
		    $res['msg'] = '验证通过';
		    echo json_encode($res);return;
	    }
    }
}
