<?php

namespace Sienekib\Mehael\Http;

use Sienekib\Mehael\Http\Files\File;
use Sienekib\Mehael\Http\Src\Uri;
use Sienekib\Mehael\Http\Json\Jsonable;

class Request extends Response
{
    use Uri, Jsonable, File;

    private array $data = [];

    public function __construct()
    {
        $this->jsonable();
        $this->renderRequestedData();
    }

    public function jsonable()
    {
        $data = $this->get_contents() ?? [];

        foreach ($data as $key => $value) {
            $countable = (is_object($value)) ? $value : [$key => $value];
            foreach ($countable as $index => $val) {
                $this->data[$index] = $val;
            }
        }
    }

    /*public function base64File(string $filename)
    {
        if ($this->$filename !== null) {
            $base64 = $this->$filename;
            $file = explode('base64', $base64)[1];
            return base64_decode($file);
        }
        return null;
    }*/

    public function base64File(string $filename)
    {
        if ($this->$filename !== null) {
            $base64 = $this->$filename;
            // Remova a parte 'data:application/zip;base64,' do início da string
            $file = preg_replace('/^data:application\/zip;base64,/', '', $base64);
            return base64_decode($file);
        }
        return null;
    }

    public function base64FileExtension(string $filename, $type = 'img')
    {
        if (!str_contains($this->$filename, '.')) {
            return 'Invalid filename';
        }

        $extension = explode('.', $this->$filename)[1];

        if ($type == 'zip') {

            return $extension;
        }

        return $this->matchExtension($extension, $type) ? $extension : null;
    }

    private function matchExtension($ext, $type)
    {
        $ext = strtolower($ext);

        if ($type == 'img') {
            return $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'webp';
        }


        if ($type == 'video') {
            return $ext == 'mp4' || $ext == 'mkv' || $ext == 'moov';
        }
    }

    public function all()
    {
        return (object) $this->data;
    }

    public function bind(array $request)
    {
        if ($this->method() == 'GET') {
            $_GET = $request;
        }

        if ($this->method() == 'POST') {
            $_POST = $request;
        }

        $this->renderRequestedData();
    }

    private function renderRequestedData()
    {
        // Adicione os cabeçalhos CORS permitindo solicitações de qualquer origem
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        // header("Access-Control-Allow-Credentials: true");

        $this->setHeader('Access-Control-Allow-Origin', '*');
        $this->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $this->setHeader('Access-Control-Allow-Headers', 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        $this->setHeader('Access-Control-Allow-Credentials', 'true');
        $this->sendHeaders();

        if ($this->method() == 'GET') {
            foreach ($_GET as $key => $value) {
                $this->data[$key] = ($this->isXmlHttpRequest()) ? $value : strip_tags($value);
            }
        }

        if ($this->method() == 'POST') {
            foreach ($_POST as $key => $value) {
                $this->data[$key] = ($this->isXmlHttpRequest()) ? $value : strip_tags($value);
            }
        }

        return $this->data;
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    private function isXmlHttpRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
