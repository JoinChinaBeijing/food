<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/20
 * Time: 2:04
 */

namespace app\common\model;
vendor("phpexcel.Classes.PHPExcel");

class ExcelTool {

	/** 参数1：letter:Excel表格式,示例： $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
	 * 参数2：tableheader,表头数组，示例：$tableheader = array('菜名', '单价', '数量', '总价', '订单号', '商户订单号', '支付时间', '支付方式', '店铺');
	 * 参数3：data:要写入表格的二位数组，示例：
	 * $data = array(
	 * array('1','小王','男','20','100'),
	 * array('2','小李','男','20','101'),
	 * array('3','小张','女','20','102'),
	 * array('4','小赵','女','20','103')
	);
	 * 参数4：filename,输出的excel文件名称*/
	public function export($letter,$tableheader,$data,$filename){
		//创建对象
		$excel = new \PHPExcel();
		for ($i = 0; $i < count($tableheader); $i++) {
			$excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
		}
		//填充表格信息
		for ($i = 2; $i <= count($data) + 1; $i++) {
			$j = 0;
			foreach ($data[$i - 2] as $key => $value) {
				$excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
				$j++;
			}
		}
		//创建Excel输入对象
		$write = new \PHPExcel_Writer_Excel5($excel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename=$filename");
		header("Content-Transfer-Encoding:binary");
		$write->save('php://output');
	}
}