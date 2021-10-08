<?php

namespace App\Business;

use App\Common\ApiResult;
use App\DAL\TestDAL;

class TestBus extends BaseBus
{
    private $testDAL;

    public function __construct()
    {
        $this->testDAL = new TestDAL();
    }

    public function getTestDAL()
    {
        return $this->testDAL;
    }



    public function insert($testForm)
    {
        $apiResult = new ApiResult();
        if ((int) $testForm['no_of_questions'] == count($testForm['question_ids'])) {
            $apiResult = $this->getTestDAL()->insert($testForm);
            $testContentBus = new TestContentBus();
            $apiResult->insertTestContent = $testContentBus->insertMany($apiResult->testId, 0, $testForm['question_ids']);
        } else {
            $apiResult->fill(1, 'Chưa nhập đủ số câu hỏi');
        }
        return $apiResult;
    }

    public function getAll()
    {
        return $this->getTestDAL()->getAll();
    }

    public function getById($testId)
    {
        return $this->getTestDAL()->getById($testId);
    }

    public function getInfoOnly($testId)
    {
        return $this->getTestDAL()->getInfoOnly($testId);
    }

    public function getAllInfo()
    {
        return $this->getTestDAL()->getAllInfo();
    }

    public function getTestForStudent($testId)
    {
        $apiResult = $this->getById($testId);

        foreach ($apiResult->test->questions as $question) {
            foreach ($question->choices as $choice) {
                $choice->is_solution = null;
            }
        }
        return $apiResult;
    }
}
