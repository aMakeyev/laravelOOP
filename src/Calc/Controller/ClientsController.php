<?php namespace Calc\Controller;

use Calc\Model\Client;
use Calc\Core\Controllers\BaseController;

use PHPExcel_IOFactory;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Writer_Excel2007;

class ClientsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->title->prepend(trans('calc::titles.clients'));
    }

    public function index()
    {
        $this->layout->content = view('calc::clients.index');
    }

    public function show($id)
    {
        /** @var Client $obj */
        $obj = Client::findOrFail($id);
        $this->title->prepend($obj->present()->fullName);
        $this->layout->content = view('calc::clients.show')->with('obj', $obj);
    }

	/**
	 * Генерация списка всех клиентов
	 */
	public function clientsXls(){

		$pExcel = new PHPExcel();

		$pExcel->setActiveSheetIndex(0);
		$aSheet = $pExcel->getActiveSheet();

		$clients = \Calc\Model\Client::with('creator')->get();
		$i = 1;
		foreach($clients as $client){
			$aSheet->getColumnDimensionByColumn($i)->setAutoSize(true);
			$aSheet->setCellValue('A'.($i+1), $client->id);
			$aSheet->setCellValue('B'.($i+1), $client->first_name . ' '.$client->last_name . PHP_EOL . $client->email . PHP_EOL . $client->phone);
			$aSheet->setCellValue('C'.($i+1), 'Последний звонок: ' . PHP_EOL . $client->last_contact_at . PHP_EOL . 'Следующий звонок: '  . PHP_EOL . $client->next_contact_at);
			$aSheet->setCellValue('D'.($i+1), date('d.m.Y', strtotime($client->created_at)));
			$aSheet->setCellValue('E'.($i+1), $client->getTypeTextAttribute());
			$aSheet->setCellValue('F'.($i+1), $client->description);
			$aSheet->setCellValue('G'.($i+1), $client->creator->last_name . ' ' . $client->creator->first_name);
			$i++;
		}
		//шапка таблицы
		$aSheet->setCellValue('A1','№');
		$aSheet->setCellValue('B1','Фамилия, Имя');
		$aSheet->setCellValue('C1','Звонки');
		$aSheet->setCellValue('D1','Добавлен');
		$aSheet->setCellValue('E1','Тип');
		$aSheet->setCellValue('F1','Описание');
		$aSheet->setCellValue('G1','Менеджер');

		// Стили для шапки таблицы (1 строка)
		$style_hprice = array(
			'alignment' => array(
				'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
			),
			'fill' => array(
				'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
				'color'=>array(
					'rgb' => 'e1d9f3'
				)
			),
			'font'=>array(
				'bold' => true,
				'name' => 'Arial',
				'size' => 12
			),
		);
		$aSheet->getStyle('A1:G1')->applyFromArray($style_hprice);
		//перенос по словам
		$aSheet->getStyle('A2:G' . $i)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setHorizontal(PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT);

		$aSheet->getColumnDimension('G')->setAutoSize(true);
		$aSheet->getColumnDimension('F')->setAutoSize(false);
		$aSheet->getColumnDimension('F')->setWidth(50);
		//Фильтр
		$aSheet->setAutoFilter('A1:G' . $i);

		//сохранить файл
		/*		$objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel2007');
				$objWriter->save('/Users/user/Documents/clients' . date('d.m.Y') . '.xlsx');*/

		// перенаправление вывода на браузер клиента
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=clients-' . date('d.m.Y') . '.xls');

		$objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel5');
		$objWriter->save('php://output');

		die();
	}

}
