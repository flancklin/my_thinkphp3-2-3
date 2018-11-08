<?php

namespace Extend\Controller;


use Think\Controller;

class ExcelController extends Controller {
    public function index() {
        $t = [
            't0' => $order['orderId'],//订单号
            't1' => '',//外部订单号
            't2' => '',//订单状态
            't3' => date('Y-m-d H:i:s', $order['orderTime']),//支付时间
            't4' => $tbPro[$order['orderId']]['title'],//抢购名称
            't5' => '电子卡券',//商品类型
            't6' => '',//商品类目
            't7' => '',//商品规格
            't8' => '',//规格编码
            't9' => '',//商品编码
            't10' => $tbPro[$order['orderId']]['money'],//商品单价
            't11' => $disMethod,//商品优惠方式
            't12' => $coupon[$order['orderId']]['money'],//优惠后价格
            't13' => $order['bag'],//商品数量
            't14' => $order['paidAmount'],//商品金额小计
            't15' => '',//店铺优惠（分摊）
            't16' => $order['paidAmount'],//商品市级成交金额
            't17' => '',//商品抵用积分
            't18' => $coupon[$order['orderId']]['selectUseDate'] ? date('Y-m-d H:i:s', $coupon[$order['orderId']]['selectUseDate']) : '',//商品留言
            't19' => $order['fullName'],//收货人
            't20' => $order['phone'],//手机号
            't21' => '',//收货人省份
            't22' => '',//收货人市区
            't23' => '',//收货人地区
            't24' => $order['address'],//收货人地址
            't25' => '',//买家备注
            't26' => '',//商品发货状态
            't27' => '无需发货',//商品发货方式
            't28' => '',//商品发货物流公司
            't29' => '',//商品发货物流单号
            't30' => '',//发货员工
            't31' => '',//商品发货时间
            't32' => '',//商品退款状态
            't33' => '',//商品已退款金额
            't34' => '',//上架订单备注
            't35' => '',//周期购信息
        ];
        $list[] = array_values($t);
        $header = [
            '订单号', '外部订单号', '订单商品状态', '交易成功时间', '商品名称',
            '商品类型', '商品目录', '商品规格', '规格编码', '商品编码',
            '商品单价', '商品优惠方式', '商品优惠后价格', '商品数量', '商品金额小计',
            '店铺优惠（分摊）', '商品实际成交金额', '商品抵用积分数', '商品留言', '收货人/提货人',
            '收货人手机号/提货人手机号', '收货人省份', '收货人城市', '收货人地区', '详细收货地址/提货地址',
            '买家备注', '商品发货状态', '商品发货方式', '商品发货物流公司', '商品发货物流单号',
            '发货员工', '商品发货时间', '商品退款状态', '商品已退款金额', '商家订单备注',
            '周期购信息'
        ];
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        header("Content-type: text/html; charset=utf-8");
        $this->exportExcel("订单列表", $header, $list);
    }

    public function exportExcel($fileName, $headr, $list) {
        // vendor("PhpExcel.PHPExcel");
        // vendor("PhpExcel.PHPExcel.Writer.Excel2007");

        import("Org.Util.PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $key = 0;
        foreach ($headr as $v) {
            $colum = \PHPExcel_Cell::stringFromColumnIndex($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach ($list as $key => $rows) {
            $span = 0;
            foreach ($rows as $keyName => $value) {
                $j = \PHPExcel_Cell::stringFromColumnIndex($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span++;
            }
            $column++;
        }
        $objPHPExcel->createSheet();
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename='" . $fileName . ".xls'");
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }


}