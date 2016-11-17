<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * The file and line of an issue
 */
class IssueLocation
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var int
     */
    protected $lineStart;

    /**
     * @param string $filePath
     * @param int $lineStart
     */
    public function __construct($filePath, $lineStart)
    {
        $this->filePath = $filePath;
        $this->lineStart = $lineStart;
    }

    /**
     * Get path to the file + filename
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return basename($this->filePath);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'path' => $this->filePath,
            'lines' => [
                'begin' => $this->lineStart,
                'end' => $this->lineStart,
            ]
        ];
    }

    /**
     * @return int
     */
    public function getLineStart()
    {
        return $this->lineStart;
    }
}
