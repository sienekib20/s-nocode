<?php

namespace Sienekib\Mehael\Http\Files;

trait File
{
    /**
     * Get the file array
     *
     * @param string $name
     * @return array|null
     */
    public function file(string $name): array|null
    {
        return $_FILES[$name] ?? null;
    }

    private function fileNameExtenstion(string $name): array
    {
        $file = $this->file($name)['name'] ?? null;

        if (!is_null($file)) {
            return str_contains($file, '.')
                ? explode('.', $file)
                : 'Invalid filename';
        }

        return [];
    }

    /**
     * Get the file name
     *
     * @param string $name
     * @return string|null
     */
    public function fileOriginalName(string $name): string|null
    {
        return $this->fileNameExtenstion($name)[0] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return string|null
     */
    public function fileExtension(string $name): string|null
    {
        //return $this->fileNameExtenstion($name)[1] ?? null;
        return pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return string|null
     */
    public function fileTempName(string $name): string|null
    {
        return $this->file($name)['tmp_name'] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return string|null
     */
    public function fileFullPath(string $name): string|null
    {
        return $this->file($name)['full_path'] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return string|null
     */
    public function fileType(string $name): string|null
    {
        return $this->file($name)['type'] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return integer|null
     */
    public function fileSize(string $name): int|null
    {
        return $this->file($name)['size'] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return integer|null
     */
    public function fileError(string $name): int|null
    {
        return $this->file($name)['error'] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @return boolean
     */
    public function fileHas(string $key): bool
    {
        return isset($_FILES[$key]);
    }
}
