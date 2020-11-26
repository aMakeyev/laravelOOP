<?php namespace Calc\Controller;

use Calc\Core\Controllers\BaseController;
use Calc\Helpers\Rus\Date;
use Calc\Helpers\Rus\Price;
use Calc\Helpers\Rus\StringHelper;
use Calc\Model\Calculation;
use Calc\Model\CalculationWrapper;
use Calc\Model\Order;
use Calc\Model\SubjectWrapper;
use Calc\Repo\CalculationRepo;
use PHPExcel_IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Writer\Word2007;
use rtf\RtfTemplate;

use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Writer_Excel2007;

/**
 * Class MakeOrderController
 * @package Calc\Controller
 */
class MakeOrderController extends BaseController
{
    protected $repositoryClassName = 'Calc\Repo\CalculationRepo';

    /**
     * @var CalculationRepo
     */
    protected $repository;

    public function show($id)
    {
        $data = $this->repository->calculateForClient($id);

        return view('calc::orders.order', $data);
    }

    /**
     * Генерирует договор
     * @param $id
     */
    public function contract($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

        /** @var CalculationWrapper $costs */
        $costs = $data['costs'];

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}
//        $contractDate = strtotime(date('d.m.Y'));

        $price = $costs->total;
        if ($order->pseudo_discount_percent || $order->pseudo_discount_meter)
            $price = $costs->totalDiscount;

        $map = [
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'YEAR' => date('Y', $contractDate),
            'MONTH' => (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY),
            'DAY' => date('d', $contractDate),
            'CLIENT_NAME' => $order->client->first_name,
            'CLIENT_SURNAME' => $order->client->last_name,
            'CLIENT_SECONDNAME' => $order->client->second_name,
            'CLIENT_PRE' => 'гражданин(ка) РФ',
			'CLIENT_POST' => '',
			'CLIENT' => $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name,
            'CLIENT_PHONE' => $order->client->phone,
            'CLIENT_EMAIL' => $order->client->email,
            'SUM_FULL' => number_format($price, 0, '.', ' '),
            'SUM_FULL_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($price))),
            'SUM_BEFORE' => number_format($price * 0.8, 0, '.', ' '),
            'SUM_BEFORE_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($price * 0.8))),
            'SUM_AFTER' => number_format($price * 0.2, 0, '.', ' '),
            'SUM_AFTER_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($price * 0.2))),
            'SUM_CLIMB' => number_format($costs->climb_price, 0, '.', ' '),
            'SUM_CLIMB_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($costs->climb_price))),
            'SUM_DELIVERY' => number_format($costs->delivery, 0, '.', ' '),
            'SUM_DELIVERY_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($costs->delivery))),
            'SUM_INSTALL' => number_format($costs->install, 0, '.', ' '),
            'SUM_INSTALL_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($costs->install))),
            'DELIVERY_ADDRESS' => str_replace("\n", '<w:br />', $order->client->delivery_address),
            'BIRTHDAY' => $order->client->birthday,
            'PASSPORT_SERIES' => $order->client->passport_series,
            'PASSPORT_NUMBER' => $order->client->passport_number,
            'PASSPORT_ISSUED_BY' => $order->client->passport_issued_by,
            'PASSPORT_ISSUED_CODE' => $order->client->passport_issued_code,
            'PASSPORT_ISSUED_DATE' => $order->client->passport_issued_date,
            'PASSPORT_ADDRESS' => $order->client->passport_address,
			'CLIENT_DETAILS' =>
				'ФИО ' . $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name .'<w:br />'.
				'Паспортные данные: серия '.$order->client->passport_series.' номер '.$order->client->passport_number . '<w:br />' .
				'Кем и когда выдан: ' . $order->client->passport_issued_by . '<w:br />' .
				'Дата выдачи: ' . $order->client->passport_issued_date . '<w:br />' .
				'Адрес места жительства (по паспорту): ' . $order->client->passport_address . '<w:br />' .
				'Код подразделения: ' . $order->client->passport_issued_code . '<w:br />' .
				'Дата рождения: ' . $order->client->birthday
        ];

		if($order->client->type == 2){
			$map['CLIENT_PRE'] = '';
			$map['CLIENT'] = $order->client->legal_name;
			$map['CLIENT_POST'] = ', в лице Генерального директора ' . $order->client->director_name . ', действующего на основании Устава';
			$map['CLIENT_DETAILS'] =
				 $order->client->legal_name .'<w:br />'.
				'ИНН / КПП: '.$order->client->inn.' / '.$order->client->kpp . '<w:br />' .
				'Юридический адрес: ' . $order->client->legal_address . '<w:br />' .
				'Почтовый адрес: ' . $order->client->mail_address . '<w:br />' .
				'Наименование банка: ' . $order->client->bank_name . '<w:br />' .
				'Расчетный счет: ' . $order->client->settlement_account . '<w:br />' .
				'Корреспондентский счет: ' . $order->client->correspondent_account . '<w:br />' .
				'БИК: ' . $order->client->bik . '<w:br />' .
				'ОКПО: ' . $order->client->okpo . '<w:br />' .
				'ОКАТО: ' . $order->client->okato . '<w:br />' .
				'ОКТМО: ' . $order->client->oktmo . '<w:br />' .
				'ОКВЭД: ' . $order->client->okved . '<w:br />' .
				'ОГРН: ' . $order->client->ogrn . '<w:br />' .
				'Телефон: ' . $order->client->legal_phone . '<w:br />' .
				'E-mail: ' . $order->client->legal_email . '<w:br />' .
				'Генеральный директор: ' . $order->client->director_name;
		}
//файл в моём гугл-докс сontractCalc.docx
        $word = new TemplateProcessor('https://drive.google.com/uc?export=download&id=1zZe5aMvkyCMik9NjrfKPmH6UKfN4-ZHy');
//		$word = new TemplateProcessor(app_path() . '/docrender/contract.docx');

        foreach ($map as $search => $replace) {
            $word->setValue($search, $replace);
        }

		$fileName = 'Договор ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: inline; filename={$fileName}");

        $word->saveAs('php://output');
        //$word->saveAs(app_path() . '/docrender/' . $fileName);
        die();
    }

    /**
     * Генерация счёта
     * @param int $id
     */
    public function check($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

        /** @var SubjectWrapper[] $subjects */
        $subjects = $data['subjects'];

        /** @var CalculationWrapper $costs */
        $costs = $data['costs'];

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}

        $objPHPExcel = PHPExcel_IOFactory::load(app_path() . '/docrender/check.xls');

        $sheet = $objPHPExcel->getSheet(0);

        $price = round($costs->totalWithInstallAndDelivery());

        $startLine = $startLineForDelete = 26;
        $itemNum = 1;

        $columns = [
            1 => 2,
            3 => 19,
            20 => 22,
            23 => 24,
            25 => 28,
            29 => 32,
        ];

        foreach ($subjects as $subject) {
            $total = $subject->total;
            if ($subject->totalDiscount)
                $total = $subject->totalDiscount;

            $total = round($total);

            $values = [
                1 => $itemNum,
                3 => $subject->title,
                20 => $subject->num,
                23 => 'шт',
                25 => number_format($total / $subject->num, 2, ',', ' '),
                29 => number_format($total, 2, ',', ' '),
            ];

            $sheet->insertNewRowBefore($startLine, 1);

            foreach ($columns as $columnStart => $columnEnd) {
                $sheet->mergeCellsByColumnAndRow($columnStart, $startLine, $columnEnd, $startLine);

                $cell = $sheet->getCellByColumnAndRow($columnStart, $startLine);
                $cell->setValue($values[$columnStart]);
            }

            $startLine++;
            $itemNum++;
        }

        if ($order->pseudo_discount_meter) {
            $values = [
                1 => $itemNum,
                3 => 'Скидка',
                20 => 1,
                23 => 'шт',
                25 => number_format(-round($order->pseudo_discount_meter), 2, ',', ' '),
                29 => number_format(-round($order->pseudo_discount_meter), 2, ',', ' '),
            ];

            $sheet->insertNewRowBefore($startLine, 1);

            foreach ($columns as $columnStart => $columnEnd) {
                $sheet->mergeCellsByColumnAndRow($columnStart, $startLine, $columnEnd, $startLine);

                $cell = $sheet->getCellByColumnAndRow($columnStart, $startLine);
                $cell->setValue($values[$columnStart]);
            }

            $startLine++;
            $itemNum++;
        }

        $additional = [
            'Установка и монтаж' => round($costs->object->install),
            'Транспортные услуги, доставка до подъезда' => round($costs->object->delivery),
            'Транспортные услуги, подъем на этаж (лифт)' => round($costs->climb_price),
        ];

        foreach ($additional as $addKey => $addValue) {
            $values = [
                1 => $itemNum,
                3 => $addKey,
                20 => 1,
                23 => 'шт',
                25 => number_format(round($addValue), 2, ',', ' '),
                29 => number_format(round($addValue), 2, ',', ' '),
            ];

            $sheet->insertNewRowBefore($startLine, 1);

            foreach ($columns as $columnStart => $columnEnd) {
                $sheet->mergeCellsByColumnAndRow($columnStart, $startLine, $columnEnd, $startLine);

                $cell = $sheet->getCellByColumnAndRow($columnStart, $startLine);
                $cell->setValue($values[$columnStart]);
            }

            $startLine++;
            $itemNum++;
        }

        $sheet->removeRow($startLineForDelete - 1);

        $map = [
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'DATE' => date('d.m.Y', $contractDate),
//            'CLIENT_NAME' => $order->client->first_name,
//            'CLIENT_SURNAME' => $order->client->last_name,
//            'CLIENT_SECONDNAME' => $order->client->second_name,
			'CLIENT' => $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name,
			'SUM_FULL' => number_format($price, 2, ',', ' '),
            'SUM_FULL_TEXT' => (new StringHelper())->firstUpper((new Price)->getText($price, true)),
            'ITEMS_COUNT' => $itemNum - 1,
        ];
		if($order->client->type == 2)
			$map ['CLIENT'] = 'ИНН ' . $order->client->inn . ' КПП ' . $order->client->kpp . ' ' . $order->client->legal_name . ' '  . $order->client->legal_address . ', тел. ' . $order->client->legal_phone;

        $tmp_map = [];
        foreach ($map as $item_key => $item_value) {
            $tmp_map['${' . $item_key . '}'] = $item_value;
        }
        $map = $tmp_map;
        unset($tmp_map);

        /** @var \PHPExcel_Worksheet_Column $row */
        foreach ($sheet->getRowIterator() as $row) {
            /** @var \PHPExcel_Worksheet_ColumnCellIterator $cell */
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $cellValue = str_replace(array_keys($map), array_values($map), $cellValue);
                $cell->setValue($cellValue);
            }
        }

        $fileName = 'Счёт ' . $id . '-' . date('my', $contractDate) . '.xls';

        ob_clean();
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: inline; filename={$fileName}");

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        //$objWriter->save(app_path() . '/docrender/' . $fileName);

        die();
    }

    /**
     * Генерация спецификации
     * @param int $id
     */
    public function spec($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

        /** @var SubjectWrapper[] $subjects */
        $subjects = $data['subjects'];

        /** @var CalculationWrapper $costs */
        $costs = $data['costs'];

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}

        $price = $costs->total;
        if ($order->pseudo_discount_percent || $order->pseudo_discount_meter)
            $price = $costs->totalDiscount;
        $map = [
            'NAME' => $order->title,
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'DATE' => date('d.m.Y', $contractDate),
        ];

        $word = new TemplateProcessor(app_path() . '/docrender/spec.docx');

        foreach ($map as $search => $replace) {
            $word->setValue($search, $replace);
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable([
            'borderColor' => '000000',
            'borderSize' => 6,
            'width' => 100,
        ]);

        $titleWidth = Converter::pixelToTwip(230);
        $priceWidth = Converter::pixelToTwip(65);

        $headTextStyle = [
            'bold' => true,
            'name' => 'Times New Roman',
            'size' => 10,
        ];

        $priceTextStyle = [
            'name' => 'Times New Roman',
            'size' => 10.5,
            'align' => 'right',
        ];

        $headCellStyle = [
            'valign' => 'top',
        ];

        $priceCellStyle = [
            'valign' => 'center',
        ];

        $table->addRow();

        $table
            ->addCell(null, $headCellStyle)
            ->addText('№', $headTextStyle);

        $table
            ->addCell($titleWidth, array_merge($headCellStyle, ['width' => $titleWidth]))
            ->addText('Наименование', $headTextStyle);

        $table
            ->addCell(null, $headCellStyle)
            ->addText('Кол-во', $headTextStyle);

        $table
            ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
            ->addText('Фасад', $headTextStyle);

        $table
            ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
            ->addText('Корпус', $headTextStyle);

        $table
            ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
            ->addText('Фурни-<w:br />тура', $headTextStyle);

        $table
            ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
            ->addText('Проекти-<w:br />рование', $headTextStyle);

        $table
            ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
            ->addText('Стои-<w:br />мость', $headTextStyle);

        if ($order->pseudo_discount_percent) {
            $table
                ->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
//                ->addText("Со<w:br />скидкой<w:br />({$order->pseudo_discount_percent}%)", $headTextStyle);
				->addText("Со скидкой ({$order->getPseudoDiscountPercentTextAttribute()})", $headTextStyle);
        }

        foreach ($subjects as $subject) {
            $table->addRow();

            $table
                ->addCell(null, $priceCellStyle)
                ->addText($subject->i, $priceTextStyle);

            $table
                ->addCell($titleWidth, array_merge($priceCellStyle, ['width' => $titleWidth]))
                ->addText($subject->title . '<w:br />' . "({$subject->x} × {$subject->y} × {$subject->z} мм)", $priceTextStyle);

            $table
                ->addCell(null, $priceCellStyle)
                ->addText($subject->num, $priceTextStyle);

            $table
                ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                ->addText((new Price)->priceFormat($subject->facade), $priceTextStyle);

            $table
                ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                ->addText((new Price)->priceFormat($subject->skeleton), $priceTextStyle);

            $table
                ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                ->addText((new Price)->priceFormat($subject->furniture), $priceTextStyle);

            $table
                ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                ->addText((new Price)->priceFormat($subject->construct_assembly), $priceTextStyle);

            $table
                ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                ->addText((new Price)->priceFormat($subject->total), $priceTextStyle);


            if ($order->pseudo_discount_percent) {
                $table
                    ->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
                    ->addText((new Price)->priceFormat($subject->totalDiscount), $priceTextStyle);
            }
        }

        if ($order->pseudo_discount_meter) {
            $table->addRow();

            $table
                ->addCell(null, $priceCellStyle)
                ->addText('<w:br />', $priceTextStyle);

            $table
                ->addCell($titleWidth, array_merge($headCellStyle, ['width' => $titleWidth]));

            $table
                ->addCell(null, $priceCellStyle);

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => 4,
                        ]
                    )
                )
                ->addText('Скидка за замер:', $headTextStyle);

            if ($order->pseudo_discount_percent) {
                $table
                    ->addCell(null, $priceCellStyle);
            }

            $table
                ->addCell(null, $priceCellStyle)
                ->addText('-' . (new Price)->priceFormat($order->pseudo_discount_meter), $headTextStyle);
        }

        $table->addRow();

        $table
            ->addCell(null, $priceCellStyle)
            ->addText('<w:br />', $priceTextStyle);

        $table
            ->addCell($titleWidth, array_merge($priceCellStyle, ['width' => $titleWidth]))
            ->addText('Всего предметов:', $headTextStyle);

        $table
            ->addCell(null, $priceCellStyle)
            ->addText($costs->num, $priceTextStyle);

        $table
            ->addCell(
                null,
                array_merge(
                    $priceCellStyle, [
                        'gridSpan' => 4,
                    ]
                )
            )
            ->addText('Общая стоимость предметов:', $headTextStyle);

        $table
            ->addCell(null, $priceCellStyle)
            ->addText((new Price)->priceFormat($costs->total), $headTextStyle);

        if ($order->pseudo_discount_percent || $order->pseudo_discount_meter) {
            $table
                ->addCell(null, $priceCellStyle)
                ->addText((new Price)->priceFormat($costs->totalDiscount), $headTextStyle);
        }

        if ($order->delivery) {
            $table->addRow();

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => 3,
                        ]
                    )
                )
                ->addText('<w:br />', $priceTextStyle);

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
                        ]
                    )
                )
                ->addText('Стоимость доставки:', $priceTextStyle);

            $table
                ->addCell(null, $priceCellStyle)
                ->addText((new Price)->priceFormat($order->delivery), $priceTextStyle);

        }

        if ($order->climb_price) {
            $table->addRow();

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => 3,
                        ]
                    )
                )
                ->addText('<w:br />', $priceTextStyle);

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
                        ]
                    )
                )
                ->addText('Стоимость подъёма:', $priceTextStyle);

            $table
                ->addCell(null, $priceCellStyle)
                ->addText((new Price)->priceFormat($order->climb_price), $priceTextStyle);
        }

        if ($order->install) {
            $table->addRow();

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => 3,
                        ]
                    )
                )
                ->addText('<w:br />', $priceTextStyle);

            $table
                ->addCell(
                    null,
                    array_merge(
                        $priceCellStyle, [
                            'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
                        ]
                    )
                )
                ->addText('Стоимость установки:', $priceTextStyle);

            $table
                ->addCell(null, $priceCellStyle)
                ->addText((new Price)->priceFormat($order->install), $priceTextStyle);
        }

        $table->addRow();

        $table
            ->addCell(
                null,
                array_merge(
                    $priceCellStyle, [
                        'gridSpan' => 3,
                    ]
                )
            )
            ->addText('<w:br />', $priceTextStyle);

        $table
            ->addCell(
                null,
                array_merge(
                    $priceCellStyle, [
                        'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
                    ]
                )
            )
            ->addText('Итоговая стоимость:', $headTextStyle);

        $table
            ->addCell(null, $priceCellStyle)
            ->addText((new Price)->priceFormat($costs->totalWithInstallAndDelivery()), $headTextStyle);

        /** @var Word2007 $objWriter */
        $objWriter = new Word2007($phpWord);
        /** @var Word2007\Part\Document $tableStr */
        $tableStr = $objWriter->getWriterPart('Document')->write($table);

        $tableStr = substr($tableStr, strpos($tableStr, '<w:tbl>'));
        $tableStr = substr($tableStr, 0, strpos($tableStr, '<w:sectPr>'));

        $word->setValue('TABLE', $tableStr);

        $fileName = 'Спецификация к договору ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: inline; filename={$fileName}");

        $word->saveAs('php://output');
        //$word->saveAs(app_path() . '/docrender/' . $fileName);

        die();
    }

    /**
     * Генерирует сертификат
     * @param $id
     */
    public function certificate($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}

        $map = [
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'YEAR' => date('Y', $contractDate),
            'MONTH' => (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY),
            'DAY' => date('d', $contractDate),
        ];

        $word = new TemplateProcessor(app_path() . '/docrender/certificate.docx');

        foreach ($map as $search => $replace) {
            $word->setValue($search, $replace);
        }

        $fileName = 'Сертификат ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: inline; filename={$fileName}");

        $word->saveAs('php://output');
        //$word->saveAs(app_path() . '/docrender/' . $fileName);
        die();
    }

    /**
     * Генерирует акт приёма-передачи
     * @param $id
     */
    public function acceptance($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

        /** @var CalculationWrapper $costs */
        $costs = $data['costs'];

        $price = $costs->total;
        if ($order->pseudo_discount_percent || $order->pseudo_discount_meter)
            $price = $costs->totalDiscount;

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}
		$order->delivery_at = date('d.m.Y');

        $map = [
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'YEAR' => date('Y', $contractDate),
            'MONTH' => (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY),
            'DAY' => date('d', $contractDate),
            'SPEC_NAME' => $order->title,
//            'CLIENT_NAME' => $order->client->first_name,
//            'CLIENT_SURNAME' => $order->client->last_name,
//            'CLIENT_SECONDNAME' => $order->client->second_name,
			'CLIENT_PRE' => 'гражданин(ка) РФ ',
			'CLIENT_POST' => '',
			'CLIENT' => $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name,
            'DELIVERY_YEAR' => date('Y', strtotime($order->delivery_at)),
//            'DELIVERY_MONTH' => '________',
            'DELIVERY_MONTH' => (new Date)->getRusMoth(date('m', strtotime($order->delivery_at)), Date::CASE_RODITELNIY),
//            'DELIVERY_DAY' => '__',
            'DELIVERY_DAY' => date('d', strtotime($order->delivery_at)),
            'TRANSPORT_PLACES' => 1,
            'PAY_SUMM' => number_format($price * 0.2, 0, '.', ' '),
			'PAY_ADD_SUMM' => number_format($order->addagree_price, 0, '.', ' '),
			'DELIVERY_SUMM' => number_format($costs->delivery, 0, '.', ' '),
			'CLIMB_SUMM' => number_format($costs->climb_price, 0, '.', ' '),
            'FULL_SUMM' => number_format($price * 0.2 + $costs->delivery + $costs->climb_price + $order->addagree_price, 0, '.', ' '),
        ];
		if($order->client->type == 2) {
			$map['CLIENT_PRE'] = '';
			$map['CLIENT'] = $order->client->legal_name;
			$map['CLIENT_POST'] = ', в лице Генерального директора '.$order->client->director_name.', действующего на основании Устава';
		}

        $word = new TemplateProcessor(app_path() . '/docrender/acceptance.docx');

        foreach ($map as $search => $replace) {
            $word->setValue($search, $replace);
        }

        $fileName = 'Акт приёма-передачи ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: inline; filename={$fileName}");

        $word->saveAs('php://output');
        //$word->saveAs(app_path() . '/docrender/' . $fileName);
        die();
    }

    /**
     * Генерирует акт выполненных работ
     * @param $id
     */
    public function work($id)
    {
        $data = $this->repository->calculateForClient($id);

        /** @var Calculation $order */
        $order = $data['order'];

        /** @var CalculationWrapper $costs */
        $costs = $data['costs'];

		$order->delivery_at = date('d.m.Y');
		$order->install_at = date('d.m.Y');

		if(!$order->contract_at){
			$contractDate = strtotime($order->created_at);
		} else{
			$contractDate = strtotime($order->contract_at);
		}
        $deliveryDate = strtotime($order->delivery_at);
        $installDate = strtotime($order->install_at);

        /** @var Order $order */
        $ordr = $order->orders->first();

        if($ordr && $ordr instanceof Order) {
            $installerName = $ordr->installer_name;
        } else {
            $installerName = '';
        }
		$order->delivery_at = date('d.m.Y');

        $map = [
            'CONTRACT_NUM' => $id . '/' . date('my', $contractDate),
            'YEAR' => date('Y', $contractDate),
            'MONTH' => (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY),
            'DAY' => date('d', $contractDate),
            'SPEC_NAME' => $order->title,
//            'CLIENT_NAME' => $order->client->first_name,
//            'CLIENT_SURNAME' => $order->client->last_name,
//            'CLIENT_SECONDNAME' => $order->client->second_name,
			'CLIENT_PRE' => 'гражданин(ка) РФ ',
			'CLIENT_POST' => '',
			'CLIENT' => $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name,
			'DELIVERY_YEAR' => date('Y', strtotime($order->delivery_at)),
			'DELIVERY_MONTH' => (new Date)->getRusMoth(date('m', strtotime($order->delivery_at)), Date::CASE_RODITELNIY),
			'DELIVERY_DAY' => date('d', strtotime($order->delivery_at)),
			'DS' => date('d', $contractDate) . ' ' . (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY) . ' ' . date('Y', $contractDate),
            'DD' => date('d', $deliveryDate) . ' ' . (new Date)->getRusMoth(date('m', $deliveryDate), Date::CASE_RODITELNIY) . ' ' . date('Y', $deliveryDate),
            'DM' => date('d', $installDate) . ' ' . (new Date)->getRusMoth(date('m', $installDate), Date::CASE_RODITELNIY) . ' ' . date('Y', $installDate),
            'INSTALLER' => $installerName,
            'INSTALL_ADDRESS' => $order->client->delivery_address,
            'SUM_INSTALL' => number_format($costs->install, 0, '.', ' '),
        ];

		if($order->client->type == 2) {
			$map['CLIENT_PRE'] = '';
			$map['CLIENT'] = $order->client->legal_name;
			$map['CLIENT_POST'] = ', в лице Генерального директора ' . $order->client->director_name . ', действующего на основании Устава';
		}

        $word = new TemplateProcessor(app_path() . '/docrender/work.docx');

        foreach ($map as $search => $replace) {
            $word->setValue($search, $replace);
        }

        $fileName = 'Акт выполненных работ ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: inline; filename={$fileName}");

        $word->saveAs('php://output');
        //$word->saveAs(app_path() . '/docrender/' . $fileName);
        die();
    }

	/**
	 * Генерация Доп.соглашения
	 * @param $id
	 */
	public function addagree($id)
	{
		$data = $this->repository->calculateForClient($id);

		/** @var Calculation $order */
		$order = $data['order'];

		/** @var CalculationWrapper $costs */
		$costs = $data['costs'];

		/** @var SubjectWrapper[] $subjects */
		$subjects = $data['subjects'];

		/** @var Calculation $parentOrder */
		$parentOrder = $data['parentOrder'];
		if(!$parentOrder->contract_at){
			$contractDate = strtotime($parentOrder->created_at);
		} else{
		$contractDate = strtotime($parentOrder->contract_at);
		}
		$nowDate = strtotime(date('d.m.Y'));

		$price = $costs->total;
		if ($order->pseudo_discount_percent || $order->pseudo_discount_meter)
			$price = $costs->totalDiscount;
		$fullprise = $price + $costs->install + $costs->delivery + $costs->climb_price;

		$map = [
			'CONTRACT_NUM' => $parentOrder->id . '/' . date('my', $contractDate),
			'CONTRACT_YEAR' => date('Y', $contractDate),
			'YEAR' => date('Y', $nowDate),
			'CONTRACT_MONTH' => (new Date)->getRusMoth(date('m', $contractDate), Date::CASE_RODITELNIY),
			'MONTH' => (new Date)->getRusMoth(date('m', $nowDate), Date::CASE_RODITELNIY),
			'CONTRACT_DAY' => date('d', $contractDate),
			'DAY' => date('d', $nowDate),
//			'CLIENT_NAME' => $order->client->first_name,
//			'CLIENT_SURNAME' => $order->client->last_name,
//			'CLIENT_SECONDNAME' => $order->client->second_name,
			'CLIENT_PRE' => 'гражданин(ка) РФ ',
			'CLIENT_POST' => '',
			'CLIENT' => $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name,
			'CLIENT_PHONE' => $order->client->phone,
			'CLIENT_EMAIL' => $order->client->email,
			'SUM_FULL' => number_format($fullprise , 0, '.', ' '),
			'SUM_FULL_TEXT' => (new StringHelper())->firstUpper((new Price)->getText(round ($fullprise))),
			'BIRTHDAY' => $order->client->birthday,
			'PASSPORT_SERIES' => $order->client->passport_series,
			'PASSPORT_NUMBER' => $order->client->passport_number,
			'PASSPORT_ISSUED_BY' => $order->client->passport_issued_by,
			'PASSPORT_ISSUED_CODE' => $order->client->passport_issued_code,
			'PASSPORT_ISSUED_DATE' => $order->client->passport_issued_date,
			'PASSPORT_ADDRESS' => $order->client->passport_address,
			'CLIENT_DETAILS' =>
				'ФИО ' . $order->client->last_name .' '. $order->client->first_name .' '. $order->client->second_name .'<w:br />'.
				'Паспортные данные: серия '.$order->client->passport_series.' номер '.$order->client->passport_number . '<w:br />' .
				'Кем и когда выдан: ' . $order->client->passport_issued_by . '<w:br />' .
				'Дата выдачи: ' . $order->client->passport_issued_date . '<w:br />' .
				'Адрес места жительства (по паспорту): ' . $order->client->passport_address . '<w:br />' .
				'Код подразделения: ' . $order->client->passport_issued_code . '<w:br />' .
				'Дата рождения: ' . $order->client->birthday
		];

		if($order->client->type == 2){
			$map['CLIENT_PRE'] = '';
			$map['CLIENT'] = $order->client->legal_name;
			$map['CLIENT_POST'] = ', в лице Генерального директора ' . $order->client->director_name . ', действующего на основании Устава';
			$map['CLIENT_DETAILS'] =
				$order->client->legal_name .'<w:br />'.
				'ИНН / КПП: '.$order->client->inn.' / '.$order->client->kpp . '<w:br />' .
				'Юридический адрес: ' . $order->client->legal_address . '<w:br />' .
				'Почтовый адрес: ' . $order->client->mail_address . '<w:br />' .
				'Наименование банка: ' . $order->client->bank_name . '<w:br />' .
				'Расчетный счет: ' . $order->client->settlement_account . '<w:br />' .
				'Корреспондентский счет: ' . $order->client->correspondent_account . '<w:br />' .
				'БИК: ' . $order->client->bik . '<w:br />' .
				'ОКПО: ' . $order->client->okpo . '<w:br />' .
				'ОКАТО: ' . $order->client->okato . '<w:br />' .
				'ОКТМО: ' . $order->client->oktmo . '<w:br />' .
				'ОКВЭД: ' . $order->client->okved . '<w:br />' .
				'ОГРН: ' . $order->client->ogrn . '<w:br />' .
				'Телефон: ' . $order->client->legal_phone . '<w:br />' .
				'E-mail: ' . $order->client->legal_email . '<w:br />' .
				'Генеральный директор: ' . $order->client->director_name;
		}

		$word = new TemplateProcessor(app_path() . '/docrender/addagree.docx');

		foreach ($map as $search => $replace) {
			$word->setValue($search, $replace);
		}

		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
		$table = $section->addTable([
			'borderColor' => '000000',
			'borderSize' => 6,
			'width' => 100,
		]);

		$titleWidth = Converter::pixelToTwip(230);
		$priceWidth = Converter::pixelToTwip(65);

		$headTextStyle = [
			'bold' => true,
			'name' => 'Times New Roman',
			'size' => 10,
		];

		$priceTextStyle = [
			'name' => 'Times New Roman',
			'size' => 10.5,
			'align' => 'right',
		];

		$headCellStyle = [
			'valign' => 'top',
		];

		$priceCellStyle = [
			'valign' => 'center',
		];

		$table->addRow();

		$table
			->addCell(null, $headCellStyle)
			->addText('№', $headTextStyle);

		$table
			->addCell($titleWidth, array_merge($headCellStyle, ['width' => $titleWidth]))
			->addText('Наименование', $headTextStyle);

		$table
			->addCell(null, $headCellStyle)
			->addText('Кол-во', $headTextStyle);

		$table
			->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
			->addText('Фасад', $headTextStyle);

		$table
			->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
			->addText('Корпус', $headTextStyle);

		$table
			->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
			->addText('Фурни-<w:br />тура', $headTextStyle);

		$table
			->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
			->addText('Проекти-<w:br />рование', $headTextStyle);

		$table
			->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
			->addText('Стои-<w:br />мость', $headTextStyle);

		if ($order->pseudo_discount_percent) {
			$table
				->addCell($priceWidth, array_merge($headCellStyle, ['width' => $priceWidth]))
//				->addText("Со<w:br />скидкой<w:br />({$order->pseudo_discount_percent}%)", $headTextStyle);
				->addText("Со скидкой ({$order->getPseudoDiscountPercentTextAttribute()})", $headTextStyle);
		}

		foreach ($subjects as $subject) {
			$table->addRow();

			$table
				->addCell(null, $priceCellStyle)
				->addText($subject->i, $priceTextStyle);

			$table
				->addCell($titleWidth, array_merge($priceCellStyle, ['width' => $titleWidth]))
				->addText($subject->title . '<w:br />' . "({$subject->x} × {$subject->y} × {$subject->z} мм)", $priceTextStyle);

			$table
				->addCell(null, $priceCellStyle)
				->addText($subject->num, $priceTextStyle);

			$table
				->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
				->addText((new Price)->priceFormat($subject->facade), $priceTextStyle);

			$table
				->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
				->addText((new Price)->priceFormat($subject->skeleton), $priceTextStyle);

			$table
				->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
				->addText((new Price)->priceFormat($subject->furniture), $priceTextStyle);

			$table
				->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
				->addText((new Price)->priceFormat($subject->construct_assembly), $priceTextStyle);

			$table
				->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
				->addText((new Price)->priceFormat($subject->total), $priceTextStyle);


			if ($order->pseudo_discount_percent) {
				$table
					->addCell($priceWidth, array_merge($priceCellStyle, ['width' => $priceWidth]))
					->addText((new Price)->priceFormat($subject->totalDiscount), $priceTextStyle);
			}
		}

		if ($order->pseudo_discount_meter) {
			$table->addRow();

			$table
				->addCell(null, $priceCellStyle)
				->addText('<w:br />', $priceTextStyle);

			$table
				->addCell($titleWidth, array_merge($headCellStyle, ['width' => $titleWidth]));

			$table
				->addCell(null, $priceCellStyle);

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => 4,
						]
					)
				)
				->addText('Скидка за замер:', $headTextStyle);

			if ($order->pseudo_discount_percent) {
				$table
					->addCell(null, $priceCellStyle);
			}

			$table
				->addCell(null, $priceCellStyle)
				->addText('-' . (new Price)->priceFormat($order->pseudo_discount_meter), $headTextStyle);
		}

		$table->addRow();

		$table
			->addCell(null, $priceCellStyle)
			->addText('<w:br />', $priceTextStyle);

		$table
			->addCell($titleWidth, array_merge($priceCellStyle, ['width' => $titleWidth]))
			->addText('Всего предметов:', $headTextStyle);

		$table
			->addCell(null, $priceCellStyle)
			->addText($costs->num, $priceTextStyle);

		$table
			->addCell(
				null,
				array_merge(
					$priceCellStyle, [
						'gridSpan' => 4,
					]
				)
			)
			->addText('Общая стоимость предметов:', $headTextStyle);

		$table
			->addCell(null, $priceCellStyle)
			->addText((new Price)->priceFormat($costs->total), $headTextStyle);

		if ($order->pseudo_discount_percent || $order->pseudo_discount_meter) {
			$table
				->addCell(null, $priceCellStyle)
				->addText((new Price)->priceFormat($costs->totalDiscount), $headTextStyle);
		}

		if ($order->delivery) {
			$table->addRow();

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => 3,
						]
					)
				)
				->addText('<w:br />', $priceTextStyle);

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
						]
					)
				)
				->addText('Стоимость доставки:', $priceTextStyle);

			$table
				->addCell(null, $priceCellStyle)
				->addText((new Price)->priceFormat($order->delivery), $priceTextStyle);

		}

		if ($order->climb_price) {
			$table->addRow();

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => 3,
						]
					)
				)
				->addText('<w:br />', $priceTextStyle);

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
						]
					)
				)
				->addText('Стоимость подъёма:', $priceTextStyle);

			$table
				->addCell(null, $priceCellStyle)
				->addText((new Price)->priceFormat($order->climb_price), $priceTextStyle);
		}

		if ($order->install) {
			$table->addRow();

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => 3,
						]
					)
				)
				->addText('<w:br />', $priceTextStyle);

			$table
				->addCell(
					null,
					array_merge(
						$priceCellStyle, [
							'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
						]
					)
				)
				->addText('Стоимость установки:', $priceTextStyle);

			$table
				->addCell(null, $priceCellStyle)
				->addText((new Price)->priceFormat($order->install), $priceTextStyle);
		}

		$table->addRow();

		$table
			->addCell(
				null,
				array_merge(
					$priceCellStyle, [
						'gridSpan' => 3,
					]
				)
			)
			->addText('<w:br />', $priceTextStyle);

		$table
			->addCell(
				null,
				array_merge(
					$priceCellStyle, [
						'gridSpan' => ($order->pseudo_discount_percent ? 5 : 4),
					]
				)
			)
			->addText('Итоговая стоимость:', $headTextStyle);

		$table
			->addCell(null, $priceCellStyle)
			->addText((new Price)->priceFormat($costs->totalWithInstallAndDelivery()), $headTextStyle);

		/** @var Word2007 $objWriter */
		$objWriter = new Word2007($phpWord);
		/** @var Word2007\Part\Document $tableStr */
		$tableStr = $objWriter->getWriterPart('Document')->write($table);

		$tableStr = substr($tableStr, strpos($tableStr, '<w:tbl>'));
		$tableStr = substr($tableStr, 0, strpos($tableStr, '<w:sectPr>'));

		$word->setValue('TABLE', $tableStr);

		$fileName = 'Дополнительное соглашение к Договору ' . str_replace('/', '-', $map['CONTRACT_NUM']) . '.docx';

		ob_clean();
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: inline; filename={$fileName}");

		$word->saveAs('php://output');
		//$word->saveAs(app_path() . '/docrender/' . $fileName);
		die();
	}

}
