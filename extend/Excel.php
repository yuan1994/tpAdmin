<?php
// +----------------------------------------------------------------------
// | tpadmin [a web admin based ThinkPHP5]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 tianpian
// +----------------------------------------------------------------------
// | Author: tianpian <tianpian0805@gmail.com>
// +----------------------------------------------------------------------

class Excel{
    /**
     * 一键导出Excel 2007格式
     * @param array $header Excel头部 ["COL1","COL2","COL3",...]
     * @param array $body 和头部长度相等字段查询出的数据就可以直接导出
     * @param null|string $name 文件名，不包含扩展名，为空默认为当前时间
     * @return string
     */
    static public function export($head,$body,$name = null){
        try {
            // 输出Excel文件头
            $name = empty($name) ? date('YmdHis') : $name;

            $objPHPExcel = new \PHPExcel();
            $sheetPHPExcel = $objPHPExcel->setActiveSheetIndex(0);
            $char_index = range("A","Z");

            //Excel表格头
            foreach ($head as $key=>$val){
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            //Excel body部分
            foreach ($body as $key=>$val){
                $row = $key+2;
                $col = 0;
                foreach ($val as $k=>$v){
                    $sheetPHPExcel->setCellValue("{$char_index[$col]}{$row}", $v);
                    $col ++;
                }
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}