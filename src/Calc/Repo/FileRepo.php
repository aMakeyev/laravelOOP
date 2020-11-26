<?php namespace Calc\Repo;

use Config;
use Exception;
use File as FileManager;

class FileRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\File';

    /**
     * @param $fileableType
     * @param $fileableId
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile$file
     *
     * @return null
     *
     * @throws \Exception
     */
    public function upload($fileableType, $fileableId, $file)
    {
        $fileables = Config::get('calc::app.fileables');
        $fileableClass = $fileables[$fileableType];

        $fileable = $fileableClass::findOrFail($fileableId);

        if (method_exists($fileable, 'canEdit'))
        {
            if ( ! $fileable->canEdit())
            {
                throw new Exception('Редактирование запрещено!');
            }
        }

        $path = public_path("files/{$fileableType}/{$fileableId}");
        if ( ! FileManager::isDirectory($path))
        {
            FileManager::makeDirectory($path, 0755, true);
        }

        $extesion = $file->guessExtension();
/*
        $allowedExtensions = \Config::get('calc::app.allowedExtensions');

        if ( ! in_array($extesion, $allowedExtensions))
        {
            throw new Exception('Запрещенный тип файла!');
        }
*/
        $newFileName = uniqid() . '.'  . $file->guessExtension();

        $obj = new $this->modelClassName;
        $obj->src = "/files/{$fileableType}/{$fileableId}/{$newFileName}";
        $obj->name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $extesion;

        $file->move($path, $newFileName);

        return $fileable->files()->save($obj) ? $obj : null;
    }
}
