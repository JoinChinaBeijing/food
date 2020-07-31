<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 16:45
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\CategoryValidate;
use think\Db;

class CategoryModel extends CommonModel {

	/**
	 * 获取分类列表
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getCategoryList(){
		$res = Db::table("category")->select();
		// 获取打印机列表
		$printList = $this->getPrints();
		if( $res ){
			foreach ( $res as $k=>$v){
				if($res[$k]['status'] == 1){
					$res[$k]['statusDesc'] = '是';
				}else{
					$res[$k]['statusDesc'] = '否';
				}

				$res[$k]['printName'] = isset($printList[$v['prints']]) ? $printList[$v['prints']] : '未定义';
			}
			return $res;
		}else{
			return null;
		}
	}

	/**
	 * 获取打印机列表
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getPrints()
	{
		return Db::table('prints')->column('name', 'code');
	}

	public function saveCategory( $data ){
		if( $data ){
			$data['createtime'] = DateTimeTool::getDate();
			$categoryValidate = new CategoryValidate();
			$result = $categoryValidate->check( $data );
			if(false === $result ){
				return self::getResponse( "403", null, $categoryValidate->getError());//数据有误
			}else{
				if($data["id"]){
					$update = Db::table("category")
					            ->where("id",$data["id"])
					            ->update($data);
					if($update){
						return self::getResponse("200",null,"修改成功");
					}else{
						self::getResponse("403",null,"修改失败");
					}
				}else{
					$row = Db::table("category")
					         ->where("code",$data['code'])
					         ->find();
					if($row){
						return self::getResponse("403",null,"编码已经存在");
					}else{
						$res = Db::table("category")
						         ->insert($data);
						if($res){
							return self::getResponse("200",null,"添加成功");
						}else{
							return self::getResponse("403",null,"数据有误");
						}
					}
				}
			}
		}else {
			return self::getResponse("403",null,"数据有误");
		}
	}


}