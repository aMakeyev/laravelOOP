<?php namespace Calc\Controller\Api;

use Input;

class FilesController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\FileRepo';
    /**
     * @var \Calc\Repo\FileRepo
     */
    protected $repository;

    /**
     * Загрузка пользовательских файлов на сервер
     *
     * @param string $fileableType
     * @param int $fileableId
     * @return \Calc\Helpers\Response
     */
    public function upload($fileableType, $fileableId)
    {
        try
        {
            $obj = $this->repository->upload($fileableType, $fileableId, Input::file('file'));
        }
        catch (\Exception $e)
        {
            return $this->response->error($e->getMessage());
        }

        if ($obj === null)
        {
            return $this->response->error('Неизвестная ошибка');
        }

        return $this->response->message("Файл \"{$obj->name}\" успешно загружен")->data([
            'file' => $obj->toArray()
        ]);
    }

    /**
     * Удаление файлов
     *
     * @param int $id
     * @return \Calc\Helpers\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $file = $this->repository->find($id);

        $fileables = \Config::get('calc::app.fileables');
        $fileableClass = $fileables[strtolower($file->fileable_type)];

        $fileable = $fileableClass::findOrFail($file->fileable_id);

        if (method_exists($fileable, 'canEdit'))
        {
            if ( ! $fileable->canEdit())
            {
                return $this->response->error('Редактирование запрещено!');
            }
        }

        if ( ! $file->delete($id))
        {
            return $this->response->message('Ошибка удаления файла');
        }

        return $this->response->message('Файл успешно удален');
    }
}
