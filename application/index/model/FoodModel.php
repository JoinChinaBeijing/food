<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/9
 * Time: 10:22
 */

namespace app\index\model;


use app\common\model\CacheTool;
use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\FoodValidate;
use think\Db;
use think\Request;

class FoodModel extends CommonModel {

	protected $field = true;
	/**
	 * @param null $tore 搜索用
	 *
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getFoodList($data = null){
		if($data == null ){
			$res = Db::table("food")->select();
		}else{
			$res = Db::table("food")->alias('f')
			         ->where('f.name','like','%'.$data['name'].'%')
					->field("f.*")
			         ->select();
		}
		if( $res ){
			foreach ( $res as $k=>$v ){
				if( $res[$k]['status'] == 1 ){
					$res[$k]['statusDesc'] = '是';
				}else {
					$res[$k]['statusDesc'] = '否';
				}
				if( $res[$k]['disstatus'] == 1 ){
					$res[$k]['disstatusDesc'] = '是';
				}else {
					$res[$k]['disstatusDesc'] = '否';
				}
				$res[$k]['cidDesc'] = $this->getCategoryNameById($res[$k]['cid']);
				$res[$k]['tastelist'] = $this->getFoodTaste($res[$k]['id']);
			}
			return $res;
		}else{
			return null;
		}
	}

	// 估清模块
	public function getFoodStoreList($data = '')
	{
		// 获取当前店铺sid
		$user = session('user');
		$sid  = $user['sid'];
		$categorySql = Db::table('store')->alias('s')
		               ->join("store_category sc", "sc.sid = s.id",'LEFT');
		if (!empty($sid))
		{
			$categorySql = $categorySql->where('sid', $sid);
		}

		$category = $categorySql->field('s.id,s.name,sc.cids')
		                        ->select();

		$cids = $sids = [];
		foreach ($category as $key => $value)
		{
			$sids[] = $value['id'];
			if (!empty($value['cids']))
			{
				$array  = explode(',', $value['cids']);
				$cids = array_merge($cids, $array);
				$category[$key]['cid'] = $array;
			}
			else
			{
				$category[$key]['cid'] = [];
			}
		}

		$foodsSql = Db::table('food')->alias('f')
		           ->join("category c", "c.id = f.cid",'LEFT')
		           ->where('f.cid', 'in', implode($cids, ','))
		           ->field('f.id,f.cid,f.name,c.name as categoryName,f.unit,f.price');

		if (!empty($data['name']))
		{
			$foodsSql = $foodsSql->where('f.name','like','%'.$data['name'].'%');
		}

		$foods = $foodsSql->select();

		$storeFoods = $foodArray = $fids = $sfList = [];
		foreach ($foods as $key => $value)
		{
			$fids[] = $value['id'];
		}

		$store_foods = Db::table('store_food')
		               ->field('concat(sid, "_", fid) as store, status')
		               ->where('sid', 'in', implode($sids, ','))
		               ->select();

		foreach ($store_foods as $key => $value)
		{
			$sfList[$value['store']] = $value['status'];
		}

		foreach ($category as $k1 => $v1)
		{
			foreach ($foods as $k2 => $food)
			{
				if (in_array($food['cid'], $v1['cid']))
				{
					$foodArray = $food;
					$foodArray['sid'] = $v1['id'];
					$foodArray['store'] = $v1['name'];
					$key = $v1['id'] . '_' . $food['id'];
					if (isset($sfList[$key]))
					{
						$foodArray['status'] = $sfList[$key];

						if ($sfList[$key] == 1)
						{
							$foodArray['statusName'] = '上架';
						}
						else
						{
							$foodArray['statusName'] = '下架';
						}
					}
					else
					{
						$foodArray['status'] = 0;
						$foodArray['statusName'] = '下架';
					}

					$storeFoods[] = $foodArray;
				}
			}
		}

		return $storeFoods;
	}

	// 估清模块修改状态
	public function updateStatus($fid, $sid, $status)
	{
		if (empty($fid) || empty($sid) || $status == '')
		{
			return array('code' => 403, 'message' => '参数错误');
		}

		// 判断是否存在
		$sfID = Db::table('store_food')->where('fid', $fid)
		                               ->where('sid', $sid)
		                               ->value('id');

		if (empty($sfID))
		{
			Db::table('store_food')
			->insert(['sid' => $sid, 'fid' => $fid, 'status' => $status]);
		}
		else
		{
			Db::table('store_food')->where('id', $sfID)->update(['status' => $status]);
		}

		CacheTool::rmCache("redis","store"."-".$sid);
		return array('code' => 200, 'message' => '修改成功');
	}

	/**
	 * 获取分类名通过id
	 * @param $id
	 *
	 * @return mixed|null
	 */
	private function getCategoryNameById( $id ){
		if( $id ){
			$res = Db::table("category")
				->where('id',$id)
				->value('name');
			return $res ? $res : null;
		}
	}

	/**
	 * 通过状态获取分类
	 * @param $status
	 *
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getCategoryByStatus( $status ){
		$res = Db::table("category")
		         ->where('status',$status)
		         ->select();
		return $res ? $res :null;
	}

	public function getStoreList($status){
		$res = Db::table("store")
		         ->where('status',$status)
		         ->select();
		return $res ? $res :null;
    }

	/**
	 * 添加、编辑食品
	 * @param $data
	 *
	 * @return string
	 */
	public function saveFood( $data ){
		if( $data ){
			$storeIDs = Db::table('store')->column('id');
			if ($_FILES ['pic']['name']) {
				$file = Request::instance()->file("pic");
				$info = $file->move(ROOT_PATH ."public" . DS ."uploads");
				if( $info ){
					$data['pic'] = $info->getFilename();
				}else{
					return self::getResponseObject("403",null,$info->getError());
				}
			}else{
				$data['pic'] = '';
			}
			$data['createtime'] = DateTimeTool::getDate();
			$data['picdate'] = DateTimeTool::getDate("Ymd");
			$storeValidate = new FoodValidate();
			$result = $storeValidate->check( $data );
			if(false === $result ){
				return self::getResponse( "403", null, $storeValidate->getError());//数据有误
			}else{
				$fooddata['name'] = $data['name'];
				if($data['pic'] !=""){
					$fooddata['picdate'] = $data['picdate'];
					$fooddata['pic'] = $data['pic'];
				}
				$fooddata['unit'] = $data['unit'];
				$fooddata['price'] = $data['price'];
				$fooddata['discount'] = $data['discount'];
				$fooddata['disstatus'] = $data['disstatus'];
				$fooddata['cid'] = $data['cid'];
				$fooddata['ischose'] = $data['ischose'];
				$fooddata['sort'] = $data['sort'];
				$fooddata['deftaste'] = $data['defaulttaste'];
				$fooddata['status'] = $data['status'];
				$fooddata['desc'] = $data['desc'];
				$fooddata['createtime'] = $data['createtime'];
				$fooddata['modifietime'] = "";
				$fooddata['modifier'] = "";;
				if($data["id"]){
					$update = Db::table("food")
					            ->where("id",$data["id"])
					            ->update($fooddata);
					if($update){
						//口味
						if(isset($data['taste']) && count($data['taste']) > 0 ){
							$row = Db::table("food_taste")
								->where("fid",$data["id"])
								->select();
							if($row){//更新
								Db::table("food_taste")
									->where("fid",$data["id"])
									->delete();
								$tastedata['fid'] = $data["id"];
								foreach ($data['taste'] as $k=>$v){
									$tastedata['tid'] = $v;
									Db::table("food_taste")
									  ->insert( $tastedata );
								}
							}else{//添加
								$tastedata['fid'] = $data["id"];
								foreach ($data['taste'] as $k=>$v){
									$tastedata['tid'] = $v;
									Db::table("food_taste")
									 ->insert($tastedata);
								}
							}
						}
						else
						{
							Db::table('food_taste')->where('fid',$data["id"])->delete();
						}
 						foreach ($storeIDs as $sid)
 						{
 							CacheTool::rmCache("redis","store"."-".$sid);
 						}
						return self::getResponseObject("200",null,"修改成功");
					}else{
						return self::getResponseObject("403",null,"修改失败");
					}
				}else{
					$row = Db::table("food")
					         ->where("name",$data['name'])
					         ->find();
					if($row){
						return self::getResponseObject("403",null,"已经存在");
					}else{
						$res = Db::table("food")
						         ->insertGetId($fooddata);
						if($res){
							//口味
							if(isset($data['taste']) && count($data['taste']) > 0 ){
								$tastedata['fid'] = $res;
								foreach ($data['taste'] as $k=>$v){
									$tastedata['tid'] = $v;
									Db::table("food_taste")
									  ->insert( $tastedata );
								}
							}
							foreach ($storeIDs as $sid)
	 						{
	 							CacheTool::rmCache("redis","store"."-".$sid);
	 						}
							return self::getResponseObject("200",null,"添加成功");
						}else{
							return self::getResponseObject("403",null,"数据有误");
						}
					}
				}
			}
		}else {
			return self::getResponse("403",null,"数据有误");
		}
	}

	private function getStoreNameById( $id ){
		if( $id ){
			$res = Db::table("store")
			         ->where('id',$id)
				     ->where('status',1)
			         ->value('name');
			return $res ? $res : null;
		}
	}

	public function getTasteList( $status ){
		return Db::table("taste")
			->where("status",$status )
			->select();
	}

	public function getFoodTaste( $id ){
		$res =  Db::table("food_taste")
			->where("fid",$id)
			->select();
		return $res ? $res : null;
	}
}