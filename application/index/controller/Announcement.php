<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 12:44
 */

namespace app\index\controller;


use app\index\model\AnnouncementModel;
use think\Request;

class Announcement extends Base {

	public function index(){
		$announcementModel = new AnnouncementModel();
		$stores = $announcementModel->getStores();
		$announcementResult = $announcementModel->getAnnouncementList();
		$listJson = json_encode( $announcementResult );
		$this->assign("stores",$stores);
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	public function addannouncement(){
		if($_POST){
			$data = Request::instance()->post();
			$announcementModel = new AnnouncementModel();
			return $announcementModel->saveAnnouncement( $data );
		}
	}
}