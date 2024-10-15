<?php

namespace app\common\repositories\ai\tools;

use app\common\dao\ai\tools\ExtractCopyDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;

class ExtractCopyRepository extends BaseRepository
{
    /**
     * @var ExtractCopyDao
     */
    public $dao;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
    }

    public function byUrl($data)
    {
        $toolsRepository = app()->make(ToolsRepository::class);
        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = $toolsRepository->getIntegralRequire($data['url'], $data['platform']);

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $data['integral'] = $integral;

        $contentRes = $toolsRepository->extractContent($data);
        $data['content'] = $contentRes['text'];
        $data['create_time'] = date('Y-m-d H:i:s');

        $data = $this->dao->create($data);

        $res = [
            'id' => $data[$this->dao->getPk()],
            'content' => $data['content'],
            'used' => $data['integral'],
            'remain' => $remain,
            'expires' => $expires,
        ];

        return $res;
    }
}
